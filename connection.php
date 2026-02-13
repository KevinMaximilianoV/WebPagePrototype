<?php

$dbhost ="localhost";
$dbuser = "kevin_concurrente";
$dbpass = "72seasons";
$dbname = "rebibanelserber";

if(!$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname))
{
    die("no se pudo realizar la conexion");
}