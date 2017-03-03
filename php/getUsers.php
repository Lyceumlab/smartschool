<?php
/**
 * Created by PhpStorm.
 * User: Thomas Egbert Duursma
 * Date: 3-3-2017
 * Time: 16:38
 */

namespace smartschool;

require_once "user.php";

if (key_exists("username", $_GET) && key_exists("password", $_GET)) {
    $username = $_GET["username"];
    $password = $_GET["password"];
    $user = new user($username);
    if ($user->verifyPassword($password)) {
        $result = array();

        foreach (getUsers() as $user2) {
            array_push($result, $user2);
        }

        echo json_encode(array("users" => $result));
    } else {
        echo json_encode(array("err" => "invalid credentials"));
    }
} else {
    echo json_encode(array("err" => "missing credentials"));
}
