<?php

namespace Controller;
require "../app/core/Model.php";
require "../app/Model/Attendances.php";
require_once '../app/core/Controller.php';
use Controller;
use Model\Attendances;

class Attendance extends Controller
{
    public function index(): void
    {
        $attendance = new Attendances();
        $attendanceList = $attendance->getAllAttendance();

        if (!empty($_GET['search'])){
            $attendanceList =$attendance->searchAttendance($_GET['search']);
        }
        

        
        $data = [
            'attendanceList' => $attendanceList
        ];

        $this->loadViewWithData('attendanceAdmin', $data);
    }
}

$attendance = new Attendance();


$attendance->index();