<?php

require_once "../app/core/imageConfig.php";
$multiYearData = $data['multiYearData'] ?? [];
$printProgram = $data['printProgram'] ?? '';
$EventName = $data['EventName'] ?? '';
$EventID = $data['EventID'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Multi-Year Attendance Report</title>
    <style>
        @page {
            size: landscape;
            margin: 10mm;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
        }
        .page-break {
            page-break-after: always;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 5px 0;
            font-size: 24px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 18px;
        }
        .header p {
            margin: 3px 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #333;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #a31d1d;
            color: white;
            font-weight: bold;
        }
        .year-section {
            margin-top: 30px;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
        }
    </style>
</head>
<body>

<?php foreach ($multiYearData as $year => $records): ?>
    <div class="year-section <?php echo $year !== array_key_last($multiYearData) ? 'page-break' : ''; ?>">
        <div class="header">
            <div style="text-align:center; margin-bottom:12px;">
                <img src="<?= $imageSource8 ?>" alt="USEP Logo" style="height:80px; max-width:100%; object-fit:contain; display:inline-block;" />
            </div>
            <h1>University of Southeastern Philippines</h1>
            <h2>Attendance Sheet - <?= htmlspecialchars($EventName) ?></h2>
            <p><strong>Program:</strong> <?= htmlspecialchars($printProgram) ?></p>
            <p><strong>Academic Year:</strong> <?= htmlspecialchars($year) ?></p>
            <p>Date Generated: <?= date('F d, Y') ?></p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                    <tr>
                        <td><?= htmlspecialchars($record['student_id']) ?></td>
                        <td><?= htmlspecialchars($record['name']) ?></td>
                        <td><?= htmlspecialchars($record['email']) ?></td>
                        <td><?= htmlspecialchars($record['time_in'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($record['time_out'] ?? 'N/A') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p style="font-size:9px; text-align:right;"><strong>Total:</strong> <?= count($records) ?> students</p>
    </div>
<?php endforeach; ?>

<?php if (empty($multiYearData)): ?>
    <div class="no-data">
        <p>No attendance records found for the selected program and event.</p>
    </div>
<?php endif; ?>

<script>
    window.print();
</script>

</body>
</html>