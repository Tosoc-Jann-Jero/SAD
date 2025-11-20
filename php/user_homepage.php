<?php
session_start();
require 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $_SESSION['username']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Homepage</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../css/user_homepage_style.css">
</head>

<body>
    <header id="header">
        <nav>
            <div class="logo">
                <img src="../images/logo.png" alt="Logo">
                <h1>Community E-Reporting</h1>
            </div>
            <ul>
                <li><a href="user_homepage.php" class="active">Home</a></li>
                <li><a href="../html/reports.html">Reports</a></li>
                <li><a href="../html/submit.html">Submit</a></li>
                <li><a href="../html/officials.html">Officials</a></li>
                <li><a href="../html/about.html">About</a></li>
                <li><a href="../html/contact.html">Contact</a></li>

                <!-- Profile Dropdown -->
                <li class="profile-menu">
                    <i class="fas fa-user-circle profile-icon"></i>
                    <ul class="dropdown">
                        <li><a href="#" id="openProfileModal">Profile</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Profile Modal -->
    <div id="profileModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Profile</h2>
            <form method="POST" action="update_profile.php">
                <label>Full Name</label>
                <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>

                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                <label>New Password</label>
                <input type="password" name="password" placeholder="Leave blank to keep current">

                <label>Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Leave blank to keep current">

                <button type="submit" class="btn">Save Changes</button>
            </form>
        </div>
    </div>

    <main>
        <section class="hero">
            <div class="hero-text">
                <h2>Empowering Communities through Digital Reporting</h2>
                <p>Report incidents, monitor peace and order, and help make Barangay West Rembo a safer place for everyone.</p>
                <a href="submit.html" class="btn">Submit a Report</a>
            </div>
            <div class="hero-img">
                <img src="../images/community.jpg" alt="Community">
            </div>
        </section>

        <section class="cards">
            <div class="card">
                <h3>Fast Response</h3>
                <p>Reports reach the barangay instantly for quicker response and better transparency.</p>
            </div>
            <div class="card">
                <h3>Track Progress</h3>
                <p>Residents can monitor the status of submitted reports and see when they are resolved.</p>
            </div>
            <div class="card">
                <h3>Community Impact</h3>
                <p>Data helps officials identify recurring issues and improve peacekeeping efforts.</p>
            </div>
        </section>
    </main>

    <footer>
        © 2025 Community E-Reporting System | Barangay West Rembo, Taguig City
    </footer>

    <script>
        // Sticky header hide/show
        let lastScroll = 0;
        const header = document.getElementById('header');
        window.addEventListener('scroll', () => {
            const current = window.pageYOffset;
            if (current > lastScroll) {
                header.classList.add('hide');
            } else {
                header.classList.remove('hide');
            }
            lastScroll = current;
        });

        // Modal functionality
        const modal = document.getElementById("profileModal");
        const btn = document.getElementById("openProfileModal");
        const span = document.querySelector(".modal .close");

        btn.onclick = function(e) {
            e.preventDefault();
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(e) {
            if (e.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>

</html>