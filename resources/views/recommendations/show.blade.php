@extends('layouts.auth')

@section('mainContent')

    <div>

        @if($warnings)

            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Careful! You got warnings...</strong>

                <ul>
                @foreach($warnings as $warning)

                    <li class="ml-4 mt-2 list-disc">{{$warning}}</li>

                @endforeach
                </ul>

            </div>

        @endif

        <div class="px-3 pt-2 pb-5">
            <p class="font-medium text-2xl">Course Recommendations for <span class="bold text-blue-900 underline">
                {{$semesterName}}</span> semester with
                <span class="bold text-blue-900 underline">{{$requestedNumberOfCourses}}</span> courses: </p>
        </div>


        @if($suggestedCourses->isEmpty())

            <p>Sorry, we couldn't find any courses that fit your criteria...</p>

        @else

            <div class="text-xl p-2 -mb-1 semibold uppercase">Recommended Courses</div>

            <div class="text-gray-700 grid sm:grid-cols-2 gap-6 mb-8 p-3 border-dashed border-2 rounded">


                @foreach($suggestedCourses as $course)

                    @include('recommendations.partials.recommended-course-list')

                    @if ($loop->iteration == $requestedNumberOfCourses) @break @endif

                @endforeach

            </div>

            @if($suggestedCourses->count() > $requestedNumberOfCourses)

                <div class="text-xl p-2 -mb-1 semibold uppercase">Alternative Options </div>
                <div class="text-gray-700 grid sm:grid-cols-2 gap-6 mb-8 p-3 border-dashed border-2 rounded">

                    @foreach($suggestedCourses as $course)

                        @if ($loop->iteration <= $requestedNumberOfCourses) @continue @endif

                        @include('recommendations.partials.recommended-course-list')

                        @if ($loop->iteration == $requestedNumberOfCourses) @break @endif

                    @endforeach

                </div>

            @endif

        @endif

    </div>

@endsection

