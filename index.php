<?php
// PHP code to process contact form
if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $to = "omarpembe999@gmail.com";
    $subject = "New message from portfolio website";
    $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
    $headers = "From: $email";

    if(mail($to, $subject, $body, $headers)) {
        $success = "Message sent successfully!";
    } else {
        $error = "Sorry, message could not be sent.";
    }
}
?>

<?php
$projects = [
    [
        'title' => '4SITE Programme Mzumbe Univ',
        'type' => 'image',
        'file' => 'assets/img/projects/iot.jpg',
    ],
    [
        'title' => 'Borntz Eagle Brand',
        'type' => 'image',
        'file' => 'assets/img/projects/poster3.jpg',
    ],
    [
        'title' => 'Golden Star Consultants',
        'type' => 'image',
        'file' => 'assets/img/projects/star.jpg',
    ],
    [
        'title' => 'MasteredX Academy',
        'type' => 'image',
        'file' => 'assets/img/projects/skill.jpg',
    ],
    [
        'title' => 'Subo Insurance Agency',
        'type' => 'image',
        'file' => 'assets/img/projects/subo.jpg',
    ],
    [
        'title' => 'Mazola Diamond Hotel',
        'type' => 'image',
        'file' => 'assets/img/projects/mazola.jpg',
    ],
    
];
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ========== Meta Tags ========== -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Omar S Pembe - Personal Portfolio Template">

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
    <div class="preloader">
        <svg viewBox="0 0 1000 1000" preserveAspectRatio="none">
            <path id="preloaderSvg" d="M0,1005S175,995,500,995s500,5,500,5V0H0Z"></path>
        </svg>

        <div class="preloader-heading">
            <div class="load-text">
                <span>W</span>
                <span>E</span>
                <span>L</span>
                <span>C</span>
                <span>O</span>
                <span>M</span>
                <span>E</span>
            </div>
        </div>
    </div>
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
                        <img src="assets/img/icon/logoo.png" class="logo" alt="Logo">
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
                            <a class="smooth-menu" href="#home">Home</a>
                        </li> 
                        <li>
                            <a class="smooth-menu" href="#services">Services</a>
                        </li>
                        <li>
                            <a class="smooth-menu" href="#portfolio">Portfolio</a>
                        </li>
                        <li>
                            <a class="smooth-menu" href="#resume">Resume</a>
                        </li>
                        <li>
                            <a class="smooth-menu" href="#contact">contact</a>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->

                <div class="nav-right">
                    <div class="attr-right">
                        <!-- Start Atribute Navigation -->
                        <div class="attr-nav attr-box">
                            <ul>
                                <li class="button">
                                    <a class="smooth-menu" href="#contact">Work With Me<i class="fas fa-comment-alt"></i></a>
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

    <!-- Start Banner 
    ============================================= -->
    <div class="banner-style-one-area bg-gray" style="background-image: url(assets/img/shape/4.png);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="banner-style-one-items">
                        <div class="info">
                            <h1>Hey <img src="assets/img/icon/hand.png" alt="Icon"> I'm Omar </h1>
                            <h2>
                                <span class="header-caption" id="page-top">
                                    <!-- type headline start-->
                                    <span class="cd-headline clip is-full-width">
                                        <!-- ROTATING TEXT -->
                                        <span class="cd-words-wrapper">
                                            <b class="is-visible">Web Developer</b>
                                            <b class="is-hidden">Graphics Designer</b>
                                            <b class="is-hidden">UI/UX Designer</b>
                                            <b class="is-hidden">Social Media Manager</b>
                                        </span>
                                    </span>
                                    <!-- type headline end -->
                                </span>
                            </h2>
                            <p>
                                Hi, I’m Omar Pembe, a Web Developer, Graphics Designer,UI/UX Designer and Social Media Manager with 5 years of experience. I create modern websites, engaging visuals, seamless user experiences, and effective social media strategies that help businesses and individuals stand out.
                            </p>
                            <div class="flex-social mt-40">
                                <div class="button">
                                    <a class="btn-style-regular" href="#contact"><span>Hire Me Now</span> <i class="fas fa-arrow-right"></i></a>
                                </div>
                                <ul class="social-info">
                                    <li>
                                        <a href="https://tinyurl.com/yckptdb" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                                    </li>
                                    <li>
                                        <a href="https://www.linkedin.com/in/omar-pembe-16a16a1b4/" target= "_blank" rel="noopener noreferrer"><i class="fab fa-linkedin-in"></i></a>
                                    </li>
                                    <li>
                                        <a href="https://www.playbook.com/omar-pembe" target="_blank" rel="noopener noreferrer"><i class="fab fa-dribbble"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="thumb">
                            <img src="assets/img/illustration/112.png" alt="Image Not Found">
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Banner -->

    <!-- Start About 
    ============================================= -->
    <div id="about" class="about-style-one-area bg-gray default-padding">
        <div class="shape-style-one">
            <img src="assets/img/shape/3.png" alt="Image Not Found">
            <img class="upDownScrol" src="assets/img/shape/8.png" alt="Image Not Found">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="fun-fact-style-one-items">
                        <div class="fun-fact">
                            <div class="counter">
                                <div class="timer" data-to="5" data-speed="1000">5</div>
                                <div class="operator">+</div>
                            </div>
                            <span class="medium">Years of Experience</span>
                        </div>
                        <div class="fun-fact">
                            <div class="counter">
                                <div class="timer" data-to="19" data-speed="1000">19</div>
                                <div class="operator">K</div>
                            </div>
                            <span class="medium">Projects completed</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 pl-80 pl-md-15 pl-xs-15">
                    <div class="about-style-one-info">
                        <p class="split-text">
                            As a versatile digital creative with 5 years of experience, I specialize in web development, graphics design, social media management, and UI/UX design. I create responsive, user-friendly websites with modern designs, craft impactful visuals, manage engaging online strategies, and design seamless user experiences. My passion is transforming ideas into functional, visually appealing, and results-driven digital solutions.
                        </p>
                        <a class="btn-style-regular btn-border" href="#"><span>Learn More</span> <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
    <!-- End About -->

    <!-- Start Services 
    ============================================= -->
    <div id="services" class="services-style-one-area default-padding bottom-less">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="site-heading text-center">
                        <h4 class="sub-title">Specialization</h4>
                        <h2 class="title split-text">Areas of Specialization</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <!-- Single Item -->
                <div class="col-xl-3 col-md-6 mb-30 wow fadeInUp">
                    <div class="service-style-one-item">
                        <img src="assets/img/icon/1.png" alt="Image Not Found">
                        <h4><a href="services-details.php">Web Development</a></h4>
                        <p>
                            I design and develop responsive, fast, and user-friendly websites that combine modern design with efficient functionality. From business websites to personal portfolios, I create solutions that help you stand out online.
                        </p>
                    </div>
                </div>
                <!-- End Single Item -->
                 <!-- Single Item -->
                <div class="col-xl-3 col-md-6 mb-30 active wow fadeInUp" data-wow-delay="200ms">
                    <div class="service-style-one-item active">
                        <img src="assets/img/icon/2.png" alt="Image Not Found">
                        <h4><a href="services-details.php">UI/UX Design</a></h4>
                        <p>
                            I design seamless user experiences with a focus on usability and aesthetics. My goal is to make digital products that not only look great but also provide smooth navigation and functionality.
                        </p>
                    </div>
                </div>
                <!-- End Single Item -->
                 <!-- Single Item -->
                <div class="col-xl-3 col-md-6 mb-30 wow fadeInUp" data-wow-delay="400ms">
                    <div class="service-style-one-item">
                        <img src="assets/img/icon/3.png" alt="Image Not Found">
                        <h4><a href="services-details.php">Graphics Design</a></h4>
                        <p>
                            I create professional and eye-catching visuals including logos, posters, social media creatives, and branding materials that effectively communicate your message and identity.
                        </p>
                    </div>
                </div>
                <!-- End Single Item -->
                 <!-- Single Item -->
                <div class="col-xl-3 col-md-6 mb-30 wow fadeInUp" data-wow-delay="600ms">
                    <div class="service-style-one-item">
                        <img src="assets/img/icon/4.png" alt="Image Not Found">
                        <h4><a href="services-details.html">Social Media Management</a></h4>
                        <p>
                            I help businesses and individuals grow their audience by managing social media accounts, creating content strategies, and running impactful campaigns that boost visibility and interaction.
                        </p>
                    </div>
                </div>
                <!-- End Single Item -->
            </div>
        </div>
    </div>
    <!-- End Services -->

    <!-- Start Portfolio 
    ============================================= -->
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="site-heading text-center">
                        <h4 class="sub-title">Portfolio</h4>
                        <h2 class="title split-text">My Projects</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 gallery-content">
                    <div class="magnific-mix-gallery gallery-masonary">
                        <div id="gallery-masonary" class="gallery-items colums-3">
                            <?php foreach($projects as $project): ?>
                            <div class="gallery-item">
                                <div class="gallery-style-one">
                                    <?php if($project['type'] === 'video'): ?>
                                        <video controls class="project-media">
                                            <source src="<?php echo $project['file']; ?>" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    <?php else: ?>
                                        <img src="<?php echo $project['file']; ?>" alt="Project Thumbnail" class="project-media">
                                    <?php endif; ?>
                                    <div class="info">
                                        <div class="overlay">
                                            <div class="content"></div>
                                        </div>
                                        <h4><a href="project-details.php"><?php echo $project['title']; ?></a></h4>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <div class="load-more-info text-center mt-60 mt-xs-30">
                                    <p>
                                        Are you interested to see more Projects? <a href="projects.php">Load More</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- End Portfolio -->

    <!-- Start Fun Fact 
    ============================================= -->
    <div class="fun-factor-area default-padding overflow-hidden">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="site-heading text-center">
                        <h4 class="sub-title">Top Skills</h4>
                        <h2 class="title split-text">See my expertise</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="fun-fact-style-two-items text-center">
                <!-- Single item -->
                <div class="funfact-style-two-item wow fadeInUp">
                    <div class="icon">
                        <img src="assets/img/icon/figma.png" alt="Image Not Found">
                    </div>
                    <div class="fun-fact">
                        <div class="counter">
                            <div class="timer" data-to="80" data-speed="2000">80</div>
                            <div class="operator">%</div>
                        </div>
                        <span class="medium">Figma</span>
                    </div>
                </div>
                <!-- End Single item -->

                <!-- Single item -->
                <div class="funfact-style-two-item wow fadeInUp" data-wow-delay="100ms">
                    <div class="icon">
                        <img src="assets/img/icon/JS.png" alt="Image Not Found">
                    </div>
                    <div class="fun-fact">
                        <div class="counter">
                            <div class="timer" data-to="90" data-speed="2000">90</div>
                            <div class="operator">%</div>
                        </div>
                        <span class="medium">HTML, CSS & JavaScript</span>
                    </div>
                </div>
                <!-- End Single item -->

                <!-- Single item -->
                <div class="funfact-style-two-item wow fadeInUp" data-wow-delay="200ms">
                    <div class="icon">
                        <img src="assets/img/icon/PY.png" alt="Image Not Found">
                    </div>
                    <div class="fun-fact">
                        <div class="counter">
                            <div class="timer" data-to="70" data-speed="2000">70</div>
                            <div class="operator">%</div>
                        </div>
                        <span class="medium">Python</span>
                    </div>
                </div>
                <!-- End Single item -->
                 <!-- Single item -->
                <div class="funfact-style-two-item wow fadeInUp" data-wow-delay="300ms">
                    <div class="icon">
                        <img src="assets/img/icon/SQL.png" alt="Image Not Found">
                    </div>
                    <div class="fun-fact">
                        <div class="counter">
                            <div class="timer" data-to="80" data-speed="2000">80</div>
                            <div class="operator">%</div>
                        </div>
                        <span class="medium">MySQL</span>
                    </div>
                </div>
                <!-- End Single item -->
                 <!-- Single item -->
                <div class="funfact-style-two-item wow fadeInUp" data-wow-delay="400ms">
                    <div class="icon">
                        <img src="assets/img/icon/photoshop.png" alt="Image Not Found">
                    </div>
                    <div class="fun-fact">
                        <div class="counter">
                            <div class="timer" data-to="95" data-speed="2000">95</div>
                            <div class="operator">%</div>
                        </div>
                        <span class="medium">Photoshop</span>
                    </div>
                </div>
                <!-- End Single item -->
                 <!-- Single item -->
                <div class="funfact-style-two-item wow fadeInUp" data-wow-delay="500ms">
                    <div class="icon">
                        <img src="assets/img/icon/DG.png" alt="Image Not Found">
                    </div>
                    <div class="fun-fact">
                        <div class="counter">
                            <div class="timer" data-to="80" data-speed="2000">80</div>
                            <div class="operator">%</div>
                        </div>
                        <span class="medium">Digital Marketing</span>
                    </div>
                </div>
                <!-- End Single item -->
           </div>
        </div>
    </div>
    <!-- End Fun Factor -->
    <!-- Start Timeline 
    ============================================= -->
    <div id="resume" class="timeline-area default-padding bg-gray">
        <div class="container">
            <div class="time-line-style-one-box">
                <div class="row guttex-xl">

                    <div class="col-lg-6">
                        <h2>My Experience</h2>
                        <div class="time-style-one-items">
                            <!-- Single Item -->
                            <div class="timeline-style-one-item wow fadeInUp">
                                <div class="timeline-header">
                                    <div class="left">
                                        <h4>Multimedia Expert</h4>
                                        <p>
                                            4SITE Programme - Mzumbe Univ
                                        </p>
                                    </div>
                                    <div class="right">
                                        <span>2021 - Present</span>
                                    </div>
                                </div>
                                <div class="timeline-body">
                                    <p>
                                        Serve as a Multimedia Expert in the 4SITE Programme at Mzumbe University, where I created tailored multimedia content for legal aid services. My role included producing visual content for brand awareness, documenting activities, and leading a team of multimedia personnel to ensure quality and impactful communication.
                                    </p>
                                </div>
                             </div>
                            <!-- End Single Item -->
                             <!-- Single Item -->
                             <div class="timeline-style-one-item wow fadeInUp">
                                <div class="timeline-header">
                                    <div class="left">
                                        <h4>Designer & Web Developer </h4>
                                        <p>
                                            BornTZ Eagle Computers
                                        </p>
                                    </div>
                                    <div class="right">
                                        <span>2023 - Present</span>
                                    </div>
                                </div>
                                <div class="timeline-body">
                                    <p>
                                       I Work as a Designer and Web Developer at BornTZ Eagle Computers, where I design and develope responsive websites and visuals that align with client needs. I focus on delivering functional, modern, and visually appealing digital platforms to support business growth and visibility.
                                    </p>
                                </div>
                             </div>
                            <!-- End Single Item -->
                             <!-- Single Item -->
                             <div class="timeline-style-one-item wow fadeInUp">
                                <div class="timeline-header">
                                    <div class="left">
                                        <h4>Digital & Social Media Expert</h4>
                                        <p>
                                            Masteredx Academy
                                        </p>
                                    </div>
                                    <div class="right">
                                        <span>2024 - Present</span>
                                    </div>
                                </div>
                                <div class="timeline-body">
                                    <p>
                                        I manage digital platforms and social media strategies at Masteredx Academy, creating multimedia content, optimizing campaigns, and developing promotional materials to enhance online presence and engagement.
                                    </p>
                                </div>
                             </div>
                            <!-- End Single Item -->
                        </div>
                    </div>
    
                    <div class="col-lg-6">
                        <h2>Education Background</h2>
                        <div class="time-style-one-items">
                            
                            <!-- Single Item -->
                            <div class="timeline-style-one-item wow fadeInUp">
                                <div class="timeline-header">
                                    <div class="left">
                                        <h4>Bsc. in Information</h4>
                                        <h4>Technology and Systems</h4>
                                        <p>
                                            Mzumbe University
                                        </p>
                                    </div>
                                    <div class="right">
                                        <span>Graduated</span>
                                    </div>
                                </div>
                                <div class="timeline-body">
                                    <p>
                                        To further expand my knowledge, I am enrolled in the Bachelor of Science in Information Technology and Systems at Mzumbe University. This program will enhance my expertise in software engineering, systems management, cybersecurity, data analytics, and emerging technologies such as AI and cloud computing. It will also deepen my understanding of research methods and strategic ICT management, preparing me to deliver impactful solutions that bridge technology and organizational needs.
                                    </p>
                                </div>
                             </div>
                            <!-- End Single Item -->
                             <!-- Single Item -->
                             <div class="timeline-style-one-item wow fadeInUp">
                                <div class="timeline-header">
                                    <div class="left">
                                        <h4>Diploma in Information Technology</h4>
                                        <p>
                                            Mzumbe University
                                        </p>
                                    </div>
                                    <div class="right">
                                        <span>Graduated</span>
                                    </div>
                                </div>
                                <div class="timeline-body">
                                    <p>
                                        Building on this foundation, I pursued a Diploma in Information Technology at Mzumbe University, where I advanced my skills in programming (C++, Java, Python), web development (HTML, CSS, JavaScript, PHP, MySQL), systems analysis, and database management. I also explored networking, ICT project management, and multimedia design, which gave me both the technical expertise and creative perspective to develop real-world digital solutions.
                                    </p>
                                </div>
                             </div>
                            <!-- End Single Item -->
                              <!-- Single Item -->
                             <div class="timeline-style-one-item wow fadeInUp">
                                <div class="timeline-header">
                                    <div class="left">
                                        <h4>Cybersecurity Specialization</h4>
                                        <p>
                                            Google (Coursera)
                                        </p>
                                    </div>
                                    <div class="right">
                                        <span>Passed</span>
                                    </div>
                                </div>
                                <div class="timeline-body">
                                    <p>
                                        In addition to my formal studies, I completed the Google Cybersecurity Specialization, which provided hands-on knowledge in identifying security risks, protecting networks, and responding to cyber threats. This training strengthened my ability to implement security best practices, work with tools like SIEMs and intrusion detection systems, and apply frameworks that keep digital environments safe.
                                    </p>
                                </div>
                             </div>
                            <!-- End Single Item -->
                             <!-- Single Item -->
                             <div class="timeline-style-one-item wow fadeInUp">
                                <div class="timeline-header">
                                    <div class="left">
                                        <h4>Certificate in Information</h4>
                                        <h4>Technology</h4>
                                        <p>
                                            Mzumbe University
                                        </p>
                                    </div>
                                    <div class="right">
                                        <span>Graduated</span>
                                    </div>
                                </div>
                                <div class="timeline-body">
                                    <p>
                                        I began my IT journey with a Certificate in Information Technology at Mzumbe University, where I gained a strong foundation in computer systems, basic programming, databases, and networking. This program equipped me with essential problem-solving skills, IT support knowledge, and hands-on experience in using productivity tools, forming the groundwork for my growth in the technology field.
                                    </p>
                                </div>
                             </div>
                            <!-- End Single Item -->
                        </div>
                    </div>
    
                </div>
            </div>
        </div>
    </div>
    <!-- End Timeline -->

    <!-- Start Faq 
    ============================================= -->
    <div class="faq-style-one-area default-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="thumb-style-one">
                        <img src="assets/img/shape/wasap.png" alt="Image Not Found">
                        <div class="chat-card">
                        


                            <a href="http://wa.me/255620272880" target="_blank" rel="noopener noreferrer"><i class="fab fa-whatsapp"></i></a>
                            <img src="assets/img/shape/12.png" alt="Image Not Found">
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1">
                    <div class="faq-style-one-items">
                        <h4 class="sub-title">Faq</h4>
                        <h2>Frequently Asked Questions</h2>
                        <div class="accordion mt-30" id="faqAccordion">
                            <div class="accordion-item accordion-style-one">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        May i see your work samples?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>
                                            Absolutely. I maintain a portfolio that showcases my recent projects across web development, multimedia design, and graphics. You can view selected samples on my portfolio page or request specific examples tailored to your area of interest.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item accordion-style-one">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        What are your rates?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>
                                            My rates vary depending on the scope, complexity, and duration of the project. I aim to provide flexible and fair pricing that aligns with the value delivered. Once I understand your project requirements, I will provide a clear and transparent quotation.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item accordion-style-one">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        How do you prefer to communicate?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>
                                            I am comfortable with multiple communication channels including email, phone, and video conferencing. For project collaboration, I also make use of professional platforms and tools to ensure smooth and efficient communication. The method can be tailored to what works best for you.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Faq -->

    <!-- Start Contact 
    ============================================= -->
    <div id="contact" class="contact-style-one-area default-padding bg-gray">
        <div class="container">
            <div class="contact-style-one-items">
                
                <h1 class="fixed-text">Contact Me</h1>
                <?php
