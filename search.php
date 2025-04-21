<?php
require_once __DIR__ . '/../../private/includes/session.php';
require_once __DIR__ . '/../../private/Models/College.php';

// Initialize filters
$filters = [];
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!empty($_GET['state'])) {
        $filters['state'] = $_GET['state'];
    }
    if (!empty($_GET['type'])) {
        $filters['type'] = $_GET['type'];
    }
    if (!empty($_GET['max_tuition'])) {
        $filters['max_tuition'] = $_GET['max_tuition'];
    }
}

// Get colleges based on filters
$college = new College();
$colleges = empty($filters) ? $college->getAllColleges() : $college->getCollegesByFilter($filters);

// Get unique states for filter dropdown
$states = [];
$allColleges = $college->getAllColleges();
foreach ($allColleges as $c) {
    if (!empty($c['state']) && !in_array($c['state'], $states)) {
        $states[] = $c['state'];
    }
}
sort($states);

// Get unique school types for filter dropdown
$schoolTypes = [];
foreach ($allColleges as $c) {
    if (!empty($c['school_type']) && !in_array($c['school_type'], $schoolTypes)) {
        $schoolTypes[] = $c['school_type'];
    }
}
sort($schoolTypes);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>UniMatch - Search Colleges</title>
    <meta name="author" content="Andrew Abbott and Chris Dai" />
    <meta name="description" content="Search colleges on UniMatch." />
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
    <!-- Link LESS -->
    <link rel="stylesheet/less" type="text/css" href="styles/style.less" />
    <link rel="stylesheet/less" type="text/css" href="styles/navbar.less" />
    <script src="https://cdn.jsdelivr.net/npm/less"></script>

    <!-- Import Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet"
    />
    <style>
        .college-card {
            height: 100%;
            transition: transform 0.3s;
        }
        .college-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .filter-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .college-rank {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #0768f7;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
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

                    <!-- hamburger menu -->
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

    <div class="container mt-5">
    <h1>Search Colleges</h1>

        <form method="get" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">State</label>
            <select name="state" class="form-select">
            <option value="">All States</option>
            <?php foreach ($states as $state): ?>
                <option value="<?= htmlspecialchars($state) ?>" <?= ($_GET['state'] ?? '') === $state ? 'selected' : '' ?>>
                <?= htmlspecialchars($state) ?>
                </option>
            <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Type</label>
            <select name="type" class="form-select">
            <option value="">All Types</option>
            <?php foreach ($schoolTypes as $type): ?>
                <option value="<?= htmlspecialchars($type) ?>" <?= ($_GET['type'] ?? '') === $type ? 'selected' : '' ?>>
                <?= htmlspecialchars($type) ?>
                </option>
            <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Max Tuition</label>
            <input type="number" name="max_tuition" class="form-control" value="<?= htmlspecialchars($_GET['max_tuition'] ?? '') ?>">
        </div>
        <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
        </form>

        <div>
        <?php if (empty($colleges)): ?>
            <p>No colleges found.</p>
        <?php else: ?>
            <?php foreach ($colleges as $c): ?>
            <div class="card mb-3">
                <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($c['name']) ?></h5>
                <p class="card-text">State: <?= htmlspecialchars($c['state']) ?></p>
                <p class="card-text">Type: <?= htmlspecialchars($c['type']) ?></p>
                <p class="card-text">Tuition: $<?= number_format($c['tuition']) ?></p>

                <?php if (Session::isLoggedIn()): ?>
                    <button class="btn btn-outline-primary favorite-button"
                            data-college-name="<?= htmlspecialchars($c['name']) ?>"
                            data-location="<?= htmlspecialchars($c['state']) ?>">
                    🤍 Favorite
                    </button>
                    <div class="favorite-status text-success mt-2" style="display: none;"></div>
                <?php else: ?>
                    <p><em>Log in to save favorites.</em></p>
                <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>

    <script src="hamburger.js"></script>
    <script>
    document.querySelectorAll('.favorite-button').forEach(button => {
      button.addEventListener('click', function () {
        const name = this.dataset.collegeName;
        const location = this.dataset.location;
        const statusDiv = this.nextElementSibling;

        fetch('favorite_college.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `college_name=${encodeURIComponent(name)}&location=${encodeURIComponent(location)}`
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-success');
            this.innerText = '❤️ Favorited';
            statusDiv.textContent = data.message;
            statusDiv.style.display = 'block';
          } else {
            statusDiv.textContent = data.message;
            statusDiv.classList.remove('text-success');
            statusDiv.classList.add('text-danger');
            statusDiv.style.display = 'block';
          }
        });
      });
    });
  </script>
</body>
</html>
