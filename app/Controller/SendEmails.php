<?php

namespace Controller;

// Get the project root directory
$projectRoot = dirname(dirname(__DIR__));

require_once $projectRoot . '/vendor/autoload.php';
require_once $projectRoot . '/app/Model/Student.php';
require_once $projectRoot . '/app/Model/Attendances.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Model\Student;
use Model\Attendances;
use PDO;

class SendEmails
{
    // SMTP Rate Limiting Configuration
    private const BATCH_SIZE = 10; // Number of emails to send per batch
    private const DELAY_BETWEEN_BATCHES = 5; // Seconds to wait between batches
    private const DELAY_BETWEEN_EMAILS = 0.5; // Seconds to wait between individual emails
    private const SMTP_TIMEOUT = 30; // SMTP connection timeout in seconds
    private const MAX_RETRIES = 3; // Maximum number of retries for failed emails

    public function connect(): PDO
    {
        //u753706103_qr_attendance
        //u753706103_christian
        //mZ2~G76JP1s5=B=Cy1L*
        $string = "mysql:host="."localhost".";dbname="."u753706103_qr_attendance";
        $con = new PDO($string, "u753706103_christian", "mZ2~G76JP1s5=B=Cy1L*");
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $con;
    }

    public function sendPendingEmails(): array
    {
        try {
            // Get all active attendance events that haven't been processed for email notifications
            $activeAttendances = $this->getActiveAttendances();
            
            if (empty($activeAttendances)) {
                return [
                    'success' => true,
                    'message' => 'No pending email notifications found.',
                    'total_sent' => 0,
                    'total_failed' => 0
                ];
            }

            $totalSent = 0;
            $totalFailed = 0;
            $results = [];

            // Use configuration constants for SMTP rate limiting
            foreach ($activeAttendances as $attendance) {
                $result = $this->sendEmailsForAttendanceWithRateLimit(
                    $attendance, 
                    self::BATCH_SIZE, 
                    self::DELAY_BETWEEN_BATCHES, 
                    self::DELAY_BETWEEN_EMAILS
                );
                $totalSent += $result['sent'];
                $totalFailed += $result['failed'];
                $results[] = $result;
            }

            return [
                'success' => true,
                'message' => "Email processing completed. Total sent: {$totalSent}, Total failed: {$totalFailed}",
                'total_sent' => $totalSent,
                'total_failed' => $totalFailed,
                'details' => $results
            ];

        } catch (Exception $e) {
            error_log("SendEmails error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to send emails: ' . $e->getMessage(),
                'total_sent' => 0,
                'total_failed' => 0
            ];
        }
    }

