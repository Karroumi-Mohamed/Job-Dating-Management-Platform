<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Company;
use App\Models\Announcement;
use App\Core\Security;
use App\Core\View;

class AdminController extends Controller
{
    public function __construct()
    {
        if (!Auth::hasRole('admin')) {
            header('Location: /403');
            exit();
        }
    }

    public function dashboard()
    {
        $companies = Company::all();
        $announcements = Announcement::all();
        
        View::render('admin/dashboard', [
            'companies' => $companies,
            'announcements' => $announcements
        ]);
    }

    public function createCompany()
    {
        $data = Security::clean($_POST);
        
        try {
            Company::create([
                'name' => $data['name'],
                'description' => $data['description']
            ]);
            
            $this->success('Entreprise créée avec succès');
            header('Location: /admin/companies');
            exit();
        } catch (\Exception $e) {
            $this->error('Erreur lors de la création de l\'entreprise');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }

    public function editCompany($id)
    {
        $data = Security::clean($_POST);
        
        try {
            $company = Company::findOrFail($id);
            $company->update([
                'name' => $data['name'],
                'description' => $data['description']
            ]);
            
            $this->success('Entreprise mise à jour avec succès');
            header('Location: /admin/companies');
            exit();
        } catch (\Exception $e) {
            $this->error('Erreur lors de la mise à jour de l\'entreprise');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }

    public function createAnnouncement()
    {
        $data = Security::clean($_POST);
        
        try {
            Announcement::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'company_id' => $data['company_id']
            ]);
            
            $this->success('Annonce créée avec succès');
            header('Location: /admin/announcements');
            exit();
        } catch (\Exception $e) {
            $this->error('Erreur lors de la création de l\'annonce');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }

    public function editAnnouncement($id)
    {
        $data = Security::clean($_POST);
        
        try {
            $announcement = Announcement::findOrFail($id);
            $announcement->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'company_id' => $data['company_id']
            ]);
            
            $this->success('Annonce mise à jour avec succès');
            header('Location: /admin/announcements');
            exit();
        } catch (\Exception $e) {
            $this->error('Erreur lors de la mise à jour de l\'annonce');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }

    public function companies()
    {
        $companies = Company::all();
        View::render('admin/companies', [
            'companies' => $companies
        ]);
    }

    public function announcements()
    {
        $announcements = Announcement::all();
        $companies = Company::all();
        View::render('admin/announcements', [
            'announcements' => $announcements,
            'companies' => $companies
        ]);
    }
} 