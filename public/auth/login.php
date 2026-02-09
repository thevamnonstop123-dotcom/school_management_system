<?php include '../partials/header.php'; ?>

<div class="auth-wrapper">
    <div class="login-container">
        <div class="login-sidebar">
            <h1>Welcome</h1>
            <div class="welcome-image">
                <img src="../../assets/images/mylogo.png" alt="Welcome Icon">
            </div>
        </div>

        <div class="login-form-section">
            <h2>Sign in</h2>
            <p>Enter your credentials to access your account</p>
            
            <form action="process_login.php" method="POST">
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
                        <i class="fas fa-lock"></i> <input type="password" name="password" id="password" placeholder="••••••••" required>
                        <i class="far fa-eye" id="togglePassword"></i> 
                    </div>
                </div>

                <div class="form-options">
                    <label><input type="checkbox"> Remember me</label>
                    <a href="#">Forgot password?</a>
                </div>

                <button type="submit" class="btn-login">Sign in to your account</button>
            </form>
            
            <p class="auth-footer">Don't have an account?</p>
            <a href="register.php" class="auth-footer2">Sign up for free</a>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>