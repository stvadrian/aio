<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Icon extends Model
{
    use HasFactory;

    protected $table = 'menu_master_icons';
    protected $primaryKey = 'id';

    protected $fillable = [
        'icons_name',
        'icons_code',
    ];
}
