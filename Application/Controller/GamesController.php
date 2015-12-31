<?php
namespace Application\Controller;

use Ouzo\Controller;

class GamesController extends Controller
{
    public function init()
    {
        $this->layout->setLayout('sample_layout');
    }

    public function index()
    {
        $this->view->render();
    }
}