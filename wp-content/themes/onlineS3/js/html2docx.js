
/*
 * exports html to doc or docx file
 * @param {string} wd_id 
 * @param {string} filename  
 */

var html2docx = function(args) {
    
    var tbl_img, kernel_img, wd_val;
    var html, link, blob;
    
    var wd_id = (args.wd_id) ? '#' + args.wd_id : 'wd-str';
    var filename = (args.filename) ? args.filename : 'online_doc.docx';
    
    // i have to load it to server
    //convertElem2img('colored-table');
    //var img_src = $('colored-table_img').attr('src');
    
    if (!window.Blob) {
        $(".app-alert").removeClass('hide').find('p').html('Your legacy browser does not support this action.');
        return false;
    }

    // creates html to export
    html = '<html>';
    
    html += '<style>' +
            '@page WordSection1 {size: 841.95pt 595.35pt;mso-page-orientation: landscape;}' +
            'div.WordSection1 {page: WordSection1;}' +
            'table{border-collapse:collapse;}td{border:1px gray solid;width:30em;padding:2px;}'+
            '</style>';
            
    html += "<body>" + $(wd_id).val() + "</body>";
    
    html += '</html>';
    
    try {
        if (typeof html != 'undefined') {
            
            // use htmlDocx to save html as docx
            var converted = htmlDocx.asBlob(html);
            saveAs(converted, filename);
            
            var a = document.createElement("a");    // create tmp link
            a.download = filename;
            link.href = URL.createObjectURL(converted);
            document.querySelector("body").appendChild(a);
            // if IE else just click
            if (navigator.msSaveOrOpenBlob ) navigator.msSaveOrOpenBlob(blob, filename);
                else a.click();
            document.querySelector("body").removeChild(a);
            
        }
    } catch(err) {
        $(".app-alert").removeClass('hide').find('p').html(err.message);
        return false;
    }
    
    return false;
};