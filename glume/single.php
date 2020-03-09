<?php
require_once('config.php');

$conn = new mysqli($config->mysql->host, $config->mysql->user, $config->mysql->pass, $config->mysql->name);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (is_numeric($_GET["id"]))
    $id = $_GET["id"];
else {
    header( 'Cache-Control: no-cache, must-revalidate' );
    header("Location: ".$config->folder."/r", true, 307);
    exit();
}

$sql = "SELECT c.text as text, c.likes as likes, c.dislikes as dislikes, c.comments_count as comments_count, c.date as date, u.user as author FROM content c, users u WHERE c.user_id = u.id AND c.id = ".$id;
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // joke data
    $single = $result->fetch_assoc();

    // generate meta
    $domain = $_SERVER[HTTP_HOST];
    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $raw = str_replace('"',"'", trim(preg_replace('/\s+/', ' ', stripslashes($single["text"]))));
    $end = " Read more at ".$domain;

    // keep title under 50 chars
    $pos=strpos($raw, ' ', 45);
    $title = $raw;
    if ($pos) {
        $title = substr($raw, 0, $pos);
        $raw = substr($raw, $pos, strlen($raw));
    }

    // keep description under 155 chars
    $description = $raw.$end;
    if (strlen($raw) + strlen($end) > 150) {
        $pos=strpos($raw, ' ', 150 - strlen($end));
        if ($pos)
            $description = substr($raw,0,$pos)."..".$end;
    }
} else {
    header( 'Cache-Control: no-cache, must-revalidate' );
    header("Location: ".$config->folder."/r", true, 307);
    exit();
}

// next and prev ids
$prev = 0;
$next = 0;

$sql = "SELECT id FROM content WHERE id < $id ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $nav = $result->fetch_assoc();
    $prev = $nav["id"];
}

$sql = "SELECT id FROM content WHERE id > $id ORDER BY id LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $nav = $result->fetch_assoc();
    $next = $nav["id"];
}

// generate image
require_once('data/php/single2image.php');
single2image($id, stripslashes($single["text"]));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?php echo $title; ?></title>
	<meta name="description" content="<?php echo $description; ?>">

	<meta property="og:title" content="<?php echo $title; ?>">
	<meta property="og:description" content="<?php echo $description; ?>">
	<meta property="og:image" content="https://variety42.com<?php echo $config->folder."/data/img/".$id;?>.jpg">
	<meta property="fb:app_id" content="649162062152755"/>

	<link rel="shortcut icon" href="/favicon.png" />
	<link rel="stylesheet" href="<?php echo $config->folder;?>/data/css/main.css" />

	<script type="text/javascript" src="<?php echo $config->folder;?>/data/js/main.js"></script>
</head>

