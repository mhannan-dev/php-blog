<?php

/**
 * Contact — all database operations for contact messages.
 */
class Contact
{
    public function __construct(private Database $db) {}

    public function getUnread(): mysqli_result|false
    {
        return $this->db->select(
            "SELECT * FROM contacts WHERE status = '0' ORDER BY id DESC"
        );
    }

    public function getSeen(): mysqli_result|false
    {
        return $this->db->select(
            "SELECT * FROM contacts WHERE status = '1' ORDER BY id DESC"
        );
    }

    public function getById(int $id): array|false
    {
        $result = $this->db->select(
            "SELECT * FROM contacts WHERE id = $id LIMIT 1"
        );
        return $result ? $result->fetch_assoc() : false;
    }

    public function getUnreadCount(): int
    {
        $result = $this->db->link->query(
            "SELECT COUNT(*) AS cnt FROM contacts WHERE status = '0'"
        );
        return $result ? (int) $result->fetch_assoc()['cnt'] : 0;
    }

    public function create(string $fname, string $lname, string $email, string $msg): bool
    {
        $fname = $this->db->escape($fname);
        $lname = $this->db->escape($lname);
        $email = $this->db->escape($email);
        $msg   = $this->db->escape($msg);
        return $this->db->insert(
            "INSERT INTO contacts (fname, lname, email, msg)
             VALUES ('$fname', '$lname', '$email', '$msg')"
        );
    }

    public function markAsSeen(int $id): bool
    {
        return $this->db->update(
            "UPDATE contacts SET status = '1' WHERE id = $id"
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->delete("DELETE FROM contacts WHERE id = $id");
    }
}
