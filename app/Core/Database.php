<?php

/**
 * Database class — wraps MySQLi with prepared statement support,
 * safer query execution, and singleton access pattern.
 */
class Database
{
    private static ?Database $instance = null;

    private string $host;
    private string $user;
    private string $pass;
    private string $dbname;

    public mysqli $link;

    private function __construct()
    {
        $this->host   = DB_HOST;
        $this->user   = DB_USER;
        $this->pass   = DB_PASS;
        $this->dbname = DB_NAME;
        $this->connectDB();
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __clone() {}

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    private function connectDB(): void
    {
        $this->link = new mysqli($this->host, $this->user, $this->pass, $this->dbname);

        if ($this->link->connect_errno) {
            error_log('DB connection failed: ' . $this->link->connect_error);
            die('Database connection error. Please try again later.');
        }

        $this->link->set_charset('utf8mb4');
    }

    /**
     * Escape a string value for safe interpolation into a query.
     * Prefer prepared statements for all new code.
     */
    public function escape(string $value): string
    {
        return $this->link->real_escape_string($value);
    }

    /**
     * Prepare a SQL statement for execution.
     */
    public function prepare(string $query): \mysqli_stmt|false
    {
        $stmt = $this->link->prepare($query);
        if ($stmt === false) {
            error_log('DB prepare error: ' . $this->link->error . ' | Query: ' . $query);
            return false;
        }
        return $stmt;
    }

    /**
     * Execute a prepared SELECT query and return all rows as an array.
     */
    public function fetchAll(string $query, string $types = '', array $params = []): array
    {
        $stmt = $this->prepare($query);
        if ($stmt === false) {
            return [];
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $rows;
    }

    /**
     * Execute a prepared SELECT query and return a single row.
     */
    public function fetchOne(string $query, string $types = '', array $params = []): array|false
    {
        $stmt = $this->prepare($query);
        if ($stmt === false) {
            return false;
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : false;
        $stmt->close();

        return $row;
    }

    /**
     * Execute a prepared INSERT query and return the insert ID.
     */
    public function insert(string $query, string $types = '', array $params = []): int|false
    {
        $stmt = $this->prepare($query);
        if ($stmt === false) {
            return false;
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $success = $stmt->execute();
        $insertId = $success ? $stmt->insert_id : false;
        $stmt->close();

        return $success ? $insertId : false;
    }

    /**
     * Execute a prepared UPDATE query and return affected rows count.
     */
    public function update(string $query, string $types = '', array $params = []): int|false
    {
        $stmt = $this->prepare($query);
        if ($stmt === false) {
            return false;
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $success = $stmt->execute();
        $affected = $success ? $stmt->affected_rows : false;
        $stmt->close();

        return $success ? $affected : false;
    }

    /**
     * Execute a prepared DELETE query and return affected rows count.
     */
    public function delete(string $query, string $types = '', array $params = []): int|false
    {
        $stmt = $this->prepare($query);
        if ($stmt === false) {
            return false;
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $success = $stmt->execute();
        $affected = $success ? $stmt->affected_rows : false;
        $stmt->close();

        return $success ? $affected : false;
    }

    /**
     * Execute a raw SELECT query (legacy support, prefer fetchAll/fetchOne).
     */
    public function select(string $query): mysqli_result|false
    {
        $result = $this->link->query($query);

        if ($result === false) {
            error_log('DB select error: ' . $this->link->error . ' | Query: ' . $query);
            return false;
        }
        return $result->num_rows > 0 ? $result : false;
    }

    /**
     * Execute a scalar query returning a single value.
     */
    public function fetchColumn(string $query, string $types = '', array $params = []): mixed
    {
        $stmt = $this->prepare($query);
        if ($stmt === false) {
            return false;
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $value = $result ? $result->fetch_row()[0] ?? false : false;
        $stmt->close();

        return $value;
    }
}
