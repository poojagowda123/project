<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to GigCircle</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: #020024;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
            scroll-behavior: smooth;
        }
        .navbar {
            background-color: rgba(255, 255, 255, 0.0);
            backdrop-filter: blur(10px);
            padding: 25px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }
        .navbar .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
        }
        .navbar ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }
        .navbar ul li a {
            text-decoration: none;
            color: white;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .navbar ul li a:hover {
            color: #00D4FF;
        }
        .hero-section {
            position: relative;
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            text-align: center;
            color: white;
        }
        .rectangle-blur {
            position: relative;
            width: 90%;
            max-width: 1200px;
            background: rgba(217, 217, 217, 0.1);
            border: 1px solid #FFFFFF;
            box-shadow: inset 75px -75px 75px rgba(165, 165, 165, 0.1),
                        inset -75px 75px 75px rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(75px);
            border-radius: 25px;
            padding: 60px 40px;
            z-index: 2;
        }
        .ellipse {
            position: absolute;
            width: 500px;
            height: 500px;
            background: linear-gradient(202.97deg, #090979 10%, #00D4FF 80%);
            filter: blur(125px);
            transform: rotate(-90deg);
            top: -100px;
            right: -60px;
            z-index: 0;
        }
         .ellipse-1 {
            position: absolute;
            width: 500px;
            height: 500px;
            background: linear-gradient(202.97deg, #090979 10%, #00D4FF 90%);
            filter: blur(125px);
            transform: rotate(-90deg);
            top: -100px;
            left: -60px;
            z-index: 0;
        }
        .hero {
            position: relative;
            z-index: 1;
        }
        .hero video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
            opacity: 0.8;
        }
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 10px;
            text-shadow: 1px 1px 5px rgba(0,0,0,0.4);
        }
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 25px;
            text-shadow: 1px 1px 5px rgba(0,0,0,0.4);
        }
        .hero a {
            display: inline-block;
            text-decoration: none;
            background-color: white;
            color: black;
            padding: 12px 25px;
            margin: 10px;
            border-radius: 30px;
            font-weight: 600;
            box-shadow: 0 5px 12px rgba(255, 148, 114, 0.4);
            transition: background 0.3s ease;
        }
        .hero a:hover {
            background-color: #00D4FF;
        }
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }
            .navbar ul {
                flex-direction: column;
                gap: 10px;
                margin-top: 10px;
            }
            .hero h1 {
                font-size: 2rem;
            }
            .hero p {
                font-size: 1rem;
            }
        }
        .features-section {
            background: linear-gradient(160deg, #020024);
            color: white;
            padding: 100px 40px;
            text-align: center;
            position: relative;
            
        }

.feature-box.in-view {
  transform: translateY(0);
  opacity: 1;
}

        .features-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, transparent, rgba(0,212,255,0.1), transparent);
            animation: slideIn 5s infinite linear;
        }
        @keyframes slideIn {
            0% { left: -100%; }
            50% { left: 100%; }
            100% { left: 100%; }
        }
        .features-section h2 {
            color: #ffffff;
            font-size: 2.8rem;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }
        /* .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            position: relative;
            z-index: 1;
        } */
         .features-marquee {
    overflow: hidden;
    width: 100%;
    position: relative;
    margin: 0 auto;
}

.marquee-track {
    display: flex;
    gap: 30px;
    animation: scrollMarquee 15s linear infinite;
    width: max-content;
}

@keyframes scrollMarquee {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}



        .feature-box {
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            transform: translateX(100px);
            opacity: 0;
            /* animation: fadeSlide 3s forwards; */
             /* transform: translateY(50px); */
            /* transition: all 0.6s ease-out; */
            transition: transform 0.6s ease, opacity 0.8s ease;
            flex-shrink: 0;
  
        }
        .feature-box.in-view {
  
  opacity: 1;
}
        .feature-box:nth-child(2) { animation-delay: 0.4s; }
        .feature-box:nth-child(3) { animation-delay: 0.6s; }
        .feature-box:nth-child(4) { animation-delay: 0.8s; }
        @keyframes fadeSlide {
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .feature-box:hover {
            background: rgba(0, 212, 255, 0.1);
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 212, 255, 0.3);
        }
        .feature-box i {
            font-size: 2rem;
            margin-bottom: 15px;
            color: #00D4FF;
        }
        .feature-box h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .feature-box p {
            font-size: 1rem;
            color: #ccc;
        }
          .how-it-works {
            background: #020024;
            color: white;
            padding: 100px 40px;
            text-align: center;
            
        }

        .how-it-works h2 {
            color: #ffffff;
            font-size: 2.8rem;
            margin-bottom: 40px;
        }

        .how-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 40px;
        }

      .how-step {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid #439daf;
    border-radius: 16px;
    width: 300px;
    padding: 30px;
    transition: transform 0.4s ease-in-out, box-shadow 0.4s ease;
    transform-origin: center center;
    animation: fadeInUp 0.8s ease forwards;
    transform: none;
}

.how-step:hover {
   transform:  scale(1.5);
    box-shadow: 0 15px 25px rgba(0, 212, 255, 0.3);
    z-index: 2;
}

        .how-step h3 {
            color: #ffffff;
            margin-bottom: 15px;
        }

        .how-step p {
            color: #ddd;
        }

        /* .image-placeholders {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 60px;
            flex-wrap: wrap;
        }

        .image-box {
            width: 300px;
            height: 200px;
            background: #090979;
            border: 2px dashed #00D4FF;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #00D4FF;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .image-box:hover {
            background: #00D4FF;
            color: #090979;
        } */
        .carousel-wrapper {
    width: 90%;
    margin: 50px auto;
}

