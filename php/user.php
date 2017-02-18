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

    function __construct(string $username, string $password = null)
    {
        $this->username = $username;

        if (!is_null($password)) {
            $connection = new mysqli("localhost", "root", "", "smartschool");

            $stmt = $connection->prepare("INSERT INTO users SET username = ?, hash = ?;");

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt->bind_param("ss", $username, $hash);

            $stmt->execute();

            $connection->close();
        }
    }
}
