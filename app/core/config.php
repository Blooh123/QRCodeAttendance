<?php
if($_SERVER['SERVER_NAME'] == 'localhost'){

    define('DBNAME', 'u753706103_qr_attendance');
    define('DBUSER', 'u753706103_christian');
    define('DBPASS', 'mZ2~G76JP1s5=B=Cy1L*');
    define('DBHOST', 'localhost');
    define('DBPORT', '3306');

    defined('ROOT') or define("ROOT", 'https://localhost/QRCodeAttendance/public/');

}else{
    define('DBNAME', 'u753706103_qr_attendance');
    define('DBUSER', 'u753706103_christian');
    define('DBPASS', 'mZ2~G76JP1s5=B=Cy1L*');
    define('DBHOST', 'localhost');
    define('DBPORT', '3306');

    defined('ROOT') or define("ROOT", 'https://usep-qrattendance.site/public/');
}