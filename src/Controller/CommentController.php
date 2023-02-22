<?php

namespace App\Controller;

use App\Core\JsonResource;
use App\Model\Comment;
use App\Requests\CommentRequest;

class CommentController
{

    public function store(CommentRequest $request)
    {
        $id = Comment::set($request->all());
        $comment = Comment::findOne($id);
        $response = (new JsonResource($comment))->jsonSerialize();
        echo $response;
    }

}