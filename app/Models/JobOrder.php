<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\JobOrderStatusType;
use App\Models\JobOrderServiceLabor;
use App\Models\JobOrderPartsMaterial;
use App\Models\JobOrderDocument;
use App\Models\PriorityStatus;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Traits\HasActivity;
use Spatie\Activitylog\LogOptions;

class JobOrder extends Model
{
    use LogsActivity;

    protected $guarded = [];

    public function customer() 
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function jobOrderStatusType() 
    {
        return $this->belongsTo(JobOrderStatusType::class, 'job_order_status_type_id');
    }

    public function priorityStatus() 
    {
        return $this->belongsTo(PriorityStatus::class, 'priority_status_id');
    }

    public function assignedDepartment() 
    {
        return $this->belongsTo(Department::class, 'assigned_department_id');
    }

    public function jobOrderServiceLabors() {
        return $this->hasMany(JobOrderServiceLabor::class, 'job_order_id', 'id');
    }

    public function jobOrderPartsMaterials() {
        return $this->hasMany(JobOrderPartsMaterial::class, 'job_order_id', 'id');
    }

    public function jobOrderDocuments() {
        return $this->hasMany(JobOrderDocument::class, 'job_order_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
                ->logOnly(['*'])
                ->logExcept(['updated_at', 'created_at'])
                ->logOnlyDirty();
    }
}