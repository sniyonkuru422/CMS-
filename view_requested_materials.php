<?php
//session_start();

include("auth.php");
checkLogin();

requireAnyRole(["COMPANY_ADMIN", "PROJECT_MANAGER"]);

include("database/connect.php");
include("dashboard_layout.php");

$company_id = $_SESSION['company_id'];

// Fetch all requested materials for this company
$requests = $conn->query("
SELECT 
    mr.request_id,
    mr.material_name,
    mr.quantity,
    mr.unit,
    mr.reason,
    mr.status,
    mr.created_at,
    p.project_name,
    u.name AS requester
FROM material_requests mr
LEFT JOIN projects p ON mr.project_id = p.project_id
LEFT JOIN users u ON mr.requested_by = u.user_id
WHERE mr.company_id = $company_id
ORDER BY mr.created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Requested Materials</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background: #2c3e50;
            color: white;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .status-pending { color: orange; font-weight: bold; }
        .status-approved { color: green; font-weight: bold; }
        .status-rejected { color: red; font-weight: bold; }
    </style>
</head>
<body>

<h2>Requested Materials</h2>

<?php if ($requests && $requests->num_rows > 0): ?>

<table>
    <tr>
        <th>Request ID</th>
        <th>Project</th>
        <th>Requested By</th>
        <th>Material</th>
        <th>Quantity</th>
        <th>Unit</th>
        <th>Reason</th>
        <th>Status</th>
        <th>Request Date</th>
    </tr>

    <?php while($row = $requests->fetch_assoc()): ?>
    <tr>
        <td><?= $row['request_id'] ?></td>
        <td><?= htmlspecialchars($row['project_name'] ?? 'N/A') ?></td>
        <td><?= htmlspecialchars($row['requester'] ?? 'Unknown') ?></td>
        <td><?= htmlspecialchars($row['material_name']) ?></td>
        <td><?= $row['quantity'] ?></td>
        <td><?= htmlspecialchars($row['unit']) ?></td>
        <td><?= htmlspecialchars($row['reason']) ?></td>
        <td>
            <span class="status-<?= strtolower(str_replace(' ', '-', $row['status'])) ?>">
                <?= htmlspecialchars($row['status']) ?>
            </span>
        </td>
        <td><?= $row['created_at'] ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<?php else: ?>
    <p style="color:red; font-weight:bold;">No material requests found.</p>
<?php endif; ?>

</body>
</html>