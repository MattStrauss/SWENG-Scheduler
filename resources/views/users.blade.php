@extends('layouts.auth')

@section('mainContent')

    <div class="text-gray-700">
        <ul class="list-disc px-12">

            @foreach($users as $user)
                @if ($loop->first)

                    <li class="mb-6">
                @else
                <li class="my-6">
                @endif
                    {{$user->name}} - {{$user->email}} <br>
                    <span class="my-2 text-sm text-gray-500">Account Created:{{$user->created_at->diffForHumans()}} </span>
                </li>

            @endforeach
        </ul>
    </div>

@endsection
