<?php
require_once 'includes/session.php';
require_once 'src/Models/User.php';

// Redirect if not logged in
if (!Session::isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';
$user = new User();
$userData = $user->getUserById(Session::get('user_id'));

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone_number'] ?? '';
    $state = $_POST['state_residence'] ?? '';
    
    if (empty($firstName) || empty($lastName) || empty($email)) {
        $error = 'Please fill in all required fields';
    } else {
        $result = $user->updateProfile(Session::get('user_id'), $firstName, $lastName, $email, $phone, $state);
        
        if ($result) {
            $success = 'Profile updated successfully!';
            // Refresh user data after update
            $userData = $user->getUserById(Session::get('user_id'));
        } else {
            $error = 'Failed to update profile. Please try again.';
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
    <title>UniMatch - Edit My Profile Information</title>
    <meta name="author" content="Andrew Abbott, navbar by Chris Dai" />
    <meta name="description" content="Find your university match." />
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
    <link rel="stylesheet" type="text/css" href="styles/editaccount.css"/>
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
            <?php if (Session::isLoggedIn()): ?>
              <a class="btn btn-danger" href="logout.php">Log Out</a>
            <?php else: ?>
              <a class="btn btn-primary" href="login.php">Log In</a>
            <?php endif; ?>
          </div>
        </div>
      </nav>
    </header>
    <div class="container mt-4">
      <h1 class="mb-4 text-center">Edit Account Information</h1>

      <div class="row">
        <!-- Left Side (Navigation) -->
        <div class="col-md-4">
          <div class="d-grid gap-3">
            <a href="profile.php" class="btn btn-primary">Profile</a>
            <a href="my_academics.php" class="btn btn-outline-primary"
              >My Academics</a
            >
            <a href="my_colleges.php" class="btn btn-outline-primary"
              >My Colleges</a
            >
          </div>
        </div>

        <!-- Right Side (Profile Information) -->
        <div class="col-md-8">
          <div class="card p-4">
            <h2 class="mb-3">Profile Information</h2>
            
            <?php if (!empty($error)): ?>
              <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
              <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
              <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input
                  type="text"
                  class="form-control"
                  id="first_name"
                  name="first_name"
                  value="<?php echo htmlspecialchars($userData['first_name'] ?? ''); ?>"
                  aria-label="First Name"
                  required
                />
              </div>

              <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input
                  type="text"
                  class="form-control"
                  id="last_name"
                  name="last_name"
                  value="<?php echo htmlspecialchars($userData['last_name'] ?? ''); ?>"
                  aria-label="Last Name"
                  required
                />
              </div>

              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                  type="email"
                  class="form-control"
                  id="email"
                  name="email"
                  value="<?php echo htmlspecialchars($userData['email'] ?? ''); ?>"
                  aria-label="Email"
                  required
                />
              </div>

              <div class="mb-3">
                <label for="phone_number" class="form-label"
                  >Phone Number</label
                >
                <input
                  type="tel"
                  class="form-control"
                  id="phone_number"
                  name="phone_number"
                  pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                  value="<?php echo htmlspecialchars($userData['phone_number'] ?? ''); ?>"
                  aria-label="Phone Number"
                />
              </div>

              <div class="mb-3">
                <label for="state_residence" class="form-label"
                  >State Residence</label
                >
                <input
                  type="text"
                  class="form-control"
                  id="state_residence"
                  name="state_residence"
                  value="<?php echo htmlspecialchars($userData['state_residence'] ?? ''); ?>"
                  aria-label="State Residence"
                />
              </div>

              <button type="submit" class="btn btn-primary w-100">
                Save Changes
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script src="hamburger.js"></script>
  </body>
</html>
