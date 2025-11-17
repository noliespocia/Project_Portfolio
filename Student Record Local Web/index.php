<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
    :root {
        /* Navbar */
        --nav-blur: 8px;
        --nav-bg-opacity: 0.4;
        /* Dropdown menus */
        --menu-blur: 15px;
        --menu-bg-opacity: 0.7;
        /* Search */
        --search-blur: 10px;
        --search-bg-opacity: 0.35;
        --search-control-height: 40px;
        --search-font-size: 0.95rem;
        /* Brand glow (navbar brand) */
        --brand-glow-color: rgb(181, 238, 245);
        --brand-glow-strength: 0.75;
        /* Table */
        --table-shadow-opacity: 0.35;
        --table-shadow-y: 8px;
        --table-radius: 12px;
        /* Table glass effect controls */
        --table-glass-opacity: 0.25;  
        --table-glass-blur: 12px;      
        --table-glass-border: rgba(255,255,255,0.25);
        --table-glass-highlight: rgba(255,255,255,0.35);
        /* Title gradient palette: sunset orange → gold */
        --title-grad-1: #ff7e5f; 
        --title-grad-2: #ff8c42; 
        --title-grad-3: #ffb347; 
        --title-grad-4: #ffd166; 
        /* Title shadow (subtle preset) */
        --title-shadow-opacity: 0.35;
        --title-shadow-blur: 12px;
        --title-shadow-y: 6px;
        /* Background video */
        --video-opacity: 0.9; 
    }
    html, body { height: 100%; }
    body { display: flex; flex-direction: column; min-height: 100vh; }
    .main-content { flex: 1 0 auto; }
    /* Relieve font face (add files under assets/fonts/relieve/) */
    @font-face {
        font-family: 'Relieve';
        src: url('assets/fonts/relieve/Relieve.woff2') format('woff2'),
             url('assets/fonts/relieve/Relieve.woff') format('woff'),
             url('assets/fonts/relieve/Relieve.ttf') format('truetype');
        font-weight: 400;
        font-style: normal;
        font-display: swap;
    }
    .aurora-title {
        font-family: 'Great Vibes', cursive;
    }
        .table {
            box-shadow: 0 var(--table-shadow-y) var(--table-shadow-blur) rgba(0,0,0,var(--table-shadow-opacity));
{{ ... }}
            border-radius: var(--table-radius);
{{ ... }}
            overflow: hidden;
        }
        .bg-video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -2; 
            pointer-events: none;
            opacity: var(--video-opacity);
        }
        /* Subtle noise overlay above video, below content */
        .noise-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            pointer-events: none;
            z-index: -1; 
            opacity: 0.18; 
            mix-blend-mode: multiply; 
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='100%' height='100%'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.7' numOctaves='4' stitchTiles='stitch'/></filter><rect width='100%' height='100%' filter='url(%23n)' opacity='1.0'/></svg>");
            background-size: cover;
        }
        /* Fallback background when video cannot play */
        body.no-video {
            background: #000 url(var(--video-poster)) no-repeat center center fixed;
            background-size: cover;
        }
        .aurora-title {
            font-family: 'Great Vibes', cursive;
            font-weight: 400; 
            position: relative;
            padding-bottom: 0; 
            letter-spacing: 0.5px; 
            display: block;
            width: -moz-fit-content;
            width: fit-content;
            margin: 0 auto; 
            /* Solid white title with shadow */
            background: none;
            -webkit-background-clip: initial;
            background-clip: initial;
            color: #ffffff;
            -webkit-text-fill-color: #ffffff;
            -webkit-text-stroke: 0;
            text-shadow:
                0 6px 18px rgba(0,0,0,0.45),
                0 12px 36px rgba(0,0,0,0.25);
            font-size: clamp(42px, 6vw, 96px);
            line-height: 1.1;
        }
        .aurora-title::before { content: none; }
        /* Glowing H2 title */
        .glow-title {
            color: #fff;
            text-shadow: none;
            /* Gradient underline highlight */
            background-image: linear-gradient(90deg, var(--title-grad-1), var(--title-grad-5));
            background-size: 100% 4px;
            background-repeat: no-repeat;
            background-position: 0 100%;
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            /* Soft stroke + subtle shadow for legibility over video/glass */
            -webkit-text-stroke: 1px rgba(255,255,255,0.15);
            text-shadow: 0 var(--title-shadow-y, 6px) var(--title-shadow-blur, 18px) rgba(0,0,0, var(--title-shadow-opacity, 0.35));
                0 0 6px rgba(255,255,255,0.8),
                0 0 12px rgba(255,255,255,0.6),
                0 0 18px rgba(255,255,255,0.4),
                0 0 24px rgba(var(--title-glow-color), var(--title-glow-strength)),
                0 0 36px rgba(var(--title-glow-color), calc(var(--title-glow-strength) * 0.85));
            animation: title-glow-pulse 3s ease-in-out infinite alternate;
        }
        @keyframes title-glow-pulse {
            0% {
                text-shadow:
                    0 0 4px rgba(255,255,255,0.6),
                    0 0 8px rgba(255,255,255,0.4),
                    0 0 12px rgba(255,255,255,0.25),
                    0 0 16px rgba(var(--title-glow-color), calc(var(--title-glow-strength) * 0.6)),
                    0 0 24px rgba(var(--title-glow-color), calc(var(--title-glow-strength) * 0.5));
            }
            100% {
                text-shadow:
                    0 0 8px rgba(255,255,255,0.9),
                    0 0 16px rgba(255,255,255,0.7),
                    0 0 24px rgba(255,255,255,0.5),
                    0 0 32px rgba(var(--title-glow-color), var(--title-glow-strength)),
                    0 0 48px rgba(var(--title-glow-color), calc(var(--title-glow-strength) * 0.9));
            }
        }
        .navbar.navbar-dark.bg-dark {
            background-color: rgba(0, 0, 0, var(--nav-bg-opacity)) !important;
            backdrop-filter: blur(var(--nav-blur));
            -webkit-backdrop-filter: blur(var(--nav-blur));
            box-shadow: 0 2px 10px rgba(0,0,0,0.25);
        }
        .navbar-brand {
            position: relative;
            color: #fff !important;
            text-shadow:
                0 0 6px rgba(255,255,255,0.8),
                0 0 12px rgba(255,255,255,0.6),
                0 0 18px rgba(255,255,255,0.4),
                0 0 24px rgba(0,229,255, var(--brand-glow-strength)),
                0 0 36px rgba(0,229,255, calc(var(--brand-glow-strength) * 0.85));
            animation: brand-glow-pulse 3s ease-in-out infinite alternate;
        }
        @keyframes brand-glow-pulse {
            0% {
                text-shadow:
                    0 0 4px rgba(255,255,255,0.6),
                    0 0 8px rgba(255,255,255,0.4),
                    0 0 12px rgba(255,255,255,0.25),
                    0 0 16px rgba(0,229,255, calc(var(--brand-glow-strength) * 0.6)),
                    0 0 24px rgba(0,229,255, calc(var(--brand-glow-strength) * 0.5));
            }
            100% {
                text-shadow:
                    0 0 8px rgba(255,255,255,0.9),
                    0 0 16px rgba(255,255,255,0.7),
                    0 0 24px rgba(255,255,255,0.5),
                    0 0 32px rgba(0,229,255, var(--brand-glow-strength)),
                    0 0 48px rgba(0,229,255, calc(var(--brand-glow-strength) * 0.9));
            }
        }
        .navbar .form-control {
            background-color: rgba(255,255,255,var(--search-bg-opacity));
            color: #fff;
            border: 1px solid rgba(255,255,255,0.5);
            backdrop-filter: blur(var(--search-blur));
            -webkit-backdrop-filter: blur(var(--search-blur));
            height: var(--search-control-height);
            font-size: var(--search-font-size);
            padding: 0 14px;
            border-radius: 6px;
            box-sizing: border-box;
        }
        .navbar .form-control::placeholder { color: rgba(255,255,255,0.85); }
        .navbar .form-control:focus {
            background-color: rgba(255,255,255,calc(var(--search-bg-opacity) + 0.1));
            color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(255,255,255,0.15);
            border-color: rgba(255,255,255,0.75);
        }
        .navbar .btn[type="submit"] {
            background-color: rgba(0,0,0,var(--search-bg-opacity));
            color: #fff;
            border: 1px solid rgba(255,255,255,0.5);
            backdrop-filter: blur(var(--search-blur));
            -webkit-backdrop-filter: blur(var(--search-blur));
            height: var(--search-control-height);
            font-size: var(--search-font-size);
            padding: 0 14px;
            border-radius: 6px;
            box-sizing: border-box;
        }
        .navbar .btn[type="submit"]:hover,
        .navbar .btn[type="submit"]:focus {
            background-color: rgba(255,255,255,0.15);
            color: #fff;
            border-color: rgba(255,255,255,0.75);
        }
        .dropdown-menu {
            background-color: rgba(0, 0, 0, var(--menu-bg-opacity)) !important;
            backdrop-filter: blur(var(--menu-blur));
            -webkit-backdrop-filter: blur(var(--menu-blur));
            border: 1px solid rgba(255,255,255,0.15);
            box-shadow: 0 6px 20px rgba(0,0,0,0.25);
            color: #fff;
        }
        .dropdown-item { color: #fff; }
        .dropdown-item:hover, .dropdown-item:focus { background-color: rgba(255,255,255,0.1); color: #fff; }
        /* Home feature glass cards */
        .feature-card {
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(10px) saturate(140%);
            -webkit-backdrop-filter: blur(10px) saturate(140%);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 16px;
            padding: 20px;
            color: #fff;
            text-align: center;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.25), 0 8px 24px rgba(0,0,0,0.25);
        }
        .feature-card h5 { font-weight: 700; margin-bottom: 6px; }
        .feature-card p { opacity: 0.9; margin-bottom: 0; }
        .site-footer { background-color: rgba(0,0,0,var(--nav-bg-opacity)); backdrop-filter: blur(var(--nav-blur)); -webkit-backdrop-filter: blur(var(--nav-blur)); border-top: 1px solid rgba(255,255,255,0.15); padding: 14px; position: relative; z-index:2; color:#bbb; flex-shrink: 0; margin-top: 50px; margin-bottom: 1px; width: 104.1%; justify-self: center;  }
        table th, table td {
            text-align: center;
            padding: 12px;
            background-color: transparent; 
            border: 1px solid rgba(255,255,255,0.65); 
            color:rgb(255, 255, 255); 
            font-weight: 500; 
        }
        table thead {
            background-color: transparent; 
        }
        table tbody tr:hover {
            background-color: transparent; 
        }
        .table, .table-bordered {
            background-color: rgba(255,255,255, var(--table-glass-opacity));
            border-color: var(--table-glass-border);
            backdrop-filter: blur(var(--table-glass-blur)) saturate(140%);
            -webkit-backdrop-filter: blur(var(--table-glass-blur)) saturate(140%);
            border-radius: var(--table-radius);
            /* subtle inner highlight and outer shadow for glass depth */
            box-shadow:
                inset 0 1px 0 var(--table-glass-highlight),
                0 8px var(--table-shadow-blur) rgba(0,0,0,var(--table-shadow-opacity));
        }
        .wrapper 
        {
        padding: 30px;
        background-color: transparent; 
        border-radius: 8px;
        box-shadow: none;
        }
        h2 
        {
        font-size: 32px;
        font-weight: 600;
        margin: 16px 0 12px;
        }
        .btn {
        border-radius: 4px;
        font-size: 13px;
        padding: 6px 12px;
        }
        .btn-success {
            background-color: #4CAF50;
            border: none;
        }
        .btn-primary {
            background-color: #007BFF;
            border: none;
        }
        .btn-info {
            background-color: #17A2B8;
            border: none;
        }
        .btn-warning {
            background-color: #FFC107;
            border: none;
        }
        .btn-danger {
            background-color: #DC3545;
            border: none;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .navbar-brand {
            font-size: 24px;
            font-weight: bold;
            color: #fff;
        }
        /* Horizontal infinite scroller */
        .feature-scroller {
          overflow: hidden;
          width: 100%;
          position: relative;
          margin-top: 16px;
        }
        .feature-track {
          display: flex;
          gap: 16px;
          will-change: transform;
          animation: feature-scroll 40s linear infinite;
        }
        @keyframes feature-scroll {
          0%   { transform: translateX(0); }
          100% { transform: translateX(-50%); } /* duplicate cards for seamless loop */
        }

        /* Glass feature cards */
        .feature-card {
          background: rgba(255,255,255,0.12);
          backdrop-filter: blur(10px) saturate(140%);
          -webkit-backdrop-filter: blur(10px) saturate(140%);
          border: 1px solid rgba(255,255,255,0.25);
          border-radius: 16px;
          padding: 14px;
          color: #fff;
          text-align: center;
          box-shadow: inset 0 1px 0 rgba(255,255,255,0.25), 0 8px 24px rgba(0,0,0,0.25);
          width: 220px; /* card width */
          flex: 0 0 auto;
        }

        /* Square image holder */
        .feature-img-wrap {
          width: 100%;
          position: relative;
          padding-top: 100%; /* 1:1 ratio */
          border-radius: 12px;
          overflow: hidden;
          border: 1px solid rgba(255,255,255,0.25);
          background: rgba(255,255,255,0.08);
          margin-bottom: 10px;
        }
        .feature-img-wrap img {
          position: absolute;
          inset: 0;
          width: 100%;
          height: 100%;
          object-fit: cover;
        }
        .feature-card h5 { font-weight: 700; margin: 6px 0 4px; font-size: 1rem; }
        .feature-card p { opacity: 0.9; margin-bottom: 0; font-size: 0.9rem; } 
        /* 5-per-row on extra-large screens */
        @media (min-width: 1200px) {
          .col-xl-20 { flex: 0 0 20%; max-width: 20%; }
        }
        
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
            // Slow motion background video
            var bg = document.querySelector('.bg-video');
            if (bg) {
                try {
                    var rate = 0.5; // adjust to taste (0.2–0.7)
                    bg.defaultPlaybackRate = rate;
                    bg.playbackRate = rate;
                    bg.addEventListener('loadedmetadata', function(){ bg.playbackRate = rate; });
                    bg.addEventListener('ratechange', function(){ if (bg.playbackRate !== rate) bg.playbackRate = rate; });
                    // In case autoplay starts before JS runs
                    if (!bg.paused) { bg.playbackRate = rate; }
                } catch (e) { /* no-op */ }
            }
        });
        function promptAndGo(action) {
            var id = prompt('Enter Student Id to ' + action + ':');
            if (!id) { return; }
            id = id.trim();
            if (!id) { return; }
            if (action === 'delete') {
                if (!confirm('Are you sure you want to delete Student Id ' + id + '?')) return;
                window.location.href = 'delete.php?studid=' + encodeURIComponent(id);
            } else {
                window.location.href = 'update.php?studid=' + encodeURIComponent(id);
            }
        }
        function downloadCsv() {
            var qs = window.location.search || '';
            window.location.href = 'export_csv.php' + qs;
        }
    </script>
</head> 
<body>
    <video class="bg-video" autoplay muted loop playsinline preload="auto" poster="assets/bg/poster.jpg">
        <source src="assets/bg/bg6.mp4" type="video/mp4">
    </video>
    <div class="noise-overlay" aria-hidden="true"></div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="index.php">GAMEPOINT.Tech</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <form method="GET" class="form-inline my-2 my-lg-0 mr-auto">
                <input class="form-control mr-sm-2" type="text" name="search" placeholder="Search by Firstname or Lastname..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Search Records</button>
            </form>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item <?php echo (!isset($_GET['filter']) || strtolower($_GET['filter']) === 'home') ? 'active' : ''; ?>">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="moreDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Menu
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="moreDropdown">
                        <a class="dropdown-item" href="?filter=all">All Records</a>
                        <a class="dropdown-item" href="?filter=82.6">Finals = 82.6</a>
                        <a class="dropdown-item" href="?filter=75">Prelim = 75</a>
                        <a class="dropdown-item" href="?filter=final_grade_firstname">Final Grade with First Name</a>
                        <a class="dropdown-item" href="?filter=final_grade_gte">Final Grade ≥ 87</a>
                        <a class="dropdown-item" href="?filter=GPA">GPA</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="?filter=uncomputed">Uncomputed (Final Grade/GPA)</a>
                        <a class="dropdown-item" href="?filter=computed">Computed (Final Grade/GPA)</a>
                    </div>
    </main>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="optionsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Options
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="optionsDropdown">
                        <a class="dropdown-item" href="create.php">Add Record</a>
                        <a class="dropdown-item" href="#" onclick="downloadCsv(); return false;">Download CSV</a>
                        <a class="dropdown-item" href="#" onclick="promptAndGo('update'); return false;">Update Student…</a>
                        <a class="dropdown-item text-danger" href="#" onclick="promptAndGo('delete'); return false;">Delete Student…</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="credits.php">Credits</a>
                </li>
            </ul>
        </div>
    </nav>
    <main class="main-content">
    <div class="wrapper" style="padding-top: 80px;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3">
                        <h1 class="text-center aurora-title">Student Records</h1>
                        <h2 class="text-center text-white">"This Features CRUD and Export"</h2>                        
                            <div class="d-flex flex-wrap justify-content-center align-items-center gap-2 mb-3">                                    
                            <?php
                                    require_once "config.php";
                                    if (!function_exists('gpa_description')) {
                                        function gpa_description($avg)
                                        {
                                            if ($avg === null) return '';
                                            $avg = floatval($avg);
                                            if ($avg >= 99 && $avg <= 100) return 'Excellent';
                                            if ($avg >= 96 && $avg <= 98) return '------';
                                            if ($avg >= 93 && $avg <= 95) return '------';
                                            if ($avg >= 90 && $avg <= 92) return 'Very Good';
                                            if ($avg >= 87 && $avg <= 89) return '------';
                                            if ($avg >= 84 && $avg <= 86) return 'Above Average';
                                            if ($avg >= 81 && $avg <= 83) return '------';
                                            if ($avg >= 78 && $avg <= 80) return 'Average';
                                            if ($avg >= 75 && $avg <= 77) return 'Passing';
                                            if ($avg <= 74) return 'Conditional';
                                            return '';
                                        }
                                    }
                            
                                    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
                                    $filter = isset($_GET['filter']) ? strtolower($_GET['filter']) : '';
                                    $isHome = ($filter === 'home' || $filter === '');
                                    $showPrelimOnly = false;
                                    $showIdLastFirstFinal = false;  
                                    $showIdFirstFinalOnly = false; 
                                    $isDirectQuery = false;
                                    $result = false;

                                    if ($isHome) {
                                        $result = false;
                                    
                                        $features = [
                                            ['img' => 'assets/features/feature1.png',  'title' => 'Create', 'desc' => 'Create New Student Record'],
                                            ['img' => 'assets/features/feature2.png',  'title' => 'Read', 'desc' => 'View Student Records'],
                                            ['img' => 'assets/features/feature3.png',  'title' => 'Update',  'desc' => 'Update Student Record'],
                                            ['img' => 'assets/features/feature4.png',  'title' => 'Delete',  'desc' => 'Delete Student Record'],
                                            ['img' => 'assets/features/feature5.png',  'title' => 'Export',  'desc' => 'Export Student Records to CSV'],
                                        ];
                                        $features = array_slice($features, 0, 5);
                                        
                                        echo '<div class="row justify-content-center align-items-stretch text-center mt-4">';
                                        foreach ($features as $f) {
                                            $img  = htmlspecialchars($f['img']);
                                            $tit  = htmlspecialchars($f['title']);
                                            $desc = htmlspecialchars($f['desc']);
                                            echo '<div class="col-12 col-md-6 col-lg-4 col-xl-20 mb-4 d-flex">';
                                            echo '  <div class="feature-card w-100 h-100 d-flex flex-column">';
                                            echo '    <div class="feature-img-wrap"><img src="'.$img.'" alt="'.$tit.'" onerror="this.src=\'data:image/svg+xml;utf8,'.rawurlencode('<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'400\'><rect width=\'100%\' height=\'100%\' fill=\'rgba(255,255,255,0.06)\'/><text x=\'50%\' y=\'50%\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'rgba(255,255,255,0.7)\' font-family=\'Arial\' font-size=\'18\'>Feature</text></svg>').'\';" /></div>';
                                            echo '    <h5>'.$tit.'</h5>';
                                            echo '    <p>'.$desc.'</p>';
                                            echo '  </div>';
                                            echo '</div>';
                                        }
                                        echo '</div>';
                                    }
                                    if ($filter === 'gpa') {
                                        $sql = "SELECT studid, lastname, firstname, ROUND((prelim + midterm + finals)/3, 2) AS GPA FROM studentrecord_tbl ORDER BY GPA DESC";
                                        $result = mysqli_query($link, $sql);
                                        $isDirectQuery = true;
                                    }

                                    if ($search !== '') {
                                        $sql = "SELECT * FROM studentrecord_tbl WHERE firstname LIKE ? OR lastname LIKE ? ORDER BY final_grade DESC";
                                    
                                        if ($stmt = mysqli_prepare($link, $sql)) {
                                            $searchParam = "%{$search}%";
                                            mysqli_stmt_bind_param($stmt, "ss", $searchParam, $searchParam);
                                            mysqli_stmt_execute($stmt);
                                            $result = mysqli_stmt_get_result($stmt);
                                        } else {
                                            echo "Oops! Something went wrong. Please try again later.";
                                            $result = false;
                                        }
                                    } else if (!$isHome && $filter !== 'gpa') {
                                        if ($filter === '82.6') {
                                            $sql = "SELECT * FROM studentrecord_tbl WHERE finals = 82.6 ORDER BY final_grade DESC";
                                            $result = mysqli_query($link, $sql);
                                        } elseif ($filter === '75') {
                                            $sql = "SELECT studid, lastname, firstname, final_grade FROM studentrecord_tbl WHERE prelim = 75 ORDER BY final_grade DESC";
                                            $result = mysqli_query($link, $sql);
                                            $showIdLastFirstFinal = true;
                                        } elseif ($filter === 'final_grade_firstname') {
                                            $sql = "SELECT studid, final_grade, firstname FROM studentrecord_tbl ORDER BY final_grade DESC";
                                            $result = mysqli_query($link, $sql);
                                            $showIdFirstFinalOnly = true;
                                        } elseif ($filter === 'final_grade_gte') {
                                            $sql = "SELECT * FROM studentrecord_tbl WHERE final_grade >= 87 ORDER BY final_grade DESC";
                    $result = mysqli_query($link, $sql);
                } elseif ($filter === 'uncomputed') {
                    $sql = "SELECT * FROM studentrecord_tbl 
                            WHERE final_grade = 0 OR prelim = 0 OR midterm = 0 OR finals = 0 
                            ORDER BY studid DESC";
                    $result = mysqli_query($link, $sql);
                } elseif ($filter === 'computed') {
                    $sql = "SELECT *, ROUND((prelim + midterm + finals)/3, 2) AS GPA FROM studentrecord_tbl 
                            WHERE final_grade > 0 AND prelim > 0 AND midterm > 0 AND finals > 0 
                            ORDER BY studid DESC";
                    $result = mysqli_query($link, $sql);
                } else {
                    $sql = "SELECT * FROM studentrecord_tbl ORDER BY final_grade DESC";
                    $result = mysqli_query($link, $sql);
                }
                
                }
                
                if ($result && mysqli_num_rows($result) > 0) {
                                        echo '<table class="table table-bordered table-striped justify-content-center">';
                                        echo '<thead><tr>';
                                        if ($showPrelimOnly) {
                                            echo '<th>Prelim</th><th>Final Grade</th>';
                                        } elseif ($filter === 'gpa') {
                                            echo '<th>Student Id</th>';
                                            echo '<th>Lastname</th>';
                                            echo '<th>Firstname</th>';
                                            echo '<th>GPA</th>';
                                            echo '<th>Description</th>';
                                        } elseif ($showIdFirstFinalOnly) {
                                            echo '<th>Student Id</th>';
                                            echo '<th>Final Grade</th>';
                                            echo '<th>Firstname</th>';
                                        } elseif ($showIdLastFirstFinal) {
                                            echo '<th>Student Id</th>';
                                            echo '<th>Lastname</th>';
                                            echo '<th>Firstname</th>';
                                            echo '<th>Final Grade</th>';
                                        } elseif ($filter === 'final_grade_gte') {
                                            echo '<th>Student Id</th>';
        										 echo '<th>Lastname</th>';
                                            echo '<th>Firstname</th>';
                                            echo '<th>Prelim</th>';
                                            echo '<th>Midterm</th>';
                                            echo '<th>Finals</th>';
                                            echo '<th>Final_grade</th>';
                                        } elseif ($filter === 'computed') {
                                            echo '<th>Student Id</th>';
                                            echo '<th>Lastname</th>';
                                            echo '<th>Firstname</th>';
                                            echo '<th>Prelim</th>';
                                            echo '<th>Midterm</th>';
                                            echo '<th>Finals</th>';
                                            echo '<th>Final_grade</th>';
                                            echo '<th>GPA</th>';
                                            echo '<th>Description</th>';
                                        } else {
                                            echo '<th>Student Id</th>';
                                            echo '<th>Lastname</th>';
                                            echo '<th>Firstname</th>';
                                            echo '<th>Prelim</th>';
                                            echo '<th>Midterm</th>';
                                            echo '<th>Finals</th>';
                                            echo '<th>Final_grade</th>';
                                        }
                                        echo '</tr></thead><tbody>';
                                    
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo "<tr>";
                                            if ($showPrelimOnly) {
                                                echo "<td>{$row['prelim']}</td><td>{$row['final_grade']}</td>";
                                            } elseif ($filter === 'gpa') {
                                                echo "<td>{$row['studid']}</td>";
                                                echo "<td>{$row['lastname']}</td>";
                                                echo "<td>{$row['firstname']}</td>";
                                                echo "<td>{$row['GPA']}</td>";
                                                $desc = gpa_description($row['GPA']);
                                                echo "<td>{$desc}</td>";
                                            } elseif ($showIdFirstFinalOnly) {
                                                echo "<td>{$row['studid']}</td>";
                                                echo "<td>{$row['final_grade']}</td>";
                                                echo "<td>{$row['firstname']}</td>";
                                            } elseif ($showIdLastFirstFinal) {
                                                echo "<td>{$row['studid']}</td>";
                                                echo "<td>{$row['lastname']}</td>";
                                                echo "<td>{$row['firstname']}</td>";
                                                echo "<td>{$row['final_grade']}</td>";
                                            } elseif ($filter === 'final_grade_gte') {
                                                echo "<td>{$row['studid']}</td>";
                                                echo "<td>{$row['lastname']}</td>";
                                                echo "<td>{$row['firstname']}</td>";
                                                echo "<td>{$row['prelim']}</td>";
                                                echo "<td>{$row['midterm']}</td>";
                                                echo "<td>{$row['finals']}</td>";
                                                echo "<td>{$row['final_grade']}</td>";
                                            } elseif ($filter === 'computed') {
                                                echo "<td>{$row['studid']}</td>";
                                                echo "<td>{$row['lastname']}</td>";
                                                echo "<td>{$row['firstname']}</td>";
                                                echo "<td>{$row['prelim']}</td>";
                                                echo "<td>{$row['midterm']}</td>";
                                                echo "<td>{$row['finals']}</td>";
                                                echo "<td>{$row['final_grade']}</td>";
                                                echo "<td>{$row['GPA']}</td>";
                                                $desc = gpa_description($row['GPA']);
                                                echo "<td>{$desc}</td>";
                                            } else {
                                                echo "<td>{$row['studid']}</td>";
                                                echo "<td>{$row['lastname']}</td>";
                                                echo "<td>{$row['firstname']}</td>";
                                                echo "<td>{$row['prelim']}</td>";
                                                echo "<td>{$row['midterm']}</td>";
                                                echo "<td>{$row['finals']}</td>";
                                                echo "<td>{$row['final_grade']}</td>";
                                            }
                                            echo "</tr>";
                                        }
                                        echo "</tbody>";
                                        echo "</table>";
                                        echo "</div>";
                                    
                                        if ($isDirectQuery) {
                                            mysqli_free_result($result);
                                        }
                                    } else {
                                        if (!$isHome) {
                                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                                        }
                                    }
                            mysqli_close($link);
                        ?>
                    </div>
                </div>        
            </div>
        </div>
    </div>
    <footer class="site-footer">
        <div class="container d-flex flex-wrap justify-content-center align-items-center text-white-50">
            <div><small>© <?php echo date('Y'); ?> GAMEPOINT.Tech | Student Records. All rights reserved.</small></div>
        </div>
    </footer>
</body>
</html>