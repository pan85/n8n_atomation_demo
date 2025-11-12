<?php

declare(strict_types=1);

namespace N8nAutomation\Jobs;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use N8nAutomation\Enums\AdScriptStatus;
use N8nAutomation\Models\AdScript;
use N8nAutomation\Services\N8nClientService;

class SendAdScriptForAnalysisJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 60;

    public int $backoff = 30;

    public ?AdScript $adScript = null;

    public function __construct(protected readonly int $id)
    {
        $this->adScript = AdScript::find($this->id);
    }

    public function handle(N8nClientService $n8nClient): void
    {
        try {
            if ($this->adScript === null) {
                throw new Exception(sprintf('AdScript with ID %d not found', $this->id));
            }
            $data = $this->getData($n8nClient);
        } catch (\Throwable $e) {
            $data = [
                'status' => AdScriptStatus::FAILED,
                'error_message' => $e->getMessage(),
            ];
        }

        $this->adScript->update($data);
    }

    public function failed(\Throwable $exception): void
    {
        if ($this->adScript && $this->adScript->status !== AdScriptStatus::COMPLETED) {
            $this->adScript->update([
                'status' => AdScriptStatus::FAILED,
                'error_message' => sprintf('%s: %s', 'Max retries reached: ', $exception->getMessage()),
            ]);
            //        Notification::route('slack', env('SLACK_ALERT_WEBHOOK'))
            //          ->notify(new JobFailedNotification($exception));
        }
    }

    /**
     * @param  N8nClientService  $n8nClient
     * @return array
     * @throws Exception
     */
    private function getData(N8nClientService $n8nClient): array
    {
        $data = $n8nClient->post(config('services.n8n_ad_script.webhook_url'), [
            'reference_script' => $this->adScript->reference_script,
            'outcome_description' => $this->adScript->outcome_description,
            'task_id' => $this->adScript->getKey(),
        ]);

        if ((int)$data['task_id'] !== $this->adScript->getKey()) {
            throw new Exception('Task ID mismatch');
        }

        return $data;
    }
}
