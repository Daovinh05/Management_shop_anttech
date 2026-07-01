<?php
require_once __DIR__ . '/Config.php';
include_once __DIR__ . '/../../Public/Classes/TimezoneHelper.php';

class connectDB
{
    public $con;
    function __construct()
    {
        $this->con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        mysqli_query($this->con, "SET NAMES 'utf8'");
        mysqli_query($this->con, "SET time_zone = '+07:00'");
    }
}
