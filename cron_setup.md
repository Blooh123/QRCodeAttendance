# Email Notification Cron Job Setup

## Overview
This system uses a cron job to automatically send email notifications to students when new attendance events are created. The system works with a `notified` column in the Students table (0 = not notified, 1 = notified).

## Database Trigger Setup
When a new attendance is added, a trigger should update all students' `notified` column to 0:

```sql
DELIMITER //
CREATE TRIGGER reset_student_notifications
AFTER INSERT ON attendance
FOR EACH ROW
BEGIN
    UPDATE students SET notified = 0;
END//
DELIMITER ;
```

## Cron Job Setup

### 1. For Linux/Unix Servers:
Add this line to your crontab (`crontab -e`):

```bash
# Run every 5 minutes
*/5 * * * * /usr/bin/php /path/to/your/project/app/Controller/SendEmails.php >> /var/log/email_cron.log 2>&1

# Or run every 10 minutes
*/10 * * * * /usr/bin/php /path/to/your/project/app/Controller/SendEmails.php >> /var/log/email_cron.log 2>&1
```

### 2. For Windows Servers:
Use Windows Task Scheduler or create a batch file:

```batch
@echo off
cd /d "C:\path\to\your\project"
php app\Controller\SendEmails.php >> email_cron.log 2>&1
```

### 3. For Shared Hosting:
Most shared hosting providers allow cron jobs through cPanel:
- Go to cPanel > Cron Jobs
- Add command: `/usr/bin/php /home/username/public_html/app/Controller/SendEmails.php`
- Set frequency (every 5-10 minutes recommended)

## How It Works

1. **New Attendance Created**: Trigger sets all students' `notified` = 0
2. **Cron Job Runs**: Every 5-10 minutes, checks for students with `notified` = 0
3. **Email Sent**: Sends emails to eligible students based on required attendees
4. **Status Updated**: Sets `notified` = 1 for successfully sent emails
5. **Logging**: All results are logged for monitoring

## Monitoring

### Check Cron Job Status:
```bash
# View cron logs
tail -f /var/log/email_cron.log

# Check if cron is running
ps aux | grep cron
```

### Test the Script Manually:
```bash
php app/Controller/SendEmails.php
```

### Expected Output:
```json
{
    "success": true,
    "message": "Email processing completed. Total sent: 150, Total failed: 2",
    "total_sent": 150,
    "total_failed": 2,
    "details": [
        {
            "atten_id": "123",
            "event_name": "Class Meeting",
            "sent": 75,
            "failed": 1,
            "message": "Sent 75 emails, 1 failed for event: Class Meeting"
        }
    ]
}
```

## Troubleshooting

### Common Issues:

1. **Permission Denied**: Make sure the script is executable
   ```bash
   chmod +x app/Controller/SendEmails.php
   ```

2. **Path Issues**: Use absolute paths in cron jobs
   ```bash
   /usr/bin/php /full/path/to/SendEmails.php
   ```

3. **SMTP Issues**: Check email credentials and server settings

4. **Database Connection**: Verify database credentials in the script

### Debug Mode:
To enable debug logging, modify the script to include:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## Performance Considerations

- **Batch Size**: Script processes emails in small batches to avoid overwhelming SMTP
- **Delays**: 0.1 second delay between emails to respect SMTP limits
- **Memory**: Efficient queries to avoid memory issues with large student lists
- **Logging**: Minimal logging to avoid disk space issues

## Security Notes

- Keep database credentials secure
- Use HTTPS for email transmission
- Regularly rotate email passwords
- Monitor logs for suspicious activity 