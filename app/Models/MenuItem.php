<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $connection = 'sqlite';
    protected $table = 'menu_master_item';
    protected $primaryKey = 'id';

    protected $fillable = [
        'master_header',
        'menu_item_name',
        'menu_item_link',
        'menu_item_file',
        'menu_function',
        'menu_item_status',
        'menu_icon',
        'modul_departemen',
        'hak_akses',
        'urutan',
    ];

    public function menuHeader()
    {
        return $this->belongsTo(MenuHeader::class, 'master_header');
    }

    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'modul')->on('sqlsrv');
    }
}
