
/*
 * exports dom element to png image
 * @param {numeric} tbl_id 
 * @param {string} filename  
 */
var exportElem2png = function(args) {
    
    var img_elem = $('#'+args.tbl_id);  // gets image div
    var filename = (args.filename) ? args.filename : 'Export.png';
    
    var max_width = img_elem.find('table').css('max-width');
    var max_height = img_elem.find('table').css('max-height');
    
    var tempScrollTop = $(window).scrollTop();
    
    // clears max-width to capture the whole image
    img_elem.find('table').css('max-width','none').css('max-height','none').css('padding', '2rem');
    
    try {
        html2canvas(img_elem, {
            onrendered: function (canvas) {
                // resets max width & height
                img_elem.find('table').css('max-width',max_width).css('max-height',max_height);
                
                theCanvas = canvas;
                if (theCanvas.msToBlob) { //for IE
                    var png_data = theCanvas.msToBlob();
                    window.navigator.msSaveBlob(png_data, filename);
                } else {
                    var png_data = theCanvas.toDataURL("image/png");    // image to url
                    png_data = png_data.replace(/^data:image\/png/, "data:application/octet-stream");

                    var a = document.createElement("a");    // create tmp link
                    a.download = filename;
                    a.href = png_data;
                    document.querySelector("body").appendChild(a);
                    a.click();
                    document.querySelector("body").removeChild(a);
                }
                $(window).scrollTop(tempScrollTop);
            }
        });
    }
    catch(err) {
        $(".app-alert").removeClass('hide').find('p').html(err.message);
        return false;
    }
    
    return false;
};

/*
 * convert dom element to img
 * @param {numeric} tbl_id  
 */
var convertElem2img = function(elem_id) {
    var img_elem = document.getElementById(elem_id);
    var img, imgDataUrl;
    
    var max_width = $('#'+elem_id).find('table').css('max-width');
    var max_height = $('#'+elem_id).find('table').css('max-height');
    
    $('#'+elem_id).find('table').css('max-width','none').css('max-height','none').css('padding', '2rem');
    
    html2canvas(img_elem, {
        onrendered: function(canvas) {
            try {
                $('#'+elem_id).find('table').css('max-width',max_width).css('max-height',max_height);
                //get a drawing context from canvas
                var context = canvas.getContext("2d");

                //convert canvas data to an image data url
                img = new Image();
//              if (canvas.msToBlob) { //for IE
//                 imgDataUrl = canvas.msToBlob();
//                 alert(imgDataUrl);
//              } else {
                imgDataUrl = canvas.toDataURL();
                //}
                
                img.src = imgDataUrl;
                
                $('#wd_table').val(imgDataUrl);
                
                return true;
            } catch(err) {
                alert(err.message);
                return false;
            }
        }
        }  
    );
    
    return img;
    
};