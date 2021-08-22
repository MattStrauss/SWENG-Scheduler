<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["title", "description", "credits", "type", "abbreviation", 'prerequisites', 'concurrents',
                            'prerequisites_for_count', 'semester_specific'];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['professors'];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'prerequisites' => 'array',
        'concurrents' => 'array',
    ];


    /**
     * Relationships
     */

    public function semesters()
    {
        return $this->belongsToMany(Semester::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function professors()
    {
        return $this->belongsToMany(Professor::class);
    }

    /**
     * Helpers
     */
    public static function getCourseIDs()
    {
        return Course::all()->pluck('id')->toArray();
    }

    public static function getCoursesBySemester($semester)
    {
        return Course::whereHas('semesters', function (Builder $query) use ($semester) {
            $query->where('semester_id', 'like', $semester);
        })->get();
    }

    public static function isPrerequisiteFor($course)
    {
       return Course::whereJsonContains('prerequisites', (string) $course->id)->get();
    }

    public static function getCoursesWithConcurrents($semester)
    {
        return Course::whereNotNull('concurrents')->whereHas('semesters', function (Builder $query) use ($semester) {
            $query->where('semester_id', 'like', $semester);
        })->get();
    }

    public static function removeDeletedCourseFromPrerequisites($course_id)
    {
        Course::whereJsonContains('prerequisites', (string) $course_id)->each(function ($item) use ($course_id) {

            $newPrerequisites = array_values(array_diff($item->prerequisites, [$course_id]));

            $item->update(['prerequisites' => (count($newPrerequisites) > 0) ? $newPrerequisites : null]);

        });
    }

    public static function removeDeletedCourseFromConcurrents($course_id)
    {
        Course::whereJsonContains('concurrents', $course_id)->each(function ($item) use ($course_id) {

            $newConcurrents = array_values(array_diff($item->concurrents, [$course_id]));

            $item->update(['concurrents' => (count($newConcurrents) > 0) ? $newConcurrents : null]);

        });
    }

}
