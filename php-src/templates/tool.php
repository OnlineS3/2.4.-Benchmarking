
<?php
    // import php files
    
    include 'php-src/onlineS3/import/files_fun.php';
    include 'php-src/onlineS3/gen_fun/str_fun.php';
    include 'php-src/onlineS3/gen_fun/array_fun.php';
    include 'php-src/onlineS3/gen_fun/form_fun.php';
    include 'php-src/onlineS3/gen_fun/math_fun.php';
    
    include 'php-src/forms/render_tbl.php';
    include 'php-src/handler/main_func.php';
    include 'php-src/handler/get_stats.php';
    include 'php-src/handler/validator.php';
    include 'php-src/onlineS3/draw/draw_form_elements.php';
    
    $tips[0] = "Lorem Ipsum is simply dummy text of the printing and typesetting "
            . "industry. Lorem Ipsum has been the industry's standard dummy text "
            . "ever since the 1500s, when an unknown printer took a galley of type "
            . "and scrambled it to make a type specimen book. It has survived not "
            . "only five centuries, but also the leap into electronic typesetting, "
            . "remaining essentially unchanged.";

    $tips[1] = "It is a long established fact that a reader will be distracted "
            . "by the readable content of a page when looking at its layout. The "
            . "point of using Lorem Ipsum is that it has a more-or-less normal "
            . "distribution of letters, as opposed to using 'Content here, content "
            . "here', making it look like readable English.";

    $tips[2] = "Contrary to popular belief, Lorem Ipsum is not simply random text. "
            . "It has roots in a piece of classical Latin literature from 45 BC, "
            . "making it over 2000 years old. Richard McClintock, a Latin professor "
            . "at Hampden-Sydney College in Virginia, looked up one of the more "
            . "obscure Latin words, consectetur, from a Lorem Ipsum passage, and "
            . "going through the cites of the word in classical literature, "
            . "discovered the undoubtable source.";

    $tips[3] = "There are many variations of passages of Lorem Ipsum available, "
            . "but the majority have suffered alteration in some form, by injected "
            . "humour, or randomised words which don't look even slightly believable. "
            . "If you are going to use a passage of Lorem Ipsum, you need to be "
            . "sure there isn't anything embarrassing hidden in the middle of "
            . "text.";
    
    $sections = "descr,quart,kernel,";

    try {
        
        echo "<div class='bench'>";

        // set title
        echo "<h2>Benchmarking</h2>";
        echo "<form action ='$home' class='validate-form' id='user-form' method='post' enctype='multipart/form-data'>";

        $form_changed = $_POST['form_changed'];

        echo "<input id='show-tip' class='hide' name='show_tip' value=''/>";

        $show_tip = (isset($_POST['show_tip'])) ? $_POST['show_tip'] : '';

        echo "<div class='tip-box app-alert $show_tip hide'><p>Upload your data in an <strong>.xlsx</strong> format "
            . "(you can use the <a href='http://assetsmapping.s3platform.eu/' target='_blank'>Regional Assets Mapping</a> application to create your xls file). "
            . "Alternatively create a new table and complete the data that are going to be included in the table.</p>"
            . "<i class='fa fa-times' aria-hidden='true'></i></div>";

        echo "<input type='text' id='form-changed' value='$form_changed' name='form_changed' class='hide'/>";

        // home url
        $home = 'http://' . $_SERVER['SERVER_NAME'] . '/home';
        $in_data = $ref = $group = $kernel_array = $input_rows = $input_cols = $show = $validate = $err_msg = $checked = '';

        // get reference region
        $ref = get_ref();

        // before user input
        $show = ($new_data) ? '' :((isset($_POST['run']) || isset($_POST['upload'])) ? 'show' : $_POST['show']);
        //$show = 'show';
        $short = ($_SERVER["REQUEST_METHOD"] == "POST") ? '' : 'short';
        echo "<div class='main-section' style='margin-bottom:1rem !important; border: none'>";

        echo "<fieldset class='main-section one accordion-primarya'>";
        echo "<input id='item-1' class='toggle-item $short' type='checkbox' checked>";
        echo "<div class='tooltip-container'>";
        echo "<i class='fa fa-info-circle section-info' aria-hidden='true'></i>";
        echo "<span class='tooltiptext arrow-right near tip-body'>$tips[0]</span>";
        echo "</div>";
        echo "<label for='item-1' class='toggle-label'>Upload data</label>";
        echo "<div class='user-input one section-content accordion-section'>";

        include 'php-src/forms/input_data.php';

        if ($fill_checked == 'checked') {
            $input_rows = $_POST["rows"];
            $input_cols = $_POST["cols"]+1;
        } else {
            $input_rows = $csv['rows'];
            $input_cols = $csv['cols'];
        }

        echo "</div>";
        
        echo "</fieldset>";

        // after user input
        //$show = ($new_data) ? '' :((isset($_POST['run'])) ? 'show' : $_POST['show']);
        $show = ($new_data) ? '' :((isset($_POST['run'])) ? 'show' : '');
        
        echo "<input name='show' type='text' value='$show' class='hide'/>";

        $group = $_POST['in_group'];

        $tbl_data = get_table_data($input_rows, $input_cols, '', $ref, $group);

//        $chk_res = validate_table($tbl_data,$ref);

        $clean_data = remove_str($tbl_data['tbl']);

//        if ($chk_res['res']) {
//            $validate = true; 
//        } else {
//            $validate = false; 
//            $err_msg = $chk_res['msg'];
//
//            echo "<script language='javascript' type='text/javascript'>";
//            echo "set_error('$err_msg');";
//            echo "</script>";
//        }
        

        if ($show) {
            // calculates statistics
            $ref = str_replace('"', "", $ref);
            if (isset($_POST['run'])) {
                $res = calc_results($conn, $clean_data, $ref, $group);
                $group_regions = $res['group'];
            }

            if ($res['group']) {
                foreach ($res['group'] as $val) {
                    $group_str .= $val . ', ';
                }
                $group_arr = explode(', ', $group_str);
                array_unshift($group_arr, "Group");
                $group_arr = json_encode($group_arr);
            }

            $ref_arr = json_encode(["Reference Region",$ref]);
            
            $checked = 'checked';
        }
        
        $checked = ($show && contains($sections, 'descr')) ? 'checked' : '';
        $short = ($show && contains($sections, 'descr')) ? '' : 'short';
            
            // descriptive statistics
            echo "<fieldset class='main-section two accordion-primary des'>";
            echo "<input id='item-2' class='toggle-item $short' type='checkbox' $checked>";
            echo "<div class='tooltip-container'>";
            echo "<i class='fa fa-info-circle section-info' aria-hidden='true'></i>";
            echo "<span class='tooltiptext arrow-right near tip-body'>$tips[1]</span>";
            echo "</div>";
            echo "<label for='item-2' class='toggle-label'>Descriptive Statistics</label>";
            echo "<div class='section-content accordion-section'>";

            if ($show && contains($sections, 'descr')) {
                echo "<div class='ben-filters'><span style='font-weight:bold'>Reference Region:</span> <span class='ref-reg'>$ref</span>";
                echo "<div>";
                echo "<span class='group-label'>Group:&nbsp;</span>";
                echo "<span class='group-data'> " . substr($group_str, 0, strlen($group_str)-2) . "</span>";
                echo "</div>";
                echo "</div>";

                // draw calculated table after run
                // calc them regardless run clicked - because of the other form
                if (isset($_POST['run'])) {
                    $tbl_dim = draw_stats_by_tbl($res['cols'], $res['stats'], 'des', $show); 
                } else {
                    $tbl_dim = draw_stats_by_post($input_rows, $input_cols, 'des'); 
                }

                $des_row_no = $tbl_dim['row_no'];
                $des_col_no = $tbl_dim['col_no'];

                echo "<input type='text' value='$des_row_no' name='des_row_no' class='hide'>";
                echo "<input type='text' value='$des_col_no' name='des_col_no' class='hide'>";

                array_unshift($res['stats'], $res['cols']);
                $des_tbl = round_array(assoc22D($res['stats']));
                $des_tbl = json_encode($des_tbl);
                $csv_name = $ref . '-descriptive.csv';
                $xls_name = $ref . '-descriptive.xlsx';

//                    echo '$des_tbl: ';
//                    print_r($des_tbl);
//                    
//                    echo '$ref_arr: ';
//                    print_r($ref_arr);
//                    
//                    echo '$group_arr: ';
//                    print_r($group_arr);
//                    
//                    echo '$xls_name: ';
//                    print_r($xls_name);

                // button area
                echo "<div class='new-btns'>";
                echo "<button class='button bench-btn export-csv' onclick='return export2csv({ data: $des_tbl, ref_col: \"$ref\", group: \"$group_str\", filename: \"$csv_name\" });'>Export CSV"
                        . "<i class='fa fa-download' aria-hidden='true'></i><img src='images/csv-3.jpg' class='csv-icon' width='15'/></button>";
                echo "<button class='button bench-btn export-csv' onclick='return export2xls({ data: $des_tbl, ref_col: $ref_arr, group: $group_arr, filename: \"$xls_name\" });'>Export XLS"
                        . "<i class='fa fa-download' aria-hidden='true'></i><img src='images/csv-3.jpg' class='csv-icon' width='15'/></button>";
                echo "</div>";
            }
            echo "</fieldset>";
            
            $checked = ($show && contains($sections, 'quart')) ? 'checked' : '';
            $short = ($show && contains($sections, 'quart')) ? '' : 'short';

            // quartiles
            
            echo "<fieldset class='main-section three accordion-primary quart'>";
            echo "<input id='item-3' class='toggle-item $short' type='checkbox' $checked>";
            echo "<div class='tooltip-container'>";
            echo "<i class='fa fa-info-circle section-info' aria-hidden='true'></i>";
            echo "<span class='tooltiptext arrow-right near tip-body'>$tips[2]</span>";
            echo "</div>";
            echo "<label for='item-3' class='toggle-label'>Quartiles</label>";
            echo "<div class='section-content accordion-section'>";

            if ($show && contains($sections, 'quart')) {
                echo "<div class='ben-filters'><span style='font-weight:bold'>Reference Region:</span> <span class='ref-reg'>$ref</span>";
                echo "<div>";
                echo "<span class='group-label'>Group:&nbsp;</span>";
                echo "<span class='group-data'> " . substr($group_str, 0, strlen($group_str)-2) . "</span>";
                echo "</div>";
                echo "</div>";

                // draw calculated table after run
                //if (isset($_POST['run'])) {
                $tbl_dim = draw_stats_by_tbl($res['cols'], $res['quart'], 'quart');

                $qu_row_no = $tbl_dim['row_no'];
                $qu_col_no = $tbl_dim['col_no'];

                echo "<input type='text' value='$qu_row_no' name='qu_row_no' class='hide'>";
                echo "<input type='text' value='$qu_col_no' name='qu_col_no' class='hide'>";

                // button area
                echo "<div class='new-btns'>";

                array_unshift($res['quart'], $res['cols']);
                $quart_tbl = round_array(assoc22D($res['quart']));
                $quart_tbl = json_encode($quart_tbl);
                $csv_name = $ref . '-quartiles' . '.csv';
                $xls_name = $ref . '-quartiles' . '.xlsx';

                echo "<button class='button bench-btn export-csv' onclick='return export2csv({ data: $quart_tbl, ref_col: \"$ref\", group: \"$group_str\", filename: \"$csv_name\" });'>Export CSV "
                        . "<i class='fa fa-download' aria-hidden='true'></i><img src='images/csv-3.jpg' class='csv-icon' width='15'/></button>";
                echo "<button class='button bench-btn export-csv' onclick='return export2xls({ data: $quart_tbl, ref_col: $ref_arr, group: $group_arr, filename: \"$xls_name\" });'>Export XLS"
                        . "<i class='fa fa-download' aria-hidden='true'></i><img src='images/csv-3.jpg' class='csv-icon' width='15'/></button>";

                echo "</div>";

                echo "<hr class='hr-primary'>";

                echo "<div class='radio-primary choose-quart'>";
                echo "<div><input id='color-tbl' value='color-tbl' name='color-tbl' type='radio' checked=''>";
                echo "<label for='color-tbl'>Table</label></div>";
                echo "<div><input id='box-plot' value='box-plot' name='box-plot' type='radio' disabled>";
                echo "<label for='box-plot' >Box Plot</label>";
                echo "</div>";
                echo "</div>";

                echo "<div id='export-tbl'>";

                echo "<div class='tbl-annex'>";
                echo "<span>Q1</span><div class='q1'></div>";
                echo "<span>Q2</span><div class='q2'></div>";
                echo "<span>Q3</span><div class='q3'></div>";
                echo "<span>Q4</span><div class='q4'></div>";
                echo "</div>";

                // $colored_tbl = (isset($_POST['run'])) ? $res['in_data'] : create_indx_array($input_rows,$input_cols,'color_tbl');

                // draw colored region table based on quartiles
                echo "<div class='quart-color'>";
                draw_colored_tbl($res['in_data'], $res['group'], $res['quart']);
                echo "</div>";

                echo "<input id='wd_table' class='hide'/>";

                echo "</div>";

                $table_name = $ref . '-quartiles.png';

                // button area
                echo "<div class='new-btns'>";
                echo "<button class='button bench-btn-alt' onclick='return exportElem2png({ tbl_id: \"export-tbl\", filename: \"$table_name\" });'>Export as Image  <i class='fa fa-picture-o' aria-hidden='true'></i></button>";
                echo "</div>";
                echo "</div>";
            }

            echo "</fieldset>";

            $checked = ($show && contains($sections, 'kernel')) ? 'checked' : '';
            $short = ($show && contains($sections, 'kernel')) ? '' : 'short';
            $kernel_array = array();

            // kernel diagram
            
                echo "<fieldset class='main-section accordion-primary four kernel'>";
                echo "<input id='item-4' class='toggle-item $short' type='checkbox' $checked>";
                echo "<div class='tooltip-container'>";
                echo "<i class='fa fa-info-circle section-info' aria-hidden='true'></i>";
                echo "<span class='tooltiptext arrow-right near tip-body'>$tips[3]</span>";
                echo "</div>";
                echo "<label for='item-4' class='toggle-label'>Kernel Diagram</label>";
                echo "<div class='section-content accordion-section'>";

                if ($show && contains($sections, 'kernel')) {
                    // inserts kernel diagram
                    $kernel_data = create_kernel_array($input_rows+1, $input_cols, $res['group']);
                    $kernel_data = remove_str($kernel_data);

                    $max = max($kernel_data);
                    $min = min($kernel_data);
                    $kernel_array = json_encode($kernel_data);
                    $ref_indx = get_ref_indx($res['in_data'], $res['group'],$ref);

                    if($res['cols']) {
                        $default_val = $res['cols'][0];

                        ?>
                            <script type='text/javascript'>
                                showKernelByVar({prop:<?php echo $default_val ?> , ref_indx: <?php echo $ref_indx ?>, kernel_array: <?php echo $kernel_array ?>, container:"kernel", export_id:"export-svg"  });
                            </script>
                        <?php

                        echo "<div class='select-var'>";
                        echo "<span class='kernel-choose'>Choose year:</span>";
                        echo "<select class='chosen-select' data-placeholder='Choose year ' id='kernel_prop' onchange='"
                                        . "return showKernelByVar({prop:this.value, ref_indx: $ref_indx, kernel_array:$kernel_array, container:\"kernel\", export_id:\"export-svg\"  });'>";
                        foreach ($res['cols'] as $val) {
                                echo "<option value='$val'>$val</option>";
                        }
                        echo "</select>";
                        echo "</div>";
                    }

                    echo "<div id='kernel'></div>";

                    echo "<input id='wd_chart' class='hide'/>";

                    echo "<img id='kernel-img'/>";

                    $img_name = $ref . '-kernel.png';

                    // button area
                    echo "<div class='new-btns hide'>";            
                    echo "<button class='button bench-btn right' onclick='return exportSvg2PNG({ container: \"export-svg\", filename: \"$img_name\" });'>Export as Image <i class='fa fa-picture-o' aria-hidden='true'></i></button>";
                    echo "</div>";
                }

                echo "</fieldset>";
            
            echo "</div>";

            echo "</div>";
            
        if ($show) {
            $in_data = array();
            $in_data = $res['in_data'];
            $ref = get_ref();
            $sections = $_POST['sections'];
            $kernel_var = $_POST['select-var'];
        }
            
        //}

        echo "<input type='submit' id='export_input' name='export_input' class='hide' value=''/>";

        echo "</form>";

        echo "</div>";

        // convert variables to json
        $in_data = json_encode($in_data);
        $ref = json_encode($ref);
        $sections = json_encode($sections);
        $group = json_encode($group);
        $kernel_array = json_encode($kernel_array);
        $input_rows = json_encode($input_rows+1);
        $input_cols = json_encode($input_cols+1);
        $show = json_encode($show);
        $validate = json_encode($validate);
        $err_msg = json_encode($err_msg);
        $kernel_var = json_encode($err_msg);

        
        //destroy_anonymous_user($conn,$user_id);
        //if($file) {unlink($file);}
        // close the connection
        $conn->close();
    
    //catch exception
    } catch(Exception $e) {
      echo 'Message: ' .$e->getMessage();
    }
?>