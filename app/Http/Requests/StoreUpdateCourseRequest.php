<?php

namespace App\Http\Requests;

use App\Models\Course;
use App\Models\Professor;
use App\Models\Semester;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreUpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => [
                'required',
                'max:100',
                Rule::unique('courses')->ignore($this->course),
            ],
            'abbreviation' => [
                'required',
                'min:4',
                'max:10',
                Rule::unique('courses')->ignore($this->course),
            ],
            'type' => 'required',
            'description' => 'required|max:400',
            'programming_language' => 'nullable|max:30',
            'credits' => [
                'required',
                Rule::in(['1', '2', '3', '4']),
            ],
            'semester' => 'required',
            'semester.*' => [
                'required',
                Rule::in(Semester::getSemesterIDs()),
            ],
            'prerequisites.*' => [
                'nullable',
                Rule::in(Course::getCourseIDs()),
            ],
            'concurrents.*' => [
                'nullable',
                Rule::in(Course::getCourseIDs()),
            ],
            'professors.*' => [
                'nullable',
                Rule::in(Professor::getProfessorIDs()),
            ],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->missing('prerequisites')) {
            $this->merge([
                'prerequisites' => null
            ]);
        }

        if ($this->missing('concurrents')) {
            $this->merge([
                'concurrents' => null
            ]);
        }

        if ($this->missing('professors')) {
            $this->merge([
                'professors' => null
            ]);
        }

        $this->merge([
            'type' => $this->getType($this->input('abbreviation'))
        ]);
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'type.required' => 'The type field is created from the abbreviation field, which was not completed',
        ];
    }

    /**
     * Create the type property from the given abbreviation
     *
     * @param $abbreviation
     *
     * @return string|null
     */
    private function getType($abbreviation)
    {
        return $abbreviation ? Str::before($abbreviation, ' ') : null;
    }
}
