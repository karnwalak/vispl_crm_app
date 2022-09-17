<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadData extends Model
{
    protected $table ="leads";

    public $timestamps = false;

    public function activity() {
       return $this->hasOne(ActivityData::class, 'leadid');
    }

    public function salesPerson() {
        return $this->belongsTo(User::class, "sales_person");
    }

    public function contactPerson() {
        return $this->belongsTo(User::class, "created_by");
    }

    public function designation_data() {
        return $this->belongsTo(Designation::class, "designation");
    }

    public function department_data() {
        return $this->belongsTo(DepartmentData::class, 'department');
    }

    public function product_data() {
        return $this->belongsTo(ProductType::class, 'product_type');
    }

    public function subProduct(){
        return $this->belongsTo(ProductType::class, 'sub_product');
    }
}
