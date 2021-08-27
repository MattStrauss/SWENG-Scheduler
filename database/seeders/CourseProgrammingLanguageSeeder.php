<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Professor;
use Illuminate\Database\Seeder;

class CourseProgrammingLanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Course::all() as $course) {

            if ($course->abbreviation == "EE 211") {
                $course->update(['programming_language' => "MATLAB"]);
            }

            if ($course->abbreviation == "CMPEN 270") {
                $course->update(['programming_language' => "VHDL"]);
            }

            if ($course->abbreviation == "CMPEN 351") {
                $course->update(['programming_language' => "Mips Assembly"]);
            }

            if ($course->abbreviation == "CMPEN 441") {
                $course->update(['programming_language' => "C"]);
            }

            if ($course->abbreviation == "CMPEN 461") {
                $course->update(['programming_language' => "Java"]);
            }

            if ($course->abbreviation == "CMPSC 121") {
                $course->update(['programming_language' => "C++"]);
            }

            if ($course->abbreviation == "CMPSC 122") {
                $course->update(['programming_language' => "C++"]);
            }

            if ($course->abbreviation == "CMPSC 431W") {
                $course->update(['programming_language' => "SQL"]);
            }

            if ($course->abbreviation == "CMPSC 461") {
                $course->update(['programming_language' => "Various"]);
            }

            if ($course->abbreviation == "CMPSC 465") {
                $course->update(['programming_language' => "C++"]);
            }

            if ($course->abbreviation == "MATH 220") {
                $course->update(['programming_language' => "MATLAB"]);
            }

            if ($course->abbreviation == "MATH 250") {
                $course->update(['programming_language' => "MATLAB"]);
            }


            if ($course->abbreviation == "SWENG 311") {
                $course->update(['programming_language' => "Java"]);
            }

            if ($course->abbreviation == "SWENG 411") {
                $course->update(['programming_language' => "Your Choice"]);
            }

            if ($course->abbreviation == "SWENG 421") {
                $course->update(['programming_language' => "C#"]);
            }

            if ($course->abbreviation == "SWENG 431") {
                $course->update(['programming_language' => "Java"]);
            }

            if ($course->abbreviation == "SWENG 452") {
                $course->update(['programming_language' => "C/C++"]);
            }

            if ($course->abbreviation == "SWENG 480") {
                $course->update(['programming_language' => "Your Choice"]);
            }

            if ($course->abbreviation == "SWENG 481") {
                $course->update(['programming_language' => "Your Choice"]);
            }
        }
    }
}
