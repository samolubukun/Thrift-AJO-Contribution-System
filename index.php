<?php include 'header.php'; ?>

<style>
    /* Sleek modern design styles */
    body {
        font-family: 'Inter', 'Segoe UI', sans-serif;
        background: #f8f9fa;
        color: #2d3142;
        line-height: 1.6;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .intro {
        text-align: center;
        padding: 3rem 2rem; /* Reduced top padding slightly */
        background: linear-gradient(135deg, rgba(45, 49, 66, 0.03), rgba(79, 93, 117, 0.05));
        border-radius: 12px;
        margin: 2.2rem auto; /* Reduced top margin slightly */
        max-width: 1000px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.04);
    }

    .intro h2 {
        font-size: 2.7rem; /* Slightly larger for impact */
        margin-bottom: 1rem; /* Reduced margin */
        font-weight: 700;
        color: #2d3142;
        letter-spacing: -0.7px; /* More modern letter spacing */
    }

    .intro p {
        font-size: 1.15rem; /* Slightly larger for better readability */
        max-width: 850px; /* Wider for better flow */
        margin: 1rem auto; /* Added top margin for spacing */
        color: #5a677d; /* Slightly softer text color */
        line-height: 1.7; /* Improved line height */
    }

    .actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 1.5rem; /* Slightly reduced gap */
        padding: 1.5rem 1rem 3rem; /* Reduced top padding */
        max-width: 1200px;
        margin: -1.8cm auto;
    }

    .action-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 2rem; /* Reduced padding slightly */
        flex: 1;
        min-width: 280px;
        max-width: 350px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06); /* Softer shadow */
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.04); /* Lighter border */
    }

    .action-card:hover {
        transform: translateY(-8px); /* Slightly less lift */
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
    }

    .action-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px; /* Slightly thinner accent line */
        background: linear-gradient(90deg, #354052, #5a677d); /* Softer gradient */
        opacity: 0.7; /* Slightly less opaque */
    }

    .action-card h3 {
        font-size: 1.6rem; /* Slightly larger heading */
        margin-bottom: 0.8rem; /* Reduced margin */
        color: #354052; /* Darker heading color */
        font-weight: 600;
    }

    .action-card p {
        color: #5a677d;
        margin-bottom: 1.5rem; /* Reduced margin */
        font-size: 1.05rem; /* Slightly larger text */
        line-height: 1.65; /* Improved line height */
    }

    .btn {
        display: inline-block;
        padding: 10px 22px; /* Slightly smaller button */
        background: linear-gradient(135deg, #354052, #5a677d); /* Softer gradient */
        color: white;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 500;
        letter-spacing: 0.4px; /* Slightly tighter letter spacing */
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 3px 12px rgba(53, 64, 82, 0.2); /* Softer shadow */
    }

    .btn:hover {
        transform: translateY(-2px); /* Less lift on hover */
        box-shadow: 0 6px 15px rgba(53, 64, 82, 0.3);
        color: white;
        text-decoration: none;
    }

    footer {
        background: #354052; /* Darker footer background */
        color: #d1d8e0; /* Lighter footer text */
        text-align: center;
        padding: 1.5rem 0; /* Reduced padding */
        margin-top: 2.5rem; /* Reduced top margin */
        font-size: 0.95rem; /* Slightly larger font */
    }

    @media (max-width: 768px) {
        .actions {
            flex-direction: column;
            align-items: center;
        }

        .action-card {
            width: 100%;
            max-width: 450px;
        }

        .intro h2 {
            font-size: 2.3rem;
        }
    }
</style>

<div class="container">
    <section class="intro" style="padding-top: 2.5rem; margin-top: 1rem;"> <h2 style="margin-bottom: 0.8rem;">Empowering Your Financial Community</h2> <p>
    Thrift Contribution System that empowers individual collectors to easily manage contributions across their organized groups.
        </p>
    </section>

    <section class="actions" style="padding-top: 1rem;"> <div class="action-card">
            <h3>Administrator Access</h3> <p>Secure login for administrators to efficiently manage member accounts, track contributions, generate reports, and configure system settings.</p>
            <a href="admin_login.php" class="btn">Admin Login</a>
        </div>
        <div class="action-card">
            <h3>Member Portal</h3> <p>Members can easily access their contribution history, make secure online payments, monitor their savings progress, and stay informed.</p>
            <a href="login.php" class="btn">Member Login</a>
        </div>
        <div class="action-card">
            <h3>Join Our Community</h3> <p>Become a part of our growing thrift community! Sign up today to start saving, contributing, and achieving your financial goals collectively.</p>
            <a href="register.php" class="btn">Sign Up Now</a>
        </div>
    </section>
</div>

<footer>
    <p>&copy; 2025 Thrift Management System. Building Financial Strength Together.</p> </footer>