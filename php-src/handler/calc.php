<?php
/**
 * Math calculations
 */

// computes min / max / avg / dif
function get_des_stats($conn, $col_ref, $group, $cols) {
    
    $i=0;
    $max = $min = $avg = $dif_max = $dif_min = array();
    
    foreach($cols as $col) {
        // creates an array with vals per col
        $vals = array();
        
        foreach($group as $reg) {
            $sql = "SELECT coalesce(val,0) as val FROM tmp_cell_data cell
                    JOIN tmp_row_head row ON cell.row_id = row.row_id
                    JOIN tmp_col_head col ON col.col_id = cell.col_id and col.col_code = '$col'
                    WHERE row.row_code = '$reg' LIMIT 1";
            $res = exec_select($conn,$sql);
            array_push($vals, $res[0]['val']);
        }
        
        // gets values for col ref
        $sql = "SELECT coalesce(val,0) as val FROM tmp_cell_data cell
                JOIN tmp_row_head row ON cell.row_id = row.row_id
                JOIN tmp_col_head col ON col.col_id = cell.col_id and col.col_code = '$col'
                WHERE row.row_code = '$col_ref'";

        $ref_val = exec_select($conn,$sql);

        // gets max / min / avg / dif from max / dif from min
        $max[$col] = (!max($vals)) ? 0 : max($vals);
        $min[$col] = (!min($vals)) ? 0 : min($vals);
        $avg[$col] = array_sum($vals)/count($vals);
        $dif_max[$col] = $max[$col] - $ref_val[0]['val'];
        $dif_min[$col] = $ref_val[0]['val'] - $min[$col];
       
        $i++;
    }

    return array('max'=>$max,'min'=>$min,'avg'=>$avg,'dif_max'=>$dif_max,'dif_min'=>$dif_min);
}

function get_quart_stats($conn, $col_ref, $group, $cols) {

    $i=0;
    $vals = $quart = array();
    foreach($cols as $col) {
        // creates an array with vals per col
        $vals =  array();
        foreach ($group as $reg) {
            $sql = "SELECT coalesce(val,0) as val FROM tmp_cell_data cell
                    JOIN tmp_row_head row ON cell.row_id = row.row_id
                    JOIN tmp_col_head col ON col.col_id = cell.col_id and col.col_code = '$col'
                    WHERE row.row_code = '$reg'";
            $res = exec_select($conn, $sql);
            array_push($vals, $res[0]['val']);
        }

        // compute quartiles
        $quart[$col] = get_quartiles($vals);
        $i++;
    }

    return array('cols'=>$cols,'quart'=>$quart);
}

// computes quartile values
function get_quartiles($arr) {

    sort($arr);
    $q1_val = $q2_val = $q3_val = $q4_val = $q4_1_val = $q2_1_val = $q4_2_val = 0;

    $q2_val = get_median($arr);
    $low_half = array_slice($arr,0,$q2_val['indx']);
    $high_half = array_slice($arr,$q2_val['indx']+1);
    $q1_val = get_median($low_half);
    $q3_val = get_median($high_half);
    $q4_val = $arr[count($arr)-1];
    $q4_val = (!$q4_val) ? 0 : $q4_val;
    if ($q1_val['val'] != 0) {
        $q4_1_val = ($q1_val==0) ? 0 : $q4_val / $q1_val['val'];
        $q2_1_val = ($q1_val==0) ? 0 : $q2_val['val'] / $q1_val['val'];
    }
    if ($q2_val['val'] != 0){
        $q4_2_val = ($q2_val==0) ? 0 : $q4_val / $q2_val['val'];
    }

    return array('Q1'=>$q1_val['val'],'Q2'=>$q2_val['val'],'Q3'=>$q3_val['val'],'Q4'=>$q4_val,'Q4/Q1'=>$q4_1_val,'Q4/Q2'=>$q4_2_val, 'Q2/Q1'=>$q2_1_val);
}

// computes median value
function get_median($arr) {
    sort($arr);
    if(count($arr) % 2 == 1){
        $indx = floor(count($arr)/2);
        $median = $arr[$indx];
    } else {
        $indx = count($arr)/2;
        $indx_2 = count($arr)/2 - 1;
        $median = ($arr[$indx] + $arr[$indx_2]) / 2;
    }
    
    $median = (!$median) ? 0 : $median;
    return array('indx'=>$indx,'val'=>$median);
}

?>