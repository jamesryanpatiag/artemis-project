<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\JobOrderStatusType;

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
}
