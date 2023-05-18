<?php
$csvData = $_POST['csv_data'] ?? '';
var_dump($_POST);
if (!empty($csvData)) {
    $tableData = json_decode($csvData, true);

    if ($tableData !== null && is_array($tableData) && count($tableData) > 0) {
        $csvContent = "";
        $headers = array_keys($tableData[0]);
        $csvContent .= implode(",", $headers) . "\n";
        foreach ($tableData as $row) {
            $csvContent .= implode(",", $row) . "\n";
        }

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="students.csv"');

        echo $csvContent;
        exit;
        
    } else {
        echo "Invalid table data";
    }
} else {
    echo "No CSV data found";
}
?>
