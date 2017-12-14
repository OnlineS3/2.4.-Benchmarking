<?php
/**
 * Created by PhpStorm.
 * User: elvira
 * Date: 12/10/2016
 * Time: 16:56
 */

function round_array($array) {
    $out = array();
    $x=0;
    foreach ($array as $row) {
        $y=0;
        foreach ($row as $val) {
            $out[$x][$y++]=(is_numeric($val))? round($val,2) : $val;
        }
        $x++;
    }
    return $out;
}

function loop_array($array) {
    foreach ($array as $val) {
        echo " " . $val . " - ";
    }
}

function loop2darray($array2D) {
    foreach ($array2D as $row) {
        foreach ($row as $val) {
            echo " " . $val . "<br />";
        }
    }
}

function get_col_no($array2D) {
    $i = 0;
    $col_no = array();
    foreach ($array2D as $row) {
        foreach ($row as $val) {
            $col_no[$i++] = count($row);
        }
    }
    $max_col = max($col_no);
    return $max_col;
}

function flip_row_col_array($array) {
    $out = array();
    foreach ($array as $rowkey => $row) {
        foreach($row as $colkey => $col){
            $out[$colkey][$rowkey]=$col;
        }
    }
    return $out;
}

// converts json to 2d associative array
function obj2assocArray($json_in) {
    $array_out = array();
    $array_tmp = (array)$json_in;
    foreach ($array_tmp as $attr_indx => $attr) {
        $attr_arr = array();
        $attr_tmp = (array)$attr;
        foreach ($attr_tmp as $val_indx => $value) {
            $attr_arr[$val_indx] = $value;
        }
        $array_out[$attr_indx] = $attr_arr;
    }

    return $array_out;
}

// converts assoc to 2d array
function assoc22D($assoc) {
    $array_out = array();
    $x=0;
    
    foreach ($assoc as $rowkey => $row) {
        
        $y=0;
        $array_out[$x][$y++] = ($rowkey) ? $rowkey : '';
        foreach($row as $col){
            $array_out[$x][$y++]=$col;
        }
        $x++;
    }
    
    return $array_out;
}

// removes col from 2d array
function del_array_col($arr,$col) {
    $out=array();
    foreach($arr as $row) {
        unset($row[$col]);
        array_push($out,$row);
    }
    return $out;
}

?>