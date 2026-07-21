<?php

/**
 * Directory Protection
 * 
 * This file prevents directory listing and redirects to the home page.
 */

// Redirect to home page
header('Location: /');
exit;