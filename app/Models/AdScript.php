<?php

declare(strict_types=1);

namespace N8nAutomation\Models;

use Database\Factories\AdScriptFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use N8nAutomation\Enums\AdScriptStatus;

class AdScript extends Model
{
    protected $table = 'ad_script_tasks';

    /** @use HasFactory<AdScriptFactory> */
    use HasFactory;

    protected $fillable = [
        'reference_script',
        'outcome_description',
        'new_script',
        'analysis',
        'status',
        'error_message',
    ];

    protected $casts = [
        'status' => AdScriptStatus::class,
    ];
}
