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
}
?>
