<?php

require_once __DIR__ . "/../Config/Config.php";
require_once __DIR__ . "/../Config/Url.php";

class App extends DB
{
    public $conn;

    public function __construct()
    {
        $this->conn = self::connect();
    }

    public function create() {}

    public function retrieveOne() {}

    public function retrieveMany() {}

    public function update() {}

    public function delete() {}

    public function paginate($sqlBase, $params = [], $page = 1, $limit = 6)
    {
        $offset = ($page - 1) * $limit;

        // Get total count
        $countSql = "SELECT COUNT(*) FROM (" . $sqlBase . ") AS total_table";
        $stmt = $this->conn->prepare($countSql);
        $stmt->execute($params);
        $totalRows = $stmt->fetchColumn();
        $totalPages = ceil($totalRows / $limit);

        // Append LIMIT and OFFSET directly (cannot bind as parameters)
        $paginatedSql = $sqlBase . " LIMIT {$limit} OFFSET {$offset}";
        $stmt = $this->conn->prepare($paginatedSql);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data' => $data,
            'total' => $totalRows,
            'pages' => $totalPages,
            'page' => $page,
            'limit' => $limit
        ];
    }
}
