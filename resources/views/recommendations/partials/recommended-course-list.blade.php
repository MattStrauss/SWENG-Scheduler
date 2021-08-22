<div class="leading-relaxed p-4 rounded bg-gray-50">
    <h4><strong>Title</strong>:
        <a class="underline font-medium hover:no-underline" href="{{route('courses.show', $course->id)}}">
            {{$course->title}} - {{$course->abbreviation}}
        </a>
    </h4>
    <p><strong>Credits</strong>: {{$course->credits}}</p>

    @if($course->semester_specific)
        <p><span class="font-bold text-yellow-600">Course Only Available in the {{$semesterName}} semester!</span></p>
    @endif


    <p><strong>Prerequisite for</strong>:
        @if(\App\Models\Course::isPrerequisiteFor($course)->isNotEmpty())

                @foreach(\App\Models\Course::isPrerequisiteFor($course) as $course)
                    @if (! $loop->last)
                        {{$course->abbreviation}},
                    @else
                        {{$course->abbreviation}}
                    @endif
                @endforeach

        @else
            None
        @endif
    </p>
</div>