if(isset($success)) {
    echo "<p style='color:green;'>$success</p>";
} elseif(isset($error)) {
    echo "<p style='color:red;'>$error</p>";
}
?>

                <div class="row">
                    <div class="col-lg-6">
                        <form action="assets/mail/contact.php" method="POST" class="contact-form contact-form">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input class="form-control" id="name" name="name" placeholder="Name" type="text">
                                        <span class="alert-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input class="form-control" id="email" name="email" placeholder="Email*" type="email">
                                        <span class="alert-error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input class="form-control" id="phone" name="phone" placeholder="Phone" type="text">
                                        <span class="alert-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group comments">
                                        <textarea class="form-control" id="comments" name="comments" placeholder="Tell Us About Project *"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <button class="btn-style-regular" type="submit" name="submit" id="submit">
                                        <span>Get in Touch</span> <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- Alert Message -->
                            <div class="col-lg-12 alert-notification">
                                <div id="message" class="alert-msg"></div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="contact-illustration">
                    <img src="assets/img/illustration/omy.png" alt="Image Not Found">
                    
                </div>

            </div>
        </div>
    </div>
    <!-- End Contact -->
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
                            <a class="btn-style-regular" href="#contact"><span>Hire Me Now </span> <i class="fas fa-arrow-right"></i></a>
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
                            <li><a class="smooth-menu" href="#services">What I Do</a></li>
                            <li><a class="smooth-menu" href="#portfolio">Portfolio</a></li>
                            <li><a class="smooth-menu" href="#contact">Contact</a></li>
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
    <script src="assets/js/SplitText.min.js"></script>
    <script src="assets/js/jquery.lettering.min.js"></script>
    <script src="assets/js/jquery.circleType.js"></script>
    <script src="assets/js/typed.js"></script>
    <script src="assets/js/main.js"></script>

</body>
</html>