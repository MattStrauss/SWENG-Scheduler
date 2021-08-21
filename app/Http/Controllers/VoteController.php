<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function store(Model $model, Request $request)
    {
        return $model->vote(auth()->id());
    }
}
