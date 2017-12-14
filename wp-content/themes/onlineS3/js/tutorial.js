
function showTutorial() {
    
   // localStorage.clear();	//NA SVISTEI AUTI I GRAMMI GIA NA STAMATISEI NA VGAINEI KATHE FORA
    var isshow = localStorage.getItem('benchtut_shown');

    if (isshow== null) {
        swal.setDefaults({
            confirmButtonText: 'Next &rarr;',
            showCancelButton: true,
            animation: false,
            progressSteps: ['1', '2', '3', '4', '5', '6']
        });

        var steps = [
            {
                title: 'Upload data',
                text: 'Guide description 1',
                imageUrl: 'images/upload_tut.png',
                onOpen: function (){
                    $('.main-section.one').addClass('highlight');
                    $('html, body').scrollTop($('.main-section.one').offset().top);
                },
                onClose: function (){
                    $('.main-section.one').removeClass('highlight');
                }
            },
            {
                title: 'Descriptive Statistics',
                text: 'Guide description 2',
                imageUrl: 'images/descr_tut.png',
                onOpen: function (){
                    $('.main-section.two').addClass('highlight');
                    $('html, body').scrollTop($('.main-section.two').offset().top);
                },
                onClose: function (){
                    $('.main-section.two').removeClass('highlight');
                }
            },
            {
                title: 'Quartiles',
                text: 'Guide description 3',
                imageUrl: 'images/table_tut.png',
                onOpen: function (){
                    $('.main-section.three').addClass('highlight');
                    $('html, body').scrollTop($('.main-section.three').offset().top);
                },
                onClose: function (){
                    $('.main-section.three').removeClass('highlight');
                }
            },
            {
                title: 'Kernel Diagram',
                text: 'Guide description 4',
                imageUrl: 'images/kernel_tut.png',
                onOpen: function (){
                    $('.main-section.four').addClass('highlight');
                    $('html, body').scrollTop($('.main-section.four').offset().top);
                },
                onClose: function (){
                    $('.main-section.four').removeClass('highlight');
                }
            },
            {
                title: 'Export Table',
                text: 'Guide description 5',
                imageUrl: 'images/excel_tut.png',
                onOpen: function (){
                    $('.export_table').addClass('highlight');
                    $('html, body').scrollTop($('.export_table').offset().top);
                },
                onClose: function (){
                    $('.export_table').removeClass('highlight');
                }
            },
            {
                title: 'Export Report',
                text: 'Guide description 6',
                imageUrl: 'images/report_tut.png',
                onOpen: function (){
                    $('.export_report').addClass('highlight');
                    $('html, body').scrollTop($('.export_report').offset().top);
                },
                onClose: function (){
                    $('.export_report').removeClass('highlight');
                }
            }
        ];

        swal.queue(steps).then(function (result) {
        swal.resetDefaults();
        swal({
                title: 'Ready now!',
                confirmButtonText: 'Start',
                showCancelButton: false,
                onOpen: function (){
                    $('.swal2-confirm').after("<div class='chk-primary hide-tutorial'><input id='hide-check' class='test' type='checkbox'><label for='hide-check'>Do not show tutorial again</label></div>");
                    hideTutorial();
                }
            });
        }, function () {
            swal.resetDefaults();
        });
    
     }
}
function hideTutorial() {
    $('#hide-check').change(function() {
        if ($(this).is(':checked')) {
            localStorage.setItem('benchtut_shown', 1);
        } else {
            localStorage.setItem('benchtut_shown', null);
        }
    });
}