
var myWindow;

/*
 * Opens report to new window
 * @param {string} url 
 * @param {string} name
 * @param {number} rows 
 * @param {number} cols 
 * @param {json} group 
 */
var openPopup = function(args) {
    
    var height=(args.height) ? args.height : '';
    var width=(args.width) ? args.width : '';
    var win_width, win_height;
    if (screen.width > 1300 ) {
        switch(width) {
            case 'large':
                win_width = screen.width - (.4 * screen.width);
                break;
            case 'medium':
                win_width = screen.width - (.52 * screen.width);
                break;
            case 'small':
                win_width = screen.width - (.6 * screen.width);
                break;
            default:
                win_width = screen.width - (.55 * screen.width);
        }
    } else {
        switch(width) {
            case 'large':
                win_width = screen.width - (.25 * screen.width);
                break;
            case 'medium':
                win_width = screen.width - (.3 * screen.width);
                break;
            case 'small':
                win_width = screen.width - (.3 * screen.width);
                break;
            default:
                win_width = screen.width - (.3 * screen.width);
        }
    }
    
    if (screen.width > 1300 ) {
        switch(height) {
            case 'large':
                win_height = screen.height - (.2 * screen.height);
                break;
            case 'medium':
                win_height = screen.height - (.25 * screen.height);
                break;
            case 'small':
                win_height = screen.height - (.6 * screen.height);
                break;
            default:
                win_height = screen.height - (.25 * screen.height);
        }
    }
    else {
        switch(height) {
            case 'large':
                win_height = screen.height - (.15 * screen.height);
                break;
            case 'medium':
                win_height = screen.height - (.2 * screen.height);
                break;
            case 'small':
                win_height = screen.height - (.4 * screen.height);
                break;
            default:
                win_height = screen.height - (.2 * screen.height);
        }
    }
    
//    var win_height = (args.height) ? args.height : 800;
//    var win_width = (args.width) ? args.width : 1000;
    var win_left = ((1-(win_width/screen.width))/ 2)*screen.width;
    var win_top = ((1-(win_height/screen.height))/ 2)*screen.height;

    var url = args.url;
    var name = args.name;
    
    // opens the window
    var myWindow = window.open(url, name,
        "scrollbars=yes, height=" + win_height + ", width = " + win_width + ", left = " + win_left + ", top = " + win_top);
    myWindow.focus();
    
    return false;
};

/*
 * Opens table to new window
 * @param {string} url 
 * @param {string} name
 * @param {number} rows 
 * @param {number} cols 
 */
var openTablePopup = function(args) {
    var win_width = (args.tbl_width) ? ((args.tbl_width > 1000) ? 1240 : args.tbl_width + 300) : 1240;
    var win_height = (args.tbl_height) ? ((args.tbl_height > 600) ? 900 : args.tbl_height + 300) : 900;
    var win_left = ((1-(win_width/screen.width))/ 2)*screen.width;
    var win_top = ((1-(win_height/screen.height))/ 2)*screen.height;
    var url = args.url;
    var name = args.name;
    
    // opens the window
    myWindow = window.open(url, name,
        "scrollbars=yes, height=" + win_height + ", width = " + win_width + ", left = " + win_left + ", top = " + win_top);
    myWindow.focus();
    
    // data sent in json format or by ids
    if (!args.rows || !args.cols) {
        $(".app-alert").removeClass('hide').find('p').html('Invalid Data!');
        return false;
    }
    var result = getValById(args.rows, args.cols);
    var tbl_data = result[0];
    var ref_data = result[1];
    var group_data = result[3];
    
    sendTblData(url,name,tbl_data,ref_data,group_data,null,null,null,null,null);
    
    return false;
};

/*
 * Opens report to new window
 * @param {string} url 
 * @param {string} name
 * @param {number} rows 
 * @param {number} cols 
 * @param {json} group 
 */
