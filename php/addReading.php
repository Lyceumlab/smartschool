<?php
/**
 * Created by PhpStorm.
 * User: Thomas Egbert Duursma
 * Date: 17-2-2017
 * Time: 22:41
 */

namespace smartschool;

require_once "sensor.php";
require_once "reading.php";

if(key_exists("id", $_GET) && key_exists("value", $_GET)) {
    $id = $_GET["id"];
    $value = $_GET["value"];

    $sensor = getSensorById($id);

    $reading = new reading($sensor, $value);

    echo json_encode(array("reading" => $reading));
} else {
    echo json_encode(array("err" => "missing parameters"));
}