    private function getActiveAttendances(): array
    {
        // Get attendance events that are active and have students who haven't been notified
        $query = "
            SELECT DISTINCT a.atten_id, a.event_name, a.sanction, a.description, a.required_attenRecord, a.date_created
            FROM attendance a
            INNER JOIN required_attendees ra ON a.atten_id = ra.atten_id
            INNER JOIN students s ON (
                (ra.program = s.program AND ra.acad_year = s.acad_year) OR
                (ra.program = 'AllStudents')
            )
            WHERE a.atten_status IN ('not started', 'ongoing')
            AND s.notified = 0
            ORDER BY a.date_created DESC
        ";

        $stmt = $this->connect()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function sendEmailsForAttendance($attendance): array
    {
        try {
            // Get students who need to be notified for this attendance
            $students = $this->getStudentsToNotify($attendance['atten_id']);
            
            if (empty($students)) {
                return [
                    'atten_id' => $attendance['atten_id'],
                    'event_name' => $attendance['event_name'],
                    'sent' => 0,
                    'failed' => 0,
                    'message' => 'No students to notify for this event'
                ];
            }

            // Initialize PHPMailer
            $mail = $this->initializePHPMailer($attendance);
            
            $sentCount = 0;
            $failedCount = 0;
            $successfulEmails = [];

            foreach ($students as $student) {
                try {
                    $mail->clearAddresses();
                    $mail->addAddress($student['email'], $student['name']);
                    
                    if ($mail->send()) {
                        $sentCount++;
                        $successfulEmails[] = $student['email'];
                    } else {
                        $failedCount++;
                        error_log("Failed to send email to: " . $student['email']);
                    }
                    
                    // Small delay to avoid overwhelming SMTP server
                    usleep(100000); // 0.1 second delay
                    
                } catch (Exception $e) {
                    $failedCount++;
                    error_log("Email error for " . $student['email'] . ": " . $e->getMessage());
                }
            }

            // Update notified status for successfully sent emails
            if (!empty($successfulEmails)) {
                $this->updateNotifiedStatus($successfulEmails);
            }

            return [
                'atten_id' => $attendance['atten_id'],
                'event_name' => $attendance['event_name'],
                'sent' => $sentCount,
                'failed' => $failedCount,
                'message' => "Sent {$sentCount} emails, {$failedCount} failed for event: {$attendance['event_name']}"
            ];

        } catch (Exception $e) {
            error_log("Error sending emails for attendance {$attendance['atten_id']}: " . $e->getMessage());
            return [
                'atten_id' => $attendance['atten_id'],
                'event_name' => $attendance['event_name'],
                'sent' => 0,
                'failed' => 0,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    private function sendEmailsForAttendanceWithRateLimit($attendance, $batchSize, $delayBetweenBatches, $delayBetweenEmails): array
    {
        try {
            // Get students who need to be notified for this attendance
            $students = $this->getStudentsToNotify($attendance['atten_id']);
            
            if (empty($students)) {
                return [
                    'atten_id' => $attendance['atten_id'],
                    'event_name' => $attendance['event_name'],
                    'sent' => 0,
                    'failed' => 0,
                    'message' => 'No students to notify for this event'
                ];
            }

            $sentCount = 0;
            $failedCount = 0;
            $successfulEmails = [];
            $totalStudents = count($students);
            $batches = array_chunk($students, $batchSize);

            error_log("Processing {$totalStudents} students in " . count($batches) . " batches for event: {$attendance['event_name']}");

            foreach ($batches as $batchIndex => $batch) {
                error_log("Processing batch " . ($batchIndex + 1) . " of " . count($batches) . " (" . count($batch) . " students)");
                
                // Initialize PHPMailer for this batch
                $mail = $this->initializePHPMailer($attendance);
                
                foreach ($batch as $student) {
                    try {
                        $mail->clearAddresses();
                        $mail->addAddress($student['email'], $student['name']);
                        
                        if ($mail->send()) {
                            $sentCount++;
                            $successfulEmails[] = $student['email'];
                            error_log("✓ Email sent successfully to: " . $student['email']);
                        } else {
                            $failedCount++;
                            error_log("✗ Failed to send email to: " . $student['email']);
                        }
                        
                        // Delay between individual emails
                        if ($delayBetweenEmails > 0) {
                            usleep($delayBetweenEmails * 1000000); // Convert seconds to microseconds
                        }
                        
                    } catch (Exception $e) {
                        $failedCount++;
                        error_log("✗ Email error for " . $student['email'] . ": " . $e->getMessage());
                    }
                }

                // Close SMTP connection after each batch
                $this->closeSMTPConnection($mail);

                // Update notified status for successfully sent emails in this batch
                if (!empty($successfulEmails)) {
                    $this->updateNotifiedStatus($successfulEmails);
                    $successfulEmails = []; // Reset for next batch
                }

                // Delay between batches (except for the last batch)
                if ($batchIndex < count($batches) - 1 && $delayBetweenBatches > 0) {
                    error_log("Waiting {$delayBetweenBatches} seconds before next batch...");
                    sleep($delayBetweenBatches);
                }
            }

            return [
                'atten_id' => $attendance['atten_id'],
                'event_name' => $attendance['event_name'],
                'sent' => $sentCount,
                'failed' => $failedCount,
                'message' => "Sent {$sentCount} emails, {$failedCount} failed for event: {$attendance['event_name']} (processed in " . count($batches) . " batches)"
            ];

        } catch (Exception $e) {
            error_log("Error sending emails for attendance {$attendance['atten_id']}: " . $e->getMessage());
            return [
                'atten_id' => $attendance['atten_id'],
                'event_name' => $attendance['event_name'],
                'sent' => 0,
                'failed' => 0,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    private function getStudentsToNotify($attenId): array
    {
        // Get students who haven't been notified and match the required attendees criteria
        $query = "
            SELECT DISTINCT s.email, s.name, s.program, s.acad_year
            FROM students s
            INNER JOIN required_attendees ra ON ra.atten_id = :atten_id
            WHERE s.notified = 0
            AND (
                (ra.program = s.program AND ra.acad_year = s.acad_year) OR
                (ra.program = 'AllStudents')
            )
        ";

        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':atten_id', $attenId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function updateNotifiedStatus($emails): void
    {
        if (empty($emails)) return;

        $placeholders = str_repeat('?,', count($emails) - 1) . '?';
        $query = "UPDATE students SET notified = 1 WHERE email IN ($placeholders)";
        
        $stmt = $this->connect()->prepare($query);
        $stmt->execute($emails);
    }

    private function initializePHPMailer($attendance): PHPMailer
    {
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
        
        // Additional SMTP settings for rate limiting
        $mail->SMTPKeepAlive = true; // Keep connection alive between emails
        $mail->Timeout = self::SMTP_TIMEOUT; // Use configuration constant
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        // Set sender
        $mail->setFrom('usep.qrattendance@gmail.com', 'USeP QR Attendance System');
        $mail->addReplyTo('usep.qrattendance@gmail.com', 'USeP QR Attendance System');
        
        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'New Attendance Event: ' . htmlspecialchars($attendance['event_name']);
        
        // Create email body
        $emailBody = $this->createEmailBody($attendance);
        $mail->Body = $emailBody;
        $mail->AltBody = $this->createPlainTextBody($attendance);
        
        return $mail;
    }

    private function createEmailBody($attendance): string
    {
        $requiredAttendanceRecord = json_decode($attendance['required_attenRecord'], true);
        $requiredText = is_array($requiredAttendanceRecord) ? implode(', ', $requiredAttendanceRecord) : 'Time In';
        
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
                    <div class='event-name'>" . htmlspecialchars($attendance['event_name']) . "</div>
                    
                    <div class='info-item'>
                        <span class='label'>⏰ Sanction:</span> " . htmlspecialchars($attendance['sanction']) . " hours
                    </div>
                    
                    <div class='info-item'>
                        <span class='label'>📝 Required Records:</span> " . htmlspecialchars($requiredText) . "
                    </div>
                    
                    <div class='info-item'>
                        <span class='label'>📝 Description:</span>
                        <div class='description'>" . $attendance['description'] . "</div>
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

    private function createPlainTextBody($attendance): string
    {
        $requiredAttendanceRecord = json_decode($attendance['required_attenRecord'], true);
        $requiredText = is_array($requiredAttendanceRecord) ? implode(', ', $requiredAttendanceRecord) : 'Time In';
        $plainDescription = strip_tags($attendance['description']);
        
        return "
NEW ATTENDANCE EVENT

Event Name: " . $attendance['event_name'] . "
Sanction: " . $attendance['sanction'] . " hours
Required Records: " . $requiredText . "

Description:
" . $plainDescription . "

Important:
- Please attend this event on time
- Use the QR code scanner to mark your attendance
- Late attendance may result in sanctions

This is an automated notification from the USeP QR Attendance System.
If you have any questions, please contact your administrator.";
    }

    private function closeSMTPConnection(PHPMailer $mail): void
    {
        try {
            if ($mail->SMTPKeepAlive) {
                $mail->smtpClose();
                error_log("SMTP connection closed successfully");
            }
        } catch (Exception $e) {
            error_log("Error closing SMTP connection: " . $e->getMessage());
        }
    }
}

// Execute the email sending process
$sendEmails = new SendEmails();
$result = $sendEmails->sendPendingEmails();

// Log the results
error_log("SendEmails cron job result: " . json_encode($result));

// Output for cron job monitoring
echo json_encode($result, JSON_PRETTY_PRINT);

