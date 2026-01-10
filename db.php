<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'wypozyczalnia';

$mysqli = new mysqli($host, $user, $pass, $dbname);

if ($mysqli->connect_errno) {
    die('Blad polaczenia z baza: ' . $mysqli->connect_error);
}

$mysqli->set_charset('utf8mb4');
