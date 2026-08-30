<?php
if($_SERVER['SERVER_NAME'] == 'localhost'){

    define('DBNAME', 'qrcode_attendance_system');
    define('DBUSER', 'root');
    define('DBPASS', '');
    define('DBHOST', 'localhost');
    define('DBPORT', '3306');

    defined('ROOT') or define("ROOT", 'https://localhost/QRCodeAttendance/QRCodeAttendance/public/');

}else{
    //DATAbaSED CREDENTIALS ARE HERE

    // NEW DOMAIN
    defined('ROOT') or define("ROOT", 'https://usep-qrattendance.site/public/');
}
