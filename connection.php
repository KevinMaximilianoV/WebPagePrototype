<?php

$dbhost ="localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "rebibanelserber";

if(!$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname))
{
    die("no se pudo realizar la conexion");
}