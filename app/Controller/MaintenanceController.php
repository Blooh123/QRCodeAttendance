<?php 

namespace Controller;

class MaintenanceController extends \Controller
{
    public function index(): void
    {
        $this->loadView('maintenance');
    }

   
}

$maintenanceController = new MaintenanceController();
$maintenanceController->index();