<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Core\Auth;
use App\Core\Middleware;
use App\Core\Security;
use App\Models\Announcement;
use App\Models\Company;

class AnnoncementsController extends Controller
{
    public function __construct()
    {
        Middleware::handleRole('admin');
    }

    public function index()
    {
        $announcements = Announcement::with('company')->orderBy('created_at', 'desc')->get();
        View::render('announcements/index', ['announcements' => $announcements]);
    }

    public function create()
    {
        $companies = Company::orderBy('name')->get();
        View::render('announcements/create', ['companies' => $companies]);
    }

    public function store()
    {
        $cleaned = Security::clean($_POST);
        if (!Security::validateCsrfToken($cleaned['token'])) {
            $this->error('Invalid Request');
            header('Location: /announcements');
            exit;
        }

        try {
            if (empty($cleaned['title']) || empty($cleaned['description']) || empty($cleaned['company_id'])) {
                throw new \Exception('All fields are required');
            }

            // Verify company exists
            Company::findOrFail($cleaned['company_id']);

            $data = [
                'title' => $cleaned['title'],
                'description' => $cleaned['description'],
                'company_id' => $cleaned['company_id']
            ];

            Announcement::create($data);
            $this->success('Announcement created successfully');
            header('Location: /announcements');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            header('Location: /announcements/create');
        }
        exit;
    }

    public function edit($id)
    {
        try {
            $announcement = Announcement::findOrFail($id);
            $companies = Company::orderBy('name')->get();
            View::render('announcements/edit', [
                'announcement' => $announcement,
                'companies' => $companies
            ]);
        } catch (\Exception $e) {
            $this->error('Announcement not found');
            header('Location: /announcements');
            exit;
        }
    }

    public function update($id)
    {
        $cleaned = Security::clean($_POST);
        if (!Security::validateCsrfToken($cleaned['token'])) {
            $this->error('Invalid Request');
            header('Location: /announcements');
            exit;
        }

        try {
            $announcement = Announcement::findOrFail($id);
            
            if (empty($cleaned['title']) || empty($cleaned['description']) || empty($cleaned['company_id'])) {
                throw new \Exception('All fields are required');
            }

            // Verify company exists
            Company::findOrFail($cleaned['company_id']);
            
            $announcement->update([
                'title' => $cleaned['title'],
                'description' => $cleaned['description'],
                'company_id' => $cleaned['company_id']
            ]);

            $this->success('Announcement updated successfully');
            header('Location: /announcements');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            header('Location: /announcements/edit/' . $id);
        }
        exit;
    }

    public function delete($id)
    {
        $cleaned = Security::clean($_POST);
        if (!Security::validateCsrfToken($cleaned['token'])) {
            $this->error('Invalid Request');
            header('Location: /announcements');
            exit;
        }

        try {
            $announcement = Announcement::findOrFail($id);
            $announcement->delete();
            $this->success('Announcement deleted successfully');
        } catch (\Exception $e) {
            $this->error('Announcement not found');
        }

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

    public function trash()
    {
        $trashAnnouncements = Announcement::onlyTrashed()->orderBy('title')->get();
        View::render('announcement/trash', ['announcements' => $trashAnnouncements]);
    }
}