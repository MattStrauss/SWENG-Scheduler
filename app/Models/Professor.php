<?php

namespace App\Models;

use App\Traits\Votable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    use HasFactory, Votable;

    protected $fillable = ['name'];

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
    public static function getProfessorIDs()
    {
        return Professor::all()->pluck('id')->toArray();
    }

}
