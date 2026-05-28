<?php
require_once('../config/database.php');
require_once('../config/session.php');

requireHR(); // Only HR/Admin

// CURRENT USER
$user = getCurrentUser();

$message = '';
$error = '';

if (isset($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM employees WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $message = "Employee deleted successfully!";
    } catch(PDOException $e) {
        $error = "Cannot delete employee: " . $e->getMessage();
    }
}

// ============================================================
// ADD EMPLOYEE
// ============================================================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ADD
    if (isset($_POST['action']) && $_POST['action'] == 'add') {

        $employee_id = 'EMP' . str_pad(rand(100,999), 3, '0', STR_PAD_LEFT);

        $firstname = trim($_POST['firstname']);
        $lastname = trim($_POST['lastname']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $phone = trim($_POST['phone']);
        $role_id = $_POST['role_id'];
        $department_id = $_POST['department_id'];
        $shift_id = $_POST['shift_id'];
        $hire_date = $_POST['hire_date'];

        try {

            $stmt = $pdo->prepare("
                INSERT INTO employees
                (
                    employee_id,
                    firstname,
                    lastname,
                    email,
                    password,
                    phone,
                    role_id,
                    department_id,
                    shift_id,
                    hire_date
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $employee_id,
                $firstname,
                $lastname,
                $email,
                $password,
                $phone,
                $role_id,
                $department_id,
                $shift_id,
                $hire_date
            ]);

            $message = "Employee added successfully!";

        } catch(PDOException $e) {
            $error = $e->getMessage();
        }
    }

    // UPDATE
    if (isset($_POST['action']) && $_POST['action'] == 'update') {

        $id = $_POST['employee_db_id'];

        try {

            $stmt = $pdo->prepare("
                UPDATE employees
                SET
                    firstname = ?,
                    lastname = ?,
                    email = ?,
                    phone = ?,
                    role_id = ?,
                    department_id = ?,
                    shift_id = ?,
                    status = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $_POST['firstname'],
                $_POST['lastname'],
                $_POST['email'],
                $_POST['phone'],
                $_POST['role_id'],
                $_POST['department_id'],
                $_POST['shift_id'],
                $_POST['status'],
                $id
            ]);

            $message = "Employee updated successfully!";

        } catch(PDOException $e) {
            $error = $e->getMessage();
        }
    }
}

