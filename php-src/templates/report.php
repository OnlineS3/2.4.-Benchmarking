<?php

include 'php-src/onlineS3/db_connector/connection.php';
$conn = conn_db();
include 'php-src/onlineS3/db_connector/exec_sql.php';
include 'php-src/onlineS3/import/files_fun.php';
include 'php-src/onlineS3/gen_fun/str_fun.php';
include 'php-src/onlineS3/gen_fun/array_fun.php';
include 'php-src/onlineS3/gen_fun/form_fun.php';
include 'php-src/onlineS3/gen_fun/math_fun.php';
include 'php-src/handler/get_stats.php';
include 'php-src/handler/main_func.php';
include 'php-src/forms/render_tbl.php';
include 'php-src/db_func/upd_tbl.php';

define('UPLOAD_DIR', 'files/tmp_img/');

?>
<div class='popup-btn'>
    <button id="word-test" class="button btn-primary-alt right">Export Report</button>
    <button id="export-pdf" class="button btn-disabled right" disabled="">Export Pdf</button>
</div>

<?php
if (isset($_POST['tbl_data'])){
    $popup_data = $_POST['tbl_data'];   // get json data 
    $popup_data = stripslashes($popup_data);    // remove slashes
    $popup_data = json_decode($popup_data, true); // json to array
    
    $ref_col = json_decode(stripslashes($_POST['ref_col']), true);
    
    $group = json_decode(stripslashes($_POST['group']), true);
    $section_data = json_decode(stripslashes($_POST['section_data']), true);
    $wd_chart = json_decode(stripslashes($_POST['wd_chart']), true);
    $wd_table = json_decode(stripslashes($_POST['wd_table']), true);
    $kernel_prop = json_decode(stripslashes($_POST['kernel_prop']), true);
    
    
    $files_array = array();
    
    $rows_no = count($popup_data);
    $cols_no = get_col_no($popup_data);
    
    echo "<div class='print' id='report'>";
    
    // calculates statistics
    $res = calc_results($conn, $popup_data, $ref_col, $group);
    $cols = $res['cols'];
    $stats = $res['stats'];
    $quart = $res['quart'];
    
    //echo "<div class='bench report'>";
    
    // draws user comment input
    echo "<fieldset class='main-section accordion-primary'>";
    echo "<input id='item-1' class='toggle-item' checked='' type='checkbox'>";
    echo "<label for='item-1' class='toggle-label'>Description</label>";
    echo "<div class='section-content'>";
    echo "<textarea name='descr' id='input-descr' rows='8' cols='74' class='descr'></textarea>";
    echo "</div>";
    echo "</fieldset>";
    
    $wd_str = 'Description:';
    
    // draws original user input
    echo "<fieldset class='main-section accordion-primary one'>";
    echo "<input id='item-2' class='toggle-item' type='checkbox' checked>";
    echo "<label for='item-2' class='toggle-label'>Data Input</label >";
    echo "<div class='section-content user-input'>";

    //draw_user_input($in_data,count($in_data)-1,get_col_no($in_data)-1,5,$ref,$rg_group,$new_data);
    $new_data=null;
    echo "<div class='ben-filters'><span style='font-weight:bold'>Reference Region:</span> $ref_col";
    echo "<div>";
    echo "<span style='font-weight:bold'>Group:&nbsp;</span>";
    $group_str='';
    foreach ($group as $val) {
        $group_str .= $val . ', ';
    }
    echo "<span class='group-data'> " . substr($group_str, 0, strlen($group_str)-2) . "</span>";
    echo "</div>";
    echo "</div>";
    draw_user_input($popup_data, $rows_no-1, $cols_no-1, 4, $ref_col, $popup_data, $new_data, null, null, $by_report=true);

    echo "</div>";
    echo "</fieldset>";
    
    $wd_str .= '<br>Data Input:';
    $wd_str .= create_wd_str($popup_data);
    
    // draws descriptive statistics
    if ($section_data[0][1] == 1) {
        echo "<fieldset class='main-section accordion-primary two'>";
        echo "<input id='item-3' class='toggle-item' type='checkbox' checked>";
        echo "<label for='item-3' class='toggle-label'>Descriptive Statistics</label>";
        echo "<div class='section-content'>";

        echo "<div class='ben-filters'><span style='font-weight:bold'>Reference Region:</span> $ref_col";
        echo "<div>";
        echo "<span style='font-weight:bold'>Group:&nbsp;</span>";
        $group_str='';
        foreach ($group as $val) {
            $group_str .= $val . ', ';
        }
        echo "<span class='group-data'> " . substr($group_str, 0, strlen($group_str)-2) . "</span>";
        echo "</div>";
        echo "</div>";

        draw_stats_by_tbl($cols, $stats, 'des', $by_report=true);

        echo "</div>";
        echo "</fieldset>";
    }

    $wd_str .= '<br>Descriptive Statistics:';
    $wd_str .= create_wd_str($stats);
    
    // draws quartiles
    if ($section_data[1][1] == 1) {
        echo "<fieldset class='main-section accordion-primary three'>";
        echo "<input id='item-4' class='toggle-item' type='checkbox' checked>";
        echo "<label for='item-4' class='toggle-label'>Quartiles</label>";
        echo "<div class='section-content quart'>";

        echo "<div class='ben-filters'><span style='font-weight:bold'>Reference Region:</span> $ref_col";
        echo "<div>";
        echo "<span style='font-weight:bold'>Group:&nbsp;</span>";
        $group_str='';
        foreach ($group as $val) {
            $group_str .= $val . ', ';
        }
        echo "<span class='group-data'> " . substr($group_str, 0, strlen($group_str)-2) . "</span>";
        echo "</div>";
        echo "</div>";

        draw_stats_by_tbl($cols, $quart, 'quart', $by_report=true);
        
        $wd_str .= '<br>Quartiles:';

        echo "<hr class='hr-primary'>";

        echo "<div id='export-tbl'>";
        
        
        $quart_name = microtime(true).'_'.rand(0,10).'_quart.png';
        $quart_file = UPLOAD_DIR . $quart_name;
        $img = str_replace('data:image/png;base64,', '', $wd_table);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $success = file_put_contents($quart_file, $data);
        if ($success) {
            array_push($files_array,$quart_name);
            $quart_src = 'files/tmp_img/' . $quart_name;
            echo "<img src='$quart_src' alt='image' width='790'>";
        }
        echo "</div>";
        

        echo "</div>";
        echo "</fieldset>";
    }

    // draws kernel diagram
    if ($section_data[2][1] == 1) {
        echo "<fieldset class='main-section accordion-primary four'>";
        echo "<input id='item-5' class='toggle-item' type='checkbox' checked>";
        echo "<label for='item-5' class='toggle-label'>Kernel Diagram:</label>";
        echo "<div class='section-content' id='kernel-img'>";
        echo "<div class='ben-filters'><span style='font-weight:bold'>Chosen Year:</span> $kernel_prop";
        
        $kernel_name = microtime(true).'_'.rand(0,10).'_kernel.png';
        $kernel_file = UPLOAD_DIR . $kernel_name;
        
        $img = str_replace('data:image/png;base64,', '', $wd_chart);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $success = file_put_contents($kernel_file, $data);
        if ($success) {
            array_push($files_array,$kernel_name);
            $kernel_src = 'files/tmp_img/' . $kernel_name;
            echo "<img src='$kernel_src' alt='image' width='700'>";
        }
        
        echo "</fieldset>";
    }
    echo "</div>";
    
    echo "</div>";
    
    echo "<div id='export-report' class='hide'>"; 
    echo "<h2>Benchmarking - <span>report</span></h2>";
    echo "<h3>Description</h3>";
    echo "<p id='report-descr'></p>";
    echo "<h3>Data Input</h3>";
    echo "<span style='font-weight:bold'>Reference Region:</span> $ref_col";
    echo "<br><span style='font-weight:bold'>Group:&nbsp;</span>";
    $group_str = '';
    foreach ($group as $val) {
        $group_str .= $val . ', ';
    }
    echo substr($group_str, 0, strlen($group_str)-2);
    draw_user_input($popup_data, $rows_no-1, $cols_no-1, 4, $ref, $popup_data, $new_data, null, null, $by_report=true, $export=true);
    echo "<h3>Descriptive Statistics</h3>";
    $group_str='';
    echo "<span style='font-weight:bold'>Reference Region:</span> $ref_col";
    echo "<br><span style='font-weight:bold'>Group:&nbsp;</span>";
    foreach ($group as $val) {
        $group_str .= $val . ', ';
    }
    echo substr($group_str, 0, strlen($group_str)-2);
    
    echo "<br>";
    draw_stats_by_tbl($cols, $stats, 'des', $by_report=true);
    echo "<br>";
    echo "<h3>Quartiles</h3>";
    $group_str='';
    echo "<span style='font-weight:bold'>Reference Region:</span> $ref_col";
    echo "<br><span style='font-weight:bold'>Group:&nbsp;</span>";
    foreach ($group as $val) {
        $group_str .= $val . ', ';
    }
    echo substr($group_str, 0, strlen($group_str)-2);
    echo "<br>";
    draw_stats_by_tbl($cols, $quart, 'quart', $by_report=true);
    echo "<br>";
    echo "<h3>Quartile Table</h3>";
    echo "<img src='$quart_src' alt='image' width='700'>";
    
    echo "<h3>Kernel Diagram</h3>";
    echo "<span style='font-weight:bold'>Chosen Year:</span> $kernel_prop";
    if ($success) {
        echo "<img src='$kernel_src' alt='image' width='700'>";
    }
    echo "</div>";
    
    $files_array = json_encode($files_array);
    
    ?>
    
    <script type="text/javascript">
        localStorage.setItem('files_array',null);
        localStorage.setItem('files_array',<?php echo $files_array ?>);
    </script>
    <?php
}
?>