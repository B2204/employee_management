<?php

require 'database.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM employees WHERE id = :id LIMIT 1');
$stmt->execute([':id' => $id]);
$employee = $stmt->fetch();

if (!$employee) {
    header('Location: index.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $salary = trim($_POST['salary'] ?? '');

    if ($name === '') {
        $errors[] = 'Name is required.';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email is required.';
    }

    if ($salary === '' || !is_numeric($salary) || (float) $salary < 0) {
        $errors[] = 'Salary must be a valid number.';
    }

    if (empty($errors)) {
        $updateStmt = $pdo->prepare(
            'UPDATE employees SET name = :name, email = :email, phone = :phone, department = :department, salary = :salary WHERE id = :id'
        );

        $updateStmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':department' => $department,
            ':salary' => $salary,
            ':id' => $id,
        ]);

        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Edit Employee</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Edit Employee</h1>

        <?php if (!empty($errors)): ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="POST">
            <div>
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? $employee['name']) ?>" required>
            </div>

            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? $employee['email']) ?>" required>
            </div>

            <div>
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? $employee['phone']) ?>">
            </div>

            <div>
                <label for="department">Department</label>
                <input type="text" id="department" name="department" value="<?= htmlspecialchars($_POST['department'] ?? $employee['department']) ?>">
            </div>

            <div>
                <label for="salary">Salary</label>
                <input type="number" id="salary" name="salary" step="0.01" min="0" value="<?= htmlspecialchars($_POST['salary'] ?? $employee['salary']) ?>" required>
            </div>

            <button type="submit">Update Employee</button>
            <a href="index.php">Cancel</a>
        </form>
    </body>
</html>
