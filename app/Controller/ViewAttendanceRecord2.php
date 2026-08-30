<?php

namespace Controller;

use Model\Attendances;
require_once "../app/Model/Attendances.php";
class ViewAttendanceRecord2 extends \Controller
{
    public function index($data): void
    {
        $this->loadViewWithData('view_attendance_record2', $data);
    }

}
$attendance = new Attendances();
$sanctioned = $attendance->vwStudentSanctioned($_GET['eventName']);
$attendanceList = [];
$eventId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($eventId > 0) {
    $attendanceRecords = $attendance->getAttendanceRecord($eventId, '');
    $sanctionedIds = array_column($sanctioned, 'student_id');
    $attendanceList = array_filter($attendanceRecords, function ($record) use ($sanctionedIds) {
        return in_array($record['student_id'], $sanctionedIds, true);
    });
}
$data = [
    'sanctioned' => $sanctioned
    ,'attendanceList' => $attendanceList
    ,'EventID' => $eventId
    ,'EventName' => $_GET['eventName'] ?? ''
];
$viewAttendanceRecord2 = new ViewAttendanceRecord2();
$viewAttendanceRecord2->index($data);