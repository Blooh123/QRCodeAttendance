<?php

namespace Model;
require_once '../app/core/Database.php';

use Database;
use DateMalformedStringException;
use DateTime;
use DateTimeZone;
use PDO;
class ActivityLog
{
    use Database;

    /**
     * @throws DateMalformedStringException
     */
    public function createActivityLog($userID, $role, $activityLog, $event): false|string
    {
        $date = new DateTime("now", new DateTimeZone('Asia/Manila'));
        $formattedTime = $date->format('Y-m-d H:i:s'); // FULL Date and Time
        $query = 'CALL sp_create_activity_log(:id, :activity, :role, :event, :time)';
        $params = [
            'id' => $userID,
            'role' => $role,
            'activity' => $activityLog,
            'time' => $formattedTime,
            'event' => $event
        ];
        return $this->query2($query, $params);
    }

    public function getActivityLogForFaci($userID, $evnt): array
    {
        $query = 'CALL sp_get_user_activity_log(:userID, :evnt)';
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":userID",$userID);
        $stmt->bindParam(":evnt",$evnt);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActivityLogForUser($evnt): array
    {
        $query = 'CALL sp_get_activity_log_on_atten(:evnt)';
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":evnt",$evnt);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all activity logs with specific event types and calculate time ago
     */
    public function getAllActivityLogs(): array
    {
        $query = "SELECT al.*, u.username, u.roles
                  FROM activity_log al 
                  LEFT JOIN users u ON al.user_id = u.id 
                  WHERE al.evnt LIKE '%add%' 
                     OR al.evnt LIKE '%delete%' 
                     OR al.evnt LIKE '%update%' 
                     OR al.evnt LIKE '%login%'
                     OR al.evnt LIKE '%logout%'
                     OR al.evnt LIKE '%backup%'
                  ORDER BY al.time_created DESC";
        
        $stmt = $this->connect()->prepare($query);
        $stmt->execute();
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculate time ago for each log
        foreach ($logs as &$log) {
            $log['time_ago'] = $this->calculateTimeAgo($log['time_created']);
        }
        
        return $logs;
    }

    /**
     * Calculate how many hours ago an activity was performed
     */
    private function calculateTimeAgo($timeCreated): string
    {
        if (!$timeCreated) {
            return 'Unknown time';
        }

        try {
            $createdTime = new DateTime($timeCreated, new DateTimeZone('Asia/Manila'));
            $currentTime = new DateTime("now", new DateTimeZone('Asia/Manila'));
            $interval = $currentTime->diff($createdTime);
            
            if ($interval->y > 0) {
                return $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago';
            } elseif ($interval->m > 0) {
                return $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago';
            } elseif ($interval->d > 0) {
                return $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
            } elseif ($interval->h > 0) {
                return $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
            } elseif ($interval->i > 0) {
                return $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
            } else {
                return 'Just now';
            }
        } catch (Exception $e) {
            return 'Time calculation error';
        }
    }
}