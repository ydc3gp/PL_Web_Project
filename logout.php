<?php
require_once 'includes/session.php';

// Destroy the session
Session::destroy();

// Redirect to the login page
header('Location: login.php');
exit;