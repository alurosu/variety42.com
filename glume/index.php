<?php
require_once('config.php');
require_once('data/php/single.php');
require_once('data/php/pagination.php');
$page = 1;
if(is_numeric($_GET['page']) && $_GET['page'] > 0){
    $page = $_GET['page'];
}
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

	<title><?php echo $config->seo->firstPage->title; ?></title>
	<meta name="description" content="<?php echo $config->seo->firstPage->description; ?>">

	<meta property="og:title" content="<?php echo $config->seo->firstPage->title; ?>">
	<meta property="og:url" content="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>">
	<meta property="og:type" content="website" />
	<meta property="og:description" content="<?php echo $config->seo->firstPage->description; ?>">
	<meta property="og:image" content="https://amongdemons.com/minecraft/data/img/amongdemons_minecraft_fb.png">
	<meta property="fb:app_id" content="649162062152755"/>

	<link rel="shortcut icon" href="/favicon.png" />
	<link rel="stylesheet" href="data/css/main.css" />

	<script type="text/javascript" src="data/js/main.js"></script>
</head>

<body>
    <?php require_once("data/php/menu.php"); ?>
    <div class="wrapper">
        <?php
        $nr_per_page = 42;
        $sql = 'SELECT id, text, likes, dislikes, comments_count FROM content ORDER BY date DESC LIMIT '.($page-1)*$nr_per_page.', '.$nr_per_page;
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                displaySingle($row['text'], $row['id'], $row['likes'], $row['dislikes'], $row['comments_count']);
            }
        } else {
            echo "0 results";
        }

        $sql = 'SELECT count(id) as count FROM content';
        $result = $conn->query($sql);
        $total = $result->fetch_assoc();
        echo pagination($total['count'], $config->folder);
        ?>
    </div>

    <?php require_once('data/php/footer.php'); ?>
</body>
</html>
<?php $conn->close(); ?>
