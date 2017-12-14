
function set_ref_region(region, btn) {
    if (region) {
        $('#ref_region').val(region);
    } else {
        region = $(btn).parent().find('.ref_region').val();
        $('#ref_region').val(region);
    }
    
    $('.ref-row').removeClass('ref-row');
    $('.ref_col').val('');
    $(btn).parent().find('.ref_col').val('ref');
    $(btn).parent().parent().addClass('ref-row');
    return false;
}

function set_error(msg) {
    jQuery('#error').css('display','block').html(msg);
    return true;
}

// sets file name in file input
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            jQuery('#upload_input').val(input.files[0].name);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// updates region group
function updRgGroup(obj,reg,row) {
    var remove = obj.children[0].classList.contains('fa-times');
    var cur_ref = document.getElementById(row + ':0');

    // check for ref col
    if (remove) {
        if (cur_ref.checked) {
            alert('You cannot remove the reference region from the group.');
            return;
        }
        var rg_group = jQuery('#rg_group').val();
        rg_group = rg_group.replace(reg + ',', "");
        rg_group = rg_group.replace(reg, "");
        jQuery('#rg_group').val(rg_group);
        obj.children[0].classList.remove("fa-times");
        obj.children[0].classList.add('fa-plus');
        obj.children[1].value = 'fa-plus';
        obj.nextSibling.innerHTML = 'Add region to reference group';
        obj.parentElement.parentElement.parentElement.classList.add("removed");
    } else {
        var rg_group = jQuery('#rg_group').val();
        rg_group = rg_group.concat(',' + reg);
        jQuery('#rg_group').val(rg_group);
        obj.children[0].classList.remove("fa-plus");
        obj.children[0].classList.add('fa-times');
        obj.nextSibling.innerHTML = 'Remove region from reference group';
        console.log(obj.children[1]);
        obj.children[1].value = 'fa-times';
        obj.parentElement.parentElement.parentElement.classList.remove("removed");
    }
}

function reset_tbl() {
    $("#user-tbl tr").removeClass("removed");
    $("#user-tbl .fa").removeClass('fa-plus');
    $("#user-tbl .fa").addClass("fa-times");
    $("#user-tbl .upd_type").val("fa-times");
    
    return true;
}

function export_input(args) {
    if(args.data !== '') {	// after run
        export2xls({ data: args.data });
    } else {	// before run to post the data
        $("#export_input").click();
    }
}

function export_report(args) {
    if(args.group !== '') {	// after run
        openReportPopup({url: "report", name:"popup", rows:args.rows, cols:args.cols, group:args.group, ref_col:args.ref_col, after_run: true, user_id:args.user_id});
    } else {	// before run to post the data
        $("#export-report").click();
    }
}

$('.in_group:checked').parent().parent().parent().addClass('removed');

