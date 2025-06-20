<?php 

namespace Controller;

class MaintenanceController extends \Controller
{
    public function index(): void
    {
        $this->loadView('maintenanceMode');
    }

   
}

$maintenanceController = new MaintenanceController();
$maintenanceController->index();