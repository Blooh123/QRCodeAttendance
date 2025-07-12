-- Add banner column to attendance table for raw image data storage
ALTER TABLE `attendance` ADD COLUMN `banner` LONGBLOB NULL AFTER `sanction`;
 
-- Note: This approach stores raw image data (same as studentProfile column)
-- The banner will be updated separately after attendance insertion 