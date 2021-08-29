<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = ["name"];

    /**
     * Relationships
     */

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }

    /**
     * Helpers
     */
    public static function getSemesterIDs()
    {
        return Semester::all()->pluck('id')->toArray();
    }


}
