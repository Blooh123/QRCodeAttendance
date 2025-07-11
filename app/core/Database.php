<?php

// Get the project root directory
$projectRoot = dirname(dirname(__DIR__));
require_once $projectRoot . '/app/core/config.php';

Trait Database
{
    public function connect(): PDO
    {
        $string = "mysql:host=".DBHOST.";dbname=". DBNAME;
        $con = new PDO($string, DBUSER, DBPASS);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
        return $con;
    }

    public function query($query, $params = [])
    {
        $con = $this->connect();
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
            echo "Query Error: " . $e->getMessage();
            return false;
        }

        // Default return for other types of queries
        return false;
    }

    public function query2($query, $params = [])
    {
        $con = $this->connect();
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
            echo "Query Error: " . $e->getMessage();
            return false;
        }

        return false;
    }
}

// Usage Example:
// $database = new class {
//     use Database;
// };
//
// $result = $database->query("CALL ValidateUser(:username, :password)", [
//     'username' => 'test_user',
//     'password' => 'test_password'
// ]);
//
// echo "<pre>";
// print_r($result);
// echo "</pre>";
