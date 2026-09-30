<?php 
 
// START SESSION TO REMEMBER THE LAST SEARCH AND PAGE FOR THE USER 
session_start(); 
 
require "database.php"; 
 
// COOKIE: REMEMBER THE LAST QUICK SEARCH FOR 30 DAYS 
if (isset($_GET['search'])) { 
    $searchValue = trim($_GET['search']); 
    $_SESSION['last_search'] = $searchValue; 
    setcookie('last_employee_search', $searchValue, time() + 60 * 60 * 24 * 30, '/'); 
} elseif (isset($_SESSION['last_search'])) { 
    $searchValue = $_SESSION['last_search']; 
} elseif (isset($_COOKIE['last_employee_search'])) { 
    $searchValue = $_COOKIE['last_employee_search']; 
} else { 
    $searchValue = ''; 
} 
 
// SEARCH AND PAGINATION 
$search = trim($searchValue); 
$limit = 10; 
 
if (isset($_GET['page'])) { 
    $_SESSION['last_page'] = max(1, (int) $_GET['page']); 
    $page = max(1, (int) $_GET['page']); 
} elseif (isset($_SESSION['last_page'])) { 
    $page = max(1, (int) $_SESSION['last_page']); 
} else { 
    $page = 1; 
} 
 
$offset = ($page - 1) * $limit; 
 
$sql = "SELECT * FROM employees"; 
$params = []; 
 
if ($search !== '') { 
    $sql .= " WHERE name LIKE :search OR email LIKE :search OR department LIKE :search"; 
    $params['search'] = "%$search%"; 
} 
 
$sql .= " ORDER BY id DESC LIMIT :limit OFFSET :offset"; 
 
$stmt = $pdo->prepare($sql); 
 
foreach ($params as $key => $value) { 
    $stmt->bindValue(":" . $key, $value); 
} 
 
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT); 
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT); 
$stmt->execute(); 
 
$employees = $stmt->fetchAll(); 
 
$countSql = "SELECT COUNT(*) FROM employees"; 
$countParams = []; 
 
if ($search !== '') { 
    $countSql .= " WHERE name LIKE :search OR email LIKE :search OR department LIKE :search"; 
    $countParams['search'] = "%$search%"; 
} 
 
$countStmt = $pdo->prepare($countSql); 
foreach ($countParams as $key => $value) { 
    $countStmt->bindValue(":" . $key, $value); 
} 
$countStmt->execute(); 
$totalEmployees = (int) $countStmt->fetchColumn(); 
$totalPages = max(1, (int) ceil($totalEmployees / $limit)); 
 
?> 
 
<!DOCTYPE html> 
<html lang="en"> 
    <head> 
        <title>Employee Management System</title> 
        <meta charset="UTF-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <link rel="stylesheet" href="style.css"> 
    </head> 
    <body> 
        <h1>Employee Management</h1> 
 
        <a href="create.php">Add employee</a> 
 
        <form method="GET" style="margin: 20px 0;"> 
            <input 
                type="text" 
                name="search" 
                value="<?= htmlspecialchars($search) ?>" 
                placeholder="Search employee" 
            > 
 
            <button type="submit">Search</button> 
 
            <?php if ($search !== ''): ?> 
                <a href="index.php?search=&page=1">Clear</a> 
            <?php endif; ?> 
        </form> 
 
        <table border="1"> 
            <tr> 
                <th>ID</th> 
                <th>NAME</th> 
                <th>EMAIL</th> 
                <th>DEPARTMENT</th> 
                <th>SALARY</th> 
                <th>ACTION</th> 
            </tr> 
 
            <?php foreach ($employees as $employee): ?> 
 
                <tr> 
                    <td><?= htmlspecialchars($employee['id']) ?></td> 
                    <td><?= htmlspecialchars($employee['name']) ?></td> 
                    <td><?= htmlspecialchars($employee['email']) ?></td> 
                    <td><?= htmlspecialchars($employee['department']) ?></td> 
                    <td><?= htmlspecialchars($employee['salary']) ?></td> 
                    <td> 
                        <a href="edit.php?id=<?= $employee['id'] ?>">Update</a> 
                        <a href="delete.php?id=<?= $employee['id'] ?>">Delete</a> 
                    </td> 
                </tr> 
            <?php endforeach; ?> 
        </table> 
 
        <?php if ($totalPages > 1): ?> 
            <div style="margin-top: 20px;"> 
                <?php if ($page > 1): ?> 
                    <a href="index.php?search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">Previous</a> 
                <?php endif; ?> 
 
                <span>Page <?= $page ?> of <?= $totalPages ?></span> 
 
                <?php if ($page < $totalPages): ?> 
                    <a href="index.php?search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">Next</a> 
                <?php endif; ?> 
            </div> 
        <?php endif; ?> 
    </body> 
</html> 
 
