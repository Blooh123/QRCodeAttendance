<?php

namespace Controller;

require_once '../app/Model/ActivityLog.php';
use Model\ActivityLog;

Class ActivityLogs extends \Controller
{
    public function index(): void
    {
        $activityLog = new ActivityLog();
        $logs = $activityLog->getAllActivityLogs();
        
        $data = [
            'activityLogs' => $logs
        ];
        
        $this->loadViewWithData('activityLogs', $data);
    }
}
$activityLog = new ActivityLogs();
$activityLog->index();