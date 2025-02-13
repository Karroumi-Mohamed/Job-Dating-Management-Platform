<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Middleware;
use App\Core\View;
use App\Models\Announcement;

class ListAnnouncesController extends Controller
{
    public function __construct()
    {
        Middleware::handleRole('learner');
    }
    public function index()
    {
        $announcements = Announcement::with('company')->get();

        View::render('announcements/list', [
            'announcements' => $announcements
        ]);
    }
}
