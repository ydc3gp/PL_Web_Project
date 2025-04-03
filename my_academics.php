<?php
require_once __DIR__ . '/../../private/includes/session.php';
require_once __DIR__ . '/../../private/Models/Academics.php';

// Redirect if not logged in
if (!Session::isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';
$academics = new Academics();
$academicData = $academics->getAcademicsByUserId(Session::get('user_id'));

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Convert empty strings to null
    $gpa = $_POST['gpa'] !== '' ? $_POST['gpa'] : null;
    $classRank = $_POST['class_rank'] !== '' ? $_POST['class_rank'] : null;
    $classSize = $_POST['class_size'] !== '' ? $_POST['class_size'] : null;
    $satScore = $_POST['sat_score'] !== '' ? $_POST['sat_score'] : null;
    $actScore = $_POST['act_score'] !== '' ? $_POST['act_score'] : null;
    
    // Validate GPA
    if ($gpa !== null && ($gpa < 0 || $gpa > 5.3)) {
        $error = 'GPA must be between 0.00 and 5.30';
    } 
    // Validate SAT Score
    else if ($satScore !== null && ($satScore < 0 || $satScore > 1600)) {
        $error = 'SAT score must be between 0 and 1600';
    } 
    // Validate ACT Score
    else if ($actScore !== null && ($actScore < 0 || $actScore > 36)) {
        $error = 'ACT score must be between 0 and 36';
    } 
    // Validate class rank and size
    else if ($classRank !== null && $classSize !== null && $classRank > $classSize) {
        $error = 'Class rank cannot be greater than class size';
    } 
    else {
        $result = $academics->updateAcademics(
            Session::get('user_id'),
            $gpa,
            $classRank,
            $classSize,
            $satScore,
            $actScore
        );
        
        if ($result) {
            $success = 'Academic information updated successfully!';
            // Refresh academic data after update
            $academicData = $academics->getAcademicsByUserId(Session::get('user_id'));
        } else {
            $error = 'Failed to update academic information. Please try again.';
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
    <title>UniMatch - Edit My Academics Information</title>
    <meta name="author" content="Andrew Abbott" />
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

            <ul class="nav-links" id="nav-links">
              <li class="">
                <a class="hover-underline-animation center" href="search.php"
                  >Search Colleges</a
                >
              </li>
              <li class="">
                <a class="hover-underline-animation center" href="about.php"
                  >About</a
                >
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
            <a href="profile.php" class="btn btn-outline-primary">Profile</a>
            <a href="my_academics.php" class="btn btn-primary">My Academics</a>
            <a href="my_colleges.php" class="btn btn-outline-primary"
              >My Colleges</a
            >
          </div>
        </div>

        <!-- Right Side (My Academics Information) -->
        <div class="col-md-8">
          <div class="card p-4">
            <h2 class="mb-3">Academic Information</h2>
            
            <?php if (!empty($error)): ?>
              <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
              <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
              <div class="mb-3">
                <label for="gpa" class="form-label">Grade Point Average</label>
                <input
                  id="gpa"
                  name="gpa"
                  type="number"
                  class="form-control"
                  step="0.01"
                  min="0.00"
                  max="5.30"
                  value="<?php echo htmlspecialchars($academicData['gpa'] ?? ''); ?>"
                  aria-label="Grade Point Average"
                />
              </div>

              <div class="mb-3">
                <label class="form-label" for="class_rank">Class Rank</label>
                <input
                  type="number"
                  class="form-control"
                  id="class_rank"
                  name="class_rank"
                  step="1"
                  value="<?php echo htmlspecialchars($academicData['class_rank'] ?? ''); ?>"
                />
              </div>

              <div class="mb-3">
                <label class="form-label" for="class_size">Class Size</label>
                <input
                  type="number"
                  id="class_size"
                  name="class_size"
                  class="form-control"
                  step="1"
                  value="<?php echo htmlspecialchars($academicData['class_size'] ?? ''); ?>"
                />
              </div>

              <div class="mb-3">
                <label class="form-label" for="sat_score">SAT Score</label>
                <input
                  id="sat_score"
                  name="sat_score"
                  type="number"
                  class="form-control"
                  step="10"
                  min="0"
                  max="1600"
                  value="<?php echo htmlspecialchars($academicData['sat_score'] ?? ''); ?>"
                />
              </div>

              <div class="mb-3">
                <label class="form-label" for="act_score">ACT Score</label>
                <input
                  id="act_score"
                  name="act_score"
                  type="number"
                  class="form-control"
                  step="1"
                  min="0"
                  max="36"
                  value="<?php echo htmlspecialchars($academicData['act_score'] ?? ''); ?>"
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
