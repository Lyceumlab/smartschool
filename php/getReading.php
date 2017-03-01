<?php
/**
 * Created by PhpStorm.
 * User: Thomas Egbert Duursma
 * Date: 27-2-2017
 * Time: 16:30
 */

namespace smartschool;

require_once "sensor.php";
require_once "reading.php";

$sensor = getSensorById(intval($_GET["id"]));

$reading = $sensor->getReading();

echo json_encode($reading);