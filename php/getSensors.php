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
require_once "reading.php";

if (key_exists("username", $_GET) && key_exists("password", $_GET)) {
    $username = $_GET["username"];
    $password = $_GET["password"];
    $user = new user($username);
    if ($user->verifyPassword($password)) {
        $result = array();

        foreach (getSensors() as $sensor) {
            $result[$sensor->id] = array("sensor" => $sensor, "readings" => $sensor->getReadings());
        }

        echo json_encode(array("sensors" => $result));
    } else {
        echo json_encode(array("err" => "invalid credentials"));
    }
} else {
    echo json_encode(array("err" => "missing credentials"));
}