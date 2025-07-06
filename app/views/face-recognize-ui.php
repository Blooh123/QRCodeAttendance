<?php
  require_once '../app/core/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Face detection on the browser using javascript !</title>
  <!-- <script defer src="face-api.min.js"></script> -->
  <script defer src="<?= ROOT ?>assets/js/face-api.min.js"></script>
  <script defer src="<?= ROOT ?>assets/js/script.js"></script>
  <link rel="stylesheet" href="<?= ROOT ?>assets/css/style.css">
</head>
<body>
  <video id="video" width="600" height="450" autoplay></video>
  <a href="face-register">register</a>
</body>

</html>