<?php
$labelsDir = __DIR__ . '/assets/js/labels';
$labels = [];
if (is_dir($labelsDir)) {
    foreach (scandir($labelsDir) as $folder) {
        if ($folder === '.' || $folder === '..') continue;
        if (is_dir("$labelsDir/$folder")) $labels[] = $folder;
    }
}
header('Content-Type: application/json');
echo json_encode($labels);