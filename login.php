<?php
ob_start();
include 'config.php';
if (session_status() == PHP_SESSION_NONE) { session_start(); }

// If already logged in
if(isset($_SESSION['role'])){
  $r = strtolower($_SESSION['role']);
  if($r == 'admin') { header("Location: admin-dashboard.php"); exit; }
  elseif($r == 'ngo') { header("Location: ngo-dashboard.php"); exit; }
  elseif($r == 'donor') { header("Location: donor-dashboard.php"); exit; }
  else { header("Location: volunteer-dashboard.php"); exit; }
}

$error = "";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $email = $conn->real_escape_string($_POST['email']);
  $pass = $_POST['password'];
  $selected_role = strtolower(trim($_POST['role'] ?? 'donor')); // donor, volunteer, ngo, admin

  $res = $conn->query("SELECT * FROM users WHERE email='$email' LIMIT 1");
  
  if($res && $res->num_rows == 1){
    $user = $res->fetch_assoc();
    
    $valid = false;
    if(password_verify($pass, $user['password'])) $valid = true;
    if($pass == $user['password']) $valid = true; // old plain passwords

    if($valid){
      $db_role = strtolower(trim($user['role'] ?? $user['user_type'] ?? 'volunteer'));

      // CHECK IF SELECTED ROLE MATCHES DB ROLE (Admin can login even if selected other role)
      if($db_role != $selected_role && $db_role != 'admin' && $selected_role != $db_role){
         // Optional: strict check. If you want to allow login regardless of selected role, comment this
         if($selected_role != $db_role){
            $error = "This account is registered as '".ucfirst($db_role)."'. Please select ".ucfirst($db_role)." to login.";
         }
      }

      if(!$error){
          $_SESSION['user_id'] = $user['id'];
          $_SESSION['role'] = $db_role;
          $_SESSION['email'] = $user['email'];
          $_SESSION['name'] = $user['full_name'] ?? $user['name'] ?? 'User';

          // FORCE REDIRECT - no header.php yet, so it WILL work
          if($db_role == 'admin'){
            header("Location: admin-dashboard.php");
          } elseif($db_role == 'ngo'){
            header("Location: ngo-dashboard.php");
          } elseif($db_role == 'donor'){
            header("Location: donor-dashboard.php");
          } else {
            header("Location: volunteer-dashboard.php");
          }
          exit;
      }
    } else {
      $error = "Wrong password!";
    }
  } else {
    $error = "No account found with $email";
  }
}
include 'includes/header.php';
?>
<style>
.login-wrapper { display:flex; justify-content:center; align-items:center; min-height:80vh; background:#f5f5f7; padding:40px 15px; }
.login-card { background:#fff; padding:36px; border-radius:20px; box-shadow:0 10px 30px rgba(0,0,0,0.08); width:100%; max-width:480px; }
.input-group { margin-bottom:16px; }
.input-group label { font-size:13px; font-weight:600; display:block; margin-bottom:6px; }
.input-group input { width:100%; padding:12px 14px; border:2px solid #eee; border-radius:10px; outline:none; background:#f8f9ff; }
.input-group input:focus { border-color:#111; background:#fff; }
.btn-login { width:100%; background:#000; color:#fff; border:0; padding:14px; border-radius:12px; font-weight:700; cursor:pointer; margin-top:10px; font-size:15px;}
.btn-login:hover { background:#222; }
.error-box { background:#fee2e2; color:#b91c1c; padding:10px; border-radius:8px; font-size:13px; margin-bottom:14px; }

/* NEW ROLE SELECTOR - NO LOGO */
.role-selector { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:22px; }
.role-btn { padding:14px 8px; border:1.5px solid #ddd; border-radius:12px; background:#f6f6f6; cursor:pointer; font-weight:700; font-size:14px; transition:0.2s; }
.role-btn.active { background:#000; color:#fff; border-color:#000; }
.role-btn:hover { border-color:#000; }
</style>

<div class="login-wrapper">
  <div class="login-card">
    <h2 style="margin:0 0 6px 0;">Welcome Back</h2>
    <p style="color:#666; font-size:13px; margin:0 0 20px 0;">Sign in as your role to continue</p>

    <?php if($error): ?>
      <div class="error-box"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <!-- 4 ROLE BUTTONS -->
      <div class="role-selector">
        <button type="button" class="role-btn active" onclick="setRole('donor', this)">❤ Donor</button>
        <button type="button" class="role-btn" onclick="setRole('volunteer', this)">🤝 Volunteer</button>
        <button type="button" class="role-btn" onclick="setRole('ngo', this)">🏢 NGO</button>
        <button type="button" class="role-btn" onclick="setRole('admin', this)">🛡 Admin</button>
      </div>
      <input type="hidden" name="role" id="roleInput" value="donor">

      <div class="input-group">
        <label>Email</label>
        <input type="email" name="email" placeholder="diapatel1510@gmail.com" required>
      </div>
      <div class="input-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn-login">Login</button>
      
      <p style="text-align:center; font-size:13px; margin-top:16px;">Don't have an account? <a href="signup.php" style="font-weight:700; color:#000;">Sign up now</a></p>
    </form>
  </div>
</div>

<script>
function setRole(role, btn){
  document.getElementById('roleInput').value = role;
  document.querySelectorAll('.role-btn').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');
}
</script>

<?php include 'includes/footer.php'; ob_end_flush(); ?>