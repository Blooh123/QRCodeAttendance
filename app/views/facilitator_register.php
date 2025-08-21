<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facilitator Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0,123,255,0.3);
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .error {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
            padding: 10px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            display: none;
        }
        .success {
            color: #28a745;
            font-size: 14px;
            margin-top: 5px;
            padding: 10px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Facilitator Registration</h1>
        
        <div id="validationStep">
            <h2>Step 1: Validate Your Account</h2>
            <p>Please enter your existing username and password to proceed with facial registration.</p>
            
            <?php if(isset($_GET['error'])): ?>
                <div class="error" style="display: block;">
                    <?php 
                        switch($_GET['error']) {
                            case 'missing_data':
                                echo 'Username and password are required.';
                                break;
                            case 'user_not_found':
                                echo 'Username not found.';
                                break;
                            case 'invalid_role':
                                echo 'This account is not a facilitator account.';
                                break;
                            case 'invalid_credentials':
                                echo 'Invalid username or password.';
                                break;
                            case 'already_registered':
                                echo 'Facial registration already completed for this account.';
                                break;
                            case 'database_error':
                                echo 'Database error occurred. Please try again.';
                                break;
                            default:
                                echo 'An error occurred. Please try again.';
                        }
                    ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="<?php echo ROOT ?>registration17236463">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                
                <button type="submit" class="btn">Validate Account</button>
            </form>
        </div>
    </div>
</body>
</html>