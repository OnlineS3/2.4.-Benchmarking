<?php

    // draw upload btn
    include 'php-src/forms/upload_file.php';

    echo "<div class='excel-tbl'>";
    
    
    if ($import_checked == 'checked' && $file) {
        // reads file data
        $csv_file = xls2csv($file, 'files/temp');
        
        $csv = read_csv($csv_file);
        
        if($csv_file) {unlink($csv_file);}
        
        $src = 1;
        $rows =  $csv['rows'];
        $cols =  $csv['cols'];
        
        // draws excel table
        if ($rows > 0 && $cols > 0 && $import_checked == 'checked') {
            echo "<hr class='hr-primary'>";
           // echo "<p id='error' class='error'></p>";
            
            echo "<p id='tbl-error' class='tbl-error'>These fields are required.</p>";
            
            echo "<div class='toolbar-primary tbl-toolbar '>";
            
            $in_tbl = $csv['csv_array'];
            $ref = ($new_data) ? '' : $ref;
            $src = ($new_data) ? 1 : 0;
            
            $tbl_width = 144 * $cols;
            $tbl_height = 32 * $rows;
             
            echo "<button onclick='return clearTable({ tbl_id: \"user-tbl\" });'>"
            . "<span>Clear Table &nbsp;</span>"
            . "<i class='fa fa-eraser' title='clear table' aria-hidden='true'></i>"
            . "</button>";
            
            echo "<button title='full screen' onclick='return openTablePopup({ "
                    . "tbl_width: $tbl_width, "
                    . "tbl_height: $tbl_height, "
                    . "url: \"edit_tbl\", "
                    . "name:\"popup\", rows: $rows+1, cols: $cols+1 })'>";
            echo "<span>Edit Table &nbsp;</span> ";
            echo "<i class='icon-primary fa fa-pencil-square-o' aria-hidden='true'></i>";
            echo "</button>";
            
            echo "</div>";
            
            draw_user_input($in_tbl,$rows,$cols,$src,$ref,null,$new_data,'import');
        }
    }
    
    // saves excel table in database
    if ($_SERVER["REQUEST_METHOD"] == "POST" && $import_checked == 'checked' ) {
        if (isset($_POST['save-tbl'])) {
            $excel_tbl = array();
            for ($x = 0; $x <= $rows; $x++) {
                for ($y = 0; $y <= $cols; $y++) {
                    $cell_ind = $x . ':' . $y;
                    $excel_tbl[$x][$y] = $_POST[$cell_ind];
                }
            }
            $ref = get_ref();
            $tbl_data = get_table_data($rows, $cols, '', $ref, $rg_group);

            if(isset($_POST['choose-section'])) {
                $inc_des = $inc_quart = $inc_kernel = 0;
                foreach ($_POST['choose-section'] as $option) {
                    switch($option) {
                        case 'descr':
                            $inc_des = 1;
                            break;
                        case 'quart':
                            $inc_quart = 1;
                            break;
                        case 'kernel':
                            $inc_kernel = 1;
                            break;
                    }
                }
                exec_upd($conn,"UPDATE tmp_bench SET inc_des=$inc_des, inc_quart=$inc_quart, inc_kernel=$inc_kernel");
            }
        }
    }

    echo "</div>";

?>