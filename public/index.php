<?php
require '../app/core/init.php';


$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$routes = [
    '/login' => '../app/Controller/LoginPage.php',
    '/logout' => '../app/Controller/Logout.php',
    '/logout2' => '../app/Controller/Logout2.php',
    '/adminHome' => '../app/Controller/AdminHome.php',
    '/facilitator' => '../app/Controller/Facilitator.php',
    '/delete_student' => '../app/Controller/DeleteStudent.php',
    '/edit_attendance'=>'../app/Controller/EditAttendance.php',
    '/update_attendance'=>'../app/Controller/UpdateAttendance.php',
    '/add_student'=>'../app/Controller/AddStudent.php',
    '/edit_student'=>'../app/Controller/EditStudent.php',
    '/edit_user' => '../app/Controller/EditUser.php',
    '/add_user' => '../app/Controller/AddUser.php',

    '/remove_sanction' => '../app/Controller/RemoveSanction.php',

    '/scanner' => '../app/Controller/QRScanner.php',
    '/upload_file' => '../app/Controller/UploadFile.php',
    '/student' => '../app/Controller/StudentHome.php',
    '/qr_code' => '../app/Controller/StudentQRCode.php',

    '/choose_attendance' => '../app/Controller/ChooseAttendance.php',
    '/add_attendance' => '../app/Controller/AddAttendance.php',
    '/create_attendance_for_student' => '../app/Controller/AttendanceSpecificStudents.php',
    '/delete_attendance' => '../app/Controller/DeleteAttendance.php',
    '/delete_attendance_record' => '../app/Controller/DeleteAttendanceRecord.php',

    '/view_records' => '../app/Controller/ViewAttendanceRecord.php',

    // '/' => '../app/Controller/LandingPage.php',

    // '/public/login' => '../app/Controller/LoginPage.php',
    // '/public/logout' => '../app/Controller/Logout.php',
    // '/public/logout2' => '../app/Controller/Logout2.php',
    // '/public/adminHome' => '../app/Controller/AdminHome.php',
    // '/public/delete_student' => '../app/Controller/DeleteStudent.php',
    // '/public/edit_attendance' => '../app/Controller/EditAttendance.php',
    // '/public/update_attendance' => '../app/Controller/UpdateAttendance.php',
    // '/public/add_student' => '../app/Controller/AddStudent.php',
    // '/public/edit_student' => '../app/Controller/EditStudent.php',
    // '/public/edit_user' => '../app/Controller/EditUser.php',
    // '/public/add_user' => '../app/Controller/AddUser.php',

    // '/public/sanctions_summary' => '../app/Controller/SanctionSummary.php',
    // '/public/student_attendance_summary' => '../app/Controller/StudentAttendanceSummary.php',
    // '/public/remove_sanction' => '../app/Controller/RemoveSanction.php',

    // '/public/facilitator' => '../app/Controller/Facilitator.php',
    // '/public/scanner' => '../app/Controller/QRScanner.php',
    // '/public/upload_file' => '../app/Controller/UploadFile.php',
    // '/public/student' => '../app/Controller/StudentHome.php',
    // '/public/qr_code' => '../app/Controller/StudentQRCode.php',

    // '/public/choose_attendance' => '../app/Controller/ChooseAttendance.php',

    // '/public/add_attendance' => '../app/Controller/AddAttendance.php',
    // '/public/create_attendance_for_student' => '../app/Controller/AttendanceSpecificStudents.php',
    // '/public/delete_attendance' => '../app/Controller/DeleteAttendance.php',
    // '/public/delete_attendance_record' => '../app/Controller/DeleteAttendanceRecord.php',

    // '/public/view_records' => '../app/Controller/ViewAttendanceRecord.php',
    // '/public/view_record2' => '../app/Controller/ViewAttendanceRecord2.php',
    // '/public/forgot-password' => '../app/Controller/ForgotPassword.php',
    // '/public/verify-otp' => '../app/Controller/VerifyOTP.php',
    // '/public/download-report' => '../app/Controller/PDFgeneration.php',

    // '/public/apply_excuse' => '../app/Controller/ApplyExcuse.php',
    // '/public/student_application' => '../app/Controller/StudentApplication.php',

    //maintenance Mode
    '/' => '../app/Controller/MaintenanceController.php',

    '/public/login' => '../app/Controller/MaintenanceController.php',
    //login2
    '/public/login2' => '../app/Controller/LoginPage.php',
    '/public/logout' => '../app/Controller/MaintenanceController.php',
    '/public/logout2' => '../app/Controller/MaintenanceController.php',
    '/public/adminHome' => '../app/Controller/MaintenanceController.php',
    '/public/delete_student' => '../app/Controller/MaintenanceController.php',
    '/public/edit_attendance' => '../app/Controller/MaintenanceController.php',
    '/public/update_attendance' => '../app/Controller/MaintenanceController.php',
    '/public/add_student' => '../app/Controller/MaintenanceController.php',
    '/public/edit_student' => '../app/Controller/MaintenanceController.php',
    '/public/edit_user' => '../app/Controller/MaintenanceController.php',
    '/public/add_user' => '../app/Controller/MaintenanceController.php',

    '/public/sanctions_summary' => '../app/Controller/MaintenanceController.php',
    '/public/student_attendance_summary' => '../app/Controller/MaintenanceController.php',
    '/public/remove_sanction' => '../app/Controller/MaintenanceController.php',

    '/public/facilitator' => '../app/Controller/MaintenanceController.php',
    '/public/scanner' => '../app/Controller/MaintenanceController.php',
    '/public/upload_file' => '../app/Controller/MaintenanceController.php',
    '/public/student' => '../app/Controller/MaintenanceController.php',
    '/public/qr_code' => '../app/Controller/MaintenanceController.php',

    '/public/choose_attendance' => '../app/Controller/MaintenanceController.php',

    '/public/add_attendance' => '../app/Controller/MaintenanceController.php',
    '/public/create_attendance_for_student' => '../app/Controller/MaintenanceController.php',
    '/public/delete_attendance' => '../app/Controller/MaintenanceController.php',
    '/public/delete_attendance_record' => '../app/Controller/MaintenanceController.php',

    '/public/view_records' => '../app/Controller/MaintenanceController.php',
    '/public/view_record2' => '../app/Controller/MaintenanceController.php',
    '/public/forgot-password' => '../app/Controller/MaintenanceController.php',
    '/public/verify-otp' => '../app/Controller/MaintenanceController.php',
    '/public/download-report' => '../app/Controller/MaintenanceController.php',


    // For prototype
    '/public/map' => '../app/Controller/Geofencing.php',
    '/public/face-register' => '../app/Controller/RegisterUI.php',
    '/public/face-recognize' => '../app/Controller/FaceRecognize.php',

    // localhost
    '/QRCodeAttendance/QRCodeAttendance/public/login' => '../app/Controller/LoginPage.php',
    '/QRCodeAttendance/QRCodeAttendance/public/logout' => '../app/Controller/Logout.php',
    '/QRCodeAttendance/QRCodeAttendance/public/logout2' => '../app/Controller/Logout2.php',
    '/QRCodeAttendance/QRCodeAttendance/public/adminHome' => '../app/Controller/AdminHome.php',
    '/QRCodeAttendance/QRCodeAttendance/public/delete_student' => '../app/Controller/DeleteStudent.php',
    '/QRCodeAttendance/QRCodeAttendance/public/edit_attendance' => '../app/Controller/EditAttendance.php',
    '/QRCodeAttendance/QRCodeAttendance/public/update_attendance' => '../app/Controller/UpdateAttendance.php',
    '/QRCodeAttendance/QRCodeAttendance/public/add_student' => '../app/Controller/AddStudent.php',
    '/QRCodeAttendance/QRCodeAttendance/public/edit_student' => '../app/Controller/EditStudent.php',
    '/QRCodeAttendance/QRCodeAttendance/public/edit_user' => '../app/Controller/EditUser.php',
    '/QRCodeAttendance/QRCodeAttendance/public/add_user' => '../app/Controller/AddUser.php',

    '/QRCodeAttendance/QRCodeAttendance/public/sanctions_summary' => '../app/Controller/SanctionSummary.php',
    '/QRCodeAttendance/QRCodeAttendance/public/student_attendance_summary' => '../app/Controller/StudentAttendanceSummary.php',
    '/QRCodeAttendance/QRCodeAttendance/public/remove_sanction' => '../app/Controller/RemoveSanction.php',

    '/QRCodeAttendance/QRCodeAttendance/public/facilitator' => '../app/Controller/Facilitator.php',
    '/QRCodeAttendance/QRCodeAttendance/public/scanner' => '../app/Controller/QRScanner.php',
    '/QRCodeAttendance/QRCodeAttendance/public/upload_file' => '../app/Controller/UploadFile.php',
    '/QRCodeAttendance/QRCodeAttendance/public/student' => '../app/Controller/StudentHome.php',
    '/QRCodeAttendance/QRCodeAttendance/public/qr_code' => '../app/Controller/StudentQRCode.php',

    '/QRCodeAttendance/QRCodeAttendance/public/choose_attendance' => '../app/Controller/ChooseAttendance.php',

    '/QRCodeAttendance/QRCodeAttendance/public/add_attendance' => '../app/Controller/AddAttendance.php',
    '/QRCodeAttendance/QRCodeAttendance/public/create_attendance_for_student' => '../app/Controller/AttendanceSpecificStudents.php',
    '/QRCodeAttendance/QRCodeAttendance/public/delete_attendance' => '../app/Controller/DeleteAttendance.php',
    '/QRCodeAttendance/QRCodeAttendance/public/delete_attendance_record' => '../app/Controller/DeleteAttendanceRecord.php',

    '/QRCodeAttendance/QRCodeAttendance/public/view_records' => '../app/Controller/ViewAttendanceRecord.php',
    '/QRCodeAttendance/QRCodeAttendance/public/view_record2' => '../app/Controller/ViewAttendanceRecord2.php',
    '/QRCodeAttendance/QRCodeAttendance/public/forgot-password' => '../app/Controller/ForgotPassword.php',
    '/QRCodeAttendance/QRCodeAttendance/public/verify-otp' => '../app/Controller/VerifyOTP.php',
    '/QRCodeAttendance/QRCodeAttendance/public/download-report' => '../app/Controller/PDFgeneration.php',

    '/QRCodeAttendance/QRCodeAttendance/public/apply_excuse' => '../app/Controller/ApplyExcuse.php',
    '/QRCodeAttendance/QRCodeAttendance/public/student_application' => '../app/Controller/StudentApplication.php',

    // For prototype
    '/QRCodeAttendance/QRCodeAttendance/public/map' => '../app/Controller/Geofencing.php',
    '/QRCodeAttendance/QRCodeAttendance/public/face-register' => '../app/Controller/RegisterUI.php',
    '/QRCodeAttendance/QRCodeAttendance/public/face-recognize' => '../app/Controller/FaceRecognize.php',

    


];




if (array_key_exists($uri, $routes)) {
    require $routes[$uri];
} else {
    require '../app/Controller/_404.php';
    die();
}