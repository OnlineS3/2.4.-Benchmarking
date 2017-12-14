<?php

$target_dir = "files/uploads/";

    if($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST['upload'])) {
        
        $originname = basename($_FILES["fileToUpload"]["name"]);
        $filename = str_replace(".","",str_replace(" ","",microtime())) . '_' . basename($_FILES["fileToUpload"]["name"]);
        $target_file = $target_dir . $filename;
        
        $fileType = pathinfo($target_file, PATHINFO_EXTENSION);
        $uploadOk = 1;

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 50000) {
            $upl_msg = "Your file is too large (maximum permitted 50mb).";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            $tmp_file = $target_dir . $filename;
            if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $tmp_file)) {
                $upl_msg = "There was an error uploading your file.";
            }
        }

        $file = $target_file;
        setcookie('filename', $file);
    }

    //echo 'path file name ' . $_POST['fileToUpload3'];
    if ($_POST['tbl-type']=='excel' && $import_checked == 'checked' && !isset($_POST['export_report'])) {
        $file = ($filename != '') ? $target_dir . $filename : $target_dir . $_POST['fileToUpload3'];
        $filename = ($filename != '') ? $filename : $_POST['fileToUpload3'];
        $originname = ($originname != '') ? $originname : $_POST['fileToUpload2'];
        if ($filename != $_POST['fileToUpload3']) {
            $new_data = true;
        }
    }
    
    // draws upload file input
    echo "<div class='import-file'>";
    echo "<input class='upload-lb' id='upload_input' name='fileToUpload2' value='$originname' type='text'>";
    echo "<input id='fileToUpload' class='file-upload' name='fileToUpload' value='$filename' accept='.xls,.xlsx' type='file'> &nbsp; &nbsp;";
    echo "<input class='hide' name='fileToUpload3' value='$filename' type='text'>";
    echo "<label class='button bench-btn files' for='fileToUpload' id='fileToUpload'>Browse <i class='fa fa-search' aria-hidden='true'></i> </label>";
    echo "<input class='button secondary files hide' name='upload' id='upload' type='submit' value='Upload' formnovalidate='formnovalidate' />";
    if($upl_msg) { echo "<p class='fl-error'>$upl_msg</p>"; }
    echo "</div>";
?>