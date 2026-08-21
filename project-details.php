<?php
declare(strict_types=1);
$projects = require __DIR__ . '/data/projects.php';
$requestedSlug = isset($_GET['project']) ? (string) $_GET['project'] : '';
if (!isset($projects[$requestedSlug])) {
    $requestedSlug = (string) array_key_first($projects);
}
$project = $projects[$requestedSlug];
$slugs = array_keys($projects);
$position = array_search($requestedSlug, $slugs, true);
$previousSlug = $slugs[($position - 1 + count($slugs)) % count($slugs)];
$nextSlug = $slugs[($position + 1) % count($slugs)];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ========== Meta Tags ========== -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Omar S Pembe - Personal Portfolio">

    <!-- ========== Page Title ========== -->
      <!-- ========== Page Title ========== -->
    <title><?= htmlspecialchars((string) $project['title'], ENT_QUOTES, 'UTF-8') ?> | Omar S Pembe</title>

    <!-- ========== Favicon Icon ========== -->
    <link rel="shortcut icon" href="assets/img/icon/logo2.png" type="image/x-icon">

    <!-- ========== Start Stylesheet ========== -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/magnific-popup.css" rel="stylesheet">
    <link href="assets/css/swiper-bundle.min.css" rel="stylesheet">
    <link href="assets/css/animate.min.css" rel="stylesheet">
    <link href="assets/css/validnavs.css" rel="stylesheet">
    <link href="assets/css/helper.css" rel="stylesheet">
    <link href="assets/css/unit-test.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <!-- ========== End Stylesheet ========== -->

    <!--[if lte IE 9]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->

