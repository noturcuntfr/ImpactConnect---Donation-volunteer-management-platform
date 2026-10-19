<?php include 'includes/header.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>About Us - ImpactConnect</title>
    <link 
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800;900&family=Instrument+Serif:ital@1&family=Bricolage+Grotesque:wght@700;800&display=swap" 
        rel="stylesheet"
    >
<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box
}
body{
    background:#EDEEF0;
    font-family:'Inter',sans-serif;
    overflow-x:hidden
}

/* HERO */
.top-wrap{
    padding:30px 20px
}
.hero-card{
    max-width:1180px;
    margin:0 auto;
    background:white;
    border-radius:32px;
    padding:60px 50px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:30px;
    box-shadow:0 20px 60px rgba(0,0,0,0.06);
    border:2.5px solid #111;
}
.hero-left{
    flex:1;
    max-width:540px
}
.hero-left h1{
    font-size:52px;
    font-weight:900;
    line-height:0.95;
    letter-spacing:-1.5px;
    color:#111;
    font-family:'Bricolage Grotesque'
}
.hero-left h1 i{
    font-family:'Instrument Serif',serif;
    font-weight:400;
    color:#FF6A2A
}
.hero-left p{
    color:#444;
    font-size:14px;
    line-height:1.7;
    margin-top:18px;
    max-width:460px
}
.hero-btns{
    display:flex;
    gap:12px;
    margin-top:26px
}
.btn-orange{
    background:#FF6A2A;
    color:white;
    border:2px solid #111;
    padding:12px 22px;
    border-radius:100px;
    font-weight:800;
    font-size:13px;
    cursor:pointer;
    box-shadow:3px 3px 0 #111
}
.btn-outline{
    background:white;
    color:black;
    border:2px solid black;
    padding:12px 18px;
    border-radius:100px;
    font-weight:800;
    font-size:13px
}
.hero-stats{
    display:flex;
    gap:28px;
    margin-top:28px;
    border-top:1.5px dashed #ddd;
    padding-top:20px
}
.hero-stats b{
    font-size:20px;
    display:block;
    color:#111;
    font-family:'Bricolage Grotesque'
}
.hero-stats span{
    font-size:9px;
    text-transform:uppercase;
    color:#9ca3af;
    letter-spacing:0.6px;
    font-weight:800
}
.hero-right{
    position:relative;
    width:360px;
    flex-shrink:0
}
.polaroid{
    background:white;
    padding:12px 12px 22px 12px;
    border-radius:20px;
    box-shadow:8px 8px 0 #111;
    transform:rotate(3deg);
    border:2.5px solid #111;
}
.polaroid img{
    width:100%;
    height:340px;
    object-fit:cover;
    border-radius:14px;
    display:block;
    border:1.5px solid #111
}
.polaroid .caption{
    margin-top:14px;
    padding:0 4px
}
.polaroid .caption b{
    font-size:12px;
    letter-spacing:0.3px;
    color:#111;
    text-transform:uppercase;
    font-family:'Bricolage Grotesque'
}
.polaroid .caption i{
    font-family:'Instrument Serif',serif;
    font-size:14px;
    color:#FF6A2A;
    display:block;
    font-style:italic
}

/* STORY */
.story-wrap{
    max-width:1180px;
    margin:24px auto;
    padding:0 20px
}
.story-card{
    background:#111;
    color:#fff;
    border-radius:32px;
    padding:50px;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:40px;
    border:2.5px solid #111;
    box-shadow:8px 8px 0 #FF6A2A
}
.story-card h2{
    font-family:'Bricolage Grotesque';
    font-size:36px;
    line-height:0.95
}
.story-card h2 span{
    color:#FF6A2A
}
.story-card p{
    color:#aaa;
    font-size:13.5px;
    line-height:1.8;
    margin-top:14px
}
.story-steps{
    margin-top:20px;
    display:flex;
    flex-direction:column;
    gap:12px
}
.step{
    display:flex;
    gap:12px;
    background:#1a1a1a;
    border:1.5px solid #2a2a2a;
    border-radius:16px;
    padding:14px 16px
}
.step b{
    width:32px;
    height:32px;
    background:#FF6A2A;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:14px;
    flex-shrink:0;
    color:#fff
}
.step div strong{
    color:#fff;
    font-size:13px;
    display:block
}
.step div small{
    color:#888;
    font-size:11.5px
}

/* LEADERS */
.leaders-section{
    max-width:1180px;
    margin:24px auto;
    padding:0 20px
}
.leaders-title{
    font-family:'Bricolage Grotesque';
    font-size:28px;
    margin-bottom:16px;
    color:#111
}
.leaders-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:18px
}
.leader-card{
    background:white;
    border-radius:24px;
    padding:22px;
    text-align:center;
    border:2.5px solid #111;
    transition:0.2s
}
.leader-card:hover{
    transform:translateY(-4px);
    box-shadow:6px 6px 0 #111
}
.leader-card img{
    width:92px;
    height:92px;
    border-radius:50%;
    object-fit:cover;
    border:3px solid #111;
    margin:0 auto
}
.leader-card .name{
    color:#111;
    font-weight:800;
    margin-top:12px;
    font-size:16px;
    font-family:'Bricolage Grotesque'
}
.leader-card .role{
    color:#FF6A2A;
    font-size:11px;
    margin-top:2px;
    font-weight:800;
    text-transform:uppercase
}
.leader-card .moto{
    font-size:12px;
    color:#6b7280;
    font-style:italic;
    margin:14px 0;
    min-height:30px
}
.connect-btn{
    background:#111;
    color:white;
    border:1.5px solid #111;
    width:100%;
    padding:9px;
    border-radius:100px;
    font-weight:700;
    cursor:pointer;
    font-size:13px
}
.connect-btn:hover{
    background:#FF6A2A
}

