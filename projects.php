<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/project-repository.php';
$projects = allProjects();
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
    <title>Omar S Pembe - Personal Portfolio</title>

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
    <link href="assets/css/style.css?v=20260822" rel="stylesheet">
    <link href="style.css?v=20260822" rel="stylesheet">
    <!-- ========== End Stylesheet ========== -->

    <!--[if lte IE 9]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->

</head>

<body>

    <!-- Header 
    ============================================= -->
   
    <!-- Preloader Area End -->

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

                    <img src="assets/img/" alt="Logo">
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
                </div>
                <!-- /.navbar-collapse -->

                <div class="nav-right">
                    <button type="button" id="theme-toggle" class="theme-toggle" aria-label="Switch to dark mode" aria-pressed="false">
                        <i class="fas fa-moon" aria-hidden="true"></i><span>Dark mode</span>
                    </button>
                    <div class="attr-right">
                        <!-- Start Atribute Navigation -->
                        <div class="attr-nav attr-box">
                            <ul>
                                <li class="button">
                                    <a href="index.php" class="smooth-menu">Work With Me<i class="fas fa-comment-alt"></i></a>
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
                    <h1>COMPLETED PROJECTS</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                            <li class="active">Projects</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <!-- Start Portfolio 
    ============================================= -->
  <div class="container">
            <div class="row">
                <div class="col-md-12 gallery-content">
                    <div class="magnific-mix-gallery gallery-masonary">
                        <div id="gallery-masonary" class="gallery-items colums-3">
                            <?php foreach ($projects as $slug => $project) { include __DIR__ . '/includes/render-project-card.php'; } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Portfolio -->

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
                        <ul class="foter-menu">
                            <li><a href="index.php">Home</a></li>
                            <li><a href="index.php#services">What I Do</a></li>
                            <li><a href="index.php#portfolio">Portfolio</a></li>
                            <li><a href="index.php">Contact</a></li>
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
    <?php require_once __DIR__ . '/includes/page-scripts.php'; renderPageScripts(false, true); ?>

</body>
</html>