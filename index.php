<?php

// Security Headers
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");

// Database Connection
include("config.php");

/* ============================
   JOIN US FORM (SECURE VERSION)
============================ */

if (isset($_POST["join_submit"])) 
{
    if (
        !empty($_POST["name"]) &&
        !empty($_POST["email"]) &&
        !empty($_POST["contact"]) &&
        !empty($_POST["gender"]) &&
        !empty($_FILES["cv"]["name"])
    ) 
    {
        // 1. Clean Inputs
        $name    = trim($_POST["name"]);
        $email   = trim($_POST["email"]);
        $contact = trim($_POST["contact"]);
        $gender  = trim($_POST["gender"]);

        // 2. File Validation Setup
        $file_name = $_FILES["cv"]["name"];
        $file_tmp  = $_FILES["cv"]["tmp_name"];
        
        // File Ki Extension Nikalein
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Allowed Formats: PDF, DOC, DOCX, PNG, JPG, JPEG
        $allowed_extensions = array("pdf", "doc", "docx", "png", "jpg", "jpeg");

        if (in_array($file_ext, $allowed_extensions)) 
        {
            // Direct uploads/ folder path & Safe Filename
            $safe_filename = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "", $file_name);
            $target_path   = "uploads/" . $safe_filename;

            // File Upload Action
            if (move_uploaded_file($file_tmp, $target_path)) 
            {
                // 3. Prepared Statement (SQL Injection Protection)
                $insert_query = "INSERT INTO faculty_applications (full_name, email, contact, gender, cv) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $insert_query);

                if ($stmt) 
                {
                    mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $contact, $gender, $safe_filename);

                    if (mysqli_stmt_execute($stmt)) 
                    {
                        echo "<script>alert('Application Submitted Successfully');</script>";
                    } 
                    else 
                    {
                        echo "<script>alert('Database Error: Could not execute query');</script>";
                    }

                    mysqli_stmt_close($stmt);
                } 
                else 
                {
                    echo "<script>alert('Database Error: Query preparation failed');</script>";
                }
            } 
            else 
            {
                echo "<script>alert('Failed to upload file to uploads folder');</script>";
            }
        } 
        else 
        {
            // Security Alert: Unauthorized File Format Blocked
            echo "<script>alert('Invalid file format! Only PDF, DOC, DOCX, PNG, and JPG files are allowed.');</script>";
        }
    } 
    else 
    {
        echo "<script>alert('Please Fill All Fields');</script>";
    }
}

/* ============================
   CONTACT / FEEDBACK FORM (SECURE VERSION)
============================ */

