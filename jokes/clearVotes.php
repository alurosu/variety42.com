<?php
require_once('config.php');

$conn = new mysqli($config->mysql->host, $config->mysql->user, $config->mysql->pass, $config->mysql->name);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$time = time()-60*60*24;
$sql = "DELETE FROM votes WHERE date < ".$time;

if ($conn->query($sql) === TRUE) {
    echo $time." - deleted old votes";
} else {
    echo "Error deleting record: " . $conn->error;
}
$conn->close(); ?>
