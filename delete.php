<?php

require "database.php";

$id = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

if (!$id) {
    die("Invalid employee ID.");
}

/* Check employee exists */
$stmt = $pdo->prepare("
    SELECT id
    FROM employees
    WHERE id = :id
");

$stmt->execute([
    'id' => $id
]);

$employee = $stmt->fetch();

if (!$employee) {
    die("Employee not found.");
}

/* Delete employee */
$stmt = $pdo->prepare("
    DELETE FROM employees
    WHERE id = :id
");

$stmt->execute([
    'id' => $id
]);

header("Location: index.php");
exit;

?>