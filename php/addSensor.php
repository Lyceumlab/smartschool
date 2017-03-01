<?php
/**
 * Created by PhpStorm.
 * User: Thomas Egbert Duursma
 * Date: 17-2-2017
 * Time: 20:22
 */

namespace smartschool;

require_once "user.php";
require_once "sensor.php";

if (key_exists("username", $_GET) && key_exists("password", $_GET)) {
    $username = $_GET["username"];
    $password = $_GET["password"];
    $user = new user($username);
    if ($user->verifyPassword($password)) {
        if($user->has_permission(1)) {
            if (key_exists("location", $_GET) && key_exists("type", $_GET)) {
                $location = $_GET["location"];
                $type = $_GET["type"];
                $sensor = new sensor(null, $location, $type, true);
                echo json_encode(array("sensor" => $sensor));
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