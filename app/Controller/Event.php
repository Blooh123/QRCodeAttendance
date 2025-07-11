<?php

require_once '../app/Model/Attendances.php';

class Event extends \Controller {
    public function index() {
        $attendanceModel = new \Model\Attendances();
        $allEvents = $attendanceModel->getAllAttendance();
    
        $this->loadViewWithData('events', [
            'allEvents' => $allEvents
        ]);
    }
}

$event = new Event();
$event->index();