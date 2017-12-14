<?php

// loads data from database
function load_from_db() {

}

// displays posted data of the whole page
function redirect() {

}

// clears whole page when from start - με js
function clear_page() {

}

function remove_str($in_tbl) {
    $i=0;
    foreach($in_tbl as $key => $row) {
        $j=0;
        if($i==0) {$i++; continue;}
        foreach($row as $cell) {
            if($j<2) {$j++; continue;}
            if (!is_numeric($cell)) {
                if($key) {
                    $in_tbl[$key][$j] = null;
                } else {
                    $in_tbl[$i][$j] = null;
                }
                    
            }
            $j++;
        }
        $i++;
    }
    
    return $in_tbl;
}

// creates an array of user input data
function create_kernel_array($rows, $cols, $group) {
   
    if (!($rows > 0 && $cols > 0)) {
        return null;
    }
    $out = array();
    
    for ($j=0;$j<$cols-1;$j++) {
        $var_inx = '0:' . ($j+2);
        $var = $_POST[$var_inx];
        $z=0;
        for ($i=0;$i<$rows;$i++) {
            $val = $_POST[$i.':1'];
            if (in_array($val,$group)) {
                $cell_index = ($i) . ':' . ($j+2);
                $value = $_POST[$cell_index];
                $out[$var][$z] = $value;
                $z++;
            }
        }
    }

    return $out;
}

function get_ref_indx($in_data,$group, $ref) {
    $i=$j=0;
    foreach($in_data as $row) {
        if (in_array($_POST[$j.':1'],$group)) {
            if ($_POST[$j.':1']==$ref) {
                return $i;
            }
            $i++;
        }
        $j++;
    }
    
}

// creates an array of user input data
function create_indx_array($rows, $cols, $type=null) {

    if (!($rows > 0 && $cols > 0)) {
        return null;
    }
    $out = array();

    for ($i=0;$i<$rows+1;$i++) {
        for ($j=0;$j<$cols;$j++) {
            $cell_index = $type . (($type)?':':''). $i . ':' . $j;
            $value = $_POST[$cell_index];
            $out[$cell_index] = $value;
        }
    }

    return $out;
}

function get_table_data($input_rows, $input_cols, $tbl_type='', $ref='', $group='') {

    $tbl = array();
    $group = array();
    
    // get post table
    for ($x = 0; $x <= $input_rows; $x++) {
        $col=0;
        for ($y = 0; $y <= $input_cols; $y++) {
            $cell_ind = (($tbl_type) ? $tbl_type . ':' : '') . $x . ':' . $y;
            
            $tbl[$x][$col++] = $_POST[$cell_ind];
        }
    }
    
    if ($_POST['in_group']) {
        foreach ($_POST['in_group'] as $option) {
            array_push($group,$option);
        }
    }
    return array('tbl'=>$tbl, 'ref'=>$ref, 'group'=>$group);
}

function get_ref() {
    $ref = $col_ref = '';
    if (isset($_POST['ref_region'])) {
        $ref = $_POST['ref_region'];
        
        //$ref = substr($col_ref, 0, strlen($col_ref) - 2);
    }

    return $ref;
}

function get_ref_byId($ref_array) {
    
    foreach ($ref_array as $cell) {
        if ($cell[1]==1) {return $cell[0];}
    }
    return '';
}

?>