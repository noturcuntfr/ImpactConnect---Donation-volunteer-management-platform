<?php
ob_start();
include 'config.php';
if (session_status() == PHP_SESSION_NONE) { session_start(); }

$error = "";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $name = $conn->real_escape_string(trim($_POST['full_name']));
  $email = $conn->real_escape_string(trim($_POST['email']));
  $role = $conn->real_escape_string(strtolower(trim($_POST['role'])));
  $password = $_POST['password'];
  $confirm = $_POST['confirm_password'] ?? $_POST['password'];

  if($password !== $confirm){
    $error = "Passwords do not match!";
  } else {
    $check = $conn->query("SELECT id FROM users WHERE email='$email' LIMIT 1");
    if($check && $check->num_rows > 0){
      $error = "Email already exists! Please login.";
    } else {
      $hashed = password_hash($password, PASSWORD_DEFAULT);
      
      // Your table has: name, email, password, role
      $sql = "INSERT INTO users (name, email, password, role) 
              VALUES ('$name', '$email', '$hashed', '$role')";
              
      if($conn->query($sql)){
        header("Location: login.php?success=1");
        exit;
      } else {
        $error = "DB Error: " . $conn->error;
      }
    }
  }
}
include 'includes/header.php';
?>
<style>
.register-wrapper{display:flex;justify-content:center;align-items:center;min-height:85vh;background:#f5f5f7;padding:40px 15px}
.register-card{background:#fff;padding:36px;border-radius:20px;box-shadow:0 10px 30px rgba(0,0,0,0.08);width:100%;max-width:500px}
.input-group{margin-bottom:14px}.input-group label{font-size:13px;font-weight:600;display:block;margin-bottom:6px}
.input-group input{width:100%;padding:12px 14px;border:2px solid #eee;border-radius:10px;background:#f8f9ff;outline:none}
.input-group input:focus{border-color:#111;background:#fff}
.role-selector{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:20px}
.role-btn{padding:14px;border:1.5px solid #ddd;border-radius:12px;background:#f6f6f6;font-weight:700;cursor:pointer;transition:0.2s}
.role-btn.active{background:#000;color:#fff;border-color:#000}
.btn-register{width:100%;background:#000;color:#fff;border:0;padding:14px;border-radius:12px;font-weight:700;cursor:pointer;margin-top:10px}
.error-box{background:#fee2e2;color:#b91c1c;padding:10px;border-radius:8px;margin-bottom:14px;font-size:13px}
</style>

<div class="register-wrapper">
  <div class="register-card">
    <h2 style="margin:0 0 6px 0;">Create Account</h2>
    <p style="color:#666;font-size:13px;margin:0 0 20px 0;">Join ImpactConnect as</p>
    <?php if($error): ?><div class="error-box"><?= $error ?></div><?php endif; ?>

    <form method="POST" action="signup.php">
      <div class="role-selector">
        <button type="button" class="role-btn active" onclick="setRole('donor',this)">❤ Donor</button>
        <button type="button" class="role-btn" onclick="setRole('volunteer',this)">🤝 Volunteer</button>
        <button type="button" class="role-btn" onclick="setRole('ngo',this)">🏢 NGO</button>
        <button type="button" class="role-btn" onclick="setRole('admin',this)">🛡 Admin</button>
      </div>
      <input type="hidden" name="role" id="roleInput" value="donor">

      <div class="input-group"><label>Full Name</label><input type="text" name="full_name" placeholder="Diya Patel" required></div>
      <div class="input-group"><label>Email</label><input type="email" name="email" placeholder="you@example.com" required></div>
      <div class="input-group"><label>Password</label><input type="password" name="password" placeholder="Min 6 characters" required></div>
      <div class="input-group"><label>Confirm Password</label><input type="password" name="confirm_password" placeholder="••••••••" required></div>

      <button type="submit" class="btn-register">Create Account</button>
      <p style="text-align:center;font-size:13px;margin-top:14px">Already have account? <a href="login.php" style="color:#000;font-weight:700">Login now</a></p>
    </form>
  </div>
</div>

<script>
function setRole(role,btn){
  document.getElementById('roleInput').value=role;
  document.querySelectorAll('.role-btn').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');
}
</script>
<?php include 'includes/footer.php'; ob_end_flush(); ?>