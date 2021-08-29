<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Professor;
use App\Models\Semester;
use App\Models\User;
use Database\Seeders\ProfessorSeeder;
use Database\Seeders\SemesterSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTest extends TestCase
{

    use RefreshDatabase;

    private $devUser;
    private $regularUser;
    private $courseOne;
    private $courseTwo;
    private $courseThree;
    private $semesterOne;
    private $semesterTwo;
    private $semesterThree;
    private $professorOne;
    private $professorTwo;
    private $professorThree;


    protected function setUp(): void
    {
        parent::setUp();

//        $this->seed(SemesterSeeder::class);
//        $this->seed(ProfessorSeeder::class);

        $this->semesterOne = Semester::factory()->create(['name' => 'Fall']);
        $this->semesterTwo = Semester::factory()->create(['name' => 'Spring']);
        $this->semesterThree = Semester::factory()->create(['name' => 'Summer']);

        $this->professorOne = Professor::factory()->create(['name' => 'Prof One']);
        $this->professorTwo = Professor::factory()->create(['name' => 'Prof Two']);
        $this->professorThree = Professor::factory()->create(['name' => 'Prof Three']);

        $this->devUser = User::factory()->create(['name' => 'Ava Dev', 'email' => 'ava@psu.edu']);
        $this->regularUser = User::factory()->create(['name' => 'Eli User', 'email' => 'eli@psu.edu']);

        $this->courseOne = Course::factory()->create(
            ['title' => 'Test Course One', 'abbreviation' => 'Test 221', 'type' => 'Test']);
        $this->courseOne->semesters()->attach([$this->semesterOne->id, $this->semesterTwo->id]);
        $this->courseOne->professors()->attach($this->professorOne->id);

        $this->courseTwo = Course::factory()->create(
            ['title' => 'Test Course Two', 'abbreviation' => 'Test 311', 'type' => 'Test']);
        $this->courseTwo->semesters()->attach($this->semesterTwo->id);
        $this->courseTwo->professors()->attach($this->professorTwo->id);

        $this->courseThree = Course::factory()->create(
            ['title' => 'Test Course Three', 'abbreviation' => 'Test 432', 'type' => 'Test']);
        $this->courseThree->semesters()->attach([$this->semesterTwo->id, $this->semesterThree->id]);
        $this->courseThree->professors()->attach($this->professorThree->id);
    }

    /**  @test */
    public function auth_user_can_visit_courses_index()
    {
        $response = $this->actingAs($this->regularUser)->get(route('courses.index'));

        $response->assertSuccessful();
    }

    /**  @test */
    public function auth_user_sees_correct_courses_on_courses_index()
    {
        $response = $this->actingAs($this->regularUser)->get(route('courses.index'));

        $response->assertSee(['Test Course One', 'Test Course Two', 'Test Course Three']);
    }

    /**  @test */
    public function guest_can_not_visit_courses_index()
    {
        $response = $this->get(route('courses.index'));

        $response->assertRedirect(route('login'));
    }

    /**  @test */
    public function dev_auth_user_can_visit_courses_show()
    {
        $response = $this->actingAs($this->devUser)->get(route('courses.show', Course::all()->first()->id));

        $response->assertSuccessful();
    }

    /**  @test */
    public function non_dev_auth_user_can_visit_courses_show()
    {
        $response = $this->actingAs($this->regularUser)->get(route('courses.show', Course::all()->first()->id));

        $response->assertSuccessful();
    }

    /**  @test */
    public function guest_can_not_visit_courses_show()
    {
        $response = $this->get(route('courses.show', Course::all()->first()->id));

        $response->assertRedirect(route('login'));
    }

    /**  @test */
    public function dev_auth_user_can_visit_create_course()
    {
        $response = $this->actingAs($this->devUser)->get(route('courses.create', Course::all()->first()->id));

        $response->assertSuccessful();
    }

    /**  @test */
    public function non_dev_auth_user_can_not_visit_create_course()
    {
        $response = $this->actingAs($this->regularUser)->get(route('courses.create', Course::all()->first()->id));

        $response->assertForbidden();
    }

    /**  @test */
    public function guest_can_not_visit_create_course()
    {
        $response = $this->get(route('courses.create', Course::all()->first()->id));

        $response->assertRedirect(route('login'));
    }

    /**  @test */
    public function dev_auth_user_can_visit_edit_course()
    {
        $response = $this->actingAs($this->devUser)->get(route('courses.edit', Course::all()->first()->id));

        $response->assertSuccessful();
    }

    /**  @test */
    public function non_dev_auth_user_can_not_visit_edit_course()
    {
        $response = $this->actingAs($this->regularUser)->get(route('courses.edit', Course::all()->first()->id));

        $response->assertForbidden();
    }

    /**  @test */
    public function guest_can_not_visit_edit_course()
    {
        $response = $this->get(route('courses.edit', Course::all()->first()->id));

        $response->assertRedirect(route('login'));
    }

    /**  @test */
    public function dev_auth_user_can_create_a_course()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 2, "semester" => [$this->semesterOne->id, $this->semesterTwo->id],
            "professors" => [$this->professorOne->id], 'programming_language' => "PHP"
        ];

        $response = $this->actingAs($this->devUser)->post(route('courses.store', $data));

        $newCourse = Course::where('abbreviation', 'Test 442')->first();

        $response->assertRedirect(route('courses.index'));
        $this->assertDatabaseHas('courses', ["title" => "Test Course", "abbreviation" => "Test 442",
                                             "description" => "Some random description", "credits" => 2,
                                                "programming_language" => "PHP"]);
        $this->assertDatabaseHas('course_semester', ['course_id' => $newCourse->id, 'semester_id' => $this->semesterOne->id]);
        $this->assertDatabaseHas('course_semester', ['course_id' => $newCourse->id, 'semester_id' => $this->semesterTwo->id]);
        $this->assertDatabaseHas('course_professor',
            ['course_id' => $newCourse->id, 'professor_id' => $this->professorOne->id]);
    }

    /**  @test */
    public function dev_auth_user_can_create_a_course_with_prerequisites()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 2, "semester" => [$this->semesterOne->id, $this->semesterTwo->id], "prerequisites" => [$this->courseOne->id],
            "professors" => [$this->professorOne->id]
        ];

        $response = $this->actingAs($this->devUser)->post(route('courses.store', $data));

        $newCourse = Course::where('abbreviation', 'Test 442')->first();

        $response->assertRedirect(route('courses.index'));
        $this->assertDatabaseHas('courses', ["title" => "Test Course", "abbreviation" => "Test 442",
                                             "description" => "Some random description", "credits" => 2]);

        $this->assertTrue(in_array($this->courseOne->id, $newCourse->prerequisites));
        $this->assertFalse(in_array($this->courseTwo->id, $newCourse->prerequisites));
        $this->assertFalse(in_array($this->courseThree->id, $newCourse->prerequisites));

        $this->assertDatabaseHas('course_semester', ['course_id' => $newCourse->id, 'semester_id' => $this->semesterOne->id]);
        $this->assertDatabaseHas('course_semester', ['course_id' => $newCourse->id, 'semester_id' => $this->semesterTwo->id]);
        $this->assertDatabaseHas('course_professor',
            ['course_id' => $newCourse->id, 'professor_id' => $this->professorOne->id]);
    }


    /**  @test */
    public function dev_auth_user_can_create_a_course_with_concurrents()
    {
        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 2, "semester" => [$this->semesterOne->id,$this->semesterTwo->id], "concurrents" => [$this->courseOne->id],
            "professors" => [$this->professorOne->id, $this->professorTwo->id]
        ];

        $response = $this->actingAs($this->devUser)->post(route('courses.store', $data));

        $newCourse = Course::where('abbreviation', 'Test 442')->first();

        $response->assertRedirect(route('courses.index'));
        $this->assertDatabaseHas('courses', ["title" => "Test Course", "abbreviation" => "Test 442",
                                             "description" => "Some random description", "credits" => 2]);

        $this->assertTrue(in_array($this->courseOne->id, $newCourse->concurrents));
        $this->assertFalse(in_array($this->courseTwo->id, $newCourse->concurrents));
        $this->assertFalse(in_array($this->courseThree->id, $newCourse->concurrents));


        $this->assertDatabaseHas('course_semester', ['course_id' => $newCourse->id, 'semester_id' => $this->semesterOne->id]);
        $this->assertDatabaseHas('course_semester', ['course_id' => $newCourse->id, 'semester_id' => $this->semesterTwo->id]);
        $this->assertDatabaseHas('course_professor',
            ['course_id' => $newCourse->id, 'professor_id' => $this->professorOne->id]);
        $this->assertDatabaseHas('course_professor',
            ['course_id' => $newCourse->id, 'professor_id' => $this->professorTwo->id]);
    }

    /**  @test */
    public function dev_auth_user_can_create_a_course_with_prerequisites_and_concurrents()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 2, "semester" => [$this->semesterOne->id, $this->semesterTwo->id], "concurrents" => [$this->courseOne->id],
            "prerequisites" => [$this->courseTwo->id, $this->courseThree->id],
            "professors" => [$this->professorThree->id, $this->professorTwo->id]
        ];

        $response = $this->actingAs($this->devUser)->post(route('courses.store', $data));

        $newCourse = Course::where('abbreviation', 'Test 442')->first();

        $response->assertRedirect(route('courses.index'));
        $this->assertDatabaseHas('courses', ["title" => "Test Course", "abbreviation" => "Test 442",
                                             "description" => "Some random description", "credits" => 2]);

        $this->assertTrue(in_array($this->courseOne->id, $newCourse->concurrents));
        $this->assertTrue(in_array($this->courseTwo->id, $newCourse->prerequisites));
        $this->assertTrue(in_array($this->courseThree->id, $newCourse->prerequisites));
        $this->assertFalse(in_array($this->courseTwo->id, $newCourse->concurrents));
        $this->assertFalse(in_array($this->courseOne->id, $newCourse->prerequisites));

        $this->assertDatabaseHas('course_semester', ['course_id' => $newCourse->id, 'semester_id' => $this->semesterOne->id]);
        $this->assertDatabaseHas('course_semester', ['course_id' => $newCourse->id, 'semester_id' => $this->semesterTwo->id]);
        $this->assertDatabaseHas('course_professor',
            ['course_id' => $newCourse->id, 'professor_id' => $this->professorThree->id]);
        $this->assertDatabaseHas('course_professor',
            ['course_id' => $newCourse->id, 'professor_id' => $this->professorTwo->id]);
    }


    /**  @test */
    public function dev_auth_user_can_not_create_a_course_with_a_course_title_already_taken()
    {

        $data = [
            "title" => $this->courseOne->title, "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 2, "semester" => [$this->semesterOne->id, $this->semesterTwo->id], "concurrents" => [$this->courseOne->id]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.create'))
                                                   ->post(route('courses.store', $data));


        $response->assertRedirect(route('courses.create'));
        $response->assertSessionHasErrorsIn('title');
        $this->assertDatabaseMissing('courses', ["title" => $this->courseOne->title, "abbreviation" => "Test 442",
                                             "description" => "Some random description", "credits" => 2,
                                             "concurrents" => json_encode([(string) $this->courseOne->id])]);
    }

    /**  @test */
    public function dev_auth_user_can_not_create_a_course_with_a_course_abbreviation_already_taken()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => $this->courseOne->abbreviation, "description" => "Some random description",
            "credits" => 2, "semester" => [$this->semesterOne->id, $this->semesterTwo->id], "concurrents" => [$this->courseOne->id]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.create'))
                         ->post(route('courses.store', $data));


        $response->assertRedirect(route('courses.create'));
        $response->assertSessionHasErrorsIn('abbreviation');
        $this->assertDatabaseMissing('courses', ["title" => "Test Course", "abbreviation" => $this->courseOne->abbreviation,
                                                 "description" => "Some random description", "credits" => 2,
                                                 "concurrents" => json_encode([(string) $this->courseOne->id])]);
    }

    /**  @test */
    public function dev_auth_user_can_not_create_a_course_with_no_description()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "",
            "credits" => 2, "semester" => [$this->semesterOne->id, $this->semesterTwo->id], "concurrents" => [$this->courseOne->id]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.create'))
                         ->post(route('courses.store', $data));


        $response->assertRedirect(route('courses.create'));
        $response->assertSessionHasErrorsIn('description');
        $this->assertDatabaseMissing('courses', ["title" => "Test Course", "abbreviation" => "Test 442",
                                                 "description" => "", "credits" => 2,
                                                 "concurrents" => json_encode([(string) $this->courseOne->id])]);
    }

    /**  @test */
    public function dev_auth_user_can_not_create_a_course_with_invalid_number_of_credits()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 0, "semester" => [$this->semesterOne->id, $this->semesterTwo->id], "concurrents" => [$this->courseOne->id]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.create'))
                         ->post(route('courses.store', $data));


        $response->assertRedirect(route('courses.create'));
        $response->assertSessionHasErrorsIn('credits');
        $this->assertDatabaseMissing('courses', ["title" => "Test Course", "abbreviation" => "Test 442",
                                                 "description" => "Some random description", "credits" => 0,
                                                 "concurrents" => json_encode([(string) $this->courseOne->id])]);
    }

    /**  @test */
    public function dev_auth_user_can_not_create_a_course_with_invalid_semester_choice()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 0, "semester" => [Semester::first()->id - 1], "concurrents" => [$this->courseOne->id]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.create'))
                         ->post(route('courses.store', $data));


        $response->assertRedirect(route('courses.create'));
        $response->assertSessionHasErrorsIn('semester');
        $this->assertDatabaseMissing('courses', ["title" => "Test Course", "abbreviation" => "Test 442",
                                                 "description" => "Some random description", "credits" => 2,
                                                 "concurrents" => json_encode([(string) $this->courseOne->id])]);
    }

    /**  @test */
    public function dev_auth_user_can_not_create_a_course_with_invalid_concurrents_choice()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 0, "semester" => [$this->semesterTwo->id], "concurrents" => [100]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.create'))
                         ->post(route('courses.store', $data));


        $response->assertRedirect(route('courses.create'));
        $response->assertSessionHasErrorsIn('concurrents');
        $this->assertDatabaseMissing('courses', ["title" => "Test Course", "abbreviation" => "Test 442",
                                                 "description" => "Some random description", "credits" => 2,
                                                 "concurrents" => json_encode([(string) 100])]);
    }

    /**  @test */
    public function dev_auth_user_can_not_create_a_course_with_invalid_prerequisites_choice()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 0, "semester" => [$this->semesterTwo->id], "prerequisites" => [100]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.create'))
                         ->post(route('courses.store', $data));


        $response->assertRedirect(route('courses.create'));
        $response->assertSessionHasErrorsIn('prerequisites');
        $this->assertDatabaseMissing('courses', ["title" => "Test Course", "abbreviation" => "Test 442",
                                                 "description" => "Some random description", "credits" => 2,
                                                 "prerequisites" => json_encode([(string) 100])]);
    }

    /**  @test */
    public function dev_auth_user_can_not_create_a_course_with_invalid_professor_choice()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 0, "semester" => [$this->semesterTwo->id], "professors" => [10000001]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.create'))
                         ->post(route('courses.store', $data));


        $response->assertRedirect(route('courses.create'));
        $response->assertSessionHasErrorsIn('professors');
        $this->assertDatabaseMissing('course_professor', ['professor_id' => 10000001 ]);
    }

    /**  @test */
    public function regular_auth_user_can_not_create_a_course()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 2, "semester" => [$this->semesterOne->id]
        ];

        $response = $this->actingAs($this->regularUser)->post(route('courses.store', $data));

        $response->assertForbidden();
        $this->assertDatabaseMissing('courses', ["title" => "Test Course", "abbreviation" => "Test 442",
                                             "description" => "Some random description", "credits" => 2,]);
    }

    /**  @test */
    public function guest_can_not_create_a_course()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 2, "semester" => [$this->semesterThree->id]
        ];

        $response = $this->post(route('courses.store', $data));

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('courses', ["title" => "Test Course", "abbreviation" => "Test 442",
                                                 "description" => "Some random description", "credits" => 2,]);
    }

    /**  @test */
    public function dev_auth_user_can_update_a_course()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 2, "semester" => [$this->semesterOne->id, $this->semesterTwo->id], 'programming_language' => "C#"
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.edit', [$this->courseOne->id]))
            ->put(route('courses.update', [$this->courseOne->id]), $data);

        $response->assertRedirect(route('courses.edit', [$this->courseOne->id]));
        $this->assertDatabaseHas('courses', ["id" => $this->courseOne->id, "title" => "Test Course",
                                             "abbreviation" => "Test 442", "description" => "Some random description",
                                             "credits" => 2, 'programming_language' => "C#"]);
        $this->assertDatabaseHas('course_semester', ['course_id' => $this->courseOne->id, 'semester_id' => $this->semesterOne->id]);
        $this->assertDatabaseHas('course_semester', ['course_id' => $this->courseOne->id, 'semester_id' => $this->semesterTwo->id]);
    }

    /**  @test */
    public function dev_auth_user_can_update_a_course_with_prerequisites()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 2, "semester" => [$this->semesterOne->id], "prerequisites" => [(string) $this->courseOne->id]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.edit', [$this->courseTwo->id]))
                         ->put(route('courses.update', [$this->courseTwo->id]), $data);


        $response->assertRedirect(route('courses.edit', [$this->courseTwo->id]));
        $this->assertDatabaseHas('courses', ["id" => $this->courseTwo->id,
                                             "title" => "Test Course", "abbreviation" => "Test 442",
                                             "description" => "Some random description", "credits" => 2]);

        $this->courseTwo->refresh();

        $this->assertTrue(in_array($this->courseOne->id, $this->courseTwo->prerequisites));
        $this->assertFalse(in_array($this->courseThree->id, $this->courseTwo->prerequisites));

        $this->assertDatabaseHas('course_semester', ['course_id' => $this->courseTwo->id, 'semester_id' => $this->semesterOne->id ]);
    }

    /**  @test */
    public function dev_auth_user_can_update_a_course_with_concurrents_and_prerequisites()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 2, "semester" => [$this->semesterTwo->id], "concurrents" => [(string) $this->courseTwo->id],
            "prerequisites" => [(string) $this->courseThree->id]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.edit', [$this->courseOne->id]))
                         ->put(route('courses.update', [$this->courseOne->id]), $data);


        $response->assertRedirect(route('courses.edit', [$this->courseOne->id]));

        $this->assertDatabaseHas('courses', ["title" => "Test Course", "abbreviation" => "Test 442",
                                             "description" => "Some random description", "credits" => 2]);

        $this->courseOne->refresh();

        $this->assertTrue(in_array($this->courseTwo->id, $this->courseOne->concurrents));
        $this->assertTrue(in_array($this->courseThree->id, $this->courseOne->prerequisites));

        $this->assertDatabaseHas('course_semester', ['course_id' => $this->courseOne->id, 'semester_id' => $this->semesterTwo->id]);
    }

    /**  @test */
    public function dev_auth_user_can_update_a_course_with_concurrents_prerequisites_and_professors()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 2, "semester" => [$this->semesterTwo->id], "concurrents" => [(string) $this->courseTwo->id],
            "prerequisites" => [(string) $this->courseThree->id], "professors" => [Professor::first()->id]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.edit', [$this->courseOne->id]))
                         ->put(route('courses.update', [$this->courseOne->id]), $data);


        $response->assertRedirect(route('courses.edit', [$this->courseOne->id]));

        $this->assertDatabaseHas('courses', ["title" => "Test Course", "abbreviation" => "Test 442",
                                             "description" => "Some random description", "credits" => 2]);

        $this->courseOne->refresh();

        $this->assertTrue(in_array($this->courseTwo->id, $this->courseOne->concurrents));
        $this->assertTrue(in_array($this->courseThree->id, $this->courseOne->prerequisites));

        $this->assertDatabaseHas('course_semester', ['course_id' => $this->courseOne->id, 'semester_id' => $this->semesterTwo->id]);
        $this->assertDatabaseHas('course_professor', ['course_id' => $this->courseOne->id,
                                                      'professor_id' => Professor::first()->id]);
    }

    /**  @test */
    public function dev_auth_user_can_not_update_a_course_with_a_course_title_already_taken()
    {

        $data = [
            "title" => $this->courseTwo->title, "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 2, "semester" => [$this->semesterTwo->id], "prerequisites" => [(string) $this->courseTwo->id]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.edit', [$this->courseOne->id]))
                         ->put(route('courses.update', [$this->courseOne->id]), $data);


        $response->assertRedirect(route('courses.edit', [$this->courseOne->id]));
        $response->assertSessionHasErrorsIn('title');
        $this->assertDatabaseMissing('courses', ["id" => $this->courseOne->id, "title" => $this->courseTwo->title,
                                                 "abbreviation" => "Test 442",
                                                 "description" => "Some random description", "credits" => 2,
                                                 "prerequisites" => json_encode([(string) $this->courseTwo->id])]);
    }

    /**  @test */
    public function dev_auth_user_can_not_update_a_course_with_a_course_abbreviation_already_taken()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => $this->courseTwo->abbreviation, "description" => "Some random description",
            "credits" => 2, "semester" => [$this->semesterThree->id], "prerequisites" => [(string) $this->courseTwo->id]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.edit', [$this->courseOne->id]))
                         ->put(route('courses.update', [$this->courseOne->id]), $data);


        $response->assertRedirect(route('courses.edit', [$this->courseOne->id]));
        $response->assertSessionHasErrorsIn('abbreviation');
        $this->assertDatabaseMissing('courses', ["id" => $this->courseOne->id,"title" => "Test Course",
                                                 "abbreviation" => $this->courseTwo->abbreviation,
                                                 "description" => "Some random description", "credits" => 2,
                                                 "prerequisites" => json_encode([(string) $this->courseTwo->id])]);
    }

    /**  @test */
    public function dev_auth_user_can_not_update_a_course_with_no_description()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "",
            "credits" => 2, "semester" => [$this->semesterTwo->id], "prerequisites" => [(string) $this->courseTwo->id]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.edit', [$this->courseOne->id]))
                         ->put(route('courses.update', [$this->courseOne->id]), $data);


        $response->assertRedirect(route('courses.edit', [$this->courseOne->id]));
        $response->assertSessionHasErrorsIn('description');
        $this->assertDatabaseMissing('courses', ["id" => $this->courseOne->id, "title" => "Test Course",
                                                 "abbreviation" => "Test 442", "description" => "", "credits" => 2,
                                                 "prerequisites" => json_encode([(string) $this->courseTwo->id])]);
    }

    /**  @test */
    public function dev_auth_user_can_not_update_a_course_with_invalid_number_of_credits()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 0, "semester" => [$this->semesterThree->id], "prerequisites" => [(string) $this->courseTwo->id]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.edit', [$this->courseOne->id]))
                         ->put(route('courses.update', [$this->courseOne->id]), $data);


        $response->assertRedirect(route('courses.edit', [$this->courseOne->id]));
        $response->assertSessionHasErrorsIn('credits');
        $this->assertDatabaseMissing('courses', ["id" => $this->courseOne->id,  "title" => "Test Course",
                                                 "abbreviation" => "Test 442",
                                                 "description" => "Some random description", "credits" => 0,
                                                 "prerequisites" => json_encode([(string) $this->courseTwo->id])]);
    }

    /**  @test */
    public function dev_auth_user_can_not_update_a_course_with_invalid_semester_choice()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 0, "semester" => [Semester::first()-> id - 1], "prerequisites" => [(string) $this->courseTwo->id]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.edit', [$this->courseOne->id]))
                         ->put(route('courses.update', [$this->courseOne->id]), $data);


        $response->assertRedirect(route('courses.edit', [$this->courseOne->id]));
        $response->assertSessionHasErrorsIn('semester');
        $this->assertDatabaseMissing('courses', ["id" => $this->courseOne->id, "title" => "Test Course",
                                                 "abbreviation" => "Test 442",
                                                 "description" => "Some random description", "credits" => 2,
                                                 "prerequisites" => json_encode([(string) $this->courseTwo->id])]);
    }

    /**  @test */
    public function dev_auth_user_can_not_update_a_course_with_invalid_concurrents_choice()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 0, "semester" => [$this->semesterThree->id], "concurrents" => [100]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.edit', [$this->courseOne->id]))
                         ->put(route('courses.update', [$this->courseOne->id]), $data);


        $response->assertRedirect(route('courses.edit', [$this->courseOne->id]));
        $response->assertSessionHasErrorsIn('concurrents');
        $this->assertDatabaseMissing('courses', ["id" => $this->courseOne->id, "title" => "Test Course",
                                                 "abbreviation" => "Test 442",
                                                 "description" => "Some random description", "credits" => 2,
                                                 "concurrents" => json_encode([(string) 100])]);
    }

    /**  @test */
    public function dev_auth_user_can_not_update_a_course_with_invalid_prerequisites_choice()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 0, "semester" => [$this->semesterThree->id], "prerequisites" => [100]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.edit', [$this->courseOne->id]))
                         ->put(route('courses.update', [$this->courseOne->id]), $data);


        $response->assertRedirect(route('courses.edit', [$this->courseOne->id]));
        $response->assertSessionHasErrorsIn('prerequisites');
        $this->assertDatabaseMissing('courses', ["id" => $this->courseOne->id, "title" => "Test Course",
                                                 "abbreviation" => "Test 442",
                                                 "description" => "Some random description", "credits" => 2,
                                                 "prerequisites" => json_encode([(string) 100])]);
    }

    /**  @test */
    public function dev_auth_user_can_not_update_a_course_with_invalid_professors_choice()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 0, "semester" => [$this->semesterTwo->id], "prerequisites" => [1], "professors" => [2121021]
        ];

        $response = $this->actingAs($this->devUser)->from(route('courses.edit', [$this->courseOne->id]))
                         ->put(route('courses.update', [$this->courseOne->id]), $data);


        $response->assertRedirect(route('courses.edit', [$this->courseOne->id]));
        $response->assertSessionHasErrorsIn('professors');
        $this->assertDatabaseMissing('course_professor', ["course_id" => $this->courseOne->id,
            "professor_id" => 2121021]);

    }

    /**  @test */
    public function regular_auth_user_can_not_update_a_course()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 2, "semester" => [$this->semesterOne->id, $this->semesterTwo->id]
        ];

        $response = $this->actingAs($this->regularUser)->put(route('courses.update', [$this->courseOne->id]), $data);

        $response->assertForbidden();
        $this->assertDatabaseMissing('courses', ["id" => $this->courseOne->id, "title" => "Test Course",
                                                 "abbreviation" => "Test 442",
                                                 "description" => "Some random description", "credits" => 2,]);
    }

    /**  @test */
    public function guest_can_not_update_a_course()
    {

        $data = [
            "title" => "Test Course", "abbreviation" => "Test 442", "description" => "Some random description",
            "credits" => 2, "semester" => [$this->semesterOne->id, $this->semesterTwo->id]
        ];

        $response = $this->put(route('courses.update', [$this->courseOne->id]), $data);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('courses', ["id" => $this->courseOne->id, "title" => "Test Course",
                                                 "abbreviation" => "Test 442",
                                                 "description" => "Some random description", "credits" => 2,]);
    }

    /**  @test */
    public function dev_auth_user_can_delete_a_course()
    {

        $response = $this->actingAs($this->devUser)
                         ->delete(route('courses.destroy', [$this->courseOne->id]));

        $response->assertRedirect(route('courses.index'));
        $this->assertDatabaseMissing('courses', ["id" => $this->courseOne->id]);
        $this->assertDatabaseMissing('course_semester', ['course_id' => $this->courseOne->id, 'semester_id' => 1]);
        $this->assertDatabaseMissing('course_semester', ['course_id' => $this->courseOne->id, 'semester_id' => 2]);
    }

    /**  @test */
    public function when_dev_auth_user_deletes_a_course_it_is_removed_as_a_prerequisite_from_other_courses()
    {

        $this->courseTwo->update(['prerequisites' => [$this->courseOne->id]]);

        $response = $this->actingAs($this->devUser)
                         ->delete(route('courses.destroy', [$this->courseOne->id]));

        $response->assertRedirect(route('courses.index'));
        $this->assertDatabaseMissing('courses', ["id" => $this->courseOne->id]);
        $this->assertDatabaseMissing('course_semester', ['course_id' => $this->courseOne->id, 'semester_id' => $this->semesterOne->id]);
        $this->assertDatabaseMissing('course_semester', ['course_id' => $this->courseOne->id, 'semester_id' => $this->semesterTwo->id]);
        $this->assertDatabaseHas('courses', ['id' => $this->courseTwo->id, 'prerequisites' => null]);
    }

    /**  @test */
    public function when_dev_auth_user_deletes_a_course_it_is_removed_from_the_course_professor_table()
    {

        $this->courseOne->professors()->attach(Professor::first()->id);

        $response = $this->actingAs($this->devUser)
                         ->delete(route('courses.destroy', [$this->courseOne->id]));

        $response->assertRedirect(route('courses.index'));
        $this->assertDatabaseMissing('courses', ["id" => $this->courseOne->id]);
        $this->assertDatabaseMissing('course_semester', ['course_id' => $this->courseOne->id, 'semester_id' => $this->semesterOne->id]);
        $this->assertDatabaseMissing('course_semester', ['course_id' => $this->courseOne->id, 'semester_id' => $this->semesterTwo->id]);
        $this->assertDatabaseMissing('course_professor', ['professor_id' => $this->professorOne->id,
                                                          "course_id" => $this->courseOne->id]);
    }


    /**  @test */
    public function regular_auth_user_can_not_delete_a_course()
    {

        $response = $this->actingAs($this->regularUser)
                         ->delete(route('courses.destroy', [$this->courseOne->id]));

        $response->assertForbidden();
        $this->assertDatabaseHas('courses', ["id" => $this->courseOne->id]);
        $this->assertDatabaseHas('course_semester', ['course_id' => $this->courseOne->id, 'semester_id' => $this->semesterOne->id]);
        $this->assertDatabaseHas('course_semester', ['course_id' => $this->courseOne->id, 'semester_id' => $this->semesterTwo->id]);
    }

    /**  @test */
    public function guest_can_not_delete_a_course()
    {

        $response = $this->delete(route('courses.destroy', [$this->courseOne->id]));

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('courses', ["id" => $this->courseOne->id]);
        $this->assertDatabaseHas('course_semester', ['course_id' => $this->courseOne->id, 'semester_id' => $this->semesterOne->id]);
        $this->assertDatabaseHas('course_semester', ['course_id' => $this->courseOne->id, 'semester_id' => $this->semesterTwo->id]);
    }

}
