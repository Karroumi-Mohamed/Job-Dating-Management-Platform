<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Core\Auth;
use App\Core\Middleware;
use App\Core\Security;
use App\Models\Company;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function store()
    {
        $cleaned = Security::clean($_POST);
        if (!Security::validateCsrfToken($cleaned['token'])) {
            $this->error('Invalid Request');
            header('Location: /companies');
            exit;
        }

        $data = [
            'name' => $cleaned['name'],
            'description' => $cleaned['description']
        ];

        Company::create($data);
        $this->success('Company created successfully');
        header('Location: /companies');
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
            $this->error('Invalid Request');
            header('Location: /companies');
            exit;
        }

        try {
            $company = Company::find($id);
            
            if (empty($cleaned['name'])) {
                $this->error('Name is required');
                header('Location: /companies/edit/' . $id);
                exit;
            }
            
            $company->update([
                'name' => $cleaned['name'],
                'description' => $cleaned['description']
            ]);

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
            $this->error('Invalid Request');
            header('Location: /companies');
            exit;
        }

        try {
            $company = Company::findOrFail($id);
            
            // Check if company has related announcements before deletion
            if ($company->announcements()->count() > 0) {
                $this->error('Company has related announcements');
                header('Location: /companies');
                exit;
            }
            
            $company->delete();
            $this->success('Company deleted successfully');
            header('Location: /companies');
        } catch (\Exception $e) {
            $this->error('Company not found');
            header('Location: /companies');
        }
        exit;
    }
}
