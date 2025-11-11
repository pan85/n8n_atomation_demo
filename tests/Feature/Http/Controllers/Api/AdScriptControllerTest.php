<?php

declare(strict_types=1);

namespace Feature\Http\Controllers\Api;

use Exception;
use Mockery;
use N8nAutomation\Contracts\AdScriptManagerInterface;
use N8nAutomation\Dtos\AdScriptDto;
use N8nAutomation\Enums\AdScriptStatus;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\BaseTest;

class AdScriptControllerTest extends BaseTest
{
    protected AdScriptManagerInterface $adScriptManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adScriptManager = Mockery::mock(AdScriptManagerInterface::class);
    }

    public function testStoreAdScriptSuccessfully(): void
    {
        $requestData = [
            'reference_script' => 'Test reference script',
            'outcome_description' => 'Test outcome description',
        ];

        $response = $this->postJson(route('ad-scripts.store'), $requestData, [
            'Authorization' => sprintf('Bearer %s', env('STORE_BEARER_TOKEN')),
        ]);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonStructure([
                     'message',
                     'data' => [
                         'reference_script',
                         'outcome_description',
                         'status',
                     ],
                 ]);

        $this->assertDatabaseHas('ad_script_tasks', [
            'reference_script' => $requestData['reference_script'],
            'outcome_description' => $requestData['outcome_description'],
            'status' => AdScriptStatus::PENDING,
        ]);
    }

    public function testStoreAdScriptFails(): void
    {
        $requestData = [
            'reference_script' => 'Test reference script',
            'outcome_description' => 'Test outcome description',
        ];

        $this->app->instance(AdScriptManagerInterface::class, $this->adScriptManager);
        $this->adScriptManager
            ->expects('store')
            ->with(Mockery::type(AdScriptDto::class))
            ->andThrow(new Exception('Failed to create ad script'));

        $response = $this->postJson(route('ad-scripts.store'), $requestData, [
            'Authorization' => sprintf('Bearer %s', env('STORE_BEARER_TOKEN')),
        ]);

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
                 ->assertJson([
                     'message' => 'Failed to create ad script',
                 ]);
    }

    public function testStoreAdScriptValidationError(): void
    {
        $response = $this->postJson(route('ad-scripts.store', []), [], [
            'Authorization' => sprintf('Bearer %s', env('STORE_BEARER_TOKEN')),
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                     'reference_script',
                     'outcome_description',
                 ]);
    }

    public function testStoreAdScriptNoAccess(): void
    {
        $response = $this->postJson(route('ad-scripts.store', []), []);

        $response->assertStatus(403);
    }
}
