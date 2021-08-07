<?php

namespace App\Models;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chapter extends Model
{
    use HasFactory, SoftDeletes;

    CONST ACTIVE  = 1;
    CONST INACTIVE = 0;

    protected $fillable = [
        'name',
        'content',
        'user_id',
        'updated_by',
        'status'
    ];

    public function path(){
        return "/api/courses/{$this->course->id}/chapters/{$this->id}";
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function quizzes(){
        return $this->hasMany(Quiz::class);
    }

    public function addQuiz($data){
        return $this->quizzes()->create($data);
    }
}
