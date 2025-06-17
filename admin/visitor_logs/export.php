<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

// Clear any previous output
ob_clean();

// Set headers for Excel download
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment;filename="visitor_logs_' . date('Y-m-d') . '.xls"');
header('Cache-Control: max-age=0');
header('Pragma: public');

// Add BOM for proper Excel encoding
echo "\xEF\xBB\xBF";

// Fetch all visitor logs
$result = $conn->query("SELECT * FROM disclaimer_agreements ORDER BY created_at DESC");
$visitor_logs = $result->fetch_all(MYSQLI_ASSOC);

// Create Excel content with proper formatting
echo '<table border="1">';
echo '<tr style="background-color: #f2f2f2; font-weight: bold;">';
echo '<th>Agreed At</th>';
echo '<th>IP Address</th>';
echo '<th>Session ID</th>';
echo '<th>User Agent</th>';
echo '<th>Location</th>';
echo '</tr>';

foreach ($visitor_logs as $log) {
    echo '<tr>';
    echo '<td>' . date('Y-m-d H:i:s', strtotime($log['created_at'])) . '</td>';
    echo '<td>' . htmlspecialchars($log['ip_address']) . '</td>';
    echo '<td>' . htmlspecialchars($log['session_id']) . '</td>';
    echo '<td>' . htmlspecialchars($log['user_agent'] ?? 'N/A') . '</td>';
    echo '<td>' . htmlspecialchars($log['location'] ?? 'N/A') . '</td>';
    echo '</tr>';
}

echo '</table>';

// Flush the output buffer
ob_end_flush(); 