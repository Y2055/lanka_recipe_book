<?php
// Database connection - change the password if your MySQL has one
$conn = new mysqli('localhost', 'root', '', 'recipe_book');
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');
