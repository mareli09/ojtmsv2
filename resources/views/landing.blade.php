<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>OJTMS | AI-Assisted OJT Monitoring System</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
/* BRAND COLORS */
:root{
    --ojtms-primary:#000156;
    --ojtms-accent:#f4b61b;
    --ojtms-light:#edeef0;
    --ojtms-dark:#0a0a29;
}

/* NAVBAR */
.navbar-ojtms{
    background-color:var(--ojtms-primary);
    box-shadow: 0 2px 10px rgba(0,1,86,0.3);
}

.navbar-ojtms .nav-link{
    color:rgba(255,255,255,.85);
    font-weight:500;
    transition: all 0.3s;
}

.navbar-ojtms .nav-link:hover,
.navbar-ojtms .nav-link.active{
    color:var(--ojtms-accent);
    text-decoration:underline;
    text-underline-offset:4px;
}

.navbar-ojtms .navbar-brand{
    color:var(--ojtms-accent);
    font-weight:bold;
    font-size:1.5rem;
}

/* HERO SECTION */
.hero-section{
    background: linear-gradient(135deg, var(--ojtms-primary) 0%, var(--ojtms-dark) 100%);
    color:white;
    padding: 80px 20px;
}

.hero-section h1{
    font-weight:900;
    margin-bottom:20px;
    color:var(--ojtms-accent);
}

.hero-section p{
    font-size:1.2rem;
    color:rgba(255,255,255,0.9);
}

/* ABOUT SECTION */
.about-section{
    background-color:var(--ojtms-light);
    padding: 60px 20px;
}

.about-box{
    background:white;
    border-left: 5px solid var(--ojtms-accent);
    box-shadow: 0 4px 15px rgba(0,1,86,0.08);
}

/* MISSION & VISION */
.mission-box, .vision-box{
    background:white;
    border-top: 4px solid var(--ojtms-accent);
    box-shadow: 0 4px 15px rgba(0,1,86,0.08);
}

.mission-box h4, .vision-box h4{
    color:var(--ojtms-primary);
    font-weight:bold;
}

.mission-box i, .vision-box i{
    color:var(--ojtms-accent);
}

/* ANNOUNCEMENTS */
.announcements-section{
    background:white;
}

.announcement-card{
    border-left: 4px solid var(--ojtms-accent);
    box-shadow: 0 2px 8px rgba(0,1,86,0.08);
    transition: all 0.3s;
}

.announcement-card:hover{
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0,1,86,0.12);
    border-left-color:var(--ojtms-primary);
}

.announcement-card h5{
    color:var(--ojtms-primary);
}

.announcement-date{
    color:var(--ojtms-accent);
    font-weight:600;
}

/* CONTACT SECTION */
.contact-section{
    background-color:var(--ojtms-light);
}

.contact-form-box{
    background:white;
    box-shadow: 0 4px 15px rgba(0,1,86,0.1);
}

.contact-info-box{
    background:white;
    box-shadow: 0 4px 15px rgba(0,1,86,0.1);
}

.contact-form-box h3, .contact-info-box h3{
    color:var(--ojtms-primary);
}

.contact-form-box h3 i, .contact-info-box h3 i{
    color:var(--ojtms-accent);
}

/* FORM CONTROLS */
.form-control{
    border:2px solid var(--ojtms-light);
    border-radius:5px;
}

.form-control:focus{
    border-color:var(--ojtms-accent);
    box-shadow: 0 0 0 0.2rem rgba(244,182,27,0.15);
}

/* BUTTONS */
.btn-ojtms{
    background-color:var(--ojtms-primary);
    border:none;
    color:var(--ojtms-accent);
    font-weight:600;
    transition: all 0.3s;
}

.btn-ojtms:hover{
    background-color:var(--ojtms-dark);
    color:var(--ojtms-accent);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,1,86,0.2);
}

.btn-ojtms-outline{
    border:2px solid var(--ojtms-accent);
    color:var(--ojtms-accent);
    background-color:transparent;
    font-weight:600;
}

.btn-ojtms-outline:hover{
    background-color:var(--ojtms-accent);
    color:var(--ojtms-primary);
}

