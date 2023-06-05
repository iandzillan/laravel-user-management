<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'category_id', 'location', 'uom_id', 'stock', 'safety_stock', 'desc', 'status', 'qrcode'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class);
    }

    public function getCodeAttribute($value)
    {
        return strtoupper($value);
    }

    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

    public function getDescAttribute($value)
    {
        return ucfirst($value);
    }
}
