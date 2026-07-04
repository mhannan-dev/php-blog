<?php

/**
 * Database class — wraps MySQLi with safer error handling.
 * Credentials are private; raw link is exposed only for legacy
 * mysqli_real_escape_string() calls that have not yet been migrated.
 */
class Database
{
    private static ?Database $instance = null;

    private string $host;
    private string $user;
    private string $pass;
    private string $dbname;

    /** @var mysqli */
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
            // In production, never expose connection details to the browser.
            error_log('DB connection failed: ' . $this->link->connect_error);
            die('Database connection error. Please try again later.');
        }

        $this->link->set_charset('utf8mb4');
    }

    /**
     * Escape a string value for safe interpolation into a query.
     * Prefer prepared statements for new code; use this for legacy queries.
     */
    public function escape(string $value): string
    {
        return $this->link->real_escape_string($value);
    }

    /**
     * Execute a SELECT query and return the result set, or false if no rows.
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
     * Execute an INSERT query. Returns true on success, false on failure.
     */
    public function insert(string $query): bool
    {
        $result = $this->link->query($query);

        if ($result === false) {
            error_log('DB insert error: ' . $this->link->error . ' | Query: ' . $query);
            return false;
        }

        return true;
    }

    /**
     * Execute an UPDATE query. Returns true on success, false on failure.
     */
    public function update(string $query): bool
    {
        $result = $this->link->query($query);

        if ($result === false) {
            error_log('DB update error: ' . $this->link->error . ' | Query: ' . $query);
            return false;
        }

        return true;
    }

    /**
     * Execute a DELETE query. Returns true on success, false on failure.
     */
    public function delete(string $query): bool
    {
        $result = $this->link->query($query);

        if ($result === false) {
            error_log('DB delete error: ' . $this->link->error . ' | Query: ' . $query);
            return false;
        }

        return true;
    }
}
