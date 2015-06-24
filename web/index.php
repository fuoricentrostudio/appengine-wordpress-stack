<?php

// WordPress view bootstrapper
define('WP_USE_THEMES', true);

if(isset($_GET['attachment_id'])){
    header('HTTP/1.1 503 Service Temporarily Unavailable');
    header('Status: 503 Service Temporarily Unavailable');
    header('Retry-After: 36000');//300 seconds    
    exit();
}

require(__DIR__ . '/wp/wp-blog-header.php');
