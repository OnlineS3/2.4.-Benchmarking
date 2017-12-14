
jQuery(document).ready(function(){
$('#tool').click(function(){
    
//    localStorage.clear();	//NA SVISTEI AUTI I GRAMMI GIA NA STAMATISEI NA VGAINEI KATHE FORA
    var isshow = localStorage.getItem('isshow');

    if (isshow== null) {
        localStorage.setItem('isshow', 1);
        
    
        swal.setDefaults({
            confirmButtonText: 'Next &rarr;',
            showCancelButton: true,
            animation: false,
            progressSteps: ['1', '2', '3']
        });

        var steps = [
            {
                title: 'Step 1',
                text: 'Benchmarking-1',
                imageUrl: 'images/marker.png',
                onOpen: function (){
                    $('').addClass('highlight');
                },
                onClose: function (){
                    $('').removeClass('highlight');
                }
            },
            {
                title: 'Step 2',
                text: 'Benchmarking-2',
                imageUrl: 'images/marker.png',
                onOpen: function (){
                    $('').addClass('highlight');
                },
                onClose: function (){
                    $('').removeClass('highlight');
                }
            },
            {
                title: 'Step 3',
                text: 'Benchmarking-3',
                imageUrl: 'images/marker.png',
                onOpen: function (){
                    $('').addClass('highlight');
                },
                onClose: function (){
                    $('').removeClass('highlight');
                }
            }
        ];

        swal.queue(steps).then(function (result) {
        swal.resetDefaults();
        swal({
            title: 'All done!',
            html: 'You are ready to use our tool!',
                confirmButtonText: 'Go On!',
                showCancelButton: false
        });
        }, function () {
            swal.resetDefaults();
        });
    
   }
});

});