<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Credits</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
    :root {
        --nav-blur: 8px;
        --nav-bg-opacity: 0.4;
        --menu-blur: 15px;
        --menu-bg-opacity: 0.7;
        --search-blur: 10px;
        --search-bg-opacity: 0.35;
        --brand-glow-color: rgb(181, 238, 245);
        --brand-glow-strength: 0.75;
        --video-opacity: 0.9;
    }
    html, body { height: 100%; }
    body { display: flex; flex-direction: column; min-height: 100vh; }
    .main-content { flex: 1 0 auto; }
    /* Title font */
    .aurora-title { font-family: 'Great Vibes', cursive; color:#fff; text-shadow:0 6px 18px rgba(0,0,0,.45),0 12px 36px rgba(0,0,0,.25); font-size: clamp(42px, 6vw, 96px); line-height:1.1; margin: 0 auto; width: fit-content; }

    .bg-video { position: fixed; top:0; left:0; width:100%; height:100%; object-fit:cover; z-index:-2; pointer-events:none; opacity: var(--video-opacity); }
    .noise-overlay { position: fixed; inset:0; pointer-events:none; z-index:-1; opacity:.18; mix-blend-mode:multiply; background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='100%' height='100%'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.7' numOctaves='4' stitchTiles='stitch'/></filter><rect width='100%' height='100%' filter='url(%23n)' opacity='1.0'/></svg>"); background-size:cover; }

    .navbar.navbar-dark.bg-dark { background-color: rgba(0, 0, 0, var(--nav-bg-opacity)) !important; backdrop-filter: blur(var(--nav-blur)); -webkit-backdrop-filter: blur(var(--nav-blur)); box-shadow: 0 2px 10px rgba(0,0,0,0.25); }
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
    /* Navbar brand glow (match index.php) */
    .navbar-brand {
        position: relative;
        color: #fff !important;
        font-size: 24px;
        font-weight: bold;
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

    /* Developer cards */
    .dev-card { background: rgba(255,255,255,0.12); backdrop-filter: blur(10px) saturate(140%); -webkit-backdrop-filter: blur(10px) saturate(140%); border: 1px solid rgba(255,255,255,0.25); border-radius: 16px; padding: 20px; color: #fff; text-align:center; box-shadow: inset 0 1px 0 rgba(255,255,255,0.25), 0 8px 24px rgba(0,0,0,0.25); }
    .dev-photo { width: 160px; height: 160px; border-radius: 50%; overflow: hidden; margin: 0 auto 12px; position: relative; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.25); }
    .dev-photo img { width: 100%; height: 100%; object-fit: cover; display:block; }
    .dev-name { font-weight: 700; margin-bottom: 4px; }
    .dev-role { opacity: 0.85; font-size: 0.95rem; }

    .site-footer { background-color: rgba(0,0,0,var(--nav-bg-opacity)); backdrop-filter: blur(var(--nav-blur)); -webkit-backdrop-filter: blur(var(--nav-blur)); border-top: 1px solid rgba(255,255,255,0.15); padding: 14px 0; position: relative; z-index:2; color:#bbb; flex-shrink: 0; margin-top: 37px; }
    </style>
    <script>
    $(function(){
        // Slow down background like index
        var bg = document.querySelector('.bg-video');
        if (bg) {
            try { var rate = 0.5; bg.defaultPlaybackRate = rate; bg.playbackRate = rate; } catch(e){}
        }
        // Replace broken dev images with placeholder
        document.querySelectorAll('.dev-photo img').forEach(function(img){
            img.onerror = function(){
                this.onerror = null;
                this.src = 'data:image/svg+xml;utf8,' + encodeURIComponent("<svg xmlns='http://www.w3.org/2000/svg' width='160' height='160'><rect width='100%' height='100%' fill='rgba(255,255,255,0.06)'/><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' fill='rgba(255,255,255,0.6)' font-family='Arial' font-size='18'>Photo</text></svg>");
            };
        });
    });
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
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="moreDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Menu</a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="moreDropdown">
                        <a class="dropdown-item" href="index.php?filter=all">All Records</a>
                        <a class="dropdown-item" href="index.php?filter=82.6">Finals = 82.6</a>
                        <a class="dropdown-item" href="index.php?filter=75">Prelim = 75</a>
                        <a class="dropdown-item" href="index.php?filter=final_grade_firstname">Final Grade with First Name</a>
                        <a class="dropdown-item" href="index.php?filter=final_grade_gte">Final Grade ≥ 87</a>
                        <a class="dropdown-item" href="index.php?filter=GPA">GPA</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="index.php?filter=uncomputed">Uncomputed (Final Grade/GPA)</a>
                        <a class="dropdown-item" href="index.php?filter=computed">Computed (Final Grade/GPA)</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="optionsDropdown">
                        <a class="dropdown-item" href="create.php">Add Record</a>
                        <a class="dropdown-item" href="#" onclick="alert('Available on main page'); return false;">Update Student…</a>
                        <a class="dropdown-item text-danger" href="credits.php" onclick="alert('Available on main page'); return false;">Delete Student…</a>
                    </div>
                </li>
                <li class="nav-item active"><a class="nav-link" href="credits.php">Credits</a></li>
            </ul>
        </div>
    </nav>

    <main class="main-content">
    <div class="container" style="padding-top: 120px; padding-bottom: 40px;">
        <h1 class="aurora-title text-center mb-4">Credits</h1>
        <p class="text-center text-white-50 mb-5">Meet the developers behind this website.</p>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="dev-card">
                    <div class="dev-photo">
                        <img src="assets/images/2x2 kyla.jpg" alt="Developer 1">
                    </div>
                    <div class="dev-name">Navarro, Kyla</div>
                    <div class="dev-role">BSCPE | Developer</div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="dev-card">
                    <div class="dev-photo">
                        <img src="assets/images/2x2 also.jpg" alt="Developer 2">
                    </div>
                    <div class="dev-name">Espocia, Noli</div>
                    <div class="dev-role">BSCPE | Lead Developer</div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="dev-card">
                    <div class="dev-photo">
                        <img src="assets/images/2x2 vince.jpg" alt="Developer 3">
                    </div>
                    <div class="dev-name">Rulloda, Vincent Joshua</div>
                    <div class="dev-role">BSCPE | Developer</div>
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
