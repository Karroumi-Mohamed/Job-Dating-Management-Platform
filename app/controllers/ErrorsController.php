<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;

class ErrorsController extends Controller
{
    
    public function error_404()
{
    View::render('errors/404');
}


public function error_403()
{
    View::render('errors/403');
}

}