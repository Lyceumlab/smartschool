<?php
/**
 * Created by PhpStorm.
 * User: Thomas Egbert Duursma
 * Date: 17-2-2017
 * Time: 16:49
 */

namespace smartschool;


use mysqli;
use mysqli_stmt;

require_once "reading.php";

class sensor
{
    /**The id of the sensor
     * @var int $id
     */
    public $id;

    /**Get the most recent reading
     * @return reading
     */
    public function getReading(): reading
    {
        /** @var mysqli $connection */
        $connection = new mysqli("localhost", "root", "", "smartschool");

        /** @var mysqli_stmt $stmt */
        $stmt = $connection->prepare("SELECT reading, datetime FROM readings WHERE id = ? ORDER BY datetime DESC;");

        $stmt->bind_param("i", $this->id);

        $stmt->bind_result($value, $datetime);

        /** @var reading | null $reading */
        $reading = null;

        if ($stmt->execute()) {
            $stmt->fetch();
            $stmt->close();

            $value = !is_null($value) ? $value : 0;

            $reading = new reading($this, $value, $datetime, false);
        }

        $connection->close();

        return $reading ? $reading : new reading($this, 0, 0, false);
    }

    /**The location of the device
     * @var string $location
     */
    public $location;

    /**Set the location of the sensor
     * @param string $location
     * @return sensor
     */
    public function setLocation(string $location): sensor
    {
        /** @var mysqli $connection */
        $connection = new mysqli("localhost", "root", "", "smartschool");

        /** @var mysqli_stmt $stmt */
        $stmt = $connection->prepare("UPDATE sensors SET location = ? WHERE id = ?;");

        $stmt->bind_param("si", $location, $this->id);

        if ($stmt->execute()) {
            $this->location = $location;
        }

        $connection->close();

        return $this;
    }

    /**
     * sensor constructor.
     * @param int|null $id
     * @param string $location
     * @param string $type
     * @param bool $insert
     */
    function __construct(int $id = null, string $location, string $type, bool $insert = false)
    {
        if (!is_null($id)) {
            $this->id = $id;
        }
        $this->location = $location;

        $this->type = $type;

        if ($insert) {
            /** @var mysqli $connection */
            $connection = new mysqli("localhost", "root", "", "smartschool");

            /** @var mysqli_stmt $stmt */
            $stmt = $connection->prepare("INSERT INTO sensors SET location = ?, sensorType = ?;");

            $stmt->bind_param("ss", $location, $type);

            if ($stmt->execute()) {
                $this->id = $connection->insert_id;
            }

            $connection->close();
        }
    }

    /**The type of the sensor
     * @var string $type
     */
    public $type;

    /**Set the type of the sensor
     * @param string $type
     * @return sensor
     */
    public function setType(string $type): sensor
    {
        /** @var mysqli $connection */
        $connection = new mysqli("localhost", "root", "", "smartschool");

        /** @var mysqli_stmt $stmt */
        $stmt = $connection->prepare("UPDATE sensors SET sensorType = ? WHERE id = ?;");

        $stmt->bind_param("si", $type, $this->id);

        if ($stmt->execute()) {
            $this->type = $type;
        }

        $connection->close();

        return $this;
    }

    /**Get all readings between start and end, or return all readings if start and end are not specified
     * @param int $start
     * @param int|null $end
     * @return reading[]
     */
    public function getReadings(int $start = 0, int $end = null): array
    {
        $end = !is_null($end) ? $end : time();

        $connection = new mysqli("localhost", "root", "", "smartschool");

        $stmt = $connection->prepare("SELECT reading, datetime FROM readings WHERE datetime BETWEEN ? AND ? AND id = ?;");

        $stmt->bind_param("iii", $start, $end, $this->id);

        $stmt->bind_result($reading, $datetime);

        $result = array();

        if ($stmt->execute()) {
            while ($stmt->fetch()) {
                $reading = new reading($this, $reading, $datetime, false);
                array_push($result, $reading);
            }
            $stmt->close();
        }

        $connection->close();

        return $result;
    }
}

/**Get all sensors on the location
 * @param string $location
 * @return sensor[]
 */
function getSensorsByLocation(string $location): array
{
    /** @var mysqli $connection */
    $connection = new mysqli("localhost", "root", "", "smartschool");

    /** @var mysqli_stmt $stmt */
    $stmt = $connection->prepare("SELECT id, sensorType FROM sensors WHERE location = ?;");

    $stmt->bind_param("s", $location);

    /** @var int $id */
    /** @var string $type */
    $stmt->bind_result($id, $type);

    /** @var sensor[] $result */
    $result = array();

    if ($stmt->execute()) {
        while ($stmt->fetch()) {
            /** @var sensor $sensor */
            $sensor = new sensor($id, $location, $type);
            array_push($result, $sensor);
        }
        $stmt->close();
    }

    $connection->close();

    return $result;
}

/**
 * @param int $id
 * @return sensor | null
 */
function getSensorById(int $id)
{
    /** @var mysqli $connection */
    $connection = new mysqli("localhost", "root", "", "smartschool");

    /** @var mysqli_stmt $stmt */
    $stmt = $connection->prepare("SELECT location, sensorType FROM sensors WHERE id = ?;");

    $stmt->bind_param("i", $id);

    /** @var string $location */
    /** @var string $sensorType */
    $stmt->bind_result($location, $sensorType);

    /** @var sensor | null $sensor */
    $sensor = null;

    if ($stmt->execute()) {
        if ($stmt->fetch()) {
            $sensor = new sensor($id, $location, $sensorType);
        }
        $stmt->close();
    }

    $connection->close();

    return $sensor;
}

/**
 * @return sensor[]
 */
function getSensors() {
    /** @var mysqli $connection */
    $connection = new mysqli("localhost", "root", "", "smartschool");

    /** @var mysqli_stmt $stmt */
    $stmt = $connection->prepare("SELECT id, sensorType, location FROM sensors;");

    /** @var int $id */
    /** @var string $type */
    /** @var string $location */
    $stmt->bind_result($id, $type, $location);

    /** @var sensor[] $result */
    $result = array();

    if ($stmt->execute()) {
        while ($stmt->fetch()) {
            /** @var sensor $sensor */
            $sensor = new sensor($id, $location, $type);
            array_push($result, $sensor);
        }
        $stmt->close();
    }

    $connection->close();

    return $result;
}