
<?php

$group_report=json_encode('');
if($group_str) {
    $group_str = '';
    foreach ($res['group'] as $val) {
        $group_str .= $val . ', ';
    }
    $group_report = explode(', ', $group_str);
    $group_report = json_encode($group_report);
}

$xls_name = str_replace('"', "", $ref) . '_input_table.xlsx';
$sheet_name = $ref;

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $tbl_data = json_encode('');
    if (isset($_POST['export_input']) || isset($_POST['run'])) {
        $tbl_data = get_table_data($rows, $cols)['tbl'];
        $tbl_data = del_array_col($tbl_data,0);
        $tbl_data = json_encode($tbl_data);

        if(isset($_POST['export_input'])) {
            ?>
            <script type='text/javascript'>
                export2xls({ data: <?php echo $tbl_data ?> });
            </script>
            <?php
        }
    }
    
    if (isset($_POST['export_report']) || isset($_POST['run'])) {
        $group_report = get_table_data($rows, $cols)['group'];
        $group_report = json_encode($group_report);

        if(isset($_POST['export_report'])) {
            ?>
            <script type='text/javascript'>
                openReportPopup({url: "report", name:"popup", rows:<?php echo $rows+1 ?>, cols:<?php echo $cols+1 ?>, group:<?php echo $group_report ?>, ref_col:<?php echo $ref ?>, after_run: false});
            </script>
            <?php
        }
    }
}

$hide_btn = ($_SERVER["REQUEST_METHOD"] == "POST") ? '' : 'hide';

echo "<aside id='sidebar' class='tool-sidebar $tool_hide'>
    <button class='alt export_table' onclick='export_input({ data: $tbl_data });'>Export Table<i class='fa fa-file-excel-o' aria-hidden='true'></i>
    </button>
    ";

?>      

<button class='primary-btn-alt export_report' style='border: solid 1px red;background: linear-gradient(#ff6b6b,#ec2929) !important;' onclick='export_report({url: "report",
        name:"popup", rows:<?php echo $rows+1 ?>, cols:<?php echo $cols+1 ?>, group:<?php echo $group_report ?>, ref_col:<?php echo $ref ?>})'>
    Export Report <i class='fa fa-file-word-o' aria-hidden='true'></i>
</button>
            
<button class="case_study" onclick='return openPopup({ url: "pdf/Benchmarking_case_study.pdf", width:"large", height:"large" });'> Case Study Example <i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>


<a href="files/test/test_data.xlsx" target="ref">
    <button class="primary-btn" onclick='' >
        Test Data<i class="fa fa-download" aria-hidden="true"></i>
    </button>
</a>

</aside>
