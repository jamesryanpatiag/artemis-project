<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\JobOrderStatusType;
use App\Models\JobOrderServiceLabor;
use App\Models\JobOrderPartsMaterial;
use App\Models\JobOrderDocument;

class JobOrder extends Model
{
    protected $guarded = [];

    public function customer() 
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function jobOrderStatusType() 
    {
        return $this->belongsTo(JobOrderStatusType::class, 'job_order_status_type_id');
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
}
