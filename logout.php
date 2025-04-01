
<?php
require_once 'includes/session.php';
// Author Andrew Abbott
// Destroy the session
Session::destroy();

// Redirect to the login page
header('Location: login.php');
exit;