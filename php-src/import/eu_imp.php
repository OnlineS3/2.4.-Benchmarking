
<?php

    function import_eu_files($conn) {
        $imp_dir = 'C:/xampp/htdocs/regplain/import/source';
        $unzip_dir = 'C:/xampp/htdocs/regplain/import/csv_unzipped';
        $excel_dir = 'C:/xampp/htdocs/regplain/import/csv_excel';

        // read file names
        $files = get_files_by_dir($imp_dir);

        // clear table
        $sql = 'DELETE FROM in_data_new';
        $conn->query($sql);

        for ($i=0;$i<count($files);$i++) {
            $path = $files[$i]['path'];
            $file = $files[$i]['name'];

            // unzip file
            if (strpos($path,'.zip') > 0) {
                unzip_file($path,$unzip_dir);
            }

            // conver excel files to csv
            if (strpos($path,'.xls') > 0) {
                xls2csv($path, $excel_dir);
            }
        }

        // load csv files to db
        load_dir($conn, $unzip_dir);
    }
	
?>