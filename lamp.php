<?php

interface LampState
{
    public function touch();
}

class Off implements LampState
{
    public function touch()
    {
        echo 'Wlaczam sie...' . PHP_EOL;

        return new On();
    }
}

class On implements LampState
{
    public function touch()
    {
        echo 'Wylaczam sie...' . PHP_EOL;

        return new Off();
    }
}


class Lamp
{
    /**
     * @var LampState
     */
    private $state;

    public function __construct()
    {
        $this->state = new Off();
    }

    public function touch()
    {
        $this->state = $this->state->touch();
    }
}

$lamp = new Lamp();

$lamp->touch();
$lamp->touch();
$lamp->touch();
$lamp->touch();
$lamp->touch();
$lamp->touch();