/* MODAL */
.modal{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.5);
    justify-content:center;
    align-items:center;
    z-index:999
}
.modal-content{
    background:white;
    padding:24px;
    border-radius:18px;
    width:320px;
    text-align:center;
    position:relative;
    border:2.5px solid #111;
    box-shadow:8px 8px 0 #111
}
.close{
    position:absolute;
    right:14px;
    top:10px;
    cursor:pointer;
    font-size:20px
}

@media(max-width:960px){
    .hero-card{
        flex-direction:column;
        padding:32px 24px
    }
    .hero-left h1{
        font-size:38px
    }
    .hero-right{
        width:100%;
        max-width:340px
    }
    .story-card{
        grid-template-columns:1fr;
        padding:28px
    }
    .leaders-grid{
        grid-template-columns:1fr 1fr
    }
}
@media(max-width:600px){
    .leaders-grid{
        grid-template-columns:1fr
    }
}
</style>
</head>
<body>

<div class="top-wrap">
    <div class="hero-card">
        <div class="hero-left">
            <h1>
                Vapi is Our Home. 
                <i>Gujarat is Our Mission.</i>
            </h1>
            <p>
                <b>No donation should go to waste.</b> 
                We started in a small room in Vapi with one question - 
                why do donations from Vapi never reach the right people?
                <br><br>
                Vapi is Gujarat's industrial heart. 
                Thousands of families have extra clothes, books, food. 
                But NGOs in Vapi, Valsad, Pardi, Udvada told us same thing: 
                <b style="color:#111">
                    "Donors want to help, but pickup is the problem."
                </b>
                <br><br>
                So we built 
                <b style="color:#FF6A2A">
                    ImpactConnect - Vapi as HQ, Gujarat as our family.
                </b> 
                You post in 30 seconds. Our volunteer from your city 
                picks it up in 24 hours with photo proof.
            </p>

            <div class="hero-btns">
                <button 
                    class="btn-orange" 
                    onclick="document.getElementById('story').scrollIntoView({behavior:'smooth'})"
                >
                    Our Story →
                </button>
                <button class="btn-outline">
                    Vapi HQ • Live in Gujarat
                </button>
            </div>

            <div class="hero-stats">
                <div><b>Vapi</b><span>HQ CITY</span></div>
                <div><b>6+</b><span>Cities Gujarat</span></div>
                <div><b>100%</b><span>Photo Proof</span></div>
            </div>
        </div>

        <div class="hero-right">
            <div class="polaroid">
                <img 
                    src="dia.jpg" 
                    onerror="this.src='https://images.unsplash.com/photo-1580489944761-15a19d654956?w=500'"
                >
                <div class="caption">
                    <b>VAPI TO GUJARAT</b>
                    <i>Small acts, big impact</i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="story-wrap" id="story">
    <div class="story-card">
        <div>
            <h2>From Vapi To <span>All Gujarat</span></h2>
            <p>
                We are from Vapi. 
                We started ImpactConnect in 2023 because we saw gap in our own city.
                <br><br>
                In <b style="color:#fff">Vapi, Valsad, Daman, Silvassa</b> - 
                people have a lot to give. But no trusted person to pick it up.
                <br><br>
                <b style="color:#fff">Our Gujarat Model:</b><br>
                <b style="color:#FF6A2A">Vapi is our head office.</b> 
                All volunteers are trained here.
                <br><br>
                <span style="display:flex; flex-wrap:wrap; gap:6px; margin-top:10px">
                    <span style="background:#222; padding:5px 12px; border-radius:20px; border:1px solid #333; font-size:11px; color:#fff">📍 Vapi (HQ)</span>
                    <span style="background:#222; padding:5px 12px; border-radius:20px; border:1px solid #333; font-size:11px; color:#fff">📍 Valsad</span>
                    <span style="background:#222; padding:5px 12px; border-radius:20px; border:1px solid #333; font-size:11px; color:#fff">📍 Daman</span>
                    <span style="background:#222; padding:5px 12px; border-radius:20px; border:1px solid #333; font-size:11px; color:#fff">📍 Silvassa</span>
                    <span style="background:#222; padding:5px 12px; border-radius:20px; border:1px solid #333; font-size:11px; color:#fff">📍 Pardi</span>
                </span>
                <br><br>
                <span style="background:#FF6A2A; color:#fff; padding:6px 14px; border-radius:20px; font-size:11px; font-weight:800; border:1.5px solid #fff">
                    EXPANDING: Surat • Bharuch • Ahmedabad • Vadodara • Rajkot
                </span>
            </p>
        </div>

        <div>
            <div class="story-steps">
                <div class="step">
                    <b>1</b>
                    <div>
                        <strong>Donor Posts from Vapi / Gujarat</strong>
                        <small>Clothes, food, books - with area & landmark</small>
                    </div>
                </div>
                <div class="step">
                    <b>2</b>
                    <div>
                        <strong>Vapi Volunteer Network Picks in 24H</strong>
                        <small>Same day in Vapi-Valsad-Daman, Next day in Surat</small>
                    </div>
                </div>
                <div class="step">
                    <b>3</b>
                    <div>
                        <strong>Verified Gujarat NGOs Receive</strong>
                        <small>Only visited & trusted NGOs - no fake NGO</small>
                    </div>
                </div>
                <div class="step">
                    <b>4</b>
                    <div>
                        <strong>Photo Proof + Impact Certificate</strong>
                        <small>You see your donation reaching - 100% transparent</small>
                    </div>
                </div>
            </div>

            <div style="margin-top:18px; background:#111; border:1.5px dashed #444; padding:14px; border-radius:14px">
                <small style="color:#FF6A2A; font-weight:800; letter-spacing:1px">
                    OUR PROMISE - GUJARAT MODEL
                </small>
                <br>
                <small style="color:#888; font-size:11.5px; line-height:1.6">
                    Vapi-Valsad-Daman-Silvassa-Pardi: Same day pickup.<br>
                    Surat, Navsari, Bharuch, Ahmedabad: Next day via volunteer.<br>
                    Every delivery = Photo proof. No proof = No count.
                </small>
            </div>
        </div>
    </div>
