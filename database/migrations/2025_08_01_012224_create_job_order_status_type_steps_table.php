<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\JobOrderStatusType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_order_status_type_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(JobOrderStatusType::class, 'parent_id_job_status_type_id');
            $table->foreignIdFor(JobOrderStatusType::class, 'child_job_status_type_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_order_status_type_steps');
    }
};
