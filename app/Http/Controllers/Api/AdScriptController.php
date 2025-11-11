<?php

declare(strict_types=1);

namespace N8nAutomation\Http\Controllers\Api;

use Exception;
use Illuminate\Http\JsonResponse;
use N8nAutomation\Contracts\AdScriptManagerInterface;
use N8nAutomation\Dtos\AdScriptDto;
use N8nAutomation\Enums\AdScriptStatus;
use N8nAutomation\Http\Requests\Api\AdScriptResultRequest;
use N8nAutomation\Http\Requests\Api\StoreAdScriptRequest;
use N8nAutomation\Models\AdScript;

class AdScriptController extends AbstractApiController
{
    public function __construct(protected readonly AdScriptManagerInterface $adScriptTaskManager)
    {
    }

    public function store(StoreAdScriptRequest $request): JsonResponse
    {
        $dto = new AdScriptDto(
            $request->getReferenceScript(),
            $request->getOutcomeDescription(),
            AdScriptStatus::PENDING

        );

        try {
            $data = $this->adScriptTaskManager->store($dto);

            return $this->successResponse($data, 'Ad script task created successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function storeResults(AdScriptResultRequest $request): JsonResponse
    {
        $adScript = AdScript::findOrFail($request->getTaskId());

        try {
            $data = $this->adScriptTaskManager->storeResult(
                $adScript,
                $request->getNewScript(),
                $request->getAnalysis()
            );


            return $this->successResponse($data, 'Ad script task result added successfully');
        } catch (Exception $e) {
            $this->adScriptTaskManager->markAsFailed($adScript, $e->getMessage());

            return $this->errorResponse($e->getMessage());
        }
    }
}
