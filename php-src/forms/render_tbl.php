<?php

// draws table with user data
function draw_user_input($in_tbl, $rows, $cols, $src, $ref, $group=null, $new_data=1, $type='import', $show='show',$by_report = false,$export=false) {
     
    $group_post = '';
    if (isset($_POST['in_group'])) {
        foreach ($_POST['in_group'] as $option) {
            $group_post .= $option . ","; 
        }
        if (strlen($group_post) > 0) {
            $group_post = substr($group_post, 0, strlen($group_post)-1);
        }
    } else {
        $group_post = $_POST['group'];
    }
    
    if (isset($_POST['run'])) {
        if ($_POST['checked_all']) {
            foreach ($_POST['checked_all'] as $option) {
                $checked_all = ($option==0) ? 'checked' : '';
            }
        } else {
            $checked_all = '';
        }
    } else {
        $checked_all = ($_POST['checked_post']) ? $_POST['checked_post'] : 'checked';
    }
    
    $group_post = str_replace("'", '', $group_post);
    $group_post = ($new_data==1) ? null : $group_post;
    
    echo "<input type='text' name='checked_post' class='hide' value='$checked_all' />";
    $ref_region = ($_POST['ref_region']) ? $_POST['ref_region'] : $ref;
    if(!$export) {
        echo "<input name='ref_region' id='ref_region' class='hide ref_region' value='$ref_region' required/>";
    }
    echo "<table class='tbl-dt tbl-secondary user-tbl $show' id='user-tbl'>";
    echo "<thead>";
    for ($col = 0; $col <= $cols; $col++) {
        $text_align = ($col==1) ? 'text-left' : 'text-right';
        $cell_indx = '0:' . $col;
        
        if($col==0){
            //$value = ($col==0) ? 'In group' : 'Region';
             echo "<th class='small center'>";
            if ($by_report) {
                echo $value;
            } else {
                echo "<div class='chk-primary'><input name='checked_all[]' id='check-all' type='checkbox' value='0' $checked_all/>";
                echo "<label for='check-all' class='check'></label></div>";
            }
            
            echo "</th>";
        } else if ($col==1) {
            echo "<th class='large center'>";
            echo "<input name='$cell_indx' id='$cell_indx' value='Region' type='text' class='tbl_cols' readonly/>";
            echo "</th>";
        }
        
        else {
            switch($src) {
                case 0:
                    $value = $_POST[$cell_indx];
                    break;
                case 1:
                    $value = $in_tbl[0][$col-1];
                    break;
                case 2:
                    $value = $in_tbl[0][$col-1];
                    break;
                case 4:
                    $value = $in_tbl[0][$col];
                    break;
                case 5:
                    $value = $in_tbl[0][$col];
                    break;
                default:
                    $value = '';
            }
            $value = (is_float($value)) ? number_format($value,2,".","") : $value;
            echo "<th class='medium center'>";
            if ($by_report) {
                echo $value;
            } else {
                echo "<input class='tbl_cols' id='$cell_indx' name='$cell_indx' value='$value' type='text' readonly required/>";
            }
            echo "</th>";
        }
    }
    echo "</thead>";

    // draws body data
    echo "<tbody>";
    for ($row = 1; $row <= $rows; $row++) {
        $even = ($row%2==0) ? 'odd' : 'even';

        echo "<tr class='$even' role='row'>";
        for ($col = 0; $col <= $cols; $col++) { // <= one extra column for the ref
            $cell_indx = $row . ':' . $col;

            $text_align = ($col==1) ? 'text-left' : 'text-right';
            if($col==0) {
                switch($src) {
                    case 0:
                        $val = $_POST[$row.':1'];
                        break;
                    case 1:
                        $val = $in_tbl[$row][0];
                        break;
                    case 2:
                        $val = $in_tbl[$row][0];
                        break;
                    case 4:
                        $val = $in_tbl[$row][0];
                        break;
                    case 5:
                        $val = $in_tbl[$row][1];
                        break;
                    default:
                        $val = 0;
                }
                
                $checked = ($group_post && $val) ? ((contains($group_post, $val)) ? 'checked' : '') : 'checked';
                
                // first col
                echo "<td class='small center'>";
                echo "<div class='chk-primary'>";
                
                $name_indx = 'in_group[]';
                
                $val = ($val) ? $val : $row;
                if ($by_report) {
                    echo ($checked) ? 'yes' : 'no';
                } else {
                    echo "<input type='checkbox' name='$name_indx' id='$cell_indx' class='$text_align in_group' value='$val' $checked/>";
                }
                echo "<label for='$cell_indx' class='check'></label>";
                
                echo "</div>";
                echo "</td>";

            } else {
                // value depends on src type - src = 0 when post / 1 when ready table / 2 when db / 3 when generate / 4 when edit / 5 when report
                switch($src) {
                    case 0:
                        $value = $_POST[$cell_indx];
                        break;
                    case 1:
                        $value = $in_tbl[$row][$col-1];
                        break;
                    case 2:
                        $value = $in_tbl[$row][$col-1];
                        break;
                    case 4:
                        $value = $in_tbl[$row][$col];
                        break;
                    case 5:
                        $value = $in_tbl[$row][$col];
                        break;
                    default:
                        $value = '';
                }
                if ($col==1) {
                    echo "<td class='large center col-base'>";
//                    echo "<input id='$cell_indx' name='$cell_indx' value='$value' type='text' class='hide' $disabled />";
                   
                    if ($by_report) {
                        echo $value;
                        if(!$export) {
                            echo "<input name='$cell_indx' id='$cell_indx' class='$text_align ref_region hide' value='$value' type='text' $disabled required readonly/>";
                        }
                    } else {
                        echo "<input name='$cell_indx' id='$cell_indx' class='$text_align ref_region' value='$value' type='text' $disabled required readonly/>";
                    }
                    $ref_indx = 'ref_col_' . $cell_indx;
                    $ref_val = $_POST[$ref_indx];
                    if(!$export) {
                        echo "<input id='$ref_indx' name='$ref_indx' value='$ref_val' class='ref_col hide' type='text' readonly/>";
                    }
                    
                    if (!$by_report){
                    ?>
                    
                    <button class="set-ref btn-primary-alt button" onclick="return set_ref_region('<?php echo $value; ?>', this);"> Set Reference </button>
                    
                    <?php
                    }
                    echo "</td>";
                }
                else {
                    $value = (is_float($value)) ? number_format($value,2,".","") : $value;
                    $first_value = ($col==2) ? 'first_value' : '';
                    echo "<td class='medium center $first_value'>";
                    if ($by_report) {
                        echo $value;
                    } else {
                        echo "<input id='$cell_indx' name='$cell_indx' class='$text_align is_numeric' value='$value' type='text' $disabled readonly/>";
                    }
                    echo "</td>";
                }
            }
        }
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";

    if ($new_data==1) {   
        echo "<script language='javascript' type='text/javascript'>";
        echo "reset_tbl();";
        echo "</script>";
    }
    if(!$export) {
        echo "<input type='text' name='group' id='group' class='hide' value=$group_post>";
    }
    
    
//    return $group;
}

// draws descriptive statistics tables by table
function draw_stats_by_tbl($cols, $in_tbl, $tbl_type, $by_report=false) {
    
    $small_tips['Q1'] = "Lorem Ipsum is simply dummy text of the printing.";
    $small_tips['Q2'] = "Lorem Ipsum is simply dummy text of the printing.";
    $small_tips['Q3'] = "Lorem Ipsum is simply dummy text of the printing.";
    $small_tips['Q4'] = "Lorem Ipsum is simply dummy text of the printing.";
    $small_tips['Q4/Q1'] = "Lorem Ipsum is simply dummy text of the printing.";
    $small_tips['Q4/Q2'] = "Lorem Ipsum is simply dummy text of the printing.";
    $small_tips['Q2/Q1'] = "Lorem Ipsum is simply dummy text of the printing.";

    if (!$in_tbl) {return null;}
    
    $des = array('max'=>'max value','min'=>'min value','avg'=>'average','dif_max'=>'difference from max','dif_min'=>'difference from min');

    echo "<table class='tbl-dt tbl-primary'>";

    // header cols
    if ($cols ){
        $j=0;
        echo "<thead>";
        echo "<th class='medium center'>";
        if ($by_report) {
            echo 'Function';
        } else {
            echo "<input name='$tbl_type:0:$j' id='$tbl_type:0:$j' class='hide' value='Function'/>";
        }
        echo "</th>";
        $j++;
        foreach($cols as $val) {
            echo "<th class='medium center'>";
            
            if ($by_report) {
                echo $val;
            } else {
                echo "<input value='$val' name='$tbl_type:0:$j' id='$tbl_type:0:$j' disabled>";
            }
            echo "</th>";
            $j++;
        }
        echo "</thead>";
    }

    $i=1;
    echo "<tbody>";
    foreach($in_tbl as $x => $row) {
        $cls = ($x=='Q4') ? 'sep' : '';
        echo "<tr class='$cls'>";
        $even = ($i%2==0) ? 'odd' : 'even';
        // left col
        echo "<td class='medium center'>";
        $cap = ($tbl_type=='des') ? 'cap' : '';
        $val = ($tbl_type=='des') ? ($des[$x]) : $x;
        $hide = ($x=='Q1' || $x=='Q2' || $x=='Q3' || $x=='Q4' || $x=='Q4/Q1' || $x=='Q4/Q2' || $x=='Q2/Q1') ? '' : 'hide';
        $secondary = ($x=='Q4/Q1' || $x=='Q4/Q2' || $x=='Q2/Q1') ? 'secondary' : '';
        $tip = $small_tips[$x];
        
        if ($by_report) {
            echo $val;
        } else {
            echo "<input class='$cap' name='$tbl_type:$i:0' id='$tbl_type:$i:0' value='$val' disabled>";
            echo "<div class='tooltip-container $hide'>";
            echo "<img src='images/info.png' class='col-info $secondary' width='11' height='11'>";
            echo "<span class='tooltiptext arrow-left near tip-body'>$tip</span>";
            echo "</div>";
        }
        echo "</td>";
        $j=1;
        foreach($row as $cell) {
            $cell = (is_null($cell)) ? 0 : $cell;
            // if float -> right format
            $cell = (is_decimal ($cell)) ? number_format($cell,2,".","") : $cell;
            //$cell = (is_float($cell)) ? 'test' : $cell;
            echo "<td class='medium center'>";
            if ($by_report) {
                echo $cell;
            } else {
                echo "<input name='$tbl_type:$i:$j' id='$tbl_type:$i:$j' value='$cell' disabled>";
            }
            echo "</td>";
            $j++;
        }
        echo "</tr>";
        $i++;
    }
    echo "</tbody>";
    echo "</table>";
    return array('row_no'=>$i, 'col_no'=>$j);
}

// draws descriptive statistics tables by post
function draw_stats_by_post($row_no, $col_no, $tbl_type) {
    echo "<table class='tbl-data style_six show'>";
    // header
    echo "<thead>";
    echo "<tr class='row tbl-body'>";
    echo "<th class='cell small center bold plain header col'>";
    echo "<span>&nbsp;</span>";
    echo "</th>";
    for ($j=0;$j<$col_no-1;$j++) {
        $val = $_POST[$tbl_type.':'.'0:'.$j];
        echo "<th class='cell medium center bold plain header'>";
        echo "<input name='$tbl_type:0:$j' id='$tbl_type:0:$j' value='$val'>";
        echo "</th>";
    }
    echo "</tr>";
    echo "</thead>";

    // data
    echo "<tbody>";
    for ($i=1;$i<$row_no;$i++) {
        echo "<tr class='row tbl-body'>";
        $even = ($i%2==0) ? 'odd' : 'even';

        // left col
        $cell_indx = $tbl_type.':'.$i . ':0';
        echo "<td class='cell small center plain col'>";
        $val = $_POST[$cell_indx];
        echo "<input class='cap' name='$cell_indx' id='$cell_indx' value='$val'>";
        echo "</td>";

        for ($j=1;$j<$col_no;$j++) {
            $cell_indx = $tbl_type. ':' . $i . ':' . $j;
            $val = $_POST[$cell_indx];
            $val = (is_null($val)) ? 0 : $val;
            $val = (is_float($val)) ? number_format($val,2,".","") : $val;

            echo "<td class='cell medium right $even'>";
            echo "<input name='$cell_indx' id='$cell_indx' value='$val'>";
            echo "</td>";
        }
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
}

function draw_colored_tbl($in_tbl, $group, $quart) {
   
    if (!$in_tbl) {return null;}
    // draw header
    echo "<table class='tbl-dt tbl-colored' id='colored-table'>
            <thead>
            <tr>
            <th class='large center row_head'><span>&nbsp;</span></th>";

    $j=0;
    $cols = array_slice($in_tbl,0,1);
    foreach($cols as $row) {
        $row = array_slice($row,2);
        foreach($row as $cell) {
            echo "<th class='medium center'>
                    <input value='$cell' name='color_tbl:0:$j' id='color_tbl:0:$j' disabled>
                    </th>";
            $j++;
        }
    }
    echo "</tr>";
    echo "</thead>";
    
    // draw tbl body
    $i=1;
    $tbl_data = array_slice($in_tbl,1);
    foreach($tbl_data as $x => $row) {
        // check if row belongs to group
        if (!in_array($row[1],$group)) {continue;}
        echo "<tr>";

        // first col
        echo "<td class='medium large row_head' style=''>";
        echo "<input name='color_tbl:$i:0' id='color_tbl:$i:0' value='$row[1]' disabled>";
        echo "</td>";
        $j=1;

        foreach($row as $cell) {
            if($j<3){$j++;continue;}
            $cell = (is_null($cell)) ? 0 : $cell;
//            $cell = (is_float($cell)) ? number_format($cell,2,".","") : $cell; // if float -> right format

            $cell = (is_decimal ($cell)) ? number_format($cell,2,".","") : $cell;
            
            $var = $cols[0][$j-1];
            if($cell<=$quart['Q1'][$var]) {
                $color = 'color-1';
            } elseif ($cell>$quart['Q1'][$var] && $cell<=$quart['Q2'][$var]) {
                $color = 'color-2';
            } elseif ($cell>$quart['Q2'][$var] && $cell<=$quart['Q3'][$var]) {
                $color = 'color-3';
            } elseif ($cell>$quart['Q3'][$var]) {
                $color = 'color-4';
            }

            echo "<td class='center medium' >";
            echo "<input name='color_tbl:$i:$j' id='color_tbl:$i:$j' value='$cell' class='$color' disabled>";
            echo "</td>";
            $j++;
        }
        echo "<tr>";
        $i++;
    }

    echo "</table>";
}

/*
 * create word html
 */
function create_wd_str($tbl_data) {
    $wd_str = '';
    $wd_str .= "<table>";
    foreach($tbl_data as $row) {
        $wd_str .= "<tr>";
        foreach($row as $cell) {
            $wd_str .= "<td>$cell</td>";
        }
        $wd_str .= "</tr>";
    }
    $wd_str .= "</table>";
    
    return $wd_str;
}

?>