var openReportPopup = function(args) {
    if (args.group == '' || args.ref_col == '') {
        return false;
    }
    
    var win_height = 900;
    var win_width = 1000;
    var win_left = ((1-(win_width/screen.width))/ 2)*screen.width;
    var win_top = ((1-(win_height/screen.height))/ 2)*screen.height;

    var url = args.url;
    var name = args.name;
    
    var section_data=[], kernel_prop;
    
    // WHEN HEIGHT > 900 => ERROR

    // opens the window
    myWindow = window.open(url, name,
        "scrollbars=yes, height=" + win_height + ", width = " + win_width + ", left = " + win_left + ", top = " + win_top);
    myWindow.focus();
    
    // data sent in json format or by ids
    var result = getValById(args.rows, args.cols,args.after_run);
    var tbl_data = result[0];
    var group = args.group;
    var ref_col = args.ref_col;
    section_data = result[2];
    var wd_chart = result[4]; 
    var wd_table = result[5]; 
    var kernel_prop = result[6]; 
    
    sendTblData(url,name,tbl_data,ref_col,group,section_data,wd_chart,wd_table, kernel_prop);
    
    deleteImages();
    
    return false;
};

function deleteImages() {
    if (myWindow) {
        var filename;
        myWindow.onbeforeunload = function (e) {
            var files_array = localStorage.getItem('files_array');
            var files2del = files_array.split(',');
            for (var i=0; i<files2del.length; i++) {
                filename = files2del[i];
                var parent_url = myWindow.opener.location.href.substring(0, myWindow.opener.location.href.length-1);
                
                $.ajax({
                    url: parent_url + 'php-src/handler/delete.php',
                    data: {'file' : filename },
                    method: 'GET',
                    success: function (response) {
                    },
                    error: function () {
                    }
                });
            }
            localStorage.setItem('files_array',null);
        };
    }
}


/*  to popup window
 * @param {string} url 
 * @param {string} name 
 * @param {json} data  
 */
function sendTblData(url,name,tbl_data,ref_col,group,section_data,wd_chart,wd_table,kernel_prop) {
    // hidden Form
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", url);
    form.setAttribute("target", "popup");
    
    // converts json to string and appends to form
    form = createJsonElem(form,'tbl_data',JSON.stringify(tbl_data));
    form = createJsonElem(form,'ref_col',JSON.stringify(ref_col));
    form = createJsonElem(form,'group',JSON.stringify(group));
    form = createJsonElem(form,'section_data',JSON.stringify(section_data));
    form = createJsonElem(form,'wd_chart',JSON.stringify(wd_chart));
    form = createJsonElem(form,'wd_table',JSON.stringify(wd_table));
    form = createJsonElem(form,'kernel_prop',JSON.stringify(kernel_prop));
    
    try {
        // to send json data via ajax
        var data = {};
        for (var i = 0, ii = form.length; i < ii; ++i) {
            var input = form[i];
            if (input.name) {
                data[input.name] = input.value;
            }
        }

        var xhr = new XMLHttpRequest();
        xhr.open(form.method, form.action, true);
        xhr.setRequestHeader("Content-type", "application/json");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                $(".app-alert").removeClass('hide').find('p').html(xhr.responseText);
                return false;
            }
        };
        xhr.send(data);
    } catch (err) {
        $(".app-alert").removeClass('hide').find('p').html(err.message);
        return false;
    }

    document.body.appendChild(form);

    // submits the form
    form.submit();
    
    return false;
}

/*
 * creates json element and append to form
 * @param {dom} form 
 * @param {string} col 
 * @param {string} val  
 */
function createJsonElem(form,col,val) {
    // hidden Field
    var hiddenField = document.createElement("input");
    
    // col
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("id", col);
    hiddenField.setAttribute("name", col);
    hiddenField.setAttribute("value", val);
    
    // append to form
    form.appendChild(hiddenField);
    
    return form;
}

