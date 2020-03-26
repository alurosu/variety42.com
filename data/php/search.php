<?php
if (!$empty_search) {
    $nr_per_page = 42;
    $query =  str_replace(' ', '%',strtolower($term));
    $sql = 'SELECT id, text, likes, dislikes FROM content WHERE lower(text) LIKE "%'.$query.'%" ORDER BY date DESC LIMIT '.($page-1)*$nr_per_page.', '.$nr_per_page;
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            displaySingle($row['text'], $row['id'], $row['likes'], $row['dislikes']);
        }

        $sql = "INSERT INTO search (query) VALUES ('".$term."') ON DUPLICATE KEY UPDATE count=count+1";
        $conn->query($sql);

        $sql = 'SELECT count(id) as count FROM content WHERE lower(text) LIKE "%'.$query.'%"';

        $result = $conn->query($sql);
        $total = $result->fetch_assoc();
        echo pagination($total['count'], $config->folder.'/s/'.$term);
    } else {
        echo "0 results";
    }
} else {?>
<div class="more">
    <div class="tags">
        <h3><?php echo $config->seo->searchPage->topTags;?></h3>
        <?php
        $sql = 'SELECT t.id as id, t.name as name, count(c.id) as count FROM content c, tags t, content_tags ct WHERE c.id = ct.content_id AND t.id = ct.tag_id GROUP BY t.id HAVING count(c.id) > 10 ORDER BY count(c.id) DESC LIMIT 15';
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {?>
                <a href="<?php echo $config->folder.'/t/'.urlencode($row['id']);?>"><?php echo $row['name']; ?></a><span>(<?php echo $row['count']; ?>)</span>
                <?php
            }
        }
        ?>
    </div>
    <div class="searches">
        <h3><?php echo $config->seo->searchPage->topSearches;?></h3>
        <?php
        $sql = 'SELECT query, count FROM search ORDER BY count DESC LIMIT 15';
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {?>
                <a href="<?php echo $config->folder.'/s/'.urlencode($row['query']);?>"><?php echo $row['query']; ?></a><span>(<?php echo $row['count']; ?>)</span>
                <?php
            }
        }
        ?>
    </div>
    <div class="all">
        <a href="<?php echo $config->folder; ?>"><?php echo $config->seo->searchPage->buttonAll;?></a>
    </div>
</div>
<?php } ?>
