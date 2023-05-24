<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'icon', 'description'];

    public function menus()
    {
        return $this->belongsToMany(Menu::class)->withTimestamps();
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class)->withTimestamps();
    }
}
