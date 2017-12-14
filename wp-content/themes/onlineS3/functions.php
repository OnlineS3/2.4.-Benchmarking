
<?php
    
// my theme for online
function my_theme_enqueue_styles() {
    $parent_style = 'parent-style';

    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . 'style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
    
    wp_enqueue_style( 'sweetalert2', get_stylesheet_directory_uri() . '/css/external/sweetalert2.min.css', array(), filemtime( get_stylesheet_directory() . '/css/external/sweetalert2.min.css' ) );
    wp_enqueue_style( 'chosen', get_stylesheet_directory_uri() . '/css/external/chosen/chosen.css', array(), filemtime( get_stylesheet_directory() . '/css/external/chosen/chosen.css' ) );
    wp_enqueue_style( 'layout_wrps', get_stylesheet_directory_uri() . '/css/layout_wrps.css', array(), filemtime( get_stylesheet_directory() . '/css/layout_wrps.css' ) );
    wp_enqueue_style( 'svg', get_stylesheet_directory_uri() . '/css/jquery.svg.css', array(), filemtime( get_stylesheet_directory() . '/css/jquery.svg.css' ) );
    wp_enqueue_style( 'font-awesome', get_stylesheet_directory_uri() . '/css/font-awesome/css/font-awesome.min.css' );
    wp_enqueue_style( 'open-sans', 'http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,700,300,600,800,400&subset=latin,greek');
    wp_enqueue_style( 'base', get_stylesheet_directory_uri() . '/css/base.css', array(), filemtime( get_stylesheet_directory() . '/css/base.css' ) );
    wp_enqueue_style( 'layout2', get_stylesheet_directory_uri() . '/css/layout.css', array(), filemtime( get_stylesheet_directory() . '/css/layout.css' ) );
    wp_enqueue_style( 'test', get_stylesheet_directory_uri() . '/css/common.css', array(), filemtime( get_stylesheet_directory() . '/css/common.css' ) );
    wp_enqueue_style( 'fonts', get_stylesheet_directory_uri() . '/css/fonts.css', array(), filemtime( get_stylesheet_directory() . '/css/fonts.css' ) );
    wp_enqueue_style( 'utils', get_stylesheet_directory_uri() . '/css/utils.css', array(), filemtime( get_stylesheet_directory() . '/css/utils.css' ) );
    wp_enqueue_style( 'table', get_stylesheet_directory_uri() . '/css/table.css', array(), filemtime( get_stylesheet_directory() . '/css/table.css' ) );
    wp_enqueue_style( 'sidebar', get_stylesheet_directory_uri() . '/css/sidebar.css', array(), filemtime( get_stylesheet_directory() . '/css/sidebar.css' ) );
    wp_enqueue_style( 'd3', get_stylesheet_directory_uri() . '/css/d3.css', array(), filemtime( get_stylesheet_directory() . '/css/d3.css' ) );
    wp_enqueue_style( 'validator', get_stylesheet_directory_uri() . '/css/validator.css', array(), filemtime( get_stylesheet_directory() . '/css/validator.css' ) );
    wp_enqueue_style( 'popup', get_stylesheet_directory_uri() . '/css/popup.css', array(), filemtime( get_stylesheet_directory() . '/css/popup.css' ) );
    wp_enqueue_style( 'print', get_stylesheet_directory_uri() . '/css/print.css', array(), filemtime( get_stylesheet_directory() . '/css/print.css' ) );
    wp_enqueue_style( 'header', get_stylesheet_directory_uri() . '/css/header.css', array(), filemtime( get_stylesheet_directory() . '/css/header.css' ) );
    wp_enqueue_style( 'footer', get_stylesheet_directory_uri() . '/css/footer.css', array(), filemtime( get_stylesheet_directory() . '/css/footer.css' ) );
    wp_enqueue_style( 'tooltip', get_stylesheet_directory_uri() . '/css/tooltip.css', array(), filemtime( get_stylesheet_directory() . '/css/tooltip.css' ) );
    wp_enqueue_style( 'images', get_stylesheet_directory_uri() . '/css/images.css', array(), filemtime( get_stylesheet_directory() . '/css/images.css' ) );
    wp_enqueue_style( 'beta', get_stylesheet_directory_uri() . '/css/beta.css', array(), filemtime( get_stylesheet_directory() . '/css/beta.css' ) );
    wp_enqueue_style( 'accordion', get_stylesheet_directory_uri() . '/css/accordion.css', array(), filemtime( get_stylesheet_directory() . '/css/accordion.css' ) );
    wp_enqueue_style( 'tutorial', get_stylesheet_directory_uri() . '/css/tutorial.css', array(), filemtime( get_stylesheet_directory() . '/css/tutorial.css' ) );
    wp_enqueue_style( 'main', get_stylesheet_directory_uri() . '/css/app/main.css', array(), filemtime( get_stylesheet_directory() . '/css/app/main.css' ) );
    wp_enqueue_style( 'plain', get_stylesheet_directory_uri() . '/css/app/plain.css', array(), filemtime( get_stylesheet_directory() . '/css/app/plain.css' ) );
    wp_enqueue_style( 'report', get_stylesheet_directory_uri() . '/css/app/report.css', array(), filemtime( get_stylesheet_directory() . '/css/app/report.css' ) );
}

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

