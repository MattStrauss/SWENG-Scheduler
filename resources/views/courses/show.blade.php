@extends('layouts.auth')

@section('mainContent')

    <div class="leading-relaxed p-4 rounded bg-gray-50">
        <h4><strong>Title</strong>:
            <span class="font-medium">
                {{$course->title}} - {{$course->abbreviation}}
            </span>
        </h4>
        <p><strong>Description</strong>: <br> {{$course->description}}</p>
        <p><strong>Credits</strong>: {{$course->credits}}</p>
        <p><strong>Semester(s)</strong>:
            @foreach($course->semesters as $semester)
                @if (! $loop->last)
                    {{$semester->name}},
                @else
                    {{$semester->name}}
                @endif
            @endforeach
        </p>

        <p><strong>Professor(s)</strong>:
            @if ($course->professors->isNotEmpty())
                @foreach($course->professors as $professor)
                    @if (! $loop->last)
                        {{$professor->name}},
                    @else
                        {{$professor->name}}
                    @endif
                @endforeach
            @else
                Unknown
            @endif
        </p>

        <h4><strong>Prerequisites </strong></h4>
        @if(! $course->prerequisites)
            None
        @else
            @foreach($course->prerequisites as $prereq)
                @if (! $loop->last)
                    {{\App\Models\Course::find($prereq)->abbreviation . ","}}
                @else
                    {{\App\Models\Course::find($prereq)->abbreviation}}
                @endif
            @endforeach
        @endif

        <h4><strong>Concurrent with </strong></h4>
        @if(! $course->concurrents)
            None
        @else
            @foreach($course->concurrents as $concurrent)
                @if (! $loop->last)
                    {{\App\Models\Course::find($concurrent)->abbreviation . ","}}
                @else
                    {{\App\Models\Course::find($concurrent)->abbreviation}}
                @endif
            @endforeach
        @endif

        <h4><strong>Prerequisite for</strong> </h4>
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

    </div>

@endsection
