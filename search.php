<?php
require_once 'includes/session.php';
require_once 'src/Models/College.php';

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
        <h1 class="mb-4">Search Colleges</h1>
        
        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="search.php" class="row g-3">
                <div class="col-md-3">
                    <label for="state" class="form-label">State</label>
                    <select class="form-select" id="state" name="state">
                        <option value="">All States</option>
                        <?php foreach ($states as $state): ?>
                            <option value="<?php echo htmlspecialchars($state); ?>" <?php echo (isset($filters['state']) && $filters['state'] === $state) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($state); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="type" class="form-label">School Type</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">All Types</option>
                        <?php foreach ($schoolTypes as $type): ?>
                            <option value="<?php echo htmlspecialchars($type); ?>" <?php echo (isset($filters['type']) && $filters['type'] === $type) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars(str_replace('-', ' ', ucwords($type))); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="max_tuition" class="form-label">Max Tuition ($)</label>
                    <input type="number" class="form-control" id="max_tuition" name="max_tuition" value="<?php echo htmlspecialchars($filters['max_tuition'] ?? ''); ?>">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                </div>
            </form>
        </div>
        
        <!-- Results Count -->
        <p class="mb-4">Found <?php echo count($colleges); ?> colleges matching your criteria.</p>
        
        <!-- Colleges Grid -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
            <?php foreach ($colleges as $college): ?>
                <div class="col">
                    <div class="card college-card h-100">
                        <?php if (!empty($college['ranking_display_rank']) && $college['ranking_display_rank'] !== '-1'): ?>
                            <div class="college-rank">
                                #<?php echo htmlspecialchars($college['ranking_display_rank']); ?>
                            </div>
                        <?php endif; ?>


                        <div class="card-body">
                          <h5 class="card-title"><?php echo htmlspecialchars($college['name']); ?></h5>
                          <h6 class="card-subtitle mb-2 text-muted">
                              <?php echo htmlspecialchars($college['city'] . ', ' . $college['state'] . ' ' . $college['zipcode']); ?>
                          </h6>
                          <p class="card-text">
                              <strong>Type:</strong> <?php echo $college['is_public'] ? 'Public' : 'Private'; ?><br>
                              <?php if (!empty($college['tuition'])): ?>
                                  <strong>Tuition:</strong> $<?php echo number_format((float)$college['tuition']); ?><br>
                              <?php endif; ?>
                              <?php if (!empty($college['cost_after_aid'])): ?>
                                  <strong>Cost After Aid:</strong> $<?php echo number_format((float)$college['cost_after_aid']); ?><br>
                              <?php endif; ?>
                              <?php if (!empty($college['acceptance_rate'])): ?>
                                  <strong>Acceptance Rate:</strong> <?php echo htmlspecialchars($college['acceptance_rate']); ?>%<br>
                              <?php endif; ?>
                              <?php if (!empty($college['enrollment'])): ?>
                                  <strong>Enrollment:</strong> <?php echo number_format((float)$college['enrollment']); ?> students<br>
                              <?php endif; ?>
                              <?php if (!empty($college['hs_gpa_avg'])): ?>
                                  <strong>Avg. GPA:</strong> <?php echo htmlspecialchars($college['hs_gpa_avg']); ?><br>
                              <?php endif; ?>
                              <?php if (!empty($college['sat_avg'])): ?>
                                  <strong>Avg. SAT:</strong> <?php echo htmlspecialchars($college['sat_avg']); ?><br>
                              <?php endif; ?>
                          </p>
                          <?php if (!empty($college['test_avg_range_1']) && $college['test_avg_range_1'] !== 'N/A'): ?>
                              <p class="card-text">
                                  <strong>SAT Range:</strong> <?php echo htmlspecialchars($college['test_avg_range_1']); ?>
                              </p>
                          <?php endif; ?>
                          <?php if (!empty($college['test_avg_range_2']) && $college['test_avg_range_2'] !== 'N/A'): ?>
                              <p class="card-text">
                                  <strong>ACT Range:</strong> <?php echo htmlspecialchars($college['test_avg_range_2']); ?>
                              </p>
                          <?php endif; ?>
                      </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="hamburger.js"></script>
</body>
</html>
