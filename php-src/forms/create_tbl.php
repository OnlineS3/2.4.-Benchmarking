<?php
    $rows = $rowsErr = $cols = $colsErr = $post_url = '';

	// gets table dimensions
	if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['generate-tbl'])) {
            $new_data = true;

            $rows = $_POST["rows"];
            $cols = $_POST["cols"];

            if (empty($rows)) {
                $rowsErr = "* Rows is required";
            } elseif (!is_numeric($rows)) {
                $rowsErr = "Only numbers allowed"; 
            }

            if (empty($cols)) {
                $colsErr = "* Cols is required";
            } elseif (!is_numeric($cols)) {
                $colsErr = "Only numbers allowed"; 
            }
	}

    $rows = $_POST["rows"];
    $cols = $_POST["cols"];

    // shows table dimension
    
    echo "<div class='tbl-dimensions'>";
    echo "<label>Rows:</label>";
    echo "<input type='text' name='rows' id='input-rows' value='$rows'/>";
    echo "<label>Cols:</label>";
    echo "<input type='text' name='cols' id='input-cols' value='$cols'/>";
    //echo "<span class='error'>$rowsErr</span>";
    //echo "<span class='error'>$colsErr</span>";
    
    echo "<p id='tbl-error' class='tbl-error'>These fields are required.</p>";
    
    echo "<input type='submit' class='btn-primary button cont' value='Generate Table' name='generate-tbl' id='generate-tbl'>";
    echo "</div>";

    echo "<div class='auto-tbl'>";
    if ($_SERVER["REQUEST_METHOD"] == "POST" && $fill_checked == 'checked') {
        $rows = $_POST["rows"];
        $cols = $_POST["cols"]+1;
//        $show = 'show';

        // saves table
        if (isset($_POST['save-tbl'])) {
            $user_tbl = array();
            for ($x = 0; $x <= $rows; $x++) {
                for ($y = 0; $y <= $cols; $y++) {
                    $cell_ind = $x . ':' . $y;
                    $user_tbl[$x][$y] = $_POST[$cell_ind];
                }
            }

            $tbl_data = get_table_data($rows, $cols, '', $ref, $rg_group);
//            $chk_res = validate_table($tbl_data,$ref_arr);
//            if (!$chk_res['res']) {
//                $err_msg = $chk_res['msg'];
//                echo "<p class='error'>$err_msg</p>";}
//            else {
                upd_user_tbl($conn, $excel_tbl, $ref_arr);
            //}

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

            // generates table
            if ($rows > 0 and $cols > 0) {
                echo "<hr class='hr-primary'>";
                
                echo "<div class='toolbar-primary tbl-toolbar'>";

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
                echo "<span>Open Table &nbsp;</span> ";
                echo "<i class='icon-primary fa fa-external-link' aria-hidden='true'></i>";
                echo "</button>";

                echo "</div>";

                $ref = ($new_data) ? '' : $ref;
                if (isset($_POST['generate-tbl'])) {
                    draw_user_input($user_tbl,$rows,$cols,3,$ref,null,$new_data,'auto');
                } else {
                    draw_user_input($user_tbl,$rows,$cols,0,$ref,null,$new_data,'auto');
                }
            }
	}

	echo "</div>";
?>