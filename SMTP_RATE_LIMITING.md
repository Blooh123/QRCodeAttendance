# SMTP Rate Limiting Configuration

The `SendEmails.php` script now includes comprehensive rate limiting to prevent overwhelming SMTP servers and ensure reliable email delivery.

## Configuration Constants

The following constants in `SendEmails.php` control the rate limiting behavior:

```php
private const BATCH_SIZE = 10; // Number of emails to send per batch
private const DELAY_BETWEEN_BATCHES = 5; // Seconds to wait between batches
private const DELAY_BETWEEN_EMAILS = 0.5; // Seconds to wait between individual emails
private const SMTP_TIMEOUT = 30; // SMTP connection timeout in seconds
private const MAX_RETRIES = 3; // Maximum number of retries for failed emails
```

## How It Works

1. **Batch Processing**: Students are processed in batches of 10 (configurable)
2. **Individual Email Delays**: 0.5 seconds between each email
3. **Batch Delays**: 5 seconds between batches
4. **Connection Management**: SMTP connections are kept alive within batches and closed between batches
5. **Error Handling**: Failed emails are logged but don't stop the process

## Recommended Settings by SMTP Provider

### Gmail SMTP
- **Batch Size**: 10-20 emails
- **Delay Between Emails**: 0.5-1 second
- **Delay Between Batches**: 5-10 seconds
- **Daily Limit**: 500 emails per day

### Outlook/Hotmail SMTP
- **Batch Size**: 5-10 emails
- **Delay Between Emails**: 1-2 seconds
- **Delay Between Batches**: 10-15 seconds
- **Daily Limit**: 300 emails per day

### Custom SMTP Server
- **Batch Size**: 20-50 emails
- **Delay Between Emails**: 0.1-0.5 seconds
- **Delay Between Batches**: 2-5 seconds
- **Daily Limit**: Varies by provider

## Adjusting for Your SMTP Provider

To adjust the rate limiting for your specific SMTP provider:

1. Edit the constants in `SendEmails.php`
2. Test with a small number of emails first
3. Monitor the logs for any SMTP errors
4. Gradually increase batch sizes if no errors occur

## Example: More Aggressive Settings (for high-volume SMTP)

```php
private const BATCH_SIZE = 25; // Larger batches
private const DELAY_BETWEEN_BATCHES = 3; // Shorter delays
private const DELAY_BETWEEN_EMAILS = 0.2; // Faster sending
private const SMTP_TIMEOUT = 60; // Longer timeout
```

## Example: Conservative Settings (for limited SMTP)

```php
private const BATCH_SIZE = 5; // Smaller batches
private const DELAY_BETWEEN_BATCHES = 10; // Longer delays
private const DELAY_BETWEEN_EMAILS = 1.0; // Slower sending
private const SMTP_TIMEOUT = 15; // Shorter timeout
```

## Monitoring and Logging

The script provides detailed logging:
- Number of students being processed
- Batch progress
- Individual email success/failure
- SMTP connection status
- Total results summary

Check your server's error log to monitor the email sending process.

## Troubleshooting

### Common Issues:
1. **SMTP Timeout**: Increase `SMTP_TIMEOUT` constant
2. **Too Many Failed Emails**: Decrease `BATCH_SIZE` and increase delays
3. **SMTP Server Rejection**: Check your SMTP provider's rate limits
4. **Connection Issues**: Verify SMTP credentials and network connectivity

### Testing:
Run the script manually first to test your configuration:
```bash
php app/Controller/SendEmails.php
``` 