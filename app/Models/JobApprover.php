<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class JobApprover extends Model
{
    use HasRoles;

    protected $guarded = [];
    
    public function approver() 
    {
        return $this->belongsTo(UserRole::class, 'user_role_id');
    }

    public function jobOrderStatusType() 
    {
        return $this->belongsTo(JobOrderStatusType::class, 'job_order_status_type_id');
    }

    public function department() 
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
