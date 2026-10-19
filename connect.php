<?php
include 'config.php';
include 'includes/header.php';

if(isset($_POST['post_req'])){
    $title = $conn->real_escape_string($_POST['org']);
    $desc = $conn->real_escape_string($_POST['need']);
    $urg = $conn->real_escape_string($_POST['urgency']);

    $sql = "INSERT INTO requirements 
            (title, description, status, urgency, created_at) 
            VALUES ('$title', '$desc', 'open', '$urg', NOW())";
    
    if($conn->query($sql)){
        echo "<script>
            alert('Requirement Posted! - $urg urgency');
            window.location='connect.php';
        </script>";
    } else {
        echo "DB Error: " . $conn->error;
    }
}

$res = $conn->query(
    "SELECT * FROM requirements ORDER BY id DESC"
);
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@700;800;900&family=Inter:wght@400;500&display=swap');

*{ box-sizing:border-box; }
body{ margin:0!important; background:#0E0E0E!important; }
header, nav{ margin-bottom:0!important; }

.full{
    width:100vw;
    margin-left:calc(-50vw + 50%);
}
.hero-cool{
    background:#0E0E0E;
    min-height:84vh;
    display:grid;
    grid-template-columns:1.1fr 0.9fr;
    padding:50px 6% 60px;
    position:relative;
    overflow:hidden;
    margin-top:-1px;
    border-top:1px solid #0E0E0E;
}
@media(max-width:900px){
    .hero-cool{ grid-template-columns:1fr; }
}
.left{
    position:relative;
    z-index:5;
    display:flex;
    flex-direction:column;
    justify-content:center;
}
.badge{
    background:#fff;
    color:#000;
    display:inline-flex;
    padding:8px 16px;
    border-radius:100px;
    font-size:12px;
    font-weight:800;
    gap:8px;
    align-items:center;
    width:fit-content;
}
.dot{
    width:8px;
    height:8px;
    background:#FF6A2A;
    border-radius:50%;
}
.left h1{
    font-family:'Bricolage Grotesque';
    font-size:72px;
    line-height:0.88;
    letter-spacing:-0.04em;
    margin:22px 0 0;
    font-weight:900;
    color:#fff;
}
.left h1 .line2{
    color:transparent;
    -webkit-text-stroke:1.5px #fff;
}
.left h1 .line3{
    color:#FF6A2A;
}
.left p{
    color:#888;
    max-width:420px;
    font-size:14px;
    line-height:1.6;
    margin-top:20px;
    font-family:'Inter';
}
.right{
    position:relative;
    z-index:5;
    display:flex;
    justify-content:center;
    align-items:center;
}
.tilted-card{
    background:#fff;
    padding:12px 12px 16px 12px;
    border-radius:20px;
    transform:rotate(3deg);
    box-shadow:0 30px 80px rgba(0,0,0,0.6);
    width:fit-content;
}
.tilted-card img{
    width:380px;
    height:420px;
    object-fit:cover;
    border-radius:14px;
    display:block;
}
.caption{
    font-family:'Bricolage Grotesque';
    font-weight:800;
    font-size:15px;
    margin-top:12px;
    color:#111;
}
.caption i{
    color:#FF6A2A;
    font-family:Georgia;
    font-style:italic;
    font-weight:400;
    font-size:13px;
    display:block;
}

.wrap{
    background:#F5F1EB;
    padding:50px 6%;
    position:relative;
    z-index:10;
    border-radius:32px 32px 0 0;
    margin-top:-20px;
}
.inner{
    max-width:1250px;
    margin:0 auto;
    display:grid;
    grid-template-columns:1.2fr 0.8fr;
    gap:24px;
}
@media(max-width:950px){
    .inner{ grid-template-columns:1fr; }
}
.card{
    background:#fff;
    border-radius:24px;
    padding:28px;
    border:1.5px solid #111;
    box-shadow:8px 8px 0 #111;
}
.input{
    width:100%;
    padding:16px 18px;
    margin:9px 0;
    border:2px solid #111;
    border-radius:14px;
    background:#fff;
    font-weight:600;
    outline:none;
    font-family:'Inter';
}
.btn{
    background:#FF6A2A;
    color:#111;
    border:2px solid #111;
    padding:18px;
    border-radius:14px;
    font-weight:900;
    width:100%;
    margin-top:10px;
    cursor:pointer;
    box-shadow:6px 6px 0 #111;
    font-size:16px;
}

/* URGENCY */
.urgency-group{
    display:grid;
    grid-template-columns:1fr 1fr 1fr;
    gap:10px;
    margin:10px 0;
}
.urg-btn{
    border:2px solid #111;
    border-radius:12px;
    padding:12px;
    text-align:center;
    cursor:pointer;
    font-weight:800;
    font-size:13px;
    font-family:'Inter';
}
.urg-btn input{ display:none; }
.urg-btn.low{ background:#DCFCE7; }
.urg-btn.medium{ background:#FFEDD5; }
.urg-btn.high{ background:#FEE2E2; }
.urg-btn:has(input:checked){
    background:#111;
    color:#fff;
    box-shadow:4px 4px 0 #FF6A2A;
    transform:translate(-2px, -2px);
}
.req-card{
    background:#fff;
    border-radius:20px;
    padding:20px 24px;
    border:1.5px solid #111;
    box-shadow:6px 6px 0 #111;
    margin-bottom:16px;
}
</style>

<div class="full hero-cool">
    <div class="left">
        <div class="badge">
            <span class="dot"></span>
            URGENCY MODE ON - 
            <?php echo $res->num_rows; ?> live requests
        </div>
        <h1>
            <span>Need help?</span><br>
            <span class="line2">Choose</span><br>
            <span class="line3">your urgency<br>level.</span>
        </h1>
        <p>
            Post what you need - Low for future, 
            Medium for this week, High for urgent today. 
            Volunteers filter by urgency.
        </p>
    </div>
    <div class="right">
        <div class="tilted-card">
            <img 
                src="https://images.unsplash.com/photo-1593113598332-cd288d649433?q=80&w=900" 
                alt="connect"
            >
            <div class="caption">
                URGENCY MATTERS 
                <i>High = Today, Low = Later</i>
            </div>
        </div>
    </div>
</div>

<div class="full wrap">
    <div class="inner">
        <div>
            <div class="card">
                <h2 style="margin:0; font-family:'Bricolage Grotesque'; font-size:26px; font-weight:900;">
                    Connect - Post Requirement 🤝
                </h2>
                <p style="color:#888; font-size:13px; margin:0 0 20px 0; font-family:'Inter';">
                    Select urgency - we will prioritize
                </p>

                <form method="POST">
                    <label style="font-size:12px; font-weight:800;">
                        Organization / Your Name
                    </label>
                    <input 
                        type="text" 
                        name="org" 
                        placeholder="Ex: Patel Jash - Janseva Foundation" 
                        required 
                        class="input"
                    >
                    
                    <label style="font-size:12px; font-weight:800; margin-top:8px; display:block;">
                        What do you need?
                    </label>
                    <textarea 
                        name="need" 
                        placeholder="Ex: We need 10 volunteers for our education drive in Bardoli..." 
                        required 
                        class="input" 
                        style="min-height:120px;"
                    ></textarea>

                    <label style="font-size:12px; font-weight:800; margin-top:8px; display:block;">
                        Select Urgency Mode 🔥
                    </label>
                    <div class="urgency-group">
                        <label class="urg-btn low">
                            <input type="radio" name="urgency" value="low" required>
                            🟢 Low<br>
                            <span style="font-size:10px; font-weight:400;">Within month</span>
                        </label>
                        <label class="urg-btn medium">
                            <input type="radio" name="urgency" value="medium" checked>
                            🟠 Medium<br>
                            <span style="font-size:10px; font-weight:400;">This week</span>
                        </label>
                        <label class="urg-btn high">
                            <input type="radio" name="urgency" value="high">
                            🔴 High<br>
                            <span style="font-size:10px; font-weight:400;">Urgent Today</span>
                        </label>
                    </div>

                    <button name="post_req" class="btn">
                        Post with Urgency →
                    </button>
                </form>
            </div>

            <h2 style="font-size:22px; font-family:'Bricolage Grotesque'; font-weight:900; margin:28px 0 16px;">
                All Requirements
            </h2>

            <?php
            $res=$conn->query("SELECT * FROM requirements ORDER BY id DESC");
            while($row=$res->fetch_assoc()){
                $u = $row['urgency'];
                if($u=='high'){
                    $col='#EF4444'; $bg='#FEE2E2'; $icon='🔴';
                } elseif($u=='low'){
                    $col='#16A34A'; $bg='#DCFCE7'; $icon='🟢';
                } else {
                    $col='#FF6A2A'; $bg='#FFEDD5'; $icon='🟠';
                }
                
                echo "<div class='req-card' style='border-left:5px solid $col'>
                    <div style='display:flex; justify-content:space-between; align-items:center;'>
                        <h4 style='margin:0; color:#111; font-family:Inter;'>"
                            .htmlspecialchars($row['title']).
                        "</h4>
                        <span style='background:$bg; color:$col; padding:5px 12px; border-radius:20px; font-size:11px; font-weight:900;'>
                            $icon ".strtoupper($u)."
                        </span>
                    </div>
                    <p style='margin:10px 0; color:#333; line-height:1.5; font-family:Inter;'>"
                        .htmlspecialchars($row['description']).
                    "</p>
                    <p style='margin:8px 0 0 0; font-size:12px; color:#888;'>
                        Status: <span style='background:#dcfce7; padding:3px 8px; border-radius:20px;'>"
                            .$row['status'].
                        "</span> | ".$row['created_at']."
                    </p>
                </div>";
            }
            ?>
        </div>

        <div style="display:flex; flex-direction:column; gap:20px; position:sticky; top:90px;">

            <!-- 1. LIVE STATS -->
            <div style="background:#111; color:#fff; border-radius:22px; padding:24px; box-shadow:8px 8px 0px #FF6A2A; border:1.5px solid #111;">
                <h4 style="margin:0; font-family:'Bricolage Grotesque'; font-size:18px; font-weight:800;">🔴 Live Right Now</h4>
                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; margin-top:16px; text-align:center;">
                    <div style="background:#1A1A1A; padding:12px 6px; border-radius:14px;">
                        <b style="font-size:20px; color:#FF6A2A;"><?php echo $res->num_rows; ?></b><br>
                        <span style="font-size:9px; color:#777;">OPEN</span>
                    </div>
                    <div style="background:#1A1A1A; padding:12px 6px; border-radius:14px;">
                        <b style="font-size:20px; color:#fff;">12</b><br>
                        <span style="font-size:9px; color:#777;">NGOs</span>
                    </div>
                    <div style="background:#1A1A1A; padding:12px 6px; border-radius:14px;">
                        <b style="font-size:20px; color:#fff;">24H</b><br>
                        <span style="font-size:9px; color:#777;">AVG REPLY</span>
                    </div>
                </div>
                <div style="margin-top:14px; background:#FF6A2A; color:#111; padding:8px 12px; border-radius:10px; font-size:11px; font-weight:800; text-align:center;">
                    ⚡ High urgency posts get volunteers 3x faster
                </div>
            </div>

            <!-- 2. URGENCY GUIDE -->
            <div style="background:#111; color:#fff; border-radius:22px; padding:24px; box-shadow:8px 8px 0px #fff; border:1.5px solid #111;">
                <h4 style="margin:0; font-family:'Bricolage Grotesque'; font-size:18px; font-weight:800;">Urgency Guide 🚦</h4>
                <div style="margin-top:16px; display:flex; flex-direction:column; gap:14px;">
                    <div style="display:flex; gap:12px; background:#1A1A1A; padding:12px; border-radius:12px; border-left:3px solid #EF4444;">
                        <span style="background:#FEE2E2; width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px;">🔴</span>
                        <div><b style="font-size:13px;">High - Today</b><br><span style="font-size:11px; color:#9A8C7D;">Emergency, food, medical, shelter</span></div>
                    </div>
                    <div style="display:flex; gap:12px; background:#1A1A1A; padding:12px; border-radius:12px; border-left:3px solid #FF6A2A;">
                        <span style="background:#FFEDD5; width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px;">🟠</span>
                        <div><b style="font-size:13px;">Medium - This Week</b><br><span style="font-size:11px; color:#9A8C7D;">Education drive, clothes, events</span></div>
                    </div>
                    <div style="display:flex; gap:12px; background:#1A1A1A; padding:12px; border-radius:12px; border-left:3px solid #16A34A;">
                        <span style="background:#DCFCE7; width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px;">🟢</span>
                        <div><b style="font-size:13px;">Low - Future</b><br><span style="font-size:11px; color:#9A8C7D;">Planning, monthly needs</span></div>
                    </div>
                </div>
            </div>

            <!-- 3. HOW IT WORKS -->
            <div style="background:#fff; border-radius:22px; padding:24px; box-shadow:8px 8px 0 #111; border:1.5px solid #111;">
                <h4 style="margin:0; font-family:'Bricolage Grotesque'; font-size:17px; font-weight:800;">How Connect Works ⚡</h4>
                <div style="margin-top:16px; display:flex; flex-direction:column; gap:12px;">
                    <div style="display:flex; gap:10px;">
                        <span style="background:#111; color:#fff; width:26px; height:26px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:800;">1</span>
                        <span style="font-size:13px; line-height:1.4;"><b>Post your need</b> with urgency<br><span style="color:#888; font-size:11px;">Takes 20 seconds</span></span>
                    </div>
                    <div style="display:flex; gap:10px;">
                        <span style="background:#111; color:#fff; width:26px; height:26px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:800;">2</span>
                        <span style="font-size:13px; line-height:1.4;"><b>Volunteers see it</b> nearby<br><span style="color:#888; font-size:11px;">Notified in Vapi / Daman / Silvassa</span></span>
                    </div>
                    <div style="display:flex; gap:10px;">
                        <span style="background:#FF6A2A; color:#111; width:26px; height:26px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:800;">3</span>
                        <span style="font-size:13px; line-height:1.4;"><b>Get direct calls</b><br><span style="color:#888; font-size:11px;">No middleman, 100% direct</span></span>
                    </div>
                </div>
            </div>

            <!-- 4. TRUST -->
            <div style="background:#FF6A2A; border-radius:22px; padding:20px; box-shadow:6px 6px 0 #111; border:1.5px solid #111; text-align:center;">
                <div style="font-size:26px;">🤝</div>
                <b style="font-family:'Bricolage Grotesque'; font-size:15px;">200+ families trust us</b>
                <p style="font-size:11px; margin:6px 0 0; line-height:1.4;">Verified NGOs, no spam, no fees. Your number stays private until you accept help.</p>
            </div>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>