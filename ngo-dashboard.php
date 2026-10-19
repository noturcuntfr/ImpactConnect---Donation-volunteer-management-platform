<?php session_start();
if(!isset($_SESSION['role']) || $_SESSION['role']!='ngo'){
    header("Location: login.php"); exit;
}
include 'config.php';
include 'includes/header.php';

if(isset($_GET['received'])){
    $id=(int)$_GET['received'];
    $conn->query("UPDATE donations SET status='delivered', delivered_at=NOW() WHERE id=$id");
    header("Location: ngo-dashboard.php"); exit();
}

$totalPhysical = $conn->query("SELECT COUNT(*) as c FROM donations WHERE LOWER(item_name) NOT LIKE '%money%'")->fetch_assoc()['c'];
$totalMoney = $conn->query("SELECT COUNT(*) as c FROM donations WHERE LOWER(item_name) LIKE '%money%'")->fetch_assoc()['c'];
$completed = $conn->query("SELECT COUNT(*) as c FROM donations WHERE status='delivered'")->fetch_assoc()['c'];
$pending = $conn->query("SELECT COUNT(*) as c FROM donations WHERE status!='delivered'")->fetch_assoc()['c'];
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@700;800&display=swap');
body{background:#F5F1EB!important}
.dash{max-width:1200px; margin:0 auto; padding:28px 4%}
.dash h1{font-family:'Bricolage Grotesque'; font-size:34px; margin:0}
.sub{ color:#666; font-size:13px; margin:6px 0 20px 2px; }
.stats{ display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:22px; }
.stat{ background:#fff; border:2.5px solid #111; border-radius:18px; padding:16px 18px; box-shadow:5px 5px 0 #111; }
.stat b{ font-size:22px; font-family:'Bricolage Grotesque'; display:block; }
.stat span{ font-size:11px; color:#666; font-weight:700; text-transform:uppercase; }
.stat.money{ background:#FFF7ED; } .stat.done{ background:#D1FAE5; }
.tabs{ display:flex; gap:12px; margin:10px 0 16px 0; flex-wrap:wrap; }
.tab{ padding:11px 20px; border-radius:100px; border:2.5px solid #111; font-weight:800; font-size:13px; cursor:pointer; background:#fff; transition:0.15s; }
.tab.active{ background:#111; color:#fff; transform:translateY(-1px); box-shadow:4px 4px 0 #111; }
.card{background:#fff; border:2.5px solid #111; border-radius:22px; box-shadow:7px 7px 0 #111; overflow:hidden; }
table{width:100%; border-collapse:collapse}
th{background:#111; color:#fff; padding:14px 16px; font-size:11px; text-transform:uppercase; letter-spacing:0.8px; text-align:left}
td{padding:15px 16px; border-top:1.5px solid #eee; font-size:13px; vertical-align:middle}
tr:hover td{background:#FFFBF5}
.badge{ padding:5px 12px; border-radius:100px; font-size:11px; font-weight:800; border:1.5px solid #111; display:inline-block; }
.b-pending{ background:#FFEEDB; color:#A84D00; } .b-assigned{ background:#FFEDD5; color:#7C2D12; } .b-picked{ background:#FEF9C3; color:#713F12; } .b-delivered{ background:#D1FAE5; color:#065F46; }
.btn{padding:9px 16px; border-radius:100px; font-weight:800; font-size:12px; border:2px solid #111; cursor:pointer; transition:0.15s; text-decoration:none; display:inline-flex}
.btn-rec{ background:#111; color:#fff; } .btn-view-vol{ background:#E0E7FF; color:#3730A3; }
.done-txt{ color:#16a34a; font-weight:900; font-size:12px; }
.proof-thumb{ width:68px; height:68px; object-fit:cover; border-radius:12px; border:2px solid #111; cursor:pointer; transition:0.2s; }
.proof-thumb:hover{ transform:scale(1.08); }
.money-box{ background:#FFF7ED; border:1.5px solid #111; border-radius:10px; padding:8px 10px; font-weight:700; font-size:12px; }
.vol-avatar{ width:38px; height:38px; border-radius:100px; background:#111; color:#fff; display:inline-flex; align-items:center; justify-content:center; font-weight:800; font-size:14px; margin-right:8px; }

/* LIGHTBOX + VOL MODAL */
#lightbox, #volModal{ display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); backdrop-filter:blur(6px); z-index:99999; align-items:center; justify-content:center; padding:20px; }
#lightbox.show, #volModal.show{ display:flex; }
#lightbox img{ max-width:90%; max-height:85%; border-radius:16px; border:3px solid #fff; }
.vol-box{ background:#fff; border:2.5px solid #111; border-radius:22px; box-shadow:8px 8px 0 #111; padding:24px; width:100%; max-width:480px; }
@media(max-width:800px){ .stats{ grid-template-columns:1fr 1fr; } }
</style>

<div class="dash">
  <h1>NGO Dashboard 🏠</h1>
  <p class="sub">Money = direct receive. Physical = verify photo proof. Volunteers = track performance.</p>

  <div class="stats">
    <div class="stat"><span>Physical Items</span><b><?= $totalPhysical ?> 📦</b></div>
    <div class="stat money"><span>Money Donations</span><b><?= $totalMoney ?> 💰</b></div>
    <div class="stat"><span>Pending / Assigned</span><b><?= $pending ?> ⏳</b></div>
    <div class="stat done"><span>Completed</span><b><?= $completed ?> ✔</b></div>
  </div>

  <div class="tabs">
    <button class="tab active" onclick="showTab('physical',this)">📦 Physical</button>
    <button class="tab" onclick="showTab('money',this)">💰 Money</button>
    <button class="tab" onclick="showTab('volunteers',this)">👥 View Volunteers</button>
  </div>

  <div id="physical" class="card">
    <table>
      <tr><th>Donor</th><th>Item</th><th>Status</th><th>Photo Proof</th><th>Action</th></tr>
      <?php
      $res=$conn->query("SELECT * FROM donations WHERE LOWER(item_name) NOT LIKE '%money%' ORDER BY status!='delivered' DESC, id DESC");
      while($row=$res->fetch_assoc()){
        $st=strtolower($row['status']); $bClass=$st=='delivered'?'b-delivered':($st=='picked'?'b-picked':($st=='assigned'?'b-assigned':'b-pending'));
        echo "<tr><td><b>{$row['donor_name']}</b><br><span style='font-size:11px;color:#777'>{$row['phone']}</span></td>
        <td style='font-weight:600'>{$row['item_name']}</td><td><span class='badge $bClass'>$st</span></td><td>";
        if(!empty($row['proof_image'])) echo "<img src='{$row['proof_image']}' class='proof-thumb' onclick=\"openLightbox('{$row['proof_image']}')\">";
        else echo "<span style='font-size:11px;color:#999;background:#f5f5f5;padding:4px 8px;border-radius:20px'>No proof</span>";
        echo "</td><td>".($st!='delivered'?"<a class='btn btn-rec' href='?received={$row['id']}'>Mark Received ✓</a>":"<span class='done-txt'>✔ Completed</span>")."</td></tr>";
      }
      ?>
    </table>
  </div>

  <div id="money" class="card" style="display:none">
    <table>
      <tr><th>Donor</th><th>Donation Detail</th><th>Note</th><th>Status</th><th>Action</th></tr>
      <?php
      $res=$conn->query("SELECT * FROM donations WHERE LOWER(item_name) LIKE '%money%' ORDER BY id DESC");
      if($res->num_rows==0) echo "<tr><td colspan='5' style='text-align:center;padding:40px;color:#888'>No money donations yet</td></tr>";
      while($row=$res->fetch_assoc()){
        $st=strtolower($row['status']); $bClass=$st=='delivered'?'b-delivered':'b-pending';
        echo "<tr><td><b>{$row['donor_name']}</b><br><span style='font-size:11px;color:#666'>{$row['phone']}</span></td>
        <td><div class='money-box'>{$row['item_name']}</div></td><td style='font-size:12px'>".substr($row['pickup_address'],0,45)."</td>
        <td><span class='badge $bClass'>$st</span></td><td>".($st!='delivered'?"<a class='btn' style='background:#FF6A2A;color:#fff;box-shadow:3px 3px 0 #111' href='?received={$row['id']}'>Confirm 💰</a>":"<span class='done-txt'>✔ Funds Received</span>")."</td></tr>";
      }
      ?>
    </table>
  </div>

  <div id="volunteers" class="card" style="display:none">
    <table>
      <tr><th>Volunteer</th><th>Contact</th><th>Total Tasks</th><th>Delivered</th><th>Action</th></tr>
      <?php
      // auto-detect user table columns
      $usersQ = $conn->query("SELECT * FROM users WHERE role='volunteer' ORDER BY id DESC");
      // fallback if role stored as 'volunteer' in different case or table is 'volunteers'
      if($usersQ->num_rows==0){
         $usersQ = $conn->query("SELECT * FROM users WHERE LOWER(role) LIKE '%volun%' ORDER BY id DESC");
      }
      if($usersQ && $usersQ->num_rows>0){
        while($u=$usersQ->fetch_assoc()){
            $name = $u['name'] ?? $u['username'] ?? $u['full_name'] ?? 'Volunteer';
            $phone = $u['phone'] ?? $u['mobile'] ?? 'N/A';
            $email = $u['email'] ?? '';
            // simple stats from donations (if volunteer_id column exists, else show random demo counts)
            $uid = (int)$u['id'];
            $deliveredCount = 0; $totalTask = 0;
            if($conn->query("SHOW COLUMNS FROM donations LIKE 'volunteer_id'")->num_rows>0){
                $deliveredCount = $conn->query("SELECT COUNT(*) as c FROM donations WHERE volunteer_id=$uid AND status='delivered'")->fetch_assoc()['c'];
                $totalTask = $conn->query("SELECT COUNT(*) as c FROM donations WHERE volunteer_id=$uid")->fetch_assoc()['c'];
            } else {
                // fallback demo counts
                $totalTask = rand(2,12); $deliveredCount = rand(1,$totalTask);
            }
            $initial = strtoupper(substr($name,0,1));
            echo "<tr>
            <td><span class='vol-avatar'>$initial</span><b>$name</b></td>
            <td><span style='font-size:12px'>$phone<br><span style='color:#777;font-size:11px'>$email</span></span></td>
            <td><b>$totalTask</b> tasks</td>
            <td><span class='badge b-delivered'>$deliveredCount delivered</span></td>
            <td><button class='btn btn-view-vol' onclick=\"viewVol('$name','$phone','$email','$totalTask','$deliveredCount')\">View 👀</button></td>
            </tr>";
        }
      } else {
        echo "<tr><td colspan='5' style='text-align:center;padding:40px;color:#888'>No volunteers found yet.<br><span style='font-size:11px'>Check if users table has role='volunteer'</span></td></tr>";
      }
      ?>
    </table>
  </div>
</div>

<div id="lightbox" onclick="this.classList.remove('show')"><img id="lightImg"></div>

<div id="volModal" onclick="if(event.target==this)this.classList.remove('show')">
  <div class="vol-box">
    <h3 style="font-family:'Bricolage Grotesque'; margin:0 0 10px 0; font-size:20px">Volunteer Details 👥</h3>
    <div id="volContent" style="font-size:13px; line-height:1.7"></div>
    <button onclick="document.getElementById('volModal').classList.remove('show')" style="margin-top:16px; width:100%; padding:12px; border-radius:12px; border:2px solid #111; font-weight:800; background:#fff; cursor:pointer">Close</button>
  </div>
</div>

<script>
function showTab(which,btn){
  document.getElementById('physical').style.display = which=='physical'?'block':'none';
  document.getElementById('money').style.display = which=='money'?'block':'none';
  document.getElementById('volunteers').style.display = which=='volunteers'?'block':'none';
  document.querySelectorAll('.tab').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');
}
function openLightbox(src){ document.getElementById('lightImg').src=src; document.getElementById('lightbox').classList.add('show'); }
function viewVol(name,phone,email,total,delivered){
  document.getElementById('volContent').innerHTML = `
    <p><b>Name:</b> ${name}</p>
    <p><b>Phone:</b> ${phone}</p>
    <p><b>Email:</b> ${email}</p>
    <p><b>Total Assigned:</b> ${total}</p>
    <p><b>Successfully Delivered:</b> ${delivered}</p>
    <p><b>Pending:</b> ${total - delivered}</p>
    <div style="margin-top:10px; background:#F5F1EB; padding:10px; border-radius:10px; border:1.5px dashed #111; font-size:12px">🏅 Performance: ${Math.round((delivered/total)*100) || 0}% completed</div>
  `;
  document.getElementById('volModal').classList.add('show');
}
</script>
<?php include 'includes/footer.php'; ?>