<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Core\JsonResource;
use App\Helpers\AndataExeption;
use App\Model\Comment;
use App\Requests\CommentRequest;

class CommentController extends BaseController
{

    /**
     * @throws AndataExeption
     */
    public function store(CommentRequest $request)
    {

        $id = Comment::set($request->validated());
        $comment = Comment::findOne($id);
        $response = (new JsonResource($comment))->jsonSerialize();
        echo $response;
    }

}