/*
 * retrieve table values by id
 * @param {number} form 
 * @param {string} rows 
 * @param {string} cols  
 * @return {array} cols  
 */
function getValById(rows, cols, after_run) {
    var result=[], checked=[], sections=[], group=[];
    var des, quart, kernel, kernel_prop;
    for (var i = 0; i < rows; i++){
        var columns = [];
        for (var j = 0; j < cols; j++){
            var indx = i + ':' + j;
            var pop_cell = document.getElementById(indx);
            var pop_cell_val = 0;
            if (pop_cell) {
                pop_cell_val = pop_cell.value;
                if (j===0 && pop_cell.checked) {
                    group.push(pop_cell.value);
                }
                
                var ref_elem = document.getElementById('ref_col_' + indx);
                
                if(ref_elem) {
                    if (ref_elem.value=='ref') {
                        checked[0] = pop_cell_val;
                    }
                }
            }
            columns[j] = pop_cell_val;
        }
        result[i] = columns;
    }
    
    des = document.getElementById('sec-descr');
    quart = document.getElementById('sec-quart');
    kernel = document.getElementById('sec-kernel');
    if(after_run) {
        sections = [[des.value,des.checked],[quart.value,quart.checked],[kernel.value,kernel.checked]];
    } else {
        sections = [[des.value,false],[quart.value,false],[kernel.value,false]];
    }
    
    var wd_chart = document.getElementById('wd_chart');
    if(wd_chart) {
        var wd_chart = document.getElementById('wd_chart').value;
    }
    
    var wd_table = document.getElementById('wd_table');
    if(wd_table) {
        var wd_table = document.getElementById('wd_table').value;
    }
    
    var kernel_prop = document.getElementById('kernel_prop');
    if(kernel_prop) {
        var kernel_prop = document.getElementById('kernel_prop').value;
    }
    
    return [result,checked,sections,group,wd_chart,wd_table,kernel_prop];
}

/*
 * set popup's values to parent's
 * @param {numeric} rows 
 * @param {numeric} cols 
 */
function savePopupTbl() {
    
    var rows = $('#popup-rows').text();
    var cols = $('#popup-cols').text();
    
    // get table values by id
    for (var j=0;j<Number(rows)+1;j++) {
        for(var y=0;y<Number(cols)+1;y++) {
            var indx = j + ':' + y;
            var pop_cell = document.getElementById(indx);
            if (pop_cell) {
                var pop_cell_val = pop_cell.value;
                var par_cell = window.opener.document.getElementById(indx);
                par_cell.value = pop_cell_val;  // set value to parent
                if (y===0) {
                    par_cell.checked = pop_cell.checked;
                    
                    if(pop_cell.checked) {
                        par_cell.parentElement.parentElement.parentElement.classList.remove('removed');
                    } else {
                        par_cell.parentElement.parentElement.parentElement.classList.add('removed');
                    }
                }
                
                if (y===1) {
                    var ref_elem = document.getElementById('ref_col_' + indx);
                    
                    
                    if (par_cell.parentElement.parentElement.childNodes[0].childNodes[0].childNodes[0]) {
                        par_cell.parentElement.parentElement.childNodes[0].childNodes[0].childNodes[0].value = pop_cell_val;
                    }
                    
                    if(ref_elem) {
                        if (ref_elem.value=='ref') {
                            window.opener.document.getElementById('ref_col_' + indx).value = 'ref';
                            var children = window.opener.document.getElementById('ref_col_' + indx).parentElement.parentElement.parentElement.childNodes;
                            for (i = 0; i < children.length; i++) {
                                var child = children[i];
                                child.classList.remove('ref-row');
                            }
                            
                            window.opener.document.getElementById('ref_col_' + indx).parentElement.parentElement.classList.add('ref-row');
                            
                            window.opener.document.getElementById('ref_region').value = pop_cell_val;
                            
                        }
                    }
                }
            }
        }
    }

    window.close();
}
