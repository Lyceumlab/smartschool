<?php
/**
 * Created by PhpStorm.
 * User: Thomas Egbert Duursma
 * Date: 18-2-2017
 * Time: 11:24
 */

namespace smartschool;


use mysqli;

class control
{
    /**The id of the control
     * @var int $id
     */
    public $id;

    /**Get the state of the control at the time, returns the current state if datetime is null
     * @param int|null $datetime
     * @return state will be zero if no state is found
     */
    public function getState(int $datetime = null): state
    {
        if (is_null($datetime)) {
            $datetime = time();
        }

        $connection = new mysqli("localhost", "root", "", "smartschool");

        $stmt = $connection->prepare("SELECT state FROM states WHERE id = ? AND datetime <= ? ORDER BY datetime DESC;");

        $stmt->bind_param("ii", $this->id, $datetime);

        $stmt->bind_result($value);

        $state = new state($this, 0, $datetime, false);

        if ($stmt->execute()) {
            $stmt->fetch();
            $stmt->close();

            $state->state = $value;
        }

        $connection->close();

        return $state;
    }

    /**The ip of the control
     * @var string $ip
     */
    public $ip;

    /**Set the ip of the sensor
     * @param string $ip
     * @return control
     */
    public function setIp(string $ip): control
    {
        $connection = new mysqli("localhost", "root", "", "smartschool");

        $stmt = $connection->prepare("UPDATE controls SET ip = ? WHERE id = ?;");

        $stmt->bind_param("si", $ip, $this->id);

        if ($stmt->execute()) {
            $this->ip = $ip;
        }

        $connection->close();

        return $this;
    }

    /**The type of the sensor
     * @var string $type
     */
    public $type;

    /**Set the type of the sensor
     * @param string $type
     * @return control
     */
    public function setType(string $type): control
    {
        $connection = new mysqli("localhost", "root", "", "smartschool");

        $stmt = $connection->prepare("UPDATE controls SET type = ? WHERE id = ?;");

        $stmt->bind_param("si", $type, $this->id);

        if ($stmt->execute()) {
            $this->type = $type;
        }

        $connection->close();

        return $this;
    }

    /**The location of the control
     * @var string $location
     */
    public $location;

    /**Set the location of the control
     * @param string $location
     * @return control
     */
    public function setLocation(string $location): control
    {
        $connection = new mysqli("localhost", "root", "", "smartschool");

        $stmt = $connection->prepare("UPDATE controls SET location = ? WHERE id = ?;");

        $stmt->bind_param("si", $location, $this->id);

        if ($stmt->execute()) {
            $this->location = $location;
        }

        $connection->close();

        return $this;
    }

    /**
     * control constructor.
     * @param int|null $id
     * @param string $location
     * @param string $ip
     * @param string $type
     * @param bool $insert
     */
    function __construct(int $id, string $location, string $ip, string $type, bool $insert = false)
    {
        $this->id = $id;
        $this->location = $location;
        $this->ip = $ip;
        $this->type = $type;

        if ($insert) {
            $connection = new mysqli("localhost", "root", "", "smartschool");

            $stmt = $connection->prepare("INSERT INTO controls SET location = ?, ip = ?, type = ?;");

            $stmt->bind_param("sss", $this->location, $this->ip, $this->type);

            if ($stmt->execute()) {
                $this->id = $stmt->insert_id;
            }

            $connection->close();
        }
    }
}

/**
 * @param int $id
 * @return control|null
 */
function getControlById(int $id)
{
    $connection = new mysqli("localhost", "root", "", "smartschool");

    $stmt = $connection->prepare("SELECT location, ip, type FROM controls WHERE id = ?;");

    $stmt->bind_param("i", $id);

    $stmt->bind_result($location, $ip, $type);

    $control = null;

    if ($stmt->execute()) {
        if ($stmt->fetch()) {
            $control = new control($id, $location, $ip, $type);
        }
        $stmt->close();
    }

    $connection->close();

    return $control;
}
