<?php
require_once('config.php');
require_once('data/php/single.php');
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
    <div class="wrapper">
        <?php
        $nr_per_page = 42;
        $sql = 'SELECT id, text, likes, dislikes, comments_count FROM content ORDER BY id LIMIT '.($page-1)*$nr_per_page.', '.$nr_per_page;
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
</body>
</html>
<?php
function pagination($count, $href) {
    global $nr_per_page, $page;
    $output = '<div class="pagination">';
    if(!isset($page)) $page = 1;

    if($nr_per_page != 0)
        $pages  = ceil($count/$nr_per_page);

    //if pages exists after loop's lower limit
    if($pages>1) {
        if(($page-3)>0) {
            $output = $output . '<a href="' . $href . '/1" class="page">1</a>';
        }
        if(($page-3)>1) {
            $output = $output . '...';
        }

        //Loop for provides links for 2 pages before and after current page
        for($i=($page-2); $i<=($page+2); $i++)	{
            if($i<1) continue;

            if($i>$pages) break;

            if($page == $i)
                $output = $output . '<span id='.$i.' class="current">'.$i.'</span>';
            else
                $output = $output . '<a href="' . $href . "/".$i . '" class="page">'.$i.'</a>';
        }

        //if pages exists after loop's upper limit
        if(($pages-($page+2))>1) {
            $output = $output . '...';
        }
        if(($pages-($page+2))>0) {
            if($page == $pages)
                $output = $output . '<span id=' . ($pages) .' class="current">' . ($pages) .'</span>';
            else
                $output = $output . '<a href="' . $href .  "/" .($pages) .'" class="page">' . ($pages) .'</a>';
        }
    }
    $output .= '</div>';
    return $output;
}

$conn->close(); ?>
