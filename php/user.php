<?php
/**
 * Created by PhpStorm.
 * User: Thomas Egbert Duursma
 * Date: 17-2-2017
 * Time: 20:03
 */

namespace smartschool;


use mysqli;

class user
{
    /**The username of the user
     * @var string $username
     */
    public $username;

    public function verifyPassword($password)
    {
        $connection = new mysqli("localhost", "root", "", "smartschool");

        $stmt = $connection->prepare("SELECT hash FROM users WHERE username = ?;");

        $stmt->bind_param("s", $this->username);

        $stmt->bind_result($hash);

        $result = false;

        if ($stmt->execute()) {
            if ($stmt->fetch()) {
                $result = password_verify($password, $hash);
            }
            $stmt->close();
        }

        $connection->close();

        return $result;
    }

    public $role;

    public function setRole($role): user
    {
        $connection = new mysqli("localhost", "root", "", "smartschool");

        $stmt = $connection->prepare("UPDATE users SET role = ? WHERE username = ?;");

        $stmt->bind_param("ss", $role, $this->username);

        $stmt->execute();

        $connection->close();

        $this->role = $role;

        return $this;
    }

    function __construct(string $username, string $password = null, string $role = "user")
    {
        $this->username = $username;
        $this->role = $role;

        if (!is_null($password)) {
            $connection = new mysqli("localhost", "root", "", "smartschool");

            $stmt = $connection->prepare("INSERT INTO users SET username = ?, hash = ?, role = ?;");

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt->bind_param("sss", $username, $hash, $role);

            $stmt->execute();

            $connection->close();
        } else {
            $connection = new mysqli("localhost", "root", "", "smartschool");

            $stmt = $connection->prepare("SELECT role FROM users WHERE username = ?;");

            $stmt->bind_param("s", $username);

            $stmt->bind_result($role);

            if ($stmt->execute()) {
                $stmt->fetch();
                $stmt->close();
            }

            $this->role = $role;

            $connection->close();
        }
    }

    public function has_permission(int $level): bool
    {
        $connection = new mysqli("localhost", "root", "", "smartschool");

        $stmt = $connection->prepare("SELECT level FROM roles WHERE name = ?;");

        $stmt->bind_param("s", $this->role);

        $stmt->bind_result($actual_level);

        $valid = false;

        if ($stmt->execute()) {
            if ($stmt->fetch()) {
                $valid = $actual_level <= $level;
            }

            $stmt->close();
        }

        $connection->close();

        return $valid;
    }
}

function getUsers() {
    $connection = new mysqli("localhost", "root", "", "smartschool");

    $stmt = $connection->prepare("SELECT username FROM users;");

    $stmt->bind_result($username);

    $result = array();

    if($stmt->execute()) {
        while($stmt->fetch()) {
            array_push($result, new user($username));
        }
        $stmt->close();
    }

    $connection->close();

    return $result;
}
