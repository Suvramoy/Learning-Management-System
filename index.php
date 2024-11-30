<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Management System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Existing Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
}

:root {
    --primary: #4F46E5;
    --secondary: #818CF8;
    --accent: #F472B6;
    --background: #F3F4F6;
    --white: #FFFFFF;
    --text: #1F2937;
}

body {
    background-color: var(--background);
    overflow-x: hidden;
}

/* Mobile Menu Styles */
.mobile-menu-btn {
    display: none;
    background: none;
    border: none;
    font-size: 1.5rem;
    color: var(--text);
    cursor: pointer;
}

.mobile-nav {
    display: none;
    position: fixed;
    top: 70px;
    left: 0;
    right: 0;
    background: var(--white);
    padding: 1rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    z-index: 999;
}

.mobile-nav.active {
    display: block;
}

.mobile-nav a {
    display: block;
    padding: 0.8rem;
    text-decoration: none;
    color: var(--text);
    border-bottom: 1px solid #eee;
}

.mobile-nav .nav-buttons {
    padding: 1rem 0;
}

/* Responsive Navbar */
.navbar {
    background: var(--white);
    padding: 1rem 2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
}

.logo {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--primary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    z-index: 1001;
}

.nav-links {
    display: flex;
    gap: 2rem;
}

.nav-links a {
    text-decoration: none;
    color: var(--text);
    font-weight: 500;
    transition: color 0.3s;
}

.nav-links a:hover {
    color: var(--primary);
}

.nav-buttons {
    display: flex;
    gap: 1rem;
}

.btn {
    padding: 0.5rem 1.5rem;
    border-radius: 0.5rem;
    border: none;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s;
    white-space: nowrap;
    text-decoration: none;
}

.btn-primary {
    background: var(--primary);
    color: var(--white);
}

.btn-primary:hover {
    background: var(--secondary);
}

.btn-outline {
    border: 2px solid var(--primary);
    color: var(--primary);
    background: transparent;
}

.btn-outline:hover {
    background: var(--primary);
    color: var(--white);
}

/* Responsive Hero Section */
.hero {
    margin-top: 70px;
    padding: 4rem 1rem;
    text-align: center;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: var(--white);
}

.hero h1 {
    font-size: clamp(2rem, 5vw, 3.5rem);
    margin-bottom: 1.5rem;
    padding: 0 1rem;
}

.hero p {
    font-size: clamp(1rem, 3vw, 1.2rem);
    margin-bottom: 2rem;
    max-width: 600px;
    margin-inline: auto;
    padding: 0 1rem;
}

/* Responsive Features Section */
.features {
    padding: 4rem 1rem;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.feature-card {
    background: var(--white);
    padding: 2rem;
    border-radius: 1rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

/* Responsive Courses Section */
.courses {
    padding: 4rem 1rem;
    max-width: 1200px;
    margin: 0 auto;
}

.course-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
}

/* Course Card Styles */
.course-card {
    border-radius: 8px;
    overflow: hidden;
    background: var(--white);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.course-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 12px rgba(0,0,0,0.2);
}

.course-image {
    height: 180px;
    background-size: cover;
    background-position: center;
}

.course-content {
    padding: 1rem;
    text-align: center;
}

.course-content h3 {
    font-size: 1.5rem;
    color: var(--primary);
    margin-bottom: 0.5rem;
}

.course-content p {
    font-size: 1rem;
    color: var(--text);
    line-height: 1.5;
}

/* Specific Background Images for Each Course */
.course-card.web-development .course-image {
    background-image: url('web.jpg'); /* Replace with your actual image URL */
}

.course-card.data-science .course-image {
    background-image: url('data-science.webp'); /* Replace with your actual image URL */
}

.course-card.digital-marketing .course-image {
    background-image: url('digital.jpg'); /* Replace with your actual image URL */
}

/* Responsive Stats Section */
.stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
    background: var(--white);
    padding: 4rem 1rem;
    margin: 2rem 0;
    text-align: center;
}

/* Responsive Footer */
footer {
    background: var(--text);
    color: var(--white);
    padding: 4rem 1rem;
    margin-top: 4rem;
}

.footer-content {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
}

/* Added styles for footer links */
.footer-links {
    list-style: none;
    padding: 0;
}

.footer-links li {
    margin: 0.5rem 0;
}

