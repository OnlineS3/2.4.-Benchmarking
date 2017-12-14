
<?php
    // displays guidelines
    $cls = ($show=='show') ? 'show-cell' : '';
    echo "<div class='guidelines $cls'>";
        echo"<p>1. Generate your own table</p>";
        echo "<ul style='margin-left: 2rem;'>";
            echo "<li>Set dimensions and press ‘Generate’</li>";
            echo "<li>Fill the table -> see our <a href=''>example</a></li>";
            echo "</ul>";
        echo "<p>or import an excel file -> see our <a href=''>example</a></p>";
        echo "<p>2. Set a reference region</p>";
        echo "<p>3. You can select a group of regions</p>";
        echo "<p>4. Check the sections to display </p>";
        echo "<p>5. Press 'Run' </p>";
        echo "<br>";
        echo "<p>Clear:</p>";
        echo "<p>Save:</p>";
        echo "<p>Insert to report:</p>";
        echo "<p><a href=''>A complete guide to the tool </a></p>";
    echo "</div>";

    $cls = ($show=='show') ? 'hide' : '';
    echo "<div class='guidelines-init $cls'>";
    echo"<p class='show'>1. Generate your own table</p>";
    echo "<ul style='margin-left: 2rem;'>";
    echo "<li>Set dimensions and press ‘Generate’</li>";
    echo "<li>Fill the table -> see our <a href=''>example</a></li>";
    echo "</ul>";
?>
