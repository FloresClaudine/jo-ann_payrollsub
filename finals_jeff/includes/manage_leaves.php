<?php
// ============================================================
// DATABASE & SESSION
// ============================================================
require_once('../config/database.php');
require_once('../config/session.php');

requireHR(); // Only HR/Admin

$user = getCurrentUser();

$message = '';
$error = '';

// ============================================================
// FILTER MONTH
// ============================================================
$selected_month = isset($_GET['month']) ? $_GET['month'] : 'all';

// ============================================================
// FETCH LEAVE REQUESTS
// ============================================================
if ($selected_month == 'all') {

    $stmt = $pdo->query("
        SELECT 
            lr.*,
            e.firstname,
            e.lastname,
            lt.leave_name,
            lt.is_paid
        FROM leave_requests lr
        JOIN employees e ON lr.employee_id = e.id
        JOIN leave_types lt ON lr.leave_type_id = lt.id
        ORDER BY lr.start_date DESC
    ");

} else {

    $stmt = $pdo->prepare("
        SELECT 
            lr.*,
            e.firstname,
            e.lastname,
            lt.leave_name,
            lt.is_paid
        FROM leave_requests lr
        JOIN employees e ON lr.employee_id = e.id
        JOIN leave_types lt ON lr.leave_type_id = lt.id
        WHERE MONTH(lr.start_date) = ?
        ORDER BY lr.start_date DESC
    ");

    $stmt->execute([$selected_month]);
}

$leave_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Leaves</title>
    <link rel="stylesheet" href="../assets/dashboard.css">
</head>

<body class="dashboard">

<!-- SIDEBAR -->
<aside class="sidebar">

    <div class="sidebar-header">
        <h2>💼 Payroll System</h2>
        <p>HR Management</p>
    </div>

    <ul class="nav-menu">

        <li class="nav-item">
            <a href="dashboard.php" class="nav-link">
                <i>🏠</i> Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a href="attendanceEmployee.php" class="nav-link">
                <i>⏰</i> My Attendance
            </a>
        </li>

        <li class="nav-item">
            <a href="leave.php" class="nav-link">
                <i>🏖️</i> My Request Leave
            </a>
        </li>

        <li class="nav-item">
            <a href="payslip.php" class="nav-link">
                <i>💰</i> My Payslip
            </a>
        </li>

        <?php if ($user['is_hr'] || $user['is_admin']): ?>

        <li class="nav-item" style="margin-top:20px; padding:10px 20px; color:rgba(255,255,255,.5); font-size:12px; font-weight:bold;">
            HR MANAGEMENT
        </li>

        <li class="nav-item">
            <a href="employee.php" class="nav-link">
                <i>👥</i> Employees
            </a>
        </li>

        <li class="nav-item">
            <a href="attendance_hr.php" class="nav-link">
                <i>📋</i> Attendance
            </a>
        </li>

        <li class="nav-item">
            <a href="manage_leaves.php" class="nav-link active">
                <i>✅</i> Leave Approval
            </a>
        </li>

        <li class="nav-item">
            <a href="manage_incentives.php" class="nav-link">
                <i>🎁</i> Incentives
            </a>
        </li>

        <li class="nav-item">
            <a href="payroll.php" class="nav-link">
                <i>📊</i> Payroll
            </a>
        </li>

        <?php endif; ?>

        <li class="nav-item" style="margin-top:20px;">
            <a href="logout.php" class="nav-link">
                <i>🚪</i> Logout
            </a>
        </li>

    </ul>

    <div class="user-info">

        <strong>
            <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?>
        </strong>

        <small><?php echo htmlspecialchars($user['email']); ?></small>

        <?php if ($user['is_admin']): ?>
            <span class="badge badge-admin">ADMIN</span>
        <?php elseif ($user['is_hr']): ?>
            <span class="badge badge-hr">HR</span>
        <?php endif; ?>

    </div>

</aside>

<!-- MAIN CONTENT -->
<main class="main-content">

    <div class="topbar">
        <h1>✅ Leave Approval Management</h1>
    </div>

    <div class="card">

        <!-- MONTH FILTER -->
        <form method="GET" style="margin-bottom:20px;">
            <label><strong>Filter By Month:</strong></label>

            <select name="month">
                <option value="all" <?= ($selected_month == 'all') ? 'selected' : ''; ?>>
                    Show All Leaves
                </option>

                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= $i ?>" <?= ($selected_month == $i) ? 'selected' : ''; ?>>
                        <?= date('F', mktime(0,0,0,$i,1)) ?>
                    </option>
                <?php endfor; ?>
            </select>

            <button type="submit" class="btn btn-primary">Filter</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Employee</th>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Total Days</th>
                    <th>Reason</th>
                    <th>Paid / Unpaid</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            <?php foreach($leave_requests as $leave): ?>

                <tr>
                    <td><?= $leave['id']; ?></td>

                    <td>
                        <?= htmlspecialchars($leave['firstname'] . ' ' . $leave['lastname']); ?>
                    </td>

                    <td><?= htmlspecialchars($leave['leave_name']); ?></td>

                    <td><?= $leave['start_date']; ?></td>
                    <td><?= $leave['end_date']; ?></td>
                    <td><?= $leave['total_days']; ?></td>

                    <td><?= htmlspecialchars($leave['reason']); ?></td>

                    <!-- ✅ PAID / UNPAID -->
                    <td>
                        <?php if ($leave['is_paid'] == 1): ?>
                            <span style="color:green; font-weight:bold;">Paid</span>
                        <?php else: ?>
                            <span style="color:red; font-weight:bold;">Unpaid</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <span class="status status-<?= $leave['status']; ?>">
                            <?= ucfirst($leave['status']); ?>
                        </span>
                    </td>

                    <td>
                        <?php if($leave['status'] == 'pending'): ?>
                            <a href="approve_leave.php?id=<?= $leave['id']; ?>" class="btn btn-success btn-sm">Approve</a>
                            <a href="reject_leave.php?id=<?= $leave['id']; ?>" class="btn btn-danger btn-sm">Reject</a>
                        <?php else: ?>
                            Done
                        <?php endif; ?>
                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>
        </table>

    </div>

</main>

</body>
</html>