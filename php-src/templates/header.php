<header id='site-header'>

    <nav id='top-menu'>
        <ul>
            <li><a href='http://www.onlines3.eu/' target="_blank">Online S3 Project</a></li>
            <li><a href='http://www.s3platform.eu/how-to-form-ris3/' target="_blank"><i class="fa fa-lightbulb-o" aria-hidden="true"></i>Applications</a></li>
            
            <li>
                <a href='#' class="beta"/><i class="fa fa-cog" aria-hidden="true"></i>Toolbox</a>
            </li>
            <li>
                <a href='#' class="beta"/><i class="fa fa-bar-chart" aria-hidden="true"></i>Analytics</a>
            </li>
            <li>
                <a href='#' class="beta"/><i class="fa fa-life-ring" aria-hidden="true"></i>Support</a>
            </li>
            <li><a href="http://www.onlines3.eu/contact/" target="_blank"><i class="fa fa-envelope-o" aria-hidden="true"></i>Contact</a></li>
        </ul>
    </nav>

    <div id='header-main'>

        <div class='top-section'>
            <div class='headers'>   
                <img class='logo-img' src='images/logo.png' width='77'>
                <div>
                    <p class="heading"><a href='http://www.s3platform.eu/'>Online S3 Platform</a></p> 
                    <p class="sub-heading">Benchmarking</p>
                </div>
            </div>

            <div class="social-links">
                <a href="https://twitter.com/online_s3" title="Twitter" target="_blank"><i class="fa fa-twitter-square" aria-hidden="true"></i></a>
                <a href="https://plus.google.com/102042915278982824881" title="Google+" target="_blank"><i class="fa fa-google-plus-square" aria-hidden="true"></i></a>
                <a href="https://www.youtube.com/channel/UCuYNnd9rdrN_EbF5_P0Nmog" title="YouTube" target="_blank"><i class="fa fa-youtube-square" aria-hidden="true"></i></a>

            </div>
            
            <img id="beta" class="alt" src="images/beta.png" width="42">
        </div>

        <div class='bottom-section'>
            <div class="menu">
                <ul>
                    <li><a id='about' href='#'>About</a></li>
                    <li><a id='guide' href='#'>Guide</a></li>
                    <li><a id='docs' href='#'>Related Documents</a></li>
                    <li><a id='tool' href='#'>Access to application</a></li>
                </ul>
            </div>

            <div class='user-btns'>
                
                <?php if (is_user_logged_in()) :  
                    echo "<p class='user-account'>$user->user_email</p>"; ?>
                    <a class='login-btn' href="<?php echo wp_logout_url(get_permalink()); ?>">Sign out</a>
                <?php else : ?>
                    <a class='login-btn' href="<?php echo wp_login_url(get_permalink()); ?>">Sign in</a>
                    <a class='register-btn' href="<?php echo site_url('/wp-login.php?action=register&redirect_to=' . get_permalink()) ?>"> Sign up </a>
                <?php endif;?>
                    
            </div>
        </div>

    </div>

    <div id='breadcrumb'>
        <ul>
            <li><a target='_blank' href='http://www.s3platform.eu'>Online S3 Platform</a></li>
            <li><a target='_blank' href='http://www.s3platform.eu/2-analysis-of-context/'>Analysis of context</a></li>
            <li><span>2.4 Benchmarking</span></li>
        </ul>
    </div>

</header>