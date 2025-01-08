<?php
// Include the database connection file
require __DIR__ . '/../../common/db.php';

// Fetch events with search functionality
$searchQuery = $_GET['search'] ?? '';
$query = "SELECT * FROM events 
          WHERE name LIKE :search 
             OR description LIKE :search 
             OR leader LIKE :search 
             OR slogan LIKE :search 
          ORDER BY id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute(['search' => "%$searchQuery%"]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .search-bar { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        button { padding: 5px 10px; margin: 0 2px; }
    </style>
    <script>
        function confirmDelete(name, id) {
            if (confirm(`Bạn chắc chắn muốn xóa sự kiện ${name}?`)) {
                window.location.href = `delete.php?id=${id}`;
            }
        }
    </script>
</head>
<body>
    <h1>Event Management</h1>

    <!-- Search Form -->
    <form method="GET" class="search-bar">
        <input type="text" name="search" placeholder="Search events" value="<?= htmlspecialchars($searchQuery) ?>">
        <button type="submit">Search</button>
    </form>

    <!-- Search Results -->
    <p>Number of results: <?= count($events) ?></p>
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>Tên sự kiện</th>
                <th>Slogan</th>
                <th>Leader</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $index => $event): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($event['name']) ?></td>
                    <td><?= htmlspecialchars($event['slogan']) ?></td>
                    <td><?= htmlspecialchars($event['leader']) ?></td>
                    <td>
                        <button onclick="confirmDelete('<?= htmlspecialchars($event['name']) ?>', <?= $event['id'] ?>)">Xóa</button>
                        <a href="edit_event.php?id=<?= $event['id'] ?>"><button>Sửa</button></a>
                        <a href="add_schedule.php?id=<?= $event['id'] ?>"><button>Lịch trình</button></a>
                        <a href="add_comment.php?id=<?= $event['id'] ?>"><button>Comment</button></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($events)): ?>
                <tr><td colspan="5">No events found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>