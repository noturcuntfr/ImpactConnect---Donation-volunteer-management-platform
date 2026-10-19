<?php session_start();
if(!isset($_SESSION['role']) || $_SESSION['role']!= 'volunteer'){
    header("Location: login.php"); exit;
}
include 'config.php';
include 'includes/header.php';

if(isset($_POST['deliver_now'])){
    $id = (int)$_POST['donation_id'];
    $proofPath = "";
    if(!empty($_FILES['proof']['name'])){
        $ext = pathinfo($_FILES['proof']['name'], PATHINFO_EXTENSION);
        $fileName = "proof_".$id."_".time().".".$ext;
        $target = "uploads/proofs/".$fileName;
        if(!is_dir("uploads/proofs")) mkdir("uploads/proofs",0777,true);
        move_uploaded_file($_FILES['proof']['tmp_name'], $target);
        $proofPath = $target;
    }
    $conn->query("UPDATE donations SET status='delivered', proof_image='$proofPath', delivered_at=NOW() WHERE id=$id");
    echo "<script>alert('Delivered with Photo Proof! ❤ '); window.location='volunteer-dashboard.php';</script>";
    exit();
}

if(isset($_GET['picked'])){
    $id=(int)$_GET['picked'];
    $conn->query("UPDATE donations SET status='picked' WHERE id=$id");
    header("Location: volunteer-dashboard.php");
    exit();
}

