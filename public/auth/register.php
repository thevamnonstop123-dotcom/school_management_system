<?php include '../partials/header.php'; ?>

<div class="auth-wrapper">
    <div class="login-container">
        <div class="login-sidebar">
            <h1>Join Us</h1>
            <div class="welcome-image">
                <img src="../../assets/images/mylogogold.png" alt="Welcome Icon">
            </div>
        </div>

        <div class="login-form-section">
            <h2>Create Account</h2>
            <p>Fill in your details to start managing your school</p>
            
            <form action="process_register.php" method="POST">
                
                <div class="form-group">
                    <label>Full Name</label>
                    <div class="input-icon-wrapper">
                        <i class="far fa-user"></i>
                        <input type="text" name="full_name" placeholder="John Doe" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Email address</label>
                    <div class="input-icon-wrapper">
                        <i class="far fa-envelope"></i>
                        <input type="email" name="email" placeholder="you@example.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" id="password" placeholder="••••••••" required>
                        <i class="far fa-eye" id="togglePassword"></i> 
                    </div>
                </div>

                <button type="submit" class="btn-login">Create your account</button>
            </form>
            
            <p class="auth-footer">Already have an account?</p>
            <a href="login.php" class="auth-footer2" style="text-align: center; display: block; color: #6366f1; text-decoration: none; font-weight: 600;">Sign in here</a>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>