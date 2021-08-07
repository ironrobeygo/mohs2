<?php

namespace App\Models;

use App\Models\User;
use App\Models\Chapter;
use App\Models\Category;
use App\Models\Institution;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    CONST ACTIVE    = 1;
    CONST PENDING   = 0;

    CONST ONLINE    = 1;
    CONST OFFLINE   = 0;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category_id',
        'instructor_id',
        'user_id',
        'updated_by',
        'status',
        'isOnline'
    ];


    protected $with = array(
        'category', 
        'user',
        'institutions',
        'instructors'
    );

    public function path(){
        return "/api/courses/{$this->id}";
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function chapters(){
        return $this->hasMany(Chapter::class);
    }

    public function addChapter($data){
        return $this->chapters()->create($data);
    }

    public function active(){
        return $this->status == Course::ACTIVE;
    }

    public function isOnline(){
        return $this->isOnline == Course::ONLINE;
    }

    public function institutions(){
        return $this->belongsToMany(Institution::class);
    }

    public function syncInstitutions($data){
        return $this->institutions()->sync($data);
    }

    public function instructors(){
        return $this->belongsToMany(User::class, 'course_instructor', 'course_id', 'instructor_id');
    }

    public function syncInstructors($data){
        return $this->instructors()->sync($data);
    }

    public function students(){
        return $this->belongsToMany(User::class, 'course_student', 'course_id', 'student_id');   
    }

    public function enrolStudent($id){
        return $this->students()->attach($id);
    }

    public function unEnrolStudent($id){
        return $this->students()->detach($id);
    }

    public static function search($search)
    {
        return empty($search) ? static::query()
            : static::query()->where('name', 'like', '%'.$search.'%');
    }
}
