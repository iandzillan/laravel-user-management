<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    use HasFactory;

    protected $table = 'modules';

    protected $fillable = ['code', 'name', 'icon', 'description'];

    public function menus()
    {
        return $this->belongsToMany(Menu::class)->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function getCodeAttribute($value)
    {
        return strtoupper($value);
    }

    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

    public function getDescriptionAttribute($value)
    {
        return ucfirst($value);
    }
}
