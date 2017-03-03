<?php
/**
 * Created by PhpStorm.
 * User: Thomas Egbert Duursma
 * Date: 3-3-2017
 * Time: 17:08
 */

namespace smartschool;

require_once "user.php";

if (key_exists("username", $_GET) && key_exists("password", $_GET)) {
    $username = $_GET["username"];
    $password = $_GET["password"];
    $user = new user($username);
    if ($user->verifyPassword($password)) {
        if($user->has_permission(1)) {
            if (key_exists("target", $_GET) && key_exists("role", $_GET)) {
                $user2 = new user($_GET["target"]);

                $user2->setRole($_GET["role"]);

                echo json_encode(array());
            } else {
                echo json_encode(array("err" => "missing parameters"));
            }
        } else {
            echo json_encode(array("err" => "insufficient permissions"));
        }
    } else {
        echo json_encode(array("err" => "invalid credentials"));
    }
} else {
    echo json_encode(array("err" => "missing credentials"));
}