// ============================================================
// FETCH EMPLOYEES
// ============================================================
$stmt = $pdo->query("
    SELECT
        e.*,
        r.role_name,
        r.monthly_salary,
        d.department_name,
        s.shift_name
    FROM employees e
    JOIN roles r ON e.role_id = r.id
    LEFT JOIN departments d ON e.department_id = d.id
    LEFT JOIN shifts s ON e.shift_id = s.id
    ORDER BY e.created_at DESC
");

$employees = $stmt->fetchAll();

// DROPDOWNS
$roles = $pdo->query("SELECT * FROM roles")->fetchAll();
$departments = $pdo->query("SELECT * FROM departments")->fetchAll();
$shifts = $pdo->query("SELECT * FROM shifts")->fetchAll();

// ============================================================
// DASHBOARD STATS
// ============================================================

// TOTAL EMPLOYEES
$stmt = $pdo->query("
    SELECT COUNT(*) 
    FROM employees
    WHERE status = 'active'
");
$total_employees = $stmt->fetchColumn();

// TOTAL HR
$stmt = $pdo->query("
    SELECT COUNT(*) 
    FROM employees e
    JOIN roles r ON e.role_id = r.id
    WHERE r.role_name LIKE '%HR%'
");
$total_hr = $stmt->fetchColumn();

// TOTAL DEPARTMENTS
$stmt = $pdo->query("SELECT COUNT(*) FROM departments");
$total_departments = $stmt->fetchColumn();

// TOTAL SHIFTS
$stmt = $pdo->query("SELECT COUNT(*) FROM shifts");
$total_shifts = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    <link rel="stylesheet" href="../assets/employee.css">
    <link rel="stylesheet" href="../assets/dashboard.css">
    <style>
        /* Additional CSS to fix table width and action buttons alignment */
        .table-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .data-table {
            width: 100%;
            min-width: 1200px;
            border-collapse: collapse;
            white-space: nowrap;
        }

        .data-table th,
        .data-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .data-table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: #1e293b;
        }

        /* Fixed width for specific columns */
        .data-table th:nth-child(1),
        .data-table td:nth-child(1) {
            min-width: 100px;
        } /* ID */

        .data-table th:nth-child(2),
        .data-table td:nth-child(2) {
            min-width: 150px;
        } /* Name */

        .data-table th:nth-child(3),
        .data-table td:nth-child(3) {
            min-width: 200px;
        } /* Email */

        .data-table th:nth-child(4),
        .data-table td:nth-child(4) {
            min-width: 120px;
        } /* Password */

        .data-table th:nth-child(5),
        .data-table td:nth-child(5) {
            min-width: 120px;
        } /* Role */

        .data-table th:nth-child(6),
        .data-table td:nth-child(6) {
            min-width: 130px;
        } /* Department */

        .data-table th:nth-child(7),
        .data-table td:nth-child(7) {
            min-width: 120px;
        } /* Shift */

        .data-table th:nth-child(8),
        .data-table td:nth-child(8) {
            min-width: 100px;
        } /* Salary */

        .data-table th:nth-child(9),
        .data-table td:nth-child(9) {
            min-width: 100px;
        } /* Status */

        .data-table th:nth-child(10),
        .data-table td:nth-child(10) {
            min-width: 150px;
            position: sticky;
            right: 0;
            background-color: white;
        } /* Action */

        /* Action buttons container */
        .action-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: nowrap;
        }

        .action-buttons .btn-sm {
            white-space: nowrap;
            margin: 0;
        }

        /* Make sure the action column doesn't wrap */
        .data-table td:last-child {
            white-space: nowrap;
        }

        /* For screens smaller than 1400px, enable horizontal scroll */
        @media (max-width: 1400px) {
            .table-container {
                overflow-x: auto;
            }
            
            .data-table {
                min-width: 1300px;
            }
        }

        /* Status badge styles */
        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-align: center;
            white-space: nowrap;
        }

        .status-active {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-inactive {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
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
            <a href="employee.php" class="nav-link active">
                <i>👥</i> Employees
            </a>
        </li>

        <li class="nav-item">
            <a href="attendance_hr.php" class="nav-link">
                <i>📋</i> Attendance
            </a>
        </li>

        <li class="nav-item">
            <a href="manage_leaves.php" class="nav-link">
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

        <strong> <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?> </strong>
        <small> <?php echo htmlspecialchars($user['email']); ?> </small>

        <?php if ($user['is_admin']): ?>
            <span class="badge badge-admin">ADMIN</span>
        <?php elseif ($user['is_hr']): ?>
            <span class="badge badge-hr">HR</span>
        <?php endif; ?>

    </div>

</aside>

<!-- MAIN CONTENT -->
<main class="main-content">

    <!-- TOPBAR -->
    <div class="topbar">
        <h1>👥 Employee Management</h1>
    </div>

    <!-- ALERTS -->
    <?php if($message): ?>
        <div class="alert alert-success">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <?php if($error): ?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <!-- EMPLOYEE TABLE -->
    <div class="card">

        <div class="card-header">

            <h3 class="card-title">All Employees</h3>

            <button class="btn btn-primary"
                    onclick="openModal('addModal')">
                + Add Employee
            </button>

        </div>

        <div class="table-container">

            <table class="data-table">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Shift</th>
                        <th>Salary</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                <?php foreach($employees as $emp): ?>

                    <tr>
                        <td><?php echo $emp['employee_id']; ?></td>
                        <td><?php echo $emp['firstname'] . ' ' . $emp['lastname']; ?></td>
                        <td><?php echo $emp['email']; ?></td>
                        <td><?php echo $emp['password']; ?></td>
                        <td><?php echo $emp['role_name']; ?></td>
                        <td><?php echo $emp['department_name']; ?></td>
                        <td><?php echo $emp['shift_name']; ?></td>
                        <td>₱<?php echo number_format($emp['monthly_salary'], 2); ?></td>
                        <td>
                            <span class="status status-<?php echo $emp['status']; ?>">
                                <?php echo ucfirst($emp['status']); ?>
                            </span>
                        </td>

                        <td>
                            <div class="action-buttons">
                                <button type="button" class="btn btn-primary btn-sm"
                                        onclick='editEmployee(<?php echo json_encode($emp); ?>)'>
                                    Edit
                                </button>
                                <a href="?delete=<?php echo $emp['id']; ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Delete <?php echo htmlspecialchars($emp['firstname'] . ' ' . $emp['lastname']); ?>? This cannot be undone.')">
                                    Delete
                                </a>
                            </div>
                        </td>
                    </tr>

                <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- ADD EMPLOYEE MODAL -->
<div id="addModal" class="modal">
    <div class="modal-content">

        <div class="modal-header">
            <h3 class="modal-title">Add New Employee</h3>

            <button class="close-btn"
                    onclick="closeModal('addModal')">
                &times;
            </button>
        </div>

        <form method="POST">

            <input type="hidden" name="action" value="add">

            <div class="form-row">

                <div class="form-group">
                    <label>First Name *</label>
                    <input type="text" name="firstname" required>
                </div>

                <div class="form-group">
                    <label>Last Name *</label>
                    <input type="text" name="lastname" required>
                </div>

            </div>

            <div class="form-row">

                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="password" required>
                </div>

            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone">
            </div>

            <div class="form-row">

                <div class="form-group">

                    <label>Role *</label>

                    <select name="role_id" required>

                        <option value="">
                            Select Role
                        </option>

                        <?php foreach ($roles as $role): ?>

                        <option value="<?php echo $role['id']; ?>">

                            <?php echo htmlspecialchars($role['role_name']); ?>

                            - ₱<?php echo number_format($role['monthly_salary'], 0); ?>/month

                        </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="form-group">

                    <label>Department *</label>

                    <select name="department_id" required>

                        <option value=""> Select Department </option>

                        <?php foreach ($departments as $dept): ?>

                        <option value="<?php echo $dept['id']; ?>">
                            <?php echo htmlspecialchars($dept['department_name']); ?>
                        </option>

                        <?php endforeach; ?>

                    </select>
                </div>
            </div>

            <div class="form-row">

                <div class="form-group">

                    <label>Shift *</label>

                    <select name="shift_id" required>

                        <option value=""> Select Shift </option>

                        <?php foreach ($shifts as $shift): ?>

                        <option value="<?php echo $shift['id']; ?>">

                            <?php echo htmlspecialchars($shift['shift_name']); ?>

                            (<?php echo $shift['time_in'] . ' - ' . $shift['time_out']; ?>)

                        </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="form-group">
                    <label>Hire Date *</label>
                    <input type="date" name="hire_date" value="<?php echo date('Y-m-d'); ?>" required>
                </div>

            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary"> Add Employee </button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('addModal')"> Cancel </button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT EMPLOYEE MODAL -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">

            <h3 class="modal-title"> Edit Employee </h3>

            <button class="close-btn" onclick="closeModal('editModal')"> &times; </button>

        </div>

        <form method="POST" id="editForm">

            <input type="hidden" name="action" value="update">
            <input type="hidden" name="employee_db_id" id="edit_id">

            <div class="form-row">

                <div class="form-group">
                    <label>First Name *</label>
                    <input type="text" name="firstname" id="edit_firstname" required>
                </div>

                <div class="form-group">
                    <label>Last Name *</label>
                    <input type="text" name="lastname" id="edit_lastname" required>
                </div>

            </div>

            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" id="edit_email" required>
            </div>

            <div class="form-group">
                <label>Password *</label>
                <input type="password" name="pass" id="pass" required>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" id="edit_phone">
            </div>

            <div class="form-row">

                <div class="form-group">

                    <label>Role *</label>

                    <select name="role_id" id="edit_role_id" required>

                        <?php foreach ($roles as $role): ?>

                        <option value="<?php echo $role['id']; ?>">

                            <?php echo htmlspecialchars($role['role_name']); ?>

                        </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="form-group">

                    <label>Department *</label>

                    <select name="department_id" id="edit_department_id" required>

                        <?php foreach ($departments as $dept): ?>

                        <option value="<?php echo $dept['id']; ?>">
                            <?php echo htmlspecialchars($dept['department_name']); ?>
                        </option>

                        <?php endforeach; ?>

                    </select>

                </div>

            </div>

            <div class="form-row">

                <div class="form-group">

                    <label>Shift *</label>

                    <select name="shift_id" id="edit_shift_id" required>

                        <?php foreach ($shifts as $shift): ?>

                        <option value="<?php echo $shift['id']; ?>">
                            <?php echo htmlspecialchars($shift['shift_name']); ?>
                        </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="form-group">

                    <label>Status *</label>

                    <select name="status" id="edit_status" required>
                        <option value="active"> Active </option>
                        <option value="inactive"> Inactive </option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary"> Update Employee </button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')"> Cancel </button>
            </div>
        </form>
    </div>
</div>

<script>

// OPEN MODAL
function openModal(id){
    document.getElementById(id).classList.add('show');
}

// CLOSE MODAL
function closeModal(id){
    document.getElementById(id).classList.remove('show');
}

// EDIT EMPLOYEE
function editEmployee(emp){

    document.getElementById('edit_id').value = emp.id;
    document.getElementById('edit_firstname').value = emp.firstname;
    document.getElementById('edit_lastname').value = emp.lastname;
    document.getElementById('edit_email').value = emp.email;
    document.getElementById('edit_phone').value = emp.phone;
    document.getElementById('edit_role_id').value = emp.role_id;
    document.getElementById('edit_department_id').value = emp.department_id;
    document.getElementById('edit_shift_id').value = emp.shift_id;
    document.getElementById('edit_status').value = emp.status;

    openModal('editModal');
}

// CLOSE WHEN CLICK OUTSIDE
window.onclick = function(event){

    if(event.target.classList.contains('modal')){
        event.target.classList.remove('show');
    }

}

</script>
</body>
</html>