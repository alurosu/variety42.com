<?php
$sql = 'SELECT c.id as id, c.text as text FROM content c, content_tags ct WHERE c.id = ct.content_id AND ct.tag_id != 2 ORDER BY rand() DESC LIMIT 5';
//echo $sql;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    $i=1;
    while($similar = $result->fetch_assoc()) { ?>
        <a href="<?php echo $config->single;?>/<?php echo $similar['id'];?>">
            <?php echo excerpt(stripslashes($similar['text']));?>
            <div>
                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="external-link-alt" class="svg-inline--fa fa-external-link-alt fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M432,320H400a16,16,0,0,0-16,16V448H64V128H208a16,16,0,0,0,16-16V80a16,16,0,0,0-16-16H48A48,48,0,0,0,0,112V464a48,48,0,0,0,48,48H400a48,48,0,0,0,48-48V336A16,16,0,0,0,432,320ZM488,0h-128c-21.37,0-32.05,25.91-17,41l35.73,35.73L135,320.37a24,24,0,0,0,0,34L157.67,377a24,24,0,0,0,34,0L435.28,133.32,471,169c15,15,41,4.5,41-17V24A24,24,0,0,0,488,0Z"></path></svg>
            </div>
        </a>
    <?php
        if ($i!=$result->num_rows) {
            echo '<div class="h_separator"></div>';
        }
        $i++;
    }
}
function excerpt($text) {
    if (strlen($text) < 60) {
         return $text;
    } else {
       $new = wordwrap($text, 58);
       $new = explode("\n", $new);
       $new = $new[0] . '...';
       return $new;
    }
}
?>
