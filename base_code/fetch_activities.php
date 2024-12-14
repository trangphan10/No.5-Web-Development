<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "activity_schedule";

//Kết nối database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Lấy dữ liệu từ database
$sql = "SELECT id, start_time, end_time, name, details FROM activities";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["start_time"] . " - " . $row["end_time"] . "</td>
                <td>" . $row["name"] . ": " . $row["details"] . "</td>
                <td><button class='edit-button'>Sửa</button></td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No activities found</td></tr>";
}

$conn->close();
?>