function my_theme_enqueue_scripts() {
    $parent_script = 'parent-script';

    wp_enqueue_script('child-script',
        get_stylesheet_directory_uri().'/js/main.js',
        array('jquery'),
        wp_get_theme()->get('Version')
    );
 
    wp_enqueue_script( 'jquerybase', get_stylesheet_directory_uri() . '/js/jquery/jquery-3.1.1.min.js');
    wp_enqueue_script( 'jquery64', get_stylesheet_directory_uri() . '/js/jquery/jquery.base64.js');
    
    wp_enqueue_script('jquery');
    
    wp_enqueue_script( 'core', 'https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js' );
    
    wp_enqueue_script( 'd3', get_stylesheet_directory_uri() . '/js/d3/d3.v3.min.js', array('jquery') );
    wp_enqueue_script( 'd3_tip', get_stylesheet_directory_uri() . '/js/d3/d3.tip.v0.6.3.js', array('jquery') );
    wp_enqueue_script( 'alasql', get_stylesheet_directory_uri() . '/js/external/alasql.min.js', array('jquery') );
    wp_enqueue_script( 'xlsx', get_stylesheet_directory_uri() . '/js/external/xlsx.core.min.js', array('jquery') );
    wp_enqueue_script( 'html2canvas', get_stylesheet_directory_uri() . '/js/external/html2canvas.js', array('jquery') );
    wp_enqueue_script( 'html_docx', get_stylesheet_directory_uri() . '/js/external/html-docx.js', array('jquery') );
    wp_enqueue_script( 'jspdf', get_stylesheet_directory_uri() . '/js/external/jspdf.min.js', array('jquery') );
//    wp_enqueue_script( 'why', get_stylesheet_directory_uri() . '/js/external/FileSaver.js', array('jquery') );
    wp_enqueue_script( 'wordexport', get_stylesheet_directory_uri() . '/js/external/jquery.wordexport.js', array('jquery') );
    wp_enqueue_script( 'validator', get_stylesheet_directory_uri() . '/js/external/jquery.validate.js', array('jquery') );
    wp_enqueue_script( 'sweetalert2', get_stylesheet_directory_uri() . '/js/external/sweetalert2.min.js', array('jquery')  );
    wp_enqueue_script( 'canvg_master', get_stylesheet_directory_uri() . '/js/external/canvg-master/canvg.js', array('jquery')  );
    
    wp_enqueue_script( 'chosen', get_stylesheet_directory_uri() . '/js/external/chosen/chosen.jquery.js', array('jquery') );
    wp_enqueue_script( 'chosen_proto', get_stylesheet_directory_uri() . '/js/external/chosen/chosen.proto.js', array('jquery') );
    
    wp_enqueue_script( 'xls2json', get_stylesheet_directory_uri() . '/js/xls2json.js', array('jquery') );
    wp_enqueue_script( 'gen', get_stylesheet_directory_uri() . '/js/gen.js', array('jquery') );
    wp_enqueue_script( 'uploadFile', get_stylesheet_directory_uri() . '/js/uploadFile.js', array('jquery') );
    wp_enqueue_script( 'table2png', get_stylesheet_directory_uri() . '/js/html2img.js' , array('jquery'));
    wp_enqueue_script( 'json2csv', get_stylesheet_directory_uri() . '/js/json2csv.js', array('jquery') );
    wp_enqueue_script( 'json2xls', get_stylesheet_directory_uri() . '/js/json2xls.js', array('jquery') );
    wp_enqueue_script( 'svg2png', get_stylesheet_directory_uri() . '/js/svg2png.js', array('jquery') );
    wp_enqueue_script( 'openPopup', get_stylesheet_directory_uri() . '/js/openPopup.js', array('jquery') );
    wp_enqueue_script( 'clearTable', get_stylesheet_directory_uri() . '/js/clearTable.js', array('jquery') );
    wp_enqueue_script( 'html2pdf', get_stylesheet_directory_uri() . '/js/html2pdf.js', array('jquery') );
    wp_enqueue_script( 'table2word', get_stylesheet_directory_uri() . '/js/html2docx.js', array('jquery') );
    wp_enqueue_script( 'images', get_stylesheet_directory_uri() . '/js/images.js', array('jquery') );
    wp_enqueue_script( 'layout', get_stylesheet_directory_uri() . '/js/layout.js', array('jquery') );

    wp_enqueue_script( 'tutorial', get_stylesheet_directory_uri() . '/js/tutorial.js', array('jquery') );
    
    wp_enqueue_script( 'main', get_stylesheet_directory_uri() . '/js/app/main.js', array('jquery') );
    wp_enqueue_script( 'kernel', get_stylesheet_directory_uri() . '/js/app/kernel.js', array('jquery') );
}

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_scripts' );

add_action('init', 'do_output_buffer');
function do_output_buffer() {
     ob_start();
}

add_filter('show_admin_bar', '__return_false');

remove_filter( 'the_content', 'wpautop' );

?>
