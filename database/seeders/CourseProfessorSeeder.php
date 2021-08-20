<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Professor;
use Illuminate\Database\Seeder;

class CourseProfessorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Course::all() as $course) {

            if ($course->abbreviation == "ENGL 15") {
                $profs = Professor::whereIn('name', ["Lauren Bello", "Andrew Erlandson", "Robert Reichle"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "MATH 22") {
                $profs = Professor::whereIn('name', ["Neena Chopra"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "MATH 26") {
                $profs = Professor::whereIn('name', ["Neena Chopra", "Steven Hair"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "EE 211") {
                $profs = Professor::whereIn('name', ["Thomas Hemminger"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "CHEM 110") {
                $profs = Professor::whereIn('name', ["Joseph Houck"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "CHEM 111") {
                $profs = Professor::whereIn('name', ["Joseph Houck"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "CMPEN 270") {
                $profs = Professor::whereIn('name', ["Eugene Walters"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "CMPEN 351") {
                $profs = Professor::whereIn('name', ["Jalaa Hoblos"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "CMPEN 441") {
                $profs = Professor::whereIn('name', ["Ahmed Sammoud"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "CMPEN 461") {
                $profs = Professor::whereIn('name', ["Naseem Ibrahim"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "CMPSC 121") {
                $profs = Professor::whereIn('name', ["Patrick Byrnes", "Barry Brinkman"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "CMPSC 122") {
                $profs = Professor::whereIn('name', ["Meng Su", "Jalaa Hoblos"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "CMPSC 360") {
                $profs = Professor::whereIn('name', ["Meng Su"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "CMPSC 431W") {
                $profs = Professor::whereIn('name', ["Barry Brinkman"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "CMPSC 461") {
                $profs = Professor::whereIn('name', ["Jalaa Hoblos"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "CMPSC 465") {
                $profs = Professor::whereIn('name', ["Zhifeng Xiao"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "ENGL 202C") {
                $profs = Professor::whereIn('name', ["Rose Bohn", "Dillon Rockrohr", "Kayla Huxford"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "MATH 140") {
                $profs = Professor::whereIn('name', ["Kasha Przybyla"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "MATH 141") {
                $profs = Professor::whereIn('name', ["Kasha Przybyla", "Seunghoon Bang"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "MATH 220") {
                $profs = Professor::whereIn('name', ["Javed Siddique"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "MATH 250") {
                $profs = Professor::whereIn('name', ["Nestor Handzy"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "MGMT 301") {
                $profs = Professor::whereIn('name', ["Ron Johnson"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "PHYS 211") {
                $profs = Professor::whereIn('name', ["Louis Leblond"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "PHYS 212") {
                $profs = Professor::whereIn('name', ["Louis Leblond"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "STAT 318") {
                $profs = Professor::whereIn('name', ["Zachariah Riel"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "SWENG 311") {
                $profs = Professor::whereIn('name', ["Thomas Rossi"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "SWENG 411") {
                $profs = Professor::whereIn('name', ["Adam Bondi"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "SWENG 421") {
                $profs = Professor::whereIn('name', ["Wen-Li Wang"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "SWENG 431") {
                $profs = Professor::whereIn('name', ["Barry Brinkman"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "SWENG 452") {
                $profs = Professor::whereIn('name', ["Chen Cao"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "SWENG 480") {
                $profs = Professor::whereIn('name', ["Naseem Ibrahim"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "SWENG 481") {
                $profs = Professor::whereIn('name', ["Naseem Ibrahim"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "ECON 102") {
                $profs = Professor::whereIn('name', ["Murat Inan"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

            if ($course->abbreviation == "ECON 104") {
                $profs = Professor::whereIn('name', ["Kapilan Mahalingam"])->pluck('id')->toArray();
                $course->professors()->sync($profs);
            }

        }
    }
}
