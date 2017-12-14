<?php

// updates user table
function upd_user_tbl($conn, $tbl_data, $ref, $tmp=0) {
    // temp table (run click) or bench table (save clicked)
    $tbl_name = ($tmp==1) ? 'tmp_bench' : 'bench';
    $tbl_id = 0;
    
    $conn->query("CREATE TEMPORARY TABLE tmp_bench (ben_id bigint(20) NOT NULL AUTO_INCREMENT,comments text,inc_des smallint(6) DEFAULT NULL,inc_quart smallint(6) DEFAULT NULL,inc_kernel smallint(6) DEFAULT NULL,creation_dt timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,update_dt timestamp NULL DEFAULT '0000-00-00 00:00:00',export_dt timestamp NULL DEFAULT NULL, PRIMARY KEY (ben_id))");
    if ($conn->error) { echo $conn->error; return false; }
    
    $conn->query("CREATE TEMPORARY TABLE tmp_col_head (col_id bigint(20) NOT NULL AUTO_INCREMENT, ben_id bigint(20) NOT NULL, col_code varchar(255) NOT NULL, PRIMARY KEY (col_id))");
    if ($conn->error) { echo $conn->error; return false; } 
    
    $conn->query("CREATE TEMPORARY TABLE tmp_row_head (row_id bigint(20) NOT NULL AUTO_INCREMENT, ben_id bigint(20) DEFAULT NULL, row_code varchar(255) NOT NULL, is_ref smallint(6) DEFAULT NULL, PRIMARY KEY (row_id))");
    if ($conn->error) { echo $conn->error; return false; } 
    
    $conn->query("CREATE TEMPORARY TABLE tmp_cell_data (col_id bigint(20) NOT NULL, row_id bigint(20) NOT NULL, val text, type varchar(20) DEFAULT NULL, PRIMARY KEY (col_id,row_id))");
    if ($conn->error) { echo $conn->error; return false; } 
    
    $tbl_id = exec_ins($conn, "INSERT INTO tmp_bench VALUES ()");
        
    if(!$tbl_id > 0) {return null;}
    
    // insert header
    $x = 0;
    foreach ($tbl_data as $row) {
        if ($x > 0) {
            break;
        }
        $y = 0;
        foreach ($row as $val) {
            $col_id = exec_ins($conn, "INSERT INTO tmp_col_head (ben_id, col_code) VALUES ($tbl_id, '$val')");
            if(!$col_id > 0) {continue;}
            $header[$y++] = $col_id;
        }
        $x++;
    }
        
    // insert rows
    $x=0;
    foreach ($tbl_data as $row) {
        if($x==0) {
            $x++;
            continue;
        }
        $row_code = $row[1];
        // if region equals reference region then ref_col => 1
        $ref_col = ($ref==$row_code) ? 1 : 0;

        $row_id = exec_ins($conn, "INSERT INTO tmp_row_head (ben_id, row_code, is_ref) VALUES ($tbl_id, '$row_code', $ref_col)");

        if (!$row_id > 0) {continue;}
        // insert cell value
        $y = 0;
        foreach ($row as $cell) {
            // reg_id and ref_col ara stored in row
            if ($y < 2) {
                $y++;
                continue;
            }

            exec_ins($conn, "INSERT INTO tmp_cell_data (col_id, row_id, val) VALUES ($header[$y], $row_id, '$cell')");
            $y++;
        }
        $x++;
    }
    return $tbl_id;
}
