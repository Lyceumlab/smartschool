<?php
/**
 * Created by PhpStorm.
 * User: Thomas Egbert Duursma
 * Date: 17-2-2017
 * Time: 17:11
 */

namespace smartschool;


use mysqli;
use mysqli_stmt;

require_once "sensor.php";

class reading
{
    /** @var sensor $sensor */
    public $sensor;

    /** @var int $value */
    public $value;

    /** @var int $datetime */
    public $datetime;

    /**
     * reading constructor.
     * @param sensor $sensor
     * @param int $value
     * @param int|null $datetime
     * @param bool $insert
     */
    function __construct(sensor &$sensor, int $value = 0, int $datetime = null, bool $insert = true)
    {
        $this->sensor = &$sensor;
        $this->value = $value;
        $this->datetime = !is_null($datetime) ? $datetime : time();

        if ($insert) {
            /** @var mysqli $connection */
            $connection = new mysqli("localhost", "root", "", "smartschool");

            /** @var mysqli_stmt $stmt */
            $stmt = $connection->prepare("INSERT INTO readings SET id = ?, reading = ?, datetime = ?;");

            $stmt->bind_param("iii", $sensor->id, $this->value, $this->datetime);

            $stmt->execute();

            $connection->close();
        }
    }
}
