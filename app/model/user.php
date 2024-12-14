function searchUsers($type, $keyword) {
    global $conn;

    $sql = "SELECT * FROM users WHERE 1=1";
    $params = [];

    // Điều kiện phân loại
    if ($type != '') {
        $sql .= " AND type = :type";
        $params[':type'] = $type;
    }

    // Điều kiện từ khóa
    if ($keyword != '') {
        $sql .= " AND (name LIKE :keyword OR description LIKE :keyword)";
        $params[':keyword'] = '%' . $keyword . '%';
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
