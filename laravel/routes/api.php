<?php

use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\UserCollection;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['middleware' => 'auth:api'], function () {


    // new User
    Route::post('/users', function (Request $request) {

        $rqArray = $request->toArray();
        $user = new User();
        $user->name = $rqArray['name'];
        $user->email = $rqArray['email'];
        $user->token = str_random(10);
        $user->save();

        return new \App\Http\Resources\User($user);
    });

    // get Userlist
    Route::get('/users', function () {

        return new \App\Http\Resources\UserCollection(User::all());
    });

    // get User
    Route::get('/users/{id}', function ($id) {

        return new \App\Http\Resources\User(User::find($id));
    });


    // edit User
    Route::put('/users/{id}', function (Request $request, $id) {

        $rqArray = $request->toArray();
        $user = User::find($id);
        $user->name = $rqArray['name'];
        $user->email = $rqArray['email'];
        $user->save();

        return new \App\Http\Resources\User($user);
    });

    // delete User
    Route::delete('/users/{id}', function ($id) {

        $user = User::find($id);
        $user->delete();

        return response('{}', 200);
    });


});


