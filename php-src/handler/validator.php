<?php

function validate_table($tbl,$ref) {
    $tbl_data = $tbl['tbl'];
    
    if (strlen($ref) == 0) {
        return array('res'=>false,'msg'=>"You have to select a reference region.");
    }
//    foreach($tbl_data as $row) {
//        if (!$row[1]) {
//            return array('res'=>false,'msg'=>"You have to enter all regions.");
//        }
//    }
//
//    $i=0;
//    foreach($tbl_data as $row) {
//        if ($i <> 0) {break;}
//        $j=0;
//        foreach($row as $cell) {
//            if ($j == 0) {$j++;continue;}
//            if (!$cell) {
//                return array('res'=>false,'msg'=>"You have to enter all variables.");
//                $j++;
//            }
//        }
//        $i++;
//    }

    return array('res'=>true);
}

?>