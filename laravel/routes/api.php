<?php

use App\Http\Resources\PostsCollection;
use App\Http\Resources\UserCollection;
use App\Post;
use App\User;
use Illuminate\Http\Request;

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


    /*
     * List all posts
     *
     *
     *
        {
          "posts": [
            {
              "id": 1,
              "message": "Das ist ein Test!",
              "author": {
                "id": 1,
                "name": "Uwe Kowalsky"
              },
              "comments": []
            },
            {
              "id": 2,
              "message": "Das ist ein zweiter Test!",
              "author": {
                "id": 1,
                "name": "Uwe Kowalsky"
              },
              "comments": []
            }
          ]
        }
     *
     *
     */
    Route::get('/posts', function () {

        $a = new PostsCollection(Post::all('id', 'user_id', 'message'));
        $ret = array();

        foreach ($a as $b) {
            $user_id = Post::findOrFail($b['id'], array('user_id'));
            $author = User::find($user_id);

            $js = array(
                "id" => $b['id'],
                "message" =>  $b['message'],
                "author" => array(
                    "id" => $b['user_id'],
                    "name" => $author->first()->name
                ),
                "comments" => ""
            );

            $ret[] = $js;
        }


       return json_encode(array("posts" =>$ret));


    });

    /*
     * delete post
     */
    Route::delete('/posts/{id}', function ($id) {
        $posts = App\Post::find($id);

        $posts->delete();

        return response('ok', 200)->header('Content-Type', 'text/plain');
    });


    /*
     * get single post
     *
            {
              "id": 1,
              "message": "Das ist ein Test!",
              "author": {
                "id": 1,
                "name": "Uwe Kowalsky"
              },
              "comments": []
            }
     */
    Route::get('/posts/{id}', function ($id) {

        $message = Post::findOrFail($id, array('message'));
        $user_id = Post::findOrFail($id, array('user_id'));
        $author = User::find($user_id);


        $a = array(
            "id" => Auth::id(),
            "message" => $message,
            "author" => array(
                "id" => $user_id,
                "name" => $author->first()->name
            ),
            "comments" => ""

        );

        return json_encode($a);

    });


    /*
     * add new post
     *
     *
            {
              "id": 1,
              "message": "Das ist ein Test!",
              "author": {
                "id": 1,
                "name": "Uwe Kowalsky"
              }
            }
     */
    Route::post('/posts', function (Request $request) {

        $post = new Post();
        $post->message = $request->message;
        $post->created_at = now();
        $post->user_id = Auth::id();
        $post->save();

        /*
         * user raussuchen
         */

        $a = array(
            "id" => Auth::id(),
            "message" => $request->message,
            "author" => array(
                "id" => Auth::id(),
                "name" => Auth::user()->name,
            )
        );

        return json_encode($a);
    });


});


