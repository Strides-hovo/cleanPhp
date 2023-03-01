<?php

namespace App\Controller;

use App\Model\User;
use App\Model\Comment;
use App\Core\BaseController;

class HomeController extends BaseController
{

    public function index()
    {
        $comments =  Comment::all();



        // dd($users);
        $this->view('default', ['comments' => $comments, 'title' => 'home Page']);
    }

}