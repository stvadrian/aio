<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;
    protected $table = 'automate_forms';
    protected $primaryKey = 'id';

    protected $fillable = [
        'form_name',
        'form_name_e',
        'link_form',
        'description',
        'status',
        'background_path',
        'qr_code',
        'created_by',
    ];
}
