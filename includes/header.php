<?php if(session_status()==PHP_SESSION_NONE) session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$role = $_SESSION['role'] ?? 'guest';
$dashboardLink = "index.php";
if($role=='donor') $dashboardLink = "donor-dashboard.php";
if($role=='volunteer') $dashboardLink = "volunteer-dashboard.php";
if($role=='ngo') $dashboardLink = "ngo-dashboard.php";
if($role=='admin') $dashboardLink = "admin-dashboard.php";
?>
<!DOCTYPE html>
<html>
<head>
<title>ImpactConnect</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
html,body { width:100%; overflow-x:hidden; }
body { font-family:'Segoe UI', Arial, sans-serif; background:#f3f4f6; display:flex; flex-direction:column; min-height:100vh; }
.main-content { flex:1; width:100%; max-width:1100px; margin:0 auto; padding:24px 16px; }

nav{
  background:#000;
  color:#fff;
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:14px 20px;
  position:sticky;
  top:0;
  z-index:999;
}
.logo{ color:#fff; text-decoration:none; font-weight:800; font-size:19px; letter-spacing:0.2px; }
.links{ display:flex; align-items:center; gap:16px; }
.links a{ color:#aaa; text-decoration:none; font-size:13px; white-space:nowrap; }
.links a:hover{ color:#fff; }
.login-btn{ background:#fff; color:#000 !important; padding:6px 14px; border-radius:18px; font-weight:700; }
.dash-btn{ border:1px solid #333; background:#1a1a1a; color:#fff !important; padding:5px 12px; border-radius:18px; font-size:12px; display:flex; gap:5px; align-items:center; }
.dot{ width:6px; height:6px; background:#FF6A2A; border-radius:50%; }
</style>
</head>
<body>
<nav>
  <a href="<?= $isLoggedIn ? $dashboardLink : 'index.php' ?>" class="logo">ImpactConnect</a>
  <div class="links">
    <a href="index.php">Home</a>
    <a href="donation.php">Donate</a>
    <a href="volunteer.php">Volunteer</a>
    <a href="connect.php">Connect</a>
    <a href="about.php">About Us</a>
    <?php if($isLoggedIn): ?>
      <a href="<?= $dashboardLink ?>" class="dash-btn"><span class="dot"></span>Dashboard</a>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php" class="login-btn">Login</a>
    <?php endif; ?>
  </div>
</nav>
<div class="main-content">