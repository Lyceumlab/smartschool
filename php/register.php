<?php
/**
 * Created by PhpStorm.
 * User: Thomas Egbert Duursma
 * Date: 1-3-2017
 * Time: 19:57
 */

namespace smartschool;

require_once "user.php";

if (key_exists("username", $_GET) && key_exists("password", $_GET)) {
    $username = $_GET["username"];
    $password = $_GET["password"];
    $user = new user($username, $password);
    echo json_encode(array());
} else {
    echo json_encode(array("err" => "missing credentials"));
}
