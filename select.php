<?php
include_once 'db/conn.php';
include_once 'db/dao.php';

//select newest update item group by scanned_txt
$sql = "select *
from (select p.*,
             row_number() over (partition by scanned_txt order by lastop_datetime desc) as seqnum
      from scanned_item p
     ) t
where seqnum = 1
order by lastop_datetime desc";
$result = mysqli_query($connect, $sql);

$output = '  
    <div class="table-boarderless">     
        <table class="table table-boarderless" >
          ';
if (mysqli_num_rows($result) > 0) {
    $col = 0;
    while ($row = mysqli_fetch_array($result)) {

        $count = get_count($row["scanned_txt"]);
        $undecided = $count["undecided"];
        $left = $count["left"];

        //$img_height = fix_image_ratio(100, $row["image_thumb_url"]);
        if ($row["lastop_datetime"] = $row["stock_datetime"])
            $color = "#5cb85c";
        elseif ($row["lastop_datetime"] = $row["trash_datetime"])
            $color = "#c9302c";
        elseif ($row["lastop_datetime"] = $row["scanned_datetime"])
            $color = "#286090";

        if (($col % 2) == 0)
            $output .= ' <tr>';
        $output .= '
                    <td>
                        <a href="#" class="notification">
                            <img src=' . $row["image_thumb_url"] . ' class="img-fluid img-thumbnail"></img>';
        if ($undecided)
            $output .=   '<span class="badge_right"> ';
        else
            $output .=   '<span class="badge_right invisible"> ';
        $output .= $undecided . '</span>
                            <span class="badge_left">' . $left . '</span>
                        
                        <div class="scanned_txt" data-id1="' . $row["id"] . '">' . $row["scanned_txt"] . '</div>
                        <div class="product_name" data-id2="' . $row["id"] . '" contenteditable>' . $row["product_name"] . '</div>
                        <div class="brand_name" data-id3="' . $row["id"] . '" contenteditable>' . $row["brand_name"] . '</div>  
                  ';
        //stock btn
        if ($undecided > 0)
            $output .= '<span class="btn_stock_left"><button type="button" name="stock_btn" data-id9="' . $row["scanned_txt"] . '" class="btn btn-m btn-success btn_stock" ><span class="fa fa-shopping-basket" /></button></span>';
        if ($undecided > 0 and $left)
            $output .= '<span class="btn_stock_right"><button type="button" name="delete_btn" data-id6="' . $row["scanned_txt"] . '" class="btn btn-m btn-warning btn_delete" ><span class="glyphicon glyphicon-trash" /></button></span>';

        $output .= ' </a></td>';

        if (($col % 2) == 1)
            $output .= ' </tr>';
        $col += 1;
    }
} else {
    $output .= '<tr>  
                    <td>Data not Found</td>  
                </tr>';
}
$output .= '  
      </div>';
echo $output;
