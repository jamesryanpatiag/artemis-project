<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApprover extends Model
{
    protected $guarded = [];
    
    public function approver() 
    {
        return $this->belongsTo(UserRole::class, 'user_role_id');
    }

    public function jobOrderStatusType() 
    {
        return $this->belongsTo(JobOrderStatusType::class, 'job_order_status_type_id');
    }
}
