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
?>
