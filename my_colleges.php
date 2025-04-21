<?php
require_once __DIR__ . '/../../private/includes/session.php';
require_once __DIR__ . '/../../private/includes/db.php';

Session::start();

if (!Session::isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = Session::get('user_id');
$db = Database::getInstance()->getConnection();

$stmt = $db->prepare("SELECT college_name, location FROM sprint3_saved_colleges WHERE user_id = :user_id");
$stmt->execute([':user_id' => $userId]);
$savedColleges = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['college_name'], $_POST['location'])) {
  $stmt = $db->prepare("DELETE FROM sprint3_saved_colleges WHERE user_id = :user_id AND college_name = :college_name AND location = :location");
  $stmt->execute([
      ':user_id' => $userId,
      ':college_name' => $_POST['college_name'],
      ':location' => $_POST['location']
  ]);
  // Redirect to refresh page
  header("Location: my_colleges.php");
  exit;
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
    <style>
        body {
        font-family: Arial, sans-serif;
        background-color: #f3f3f3;
        margin: 0;
        padding: 20px;
      }

      .college-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
        max-width: 600px;
        margin: 0 auto;
      }

      .college-card {
        background-color: white;
        border-radius: 8px;
        padding: 16px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .college-info {
        display: flex;
        flex-direction: column;
      }

      .college-info h3 {
        margin: 0;
        font-size: 18px;
      }

      .college-info p {
        margin: 4px 0 0 0;
        font-size: 14px;
        color: #555;
      }

      .unfavorite-form button {
        background-color: crimson;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
      }

      .unfavorite-form button:hover {
        background-color: crimson;
      }
    </style>
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
    <div class="container mt-4"></div>

    <!-- Page Content -->
    <!-- Right content area -->
    <div class="container mt-4">
          <h2 class="mb-4">My Favorited Colleges</h2>

          <?php if (empty($savedColleges)): ?>
            <p>You haven't favorited any colleges yet. Head to the <a href="search.php">Search</a> page to start exploring!</p>
          <?php else: ?>
            <div class="college-list">
              <?php foreach ($savedColleges as $college): ?>
                <div class="college-card">
                  <div class="college-info">
                    <h3><?= htmlspecialchars($college['college_name']) ?></h3>
                    <p><?= htmlspecialchars($college['location']) ?></p>
                  </div>
                  <form method="POST" class="unfavorite-form">
                    <input type="hidden" name="college_name" value="<?= htmlspecialchars($college['college_name']) ?>">
                    <input type="hidden" name="location" value="<?= htmlspecialchars($college['location']) ?>">
                    <button type="submit">Unfavorite</button>
                  </form>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
  </body>
</html>

