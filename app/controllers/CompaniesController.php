<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Core\Auth;
use App\Models\Company;

class CompanyController extends Controller
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
        $companies = Company::orderBy('name')->get();
        View::render('companies/index', ['companies' => $companies]);
    }

    public function create()
    {
        if (!Auth::hasRole('admin')) {
            $this->error('Unauthorized access');
            header('Location: /companies');
            exit;
        }

        View::render('companies/create');
    }

    public function store()
    {
        if (!Auth::hasRole('admin')) {
            $this->error('Unauthorized access');
            header('Location: /companies');
            exit;
        }

        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description']
        ];

        Company::create($data);
        $this->success('Company created successfully');
        header('Location: /companies');
        exit;
    }

    public function edit($id)
    {
        if (!Auth::hasRole('admin')) {
            $this->error('Unauthorized access');
            header('Location: /companies');
            exit;
        }

        $company = Company::findOrFail($id);
        View::render('companies/edit', ['company' => $company]);
    }

    public function update($id)
    {
        if (!Auth::hasRole('admin')) {
            $this->error('Unauthorized access');
            header('Location: /companies');
            exit;
        }

        $company = Company::findOrFail($id);
        $company->update([
            'name' => $_POST['name'],
            'description' => $_POST['description']
        ]);

        $this->success('Company updated successfully');
        header('Location: /companies');
        exit;
    }

    public function delete($id)
    {
        if (!Auth::hasRole('admin')) {
            $this->error('Unauthorized access');
            header('Location: /companies');
            exit;
        }

        $company = Company::findOrFail($id);
        $company->delete();

        $this->success('Company deleted successfully');
        header('Location: /companies');
        exit;
    }
}