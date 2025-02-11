<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Middleware;
use App\Core\View;
use App\Models\Announcement;

class UserController extends Controller
{
    public function __construct()
    {
        Middleware::handleRole("user");
    }
    public function afficherAnnance()
    {
        $annances = Announcement::whereNull('deleted_at');
        return  View::render('user/index', ['annances' => $annances]);
    }
    public function deatailsAnnanace($id)
    {
        $annance = Announcement::find($id);
        if (!$annance) {
            return View::render('', ['message' => 'Annonce non trouvée']);
        }
        return  View::render('user/details', ['annance' => $annance]);
    }
}
