<?php
$servername = "localhost";
$username = "root";
$password = ""; // Leave this empty if there's no password
$dbname = "activity_schedule";

//Kết nối database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_time = $_POST["start_time"];
    $end_time = $_POST["end_time"];
    $name = $_POST["name"];
    $details = $_POST["details"];
    $person = $_POST["person"];

    $stmt = $conn->prepare("INSERT INTO activities (start_time, end_time, name, details, person) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $start_time, $end_time, $name, $details, $person);

    if ($stmt->execute()) {
        echo "<h1>Thêm sự kiện thành công!</h1>
                <br>
                <a href='index.html'>
                    <i>Quay trở về trang trước</i>
                </a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
