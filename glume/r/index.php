<?php
require_once('../config.php');
$conn = new mysqli($config->mysql->host, $config->mysql->user, $config->mysql->pass, $config->mysql->name);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id FROM content ORDER BY RAND() LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $raw = $result->fetch_assoc();
    echo $raw["id"];
}

$conn->close();

header( 'Cache-Control: no-cache, must-revalidate' );
header( 'Location: '.$config->single.'/'.$raw["id"], true, 307 );
exit;
?>
