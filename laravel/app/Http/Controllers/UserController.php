<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //
    public function findById($id)
    {
        return User::where('id', $id)->first();;
    }

}
