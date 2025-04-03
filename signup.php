<?php
require_once __DIR__ . '/../../private/includes/session.php';
require_once __DIR__ . '/../../private/Models/User.php';

// Redirect if already logged in
// TODO: redirect to dashboard once it's created
if (Session::isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

	// Password regex validation - at least 1 number, 1 special character, and 6+ characters long
    $passwordRegex = '/^(?=.*[0-9])(?=.*[\W])(?=.{6,}).+$/';
    
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        $error = 'Please fill in all required fields';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match';
    } else if (!preg_match($passwordRegex, $password)) {
		$error = 'Password must be at least 6 characters long and contain at least 1 number and 1 special character';
	} else {
        $user = new User();
        $userId = $user->register($email, $password, $firstName, $lastName);
        
        if ($userId) {
            $success = 'Registration successful! You can now log in.';
        } else {
            $error = 'Registration failed. Email may already be in use.';
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
    <title>UniMatch - Register</title>
    <meta name="author" content="Andrew Abbott and Chris Dai" />
    <meta name="description" content="Register for UniMatch." />
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
              <h3 class="mb-0">Register for UniMatch</h3>
            </div>
            <div class="card-body">
              <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
              <?php endif; ?>
              
              <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
              <?php endif; ?>
              
              <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
					<div class="mb-3">
						<label for="first_name" class="form-label">First Name</label>
						<input type="text" class="form-control" id="first_name" name="first_name" required>
					</div>
					<div class="mb-3">
						<label for="last_name" class="form-label">Last Name</label>
						<input type="text" class="form-control" id="last_name" name="last_name" required>
					</div>
					<div class="mb-3">
						<label for="email" class="form-label">Email</label>
						<input type="email" class="form-control" id="email" name="email" required>
					</div>
					<div class="mb-3">
						<label for="password" class="form-label">Password</label>
						<input type="password" class="form-control" id="password" name="password" required>
					</div>
					<div class="mb-3">
						<label for="confirm_password" class="form-label">Confirm Password</label>
						<input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
					</div>
					<div class="d-grid">
						<button type="submit" class="btn btn-primary" name="submit">Register</button>
					</div>
				</form>


          <div class="mt-3 text-center">
            <p>Already have an account? <a href="login.php">Login here</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="hamburger.js"></script>