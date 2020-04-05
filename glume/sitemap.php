<?php
require_once('config.php');

$conn = new mysqli($config->mysql->host, $config->mysql->user, $config->mysql->pass, $config->mysql->name);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$time = date("Y-m-d");

$sitemap = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
$sitemap .= '<url><loc>https://variety42.com'.$config->folder.'</loc><lastmod>'.$time.'</lastmod></url>';

// get all tag links with entries over 10
$sql = 'SELECT t.id as id FROM content c, tags t, content_tags ct WHERE c.id = ct.content_id AND t.id = ct.tag_id GROUP BY t.id HAVING count(c.id) > 10';
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $sitemap .= '<url><loc>https://variety42.com'.$config->folder.'/t/'.$row['id'].'</loc><lastmod>'.$time.'</lastmod></url>';
    }
}

// get all search links with entries over 10
$sql = 'SELECT query FROM search WHERE count > 10';
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $sitemap .= '<url><loc>https://variety42.com'.$config->folder.'/s/'.urlencode($row['query']).'</loc><lastmod>'.$time.'</lastmod></url>';
    }
}

// get all search links with entries over 10
$sql = 'SELECT id, date FROM content ORDER BY date DESC';
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $sitemap .= '<url><loc>https://variety42.com'.$config->single.'/'.$row['id'].'</loc><lastmod>'.date("Y-m-d", $row['date']).'</lastmod></url>';
    }
}
$sitemap .= '</urlset>';
$conn->close();
file_put_contents("/var/www/html/variety42.com/www".$config->folder."/sitemap.xml", $sitemap);
echo "Done!";
?>
 - <a href="sitemap.xml" target="_blank">sitemap.xml</a>
