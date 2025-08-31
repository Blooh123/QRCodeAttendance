-- Add permissions column to users table
ALTER TABLE `users` ADD COLUMN `permissions` TEXT NULL AFTER `state`;

-- Add comment to explain the column
ALTER TABLE `users` MODIFY COLUMN `permissions` TEXT NULL COMMENT 'JSON array of user permissions';