/* SOCIAL ICONS */
.social-icon{
    width:45px;
    height:45px;
    border-radius:50%;
    background:var(--ojtms-accent);
    color:var(--ojtms-primary);
    display:inline-flex;
    align-items:center;
    justify-content:center;
    margin:0 8px;
    font-size:18px;
    transition: all 0.3s;
    text-decoration:none;
}

.social-icon:hover{
    background:var(--ojtms-primary);
    color:var(--ojtms-accent);
    transform: scale(1.15);
}

/* FOOTER */
.footer-ojtms{
    background-color:var(--ojtms-primary);
    color:white;
}

.footer-ojtms a{
    color:var(--ojtms-accent);
    text-decoration:none;
    transition: all 0.3s;
}

.footer-ojtms a:hover{
    color:white;
    text-decoration:underline;
}

/* SECTION HEADERS */
.section-header{
    color:var(--ojtms-primary);
    font-weight:bold;
    font-size:2rem;
    margin-bottom:30px;
}

.section-header::after{
    content:'';
    display:block;
    width:60px;
    height:4px;
    background-color:var(--ojtms-accent);
    margin-top:10px;
}

/* GENERAL */
body{
    font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.text-primary-custom{
    color:var(--ojtms-primary);
}

.text-accent-custom{
    color:var(--ojtms-accent);
}

.bg-accent-light{
    background-color:var(--ojtms-light);
}
</style>

</head>

<body>
    
<!-- ===== NAVBAR ===== -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-ojtms px-4 sticky-top">
    <a class="navbar-brand" href="/">
        <i class="fas fa-graduation-cap me-2"></i>OJTMS
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navMenu">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link active" href="#home">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#about">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#announcements">Announcements</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#contact">Contact</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/login" style="background:var(--ojtms-accent); color:var(--ojtms-primary); border-radius:5px; padding:5px 15px; font-weight:600;">Login</a>
            </li>
        </ul>
    </div>
</nav>

<!-- ===== HERO SECTION ===== -->
<section id="home" class="hero-section text-center">
<div class="container">
    <h1 class="display-3 fw-bold mb-3">{{ $cms['header'] ?? 'AI-Assisted OJT Monitoring System' }}</h1>
    <p class="lead mb-5">{{ $cms['subheader'] ?? 'Streamline On-the-Job Training Management with Intelligent Analytics' }}</p>
    <div>
        <a href="/login" class="btn btn-ojtms btn-lg fw-bold me-3">Get Started</a>
        <a href="#about" class="btn btn-ojtms-outline btn-lg fw-bold">Learn More</a>
    </div>
</div>
</section>

<!-- ===== ABOUT US ===== -->
<section id="about" class="about-section">
<div class="container">
    <h2 class="section-header">About OJTMS</h2>
    
    <div class="about-box p-4 rounded mb-4">
        <p class="mb-0 text-primary-custom">
            {{ $cms['about'] ?? 'The OJT Monitoring System (OJTMS) is committed to fostering meaningful partnerships between educational institutions, companies, and students through comprehensive on-the-job training management, real-time monitoring, and intelligent analytics.' }}
        </p>
    </div>

    <div class="row g-4">
        <!-- MISSION -->
        <div class="col-md-6">
            <div class="mission-box p-4 rounded h-100">
                <h4 class="mb-3"><i class="fas fa-bullseye me-2"></i>Mission</h4>
                <p class="text-primary-custom">
                    {{ $cms['mission'] ?? 'To empower students and educators through an intelligent, integrated platform that monitors OJT progress, ensures quality internship experiences, and facilitates meaningful skill development.' }}
                </p>
            </div>
        </div>

        <!-- VISION -->
        <div class="col-md-6">
            <div class="vision-box p-4 rounded h-100">
                <h4 class="mb-3"><i class="fas fa-eye me-2"></i>Vision</h4>
                <p class="text-primary-custom">
                    {{ $cms['vision'] ?? 'A comprehensive platform leveraging AI and data analytics to create transparent, measurable, and transformative internship experiences that bridge academia and industry.' }}
                </p>
            </div>
        </div>
    </div>
</div>
</section>

<!-- ===== ANNOUNCEMENTS ===== -->
<section id="announcements" class="announcements-section py-5">
<div class="container">
    <h2 class="section-header">Latest Announcements</h2>
    
    <div class="row g-4">
        @foreach($announcements as $announcement)
        <div class="col-md-6 col-lg-4">
            <div class="announcement-card bg-white p-4 rounded h-100">
                <h5 class="fw-bold mb-2">{{ $announcement->title }}</h5>
                <p class="announcement-date small">
                    <i class="far fa-calendar me-2"></i>{{ $announcement->created_at->format('M d, Y') }}
                </p>
                <p class="text-primary-custom">{{ Str::limit($announcement->content, 100) }}</p>
                <a href="#" class="text-accent-custom fw-600" style="text-decoration:none;">Read More →</a>
            </div>
        </div>
        @endforeach
    </div>

    @if($announcements->isEmpty())
    <div class="alert alert-info text-center" style="background-color:var(--ojtms-light); border-color:var(--ojtms-primary);">
        <p class="text-primary-custom mb-0">No announcements at this time. Check back soon!</p>
    </div>
    @endif
</div>
</section>

<!-- ===== CONTACT SECTION ===== -->
<section id="contact" class="contact-section py-5">
<div class="container">
    <h2 class="section-header mb-5">Get In Touch</h2>
    
    <div class="row g-4">

        <!-- CONTACT FORM -->
        <div class="col-md-6">
            <div class="contact-form-box p-5 rounded h-100">
                <h3 class="fw-bold mb-4"><i class="fas fa-envelope me-2"></i>Contact Us</h3>
                <form>
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Full Name" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" class="form-control" placeholder="Email Address" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Subject" required>
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" rows="5" placeholder="Your Message" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-ojtms w-100">Send Message</button>
                </form>
            </div>
        </div>

        <!-- CONTACT INFO -->
        <div class="col-md-6">
            <div class="contact-info-box p-5 rounded h-100">
                <h3 class="fw-bold mb-4"><i class="fas fa-phone me-2"></i>Contact Information</h3>
                
                <div class="mb-4">
                    <p class="text-primary-custom fw-bold mb-1">Email Address</p>
                    <p class="text-muted">{{ $cms['contact_email'] ?? 'ojtms@example.edu.ph' }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-primary-custom fw-bold mb-1">Phone Number</p>
                    <p class="text-muted">{{ $cms['contact_phone'] ?? '+63 900 000 0000' }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-primary-custom fw-bold mb-1">Office Address</p>
                    <p class="text-muted">{{ $cms['contact_address'] ?? 'Sample City, Philippines' }}</p>
                </div>

                <hr style="border-color:var(--ojtms-light);">

                <p class="text-primary-custom fw-bold mb-3">Follow Us</p>
                <div>
                    <a href="{{ $cms['facebook_url'] ?? '#' }}" target="_blank" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="{{ $cms['instagram_url'] ?? '#' }}" target="_blank" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="{{ $cms['linkedin_url'] ?? '#' }}" target="_blank" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    <a href="{{ $cms['twitter_url'] ?? '#' }}" target="_blank" class="social-icon"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>

    </div>
</div>
</section>

<!-- ===== FOOTER ===== -->
<footer class="footer-ojtms text-center py-4">

<div class="mb-3">
    <a href="{{ $cms['facebook_url'] ?? '#' }}" target="_blank" class="social-icon"><i class="fab fa-facebook-f"></i></a>
    <a href="{{ $cms['instagram_url'] ?? '#' }}" target="_blank" class="social-icon"><i class="fab fa-instagram"></i></a>
    <a href="{{ $cms['youtube_url'] ?? '#' }}" target="_blank" class="social-icon"><i class="fab fa-youtube"></i></a>
    <a href="{{ $cms['twitter_url'] ?? '#' }}" target="_blank" class="social-icon"><i class="fab fa-twitter"></i></a>
</div>

<div class="mb-2">
    <a href="#" class="me-3">Privacy Policy</a>
    <span class="text-muted">|</span>
    <a href="#" class="mx-3">Terms of Service</a>
    <span class="text-muted">|</span>
    <a href="#" class="ms-3">Accessibility</a>
</div>

<div class="small mt-3">
    © 2026 OJT Monitoring System. All rights reserved.
</div>

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
