<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfessorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getProffesorData() as $professor) {
            DB::table('professors')->insert([
                'name' => $professor
            ]);
        }
    }

    private function getProffesorData()
    {
        return [
            "Lauren Bello", "Andrew Erlandson", "Robert Reichle", "Neena Chopra", "Steven Hair", "Thomas Hemminger",
            "Joseph Houck", "Eugene Walters", "Jalaa Hoblos", "Ahmed Sammoud", "Naseem Ibrahim", "Patrick Byrnes",
            "Barry Brinkman", "Meng Su", "Zhifeng Xiao", "Rose Bohn", "Dillon Rockrohr", "Kayla Huxford",
            "Kasha Przybyla", "Seunghoon Bang", "Javed Siddique", "Nestor Handzy", "Ron Johnson", "Louis Leblond",
            "Zachariah Riel", "Thomas Rossi", "Adam Bondi", "Wen-Li Wang", "Chen Cao", "Murat Inan",
            "Kapilan Mahalingam",
        ];
    }
}
