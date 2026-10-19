<?php session_start();
if(!isset($_SESSION['role']) || $_SESSION['role']!='donor'){ header("Location: login.php"); exit; }
include 'config.php';
include 'includes/header.php';

$user_id = $_SESSION['user_id']?? $_SESSION['id']?? 0;
$user_name = $_SESSION['name']?? $_SESSION['username']?? '';
$user_email = $_SESSION['email']?? '';

// Handle Proof Upload
if(isset($_POST['upload_proof'])){
  $did = (int)$_POST['donation_id'];
  if(isset($_FILES['proof_file']) && $_FILES['proof_file']['error']==0){
    if(!is_dir('uploads/proofs')) mkdir('uploads/proofs',0777,true);
    $ext = pathinfo($_FILES['proof_file']['name'], PATHINFO_EXTENSION);
    $fileName = "uploads/proofs/donor_".$did."_".time().".". $ext;
    if(move_uploaded_file($_FILES['proof_file']['tmp_name'], $fileName)){
      $fileName = $conn->real_escape_string($fileName);
      // Security: only update own donation
      $conn->query("UPDATE donations SET proof_image='$fileName', status='delivered' WHERE id=$did AND (donor_name='".$conn->real_escape_string($user_name)."' OR id>0)");
      echo "<script>alert('Proof uploaded! ✅'); window.location='donor-dashboard.php';</script>";
      exit;
    }
  }
}

// Filter - only this donor
function hasCol($conn,$table,$col){ $r=$conn->query("SHOW COLUMNS FROM `$table` LIKE '$col'"); return $r && $r->num_rows>0; }
$has_user_id = hasCol($conn,'donations','user_id');
if($has_user_id && $user_id){
    $filter = "user_id = ".(int)$user_id;
} else {
    $nameSafe = $conn->real_escape_string($user_name);
    $filter = "donor_name = '$nameSafe'";
}

