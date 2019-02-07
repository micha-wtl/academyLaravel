<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Post extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
            'author' => [
                'id' => $this->user_id,
                'name' => $this->user->name,
                'email' => $this->user->email
            ],
            'comments' => (new CommentCollection($this->comments))->collection,
        ];
    }
}