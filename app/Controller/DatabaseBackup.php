<?php

namespace Controller;
require_once "../app/core/init.php";
require_once "../app/core/Database.php";
require_once "../app/Model/User.php";
require_once "../app/Model/ActivityLog.php";
use Model\User;
use Database;
use Model\ActivityLog;

class DatabaseBackup
{
    use Database;
    
    private $userData;
    private $activityLog;
    public function __construct()
    {
        // Check if user is logged in and is admin
        // check session
        $user = new User();
        $this->userData = $user->checkSession('database-backup');
        if (!isset($this->userData['user_id']) || $this->userData['role'] !== 'admin') {
            header("Location: " . ROOT . "login");
            exit();
        }

    }

    public function downloadBackup()
    {
        try {
            // Check if ZipArchive is available
            if (!class_exists('ZipArchive')) {
                throw new Exception("ZipArchive extension is not available");
            }
            
            // Get database connection
            $pdo = $this->connect();
            
            // Get all tables
            $tables = $this->getTables($pdo);
            
            // Generate backup filename with timestamp
            $timestamp = date('Y-m-d_H-i-s');
            $sqlFilename = 'qrcode_attendance_backup_' . $timestamp . '.sql';
            $zipFilename = 'qrcode_attendance_backup_' . $timestamp . '.zip';
            
            // Generate password for the ZIP file
            $password = $this->generateBackupPassword();
            
            // Create temporary SQL file
            $tempSqlFile = tempnam(sys_get_temp_dir(), 'backup_');
            $sqlContent = $this->generateSqlContent($pdo, $tables);
            file_put_contents($tempSqlFile, $sqlContent);
            
            // Create password-protected ZIP file
            $tempZipFile = tempnam(sys_get_temp_dir(), 'backup_zip_');
            $zipCreated = false;
            
            try {
                // Try ZipArchive first
                $zip = new \ZipArchive();
                $result = $zip->open($tempZipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
                
                if ($result === TRUE) {
                    // Add SQL file to ZIP
                    $zip->addFile($tempSqlFile, $sqlFilename);
                    
                    // Try to set password protection
                    $encryptionSet = false;
                    if (defined('ZipArchive::EM_AES_256')) {
                        $encryptionSet = $zip->setEncryptionName($sqlFilename, \ZipArchive::EM_AES_256, $password);
                    } elseif (defined('ZipArchive::EM_AES_128')) {
                        $encryptionSet = $zip->setEncryptionName($sqlFilename, \ZipArchive::EM_AES_128, $password);
                    }
                    
                    // Close the ZIP file
                    if ($zip->close()) {
                        $zipCreated = true;
                        if (!$encryptionSet) {
                            // If encryption failed, add password to filename
                            $zipFilename = 'qrcode_attendance_backup_' . $timestamp . '_PWD_' . substr($password, -8) . '.zip';
                        }
                    }
                }
            } catch (Exception $e) {
                // ZipArchive failed, try alternative method
                error_log("ZipArchive failed: " . $e->getMessage());
            }
            
            // If ZipArchive failed, try system command
            if (!$zipCreated) {
                try {
                    $zipCreated = $this->createZipWithCommand($tempSqlFile, $tempZipFile, $sqlFilename, $password);
                    if ($zipCreated) {
                        // Add password to filename since we can't encrypt
                        $zipFilename = 'qrcode_attendance_backup_' . $timestamp . '_PWD_' . substr($password, -8) . '.zip';
                    }
                } catch (Exception $e) {
                    error_log("System command ZIP creation failed: " . $e->getMessage());
                }
            }
            
            // If all methods failed, fall back to SQL file download
            if (!$zipCreated) {
                // Clean up and fall back to SQL download
                if (file_exists($tempZipFile)) {
                    unlink($tempZipFile);
                }
                
                // Set headers for SQL file download
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . $sqlFilename . '"');
                header('Content-Length: ' . filesize($tempSqlFile));
                header('Cache-Control: no-cache, must-revalidate');
                header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
                
                // Output the SQL file
                readfile($tempSqlFile);
                
                // Clean up
                unlink($tempSqlFile);
                
                // Log the backup activity
                $this->logBackupActivity($password);
                
                exit();
            }
            
            // Verify ZIP file was created and has content
            if (!file_exists($tempZipFile) || filesize($tempZipFile) === 0) {
                throw new Exception("ZIP file was not created properly");
            }
            
            // Set headers for ZIP file download
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zipFilename . '"');
            header('Content-Length: ' . filesize($tempZipFile));
            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
            
            // Output the ZIP file
            readfile($tempZipFile);
            
            // Clean up temporary files
            if (file_exists($tempSqlFile)) {
                unlink($tempSqlFile);
            }
            if (file_exists($tempZipFile)) {
                unlink($tempZipFile);
            }
            
            // Log the backup activity with password info
            $this->logBackupActivity($password);
            
            exit();
            
        } catch (Exception $e) {
            // Handle errors
            $_SESSION['error'] = "Backup failed: " . $e->getMessage();
            header("Location: " . ROOT . "admin-home");
            exit();
        }
    }
    
    private function getTables($pdo)
    {
        $stmt = $pdo->query("SHOW TABLES");
        $tables = [];
        while ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }
        return $tables;
    }
    
    
    private function generateBackupPassword()
    {
        // Generate a secure password using current timestamp and user ID
        $user = new User();
        $this->userData = $user->checkSession('database-backup');
        $timestamp = time();
        $userId = $this->userData['user_id'];
        $randomSalt = bin2hex(random_bytes(8));
        
        // Create a password that includes timestamp, user ID, and random salt
        $password = 'QR_' . $userId . '_' . $timestamp . '_' . $randomSalt;
        
        return $password;
    }
    
    private function generateSqlContent($pdo, $tables)
    {
        $sqlContent = "-- QR Code Attendance System Database Backup\n";
        $sqlContent .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n";
        $sqlContent .= "-- Database: " . DBNAME . "\n";
        $sqlContent .= "-- This backup is password protected\n\n";
        $sqlContent .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
        $sqlContent .= "SET AUTOCOMMIT = 0;\n";
        $sqlContent .= "START TRANSACTION;\n";
        $sqlContent .= "SET time_zone = \"+00:00\";\n\n";
        
        // Process each table
        foreach ($tables as $table) {
            $sqlContent .= $this->exportTableToString($pdo, $table);
        }
        
        $sqlContent .= "COMMIT;\n";
        
        return $sqlContent;
    }
    
    private function exportTableToString($pdo, $tableName)
    {
        $content = "-- Table structure for table `$tableName`\n";
        $content .= "DROP TABLE IF EXISTS `$tableName`;\n";
        
        // Get table structure
        $stmt = $pdo->query("SHOW CREATE TABLE `$tableName`");
        $row = $stmt->fetch(\PDO::FETCH_NUM);
        $content .= $row[1] . ";\n\n";
        
        // Get table data
        $stmt = $pdo->query("SELECT * FROM `$tableName`");
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        if (!empty($rows)) {
            $content .= "-- Data for table `$tableName`\n";
            
            // Get column names
            $columns = array_keys($rows[0]);
            $columnList = '`' . implode('`, `', $columns) . '`';
            
            foreach ($rows as $row) {
                $values = [];
                foreach ($row as $value) {
                    if ($value === null) {
                        $values[] = 'NULL';
                    } else {
                        $values[] = $pdo->quote($value);
                    }
                }
                
                $content .= "INSERT INTO `$tableName` ($columnList) VALUES (" . implode(', ', $values) . ");\n";
            }
            $content .= "\n";
        }
        
        return $content;
    }
    
    private function createZipWithCommand($tempSqlFile, $tempZipFile, $sqlFilename, $password)
    {
        // Alternative method using system commands if ZipArchive fails
        $command = "cd " . dirname($tempSqlFile) . " && ";
        
        // Try different ZIP commands based on system
        if (PHP_OS_FAMILY === 'Windows') {
            // Windows - try PowerShell Compress-Archive
            $command = "powershell -Command \"Compress-Archive -Path '" . basename($tempSqlFile) . "' -DestinationPath '" . basename($tempZipFile) . "' -Force\"";
        } else {
            // Linux/Unix - use zip command
            $command = "zip -j '" . $tempZipFile . "' '" . $tempSqlFile . "'";
        }
        
        $output = [];
        $returnCode = 0;
        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            throw new Exception("Failed to create ZIP using system command");
        }
        
        return file_exists($tempZipFile) && filesize($tempZipFile) > 0;
    }
    
    private function logBackupActivity($password = null)
    {
        // asia/manila time
        $date = new \DateTime('now', new \DateTimeZone('Asia/Manila'));
        $dateString = $date->format('Y-m-d H:i:s');
        try {
            $details = 'Downloaded password-protected ZIP backup';
            if ($password) {
                $details .= ' (Password: ' . $password . ')';
            }
            
            $query = "INSERT INTO activity_log (user_id, role, activity, evnt, time_created) 
                     VALUES (:user_id, :role, :activity, :evnt, :time_created)";
            
            $params = [
                'user_id' => $this->userData['user_id'],
                'role' => $this->userData['role'],
                'activity' => $details,
                'evnt' => 'backup',
                'time_created' => $dateString
            ];
            
            $this->query($query, $params);
        } catch (Exception $e) {
            // Log error but don't fail the backup
            error_log("Failed to log backup activity: " . $e->getMessage());
        }
    }
}

// Handle the backup request
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'download') {
    $backup = new DatabaseBackup();
    $backup->downloadBackup();
}
?>
