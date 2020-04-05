<?php
$folder = $_GET['folder'];
if (!file_exists("../".$folder."/config.php"))
    $folder = "jokes";

$id = 1;
if (is_numeric($_GET['id']) && $_GET['id'] > 0)
    $id = $_GET['id'];
require_once("../".$folder."/config.php");

$conn = new mysqli($config->mysql->host, $config->mysql->user, $config->mysql->pass, $config->mysql->name);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$message = '';
if(isset($_POST['edit'])){
    if(strlen($_POST['text'])>0){
        $text = $conn->real_escape_string($_POST['text']);
        $sql ="UPDATE content SET date = '".time()."',text = '$text' WHERE id = '$id'";
        $conn->query($sql);
        regenImage($id, $_POST['text'], $folder);

        $sql_insert_tags = "INSERT INTO content_tags (content_id, tag_id) VALUES";
        //add tags that don't exist
        $tag_names = $_POST['tag_names'];
        $tag_ids = $_POST['tag_ids'];
        for($i=0;$i<count($tag_names);$i++){
            if(!$tag_ids[$i]){
                $sql = "INSERT INTO tags (name) VALUES ('".$tag_names[$i]."')";
                $conn->query($sql);
                $tag_ids[$i] = $conn->insert_id;
            }
            $sql_insert_tags.=" ('$id','".$tag_ids[$i]."')";
            if($i!=(count($tag_names)-1))
                $sql_insert_tags.=",";
        }

        //delete all content tags
        $sql = "DELETE FROM content_tags WHERE content_id = $id";
        $conn->query($sql);

        //add tags to content
        $conn->query($sql_insert_tags);

        $message = 'Done! <a href="'.$config->single.'/'.$id.'"> Click Here </a>';
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Edit <?php echo $folder." - id: ".$id;?></title>

	<meta property="og:title" content="Edit <?php echo $folder." - id: ".$id;?>">
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
        <h3 style="margin-bottom: 0;">
            Edit <?php echo $folder." - id: ".$id;?>
            <div class="edit_notification"> <?php echo $message; ?></div>
        </h3>
        <div class="delete_form_trigger">Delete</div>
        <form action="/u/delete.php" method="post" class="delete_form">
            <label>Are you sure?</label>
            <input type="hidden" name="id" value="<?php echo $id;?>">
            <input type="hidden" name="folder" value="<?php echo $folder;?>">
            <input type="submit" name="delete" value="Yes">
        </form>
        <?php
        $sql = "SELECT text FROM content WHERE id = ".$id;
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $single = $result->fetch_assoc();
            ?>
            <form action="" method="post">
                <textarea name="text"><?php echo stripslashes($single['text']);?></textarea>
                <ul class="tags">
                    <?php
                    $sql = 'SELECT t.name as name, t.id as id FROM content_tags ct, tags t WHERE ct.tag_id = t.id AND ct.content_id = '.$id;
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // output data of each row
                        while($tag = $result->fetch_assoc()) { ?>
                            <li>
                                <?php echo $tag['name']; ?>
                                <span>x</span>
                                <input type="hidden" name="tag_names[]" value="<?php echo $tag['name'];?>">
                                <input type="hidden" name="tag_ids[]" value="<?php echo $tag['id'];?>">
                            </li>
                        <?php }
                    }
                    ?>
                </ul>
                <input type="text" class="suggested_tag" data-folder="<?php echo $folder; ?>" placeholder="Add tag" value="">
                <ul class="autocomplete"></ul>
                <input type="submit" name="edit" value="Edit">
            </form>
        <?php } else echo "There are no ".$folder." with id: ".$id; ?>
    </div>

    <?php require_once('../data/php/footer.php'); ?>
</body>
</html>
<?php $conn->close();

function regenImage($id, $text, $folder) {
    //$img = imagecreatefromjpeg('balloon.jpg');
    $img_h = 630;
    $img_w = 1200;
    $img = imagecreate($img_w, $img_h);
    $white = imagecolorallocate($img, 255, 255, 255);
    $grey = imagecolorallocate($img, 51, 51, 51);
    $green = imagecolorallocate($img, 0, 128, 0);
    $logo = "variety42.com";
    $font = "../data/font/Arial.ttf";
    $font_size = ($img_h-30) / 7.9;

    $text = wordwrap($text, 26, "\n");
    $lines = explode("\n", $text);
    $text = $lines[0]."\n".$lines[1]."\n".$lines[2]."\n".$lines[3]."\n".$lines[4];

    $offset = 15+$font_size;
    if (count($lines)<5)
        $offset += $font_size * (5-count($lines))/2;
    imagettftext($img, $font_size, 0, 15, $offset, $grey, $font, $text);
    imagettftext($img, 0.5*$font_size, 0, $img_w - 340, ($img_h - 20), $green, $font, $logo);

    // OUTPUT IMAGE
    imagejpeg($img, "../".$folder."/data/img/".$id.".jpg", 100);
}
?>
