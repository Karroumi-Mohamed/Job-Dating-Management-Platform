<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Core\Auth;
use App\Core\Middleware;
use App\Core\Security;
use App\Models\Company;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Core\FileUploader;

class CompaniesController extends Controller
{
    public function __construct()
    {
        Middleware::handleRole('admin');
    }

    public function index()
    {
        $companies = Company::orderBy('name')->get();
        View::render('companies/index', ['companies' => $companies]);
    }

    public function create()
    {
        View::render('companies/create');
    }

    public function getCompaniesTable()
    {
        $companies = Company::orderBy('name')->get();
        return View::render('companies/_table', ['companies' => $companies], true);
    }

    public function store()
    {
        $cleaned = Security::clean($_POST);
        if (!Security::validateCsrfToken($cleaned['token'])) {
            return $this->jsonResponse(['error' => 'Invalid Request'], 403);
        }

        try {
            if (empty($cleaned['name'])) {
                throw new \Exception('Name is required');
            }

            $data = [
                'name' => $cleaned['name'],
                'description' => $cleaned['description']
            ];


            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
                $logoPath = FileUploader::upload($_FILES['logo'], 'companies/');
                if ($logoPath === false) {
                    throw new \Exception('Error uploading logo');
                }
                $data['logo'] = $logoPath;
            }

            $company = Company::create($data);
            


            if ($this->isApiRequest()) {
                return $this->jsonResponse(['message' => 'Company created successfully']);
            }

            $this->success('Company created successfully');
            header('Location: /companies');
        } catch (\Exception $e) {
            error_log('Error creating company: ' . $e->getMessage());
            if ($this->isApiRequest()) {
                return $this->jsonResponse(['error' => $e->getMessage()], 400);
            }
            $this->error($e->getMessage());
            header('Location: /companies/create');
        }
        exit;
    }

    public function edit($id)
    {
        try {
            $company = Company::findOrFail($id);
            View::render('companies/edit', ['company' => $company]);
        } catch (\Exception $e) {
            $this->error('Company not found');
            header('Location: /companies');
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
            $company = Company::findOrFail($id);
            
            if (empty($cleaned['name'])) {
                throw new \Exception('Name is required');
            }
            
            $data = [
                'name' => $cleaned['name'],
                'description' => $cleaned['description']
            ];


            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {

                if ($company->logo) {
                    FileUploader::delete($company->logo);
                }
                
                $logoPath = FileUploader::upload($_FILES['logo'], 'companies/');
                if ($logoPath) {
                    $data['logo'] = $logoPath;
                }
            }
            
            $company->update($data);

            $this->success('Company updated successfully');
            header('Location: /companies');
        } catch (\Exception $e) {
            $this->error('Company not found');
            header('Location: /companies');
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
            $company = Company::findOrFail($id);

            if ($company->logo) {
                FileUploader::delete($company->logo);
            }

            if ($company->announcements()->count() > 0) {
                if ($this->isApiRequest()) {
                    return $this->jsonResponse(['error' => 'Cannot delete company with existing announcements'], 400);
                }
                $this->error('Company has related announcements');
                header('Location: /companies');
                exit;
            }
            
            $company->delete();

            if ($this->isApiRequest()) {
                return $this->jsonResponse(['message' => 'Company deleted successfully']);
            }

            $this->success('Company deleted successfully');
            header('Location: /companies');
        } catch (\Exception $e) {
            if ($this->isApiRequest()) {
                return $this->jsonResponse(['error' => $e->getMessage()], 400);
            }
            $this->error('Company not found');
            header('Location: /companies');
        }
        exit;
    }

    public function restore($id)
    {
        $cleaned = Security::clean($_POST);
        if (!Security::validateCsrfToken($cleaned['token'])) {
            return $this->jsonResponse(['error' => 'Invalid Request'], 403);
        }

        try {
            $company = Company::withTrashed()->findOrFail($id);
            $company->restore();

            if ($this->isApiRequest()) {
                return $this->jsonResponse(['message' => 'Company restored successfully']);
            }

            $this->success('Company restored successfully');
        } catch (\Exception $e) {
            if ($this->isApiRequest()) {
                return $this->jsonResponse(['error' => $e->getMessage()], 400);
            }
            $this->error('Company not found');
        }

        if (!$this->isApiRequest()) {
            header('Location: /companies');
            exit;
        }
    }

    public function trash()
    {
        $trashCompanies = Company::onlyTrashed()->orderBy('name')->get();
        View::render('companies/trash', ['companies' => $trashCompanies]);
    }

    public function getTrashTable()
    {
        $companies = Company::onlyTrashed()->orderBy('name')->get();
        return View::render('companies/_trash_table', ['companies' => $companies], true);
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
