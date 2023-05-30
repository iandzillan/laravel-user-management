<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'icon', 'name'];

    public function modules()
    {
        return $this->belongsToMany(Modul::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class)->withTimestamps();
    }

    public function getCodeAttribute($value)
    {
        return strtoupper($value);
    }

    public function getNameAttribute($value)
    {
        return ucwords($value);
    }
}
