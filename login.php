<?php
require_once __DIR__ . '/../../private/includes/session.php';
require_once __DIR__ . '/../../private/Models/User.php';

// Redirect if already logged in
if (Session::isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password';
    } else {
        $user = new User();
        $loggedInUser = $user->login($email, $password);
        
        if ($loggedInUser) {
            Session::set('user_id', $loggedInUser['id']);
            Session::set('user_name', $loggedInUser['first_name']);
            Session::set('user_email', $loggedInUser['email']);
            
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid email or password';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>UniMatch - Login</title>
    <meta name="author" content="Andrew Abbott and Chris Dai" />
    <meta name="description" content="Log in to UniMatch." />
    <meta
      name="keywords"
      content="university, unimatch, university match, university finder, college finder, college, college match, college rankings, best university 2025, best college 2025"
    />
    <!-- Link Bootstrap -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      crossorigin="anonymous"
    />
    <!-- Link LESS  -->
    <link rel="stylesheet/less" type="text/css" href="styles/style.less" />
    <link rel="stylesheet/less" type="text/css" href="styles/navbar.less" />
    <script src="https://cdn.jsdelivr.net/npm/less"></script>

    <!-- Import Font  -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <!-- Navbar  -->
    <header>
      <nav class="custom-navbar">
        <div class="nav-container">
          <div class="left-section">
            <span class="logo">
              <a href="index.php">
                <img src="assets/logo.png" alt="UniMatch Logo" />
                <span class="logo-text">
                  <span id="logo-text-uni">Uni</span>Match
                </span>
              </a>
            </span>

            <!-- hamburger menu  -->
            <button class="hamburger" aria-label="Menu">
              <span class="bar"></span>
              <span class="bar"></span>
              <span class="bar"></span>
            </button>

            <ul class="nav-links">
              <li>
                <a class="hover-underline-animation" href="search.php"
                  >Search Colleges</a
                >
              </li>
              <li>
                <a class="hover-underline-animation" href="about.php">About</a>
              </li>
              <li>
                <a class="hover-underline-animation center" href="profile.php"
                  >Edit Profile</a
                >
              </li>
            </ul>
          </div>
          <div class="right-section">
            <a class="btn btn-primary" href="login.php">Log In</a>
          </div>
        </div>
      </nav>
    </header>

    <div class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header bg-primary text-white">
              <h3 class="mb-0">Login to UniMatch</h3>
            </div>
            <div class="card-body">
              <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
              <?php endif; ?>
              
              <form method="POST" action="login.php">
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="d-grid">
                  <button type="submit" class="btn btn-primary">Login</button>
                </div>
              </form>
              <div class="mt-3 text-center">
                <p>Don't have an account? <a href="signup.php">Register here</a></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="hamburger.js"></script>
  </body>
</html>
