<?php
require_once('config.php');
echo $_GET['page'];
$conn = new mysqli($config->mysql->host, $config->mysql->user, $config->mysql->pass, $config->mysql->name);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Jokes</title>
	<meta name="description" content="Java Edition 1.15.2 open to all, including non-premium users. Grief protection and always up to date.">

	<meta property="og:title" content="Vanilla Minecraft Server - Among Demons">
	<meta property="og:url" content="https://amongdemons.com/minecraft/">
	<meta property="og:type" content="website" />
	<meta property="og:description" content="Java Edition 1.15.2 open to all, including non-premium users. Grief protection and always up to date.">
	<meta property="og:image" content="https://amongdemons.com/minecraft/data/img/amongdemons_minecraft_fb.png">
	<meta property="fb:app_id" content="649162062152755"/>

	<link rel="shortcut icon" href="https://amongdemons.com/minecraft/data/img/AmongDemons_LogoSquare.png" />
	<link rel="stylesheet" href="data/css/main.css" />

	<script type="text/javascript" src="data/js/main.js"></script>
</head>

<body>
    <?php require_once("data/php/menu.php"); ?>
    <?php
    $sql = 'SELECT text FROM content';
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo nl2br(stripslashes($row["text"])). "<hr>";
        }
    } else {
        echo "0 results";
    }
    ?>
</body>
</html>
<?php $conn->close(); ?>
