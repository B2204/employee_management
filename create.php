<?php

require "database.php";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $salary = trim($_POST['salary'] ?? '');

    // Validation

    if ($name === '') {
        $errors[] = "Name is required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }

    if (!is_numeric($salary) || $salary < 0) {
        $errors[] = "Invalid salary.";
    }

    if (empty($errors)) {

        $stmt = $pdo->prepare("
            INSERT INTO employees
            (name, email, phone, department, salary)
            VALUES
            (:name, :email, :phone, :department, :salary)
        ");

        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'department' => $department,
            'salary' => $salary
        ]);

        header("Location: index.php");
        exit;
    }
}

?>

<!DOCTYPE html>

<html>

<body>

<h2>Add Employee</h2>

<?php foreach ($errors as $error): ?>

<p>
    <?= htmlspecialchars($error) ?>
</p>

<?php endforeach; ?>

<form method="POST">

    <input
        type="text"
        name="name"
        placeholder="Name"
    >

    <br><br>

    <input
        type="email"
        name="email"
        placeholder="Email"
    >

    <br><br>

    <input
        type="text"
        name="phone"
        placeholder="Phone"
    >

    <br><br>

    <input
        type="text"
        name="department"
        placeholder="Department"
    >

    <br><br>

    <input
        type="number"
        name="salary"
        placeholder="Salary"
    >

    <br><br>

    <button type="submit">
        Add Employee
    </button>

</form>

</body>

</html>