<?php
/**
 * Created by PhpStorm.
 * User: elvira
 * Date: 28/11/2016
 * Time: 17:31
 */

include 'php-src/onlineS3/db_connector/connection.php';
$conn = conn_db();
include 'php-src/onlineS3/db_connector/exec_sql.php';
include 'php-src/onlineS3/import/files_fun.php';
include 'php-src/onlineS3/gen_fun/str_fun.php';
include 'php-src/onlineS3/gen_fun/array_fun.php';
include 'php-src/handler/get_stats.php';
include 'php-src/handler/main_func.php';
include 'php-src/forms/render_tbl.php';

//phpinfo();

echo "<form action ='' class='validate-form' id='popup-form' method='get'>";

if (isset($_POST['tbl_data'])){
    $popup_data = $_POST['tbl_data'];   // get json data 
    $popup_data = stripslashes($popup_data);    // remove slashes
    $popup_data = json_decode($popup_data, true); // json to array
    
    $group = $_POST['group'];   // get json data 
    
    $group = stripslashes($group);    // remove slashes
    $group = json_decode($group, false); // json to array
    $group = implode(",",$group);
    
    $ref_data = json_decode(stripslashes($_POST['ref_col']), false);
    
    $ref = $ref_data[0];
    
    $rows = count($popup_data);
    $cols = get_col_no($popup_data);
    
    echo "<p class='hide' id='popup-rows'>$rows</p>";
    echo "<p class='hide' id='popup-cols'>$cols</p>";
    
    echo "<div class='popup'>";
    
    echo "<p class='popup-txt'>You can edit the values of your table : </p>";

    $new_data=null;
    draw_user_input($popup_data, $rows-1, $cols-1, 4, $ref, $group, $new_data);
    
    // draw save & discard buttons
    echo "<div class='popup-btn'>";
    echo '<button class="button cancel-btn" onclick="return self.close()">Discard</button>';
    //echo '<button class="button accept-btn" type="submit" onclick="savePopupTbl({rows: '.$rows . ', cols: '.$cols.'})" type="submit">Accept</button>';
    echo '<button class="button accept-btn" type="submit">Accept</button>';
    echo "</div>";

    echo "</div>";
}

echo "</form>";