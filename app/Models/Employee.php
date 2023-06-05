<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'dept'];

    public function user()
    {
        return $this->hasOne(User::class, 'employee_id');
    }

    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

    public function getDeptAttribute($value)
    {
        return ucwords($value);
    }
}
