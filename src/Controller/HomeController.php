<?php

namespace App\Controller;

use App\Model\User;
use App\Core\Request;
use App\Model\Comment;
use App\Core\BaseController;

class HomeController extends BaseController
{

    public function index( Request $request )
    {
        $comments =  Comment::all();
        
        $this->view('default', ['comments' => $comments, 'title' => 'home Page']);
    }

}