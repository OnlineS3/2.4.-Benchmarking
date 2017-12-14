<?php
    echo "<label class='select-source'>Select source of data:</label>";
    
    // draws import type radio buttons
    echo "<div class='radio-primary check-import'>";
    
    echo "<div>";
    $import_checked = (isset($_POST['tbl-type'])) ? (($_POST['tbl-type']=='excel') ? "checked" : "") : "checked";
    echo "<input type='radio' id='import' value='excel' name='tbl-type' $import_checked/>";
    echo "<label for='import'>Import XLS File</label>";
    echo "</div>";
    
    echo "<div>";
    $fill_checked = ( $_POST['tbl-type']=='fill') ? "checked" : "";
    echo "<input type='radio' id='fill' value='fill' name='tbl-type' $fill_checked/>";
    echo "<label for='fill'>Create Table</label>";
    echo "</div>";
    
    echo "</div>";

    echo "<div class='user-main'>";
    // user input table with row data
    include 'php-src/forms/create_tbl.php';

    // user input table from excel file
    include 'php-src/forms/import_xls.php';
    
    
    
//    if ($_SERVER["REQUEST_METHOD"] == "POST") {
//        $show = 'show';
//    }

    echo "<input type='text' value='' id='fs_tbl' class='hide'/>";

    // load section choices
    //$sections = '';
    if (isset($_POST['run'])) {
        $sections = '';
        if (isset($_POST['choose-section'])) {
            foreach ($_POST['choose-section'] as $option) {
                $sections .=  $option . ",";
            }
        } 
    } else {
        $sections = ($_POST['sections']) ? $_POST['sections'] : $sections;
    }
    
    $sections = normalize_input($sections);
    

    // draws section checkboxes
    echo "<div class='chk-primary choose-section $show'>";
    
    
    $choices = array('descr'=>'Descriptive Statistics','quart'=>'Quartiles','kernel'=>'Kernel Graph');
    
    $i=0;
    foreach($choices as $item => $item_value) {
        $checked = (!$new_data) ? ((isset($_POST['choose-section']) && contains($sections,$item)) ? 'checked' : '') : 'checked';
        echo "<input type='checkbox' name='choose-section[]' id='sec-$item' value='$item' $checked/>";
        echo "<label for='sec-$item'>$item_value</label>";
        $i++;
    }
    echo "<input type='text' name='sections' value='$sections' class='msg-area hide'/>";
    echo "<input type='submit' id='export-input' name='export_input' class='hide'>";
    echo "<input type='submit' id='export-report' name='export_report' class='hide'>";
    
    // run button
    echo "<input type='submit' id='run' class='button bench-btn right run-btn $show' style='bottom:0' value='Run' name='run'> <i class='fa fa-refresh' aria-hidden='true'></i>";

    echo "</div>";
    
    echo "</div>";

?>

