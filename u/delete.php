<?php
$folder = $_GET['folder'];
if (!file_exists("../".$folder."/config.php"))
    $folder = "jokes";

require_once("../".$folder."/config.php");

$conn = new mysqli($config->mysql->host, $config->mysql->user, $config->mysql->pass, $config->mysql->name);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$message = '';
if(isset($_POST['delete']) && is_numeric($_POST['id'])){
    $id = $_POST['id'];
    $sql ="DELETE FROM content WHERE id = '$id'";
    $conn->query($sql);

    //delete all content tags
    $sql = "DELETE FROM content_tags WHERE content_id = $id";
    $conn->query($sql);

    // delete image
    $image_url = "../".$folder."/data/img/".$id.".jpg";
    unlink($image_url);

    $message = 'Done! <a href="'.$config->single.'/'.$id.'"> Click Here </a>';
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Deleted <?php echo $folder." - id: ".$id;?></title>

	<meta property="og:title" content="Deleted <?php echo $folder." - id: ".$id;?>">
	<meta property="og:type" content="website" />
	<meta property="og:description" content="User Panel">
	<meta property="og:image" content="https://variety42.com<?php echo $config->folder; ?>/variety42_share.jpg">
	<meta property="fb:app_id" content="616440488939771"/>

	<link rel="shortcut icon" href="/favicon.png" />
	<link rel="stylesheet" href="/data/css/main.css" />
	<link rel="stylesheet" href="/data/css/user.css" />

	<script type="text/javascript" src="/data/js/jquery.js"></script>
	<script type="text/javascript" src="/data/js/main.js"></script>
	<script type="text/javascript" src="/data/js/user.js"></script>

    <?php require_once('../data/php/header.php'); ?>
</head>

<body>
    <?php require_once("../".$folder."/data/php/menu.php"); ?>
    <div class="wrapper user">
        <h3>Deleted <?php echo $folder." - id: ".$id;?><div class="edit_notification"> <?php echo $message; ?></div></h3>
    </div>

    <?php require_once('../data/php/footer.php'); ?>
</body>
</html>
<?php $conn->close(); ?>
