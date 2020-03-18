<?php
header('Content-type: text/xml');
require_once('config.php');

$conn = new mysqli($config->mysql->host, $config->mysql->user, $config->mysql->pass, $config->mysql->name);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$time = date("Y-m-d");
?>
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<url>
		<loc>https://variety42.com<?php echo $config->folder;?></loc>
		<lastmod><?php echo $time;?></lastmod>
	</url>

    <?php
    // get all tag links with entries over 10
    $sql = 'SELECT t.id as id FROM content c, tags t, content_tags ct WHERE c.id = ct.content_id AND t.id = ct.tag_id GROUP BY t.id HAVING count(c.id) > 10';
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {?>
            <url>
        		<loc>https://variety42.com<?php echo $config->folder;?>/t/<?php echo $row['id'];?></loc>
        		<lastmod><?php echo $time;?></lastmod>
        	</url>
        <?php }
    }

    // get all search links with entries over 10
    $sql = 'SELECT query FROM search WHERE count > 10';
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {?>
            <url>
        		<loc>https://variety42.com<?php echo $config->folder;?>/s/<?php echo urlencode($row['query']);?></loc>
        		<lastmod><?php echo $time;?></lastmod>
        	</url>
        <?php }
    }

    // get all search links with entries over 10
    $sql = 'SELECT id, date FROM content ORDER BY date DESC';
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {?>
            <url>
                <loc>https://variety42.com<?php echo $config->single;?>/<?php echo $row['id'];?></loc>
                <lastmod><?php echo date("Y-m-d", $row['date']);?></lastmod>
            </url>
        <?php }
    }
    ?>
</urlset>
<?php $conn->close(); ?>
