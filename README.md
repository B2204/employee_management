# Employee Management System

A small PHP application for managing employee records with MySQL.

## Features

- Create, view, update, and delete employee records
- Search by name, email, or department
- Paginate employee results

## Requirements

- PHP 8.0 or newer
- MySQL
- PHP PDO and PDO MySQL extensions

## Setup

1. Create a MySQL database named `employees` (or change `$dbname` in `database.php`).
2. Create the table used by the application:

   ```sql
   CREATE TABLE employees (
       id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(255) NOT NULL,
       email VARCHAR(255) NOT NULL,
       phone VARCHAR(50),
       department VARCHAR(255),
       salary DECIMAL(10, 2) NOT NULL DEFAULT 0
   );
   ```

3. Set the MySQL host, database name, username, and password in `database.php` to match your environment.
4. From the project directory, start PHP's development server:

   ```sh
   php -S 127.0.0.1:8000
   ```

5. Open <http://127.0.0.1:8000> in your browser.

The included `employees.sqlite` file is not used by the current application configuration, which connects to MySQL.
