-- Add geofence columns to attendance table
ALTER TABLE `attendance` 
ADD COLUMN `latitude` DECIMAL(10, 8) NULL AFTER `sanction`,
ADD COLUMN `longitude` DECIMAL(11, 8) NULL AFTER `latitude`,
ADD COLUMN `radius` INT NULL AFTER `longitude`;

-- Update the sp_insert_attendance stored procedure to include geofence parameters
DROP PROCEDURE IF EXISTS `sp_insert_attendance`;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_attendance` (
    IN `eventName` VARCHAR(255), 
    IN `a_status` VARCHAR(255), 
    IN `sanction` VARCHAR(255), 
    IN `requireAttndanceRecord` JSON,
    IN `latitude` DECIMAL(10, 8),
    IN `longitude` DECIMAL(11, 8),
    IN `radius` INT
) 
BEGIN
    INSERT INTO attendance (
        event_name, 
        date_created, 
        atten_status, 
        atten_OnTimeCheck, 
        required_AttenRecord, 
        sanction,
        latitude,
        longitude,
        radius
    ) 
    VALUES (
        eventName, 
        NOW(), 
        a_status, 
        0, 
        requireAttndanceRecord, 
        sanction,
        latitude,
        longitude,
        radius
    );
END$$
DELIMITER ; 