<body>
    <?php require_once("data/php/menu.php"); ?>
    <div class="wrapper single">
        <div class="content">
            <?php echo nl2br(stripslashes($single["text"])); ?>
        </div>
        <div class="nav">
            <?php if ($next) { ?>
                <a href="<?php echo $next;?>" class="right">
                    next
                    <svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path fill="currentColor" d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z"></path></svg>
                </a>
            <?php } ?>
            <?php if ($prev) { ?>
                <a href="<?php echo $prev;?>" class="left">
                    <svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path fill="currentColor" d="M31.7 239l136-136c9.4-9.4 24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 9.4 24.6 0 33.9L127.9 256l96.4 96.4c9.4 9.4 9.4 24.6 0 33.9L201.7 409c-9.4 9.4-24.6 9.4-33.9 0l-136-136c-9.5-9.4-9.5-24.6-.1-34z"></path></svg>
                    prev
                </a>
            <?php } ?>
            <div class="random">
                <a href="<?php echo $config->folder;?>/r">
                    <svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="currentColor" d="M592 192H473.26c12.69 29.59 7.12 65.2-17 89.32L320 417.58V464c0 26.51 21.49 48 48 48h224c26.51 0 48-21.49 48-48V240c0-26.51-21.49-48-48-48zM480 376c-13.25 0-24-10.75-24-24 0-13.26 10.75-24 24-24s24 10.74 24 24c0 13.25-10.75 24-24 24zm-46.37-186.7L258.7 14.37c-19.16-19.16-50.23-19.16-69.39 0L14.37 189.3c-19.16 19.16-19.16 50.23 0 69.39L189.3 433.63c19.16 19.16 50.23 19.16 69.39 0L433.63 258.7c19.16-19.17 19.16-50.24 0-69.4zM96 248c-13.25 0-24-10.75-24-24 0-13.26 10.75-24 24-24s24 10.74 24 24c0 13.25-10.75 24-24 24zm128 128c-13.25 0-24-10.75-24-24 0-13.26 10.75-24 24-24s24 10.74 24 24c0 13.25-10.75 24-24 24zm0-128c-13.25 0-24-10.75-24-24 0-13.26 10.75-24 24-24s24 10.74 24 24c0 13.25-10.75 24-24 24zm0-128c-13.25 0-24-10.75-24-24 0-13.26 10.75-24 24-24s24 10.74 24 24c0 13.25-10.75 24-24 24zm128 128c-13.25 0-24-10.75-24-24 0-13.26 10.75-24 24-24s24 10.74 24 24c0 13.25-10.75 24-24 24z"></path></svg>
                </a>
            </div>
            <div class="clear"></div>
        </div>
        <div class="meta">
            <div class="share">
                <svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M503.691 189.836L327.687 37.851C312.281 24.546 288 35.347 288 56.015v80.053C127.371 137.907 0 170.1 0 322.326c0 61.441 39.581 122.309 83.333 154.132 13.653 9.931 33.111-2.533 28.077-18.631C66.066 312.814 132.917 274.316 288 272.085V360c0 20.7 24.3 31.453 39.687 18.164l176.004-152c11.071-9.562 11.086-26.753 0-36.328z"></path></svg>
                <span>SHARE</span>
            </div>
            <div class="dislike">
                <svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M466.27 225.31c4.674-22.647.864-44.538-8.99-62.99 2.958-23.868-4.021-48.565-17.34-66.99C438.986 39.423 404.117 0 327 0c-7 0-15 .01-22.22.01C201.195.01 168.997 40 128 40h-10.845c-5.64-4.975-13.042-8-21.155-8H32C14.327 32 0 46.327 0 64v240c0 17.673 14.327 32 32 32h64c11.842 0 22.175-6.438 27.708-16h7.052c19.146 16.953 46.013 60.653 68.76 83.4 13.667 13.667 10.153 108.6 71.76 108.6 57.58 0 95.27-31.936 95.27-104.73 0-18.41-3.93-33.73-8.85-46.54h36.48c48.602 0 85.82-41.565 85.82-85.58 0-19.15-4.96-34.99-13.73-49.84zM64 296c-13.255 0-24-10.745-24-24s10.745-24 24-24 24 10.745 24 24-10.745 24-24 24zm330.18 16.73H290.19c0 37.82 28.36 55.37 28.36 94.54 0 23.75 0 56.73-47.27 56.73-18.91-18.91-9.46-66.18-37.82-94.54C206.9 342.89 167.28 272 138.92 272H128V85.83c53.611 0 100.001-37.82 171.64-37.82h37.82c35.512 0 60.82 17.12 53.12 65.9 15.2 8.16 26.5 36.44 13.94 57.57 21.581 20.384 18.699 51.065 5.21 65.62 9.45 0 22.36 18.91 22.27 37.81-.09 18.91-16.71 37.82-37.82 37.82z"></path></svg>
                <span><?php echo $single["dislikes"]; ?></span>
            </div>
            <div class="like">
                <svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M466.27 286.69C475.04 271.84 480 256 480 236.85c0-44.015-37.218-85.58-85.82-85.58H357.7c4.92-12.81 8.85-28.13 8.85-46.54C366.55 31.936 328.86 0 271.28 0c-61.607 0-58.093 94.933-71.76 108.6-22.747 22.747-49.615 66.447-68.76 83.4H32c-17.673 0-32 14.327-32 32v240c0 17.673 14.327 32 32 32h64c14.893 0 27.408-10.174 30.978-23.95 44.509 1.001 75.06 39.94 177.802 39.94 7.22 0 15.22.01 22.22.01 77.117 0 111.986-39.423 112.94-95.33 13.319-18.425 20.299-43.122 17.34-66.99 9.854-18.452 13.664-40.343 8.99-62.99zm-61.75 53.83c12.56 21.13 1.26 49.41-13.94 57.57 7.7 48.78-17.608 65.9-53.12 65.9h-37.82c-71.639 0-118.029-37.82-171.64-37.82V240h10.92c28.36 0 67.98-70.89 94.54-97.46 28.36-28.36 18.91-75.63 37.82-94.54 47.27 0 47.27 32.98 47.27 56.73 0 39.17-28.36 56.72-28.36 94.54h103.99c21.11 0 37.73 18.91 37.82 37.82.09 18.9-12.82 37.81-22.27 37.81 13.489 14.555 16.371 45.236-5.21 65.62zM88 432c0 13.255-10.745 24-24 24s-24-10.745-24-24 10.745-24 24-24 24 10.745 24 24z"></path></svg>
                <span><?php echo $single["likes"]; ?></span>
            </div>
            <div class="author">
                by <a href="/u/<?php echo $single["author"]; ?>"><?php echo $single["author"]; ?></a>
            </div>
            <div class="date">
                <?php echo date("d M, Y", $single["date"]); ?>
            </div>
            <div class="tags">
                <?php
                $sql = 'SELECT t.name as name, t.id as id FROM content_tags ct, tags t WHERE ct.tag_id = t.id AND ct.content_id = '.$id;
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // output data of each row
                    while($tag = $result->fetch_assoc()) { ?>
                        <a href="<?php echo $config->folder;?>/t/<?php echo $tag['id'];?>"><?php echo $tag['name']; ?></a>
                    <?php }
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
