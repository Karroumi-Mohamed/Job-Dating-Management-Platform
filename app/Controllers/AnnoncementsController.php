<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Core\Auth;
use App\Models\Announcement;
use App\Models\Company;

class AnnouncementController extends Controller
{
    public function __construct()
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }
    }

    public function index()
    {
        $announcements = Announcement::with('company')->orderBy('created_at', 'desc')->get();
        View::render('announcements/index', ['announcements' => $announcements]);
    }

    public function create()
    {
        if (!Auth::hasRole('admin')) {
            $this->error('Unauthorized access');
            header('Location: /announcements');
            exit;
        }

        $companies = Company::all();
        View::render('announcements/create', ['companies' => $companies]);
    }

    public function store()
    {
        if (!Auth::hasRole('admin')) {
            $this->error('Unauthorized access');
            header('Location: /announcements');
            exit;
        }

        $data = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'company_id' => $_POST['company_id']
        ];

        Announcement::create($data);
        $this->success('Announcement created successfully');
        header('Location: /announcements');
        exit;
    }

    public function edit($id)
    {
        if (!Auth::hasRole('admin')) {
            $this->error('Unauthorized access');
            header('Location: /announcements');
            exit;
        }

        $announcement = Announcement::findOrFail($id);
        $companies = Company::all();
        View::render('announcements/edit', [
            'announcement' => $announcement,
            'companies' => $companies
        ]);
    }

    public function update($id)
    {
        if (!Auth::hasRole('admin')) {
            $this->error('Unauthorized access');
            header('Location: /announcements');
            exit;
        }

        $announcement = Announcement::findOrFail($id);
        $announcement->update([
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'company_id' => $_POST['company_id']
        ]);

        $this->success('Announcement updated successfully');
        header('Location: /announcements');
        exit;
    }

    public function delete($id)
    {
        if (!Auth::hasRole('admin')) {
            $this->error('Unauthorized access');
            header('Location: /announcements');
            exit;
        }

        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        $this->success('Announcement deleted successfully');
        header('Location: /announcements');
        exit;
    }

    public function restore($id)
    {
        if (!Auth::hasRole('admin')) {
            $this->error('Unauthorized access');
            header('Location: /announcements');
            exit;
        }

        $announcement = Announcement::withTrashed()->findOrFail($id);
        $announcement->restore();

        $this->success('Announcement restored successfully');
        header('Location: /announcements');
        exit;
    }
}