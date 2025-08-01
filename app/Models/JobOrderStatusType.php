<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\JobOrderStatusTypeStep;


class JobOrderStatusType extends Model
{
    protected $guarded = [];

    public function jobOrderStatusTypeSteps() {
        return $this->hasMany(JobOrderStatusTypeStep::class, 'parent_id_job_status_type_id', 'id');
    }
}
