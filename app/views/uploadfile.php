<?php
require '../app/core/config.php';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Upload File</title>
    <link rel="icon" type="image/x-icon" href="<?php echo ROOT?>assets/images/LOGO_QRCODE_v2.png">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        form {
            max-width: 500px;
            width: 100%;
            margin: 20px;
            padding: 30px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        form:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
            font-size: 1rem;
            color: #444;
        }
        .file-info {
            margin-top: 12px;
            font-size: 0.9rem;
            color: #4CAF50;
            font-weight: 500;
            transition: opacity 0.3s ease;
        }
        .file-info.hidden {
            opacity: 0;
        }
        .warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
            padding: 10px;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-bottom: 15px;
            display: none;
        }
        input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            color: #333;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease, background-color 0.3s ease;
        }
        input[type="file"]:hover, input[type="file"]:focus {
            border-color: #4CAF50;
            background-color: #fff;
        }
        button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 12px 24px;
            cursor: pointer;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        button:hover {
            background-color: #388e3c;
            transform: translateY(-2px);
        }
        button:active {
            transform: translateY(0);
        }
        button svg {
            width: 20px;
            height: 20px;
        }
        #loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.85);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            font-size: 1.5rem;
            color: #840303;
            font-weight: bold;
            flex-direction: column;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #8b0000;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin-bottom: 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

    </style>
</head>
<body>

<form id="uploadForm" method="POST" class="p-4 md:p-5" action="<?php echo ROOT ?>add_student" enctype="multipart/form-data">


    <!-- Warning Message -->
    <div id="warning-message" class="warning">
        ⚠ <strong>Important:</strong> The Excel file must contain the following headers:
        <br><strong>student id, name, program, year, email</strong>
    </div>

    <label for="excelFile">Import Excel File</label>
    <input type="file" name="excelFile" id="excelFile" accept=".xlsx, .xls">

    <!-- File Info Section -->
    <div id="file-info" class="file-info hidden"></div>

    <button type="submit">
        <svg class="me-2 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M3 16a1 1 0 011-1h12a1 1 0 011 1v1a1 1 0 11-2 0v-1H5v1a1 1 0 11-2 0v-1zM10 3a1 1 0 011 1v8.586l1.707-1.707a1 1 0 011.414 1.414l-3.5 3.5a1 1 0 01-1.414 0l-3.5-3.5a1 1 0 111.414-1.414L9 12.586V4a1 1 0 011-1z"></path>
        </svg>
        Import Students
    </button>
</form>

<script>
    const excelFileInput = document.getElementById('excelFile');
    const fileInfoDisplay = document.getElementById('file-info');
    const warningMessage = document.getElementById('warning-message');

    // Show warning message when the input is focused
    excelFileInput.addEventListener('focus', () => {
        warningMessage.style.display = 'block';
    });

    // Display file name when a file is selected
    excelFileInput.addEventListener('change', () => {
        const file = excelFileInput.files[0];
        if (file) {
            fileInfoDisplay.textContent = `Selected File: ${file.name}`;
            fileInfoDisplay.classList.remove('hidden');
        } else {
            fileInfoDisplay.textContent = '';
            fileInfoDisplay.classList.add('hidden');
        }
    });
</script>


<!-- Loading Overlay -->
<div id="loading-overlay">
    <div class="spinner"></div>
    Uploading...
</div>

<script>
    const uploadForm = document.getElementById('uploadForm');
    const loadingOverlay = document.getElementById('loading-overlay');

    // Show warning message when the input is focused
    excelFileInput.addEventListener('focus', () => {
        warningMessage.style.display = 'block';
    });

    // Display file name when a file is selected
    excelFileInput.addEventListener('change', () => {
        const file = excelFileInput.files[0];
        if (file) {
            fileInfoDisplay.textContent = `Selected File: ${file.name}`;
            fileInfoDisplay.classList.remove('hidden');
        } else {
            fileInfoDisplay.textContent = '';
            fileInfoDisplay.classList.add('hidden');
        }
    });

    // Show loading overlay on form submit
    uploadForm.addEventListener('submit', () => {
        loadingOverlay.style.display = 'flex';
    });
</script>


</body>



</html>