if (isset($_POST["contact_submit"])) 
{
    if (
        !empty($_POST["name"]) &&
        !empty($_POST["email"]) &&
        !empty($_POST["subject"]) &&
        !empty($_POST["message"])
    ) 
    {
        // 1. Clean Inputs
        $name    = trim($_POST["name"]);
        $email   = trim($_POST["email"]);
        $subject = trim($_POST["subject"]);
        $message = trim($_POST["message"]);

        // 2. Prepared Statement (FIXED: contact_messages Table Name)
        $insert_query = "INSERT INTO contact_messages (full_name, email, subject, message) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);

        if ($stmt) 
        {
            mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $subject, $message);

            if (mysqli_stmt_execute($stmt)) 
            {
                echo "<script>alert('Thank you! Your message has been sent successfully.');</script>";
            } 
            else 
            {
                echo "<script>alert('Database Error: Could not save message.');</script>";
            }

            mysqli_stmt_close($stmt);
        } 
        else 
        {
            echo "<script>alert('Database Error: Query preparation failed.');</script>";
        }
    } 
    else 
    {
        echo "<script>alert('Please fill in all required fields.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Computer Institute | Professional IT Education</title>
    
<!-- YEH LINE ADD KAREIN: Aap ka logo ab tab icon ban jayega -->
    <link rel="icon" type="image/png" href="./img/logo.png">

    <!-- Modern Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        /* --- CSS VARIABLES & RESET --- */
        :root {
            --primary-yellow: #F8D70C;
            --bg-black: #0a0a0a;
            --bg-dark: #161616;
            --bg-card: #1e1e1e;
            --text-white: #f8f9fa;
            --text-gray: #a0a0a0;
            --nav-height: 80px;
            --transition-speed: 0.3s;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        html {
            scroll-behavior: smooth;
            max-width: 100%;
            overflow-x: hidden;
        }

        body {
            background-color: var(--bg-black);
            color: var(--text-white);
            line-height: 1.7;
            padding-top: var(--nav-height);
            max-width: 100%;
            overflow-x: hidden; /* Prevent horizontal scroll bar */
        }

        /* --- STICKY NAVBAR --- */
        header {
            background-color: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 2px solid var(--primary-yellow);
            position: fixed;
            width: 100%;
            height: var(--nav-height);
            top: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 0 5%;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* LOGO STYLING */
        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        
        .logo img {
            max-height: 60px;
            filter: drop-shadow(1px 1px 0px #fff) 
                    drop-shadow(-1px -1px 0px #fff) 
                    drop-shadow(1px -1px 0px #fff) 
                    drop-shadow(-1px 1px 0px #fff);
            object-fit: contain;
            transition: transform var(--transition-speed);
        }

        .logo img:hover {
            transform: scale(1.05);
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 30px;
        }

        .nav-links li a {
            color: var(--text-white);
            text-decoration: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1.5px;
            transition: color var(--transition-speed);
            padding: 8px 12px;
            position: relative;
        }

        .nav-links li a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--primary-yellow);
            transition: width var(--transition-speed);
        }

        .nav-links li a:hover::after {
            width: 100%;
        }
        
        .nav-links li a:hover {
            color: var(--primary-yellow);
        }

        /* --- BURGER MENU (MOBILE) --- */
        .hamburger {
            display: none;
            cursor: pointer;
            flex-direction: column;
            gap: 6px;
            z-index: 2000;
        }

        .hamburger span {
            display: block;
            width: 30px;
            height: 3px;
            background-color: var(--text-white);
            transition: all var(--transition-speed);
            border-radius: 2px;
        }

        /* --- HERO SLIDER SECTION --- */
        .slider-container {
            position: relative;
            width: 100%;
            height: calc(100vh - var(--nav-height)); 
            overflow: hidden;
            background-color: var(--bg-black);
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1.2s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .slide.active {
            opacity: 1;
            z-index: 1;
        }

        .slide img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -2;
            opacity: 0.4; 
        }

        .slide-content {
            z-index: 2;
            padding: 0 20px;
            transform: translateY(30px);
            opacity: 0;
            transition: all 1s ease-out 0.3s;
            max-width: 900px;
        }

        .slide.active .slide-content {
            transform: translateY(0);
            opacity: 1;
        }

        .slide-content h1 {
            font-size: 4.5rem;
            font-weight: 800;
            color: var(--primary-yellow);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 15px;
            text-shadow: 2px 4px 10px rgba(0,0,0,0.8);
        }

        .slide-content p {
            font-size: 1.4rem;
            color: var(--text-white);
            font-weight: 300;
            text-shadow: 1px 2px 5px rgba(0,0,0,0.8);
        }

        /* --- CLEAN & CONSISTENT SECTION STYLES --- */
        section {
            padding: 80px 5%;
            max-width: 1300px;
            margin: 0 auto;
        }

        .section-title {
            font-size: 2.8rem;
            font-weight: 800;
            color: var(--text-white);
            text-transform: uppercase;
            margin-bottom: 60px;
            text-align: center;
            letter-spacing: 1px;
        }

        .section-title span {
            color: var(--primary-yellow);
        }

        /* --- ABOUT SECTION --- */
        #about {
            background-color: var(--bg-dark);
            border-radius: 12px;
            margin-top: 40px;
            padding: 80px 8%;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        #about p {
            font-size: 1.25rem;
            color: var(--text-gray);
            max-width: 900px;
            margin: 0 auto 20px auto;
            font-weight: 300;
            line-height: 1.8;
        }

        #about p:last-child {
            margin-bottom: 0;
        }

        /* ==========================================
           COURSES SECTION (STABLE GRID LAYOUT)
           ========================================== */
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            align-items: stretch;
        }

        .course-card {
            background-color: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 25px;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            transition: border-color 0.3s ease, transform 0.3s ease;
        }

        .course-card:hover {
            border-color: var(--primary-yellow);
            transform: translateY(-4px);
        }

        .course-card h3 {
            color: var(--primary-yellow);
            font-size: 1.25rem;
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .course-card > p {
            color: var(--text-gray);
            flex-grow: 1;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .course-details-btn {
            align-self: flex-start;
            margin-top: auto;
            background-color: transparent;
            border: 1px solid var(--primary-yellow);
            color: var(--primary-yellow);
            padding: 10px 22px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .course-details-btn:hover {
            background-color: var(--primary-yellow);
            color: #000;
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.3);
        }

        /* --- MODAL POP-UP --- */
        .course-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.82);
            backdrop-filter: blur(8px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            padding: 20px;
            box-sizing: border-box;
        }

        .course-modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .course-modal-box {
            background-color: #121212;
            border: 1px solid var(--primary-yellow);
            border-radius: 14px;
            width: 100%;
            max-width: 650px;
            max-height: 85vh;
            padding: 35px;
            position: relative;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.9), 0 0 20px rgba(255, 215, 0, 0.15);
            transform: scale(0.9);
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .course-modal-overlay.active .course-modal-box {
            transform: scale(1);
        }

        .modal-close-btn {
            position: absolute;
            top: 18px;
            right: 22px;
            background: transparent;
            border: none;
            color: #fff;
            font-size: 32px;
            cursor: pointer;
            line-height: 1;
            transition: color 0.2s ease;
        }

        .modal-close-btn:hover {
            color: var(--primary-yellow);
        }

        .modal-title {
            color: var(--primary-yellow);
            font-size: 1.5rem;
            margin-bottom: 20px;
            padding-right: 30px;
            line-height: 1.3;
        }

        .modal-body h4 {
            color: #ffffff;
            font-size: 1.1rem;
            margin: 20px 0 8px 0;
            display: flex;
            align-items: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .modal-body h4::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 16px;
            background-color: var(--primary-yellow);
            margin-right: 10px;
            border-radius: 2px;
        }

        .modal-body p {
            color: var(--text-gray);
            font-size: 0.98rem;
            line-height: 1.6;
        }

        .modal-body ul {
            padding-left: 20px;
            margin-top: 8px;
            margin-bottom: 15px;
        }

        .modal-body ul li {
            color: var(--text-gray);
            margin-bottom: 8px;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .course-modal-box::-webkit-scrollbar {
            width: 6px;
        }

        .course-modal-box::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .course-modal-box::-webkit-scrollbar-thumb {
            background: var(--primary-yellow);
            border-radius: 4px;
        }

        /* --- LOCATION SECTION --- */
        #location {
            background-color: var(--bg-dark);
            border-radius: 12px;
            margin-top: 40px;
            padding: 80px 5%;
        }

        .location-container {
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        .campus-card {
            display: flex;
            background-color: var(--bg-card);
            border-left: 4px solid var(--primary-yellow);
            border-radius: 6px;
            overflow: hidden;
            transition: transform var(--transition-speed);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .campus-card:hover {
            transform: translateX(10px);
        }

        .campus-info {
            flex: 1;
            padding: 35px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .campus-info h4 {
            color: var(--primary-yellow);
            font-size: 1.5rem;
            margin-bottom: 15px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .campus-info p {
            color: var(--text-gray);
            margin-bottom: 8px;
            font-size: 1rem;
            line-height: 1.5;
        }

        .campus-map {
            flex: 1;
            min-width: 300px;
            min-height: 300px;
        }

        .campus-map iframe {
            width: 100%;
            height: 100%;
            border: 0;
            display: block;
        }

        /* --- FORM STYLING (Used in Join Us & Contact) --- */
        .feedback-form {
            max-width: 800px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            background-color: var(--bg-dark);
            padding: 45px;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.4);
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .feedback-form input,
        .feedback-form textarea,
        .feedback-form select {
            width: 100%;
            padding: 16px 20px;
            background-color: var(--bg-card);
            border: 1px solid #333;
            color: var(--text-white);
            font-size: 1rem;
            border-radius: 6px;
            outline: none;
            transition: border-color var(--transition-speed), box-shadow var(--transition-speed);
        }

        .feedback-form select:invalid {
            color: var(--text-gray);
        }

        .feedback-form input[type="file"] {
            padding: 12px 20px;
            color: var(--text-gray);
        }
        
        .feedback-form input[type="file"]::-webkit-file-upload-button {
            background-color: var(--primary-yellow);
            color: var(--bg-black);
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            margin-right: 15px;
            transition: background-color 0.2s;
        }

        .feedback-form input[type="file"]::-webkit-file-upload-button:hover {
            background-color: #e0c20a;
        }

        .feedback-form input:focus,
        .feedback-form textarea:focus,
        .feedback-form select:focus {
            border-color: var(--primary-yellow);
            box-shadow: 0 0 0 2px rgba(248, 215, 12, 0.2);
        }

        .feedback-form button {
            grid-column: 1 / -1;
            background-color: var(--primary-yellow);
            color: var(--bg-black);
            padding: 18px;
            font-size: 1.1rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color var(--transition-speed), transform 0.1s;
            margin-top: 10px;
        }

        .feedback-form button:hover {
            background-color: #e0c20a;
        }
        
        .feedback-form button:active {
            transform: scale(0.98);
        }
        
        .contact-info {
            text-align: center;
            margin-bottom: 50px;
            color: var(--text-gray);
            font-size: 1.1rem;
        }
        
        .contact-info span {
            color: var(--primary-yellow);
            font-weight: 600;
        }

        /* --- FOOTER --- */
        footer {
            background-color: var(--bg-dark);
            padding: 30px 0;
            text-align: center;
        }

        footer p {
            color: var(--text-gray);
        }
        
        footer span {
            color: var(--primary-yellow);
        }

        /* --- RESPONSIVE DESIGN BREAKPOINTS --- */
        @media (max-width: 992px) {
            .slide-content h1 { font-size: 3.5rem; }
            .courses-grid { grid-template-columns: repeat(2, 1fr); }
            .campus-card { flex-direction: column; }
            .campus-map { min-height: 280px; }
        }

        @media (max-width: 768px) {
            section, #about, #location, #join-us, #contact {
                padding: 50px 4%;
            }

            /* Fix 1: Course Cards 1 Column on Mobile */
            .courses-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            /* Fix 2: Map Card Height Adjustment on Mobile */
            .campus-map {
                min-height: auto;
                height: 250px;
                width: 100%;
            }

            .campus-map iframe {
                height: 100%;
                width: 100%;
            }

            .hamburger { display: flex; }
            
            .nav-links {
                position: fixed;
                top: var(--nav-height);
                right: -100%;
                width: 100%;
                height: calc(100vh - var(--nav-height));
                background-color: rgba(10, 10, 10, 0.98);
                backdrop-filter: blur(15px);
                flex-direction: column;
                justify-content: center;
                align-items: center;
                transition: right 0.4s ease-in-out;
                gap: 40px;
            }

            .nav-links.active { right: 0; }
            
            .nav-links li a { font-size: 1.2rem; }

            .hamburger.active span:nth-child(1) {
                transform: translateY(9px) rotate(45deg);
            }
            .hamburger.active span:nth-child(2) {
                opacity: 0;
            }
            .hamburger.active span:nth-child(3) {
                transform: translateY(-9px) rotate(-45deg);
            }

            .slide-content h1 { font-size: 2.8rem; }
            .section-title { font-size: 2.2rem; }

            .feedback-form { 
                grid-template-columns: 1fr;
                padding: 25px 18px;
            }

            .course-modal-box {
                padding: 25px;
            }
        }
</style>
</head>
<body>

    <!-- NAVBAR -->
    <header>
        <nav>
            <!-- LOGO: White Stroke Applied via CSS Drop-Shadow -->
            <a href="#" class="logo">
                <img src="./img/logo.png" alt="Logo">
            </a>
            
            <ul class="nav-links">
                <li><a href="#about">About Us</a></li>
                <li><a href="#courses">Academic Courses</a></li>
                <li><a href="#location">Campuses</a></li>
                <li><a href="#join-us">Join Us</a></li>
                <li><a href="#contact">Contact Support</a></li>
            </ul>

            <!-- Mobile Burger Icon -->
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </header>

    <!-- HERO SLIDER SECTION -->
    <div id="home" class="slider-container">
        <!-- Slide 1 -->
        <div class="slide active">
            <img src="./img/slider-img0.png" alt="Graduation Ceremony">
            <div class="slide-content">
                <h1>Achieve Greatness</h1>
                <p>Empowering the next generation of IT leaders through excellence, innovation, and dedicated mentorship. Join a community built for success.</p>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="slide">
            <img src="./img/slider-img1.png" alt="Modern IT Labs">
            <div class="slide-content">
                <h1>Build Your Future</h1>
                <p>Master real-world technical skills and digital media strategies in our state-of-the-art facilities designed for hands-on learning.</p>
            </div>
        </div>

        <!-- Slide 3 (Workshop) -->
        <div class="slide">
            <img src="./img/slider-img2.png" alt="Language and Communication Workshop">
            <div class="slide-content">
                <h1>Master Communication</h1>
                <p>Build confidence and enhance your interpersonal skills with our specialized language and communication workshops. Learn to express yourself effectively in any professional environment.</p>
            </div>
        </div>

        <!-- Slide 4 (Cricket Tournament) -->
        <div class="slide">
            <img src="./img/slider-img3.png" alt="Cricket Tournament">
            <div class="slide-content">
                <h1>Vibrant Campus Life</h1>
                <p>Fostering teamwork, leadership, and a healthy lifestyle through our annual sports events and engaging student activities.</p>
            </div>
        </div>

        <!-- Slide 5 (Building Front / Main Gate) -->
        <div class="slide">
            <img src="./img/slider-img4.png" alt="Global Computer Institute Campus">
            <div class="slide-content">
                <h1>Your Gateway to Excellence</h1>
                <p>Welcome to our modern campus, designed to inspire creativity and serve as the starting point for your professional IT journey.</p>
            </div>
        </div>
    </div>

    <!-- ABOUT SECTION -->
    <section id="about">
        <h2 class="section-title">About <span>Our Institute</span></h2>
        <p>
            Global Computer Institute is a premier educational establishment dedicated to equipping students with cutting-edge technological proficiencies. Officially affiliated and registered with the Sindh Board of Technical Education, our institution bridges the gap between traditional learning and modern industry demands.
        </p>
        <p>
            We foster an environment of innovation, practical application, and career-driven mentorship to ensure our graduates are fully prepared for the global digital economy. From foundational IT knowledge to advanced software engineering and digital marketing, our curriculum is meticulously designed by industry experts.
        </p>
    </section>

<!-- COURSES SECTION -->
<section id="courses">
    <h2 class="section-title">Academic <span>Programs</span></h2>
    <div class="courses-grid">
        
        <!-- Course 1 -->
        <div class="course-card">
            <h3>Certificate in Information Technology (C.I.T)</h3>
            <p>A comprehensive introduction to modern computing, covering core Windows architectures, advanced MS Office deployment, and fundamental networking principles.</p>
            <div class="course-details-content" style="display: none;">
                <h4>Course Overview</h4>
                <p>Perfect for beginners, this course builds a strong foundation in essential computer operations and office automation tools required in every modern workplace.</p>
                
                <h4>Course Outline</h4>
                <ul>
                    <li>Windows Operating System Basics</li>
                    <li>Microsoft Office Suite (Word, Excel, PowerPoint)</li>
                    <li>Internet Browsing & Email Etiquette</li>
                </ul>
                
                <h4>Skills You'll Learn</h4>
                <p>Data Entry, Office Automation, Basic Troubleshooting, Document Formatting.</p>
            </div>
            <button class="course-details-btn">Course Details</button>
        </div>

        <!-- Course 2 -->
        <div class="course-card">
            <h3>Diploma in Information Technology (D.I.T)</h3>
            <p>An advanced curriculum encompassing enterprise networking, full-stack web design concepts, office administration, multimedia integrations, and digital computerized accounting systems.</p>
            <div class="course-details-content" style="display: none;">
                <h4>Course Overview</h4>
                <p>An extensive, job-oriented diploma designed to turn novices into multifaceted IT professionals capable of handling diverse technical workflows.</p>
                
                <h4>Course Outline</h4>
                <ul>
                    <li>Advanced MS Office & Database Management</li>
                    <li>Basic Web Designing (HTML/CSS)</li>
                    <li>Computerized Accounting Fundamentals</li>
                </ul>
                
                <h4>Skills You'll Learn</h4>
                <p>Network Configuration, Web Layout Design, Database Setup, IT Administration.</p>
            </div>
            <button class="course-details-btn">Course Details</button>
        </div>

        <!-- Course 3 -->
        <div class="course-card">
            <h3>Graphics, Print & Digital Media Diploma</h3>
            <p>Professional mastery in industry-standard creative suites including Adobe Photoshop, Illustrator, Premiere Pro, and After Effects.</p>
            <div class="course-details-content" style="display: none;">
                <h4>Course Overview</h4>
                <p>Dive into the creative world of digital art. Learn to design stunning visuals, edit professional videos, and create captivating motion graphics.</p>
                
                <h4>Course Outline</h4>
                <ul>
                    <li>Vector Art & Raster Image Editing</li>
                    <li>UI/UX Layout Concepts</li>
                    <li>Video Editing & Motion Graphics</li>
                </ul>
                
                <h4>Skills You'll Learn</h4>
                <p>Brand Identity Design, Video Production, Photo Retouching, Visual Communication.</p>
            </div>
            <button class="course-details-btn">Course Details</button>
        </div>

        <!-- Course 4 -->
        <div class="course-card">
            <h3>Freelancing & Digital Media Marketing</h3>
            <p>Strategic training in Search Engine Optimization (SEO), advanced Social Media Optimization (SMO), performance marketing, and international freelancing via Upwork and Fiverr.</p>
            <div class="course-details-content" style="display: none;">
                <h4>Course Overview</h4>
                <p>Learn how to monetize your skills globally and build high-converting marketing campaigns across major social media platforms.</p>
                
                <h4>Course Outline</h4>
                <ul>
                    <li>Profile Creation & Bidding Strategies</li>
                    <li>SEO & Content Marketing</li>
                    <li>Facebook, Instagram & Google Ads</li>
                </ul>
                
                <h4>Skills You'll Learn</h4>
                <p>Client Acquisition, Ad Campaign Management, Keyword Research, Copywriting.</p>
            </div>
            <button class="course-details-btn">Course Details</button>
        </div>

        <!-- Course 5 -->
        <div class="course-card">
            <h3>AutoCAD 2D & 3D Engineering Design</h3>
            <p>Technical instruction tailored for architects and engineers. Covers drafting submission drawings, complex 3D modeling, photorealistic rendering, and complete structural planning projects.</p>
            <div class="course-details-content" style="display: none;">
                <h4>Course Overview</h4>
                <p>A specialized drafting course that takes you from basic 2D floor plans to intricate 3D architectural and mechanical modeling.</p>
                
                <h4>Course Outline</h4>
                <ul>
                    <li>2D Drafting & Annotation</li>
                    <li>3D Modeling & Workspace Fundamentals</li>
                    <li>Material Application & Rendering</li>
                </ul>
                
                <h4>Skills You'll Learn</h4>
                <p>Blueprint Reading, Precision Drafting, Spatial Visualization, 3D Rendering.</p>
            </div>
            <button class="course-details-btn">Course Details</button>
        </div>

        <!-- Course 6 -->
        <div class="course-card">
            <h3>Web Engineering & Development</h3>
            <p>End-to-end full-stack development training. Master modern front-end technologies (HTML5, CSS3, JavaScript, ReactJS) and robust back-end server architectures.</p>
            <div class="course-details-content" style="display: none;">
                <h4>Course Overview</h4>
                <p>Learn to build dynamic, responsive, and secure web applications from scratch using industry-standard programming languages and frameworks.</p>
                
                <h4>Course Outline</h4>
                <ul>
                    <li>Responsive UI Design (Tailwind/Bootstrap)</li>
                    <li>Interactive Logic (JavaScript/React)</li>
                    <li>Server, API, and Database Management (Node/PHP/MySQL)</li>
                </ul>
                
                <h4>Skills You'll Learn</h4>
                <p>Full-Stack Coding, Database Architecture, API Integration, Version Control (Git).</p>
            </div>
            <button class="course-details-btn">Course Details</button>
        </div>

        <!-- Course 7 -->
        <div class="course-card">
            <h3>Corporate English Language Proficiency</h3>
            <p>Intensive linguistic training aimed at corporate communication. Enhances reading, writing, speaking, and listening proficiencies.</p>
            <div class="course-details-content" style="display: none;">
                <h4>Course Overview</h4>
                <p>Designed to overcome hesitation and build fluency, enabling you to communicate confidently in professional business environments.</p>
                
                <h4>Course Outline</h4>
                <ul>
                    <li>Grammar & Vocabulary Expansion</li>
                    <li>Business Email Writing & Reporting</li>
                    <li>Presentation & Interview Skills</li>
                </ul>
                
                <h4>Skills You'll Learn</h4>
                <p>Public Speaking, Professional Drafting, Active Listening, Persuasive Communication.</p>
            </div>
            <button class="course-details-btn">Course Details</button>
        </div>

        <!-- Course 8 -->
        <div class="course-card">
            <h3>Enterprise Computerized Accounting</h3>
            <p>Specialized financial training utilizing industry-leading software. Students learn to maintain complex corporate ledgers using advanced MS Excel functions, Peachtree, and QuickBooks.</p>
            <div class="course-details-content" style="display: none;">
                <h4>Course Overview</h4>
                <p>Transition from manual bookkeeping to modern digital accounting, mastering the software trusted by enterprises worldwide.</p>
                
                <h4>Course Outline</h4>
                <ul>
                    <li>Advanced Excel for Finance</li>
                    <li>QuickBooks Enterprise Setup</li>
                    <li>Peachtree (Sage 50) Ledger Management</li>
                </ul>
                
                <h4>Skills You'll Learn</h4>
                <p>Financial Reporting, Payroll Management, Inventory Tracking, Tax Computation.</p>
            </div>
            <button class="course-details-btn">Course Details</button>
        </div>

        <!-- Course 9 -->
        <div class="course-card">
            <h3>Fast Track Courses</h3>
            <p>Intensive 4 to 12-week skill-building programs designed for working professionals needing quick upgrades in specific IT tools.</p>
            <div class="course-details-content" style="display: none;">
                <h4>Course Overview</h4>
                <p>High-paced, condensed modules that skip the fluff and focus purely on practical, immediate implementation for career advancement.</p>
                
                <h4>Course Outline</h4>
                <ul>
                    <li>Advanced WordPress Development Bootcamps</li>
                    <li>Expedited MS Office & C.I.T Modules</li>
                    <li>Intensive Print Graphics Production</li>
                </ul>
                
                <h4>Skills You'll Learn</h4>
                <p>Rapid Prototyping, Accelerated Problem Solving, Tool Mastery, Time Management.</p>
            </div>
            <button class="course-details-btn">Course Details</button>
        </div>

        <!-- Course 10 -->
        <div class="course-card">
            <h3>IELTS Preparation</h3>
            <p>Comprehensive training modules covering Reading, Writing, Listening, and Speaking to help you achieve your target band score for study or immigration abroad.</p>
            <div class="course-details-content" style="display: none;">
                <h4>Course Overview</h4>
                <p>A rigorous preparatory program led by expert instructors focusing on mock tests, strategic time management, and specific scoring criteria.</p>
                
                <h4>Course Outline</h4>
                <ul>
                    <li>Academic & General Training Modules</li>
                    <li>Speaking Mock Interviews</li>
                    <li>Advanced Reading Comprehension Techniques</li>
                </ul>
                
                <h4>Skills You'll Learn</h4>
                <p>Skimming & Scanning, Essay Structuring, Accent Comprehension, Exam Pacing.</p>
            </div>
            <button class="course-details-btn">Course Details</button>
        </div>

        <!-- Course 11 -->
        <div class="course-card">
            <h3>Kids Computer Camp</h3>
            <p>An engaging, interactive summer and winter camp program aimed at introducing young minds to the foundational logic of coding, robotics, and digital creativity.</p>
            <div class="course-details-content" style="display: none;">
                <h4>Course Overview</h4>
                <p>A fun-filled environment where kids transition from being tech consumers to tech creators through game-based learning.</p>
                
                <h4>Course Outline</h4>
                <ul>
                    <li>Scratch Visual Programming</li>
                    <li>Digital Painting & Basic Animations</li>
                    <li>Cyber Safety & Typing Fundamentals</li>
                </ul>
                
                <h4>Skills You'll Learn</h4>
                <p>Logical Thinking, Creative Expression, Basic Algorithmic Logic, Keyboard Mastery.</p>
            </div>
            <button class="course-details-btn">Course Details</button>
        </div>

    </div>
</section>

<!-- GLOBAL COURSE DETAILS MODAL -->
<div id="courseModal" class="course-modal-overlay">
    <div class="course-modal-box">
        <button class="modal-close-btn" id="modalCloseBtn">&times;</button>
        <h3 id="modalCourseTitle" class="modal-title">Course Title</h3>
        <div id="modalCourseBody" class="modal-body">
            <!-- Details will be injected dynamically via JS -->
        </div>
    </div>
</div>
    <!-- LOCATION SECTION -->
    <section id="location">
        <h2 class="section-title">Campus <span>Locations</span></h2>
        <div class="location-container">
            <!-- Campus 1: Malir -->
            <div class="campus-card">
                <div class="campus-info">
                    <h4>Primary Campus (Malir)</h4>
                    <p>Plot# A-22, Indus Mehran Society, Saudabad Malir</p>
                    <p>Near 1st P.S.O Petrol Pump, Karachi-75080</p>
                    <p style="margin-top: 10px;"><strong>Support:</strong> 0333-3580212 | 0313-2246517</p>
                </div>
                <div class="campus-map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3618.8766875725755!2d67.2031875!3d24.9021875!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3eb3376842ce87bf%3A0x248e0d3c64612b4b!2sGlobal%20Computer%20%26%20English%20Language%20Institute!5e0!3m2!1sen!2s!4v1784837810195!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                </div>
            </div>

            <!-- Campus 2: Model Colony -->
            <div class="campus-card">
                <div class="campus-info">
                    <h4>Model Colony Campus</h4>
                    <p>Near Railway Crossing, Adjacent to Rabbani Masjid</p>
                    <p>Model Colony, Karachi</p>
                    <p style="margin-top: 10px;"><strong>Support:</strong> 0322-2511944 | 0318-2511944</p>
                </div>
                <div class="campus-map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3618.887683033802!2d67.19306250000001!3d24.9018125!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3eb337610353e805%3A0x265becb78ce97beb!2sGlobal%20Computer%20Institute%20(Campus%202)!5e0!3m2!1sen!2s!4v1784838037523!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                </div>
            </div>

            <!-- Campus 3: Shah Faisal -->
            <div class="campus-card">
                <div class="campus-info">
                    <h4>Shah Faisal Campus</h4>
                    <p>Shahfaisal Colony-3, In proximity to</p>
                    <p>Fauji Foundation Hospital, Karachi</p>
                    <p style="margin-top: 10px;"><strong>Support:</strong> 0317-4740335 | 0347-2763587</p>
                </div>
                <div class="campus-map">
                    <iframe src="https://maps.google.com/maps?q=Fauji+Foundation+Hospital,+Shahfaisal+Colony,+Karachi&t=&z=14&ie=UTF8&iwloc=&output=embed" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- JOIN US SECTION (NEW) -->
    <section id="join-us">
        <h2 class="section-title">Join <span>Our Faculty</span></h2>
        
        <p class="contact-info">
            Want to teach at Global Computer Institute? Fill out the form below to apply.
        </p>

        <!-- enctype is important for file uploads -->

    <form action="" method="POST" enctype="multipart/form-data" class="feedback-form">
    <div class="form-group">
        <input type="text" name="name" placeholder="Full Name" required autocomplete="name">
    </div>
    <div class="form-group">
        <input type="email" name="email" placeholder="Email Address" required autocomplete="email">
    </div>
    <div class="form-group">
        <input type="tel" name="contact" placeholder="Contact Number (e.g. 03001234567)" inputmode="numeric" pattern="[0-9+]{10,14}" required autocomplete="tel">
    </div>
    <div class="form-group">
        <select name="gender" required>
            <option value="" disabled selected>Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
    </div>
    <div class="form-group full-width">
        <input type="file" name="cv" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" required>
    </div>
    <button type="submit" name="join_submit">Submit Application</button>
    </form>
    </section>

    <!-- CONTACT / FEEDBACK SECTION -->
    <section id="contact">
        <h2 class="section-title">Get In <span>Touch</span></h2>
        
        <p class="contact-info">
            Direct Inquiries: <span>gcisbte11@gmail.com</span><br>
            Connect with us on Facebook & Instagram: <span>@Global.Computer.Institute</span>
        </p>

    <form action="" method="POST" class="feedback-form">
    <div class="form-group">
        <input type="text" name="name" placeholder="Your Name" required autocomplete="name">
    </div>
    <div class="form-group">
        <input type="email" name="email" placeholder="Your Email" required autocomplete="email">
    </div>
    <div class="form-group full-width">
        <input type="text" name="subject" placeholder="Subject" required>
    </div>
    <div class="form-group full-width">
        <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
    </div>
    <button type="submit" name="contact_submit">Send Message</button>
    </form>
    </section>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2026 <span>Global Computer Institute</span>. Engineered for Excellence. All rights reserved.</p>
        <p>Designed & Developed by <span>Wania Shah</span></p>
    </footer>
<!-- JAVASCRIPT: SLIDER, MOBILE MENU & MODAL -->
<script>
    // --- 1. MOBILE MENU TOGGLE & AUTO-CLOSE ON CLICK ---
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');
    const navItems = document.querySelectorAll('.nav-links a');

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navLinks.classList.toggle('active');
        });

        navItems.forEach(item => {
            item.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navLinks.classList.remove('active');
            });
        });
    }

    // --- 2. HERO SLIDER LOGIC ---
    const slides = document.querySelectorAll('.slide');
    let currentSlide = 0;

    function nextSlide() {
        if (slides.length === 0) return;
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide + 1) % slides.length;
        slides[currentSlide].classList.add('active');
    }

    if (slides.length > 0) {
        setInterval(nextSlide, 5000);
    }

    // --- 3. COURSE MODAL POPUP LOGIC ---
    window.openModal = function(title, contentHtml) {
        const courseModal = document.getElementById('courseModal');
        // Fixed IDs matching HTML
        const modalTitle = document.getElementById('modalCourseTitle');
        const modalBody = document.getElementById('modalCourseBody');

        if (courseModal && modalTitle && modalBody) {
            modalTitle.textContent = title;
            modalBody.innerHTML = contentHtml;
            courseModal.classList.add('active');
            document.body.style.overflow = 'hidden'; // Stop background scroll
        }
    };

    window.closeModal = function() {
        const courseModal = document.getElementById('courseModal');
        if (courseModal) {
            courseModal.classList.remove('active');
            document.body.style.overflow = 'auto'; // Restore background scroll
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        // Automatically attach click listener to all "Course Details" buttons
        const detailButtons = document.querySelectorAll('.course-details-btn');
        detailButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const card = this.closest('.course-card');
                const title = card.querySelector('h3').innerText;
                const contentHtml = card.querySelector('.course-details-content').innerHTML;
                window.openModal(title, contentHtml);
            });
        });

        const courseModal = document.getElementById('courseModal');
        const closeBtn = document.getElementById('modalCloseBtn');

        // Close on 'X' button
        if (closeBtn) {
            closeBtn.addEventListener('click', window.closeModal);
        }

        // Close on backdrop click
        if (courseModal) {
            courseModal.addEventListener('click', (e) => {
                if (e.target === courseModal) {
                    window.closeModal();
                }
            });
        }

        // Close on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                window.closeModal();
            }
        });
    });

    // --- 4. PHONE INPUT RESTRICTION (NUMBERS & '+' ONLY) ---
    const phoneInputs = document.querySelectorAll('input[type="tel"], input[name="contact"]');

    phoneInputs.forEach(input => {
        input.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/[^0-9+]/g, '');
        });
    });
</script>
</body>
</html>