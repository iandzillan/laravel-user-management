<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequisition extends Model
{
    use HasFactory;

    protected $fillable = ['pr_number', 'employee_id', 'due_date', 'status', 'sub_qty'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_id');
    }
}
