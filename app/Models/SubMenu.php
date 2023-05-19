<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubMenu extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'menu_id', 'icon', 'name'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
