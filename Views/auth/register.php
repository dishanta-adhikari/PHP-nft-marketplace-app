<?php
session_start();
require_once __DIR__ . "/../../App/App.php";
require_once __DIR__ . "/../../Config/Url.php";
include_once __DIR__ . "/../../Views/Components/header.php";

$app = new App();
$conn = $app->connect();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] === 'admin') {
        header("Location: " . VIEW_URL . "/admin/dashboard");
    } else {
        header("Location: " . VIEW_URL . "/user/dashboard");
    }
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $dob = $_POST['dob'] ?? null;
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Basic validations
    if (!$name) $errors[] = "Name is required.";
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (!$dob) $errors[] = "Date of birth is required.";
    if (!$password) $errors[] = "Password is required.";
    if ($password !== $confirm_password) $errors[] = "Passwords do not match.";

    // Check if email exists
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT User_ID FROM user WHERE Email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "Email is already registered.";
        }
    }

    if (empty($errors)) {
        // Hash password securely
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insert user
        $stmt = $conn->prepare("INSERT INTO user (Name, Email, DOB, Role, Password) VALUES (?, ?, ?, 'user', ?)");
        $stmt->execute([$name, $email, $dob, $passwordHash]);

        $_SESSION['success'] = "Registration successful! Please login.";
        header("Location: login");
        exit;
    }
}
?>


<link rel="stylesheet" href="<?php echo BASE_URL; ?>/Assets/css/register.css">

<div id="register-page" class="container tubelight-box side-light mt-5" style="max-width: 480px;">
    <h2 class="mb-4 text-center">Create Account</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="register" novalidate>
        <div class="mb-3">
            <label for="register-name" class="form-label">Name *</label>
            <input type="text" id="register-name" name="name" class="form-control" required
                value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" autocomplete="name">
        </div>
        <div class="mb-3">
            <label for="register-email" class="form-label">Email *</label>
            <input type="email" id="register-email" name="email" class="form-control" required
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" autocomplete="email">
        </div>
        <div class="mb-3">
            <label for="register-dob" class="form-label">Date of Birth *</label>
            <input type="date" id="register-dob" name="dob" class="form-control" required
                value="<?= htmlspecialchars($_POST['dob'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="register-password" class="form-label">Password *</label>
            <input type="password" id="register-password" name="password" class="form-control" required autocomplete="new-password">
        </div>
        <div class="mb-3">
            <label for="register-confirm_password" class="form-label">Confirm Password *</label>
            <input type="password" id="register-confirm_password" name="confirm_password" class="form-control" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn btn-primary w-100">Register</button>

        <div class="mt-3 text-center">
            <a href="login" class="btn btn-link p-0">Already have an account? Login</a>
        </div>
    </form>
</div>


<?php include __DIR__ . "/../../Views/Components/footer.php"; ?>