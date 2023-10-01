<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreadCategory extends Model
{
    use HasFactory;

    protected $fillable = ['category_name', 'category_description', 'category_icon'];

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
    public function posts()
    {
        return $this->hasManyThrough(Post::class, Thread::class);
    }

    public function latestThread()
    {
        return $this->hasOne(Thread::class)->latest();
    }

    public function latestPost()
    {
        return $this->hasOneThrough(Post::class, Thread::class)->latest();
    }
}
