<?php
/**
 * Created by PhpStorm.
 * User: elvira
 * Date: 17/10/2016
 * Time: 16:03
 */

include 'php-src/handler/calc.php';

function calc_results($conn, $tbl, $ref, $group) {
    upd_user_tbl($conn, $tbl, $ref, 1);

    $sql = "SELECT ben_id FROM tmp_bench";
    $res = exec_select($conn, $sql);
    $tbl_id = $res[0]['ben_id'];
    
    // get data in my format
    $cols_tmp = exec_select($conn,"SELECT col_code FROM tmp_col_head WHERE ben_id = $tbl_id");
    $cols_tmp = array_slice($cols_tmp, 2);
    $cols = array();
    for ($i=0;$i<count($cols_tmp);$i++) {
        $cols[$i] =  $cols_tmp[$i]['col_code'];
    }
    
    // call function for descriptive statistics
    $stats = get_des_stats($conn, $ref, $group, $cols);
    
    // call function for quartiles
    $res = get_quart_stats($conn, $ref, $group, $cols);
    $quart = $res['quart'];
    $quart = flip_row_col_array($quart);
    
    
    
    return array('in_data'=>$tbl, 'stats'=>$stats, 'quart'=>$quart, 'cols'=>$cols, 'ref'=>$ref, 'group'=>$group);
}

?>