<?php

namespace Controller;
require_once '../app/core/config.php';
require_once '../app/Model/User.php';
require_once '../app/Model/ExcuseApplication.php';

use Controller;
use Model\User;



class StudentApplication extends Controller{
    public function index(): void
    {
        $user = new User();
        $userData = $user->checkSession('admin');

        if (!$userData || !isset($userData['role']) || $userData['role'] !== 'admin') {
            $uri = str_replace('/student_application', '/login', $_SERVER['REQUEST_URI']);
            header('Location: ' . $uri);
            exit();
        }

        // Handle POST requests for approve/reject actions
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'filter' || $action === 'search') {
                // Handle filter/search - reload the page with filtered data
                $this->loadFilteredApplications($userData);
                return;
            } else {
                $this->loadFilteredApplications($userData);
                return;
            }
        }

        // Default: show pending applications
        $this->loadFilteredApplications($userData);
    }

    private function loadFilteredApplications($userData): void
    {
        $excuseApp = new \Model\ExcuseApplication();
        $filter = $_POST['filter'] ?? '0'; // Default to pending
        $searchQuery = $_POST['search_query'] ?? '';
        
        // Get applications based on filter
        switch ($filter) {
            case '0':
                $applications = $excuseApp->getPendingExcuseApplications();
                break;
            case '1':
                $applications = $excuseApp->getApprovedExcuseApplications();
                break;
            case '2':
                $applications = $excuseApp->getRejectedExcuseApplications();
                break;
            case 'all':
            default:
                $applications = $excuseApp->getAllExcuseApplications();
                break;
        }
        
        // Apply search filter if provided
        if (!empty($searchQuery)) {
            $searchQuery = strtolower($searchQuery);
            $applications = $excuseApp->searchApplication($searchQuery);
        }
        
        // Get counts for stats
        $pendingCount = count($excuseApp->getPendingExcuseApplications());
        $approvedCount = count($excuseApp->getApprovedExcuseApplications());
        $rejectedCount = count($excuseApp->getRejectedExcuseApplications());
        
        $this->loadViewWithData('student_application', [
            'applications' => $applications,
            'userData' => $userData,
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
            'currentFilter' => $filter,
            'searchQuery' => $searchQuery
        ]);
    }



}

// Handle the application list
$studentApplication = new StudentApplication();
$studentApplication->index();