.footer-links a {
    color: var(--white);
    text-decoration: none;  /* Remove underlines */
    opacity: 0.8;
    transition: opacity 0.3s;
}

.footer-links a:hover {
    opacity: 1;
}

/* Medium Screens */
@media (max-width: 968px) {
    .nav-links, .nav-buttons {
        display: none;
    }

    .mobile-menu-btn {
        display: block;
    }

    .stats {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Small Screens */
@media (max-width: 640px) {
    .hero {
        padding: 3rem 1rem;
    }

    .features {
        grid-template-columns: 1fr;
    }

    .course-grid {
        grid-template-columns: 1fr;
    }

    .stats {
        grid-template-columns: 1fr;
    }

    .footer-content {
        grid-template-columns: 1fr;
        text-align: center;
    }
}

    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <i class="fas fa-graduation-cap"></i>
            Learning Management System
        </div>
        <div class="nav-links">
            <a href="#">Home</a>
            <a href="#">Courses</a>
            <a href="#">Instructors</a>
            <a href="#">Contact</a>
        </div>
        <div class="nav-buttons">
            <a href="signIn.php" class="btn btn-outline">Log In</button>
            <a href="signUp.php" class="btn btn-primary">Sign Up</a>
        </div>
        <button class="mobile-menu-btn">
            <i class="fas fa-bars"></i>
        </button>
    </nav>

    <div class="mobile-nav">
        <a href="#">Home</a>
        <a href="#">Courses</a>
        <a href="#">Instructors</a>
        <a href="#">Contact</a>
        <div class="nav-buttons">
            <a href="signIn.php" class="btn btn-outline">Log In</button>
            <a href="signUp.php" class="btn btn-primary">Sign Up</a>
        </div>
    </div>

    <section class="hero">
        <h1>Transform Your Learning Journey</h1>
        <p>Experience the future of education with our advanced learning management system. Interactive courses, real-time collaboration, and personalized learning paths.</p>
        <a href="signUp.php" class="btn btn-primary">Get Started</a>
    </section>

    <section class="features">
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-laptop-code"></i>
            </div>
            <h3>Interactive Learning</h3>
            <p>Engage with dynamic content, quizzes, and assignments that make learning fun and effective.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3>Community Driven</h3>
            <p>Connect with peers, participate in discussions, and learn together in a collaborative environment.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <h3>Track Progress</h3>
            <p>Monitor your learning journey with detailed analytics and personalized insights.</p>
        </div>
    </section>

    <section class="courses">
    <h2 class="section-title">Featured Courses</h2>
    <div class="course-grid">
        <div class="course-card web-development">
            <div class="course-image"></div>
            <div class="course-content">
                <h3>Web Development</h3>
                <p>Learn modern web development with HTML, CSS, and JavaScript.</p>
            </div>
        </div>
        <div class="course-card data-science">
            <div class="course-image"></div>
            <div class="course-content">
                <h3>Data Science</h3>
                <p>Master data analysis and machine learning fundamentals.</p>
            </div>
        </div>
        <div class="course-card digital-marketing">
            <div class="course-image"></div>
            <div class="course-content">
                <h3>Digital Marketing</h3>
                <p>Discover strategies to grow your online presence.</p>
            </div>
        </div>
    </div>
</section>


    <section class="stats">
        <div class="stat-item">
            <div class="stat-number">10K+</div>
            <div class="stat-label">Active Students</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">500+</div>
            <div class="stat-label">Courses</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">50+</div>
            <div class="stat-label">Expert Instructors</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">95%</div>
            <div class="stat-label">Success Rate</div>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>About Us</h3>
                <ul class="footer-links">
                    <li><a href="#">Our Story</a></li>
                    <li><a href="#">Team</a></li>
                    <li><a href="#">Careers</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Resources</h3>
                <ul class="footer-links">
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Guidelines</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Legal</h3>
                <ul class="footer-links">
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Cookie Policy</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Connect</h3>
                <ul class="footer-links">
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">LinkedIn</a></li>
                    <li><a href="#">Facebook</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <script>
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const mobileNav = document.querySelector('.mobile-nav');

        mobileMenuBtn.addEventListener('click', () => {
            mobileNav.classList.toggle('active');
            mobileMenuBtn.querySelector('i').classList.toggle('fa-bars');
            mobileMenuBtn.querySelector('i').classList.toggle('fa-times');
        });
    </script>
</body>
</html>