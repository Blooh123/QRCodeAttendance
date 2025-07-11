<?php
/**
 * Background Email Queue Processor
 * This script processes email queues in batches to handle large numbers of students
 */

require_once '../vendor/autoload.php';
require_once '../app/core/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Check if email queue file is provided
if ($argc < 2) {
    error_log("Email queue processor: No queue file specified");
    exit(1);
}

$emailQueueFile = $argv[1];

// Check if file exists
if (!file_exists($emailQueueFile)) {
    error_log("Email queue processor: Queue file not found: " . $emailQueueFile);
    exit(1);
}

try {
    // Read email data
    $emailData = json_decode(file_get_contents($emailQueueFile), true);
    if (!$emailData) {
        error_log("Email queue processor: Invalid JSON data in queue file");
        exit(1);
    }
    
    $students = $emailData['students'];
    $eventName = $emailData['eventName'];
    $description = $emailData['description'];
    $sanction = $emailData['sanction'];
    $programs = $emailData['programs'];
    $years = $emailData['years'];
    $totalStudents = count($students);
    
    error_log("Email queue processor: Starting to process {$totalStudents} students");
    
    // Initialize PHPMailer
    $mail = new PHPMailer(true);
    
    // Server settings
    $mail->SMTPDebug = SMTP::DEBUG_OFF;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'usep.qrattendance@gmail.com';
    $mail->Password = 'vvyg egpy egtv ajms';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    
    // Set sender
    $mail->setFrom('usep.qrattendance@gmail.com', 'USeP QR Attendance System');
    $mail->addReplyTo('usep.qrattendance@gmail.com', 'USeP QR Attendance System');
    
    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'New Attendance Event: ' . htmlspecialchars($eventName);
    
    // Create email body
    $emailBody = createEmailBody($eventName, $description, $sanction, $programs, $years);
    $mail->Body = $emailBody;
    $mail->AltBody = createPlainTextBody($eventName, $description, $sanction, $programs, $years);
    
    // Process in batches
    $batchSize = 25; // Smaller batch size for background processing
    $successCount = 0;
    $errorCount = 0;
    $processedCount = 0;
    
    for ($i = 0; $i < $totalStudents; $i += $batchSize) {
        $batch = array_slice($students, $i, $batchSize);
        $batchNumber = floor($i / $batchSize) + 1;
        $totalBatches = ceil($totalStudents / $batchSize);
        
        error_log("Email queue processor: Processing batch {$batchNumber}/{$totalBatches} (" . count($batch) . " students)");
        
        foreach ($batch as $studentData) {
            try {
                // Clear previous recipients
                $mail->clearAddresses();
                
                // Add student as recipient
                $studentName = $studentData['name'];
                $mail->addAddress($studentData['email'], $studentName);
                
                // Send email
                if ($mail->send()) {
                    $successCount++;
                } else {
                    $errorCount++;
                    error_log("Email queue processor: Failed to send email to: " . $studentData['email']);
                }
                
                $processedCount++;
                
                // Progress logging every 100 emails
                if ($processedCount % 100 === 0) {
                    error_log("Email queue processor: Progress: {$processedCount}/{$totalStudents} emails processed");
                }
                
                // Small delay to avoid overwhelming the SMTP server
                usleep(100000); // 0.1 second delay
                
            } catch (Exception $e) {
                $errorCount++;
                error_log("Email queue processor: Error for " . $studentData['email'] . ": " . $e->getMessage());
            }
        }
        
        // Delay between batches
        if ($i + $batchSize < $totalStudents) {
            sleep(2); // 2 second delay between batches
        }
    }
    
    // Log final results
    error_log("Email queue processor: Completed! {$successCount} successful, {$errorCount} failed out of {$totalStudents} total");
    
    // Clean up queue file
    unlink($emailQueueFile);
    error_log("Email queue processor: Queue file cleaned up: " . $emailQueueFile);
    
} catch (Exception $e) {
    error_log("Email queue processor: Fatal error: " . $e->getMessage());
    exit(1);
}

function createEmailBody($eventName, $description, $sanction, $programs, $years): string
{
    $programsList = [];
    foreach ($programs as $i => $program) {
        $year = $years[$i] ?? '';
        if ($program === 'AllStudents') {
            $programsList[] = 'All Students';
        } else {
            $yearDisplay = !empty($year) ? " ({$year})" : '';
            $programsList[] = $program . $yearDisplay;
        }
    }
    
    $programsText = implode(', ', $programsList);
    
    return "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #a31d1d; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
            .content { background-color: #f9f9f9; padding: 20px; border-radius: 0 0 8px 8px; }
            .event-name { font-size: 24px; font-weight: bold; color: #a31d1d; margin-bottom: 15px; }
            .info-item { margin-bottom: 15px; }
            .label { font-weight: bold; color: #555; }
            .description { background-color: white; padding: 15px; border-radius: 5px; border-left: 4px solid #a31d1d; }
            .footer { margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>📅 New Attendance Event</h1>
                <p>USeP QR Attendance System</p>
            </div>
            <div class='content'>
                <div class='event-name'>" . htmlspecialchars($eventName) . "</div>
                
                <div class='info-item'>
                    <span class='label'>📚 Programs:</span> " . htmlspecialchars($programsText) . "
                </div>
                
                <div class='info-item'>
                    <span class='label'>⏰ Sanction:</span> " . htmlspecialchars($sanction) . " hours
                </div>
                
                <div class='info-item'>
                    <span class='label'>📝 Description:</span>
                    <div class='description'>" . $description . "</div>
                </div>
                
                <div class='info-item'>
                    <span class='label'>⚠️ Important:</span>
                    <ul>
                        <li>Please attend this event on time</li>
                        <li>Use the QR code scanner to mark your attendance</li>
                        <li>Late attendance may result in sanctions</li>
                    </ul>
                </div>
                
                <div class='footer'>
                    <p>This is an automated notification from the USeP QR Attendance System.</p>
                    <p>If you have any questions, please contact your administrator.</p>
                </div>
            </div>
        </div>
    </body>
    </html>";
}

function createPlainTextBody($eventName, $description, $sanction, $programs, $years): string
{
    $programsList = [];
    foreach ($programs as $i => $program) {
        $year = $years[$i] ?? '';
        if ($program === 'AllStudents') {
            $programsList[] = 'All Students';
        } else {
            $yearDisplay = !empty($year) ? " ({$year})" : '';
            $programsList[] = $program . $yearDisplay;
        }
    }
    
    $programsText = implode(', ', $programsList);
    
    // Strip HTML tags from description for plain text
    $plainDescription = strip_tags($description);
    
    return "
NEW ATTENDANCE EVENT

Event Name: " . $eventName . "
Programs: " . $programsText . "
Sanction: " . $sanction . " hours

Description:
" . $plainDescription . "

Important:
- Please attend this event on time
- Use the QR code scanner to mark your attendance
- Late attendance may result in sanctions

This is an automated notification from the USeP QR Attendance System.
If you have any questions, please contact your administrator.";
} 