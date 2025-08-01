<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\JobOrderStatusType;

class JobOrderStatusTypeStep extends Model
{
    protected $guarded = [];

    public function parentJobStatusType() {
        return $this->belongsTo(JobOrderStatusType::class, 'parent_id_job_status_type_id');
    }

    public function childJobStatusType() {
        return $this->belongsTo(JobOrderStatusType::class, 'child_job_status_type_id');
    }
}
