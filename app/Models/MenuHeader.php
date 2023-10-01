<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuHeader extends Model
{
    use HasFactory;

    protected $connection = 'sqlite';
    protected $table = 'menu_master_header';
    protected $primaryKey = 'id';

    protected $fillable = [
        'menu_header_name',
        'menu_header_status',
    ];

    public function listMenuItems()
    {
        return $this->hasMany(MenuItem::className());
    }
}
