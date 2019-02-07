<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CommentWithPostId extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'postId' => $this->id,
            'comments' => Comment::collection($this->comments),
        ];
    }
}
