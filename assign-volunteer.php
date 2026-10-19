<?php
include 'config.php';
include 'includes/header.php';

$donation_id = intval($_GET['donation_id'] ?? 0);

$don = $conn->query("SELECT * FROM donations WHERE id=$donation_id")->fetch_assoc();
if(!$don){
  echo "<div style='background:#fff; padding:30px; border-radius:16px; text-align:center;'>Donation not found! <a href='ngo-dashboard.php'>Go back</a></div>";
  include 'includes/footer.php';
  exit;
}

$donor = $don['donor_name'] ?? $don['name'] ?? 'Anonymous';
$item = $don['item_type'] ?? $don['donation_type'] ?? $don['category'] ?? 'Donation';

if(isset($_POST['assign'])){
  $vol_id = intval($_POST['volunteer_id']);
  
  // FIXED QUERY - matches your actual table
  $sql = "INSERT INTO volunteer_tasks (donation_id, volunteer_id, status) VALUES ($donation_id, $vol_id, 'assigned')";
  if($conn->query($sql)){
     // Also update donation status
     $conn->query("UPDATE donations SET status='assigned' WHERE id=$donation_id");
     echo "<script>alert('Volunteer Assigned Successfully!'); window.location='ngo-dashboard.php';</script>";
     exit;
  } else {
     // If volunteer_tasks fails, try alternate table
     $conn->query("INSERT INTO assignments (donation_id, volunteer_id, status) VALUES ($donation_id, $vol_id, 'assigned')");
     if(!$conn->error){
        $conn->query("UPDATE donations SET status='assigned' WHERE id=$donation_id");
        echo "<script>alert('Volunteer Assigned Successfully!'); window.location='ngo-dashboard.php';</script>";
        exit;
     } else {
        echo "<div style='background:#fee2e2; padding:15px; border-radius:10px; color:#b91c1c;'>Error: ".$conn->error."</div>";
     }
  }
}
?>

<div style="background:#fff; padding:28px; border-radius:16px; box-shadow:0 4px 12px rgba(0,0,0,0.06);">
<a href="ngo-dashboard.php" style="text-decoration:none; color:#666; font-size:13px;">← Back to Dashboard</a>
<h2 style="margin:10px 0 6px 0;">Assign Volunteer</h2>
<div style="background:#fff7ed; border:1px solid #ffedd5; padding:14px 18px; border-radius:10px; margin:14px 0;">
<strong>Donation #<?php echo $donation_id; ?></strong> — <?php echo htmlspecialchars($donor); ?> donated <span style="color:#FF6A2A; font-weight:700;"><?php echo htmlspecialchars($item); ?></span>
</div>

<h3>Available Volunteers</h3>
<form method="POST">
<div style="display:grid; gap:10px; margin-top:12px;">

<?php
$v = $conn->query("SELECT * FROM volunteers ORDER BY id DESC");
if($v && $v->num_rows > 0){
  while($vol = $v->fetch_assoc()){
    $vid = $vol['id'];
    $vname = $vol['full_name'] ?? $vol['name'] ?? 'Volunteer';
    $vphone = $vol['phone'] ?? '';
    $vskill = $vol['skills'] ?? 'General Help';
    echo "<label style='background:#f9fafb; padding:16px; border-radius:12px; border:2px solid #eee; display:flex; justify-content:space-between; align-items:center; cursor:pointer;'>
      <div style='display:flex; gap:12px; align-items:center;'>
        <input type='radio' name='volunteer_id' value='$vid' required>
        <div>
          <div style='font-weight:700;'>".htmlspecialchars($vname)."</div>
          <div style='font-size:12px; color:#666;'>$vskill • $vphone</div>
        </div>
      </div>
      <span style='font-size:12px; background:#dcfce7; padding:4px 10px; border-radius:20px;'>Available</span>
    </label>";
  }
} else {
  echo "<div style='padding:20px; text-align:center; color:#888;'>No volunteers yet.</div>";
}
?>

</div>
<?php if($v && $v->num_rows > 0){ ?>
<button name="assign" style="margin-top:20px; background:#000; color:#fff; border:0; padding:14px 28px; border-radius:10px; cursor:pointer; font-weight:700;">Confirm Assign</button>
<?php } ?>
</form>
</div>

<?php include 'includes/footer.php'; ?>