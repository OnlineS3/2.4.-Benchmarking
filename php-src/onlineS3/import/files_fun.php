
<?php
	
	function load_dir($conn, $unzip_dir) {
            // read file names
            $files = scandir($unzip_dir);

            // load files
            for ($i=0;$i<count($files);$i++) {
                $filename = $unzip_dir . '/' . $files[$i];
                $var = substr($files[$i], 0, strrpos($files[$i],'_1_Data'));

                if(strpos($filename, 'Data') >0) {
                    load_csv($conn, $filename, $var);
                }
            }
	}
	
	function load_csv($conn, $file, $variable) {
		$file_dt = fopen($file, "r") or die("Unable to open file!");
		$labels = fgets($file_dt);
		
		$labels = str_replace('"','',$labels);
		$fields = str2array($labels);
		
		$col_str = $set_str = '';
		
		for ($y=0;$y<count($fields);$y++) {
                    $col_str .= '@col' . ($y+1) . ',';

                    if (strtolower($fields[$y]) == 'geo') {
                            $set_str .= 'reg_id = @col' . ($y+1) . ', ';
                    }
                    if (strtolower($fields[$y]) == 'time') {
                            $set_str .= 'year_id = @col' . ($y+1) . ', ';
                    }
                    if (strtolower($fields[$y]) == 'unit') {
                            $set_str .= 'unit = @col' . ($y+1) . ', ';
                    }
                    if (strtolower($fields[$y]) == 'value') {
                            $set_str .= 'rd_value = @col' . ($y+1);
                    }
		}
		$col_str = substr($col_str,0,strlen($col_str)-1);
		
		fclose($file_dt);
		$enclose_str = '"';
		
		$sql = "LOAD DATA INFILE '$file' 
                        INTO TABLE in_data_new 
                        FIELDS TERMINATED BY ','
                        OPTIONALLY ENCLOSED BY '\"'
                        LINES TERMINATED BY '\n'
                        IGNORE 1 LINES
                        ($col_str)
                        set $set_str;";
		
		$conn->query($sql);
		echo $conn->error;
		
		$sql = "UPDATE in_data_new SET var_id = '$variable' WHERE coalesce(var_id, '') = ''";
		$conn->query($sql);
	}
	
	function unzip_file($file_name,$unzip_dir) {
		$zip = new ZipArchive;
		$res = $zip->open($file_name);
		if ($res === TRUE) {
		  $zip->extractTo($unzip_dir);
		  $zip->close();
		  return true;
		} else {
			return false;
		}
	}

    // converts csv file to array
    function read_csv($file) {
        $rows = 0;
        $csv_array = array();

        // gets the file pointer
        if (($handle = fopen($file, "r")) !== FALSE) {
            // reads each line of csv
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // number of cols
                $cols = count($data);
                //echo "<p> $num fields in line $row: <br /></p>\n";

                // fills array
                for ($c=0; $c < $cols; $c++) {
                    $csv_array[$rows][$c] = $data[$c];
                    //echo $csv_array[$rows][$c] . "<br />\n";
                }
                $rows++;
            }
            fclose($handle);
        }

        $csv = array('csv_array'=>$csv_array, 'rows'=>$rows-1, 'cols'=>$cols);
        return $csv;

    }

    function xls2csv($file_name, $excel_dir) {
        include 'php-src/onlineS3/classes/PHPExcel/Classes/PHPExcel.php';

        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_type = ($ext=='xls') ? 'Excel5' : 'Excel2007';

        // Open Excel file using PHPExcel IOFactory
        $objReader = PHPExcel_IOFactory::createReader($file_type);
        $objReader->setReadDataOnly(true);   
        $objPHPExcel = $objReader->load($file_name);

        // create a CSV File Writer
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');

        // Save xls as csv
        $out_file = $excel_dir . '/' . "output.csv";
        $objWriter->save($out_file);

        return $out_file;
    }

?>