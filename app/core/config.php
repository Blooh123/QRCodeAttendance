<?php
if($_SERVER['SERVER_NAME'] == 'localhost'){

    define('DBNAME', 'qrcode_attendance_system');
    define('DBUSER', 'root');
    define('DBPASS', '');
    define('DBHOST', 'localhost');
    define('DBPORT', '3306');

    defined('ROOT') or define("ROOT", 'https://localhost/QRCodeAttendance/QRCodeAttendance/public/');

}else{
    define('DBNAME', 'u753706103_qr_attendance');//u753706103_qr_attendance
    define('DBUSER', 'u753706103_christian');//u753706103_christian
    define('DBPASS', 'mZ2~G76JP1s5=B=Cy1L*');//mZ2~G76JP1s5=B=Cy1L*
    define('DBHOST', 'localhost');
    define('DBPORT', '3306');
    defined('ROOT') or define("ROOT", 'https://usep-qrattendance.site/public/');

    // define('DBNAME', 'qrcode_attendance_system');//u753706103_qr_attendance
    // define('DBUSER', 'root');//u753706103_christian
    // define('DBPASS', '');//mZ2~G76JP1s5=B=Cy1L*
    // define('DBHOST', 'localhost');
    // define('DBPORT', '3306');
    // defined('ROOT') or define("ROOT", 'http://192.168.104.67/QRCodeAttendance/QRCodeAttendance/public/');
    // // defined('ROOT') or define("ROOT", 'https://usep-qrattendance.site/public/');
}