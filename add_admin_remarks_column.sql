-- Add admin_remarks column to excuse_application table
ALTER TABLE excuse_application ADD COLUMN admin_remarks TEXT NULL AFTER application_status; 