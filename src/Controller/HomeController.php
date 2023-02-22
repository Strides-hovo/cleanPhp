<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Model\Comment;

class HomeController extends BaseController
{

    public function index()
    {
        $comments = (Comment::all());
        $this->view('default', ['comments' => $comments, 'title' => 'home Page']);

    }

}