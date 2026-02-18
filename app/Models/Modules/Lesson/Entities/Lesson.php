<?php

namespace App\Models\Modules\Lesson\Entities;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model { protected $fillable = [ 'title', 'description', 'file_path', 'teacher_id', ]; }
