<?php


// You can access the admin panel by using the following url: http://yoursite.com/admincp 

require 'assets/init.php';

if (IS_LOGGED == false || PT_IsAdmin() == false) {
	header("Location: " . PT_Link(''));
    exit();
}

// autoload admin panel files
require 'admin-panel/autoload.php';