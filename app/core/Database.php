<?php

// Get the project root directory
$projectRoot = dirname(dirname(__DIR__));
require_once $projectRoot . '/app/core/config.php';

Trait Database
{
    /**
     * Static connection pool to reuse PDO connections
     * CRITICAL FIX for max_connections_per_hour exceeded error
     */
    private static ?PDO $connection = null;

    /**
     * Get or create a pooled database connection
     * Instead of creating new connections each time, reuse the same connection
     * 
     * @return PDO
     */
    public function connect(): PDO
    {
        // If connection already exists and is valid, reuse it
        if (self::$connection !== null) {
            return self::$connection;
        }

        // Create new connection only if needed
        $string = "mysql:host=".DBHOST.";port=".DBPORT.";dbname=".DBNAME;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5,                    // 5 second timeout
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
            PDO::ATTR_PERSISTENT => false              // Don't use persistent connections on shared hosting
        ];
        
        try {
            self::$connection = new PDO($string, DBUSER, DBPASS, $options);
        } catch (PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            throw $e;
        }

        return self::$connection;
    }

    /**
     * Close the connection pool (optional, called on script termination)
     */
    public function closeConnection(): void
    {
        self::$connection = null;
    }

    public function query($query, $params = [])
    {
        $con = $this->connect();  // Reuses pooled connection
        $stmt = $con->prepare($query);

        try {
            // Execute the query
            $check = $stmt->execute($params);

            // Check if the query was successful
            if (!$check) {
                return false;
            }

            // Determine the type of query
            $queryType = strtoupper(explode(' ', trim($query))[0]);

            if ($queryType === 'SELECT') {
                // Fetch and return results for SELECT queries
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } elseif ($queryType === 'CALL') {
                // Fetch and return results for CALL (stored procedures)
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                // Clear any remaining result sets for stored procedures
                while ($stmt->nextRowset()) { }
                return $result;
            } elseif (in_array($queryType, ['INSERT', 'UPDATE', 'DELETE'])) {
                // Return true for INSERT, UPDATE, DELETE queries
                return true;
            }
        } catch (PDOException $e) {
            // Handle query exceptions
            error_log("Query Error: " . $e->getMessage() . " Query: " . $query);
            return false;
        }

        // Default return for other types of queries
        return false;
    }

    public function query2($query, $params = [])
    {
        $con = $this->connect();  // Reuses pooled connection
        $stmt = $con->prepare($query);

        try {
            $check = $stmt->execute($params);

            if (!$check) {
                return false;
            }

            // Handle INSERT queries and return last inserted ID
            if (stripos($query, 'INSERT') === 0) {
                return $con->lastInsertId();
            }
        } catch (PDOException $e) {
            // Handle query exceptions
            error_log("Query2 Error: " . $e->getMessage() . " Query: " . $query);
            return false;
        }

        return false;
    }
}
