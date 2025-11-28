<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<script src="https://cdn.tailwindcss.com"></script>
<title>CEMS Registration</title>
</head>
<body class="bg-white min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md bg-white shadow-xl rounded-2xl p-8 border border-gray-100">

    <!-- Logo -->
    <div class="flex justify-center mb-6">
        <img src="./img/Community Logo.png" alt="CEMS Logo" class="w-32 h-32">
    </div>

    <!-- LOGIN FORM -->
    <div id="loginForm">
        <h2 class="text-2xl font-bold text-center text-green-700 mb-6">CEMS</h2>

        <?php if(isset($_SESSION['login_error'])): ?>
            <p class="text-red-500 text-center mb-3">
                <?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="login.php" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-green-400" 
                    placeholder="Enter your email" 
                    required
                    value="<?php echo isset($_SESSION['old_email']) ? $_SESSION['old_email'] : ''; ?>"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Password</label>
                <input type="password" name="password" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-green-400" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-xl font-semibold hover:bg-green-700 transition duration-200">Login</button>
            <a onclick="showRegister()" class="block w-full text-center bg-gray-100 text-green-700 py-3 rounded-xl font-semibold hover:bg-gray-200 transition duration-200 cursor-pointer">Sign Up</a>
            <p class="text-center text-sm text-gray-500 mt-3">
                Forgot your password? <a onclick="showRecover()" class="text-green-700 font-medium cursor-pointer">Recover</a>
            </p>
        </form>
    </div>

    <!-- REGISTRATION FORM -->
    <div id="registerForm" class="hidden">
        <h2 class="text-2xl font-bold text-center text-green-700 mb-6">Sign Up</h2>

        <?php if(isset($_SESSION['register_error'])): ?>
            <p class="text-red-500 text-center mb-3"><?php echo $_SESSION['register_error']; unset($_SESSION['register_error']); ?></p>
        <?php elseif(isset($_SESSION['register_success'])): ?>
            <p class="text-green-500 text-center mb-3"><?php echo $_SESSION['register_success']; unset($_SESSION['register_success']); ?></p>
        <?php endif; ?>

        <form method="POST" action="register.php" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Full Name</label>
                <input type="text" name="fullname" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-green-400" placeholder="Enter your full name" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                <input type="email" name="email" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-green-400" placeholder="Enter your email" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Password</label>
                <input type="password" name="password" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-green-400" placeholder="Create a password" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Confirm Password</label>
                <input type="password" name="confirm_password" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-green-400" placeholder="Confirm your password" required>
            </div>
            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-xl font-semibold hover:bg-green-700 transition duration-200">Create Account</button>
            <p class="text-center text-sm text-gray-500 mt-3">Already have an account? <a onclick="showLogin()" class="text-green-700 font-medium cursor-pointer">Login</a></p>
        </form>
    </div>

    <!-- RECOVER PASSWORD FORM -->
    <div id="recoverForm" class="hidden">
        <h2 class="text-2xl font-bold text-center text-green-700 mb-6">Recover Password</h2>
        <?php if(isset($_SESSION['recover_error'])): ?>
            <p class="text-red-500 text-center mb-3"><?php echo $_SESSION['recover_error']; unset($_SESSION['recover_error']); ?></p>
        <?php elseif(isset($_SESSION['recover_success'])): ?>
            <p class="text-green-500 text-center mb-3"><?php echo $_SESSION['recover_success']; unset($_SESSION['recover_success']); ?></p>
        <?php endif; ?>
        <form method="POST" action="recover.php" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                <input type="email" name="recover_email" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-green-400" placeholder="Enter your email" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">New Password</label>
                <input type="password" name="new_password" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-green-400" placeholder="Enter new password" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Confirm Password</label>
                <input type="password" name="confirm_new_password" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-green-400" placeholder="Confirm new password" required>
            </div>
            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-xl font-semibold hover:bg-green-700 transition duration-200">Next</button>
            <p class="text-center text-sm text-gray-500 mt-3">Back to <a onclick="showLogin()" class="text-green-700 font-medium cursor-pointer">Login</a></p>
        </form>
    </div>

</div>

<script>
// Toggle forms
function showRegister() {
    document.getElementById("loginForm").classList.add("hidden");
    document.getElementById("registerForm").classList.remove("hidden");
    document.getElementById("recoverForm").classList.add("hidden");
}
function showLogin() {
    document.getElementById("registerForm").classList.add("hidden");
    document.getElementById("loginForm").classList.remove("hidden");
    document.getElementById("recoverForm").classList.add("hidden");
}
function showRecover() {
    document.getElementById("loginForm").classList.add("hidden");
    document.getElementById("registerForm").classList.add("hidden");
    document.getElementById("recoverForm").classList.remove("hidden");
}
</script>

</body>
</html>
