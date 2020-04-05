<?php
require_once('../config.php');
require_once('../../data/php/single.php');
require_once('../../data/php/pagination.php');
$page = 1;
if(is_numeric($_GET['page']) && $_GET['page'] > 0){
    $page = $_GET['page'];
}
$tag_id = 1;
if(is_numeric($_GET['tag']))
    $tag_id = $_GET['tag'];
$conn = new mysqli($config->mysql->host, $config->mysql->user, $config->mysql->pass, $config->mysql->name);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = 'SELECT name FROM tags WHERE id = '.$tag_id;
$result = $conn->query($sql);
$tag['name'] = 'General';
if ($result->num_rows > 0) {
    // tag data
    $tag = $result->fetch_assoc();
} else $tag_id = 1;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?php echo sprintf($config->seo->tagPage->title, $tag['name'], $page); ?></title>

	<meta property="og:title" content="<?php echo sprintf($config->seo->tagPage->title, $tag['name'], $page); ?>">
	<meta property="og:type" content="website" />
	<meta property="og:image" content="https://variety42.com<?php echo $config->folder; ?>/variety42_share.jpg">
	<meta property="fb:app_id" content="616440488939771"/>

	<link rel="shortcut icon" href="/favicon.png" />
	<link rel="stylesheet" href="/data/css/main.css" />

	<script type="text/javascript" src="/data/js/jquery.js"></script>
	<script type="text/javascript" src="/data/js/main.js"></script>

    <?php require_once('../../data/php/header.php'); ?>
</head>

<body>
    <?php require_once("../data/php/menu.php"); ?>
    <div class="wrapper">
        <?php require_once("../data/php/ads/header.php"); ?>
        <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="/" title="variety42.com">
                        <svg class="home" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="home" class="svg-inline--fa fa-home fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                            <path fill="currentColor" d="M280.37 148.26L96 300.11V464a16 16 0 0 0 16 16l112.06-.29a16 16 0 0 0 15.92-16V368a16 16 0 0 1 16-16h64a16 16 0 0 1 16 16v95.64a16 16 0 0 0 16 16.05L464 480a16 16 0 0 0 16-16V300L295.67 148.26a12.19 12.19 0 0 0-15.3 0zM571.6 251.47L488 182.56V44.05a12 12 0 0 0-12-12h-56a12 12 0 0 0-12 12v72.61L318.47 43a48 48 0 0 0-61 0L4.34 251.47a12 12 0 0 0-1.6 16.9l25.5 31A12 12 0 0 0 45.15 301l235.22-193.74a12.19 12.19 0 0 1 15.3 0L530.9 301a12 12 0 0 0 16.9-1.6l25.5-31a12 12 0 0 0-1.7-16.93z"></path>
                        </svg>
                    </a>
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="caret-right" class="svg-inline--fa fa-caret-right fa-w-6" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512">
                        <path fill="currentColor" d="M0 384.662V127.338c0-17.818 21.543-26.741 34.142-14.142l128.662 128.662c7.81 7.81 7.81 20.474 0 28.284L34.142 398.804C21.543 411.404 0 402.48 0 384.662z"></path>
                    </svg>
                </li>
                <li>
                    <a href="<?php echo $config->folder; ?>" title="Jokes">Jokes</a>
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="caret-right" class="svg-inline--fa fa-caret-right fa-w-6" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512">
                        <path fill="currentColor" d="M0 384.662V127.338c0-17.818 21.543-26.741 34.142-14.142l128.662 128.662c7.81 7.81 7.81 20.474 0 28.284L34.142 398.804C21.543 411.404 0 402.48 0 384.662z"></path>
                    </svg>
                </li>
                <li> <?php echo $tag['name']; ?> </li>
            </ul>
        </div>
        <?php
        $nr_per_page = 20;
        $sql = 'SELECT c.id, c.text, c.likes, c.dislikes FROM content c, content_tags ct WHERE ct.tag_id = '.$tag_id.' AND ct.content_id = c.id ORDER BY date DESC LIMIT '.($page-1)*$nr_per_page.', '.$nr_per_page;
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            $i=1;
            while($row = $result->fetch_assoc()) {
                displaySingle($row['text'], $row['id'], $row['likes'], $row['dislikes']);
                if ($i == 5 || $i == 10 || $i == 15)
                    require("../data/php/ads/list.php");
                $i++;
            }

            $sql = 'SELECT count(c.id) as count FROM content c, content_tags ct WHERE ct.tag_id = '.$tag_id.' AND ct.content_id = c.id';

            $result = $conn->query($sql);
            $total = $result->fetch_assoc();
            echo pagination($total['count'], $config->folder.'/t/'.$tag_id);
        } else {
            echo "0 results";
        }
        ?>
    </div>

    <?php require_once('../../data/php/footer.php'); ?>
</body>
</html>
<?php $conn->close(); ?>