.image-box {
    width: 100%;
    height: 400px;
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    border: 2px solid #00D4FF;
    box-shadow: 0 0 15px rgba(0, 212, 255, 0.5);
}

.image-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.image-box p {
    position: absolute;
    bottom: 0;
    margin: 0;
    width: 100%;
    background: rgba(0, 212, 255, 0.85);
    color: #090979;
    font-weight: bold;
    padding: 12px;
    text-align: center;
    font-size: 18px;
}


     @keyframes fadeInUp {
    from {
        opacity: 0;
        
    }
    to {
        opacity: 1;
        
    }
}
    </style>
</head>
<body>
<!-- Remaining HTML untouched -->
 <header class="navbar">
        <a href="#" class="logo">GigCircle</a>
        <ul>
            <li><a href="#">Browse Jobs</a></li>
            <li><a href="register.php">Post a Job</a></li>
            <li><a href="#how">How It Works</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Sign Up</a></li>
        </ul>
    </header>

    <section class="hero-section">
        <div class="ellipse"></div>
        <div class="rectangle-blur">
            <div class="hero">
                <video autoplay muted loop playsinline>
                    <source src="./assets/index.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <h1>Welcome to GigCircle</h1>
                <p id="typeWriter"></p>
                <a href="register.php">Get Started</a>
            </div>
        </div>
    </section>

    <section class="features-section">
         <div class="ellipse-1"></div>
        <h2>Platform Highlights</h2>
        <div class="features-grid">
            <div class="features-marquee">
        <div class="marquee-track">
            <div class="feature-box">
                <i class="fas fa-user-tie"></i>
                <h3>Top Talent</h3>
                <p>Hire skilled professionals with proven track records and verified portfolios.</p>
            </div>
            <div class="feature-box">
                <i class="fas fa-bolt"></i>
                <h3>Fast Proposals</h3>
                <p>Post a job and receive high-quality bids in seconds, not hours.</p>
            </div>
            <div class="feature-box">
                <i class="fas fa-shield-alt"></i>
                <h3>Secure Payments</h3>
                <p>We hold your funds safely and release them when you're satisfied.</p>
            </div>
            <div class="feature-box">
                <i class="fas fa-chart-line"></i>
                <h3>Progress Tracking</h3>
                <p>Monitor your project milestones and communicate with freelancers easily.</p>
            </div>
            </div>
            </div>
        </div>
    </section>

    <section id="how" class="how-it-works">
        <h2>How It Works</h2>
        <div class="how-grid">
            <div class="how-step">
                <h3>1. Register & Log In</h3>
                <p>Create your account and log in to start exploring opportunities.</p>
            </div>
            <div class="how-step">
                <h3>2. Post or Find a Job</h3>
                <p>Post your project or browse gigs suited to your skills.</p>
            </div>
            <div class="how-step">
                <h3>3. Collaborate</h3>
                <p>Communicate, track tasks, and share progress seamlessly.</p>
            </div>
            <div class="how-step">
                <h3>4. Get Paid</h3>
                <p>Secure payment system ensures you’re paid fairly and fast.</p>
            </div>
        </div>
       <div class="carousel-wrapper">
    <div class="image-carousel">
        <div class="image-box">
            <img src="assets/images/dashboard.png" alt="Dashboard Screenshot">
            
        </div>
        <div class="image-box">
            <img src="assets/images/postjob.png" alt="Post a Job Screenshot">
            
        </div>
        <div class="image-box">
            <img src="assets/images/apply.png" alt="Apply Page Screenshot">
            
        </div>
    </div>
</div>

    </section>

    <footer style="background-color: #020024; color: #ccc; text-align: center; padding: 40px 20px; font-family: 'Poppins', sans-serif;">
        <h2 style="color: #00D4FF; font-size: 1.8rem; margin-bottom: 8px;">GigCircle</h2>
        <p style="margin: 8px 0 20px; font-size: 0.95rem;">Empowering Freelancers. Connecting Ideas.</p>
        <div style="margin: 20px 0;">
            <a href="#" style="margin: 0 10px; font-size: 1.2rem; color: #ccc;"><i class="fab fa-facebook-f"></i></a>
            <a href="#" style="margin: 0 10px; font-size: 1.2rem; color: #ccc;"><i class="fab fa-instagram"></i></a>
            <a href="#" style="margin: 0 10px; font-size: 1.2rem; color: #ccc;"><i class="fab fa-twitter"></i></a>
            <a href="#" style="margin: 0 10px; font-size: 1.2rem; color: #ccc;"><i class="fab fa-linkedin-in"></i></a>
        </div>
        <p style="margin-top: 20px; font-size: 0.8rem; color: #666;">© 2025 GigCircle. All rights reserved.</p>
    </footer>
 <!-- Slick CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>

<!-- jQuery (required for Slick) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Slick JS -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <script>
        const text = "Your Freelance Journey Begins Now"; 
         const speed = 100; // Typing speed in milliseconds
    let i = 0;
    function typeWriter() {
        if (i < text.length) {
            document.getElementById("typeWriter").innerHTML += text.charAt(i);
            i++;
            setTimeout(typeWriter, speed);
        }
    }

document.addEventListener("DOMContentLoaded", () => {
    const boxes = document.querySelectorAll(".feature-box");

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("in-view");
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.4 });

    boxes.forEach((box, index) => {
        observer.observe(box);
    });

    // ✅ Start typewriter animation
    typeWriter();
});


$(document).ready(function(){
    $('.image-carousel').slick({
        dots: true,
        infinite: true,
        speed: 500,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,            // ENABLE AUTOPLAY
        autoplaySpeed: 2500,       // 2.5 seconds per slide
        arrows: true
    });
});


    
    </script>



   
</body>
</html>