</head>
 <body>

    <!-- Header 
    ============================================= -->
    <!-- Header 
    ============================================= -->
    <header>
        <!-- Start Navigation -->
        <nav class="navbar mobile-sidenav navbar-box navbar-default validnavs navbar-sticky">

            <!-- Start Top Search -->
            <div class="top-search">
                <div class="container-xl">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search">
                        <span class="input-group-addon close-search"><i class="fa fa-times"></i></span>
                    </div>
                </div>
            </div>
            <!-- End Top Search -->


            <div class="container nav-box d-flex justify-content-between align-items-center">            

                <!-- Start Header Navigation -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                        <i class="fa fa-bars"></i>
                    </button>
                    <a class="navbar-brand" href="index.php">
                        <img src="assets/img/icon/logo2.png" class="logo" alt="Logo">
                    </a>
                </div>
                <!-- End Header Navigation -->

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar-menu">

                    <img src="assets/img/icon/logo2.png" alt="Logo">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                        <i class="fa fa-times"></i>
                    </button>
                    
                    <ul class="nav navbar-nav navbar-right" data-in="fadeInDown" data-out="fadeOutUp">
                                <li>
                            <a class="smooth-menu" href="index.php">Home</a>
                        </li>
                        </li>
                        <li>
                            <a class="smooth-menu" href="index.php#services">What I Do</a>
                        </li>
                        <li>
                            <a class="smooth-menu" href="index.php#portfolio">Portfolio</a>
                        </li>
                        <li>
                            <a class="smooth-menu" href="index.php#resume">Resume</a>
                        </li>
                        <li>
                            <a class="smooth-menu" href="index.php#contact">contact</a>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->

                <div class="nav-right">
                    <div class="attr-right">
                        <!-- Start Atribute Navigation -->
                        <div class="attr-nav attr-box">
                            <ul>
                                <li class="button">
                                    <a class="smooth-menu" href="index.php#contact">Work With Me<i class="fas fa-comment-alt"></i></a>
                                </li>
                            </ul>
                        </div>
                        <!-- End Atribute Navigation -->
                    </div>
                </div>
            </div>   

            <!-- Overlay screen for menu -->
            <div class="overlay-screen"></div>
            <!-- End Overlay screen for menu -->
        </nav>
        <!-- End Navigation -->
    </header>
    <!-- End Header -->

    <!-- Start Breadcrumb 
    ============================================= -->
    <div class="breadcrumb-area text-center">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">
                    <h1><?= htmlspecialchars((string) $project['title'], ENT_QUOTES, 'UTF-8') ?></h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="projects.php"><i class="fas fa-home"></i> Home</a></li>
                            <li class="active">Project</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <!-- Star Project Details Area
    ============================================= -->
    <div class="project-details-items default-padding">
        <div class="container">
            <div class="top-info">
                <div class="row">
    
                    <div class="col-xl-4 col-lg-5 left-info mb-xs-40 mb-md-50">
                        <div class="project-single-info">
                            <ul>
                                <li>Client <span><?= htmlspecialchars((string) $project['client'], ENT_QUOTES, 'UTF-8') ?></span></li>
                                <li>Date <span><?= htmlspecialchars((string) $project['date'], ENT_QUOTES, 'UTF-8') ?></span></li>
                                <li>Service <span><?= htmlspecialchars((string) $project['service'], ENT_QUOTES, 'UTF-8') ?></span></li>
                                <li>Location <span><?= htmlspecialchars((string) $project['location'], ENT_QUOTES, 'UTF-8') ?></span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="right-info col-xl-8 col-lg-7 pl-50 pl-md-15 pl-xs-15 mt-md-10">
                        <h2>Background</h2>
                        <p><?= htmlspecialchars((string) $project['background'], ENT_QUOTES, 'UTF-8') ?></p>
                        <h2>Challenges</h2>
                        <p><?= htmlspecialchars((string) $project['challenges'], ENT_QUOTES, 'UTF-8') ?></p>
                        <h2>Solution</h2>
                        <p><?= htmlspecialchars((string) $project['solution'], ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
   <div class="container">
            <div class="row">
                <div class="col-md-12 gallery-content">
                    <div class="magnific-mix-gallery gallery-masonary">
                        <div id="gallery-masonary" class="gallery-items colums-3">
                            <?php foreach ($project['gallery'] as $image): ?>
                            <div class="gallery-item">
                                <div class="gallery-style-one">
                                    <img src="<?= htmlspecialchars((string) $image, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars((string) $project['title'], ENT_QUOTES, 'UTF-8') ?> project image" class="project-media" loading="lazy" decoding="async">
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- End Project Details Area -->

    <!-- Start Promo box
    ============================================= -->
  <div class="promot-box-area default-padding">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 offset-xl-2">
                    <div class="promo-box-items text-center">
                        <h2>Hello👋i'm open for freelance work and collaborations</h2>
                        <h4>For quick response: <a href="http://wa.me/255620272880" target="_blank" rel="noopener noreferrer"><i class="fab fa-whatsapp"></i> Chat now</a></h4>
                        <div class="button mt-40">
                            <a class="btn-style-regular" href="index.php#contact"><span>Hire Me Now </span> <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Promo box -->

    <!-- Start Footer 
    ============================================= -->
   <footer class="default-padding bg-cover" style="background-image: url(assets/img/shape/1.jpg);">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="footer-items text-center">
                        <a href="index.php" class="footer-logot"><img src="assets/img/icon/logo2.png" alt="Omar Pembe portfolio visual"></a>
                        <ul class="foter-menu">
                            <li><a href="index.php">Home</a></li>
                            <li><a class="smooth-menu" href="#services">What I Do</a></li>
                            <li><a class="smooth-menu" href="#portfolio">Portfolio</a></li>
                            <li><a class="smooth-menu" href="index.php#contact">Contact</a></li>
                        </ul>
                        <p>Copyright &copy; 2025 Omar Suleiman Pembe. All Rights Reserved</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer -->
    
    <!-- jQuery Frameworks
    ============================================= -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.appear.js"></script>
    <script src="assets/js/jquery.easing.min.js"></script>
    <script src="assets/js/swiper-bundle.min.js"></script>
    <script src="assets/js/progress-bar.min.js"></script>
    <script src="assets/js/wow.min.js"></script>
    <script src="assets/js/isotope.pkgd.min.js"></script>
    <script src="assets/js/imagesloaded.pkgd.min.js"></script>
    <script src="assets/js/magnific-popup.min.js"></script>
    <script src="assets/js/jquery.waypoints.js"></script>
    <script src="assets/js/count-to.js"></script>
    <script src="assets/js/YTPlayer.min.js"></script>
    <script src="assets/js/validnavs.js"></script>
    <script src="assets/js/gsap.js"></script>
    <script src="assets/js/ScrollTrigger.min.js"></script>
    <script src="assets/js/jquery.lettering.min.js"></script>
    <script src="assets/js/jquery.circleType.js"></script>
    <script src="assets/js/typed.js"></script>
    <script src="assets/js/features/ui.js"></script>
    <script src="assets/js/features/portfolio.js"></script>
    <script src="assets/js/features/animations.js"></script>
    <script src="assets/js/main.js"></script>

</body>
</html>
