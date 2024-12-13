<?php
require_once '../common/db.php';

function insertUser($name, $category, $id, $description, $avatarPath) {
    global $pdo;
    $sql = "INSERT INTO users (name, category, id, description, avatar) 
            VALUES (:name, :category, :id, :description, :avatar)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':avatar', $avatarPath);
    return $stmt->execute();
}
?>
