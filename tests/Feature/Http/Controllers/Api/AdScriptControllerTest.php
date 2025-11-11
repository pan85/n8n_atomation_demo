<?php

declare(strict_types=1);

namespace Feature\Http\Controllers\Api;

use Exception;
use Mockery;
use N8nAutomation\Contracts\AdScriptManagerInterface;
use N8nAutomation\Dtos\AdScriptDto;
use N8nAutomation\Enums\AdScriptStatus;
use N8nAutomation\Models\AdScript;
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
                 ->assertJsonFragments([
                     'data' => [
                         'reference_script' => 'Test reference script',
                         'outcome_description' => 'Test outcome description',
                         'status' => AdScriptStatus::PENDING->value,
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

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
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

    public function testStoreResultSuccessfully(): void
    {
        $adScript = AdScript::factory()
                            ->setReferenceScript('Test reference script')
                            ->create();
        $requestData = [
            'task_id' => $adScript->getKey(),
            'new_script' => 'Test new script',
            'analysis' => 'Test analysis',
        ];

        $response = $this->postJson(route('ad-scripts.results', ['id' => $adScript->getKey()]), $requestData, [
            'Authorization' => sprintf('Bearer %s', env('STORE_BEARER_TOKEN')),
        ]);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonFragments([
                     'data' => [
                         'new_script' => $requestData['new_script'],
                         'analysis' => $requestData['analysis'],
                         'status' => AdScriptStatus::COMPLETED,
                     ],
                 ]);

        $this->assertDatabaseHas('ad_script_tasks', [
            'new_script' => $requestData['new_script'],
            'analysis' => $requestData['analysis'],
            'status' => AdScriptStatus::COMPLETED,
        ]);
    }

    public function testStoreResultValidationFails(): void
    {
        $adScript = AdScript::factory()
                            ->setReferenceScript('Test reference script')
                            ->create();
        $notExistingId = $adScript->getKey() + 1;

        $requestData = [
            'task_id' => $notExistingId,
            'new_script' => 'Test new script',
            'analysis' => 'Test analysis',
        ];

        $response = $this->postJson(route('ad-scripts.results', ['id' => $notExistingId]), $requestData, [
            'Authorization' => sprintf('Bearer %s', env('STORE_BEARER_TOKEN')),
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testStoreResultFailureStore(): void
    {
        $adScript = AdScript::factory()
                            ->setReferenceScript('Test reference script')
                            ->create();

        $requestData = [
            'task_id' => $adScript->getKey(),
            'new_script' => 'Test new script',
            'analysis' => 'Test analysis',
        ];

        $this->app->instance(AdScriptManagerInterface::class, $this->adScriptManager);
        $this->adScriptManager
            ->expects('storeResult')
            ->andThrow(new Exception('Failed!!'));

        $response = $this->postJson(route('ad-scripts.results', ['id' => $adScript->getKey()]), $requestData, [
            'Authorization' => sprintf('Bearer %s', env('STORE_BEARER_TOKEN')),
        ]);

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);

        $this->assertDatabaseHas('ad_script_tasks', [
            'new_script' => null,
            'analysis' => null,
            'status' => AdScriptStatus::FAILED,
        ]);
    }
}