jQuery(document).ready(function(){

    var session_cookie = document.cookie;
    var sep_1 = session_cookie.indexOf("=",session_cookie.indexOf("menu_ben")+1)+1;
    var sep_2 = (session_cookie.indexOf(";",session_cookie.indexOf("menu_ben"))>0) ? (session_cookie.indexOf(";",session_cookie.indexOf("menu_ben")+1)) : session_cookie.length;
    
    var menu_clicked = session_cookie.substring(sep_1,sep_2);
    menu_clicked = (menu_clicked!="about" && menu_clicked!="guide" && menu_clicked!="docs" && menu_clicked!="tool") ? 'about' : menu_clicked;
    
    setPage(menu_clicked);
    
    $.validator.addClassRules({
        tbl_cols:{
            required: true
        }
    });
    
    $.validator.addClassRules("is_numeric", {
        number: true
    });
    
    $('#user-form').validate({
        ignore: [], // not ignore hidden fields
        messages: {
            'ref_region': {
                required: "* You have to set a Reference Region",
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
    
    $('#popup-form').validate({
        ignore: [], // not ignore hidden fields
        messages: {
            'ref_region': {
                required: "* You have to set a Reference Region",
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
    
    $('#popup-form').on('submit', function() {
        if ($(this).valid()) {
            savePopupTbl();
        }
    });
    
    $('.col-base').hover(function() {
        if(!$(this).parent('tr').hasClass('removed')) {
            $(this).find('.set-ref').css('display','inline-block');
        }
    });
    
//    $('td.first_value').hover(function() {
//        if(!$(this).parent('tr').hasClass('removed')) {
//            $(this).parent('tr').find('.set-ref').css('display','inline-block');
//        }
//    });
    
    $('.col-base, td.first_value').mouseleave(function() {
        $(this).parent('tr').find('.set-ref').css('display','none');
    });
    
    if (sessionStorage.scrollTop != "undefined") {
        $(window).scrollTop(sessionStorage.scrollTop);
    }
    
    $('#check-all').on('change', function() {
        if($(this).is(':checked')) {
            $('.in_group').prop('checked',true);
            $('.removed').removeClass('removed');
        } else {
            $('.in_group').each(function(){
                if($(this).parent().parent().parent().hasClass('ref-row')===false) {
                    $(this).prop('checked',false);
                    $(this).parent().parent().parent().addClass('removed');
                }
            });
        }
    });
    
    //$(".chosen-select").chosen();
    
    $('#kernel_prop').chosen({
        "disable_search": true
    });
    
    jQuery('.auth0-lock-tabs li:first-child').css('display','none');
	
    $("#word-test").click(function() {
        $("#report-descr").html($('#input-descr').val());
        $("#export-report").wordExport('Benchmarking Report');
    });
    
    var $form = $('form#user-form'),
        origForm = $form.serialize();

    $("form#user-form :input:not([type='radio'])").on('change input', function() {
        $('#form-changed').val(1);
    });
    
    $('#fill:checked').parent().parent().parent().find('.tbl-dimensions').addClass('show');
    
    $('#fill:checked').parent().parent().parent().find('.tbl-dimensions').addClass('show');
    
    jQuery('#fill:checked').parents().find('.import-file').addClass('hide');
    
    $('.in_group').change(function(event) {
        event.preventDefault();
        if($(this).is(':checked')){
            $(this).parent().parent().parent().removeClass('removed');
            $(this).prop('checked',true);
        } else {
            
            $(this).parent().parent().parent().addClass('removed');
            $(this).prop('checked',false);
            $(this).parent().parent().parent().removeClass('ref-row');
            $('#ref_region').val('');
        }
    });
    
    $('input.ref_region').on('change',function(event) {
        var input_val = $(this).val();
        $(this).parent().parent().find('.in_group').val(input_val);
    });

    $("#word-export").click(function(event) {
        $("#export-tbl").wordExport('Benchmarking Report');
    });
    
    var ref_regions = $(this).find('.ref_region');
    ref_regions.each(function (index, value) { 
        var ref_val = $(this).val();
        
        if($('#ref_region').val() === ref_val && ref_val != '') {
            $(this).parent().parent().addClass('ref-row');
        }
    });
    
    jQuery('.tip-box .fa').on('click', function(){
        $(this).parent().remove();
        $('#show-tip').val('hide');
    });
    
    // on fill / import click display auto / excel blocks
    jQuery('#fill').click(function() {
        
        if ($('#form-changed').val() == 1) {
            swal({
                title: '',
                text: "Input data will get lost. Are you sure you want to change input method?",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger'
              }).then(function () {
                jQuery('.tbl-dimensions').addClass('show');
                jQuery('.import-file').removeClass('show');
                jQuery('.import-file').addClass('hide');
                jQuery('#upload_input').val('');
                jQuery('.excel-tbl, .chk-primary, .main-section.two, .main-section.three, .main-section.four').empty();
                $('#form-changed').val(0);
              }, function (dismiss) {
                    // dismiss can be 'cancel', 'overlay',
                    // 'close', and 'timer'
                    if (dismiss === 'cancel') {
                        $('#import').attr('checked','checked');
                        $('#import').prop("checked", true);
                    }
              });
            } else {
                jQuery('.tbl-dimensions').addClass('show');
                jQuery('.import-file').removeClass('show');
                jQuery('.import-file').addClass('hide');
                jQuery('#upload_input').val('');
                jQuery('.excel-tbl, .chk-primary, .main-section.two, .main-section.three, .main-section.four').empty();
                $('#form-changed').val(0);
            }
        } 
         
    );
    
    jQuery('#import').click(function() {
        
        if ($('#form-changed').val() == 1) {
            swal({
                title: '',
                text: "Input data will get lost. Are you sure you want to change input method?",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger'
              }).then(function () {
                $('.import-file').addClass('show');
                $('.tbl-dimensions').removeClass('show');
                $('#input-rows,#input-cols').val('');
                $('#form-changed').val(0);
            $('.auto-tbl, .chk-primary, .main-section.two, .main-section.three, .main-section.four').empty();
              }, function (dismiss) {
                    // dismiss can be 'cancel', 'overlay',
                    // 'close', and 'timer'
                    if (dismiss === 'cancel') {
                        $(this).prop("checked", false);
                        $('#fill').prop("checked", true);
                    }
              });
            } else {
                $('.import-file').addClass('show');
                $('.tbl-dimensions').removeClass('show');
                $('#input-rows,#input-cols').val('');
                $('.auto-tbl, .chk-primary, .main-section.two, .main-section.three, .main-section.four').empty();
                $('#form-changed').val(0);
            }
        } 
    );
     
        
    $('.in_group:not(:checked)').parent().parent().parent().addClass('removed');
    
    jQuery('.fa-plus').parent().parent().parent().parent().addClass('removed');

    jQuery('.ref-region').on('change', function() {
        //alert('Only one reference region permitted.');
        
        if(jQuery(this).is(':checked')) {
            if (jQuery(this).parent().parent().parent().hasClass('removed')) {
                jQuery(this).prop('checked','false');
                alert('You cannot set removed region as reference!');
                return true;
            }
            jQuery('.ref-region').not(this).prop('checked', false);
            jQuery('tr').removeClass('ref-row');
            jQuery(this).parent().parent().parent().addClass('ref-row');
        } else {
            jQuery(this).parent().parent().parent().removeClass('ref-row');
        }
        
    });

    // triggers upload button
    jQuery("#fileToUpload").change(function() {
        readURL(this);
        jQuery("#fileToUpload").siblings('#upload')[0].click();
    });
    
    // clears all table
    jQuery("#clear-table").click(function() {
        var confirm_val = confirm('Are you sure you want to clear all the data of the table ?');
        if (confirm_val === true) {
            jQuery('.input-data .cell input').val('');
        }
        return false;
    });

    jQuery('.full-screen').parents().find('.site-header').css('display','none');
    jQuery('.full-screen').parents().find('.site-footer').css('display','none');
    
    jQuery('.popup input').prop('readonly', false);
    jQuery('.auto-tbl input').prop('readonly', false);
    
    $('input[readonly]').focus(function(){
        $(this).blur();
    });

    $("#export_input").click(function(event){
            event.preventDefault();

    });
    
    if ($('#colored-table').width() > 0) {
        convertElem2img('export-tbl');
    } 
});

$(window).scroll(function() {
    sessionStorage.scrollTop = $(this).scrollTop();
});