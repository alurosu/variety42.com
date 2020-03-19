<?php
require_once('../config.php');
$conn = new mysqli($config->mysql->host, $config->mysql->user, $config->mysql->pass, $config->mysql->name);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT t.id as id FROM content c, tags t, content_tags ct WHERE c.id = ct.content_id AND t.id = ct.tag_id GROUP BY t.id HAVING count(c.id) > 10 ORDER BY RAND() LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $tag = $result->fetch_assoc();
}

$sql = "SELECT c.id FROM content c, content_tags ct, tags t WHERE c.id = ct.content_id AND t.id = ct.tag_id AND t.id = ".$tag['id']." ORDER BY RAND() LIMIT 1";
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
