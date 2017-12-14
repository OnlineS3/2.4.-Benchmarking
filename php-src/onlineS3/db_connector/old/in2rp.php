

<?php
function in2rp($conn) {
	
	$sql_delete = "DELETE FROM rp_data";
	$conn->query($sql_delete);
	
	$sql_delete = "DELETE FROM tmp_data";
	$conn->query($sql_delete);
	
	
	$col_sql = "SHOW COLUMNS FROM in_data";
	$col_res = $conn->query($col_sql);
	
	$y = 0;
	if ($col_res->num_rows > 0) {
		while($row = $col_res->fetch_assoc()) {
			$col = $row['Field'];
			if (contains($col, 'year')) {
				$year_array[$y++] = substr($col, 4);
			}
		}
	}
	
	$var_sql = "SELECT DISTINCT var_id, var_descr FROM in_data";
	$var_res = $conn->query($var_sql);
	
	// if ($var_res->num_rows > 0) {
		// while($row = $var_res->fetch_assoc()) {
			// $var_id = $row['var_id'];
			// $var_descr = $row['var_descr'];
			
			// $sql_insert = "INSERT INTO rp_variable (var_id, var_descr) VALUES ('$var_id', '$var_descr')";
			
			// $conn->query($sql_insert);
		// }
	// }
	
	$imp_sel = "SELECT in_data.var_descr, reg.reg_id as reg_id, in_data.var_id, reg.reg_id FROM in_data JOIN rp_region reg ON in_data.region = reg.rg_label";
	
	$import_res = $conn->query($imp_sel);
	
	$x = 0;
	if ($import_res->num_rows > 0) {
		while($row = $import_res->fetch_assoc()) {
			
			$var_id = $row['var_id'];
			$reg_id = $row['reg_id'];

			$sql_insert = "INSERT INTO tmp_data (var_id, reg_id) VALUES ('$var_id', '$reg_id')";
			
			$conn->query($sql_insert);
		}
	}
	
	for ($i=0; $i<count($year_array); $i++) {
		$year = 'year' . $year_array[$i];

						
		$sql_base = "INSERT INTO rp_data (var_id, reg_id, year_id, rd_value)
						SELECT DISTINCT rt.var_id, rt.reg_id, '$year_array[$i]', ind.$year
						FROM tmp_data rt JOIN in_data ind ON rt.var_id = ind.var_id 
						JOIN rp_region reg ON ind.region = reg.rg_label AND rt.reg_id = reg.reg_id WHERE ind.$year > 0";
						
		 $conn->query($sql_base);
	
	}
	
	return  true;
}
?>