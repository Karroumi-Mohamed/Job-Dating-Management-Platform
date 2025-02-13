<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Core\Auth;
use App\Core\Middleware;
use App\Core\Security;
use App\Models\Announcement;
use App\Models\Company;
use App\Core\FileUploader;

class AnnoncementsController extends Controller
{
    public function __construct()
    {
        Middleware::handleRole('admin');
    }

    public function index()
    {
        $announcements = Announcement::with('company')->orderBy('created_at', 'desc')->get();
        $companies = Company::orderBy('name')->get();
        View::render('announcements/index', [
            'announcements' => $announcements,
            'companies' => $companies
        ]);
    }

    public function getAnnouncementsTable()
    {
        $announcements = Announcement::with('company')->orderBy('created_at', 'desc')->get();
        return View::render('announcements/_table', ['announcements' => $announcements], true);
    }

    public function getTrashTable()
    {
        $announcements = Announcement::onlyTrashed()->with('company')->orderBy('created_at', 'desc')->get();
        return View::render('announcements/_trash_table', ['announcements' => $announcements], true);
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
            return $this->jsonResponse(['error' => 'Invalid Request'], 403);
        }

        try {
            if (empty($cleaned['title']) || empty($cleaned['description']) || empty($cleaned['company_id'])) {
                throw new \Exception('All fields are required');
            }

            Company::findOrFail($cleaned['company_id']);

            $data = [
                'title' => $cleaned['title'],
                'description' => $cleaned['description'],
                'company_id' => $cleaned['company_id']
            ];

            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $imagePath = FileUploader::upload($_FILES['image'], 'announcements/');
                if ($imagePath) {
                    $data['image'] = $imagePath;
                }
            }

            Announcement::create($data);

            if ($this->isApiRequest()) {
                return $this->jsonResponse(['message' => 'Announcement created successfully']);
            }

            $this->success('Announcement created successfully');
            header('Location: /announcements');
        } catch (\Exception $e) {
            if ($this->isApiRequest()) {
                return $this->jsonResponse(['error' => $e->getMessage()], 400);
            }
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
            return $this->jsonResponse(['error' => 'Invalid Request'], 403);
        }

        try {
            $announcement = Announcement::findOrFail($id);

            if (empty($cleaned['title']) || empty($cleaned['description']) || empty($cleaned['company_id'])) {
                throw new \Exception('All fields are required');
            }

            Company::findOrFail($cleaned['company_id']);

            $data = [
                'title' => $cleaned['title'],
                'description' => $cleaned['description'],
                'company_id' => $cleaned['company_id']
            ];

            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                // Delete old image if exists
                if ($announcement->image) {
                    FileUploader::delete($announcement->image);
                }
                
                $imagePath = FileUploader::upload($_FILES['image'], 'announcements/');
                if ($imagePath) {
                    $data['image'] = $imagePath;
                }
            }

            $announcement->update($data);

            if ($this->isApiRequest()) {
                return $this->jsonResponse(['message' => 'Announcement updated successfully']);
            }

            $this->success('Announcement updated successfully');
            header('Location: /announcements');
        } catch (\Exception $e) {
            if ($this->isApiRequest()) {
                return $this->jsonResponse(['error' => $e->getMessage()], 400);
            }
            $this->error($e->getMessage());
            header('Location: /announcements/edit/' . $id);
        }
        exit;
    }

    public function delete($id)
    {
        $cleaned = Security::clean($_POST);
        if (!Security::validateCsrfToken($cleaned['token'])) {
            return $this->jsonResponse(['error' => 'Invalid Request'], 403);
        }

        try {
            $announcement = Announcement::findOrFail($id);

            if ($announcement->image) {
                FileUploader::delete($announcement->image);
            }
            
            $announcement->delete();

            if ($this->isApiRequest()) {
                return $this->jsonResponse(['message' => 'Announcement deleted successfully']);
            }

            $this->success('Announcement deleted successfully');
        } catch (\Exception $e) {
            if ($this->isApiRequest()) {
                return $this->jsonResponse(['error' => $e->getMessage()], 400);
            }
            $this->error('Announcement not found');
        }

        if (!$this->isApiRequest()) {
            header('Location: /announcements');
            exit;
        }
    }

    public function restore($id)
    {
        $cleaned = Security::clean($_POST);
        if (!Security::validateCsrfToken($cleaned['token'])) {
            return $this->jsonResponse(['error' => 'Invalid Request'], 403);
        }

        try {
            $announcement = Announcement::withTrashed()->findOrFail($id);
            $announcement->restore();

            if ($this->isApiRequest()) {
                return $this->jsonResponse(['message' => 'Announcement restored successfully']);
            }

            $this->success('Announcement restored successfully');
        } catch (\Exception $e) {
            if ($this->isApiRequest()) {
                return $this->jsonResponse(['error' => $e->getMessage()], 400);
            }
            $this->error('Announcement not found');
        }

        if (!$this->isApiRequest()) {
            header('Location: /announcements');
            exit;
        }
    }

    public function trash()
    {
        $announcements = Announcement::onlyTrashed()->with('company')->orderBy('created_at', 'desc')->get();
        View::render('announcements/trash', ['announcements' => $announcements]);
    }

    private function isApiRequest()
    {
        return strpos($_SERVER['REQUEST_URI'], '/api/') === 0;
    }

    private function jsonResponse($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
