<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uom extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'unit'];

    public function getCodeAttribute($value)
    {
        return strtoupper($value);
    }

    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

    public function getUnitAttribute($value)
    {
        return strtoupper($value);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