</div>

<div class="leaders-section" id="leaders">
    <div class="leaders-title">Meet The Leaders - Vapi</div>
    <div class="leaders-grid">

        <div class="leader-card">
            <img src="dia.jpg" onerror="this.src='https://i.pravatar.cc/150?img=5'">
            <div class="name">Patel Dia</div>
            <div class="role">Founder - Vapi</div>
            <div class="moto">"Small Acts, Big Impact"</div>
            <button class="connect-btn" onclick="showContact('Patel Dia','diapatel@gmail.com','+91 98765 43210')">Connect →</button>
        </div>

        <div class="leader-card">
            <img src="nancy.jpg" onerror="this.src='https://i.pravatar.cc/150?img=9'">
            <div class="name">Patel Nancy</div>
            <div class="role">Co-Founder - Vapi</div>
            <div class="moto">"Together we build, together we grow"</div>
            <button class="connect-btn" onclick="showContact('Patel Nancy','nancyp1402@gmail.com','+91 98765 43212')">Connect →</button>
        </div>

        <div class="leader-card">
            <img src="naynaben.jpg" onerror="this.src='https://i.pravatar.cc/150?img=26'">
            <div class="name">Nayna Patel</div>
            <div class="role">Lead Investor & Mentor</div>
            <div class="moto">"Investing in humanity is best return"</div>
            <button class="connect-btn" onclick="showContact('Nayna Patel','naynapatel.ceo@impactconnect.org','+91 98765 43211')">Connect →</button>
        </div>

        <div class="leader-card">
            <img src="maheshbhai.jpg" onerror="this.src='https://i.pravatar.cc/150?img=12'">
            <div class="name">Mahesh Patel</div>
            <div class="role">CEO - Gujarat Operations</div>
            <div class="moto">"Leading with empathy, scaling with purpose"</div>
            <button class="connect-btn" onclick="showContact('Mahesh Patel','maheshpatel@impactconnect.org','+91 98765 43213')">Connect →</button>
        </div>

    </div>
</div>

<div id="contactModal" class="modal" onclick="closeModal()">
    <div class="modal-content" onclick="event.stopPropagation()">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3 id="modalName" style="color:#111; margin-bottom:10px; font-family:'Bricolage Grotesque'"></h3>
        <p id="modalEmail" style="background:#f5f5f5; padding:10px; border-radius:8px; font-size:13px; margin-bottom:8px; border:1.5px solid #111"></p>
        <p id="modalPhone" style="background:#f5f5f5; padding:10px; border-radius:8px; font-size:13px; border:1.5px solid #111"></p>
        <a id="mailLink" href="#" style="display:inline-block; background:#FF6A2A; color:white; padding:10px 18px; border-radius:100px; text-decoration:none; margin-top:12px; font-size:13px; font-weight:800; border:2px solid #111">Send Email →</a>
    </div>
</div>

<script>
function showContact(name,email,phone){
    document.getElementById('modalName').innerText = name;
    document.getElementById('modalEmail').innerText = '📧 ' + email;
    document.getElementById('modalPhone').innerText = '📞 ' + phone;
    document.getElementById('mailLink').href = 'mailto:' + email;
    document.getElementById('contactModal').style.display = 'flex';
}
function closeModal(){
    document.getElementById('contactModal').style.display = 'none';
}
</script>

</body>
</html>
<?php include 'includes/footer.php'; ?>