// COUNTS FOR TOP CARDS
$totalActive = $conn->query("SELECT COUNT(*) as c FROM donations WHERE status!='delivered' AND LOWER(item_name) NOT LIKE '%money%'")->fetch_assoc()['c']?? 0;
$pending = $conn->query("SELECT COUNT(*) as c FROM donations WHERE status='pending' AND LOWER(item_name) NOT LIKE '%money%'")->fetch_assoc()['c']?? 0;
$deliveredToday = $conn->query("SELECT COUNT(*) as c FROM donations WHERE status='delivered' AND DATE(delivered_at)=CURDATE()")->fetch_assoc()['c']?? 0;
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@700;800&display=swap');
body{background:#F5F1EB!important}
.dash{max-width:1150px; margin:0 auto; padding:30px 5%}
.dash h1{font-family:'Bricolage Grotesque'; font-size:32px; letter-spacing:-0.5px; margin:0 0 6px 0}
.dash p.head-sub{ color:#666; font-size:13px; margin:0 0 20px 2px; }
.stats{display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; margin:0 0 22px 0}
.stat{border:2.5px solid #111; border-radius:18px; padding:16px 18px; background:#fff; box-shadow:5px 5px 0 #111}
.card{background:#fff; border:2.5px solid #111; border-radius:22px; box-shadow:7px 7px 0 #111; overflow:hidden; margin-top:10px}
table{width:100%; border-collapse:collapse}
th{background:#111; color:#fff; padding:14px 16px; font-size:11px; text-transform:uppercase; letter-spacing:0.8px; text-align:left}
td{padding:16px; border-top:1.5px solid #eee; font-size:13px; vertical-align:middle}
tr:hover td{background:#FFFBF5}
.badge{ padding:5px 10px; border-radius:100px; font-size:11px; font-weight:800; text-transform:lowercase; border:1.5px solid #111; display:inline-block; }
.b-pending{ background:#FFEEDB; color:#A84D00; }
.b-picked{ background:#FFF3C4; color:#7A5D00; }
.b-assigned{ background:#E0E7FF; color:#3730A3; }
.btn{padding:9px 16px; border-radius:100px; text-decoration:none; font-weight:800; font-size:12px; border:2px solid #111; cursor:pointer; transition:0.15s; display:inline-flex; align-items:center; gap:5px; white-space:nowrap}
.btn-pick{background:#FF6A2A; color:#fff; box-shadow:3px 3px 0 #111}
.btn-pick:hover{ transform:translate(-1px,-1px); box-shadow:4px 4px 0 #111 }
.btn-deli{background:#111; color:#fff}
.btn-deli:hover{ background:#222; transform:scale(1.03) }
#proofModal{display:none; position:fixed; inset:0; background:rgba(0,0,0,0.65); backdrop-filter:blur(6px); z-index:9999; align-items:center; justify-content:center; padding:20px}
#proofModal.show{display:flex}
.modal-box{background:#fff; padding:26px; border-radius:22px; border:2.5px solid #111; box-shadow:9px 9px 0 #111; width:100%; max-width:420px}
.file-box{ border:2.2px dashed #111; border-radius:14px; padding:22px 20px; text-align:center; background:#FAFAF7; position:relative; cursor:pointer; width:100%; box-sizing:border-box; display:block }
.file-box.has-file{ border-style:solid; background:#fff; border-color:#16a34a; }
.file-box input{ position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
#previewWrap{ display:none; margin-top:14px; border:2px solid #111; border-radius:14px; background:#fff; padding:8px; }
#previewImg{ width:100%; height:200px; object-fit:cover; border-radius:10px; display:block; }
.btn-row{ display:flex; gap:10px; margin-top:16px }
.btn-c{ flex:1; padding:12px; border-radius:12px; border:2px solid #111; background:#fff; font-weight:800; cursor:pointer }
.btn-s{ flex:1.3; padding:12px; border-radius:12px; border:2px solid #111; background:#22c55e; color:#fff; font-weight:900; cursor:pointer; box-shadow:3px 3px 0 #111 }
.btn-s:disabled{ background:#ddd; color:#888; box-shadow:none; cursor:not-allowed; }
</style>

<div class="dash">
  <h1>Volunteer Dashboard 🤝</h1>
  <p class="head-sub">Pickup pending donations and upload delivery proof for donors to see.</p>

  <!-- ADDED: TOP 3 CARDS SO IT NEVER LOOKS EMPTY -->
  <div class="stats">
    <div class="stat">
      <b style="font-size:26px; font-family:'Bricolage Grotesque'; display:block;"><?= $totalActive?></b>
      <span style="font-size:11px; color:#6B7280; font-weight:700">Active Donations</span>
    </div>
    <div class="stat" style="background:#111; color:#fff;">
      <div style="display:flex; align-items:center; gap:6px;"><span style="width:8px; height:8px; background:#00C950; border-radius:50%; display:inline-block;"></span><span style="font-size:10px; font-weight:800; color:#00C950;">LIVE</span></div>
      <b style="font-size:22px; display:block; color:#fff; margin-top:2px;"><?= $pending?> Pending Pickups</b>
      <span style="font-size:11px; color:#9CA3AF;">in Vapi-Daman</span>
    </div>
    <div class="stat" style="background:#FFF7ED;">
      <b style="font-size:26px; display:block;"><?= $deliveredToday?></b>
      <span style="font-size:11px; color:#6B7280; font-weight:700">Delivered Today 🎉</span>
    </div>
  </div>

  <div class="card">
    <table>
      <tr><th>Donor / Phone</th><th>Item</th><th>Address</th><th>Status</th><th>Action</th></tr>
      <?php
      $res=$conn->query("SELECT * FROM donations WHERE status!='delivered' AND LOWER(item_name) NOT LIKE '%money%' ORDER BY id DESC");
      if($res->num_rows==0){
          echo "<tr><td colspan='5' style='padding:0'>
            <div style='padding:60px 20px; text-align:center; background:#FFFBF5;'>
              <div style='width:80px; height:80px; background:#fff; border:2.5px solid #111; border-radius:50%; margin:0 auto 16px; display:flex; align-items:center; justify-content:center; font-size:36px; box-shadow:4px 4px 0 #111;'>📦</div>
              <h3 style='font-family:Bricolage Grotesque; font-size:20px; margin:0 0 6px 0;'>All caught up! 🎉</h3>
              <p style='color:#6B7280; font-size:13px; max-width:340px; margin:0 auto 16px;'>No active donations right now. New donations in Vapi-Daman will appear here automatically. You're doing great!</p>
              <a href='../donation.php' style='display:inline-block; background:#111; color:#fff; padding:10px 18px; border-radius:100px; font-size:12px; font-weight:800; text-decoration:none; border:2px solid #111; box-shadow:3px 3px 0 #FF6A2A;'>Check Donation Page ↗</a>
            </div>
          </td></tr>";
      }
      while($row=$res->fetch_assoc()){
          $st=strtolower($row['status']);
          $badgeClass = $st=='pending'?'b-pending':($st=='picked'?'b-picked':'b-assigned');
          echo "<tr>
          <td><b>{$row['donor_name']}</b><br><span style='font-size:11px; color:#666'>{$row['phone']}</span></td>
          <td style='font-weight:600'>{$row['item_name']}</td>
          <td style='color:#555; font-size:12px'>".substr($row['pickup_address'],0,32)."...</td>
          <td><span class='badge $badgeClass'>{$st}</span></td><td>";
          if($st=='pending') echo "<a class='btn btn-pick' href='?picked={$row['id']}'>Pick Up 🚚</a>";
          else echo "<button class='btn btn-deli' onclick=\"openProof({$row['id']})\">Deliver + Photo 📸 </button>";
          echo "</td></tr>";
      }
    ?>
    </table>
  </div>
</div>

<!-- PROOF UPLOAD MODAL (same as yours) -->
<div id="proofModal">
  <div class="modal-box">
    <h3 style="font-family:'Bricolage Grotesque'; margin:0; font-size:19px">Upload Photo Proof 📸 </h3>
    <p style="font-size:12px; color:#666; margin:6px 0 0">Take photo when NGO receives items.</p>
    <form method="POST" enctype="multipart/form-data" id="proofForm">
      <input type="hidden" name="donation_id" id="modalId">
      <label class="file-box" id="dropZone">
        <input type="file" name="proof" id="proofInput" accept="image/*" capture="camera" required>
        <div style="font-size:28px" id="bigIcon">🖼️</div>
        <div style="font-size:13px; font-weight:700; margin-top:4px;" id="txt1">Click or drag photo here</div>
        <div style="font-size:11px; color:#888; margin-top:2px;" id="small1">JPG, PNG - NGO holding items</div>
      </label>
      <div id="previewWrap"><img id="previewImg"><div id="fileName" style="font-size:12px; font-weight:800; text-align:center; margin-top:8px; color:#16a34a;"></div></div>
      <div class="btn-row">
        <button type="button" onclick="closeProof()" class="btn-c">Cancel</button>
        <button type="submit" name="deliver_now" id="submitBtn" class="btn-s" disabled>Mark Delivered ✓</button>
      </div>
    </form>
  </div>
</div>

<script>
const proofInput = document.getElementById('proofInput');
const previewImg = document.getElementById('previewImg');
const previewWrap = document.getElementById('previewWrap');
const fileName = document.getElementById('fileName');
const submitBtn = document.getElementById('submitBtn');
const dropZone = document.getElementById('dropZone');
function openProof(id){ document.getElementById('modalId').value=id; document.getElementById('proofModal').classList.add('show'); }
proofInput.addEventListener('change', function(){
  const file = this.files[0]; if(!file) return;
  previewImg.src = URL.createObjectURL(file);
  previewWrap.style.display='block';
  fileName.textContent = file.name + " ✓ Ready";
  dropZone.classList.add('has-file');
  submitBtn.disabled=false;
});
function closeProof(){ document.getElementById('proofModal').classList.remove('show'); previewWrap.style.display='none'; dropZone.classList.remove('has-file'); proofInput.value=''; submitBtn.disabled=true; }
</script>
<?php include 'includes/footer.php';?>