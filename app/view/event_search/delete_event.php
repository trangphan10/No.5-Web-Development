<?php
// Include the database connection
require __DIR__ . '/../../common/db.php';

// Check if the 'id' parameter is provided
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Prepare the delete query
    $query = "DELETE FROM events WHERE id = :id";
    $stmt = $pdo->prepare($query);

    // Execute the query
    if ($stmt->execute(['id' => $id])) {
        // Redirect back to the event management page
        header('Location: search_events.php?status=deleted');
        exit;
    } else {
        echo "Failed to delete the event. Please try again.";
    }
} else {
    echo "Invalid request. No event ID provided.";
}