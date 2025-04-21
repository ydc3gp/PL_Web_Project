<?php
require_once 'includes/session.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>UniMatch - About</title>
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
    <link rel="stylesheet/less" type="text/css" href="styles/tooltip.less">
    <script src="https://cdn.jsdelivr.net/npm/less"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

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
              <a href="index.html">
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
                <a class="hover-underline-animation center" href="search.html"
                  >Search Colleges</a
                >
              </li>
              <li class="">
                <a class="hover-underline-animation center" href="about.html"
                  >About</a
                >
              </li>
              <li>
                <a class="hover-underline-animation center" href="profile.html"
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

    <!-- Main content -->
    <main>
      <div class="container mt-5">
        <div class="row justify-content-center">
          <div class="col-md-10">
            <div class="card shadow mb-5">
              <div class="card-body p-4">
                <h1 class="mb-4">About UniMatch</h1>
                
                <p class="lead">
                  UniMatch is designed to simplify the college search process by helping students find universities that match their preferences and academic profile.
                </p>
                
                <h2 class="mt-4 mb-3">Our Mission</h2>
                <p>
                  Our goal is to reduce the stress of the college application process by providing clear, organized information about universities across the United States. 
                  We believe every student deserves access to comprehensive college data to make informed decisions about their educational future.
                </p>
                
                <h2 class="mt-4 mb-3">Database Statistics</h2>
                <div class="row mt-4" id="stats-loading">
                  <div class="col-12 text-center">
                    <div class="spinner-border text-primary" role="status">
                      <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading database statistics...</p>
                  </div>
                </div>
                
                <div class="row mt-4" id="stats-container" style="display: none;">
                  <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white text-center h-100">
                      <div class="card-body">
                        <h3 class="display-4" id="total-colleges">-</h3>
                        <p class="card-text">Total Colleges</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white text-center h-100">
                      <div class="card-body">
                        <h3 class="display-4" id="public-colleges">-</h3>
                        <p class="card-text">Public Colleges</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 mb-3">
                    <div class="card bg-info text-white text-center h-100">
                      <div class="card-body">
                        <h3 class="display-4" id="private-colleges">-</h3>
                        <p class="card-text">Private Colleges</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-dark text-center h-100">
                      <div class="card-body">
                        <h3 class="display-4" id="proprietary-colleges">-</h3>
                        <p class="card-text">Proprietary Colleges</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 text-center mt-3">
                    <small class="text-muted" id="last-updated">Last updated: -</small>
                  </div>
                </div>
                
                <h2 class="mt-4 mb-3">Features</h2>
                <ul class="list-group list-group-flush mb-4">
                  <li class="list-group-item"><i class="bi bi-search"></i> Search through our comprehensive database of colleges</li>
                  <li class="list-group-item"><i class="bi bi-sliders"></i> Filter colleges by state, tuition cost, and type</li>
                  <li class="list-group-item"><i class="bi bi-star"></i> Save your favorite colleges to your personal list</li>
                  <li class="list-group-item"><i class="bi bi-graph-up"></i> Track your academic progress and match with suitable colleges</li>
                </ul>
                
                <h2 class="mt-4 mb-3">Contact Us</h2>
                <p>
                  Have questions or suggestions? Feel free to reach out to our team:
                  <br>
                  Email: <a href="mailto:contact@unimatch.com">contact@unimatch.com</a>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <script src="hamburger.js"></script>
    <script src="js/user-data.js"></script>

    <script>
      $(document).ready(function() {
        // Fetch and display database statistics
        $.ajax({
          url: 'api/get_database_stats.php',
          method: 'GET',
          dataType: 'json',
          success: function(data) {
            $('#stats-loading').hide();
            $('#stats-container').show();
            $('#total-colleges').text(data.total_colleges);
            $('#public-colleges').text(data.public_colleges);
            $('#private-colleges').text(data.private_colleges);
            $('#proprietary-colleges').text(data.proprietary_colleges);
            $('#last-updated').text('Last updated: ' + data.last_updated);
          },
          error: function() {
            $('#stats-loading').hide();
            alert('Failed to load database statistics.');
          }
        });

      const tooltipData = {
        'public-colleges': 'Public colleges are primarily funded by state governments and typically offer lower tuition for in-state residents. They are governed by state-appointed boards and prioritize serving their state\'s educational needs.',
        'private-colleges': 'Private colleges are primarily funded through tuition, endowments, and donations. They are typically governed by independent boards and often have smaller class sizes with more personalized attention.',
        'proprietary-colleges': 'Proprietary (for-profit) colleges are privately owned businesses that offer practical and career-focused education. They operate to generate profit for owners and shareholders while providing educational services.'
      };
      
      // add tooltips to each card
      Object.keys(tooltipData).forEach(function(id) {
        const $card = $(`#${id}`).closest('.card');
        
        // create tooltip element
        const $tooltip = $('<div>')
          .addClass('college-type-tooltip')
          .html(tooltipData[id])
          .hide();
        
        $card.append($tooltip);
        
        // hover events
        $card.hover(
          function() { // Mouse enter
            const $t = $(this).find('.college-type-tooltip');
            $t.fadeIn(200);
          },
          function() { // Mouse leave
            const $t = $(this).find('.college-type-tooltip');
            $t.fadeOut(200);
          }
        );
      });
      });
    </script>
  </body>
</html>