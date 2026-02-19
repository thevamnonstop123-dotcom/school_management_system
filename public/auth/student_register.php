<?php
require_once "../../classes/Student.php";

$studentObj = new Student();

$student_id = $studentObj->generateStudentID();
include '../partials/header.php';
?>

    <header class="figma-header">
        <div class="header-container">
            <div class="logo-section">
                <div class="logo-icon"><i class="fas fa-graduation-cap"></i></div>
                <div class="logo-text">
                    <strong>EduManage</strong>
                    <span>School Management System</span>
                </div>
            </div>
            <div class="header-links">
                <a href="#" class="help-link"><i class="fas fa-question-circle"></i> Help</a>
                <a href="login.php" class="login-nav-btn">Login</a>
            </div>
        </div>
    </header>

    <main class="main-wrapper">
        <div class="register-container">
            <div class="info-panel">
                <div class="info-content">
                    <div class="main-icon-box">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h1>Join Our Academic Community</h1>
                    <p class="subtitle">Create your student account and get access to our comprehensive school management platform.</p>
                    
                    <ul class="features-list">
                        <li>
                            <div class="feat-icon"><i class="fas fa-book-open"></i></div> 
                            <div class="feat-text">
                                <strong>Access Course Materials</strong>
                                <p>View assignments, grades, and course content</p>
                            </div>
                        </li>
                        <li>
                            <div class="feat-icon"><i class="fas fa-calendar-alt"></i></div> 
                            <div class="feat-text">
                                <strong>Track Your Schedule</strong>
                                <p>Manage classes, events, and important dates</p>
                            </div>
                        </li>
                        <li>
                            <div class="feat-icon"><i class="fas fa-users"></i></div> 
                            <div class="feat-text">
                                <strong>Connect with Peers</strong>
                                <p>Collaborate and communicate with classmates</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="form-panel">
                <form action="process_student_register.php" method="POST" enctype="multipart/form-data">
                    <div class="form-header">
                        <h2>Student Registration</h2>
                        <p>Fill in your information to create your student account</p>
                    </div>

                    <div class="profile-upload">
                        <div class="avatar-preview">
                            <img src="../../assets/images/default-avatar.png" id="img-preview" alt="Preview">
                        </div>
                        <label for="profile_image" class="upload-btn">UPLOAD</label>
                        <input type="file" id="profile_image" name="profile_image" hidden onchange="previewImage(event)">
                    </div>

                    <div class="form-grid">
                        <div class="input-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" placeholder="John" required>
                        </div>
                        <div class="input-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" placeholder="Doe" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <label>Email Address</label>
                        <input type="email" name="email" placeholder="john.doe@email.com" required>
                    </div>

                    <div class="form-grid">
                        <div class="input-group">
                            <label>Student ID</label>
                            <input type="text" name="sid_code" value="<?= $student_id; ?>" readonly class="readonly-input">
                        </div>
                        <div class="input-group">
                            <label>Date of Birth</label>
                            <input type="date" name="dob" required>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="input-group">
                            <label>Address</label>
                            <input type="text" name="address" placeholder="Your Address">
                        </div>
                        <div class="input-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone" placeholder="+1 (555) 123-4567">
                        </div>
                    </div>

                    <div class="input-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Create a strong password" required>
                    </div>

                    <div class="input-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" placeholder="Confirm your password" required>
                    </div>

                    <button type="submit" class="submit-btn">Create Student Account</button>
                    
                    <div class="login-footer">
                        <span>Already have an account?</span>
                        <a href="login.php">Sign in here</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const output = document.getElementById('img-preview');
        output.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>