<?php include 'includes/header.php'; ?>
<style>
.hero { background:#fff; padding:60px 40px; border-radius:16px; box-shadow:0 2px 15px rgba(0,0,0,0.05); margin-bottom:25px; }
.hero h1 { font-size:48px; font-weight:800; line-height:1.1; margin:0; }
.hero h1 span { color:#666; }
.hero p { font-size:18px; color:#555; max-width:650px; margin:20px 0 30px 0; line-height:1.6; }
.btn { display:inline-block; padding:12px 22px; border-radius:8px; font-weight:600; text-decoration:none; margin-right:10px; }
.btn-black { background:#000; color:#fff; }
.btn-white { background:#fff; color:#000; border:1px solid #ddd; }
.stats { display:grid; grid-template-columns:repeat(3, 1fr); gap:20px; margin-bottom:25px; }
.stat-card { background:#000; color:#fff; padding:25px; border-radius:12px; text-align:center; }
.stat-card h2 { font-size:32px; margin:0; }
.stat-card p { margin:5px 0 0 0; color:#aaa; }
.stat-card:hover { transform:translateY(-6px); }
.how-it-works { background:#fff; padding:40px; border-radius:16px; box-shadow:0 2px 15px rgba(0,0,0,0.05); }
.steps { display:grid; grid-template-columns:repeat(3, 1fr); gap:30px; margin-top:25px; }
.step h3 { margin:10px 0; }
.step p { color:#666; font-size:14px; line-height:1.5; }
.icon { width:50px; height:50px; background:#f4f4f4; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:24px; }
</style>


    <div style="max-width:1200px; margin: 40px auto; background:#fff; border-radius:20px; padding:50px; display:grid; grid-template-columns: 1.2fr 340px; align-items:center; gap:20px;">

  <!-- LEFT TEXT -->
  <div>
    <h1 style="font-size:52px; font-weight:800; line-height:1.1; margin:0;">
      Be The <i style="font-family:serif; font-weight:400; color:#888;">Impact.</i><br>
      Donate. Volunteer. Connect.
    </h1>
    <p style="color:#666; margin-top:18px; line-height:1.6;">
      ImpactConnect is a unified platform that bridges the gap between those who want to give and those in need. We connect generous donors, passionate volunteers, and verified NGOs in one seamless ecosystem to create real, measurable change.
    </p>
    <div style="margin-top:24px; display:flex; gap:12px;">
      <a href="donation.php" style="background: #FF6A2A; color:#fff; padding:11px 20px; border-radius:10px; text-decoration:none; font-weight:600;">Donate Now →</a>
      <a href="volunteer.php" style="background:#fff; color:#000; padding:11px 20px; border-radius:10px; border:1px solid #ddd; text-decoration:none; font-weight:600;">Join as Volunteer</a>
    </div>
  </div>

  <!-- RIGHT CARD -->
  <div style="display:flex; justify-content:center;">
    <div style="width:320px; background:#fff; padding:8px; border-radius:18px; transform:rotate(4deg); box-shadow:0 20px 40px rgba(0,0,0,0.15); border:1px solid #eee;">
      <img src="donate-card.jpg" style="width:100%; border-radius:12px; display:block;" alt="Small Acts">
    </div>
  </div>

</div>
  </div>

</div>
    <!-- <a href="donation.php" class="btn btn-black">Donate Now →</a>
    <a href="volunteer.php" class="btn btn-white">Join as Volunteer</a> -->
</div>

<div class="stats">
    <div class="stat-card">
        <h2>500+</h2>
        <p>Donations Completed</p>
    </div>
    <div class="stat-card">
        <h2>120+</h2>
        <p>Active Volunteers</p>
    </div>
    <div class="stat-card">
        <h2>25+</h2>
        <p>NGOs Connected</p>
    </div>
</div>

<div class="how-it-works">
    <h2 style="font-size:28px; font-weight:700; margin:0;">How It Works</h2>
    <p style="color:#666; margin-top:8px;">Simple, transparent, and impactful - in just 3 steps.</p>
    
    <div class="steps">
        <div class="step">
            <div class="icon">📦</div>
            <h3>1. Donate Goods</h3>
            <p>List your extra clothes, books, food, or essentials. Add your location and our volunteers will handle the pickup.</p>
        </div>
        <div class="step">
            <div class="icon">🤝</div>
            <h3>2. Volunteer Steps In</h3>
            <p>Verified volunteers from your area pick up the donation and ensure it reaches the right NGO safely and quickly.</p>
        </div>
        <div class="step">
            <div class="icon">❤️</div>
            <h3>3. Create Impact</h3>
            <p>Your small contribution reaches someone who truly needs it. Track your donation and see the real impact you made.</p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>