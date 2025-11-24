<?php
session_start();
require_once "config.php";

$isLoggedIn = isset($_SESSION['username']);
if ($isLoggedIn) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
    $stmt->execute(['username' => $_SESSION['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ & Contact | Community E-Reporting</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../css/faq_contact.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <header id="header">
        <nav>
            <div class="logo">
                <img src="../images/logo.png" alt="Logo">
                <h1>Community E-Reporting</h1>
            </div>
            <ul>
                <?php if ($isLoggedIn): ?>
                    <li><a href="user_homepage.php">Home</a></li>
                <?php else: ?>
                    <li><a href="../html/index.html">Home</a></li>
                <?php endif; ?>
                <li><a href="reports.php">Reports</a></li>
                <li><a href="officials.php">Officials</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php" class="active">FAQ</a></li>

                <?php if ($isLoggedIn): ?>
                    <li class="profile-menu">
                        <i class="fas fa-user-circle profile-icon"></i>
                        <ul class="dropdown">
                            <li><a href="#" id="openProfileModal">Profile</a></li>
                            <li><a href="../php/logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li><a href="../php/login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main class="faq-contact-container">
        <!-- FAQ Section -->
        <section class="faq-section">
            <h2>Frequently Asked Questions</h2>
            <div class="faq-item">
                <button class="faq-question">How do I submit a report?</button>
                <div class="faq-answer">
                    <p>Go to the Reports section, fill out the form, and submit your report. You can attach images as well.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">Can I track my report?</button>
                <div class="faq-answer">
                    <p>Yes! You can see all your submitted reports in the Reports page once logged in.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">Who can access the reports?</button>
                <div class="faq-answer">
                    <p>Only the user who submitted the report and barangay officials can access report details.</p>
                </div>
            </div>
        </section>

        <!-- Contact Form Section -->
        <section class="contact-section">
            <h2>Contact Us</h2>
            <form action="../php/send_contact.php" method="POST">
                <label>Name</label>
                <input type="text" name="name" placeholder="Your full name" required value="<?= $isLoggedIn ? htmlspecialchars($user['fullname']) : '' ?>">

                <label>Email</label>
                <input type="email" name="email" placeholder="Your email" required value="<?= $isLoggedIn ? htmlspecialchars($user['email']) : '' ?>">

                <label>Message</label>
                <textarea name="message" rows="5" placeholder="Type your message here..." required></textarea>

                <button type="submit" class="btn">Send Message</button>
            </form>
        </section>
    </main>

    <footer>
        © 2025 Community E-Reporting System | Barangay West Rembo, Taguig City
    </footer>

    <?php if ($isLoggedIn): ?>
        <!-- Profile Modal -->
        <div id="profileModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Edit Profile</h2>
                <form method="POST" action="update_profile.php">
                    <label>Full Name</label>
                    <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']); ?>" required>
                    <label>Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
                    <label>New Password</label>
                    <input type="password" name="password" placeholder="Leave blank">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" placeholder="Leave blank">
                    <button type="submit" class="btn">Save Changes</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <script src="../js/modal.js"></script>
   <script>
document.addEventListener("DOMContentLoaded", () => {
    // ===== FAQ TOGGLE =====
    const faqButtons = document.querySelectorAll('.faq-question');
    faqButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            btn.classList.toggle('active');
            const answer = btn.nextElementSibling;
            if (answer.style.maxHeight) {
                answer.style.maxHeight = null;
            } else {
                answer.style.maxHeight = answer.scrollHeight + "px";
            }
        });
    });

    // ===== PROFILE DROPDOWN =====
    const profileIcon = document.querySelector(".profile-icon");
    const dropdown = document.querySelector(".profile-menu .dropdown");
    const profileModal = document.getElementById("profileModal");

    if (profileIcon && dropdown) {
        profileIcon.addEventListener("click", (e) => {
            e.stopPropagation();
            dropdown.classList.toggle("show");
        });

        // Close dropdown if click outside
        document.addEventListener("click", (e) => {
            if (!dropdown.contains(e.target) && !profileIcon.contains(e.target)) {
                dropdown.classList.remove("show");
            }
        });
    }

    // ===== PROFILE MODAL =====
    if (profileModal) {
        const openProfile = document.getElementById("openProfileModal");
        const closeBtn = profileModal.querySelector(".close");

        openProfile.addEventListener("click", (e) => {
            e.preventDefault();
            profileModal.style.display = "block";
            dropdown.classList.remove("show");
        });

        closeBtn.addEventListener("click", () => {
            profileModal.style.display = "none";
        });

        window.addEventListener("click", (e) => {
            if (e.target === profileModal) {
                profileModal.style.display = "none";
            }
        });
    }
});
</script>

</body>

</html>