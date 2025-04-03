<?php
require_once __DIR__ . '/../../private/includes/session.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>UniMatch - Home</title>
    <meta name="author" content="Chris Dai" />
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
    <!-- Link LESS  -->
    <link rel="stylesheet/less" type="text/css" href="styles/navbar.less" />
    <link rel="stylesheet/less" type="text/css" href="styles/home.less" />
    <link rel="stylesheet/less" type="text/css" href="styles/style.less" />
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
      <!-- Working URL  -->
      <!-- https://cs4640.cs.virginia.edu/ydc3gp/PL_Web_Project/index.php -->
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

    <!-- Main content  -->
    <main class="">
      <div class="hero">
        <div class="hero-left">
          <div class="hero-text">
            <h1 class="">Find Your Perfect Fit</h1>
            <p>
              Our goal is to simplify the college application process by helping
              students find college that meet all of their preferences. We aim
              to reduce stress and give students a balance of safety, target,
              and reach schools.
            </p>
            <a
              class="btn btn-primary rounded-4 view-colleges-btn"
              href="search.php"
              >View Colleges</a
            >
          </div>
        </div>
        <div class="hero-right">
          <div class="square">
            <div class="square-img"></div>
          </div>
        </div>
      </div>
    </main>

    <script src="hamburger.js"></script>
  </body>
</html>
