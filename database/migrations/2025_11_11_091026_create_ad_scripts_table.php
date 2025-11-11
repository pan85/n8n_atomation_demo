<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use N8nAutomation\Enums\AdScriptStatus;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ad_script_tasks', function (Blueprint $table) {
            $table->id();
            $table->mediumText('reference_script');
            $table->text('outcome_description');
            $table->longText('new_script')->nullable();
            $table->longText('analysis')->nullable();
            $table->tinyInteger('status')->default(AdScriptStatus::PENDING);
            $table->text('error_message')->nullable();
            $table->timestamps();
        });

        // Add check constraint after table creation
        DB::statement(
            sprintf(
                'ALTER TABLE ad_script_tasks ADD CONSTRAINT chk_status CHECK (status IN (%s))',
                join(',', AdScriptStatus::getValues())
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_scripts');
    }
};
