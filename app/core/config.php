<?php
if($_SERVER['SERVER_NAME'] == 'localhost'){

    define('DBNAME', 'qrcode_attendance_system');
    define('DBUSER', 'root');
    define('DBPASS', '');
    define('DBHOST', 'localhost');
    define('DBPORT', '3306');

    defined('ROOT') or define("ROOT", 'https://localhost/QRCodeAttendance/QRCodeAttendance/public/');

}else{

    // OLD HOSTINGER DATABASE
    define('DBNAME', 'u753706103_qr_attendance');
    define('DBUSER', 'u753706103_christian');
    define('DBPASS', 'BloohGameDev123#');

    // REMOTE MYSQL HOST
    define('DBHOST', 'srv1401.hstgr.io');
    // OR: 193.203.184.83

    define('DBPORT', '3306');

    // NEW DOMAIN
    defined('ROOT') or define("ROOT", 'https://usepqrattendance.site/public/');
}