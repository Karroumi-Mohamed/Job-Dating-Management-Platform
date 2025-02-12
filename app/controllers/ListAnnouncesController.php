<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Models\Announcement;

class ListAnnouncesController extends Controller
{
    public function index()
    {
        // Get all announcements with their companies
        $announcements = Announcement::with('company')->get();

        // Render the view with announcements
        View::render('announces/list', [
            'announcements' => $announcements
        ]);
    }
}
