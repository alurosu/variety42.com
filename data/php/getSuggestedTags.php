<?php
header('content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");

$folder = $_GET['folder'];
if (!file_exists("../../".$folder."/config.php"))
    $folder = "jokes";

require_once("../../".$folder."/config.php");

if (!empty($_GET['keyword'])) {
    $conn = new mysqli($config->mysql->host, $config->mysql->user, $config->mysql->pass, $config->mysql->name);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

	$keyword = strtolower($conn->real_escape_string($_GET['keyword']));

	$sql = "SELECT * FROM tags
		WHERE lower(name) LIKE '%$keyword%'
        LIMIT 10";

	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc())
			$resultSet['suggestions'][] = $row;
	} else $resultSet['error'] = 'No suggestions';
    $conn->close();
} else $resultSet['error'] = 'Missing keyword';

if (!empty($_GET['callback']))
	echo $_GET['callback'] . '(' .json_encode($resultSet) . ')';
else
	echo json_encode($resultSet);
?>
