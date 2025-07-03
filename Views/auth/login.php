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
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $isAdmin = isset($_POST['is_admin']);

    if (!$email || !$password) {
        $errors[] = "Email and password are required.";
    } else {
        $stmt = $conn->prepare("SELECT User_ID, Name, Password, Role FROM user WHERE Email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['Password'])) {
            if ($isAdmin && $user['Role'] !== 'admin') {
                $errors[] = "You are not authorized as an admin.";
            } else {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['User_ID'];
                $_SESSION['user_role'] = $user['Role'];
                $_SESSION['user_name'] = $user['Name'];

                if ($user['Role'] === 'admin') {
                    header("Location: " . VIEW_URL . "/admin/dashboard");
                } else {
                    header("Location: " . BASE_URL . "/");
                }
                exit;
            }
        } else {
            $errors[] = "Invalid email or password.";
        }
    }
}
?>


<link rel="stylesheet" href="<?php echo BASE_URL; ?>/Assets/css/login.css">

<div id="login-page" class="container tubelight-box side-light mt-5" style="max-width: 480px;">
    <h2 class="mb-4 text-center">Log In</h2>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success" role="alert">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="login" novalidate>
        <div class="mb-3">
            <label for="login-email" class="form-label">Email *</label>
            <input type="email" id="login-email" name="email" class="form-control" required
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" autocomplete="username">
        </div>

        <div class="mb-3">
            <label for="login-password" class="form-label">Password *</label>
            <input type="password" id="login-password" name="password" class="form-control" required autocomplete="current-password">
        </div>

        <div class="form-check mb-4">
            <input type="checkbox" class="form-check-input" id="login-is_admin" name="is_admin" <?= isset($_POST['is_admin']) ? 'checked' : '' ?>>
            <label class="form-check-label" for="login-is_admin">Login as Admin</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>

        <div class="mt-3 text-center">
            <a href="<?php echo VIEW_URL; ?>/auth/register" class="btn btn-link p-0">Don't have an account? Register</a>
        </div>
    </form>
</div>

<?php include_once __DIR__ . "/../../Views/Components/footer.php"; ?>