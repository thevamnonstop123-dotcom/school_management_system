<?php 
session_start();
include '../partials/header.php';
?>

<div class="auth-wrapper">
    <div class="register-container admin-register-container">
        <div class="info-panel admin-info-panel">
            <div class="info-content">
                <div class="main-icon-box">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h1>Training School</h1>
                <p class="subtitle">Professional Training Management Platform</p>
                
                <div class="features-list">
                    <div class="feature-item">
                        <div class="feat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                        <div class="feat-text">
                            <strong>Train Management</strong>
                            <p>Manage trainers, courses, and schedules</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feat-icon"><i class="fas fa-chart-line"></i></div>
                        <div class="feat-text">
                            <strong>Analytics</strong>
                            <p>Track performance and enrollment trends</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feat-icon"><i class="fas fa-shield-alt"></i></div>
                        <div class="feat-text">
                            <strong>Security</strong>
                            <p>Secure access control and user management</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Registration Form (matches Figma) -->
        <div class="form-panel admin-form-panel">
            <div class="form-header">
                <h2>Create Admin Account</h2>
                <p>Set up your administrator credentials to access the training management system.</p>
            </div>

            <form action="process_admin_register.php" method="POST" class="admin-form">

                <?php if(isset($_SESSION['error'])): ?>
                    <div class="error-messages" style="color: #e74c3c; background: #fdeaea; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                        <?php foreach($_SESSION['error'] as $error) : ?>
                            <p style="margin: 0; font-size: 14px;"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                    <?php unset($_SESSION['error']); ?> 
                <?php endif; ?>

                <div class="form-row">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" placeholder="John" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" placeholder="Doe" required>
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label>Email Address</label>
                    <div class="input-icon-wrapper">
                        <i class="far fa-envelope"></i>
                        <input type="email" name="email" placeholder="admin@trainerhub.com" required>
                    </div>
                </div>

                <!-- Phone Number -->
                <div class="form-group">
                    <label>Phone Number</label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-phone-alt"></i>
                        <input type="text" name="phone_number" placeholder="+1 (555) 123-4567" required>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label>Password</label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" id="password" placeholder="Create strong password" required>
                        <i class="far fa-eye" id="togglePassword" onclick="togglePasswordVisibility()"></i>
                    </div>
                    <small class="hint">Minimum 8 characters with letters and numbers</small>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label>Confirm Password</label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your password" required>
                    </div>
                </div>

                <!-- Admin Role Dropdown (matches Figma) -->
                <div class="form-group">
                    <label>Admin Role</label>
                    <select name="admin_role" class="role-select" required>
                        <option value="" disabled selected>Select Admin Role</option>
                        <option value="Super Admin">Super Admin</option>
                        <option value="Administrator">Administrator</option>
                        <option value="Manager">Manager</option>
                        <option value="Staff">Staff</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <button type="submit" name="register_admin" class="btn-register">
                    <i class="fas fa-user-plus"></i> Create Admin Account
                </button>

                <p class="auth-footer">
                    Already have an account? 
                    <a href="login.php">Sign in here</a>
                </p>
            </form>
        </div>
    </div>
</div>
<?php include '../partials/footer.php'; ?>