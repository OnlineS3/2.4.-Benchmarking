
/*
 * exports xlsx file
 * @param {json} data 
 * @param {string} filename  
 */
var export2xls = function(args) {
    
    var arr_data = args.data;
    var sheet_name;
    
    if (args.ref_col) {arr_data.unshift([]);}
    if (args.group) {arr_data.unshift(args.group);}
    if (args.ref_col) {
        arr_data.unshift(args.ref_col);
    }
    if (args.sheet_name) {
        sheet_name = args.sheet_name;
    }
    
    var max_cols = getMaxCol(arr_data);
    
    var headers_sql='';
    for (var i=0;i<max_cols;i++) {
        headers_sql += '[' + i + '],';
    }
    headers_sql = headers_sql.substring(0, headers_sql.length-1);
    
    // get max columns
    // create string
    var filename = (args.filename) ? args.filename : 'online_xls.xlsx';
    if (arr_data === null) {
        $(".app-alert").removeClass('hide').find('p').html('The table provided is empty !');
        return false;
    }
    
    try {
        alasql("SELECT " + headers_sql + " INTO XLSX('" + filename + "', {headers:false,sheetid:'" + sheet_name + "'}) FROM ?", [arr_data]);
    }
    catch(err) {
        $(".app-alert").removeClass('hide').find('p').html(err.message);
        return false;
    }
    return false;
};

