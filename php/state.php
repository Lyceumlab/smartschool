<?php
/**
 * Created by PhpStorm.
 * User: Thomas Egbert Duursma
 * Date: 18-2-2017
 * Time: 11:44
 */

namespace smartschool;


use mysqli;

class state
{
    /**The control that this state belongs to
     * @var control $control
     */
    public $control;

    /**The value of this state
     * @var int $state
     */
    public $state;

    /**The date and time of the state
     * @var int $datetime
     */
    public $datetime;

    /**
     * state constructor.
     * @param control $control
     * @param int $state
     * @param int|null $datetime
     * @param bool $insert
     */
    function __construct(control &$control, int $state, int $datetime = null, bool $insert = true)
    {
        $this->control = &$control;
        $this->state = $state;
        /** @var int $datetime */
        $this->datetime = !is_null($datetime) ? $datetime : time();

        if ($insert) {
            $connection = new mysqli("localhost", "root", "", "smartschool");

            $stmt = $connection->prepare("INSERT INTO states SET id = ?, state = ?, datetime = ?;");

            $stmt->bind_param("iii", $this->control->id, $this->state, $this->datetime);

            if ($stmt->execute()) {
                $ip = $this->control->ip;

                $response = file_get_contents("http://$ip?state=$this->state");

                if ($response == "error") {
                    $this->error = "error";
                }
            }

            $connection->close();
        }
    }

    /**Contains any error that might be caused while setting the state
     * @var string|null $error
     */
    public $error;
}
