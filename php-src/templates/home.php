
<?php
    
    include 'php-src/onlineS3/db_connector/connection.php';
    $conn = conn_db();
    include 'php-src/onlineS3/db_connector/exec_sql.php';
    include 'php-src/db_func/upd_tbl.php';
    
    include 'header.php';

    echo "<div class='benchmarking'>";
    
    echo "<div id='about' class='section-page hide plain'>";
    include 'about.php';
    echo "</div>";

    echo "<div id='guide' class='section-page hide plain'>";
    include 'guide.php';
    echo "</div>";

    echo "<div id='docs' class='section-page hide plain'>";
    include 'docs.php';
    echo "</div>";
    
    echo "<div id='tool' class='section-page hide'>";
    include 'tool.php';
    echo "</div>";
    
    // include header and categories
    echo "</div>";
    
    echo "</div>";
    
    include 'menu.php';
    include 'sidebar.php';
    
    include 'footer.html';
    
    
