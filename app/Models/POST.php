<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POST extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = ['title', 'description', 'tags', 'date', 'status','type'];
}
