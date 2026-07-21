<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseName extends Model
{
    use HasFactory;
    protected $table = 'coursename'; // Explicitly set the table name

    protected $fillable = ['name'];
}
