<?php

use App\Comment;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\UserCollection;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

/**
 *  Users
 */

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
    $user = User::find($id);

    if (!$user) {
        return response('Not found', 404)->header('Content-Type', 'text/plain');
    }

    return new \App\Http\Resources\User($user);
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


// -------------------------------------------------------------------------

/**
 * Posts
 */

Route::get('/posts', function () {

    return new \App\Http\Resources\PostCollection(Post::all());

});

/*
  * get single post

  */
Route::get('/posts/{id}', function ($id) {

    return new \App\Http\Resources\Post(Post::findOrFail($id));
});


Route::group(['middleware' => 'auth:api'], function () {

    // add Post
    Route::post('/posts', function (PostRequest $request) {

        $rqArray = $request->toArray();

        $post = new Post();
        $post->message = $rqArray['message'];
        $post->user_id = Auth::id();
        $post->save();

        return new \App\Http\Resources\Post($post);
    });


    /*
     * delete post
     */
    Route::delete('/posts/{id}', function ($id) {
        $posts = App\Post::find($id);

        $posts->delete();

        return response('ok', 200)->header('Content-Type', 'text/plain');
    });

    // edit Post
    Route::put('/posts/{id}', function (PostRequest $request, $id) {

        $rqArray = $request->toArray();
        $post = Post::find($id);
        $post->message = $rqArray['message'];

        $post->save();

        return new \App\Http\Resources\Post($post);
    });


// -------------------------------------------------------------------------

    // add Comment
    Route::post('/posts/{id}/comments', function (Request $request, $id) {

        $rqArray = $request->toArray();
        $comment = new \App\Comment();
        $comment->post_id = $id;
        $comment->user_id = Auth::id();
        $comment->message = $rqArray['message'];
        $comment->save();

        return new \App\Http\Resources\Comment($comment);
    });


    // edit Comment
    Route::put('/posts/{postId}/comments/{commentId}', function (Request $request, $postId, $commentId) {

        $rqArray = $request->toArray();

        $comment = Comment::find($commentId);
        $comment->message = $rqArray['message'];

        $comment->save();

        return new \App\Http\Resources\Comment($comment);
    });

    /*
     * delete Comment
     */
    Route::delete('/posts/{postId}/comments/{commentId}', function ($postId, $commentId) {
        $comment = App\Comment::find($commentId);
        if (!$comment) {
            return response('Not found', 404)->header('Content-Type', 'text/plain');
        }

        $comment->delete();

        return response('ok', 200)->header('Content-Type', 'text/plain');
    });

});


// get Comment
Route::get('/posts/{postID}/comments/{commentId}', function ($postId, $commentId) {
    $comment = App\Comment::find($commentId);

    if (!$comment) {
        return response('Not found', 404)->header('Content-Type', 'text/plain');
    }

    return new \App\Http\Resources\Comment($comment);
});

// get Comments
Route::get('/posts/{postId}/comments', function ($postId) {

    return new \App\Http\Resources\CommentWithPostId(Post::find($postId));
});