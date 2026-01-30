<?php
include_once __DIR__ . '/../../Public/Classes/TimezoneHelper.php';

class connectDB
{
    public $con;
    function __construct()
    {
        $this->con = mysqli_connect('localhost', 'root', '', 'phone_store_v2');
        mysqli_query($this->con, "SET NAMES 'utf8'");


        mysqli_query($this->con, "SET time_zone = '+07:00'");
    }
}