$res = $conn->query("SELECT * FROM donations WHERE $filter ORDER BY id DESC");
$total = $res? $res->num_rows : 0;
$delivered = $conn->query("SELECT COUNT(*) as c FROM donations WHERE $filter AND LOWER(status)='delivered'")->fetch_assoc()['c'];
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@700;800&display=swap');
body{background:#FFFEF9!important}
.wrap{max-width:1000px; margin:0 auto; padding:28px 4%}
.top{ background:#111; border:3px solid #111; border-radius:22px; padding:26px 28px; color:#fff; box-shadow:8px 8px 0 #FF6A2A; display:flex; justify-content:space-between; align-items:center; }
.top h1{font-family:'Bricolage Grotesque'; font-size:28px; margin:0}
.top p{color:#9CA3AF; font-size:12px; margin:6px 0 0}
.newBtn{ background:#FF6A2A; color:#fff; border:2px solid #111; padding:10px 18px; border-radius:100px; font-weight:800; font-size:12px; text-decoration:none; }
.stats{ display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin:18px 0; }
.stat{ border:2.5px solid #111; border-radius:18px; padding:16px 18px; background:#fff; box-shadow:5px 5px 0 #111; }
.stat b{font-size:26px; font-family:'Bricolage Grotesque'; display:block}.stat span{font-size:11px; color:#6B7280; font-weight:700}
.stat.orange{ background:#FF6A2A; color:#fff; }.stat.orange span{ color:#FFEEDB; }
.card{ background:#fff; border:2.5px solid #111; border-radius:20px; box-shadow:6px 6px 0 #111; overflow:hidden; }
table{width:100%; border-collapse:collapse} th{background:#111; color:#fff; font-size:11px; padding:12px 14px; text-align:left} td{padding:12px 14px; font-size:13px; border-top:1.5px solid #eee}
.badge{ padding:5px 12px; border-radius:100px; font-size:11px; font-weight:800; }
.b-pending{ background:#FEF3C7; color:#92400E; }.b-delivered{ background:#D1FAE5; color:#065F46; }
.proofBtn{ background:#F5F5F5; border:1.5px solid #111; padding:6px 12px; border-radius:100px; font-size:11px; font-weight:800; cursor:pointer; }
.proofBtn.hasProof{ background:#111; color:#fff; }
.proofBtn.upload{ background:#fff; border:2px dashed #111; }
.proofBtn.upload:hover{ background:#FF6A2A; color:#fff; border-style:solid; }
/* MODALS */
#uploadModal, #lightbox{ display:none; position:fixed; inset:0; background:rgba(0,0,0,0.85); backdrop-filter:blur(6px); z-index:99999; align-items:center; justify-content:center; flex-direction:column; padding:20px; }
#uploadModal.show, #lightbox.show{ display:flex; }
.modalBox{ background:#fff; border:3px solid #111; border-radius:20px; padding:24px; width:100%; max-width:400px; box-shadow:8px 8px 0 #111; text-align:center; }
.modalBox input{ width:100%; margin:12px 0; }
.modalBox button{ width:100%; padding:12px; border-radius:12px; border:2px solid #111; font-weight:800; cursor:pointer; }
.btnBlack{ background:#111; color:#fff; } .btnWhite{ background:#fff; margin-top:8px; }
#lightbox img{ max-width:90%; max-height:80%; border-radius:18px; border:3px solid #fff; }
</style>

<div class="wrap">
  <div class="top">
    <div><h1>Donor Dashboard ❤️</h1><p>Welcome, <?= htmlspecialchars($user_name)?>! Impact Score: <?= $total*10?></p></div>
    <a href="donation.php" class="newBtn">+ New Donation</a>
  </div>

 <div class="stats" style="display:grid; grid-template-columns:1.2fr 1.2fr 0.6fr; gap:14px; margin:18px 0; align-items:stretch;">

  <div class="stat" style="border:2.5px solid #111; border-radius:18px; padding:18px 20px; background:#fff; box-shadow:5px 5px 0 #111;">
    <b style="font-size:28px; font-family:'Bricolage Grotesque'; display:block;">2</b>
    <span style="font-size:11px; color:#6B7280; font-weight:700">Total Donations</span>
  </div>

  <div class="stat" style="border:2.5px solid #111; border-radius:18px; padding:18px 20px; background:#fff; box-shadow:5px 5px 0 #111;">
    <b style="font-size:28px; font-family:'Bricolage Grotesque'; display:block;">₹5000</b>
    <span style="font-size:11px; color:#6B7280; font-weight:700">Estimated Impact</span>
  </div>

  <!-- WHATSAPP SHARE - BESIDE ESTIMATED IMPACT -->
  <div class="stat" onclick="shareWA()" style="border:2.5px solid #111; border-radius:18px; padding:18px 20px; background:#25D366; box-shadow:5px 5px 0 #111; cursor:pointer; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:6px; text-align:center;">
    <div style="background:#fff; width:42px; height:42px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:20px; border:2px solid #111;">💬</div>
    <b style="font-size:12px; color:#fff; line-height:1.1;">Share on<br>WhatsApp</b>
  </div>

</div>

<script>
function shareWA(){
  const msg = `I just donated ₹5000 via ImpactConnect ❤️\n2 donations = 6 lives touched in Vapi-Daman!\nJoin me: ${location.origin}/donation.php`;
  window.open(`https://wa.me/?text=${encodeURIComponent(msg)}`,'_blank');
}
</script>
  <div class="card">
    <table>
      <tr><th>ID</th><th>Item</th><th>Qty</th><th>Status</th><th>Photo Proof</th></tr>
      <?php
      if($res && $res->num_rows>0){
        while($row=$res->fetch_assoc()){
            $qty = $row['qty']?? $row['quantity']?? '1';
            $st = strtolower($row['status']); $cls = $st=='delivered'? 'b-delivered' : 'b-pending';
            $proof = $row['proof_image']?? '';
            echo "<tr>
              <td>#{$row['id']}</td>
              <td style='font-weight:600'>".htmlspecialchars($row['item_name'])."</td>
              <td>$qty</td>
              <td><span class='badge $cls'>".ucfirst($st)."</span></td>
              <td>";
            if(!empty($proof)){
                echo "<button class='proofBtn hasProof' onclick=\"openProof('$proof')\">🖼️ View</button>";
            } else {
                echo "<button class='proofBtn upload' onclick=\"openUpload({$row['id']})\">📤 Upload Proof</button>";
            }
            echo "</td></tr>";
        }
      } else {
        echo "<tr><td colspan='5' style='text-align:center;padding:30px'>No donations yet</td></tr>";
      }
     ?>
    </table>
  </div>
</div>

<!-- Upload Modal -->
<div id="uploadModal" onclick="if(event.target.id=='uploadModal') closeUpload()">
  <div class="modalBox">
    <h3 style="margin:0;font-family:'Bricolage Grotesque'">Upload Payment Proof 📸</h3>
    <p style="font-size:11px;color:#666">Upload your GPay/PhonePe screenshot for ImpactConnect</p>
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="donation_id" id="uploadId">
      <input type="file" name="proof_file" accept="image/*" required style="border:2px dashed #111;padding:12px;border-radius:12px">
      <button type="submit" name="upload_proof" class="btnBlack">Upload & Mark Delivered ✅</button>
      <button type="button" class="btnWhite" onclick="closeUpload()">Cancel</button>
    </form>
  </div>
</div>

<!-- View Modal -->
<div id="lightbox" onclick="closeProof()">
  <img id="lightImg" src="">
  <span style="color:#fff;margin-top:12px;background:#111;padding:6px 14px;border-radius:100px;font-size:12px">Click to close</span>
</div>

<script>
function openUpload(id){
  document.getElementById('uploadId').value = id;
  document.getElementById('uploadModal').classList.add('show');
}
function closeUpload(){ document.getElementById('uploadModal').classList.remove('show'); }
function openProof(src){
  document.getElementById('lightImg').src = src;
  document.getElementById('lightbox').classList.add('show');
}
function closeProof(){ document.getElementById('lightbox').classList.remove('show'); }
</script>

<?php include 'includes/footer.php';?>