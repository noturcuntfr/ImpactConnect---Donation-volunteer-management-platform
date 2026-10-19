<?php
include 'config.php';
include 'includes/header.php';
if(isset($_POST['register'])){
    $name = $conn->real_escape_string($_POST['vol_name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $area = $conn->real_escape_string($_POST['area']);
    $conn->query("INSERT INTO volunteers (volunteer_name, phone, area, status) VALUES ('$name', '$phone', '$area', 'active')");
    echo "<script>alert('Welcome aboard! ❤️'); window.location='volunteer.php';</script>";
}
$vols = $conn->query("SELECT * FROM volunteers ORDER BY id DESC");
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@700;800;900&family=Inter:wght@400;500&display=swap');
*{box-sizing:border-box;}
body{margin:0!important; background:#F2F2F5!important;}
header, nav{margin-bottom:0!important;}

/* SAME AS HOME PAGE - LIGHT */
.full{ width:100vw; margin-left:calc(-50vw + 50%);}
.hero-home{
  background:#F2F2F5; padding:50px 4% 30px; display:flex; justify-content:center;
}
.hero-card{
  background:#FFFFFF; width:92%; max-width:1250px; border-radius:28px; padding:50px 5%;
  display:grid; grid-template-columns:1.1fr 0.9fr; align-items:center; gap:30px;
  box-shadow:0 10px 40px rgba(0,0,0,0.04); border:1px solid #eee;
}
@media(max-width:900px){.hero-card{grid-template-columns:1fr; width:95%;}}
.left h1{ font-family:'Bricolage Grotesque'; font-size:58px; line-height:0.95; font-weight:900; margin:0; letter-spacing:-0.03em; color:#111;}
.left h1 i{ font-family:Georgia; font-weight:400; font-style:italic; color:#9AA0A6; }
.left p{ color:#6B7280; font-size:15px; line-height:1.6; max-width:480px; margin-top:18px; font-family:'Inter';}
.btn-orange{ background:#FF6A2A; color:#fff; border:none; padding:14px 24px; border-radius:12px; font-weight:800; cursor:pointer; font-family:'Inter';}
.btn-white{ background:#fff; color:#111; border:1.5px solid #111; padding:14px 24px; border-radius:12px; font-weight:800; cursor:pointer; font-family:'Inter'; margin-left:8px;}

/* Tilted card like home */
.right{ display:flex; justify-content:center; align-items:center;}
.tilted-card{
  background:#fff; padding:10px 10px 18px 10px; border-radius:20px; transform:rotate(3deg);
  box-shadow:0 20px 60px rgba(0,0,0,0.15); width:fit-content; border:1px solid #eee;
}
.tilted-card img{ width:360px; height:380px; object-fit:cover; border-radius:14px; display:block;}
.caption{ font-family:'Bricolage Grotesque'; font-weight:900; font-size:16px; margin-top:12px; color:#111; line-height:1; text-transform:uppercase;}
.caption i{ color:#FF6A2A; font-family:Georgia; font-style:italic; font-weight:400; font-size:15px; display:block; margin-top:4px; text-transform:none;}

.wrap{ background:#F2F2F5; padding:30px 4% 50px; display:flex; justify-content:center;}
.inner{ width:92%; max-width:1250px; display:grid; grid-template-columns:1.2fr 0.8fr; gap:24px;}
@media(max-width:950px){.inner{grid-template-columns:1fr; width:95%;}}
.card{ background:#fff; border-radius:24px; padding:28px; border:1.5px solid #111; box-shadow:8px 8px 0 #111;}
.input{ width:100%; padding:16px 18px; margin:9px 0; border:2px solid #111; border-radius:14px; background:#fff; font-weight:600; outline:none; font-family:'Inter';}
.btn-black{ background:#111; color:#fff; border:2px solid #111; padding:18px; border-radius:14px; font-weight:900; width:100%; margin-top:10px; cursor:pointer; box-shadow:6px 6px 0 #FF6A2A;}
.vol-table{ width:100%; border-collapse:collapse; margin-top:16px;}
.vol-table th{ text-align:left; font-size:11px; color:#999; padding:12px 10px; background:#F9F6F1; }
.vol-table td{ padding:14px 10px; font-size:13px; border-top:1px solid #eee; font-family:'Inter';}
.status{ background:#D1FAE5; color:#065F46; padding:5px 12px; border-radius:100px; font-size:11px; font-weight:800;}
</style>

<div class="full hero-home">
  <div class="hero-card">
    <div class="left">
      <h1>Be The <i>Bridge.</i><br>Volunteer.<br>Connect.</h1>
      <p>No bike? No problem. We connect you to nearby donors in Your City. Pickup items, deliver to NGOs, get real impact photos. Your area, your time.</p>
      <div style="margin-top:24px; display:flex;">
        <button onclick="document.getElementById('form').scrollIntoView({behavior:'smooth'})" class="btn-orange">Join as Volunteer →</button>
        <button class="btn-white"><?= $vols->num_rows ?> Heroes Active</button>
      </div>
      <div style="display:flex; gap:24px; margin-top:28px; border-top:1px solid #eee; padding-top:20px; width:fit-content;">
        <div><b style="font-size:20px;"><?= $vols->num_rows ?></b><br><span style="font-size:10px; color:#888;">ACTIVE HEROES</span></div>
        <div><b style="font-size:20px;">0 ₹</b><br><span style="font-size:10px; color:#888;">NO FEES EVER</span></div>
        <div><b style="font-size:20px;">2H</b><br><span style="font-size:10px; color:#888;">/ WEEK ENOUGH</span></div>
      </div>
    </div>
    <div class="right">
      <div class="tilted-card">
        <img src="https://images.unsplash.com/photo-1559027615-cd4628902d4a?q=80&w=900" alt="volunteer">
        <div class="caption">VOLUNTEERS ARE REAL HEROES <i>Delivering hope daily</i></div>
      </div>
    </div>
  </div>
</div>

<div class="full wrap">
  <div class="inner">
    <div>
      <div class="card" id="form">
        <h2 style="margin:0; font-family:'Bricolage Grotesque'; font-size:26px; font-weight:900;">Become a Volunteer. ⚡</h2>
        <p style="color:#888; font-size:13px; margin:10px 0 18px; font-family:'Inter';">Takes 20 seconds - we will call you to verify</p>
        <form method="POST">
          <input class="input" type="text" name="vol_name" placeholder="Your Full Name" required>
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
            <input class="input" type="tel" name="phone" placeholder="Phone - 9876543210" pattern="[0-9]{10}" maxlength="10" required>
            <input class="input" type="text" name="area" placeholder="Your Area - ex: Vapi" required>
          </div>
          <button class="btn-black" name="register">Register as Volunteer →</button>
        </form>
      </div>
      <div class="card" style="margin-top:20px;">
        <h3 style="margin:0; font-family:'Bricolage Grotesque'; font-size:18px; font-weight:900;">Community Heroes</h3>
        <table class="vol-table">
          <tr><th>VOLUNTEER</th><th>AREA</th><th>STATUS</th></tr>
          <?php while($v = $vols->fetch_assoc()){
              $nm = htmlspecialchars($v['volunteer_name']);
              echo "<tr><td><b>$nm</b></td><td>{$v['area']}</td><td><span class='status'>Active</span></td></tr>";
          } ?>
        </table>
      </div>
    </div>
    <div>
      <div class="card" style="background:#111; color:#fff; border-radius:22px; padding:24px; box-shadow:8px 8px 0px #FF6A2A; position:sticky; top:90px;">
        <h4 style="margin:0; font-family:'Bricolage Grotesque'; font-size:18px; font-weight:800;">Why volunteers love us? 🤝</h4>
        <div style="margin-top:16px; display:flex; flex-direction:column; gap:14px;">
          <div style="display:flex; gap:12px;"><span style="background:#FF6A2A; width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px;">✓</span><div><b style="font-size:13px;">Flexible Timing</b><br><span style="font-size:11px; color:#9A8C7D;">2 hours a week is enough.</span></div></div>
          <div style="display:flex; gap:12px;"><span style="background:#fff; color:#111; width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px;">📸</span><div><b style="font-size:13px;">Real Impact</b><br><span style="font-size:11px; color:#9A8C7D;">See photos of your delivery.</span></div></div>
          <div style="display:flex; gap:12px;"><span style="background:#fff; color:#111; width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px;">🔒</span><div><b style="font-size:13px;">Private & Safe</b><br><span style="font-size:11px; color:#9A8C7D;">Your number never public.</span></div></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include 'includes/footer.php';?>