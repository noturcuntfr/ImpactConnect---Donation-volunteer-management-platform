<?php
include 'config.php';
include 'includes/header.php';

if(isset($_POST['donate'])){
    $donor = $conn->real_escape_string(
        $_POST['donor_name']
    );
    $phone = $conn->real_escape_string(
        $_POST['phone']
    );
    $item = $conn->real_escape_string(
        $_POST['item']
    );
    $qty = (int)$_POST['qty'];
    $addr = $conn->real_escape_string(
        $_POST['address']
    );
    $amount = isset($_POST['amount']) 
        ? (int)$_POST['amount'] : 0;
    
    // FIX: If Money, address can be empty
    if(strpos($item, 'Money') !== false){
        if(empty($addr)){
            $addr = "Online - UPI Payment (No pickup needed)";
        }
        if($amount > 0){
            $purpose = $conn->real_escape_string(
                $_POST['purpose'] ?? 'Where most needed'
            );
            $item = "Money Donation - Rs $amount - $purpose";
        }
    }

    if(empty($addr) && strpos($item, 'Money') === false){
        echo "<script>
            alert('Pickup address is required for goods donation');
            history.back();
        </script>";
        exit;
    }
    
    $conn->query(
        "INSERT INTO donations 
        (donor_name, phone, item_name, qty, pickup_address, status) 
        VALUES ('$donor', '$phone', '$item', $qty, '$addr', 'pending')"
    );

    echo "<script>
        alert('Locked in! Volunteer will call you on $phone in 24H ❤️');
        window.location='donation.php';
    </script>";
}
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@700;800;900&family=Inter:wght@400;500&display=swap');

*{ box-sizing:border-box; }
body{ margin:0 !important; background:#0E0E0E !important; }
header, nav{ margin-bottom:0 !important; }
.full{ width:100vw; margin-left:calc(-50vw + 50%); }

.hero-cool{
    background:#0E0E0E;
    min-height:88vh;
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
    background:#00C950;
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
.left h1 .line3{ color:#FF6A2A; }
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
    transition:0.4s ease;
    width:fit-content;
}
.tilted-card:hover{
    transform:rotate(0deg) scale(1.03);
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
    line-height:1.1;
}
.caption i{
    color:#FF6A2A;
    font-family:Georgia;
    font-style:italic;
    font-weight:400;
    font-size:13px;
    display:block;
    margin-top:2px;
}
.wrap{
    background:#F5F1EB;
    padding:50px 6%;
    display:grid;
    grid-template-columns:1.2fr 0.8fr;
    gap:24px;
    position:relative;
    z-index:10;
    border-radius:32px 32px 0 0;
    margin-top:-20px;
}
@media(max-width:950px){
    .wrap{ grid-template-columns:1fr; }
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
.input:focus{
    background:#FFFBF2;
    border-color:#FF6A2A;
}
.trigger{
    width:100%;
    padding:16px 18px;
    border:2px solid #111;
    border-radius:14px;
    background:#111;
    color:#fff;
    display:flex;
    justify-content:space-between;
    cursor:pointer;
    font-weight:800;
    margin:9px 0;
    box-shadow:4px 4px 0 #111;
}
.trigger.active{
    background:#FF6A2A;
    border-color:#111;
    color:#fff;
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
#overlay{
    position:fixed;
    inset:0;
    background:#0E0E0E;
    z-index:9999;
    display:none;
    padding:40px 6%;
    overflow:auto;
}
#overlay.show{ display:block; }
.cat{
    background:#fff;
    border:2.5px solid #111;
    border-radius:20px;
    overflow:hidden;
    display:flex;
    box-shadow:6px 6px 0 #111;
    cursor:pointer;
    transition:0.2s
}
.cat:hover{ transform:translateY(-3px); }
.cat img{
    width:46%;
    height:180px;
    object-fit:cover;
    border-right:2.5px solid #111;
}
#moneyBox{
    display:none;
    background:#FFF7ED;
    border:2px dashed #FF6A2A;
    border-radius:18px;
    padding:18px;
    margin:12px 0;
    text-align:center
}
#moneyBox.show{ display:block }
#moneyBox img{
    width:190px;
    height:190px;
    background:#fff;
    padding:8px;
    border-radius:12px;
    margin:10px auto;
    display:block;
    border:2px solid #111
}
.amt-chips{
    display:flex;
    gap:8px;
    margin:10px 0;
    flex-wrap:wrap
}
.chip{
    padding:8px 14px;
    border:2px solid #111;
    border-radius:100px;
    font-weight:800;
    font-size:13px;
    cursor:pointer;
    background:#fff
}
.chip.active{ background:#111; color:#fff }
.wa-btn{
    background:#25D366;
    color:#fff;
    border:2px solid #111;
    padding:14px;
    border-radius:14px;
    font-weight:900;
    width:100%;
    margin-top:12px;
    cursor:pointer;
    box-shadow:4px 4px 0 #111
}
.upi-btns{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px;
    margin-top:10px
}
.upi-btn{
    padding:12px;
    border-radius:12px;
    border:2px solid #111;
    background:#fff;
    font-weight:800;
    cursor:pointer
}
.addrHint{
    display:none;
    font-size:11px;
    font-weight:700;
    color:#059669;
    background:#ECFDF5;
    border:1px dashed #059669;
    padding:8px 12px;
    border-radius:100px;
    margin-top:6px;
}
.addrHint.show{ display:inline-block; }
</style>

<div class="full hero-cool">
    <div class="left">
        <div class="badge">
            <span class="dot"></span> 
            LIVE - 3 volunteers in Vapi now
        </div>
        <h1>
            <span>Your old</span><br>
            <span class="line2">books</span><br>
            <span class="line3">can be someone's<br>new future.</span>
        </h1>
        <p>
            We don't take money. Just clothes, books, 
            food you don't use. Free pickup from Vapi, 
            Daman, Silvassa → verified NGOs with photo proof.
        </p>
        <div style="display:flex; gap:20px; margin-top:32px; border-top:1px solid #222; padding-top:20px; width:fit-content;">
            <div><b style="color:#fff; font-size:22px;">12+</b><br><span style="font-size:10px; color:#666;">DONATIONS</span></div>
            <div><b style="color:#fff; font-size:22px;">24H</b><br><span style="font-size:10px; color:#666;">FREE PICKUP</span></div>
            <div><b style="color:#fff; font-size:22px;">100%</b><br><span style="font-size:10px; color:#666;">PRIVATE</span></div>
        </div>
    </div>
    <div class="right">
        <div class="tilted-card">
            <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=900" alt="kids">
            <div class="caption">SMALL ACTS BIG IMPACT <i>Together, we make difference</i></div>
        </div>
    </div>
</div>

<div class="full wrap">
    <div class="card">
        <h2 style="margin:0; font-family:'Bricolage Grotesque'; font-size:26px; font-weight:900;">
            Donate in 30 seconds. ⚡
        </h2>
        <p style="color:#888; font-size:13px; margin:10px 0 18px; font-family:'Inter';">
            Phone is required for volunteer to contact you
        </p>

        <form method="POST" id="donateForm">
            <input class="input" type="text" name="donor_name" id="dName" placeholder="Your Full Name" required>
            
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <input class="input" type="tel" name="phone" id="dPhone" placeholder="Phone - 9876543210" pattern="[0-9]{10}" maxlength="10" required>
                <input class="input" type="number" name="qty" placeholder="Qty e.g. 10" value="0" required>
            </div>

            <div class="trigger" id="trig" onclick="openO()">
                <span id="tTxt">Select what you want to donate</span>
                <span>↗</span>
            </div>
            <input type="hidden" name="item" id="realItem" required>
            
            <div id="moneyFields" style="display:none">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                    <input class="input" type="number" name="amount" id="amount" placeholder="Amount ₹500" oninput="genQR()">
                    <select class="input" name="purpose" id="purpose">
                        <option>Where most needed</option>
                        <option>Food for Kids</option>
                        <option>Education</option>
                        <option>Seva Ashram</option>
                    </select>
                </div>
                <div class="amt-chips">
                    <div class="chip" onclick="setAmt(100)">₹100</div>
                    <div class="chip" onclick="setAmt(500)">₹500</div>
                    <div class="chip" onclick="setAmt(1000)">₹1000</div>
                    <div class="chip" onclick="setAmt(2000)">₹2000</div>
                </div>
            </div>

            <div>
                <textarea class="input" name="address" id="dAddr" placeholder="Pickup address - Chala, Vapi" rows="3" required></textarea>
                <span id="addrHint" class="addrHint">✓ Address is NOT mandatory for Money donation 💸 - UPI is enough</span>
            </div>

            <div id="moneyBox">
                <b style="font-family:'Bricolage Grotesque'; font-size:16px;">Scan & Pay via GPay / PhonePe</b>
                <p style="font-size:11px; color:#666; margin-top:4px">UPI: impactconnect@okicici • 80G Receipt on WhatsApp</p>
                <img id="qrImg" src="">
                <p style="font-size:13px; font-weight:800; margin-top:8px">Amount: ₹<span id="qrAmt">0</span></p>
                <div class="upi-btns">
                    <button type="button" class="upi-btn" onclick="openUPI('gpay')">GPay Pay</button>
                    <button type="button" class="upi-btn" onclick="openUPI('phonepe')">PhonePe Pay</button>
                </div>
                <button type="button" class="wa-btn" onclick="sendWAReceipt()">📲 After Payment - Get Receipt on WhatsApp</button>
            </div>

            <div style="font-size:11px; color:#666; margin:4px 2px 8px; font-family:'Inter';">
                🔒 Phone is private - only assigned volunteer can see it
            </div>
            <button class="btn" name="donate" id="mainBtn" type="submit">Donate Now → Get Pickup in 24H</button>
        </form>
    </div>

    <div>
        <div class="card" style="background:#111; color:#fff; border-radius:22px; padding:24px; box-shadow:8px 8px 0px #FF6A2A;">
            <h4 style="margin:0; font-family:'Bricolage Grotesque'; font-size:18px; font-weight:800;">Why 200+ families trust us? 🤝</h4>
            <div style="margin-top:16px; display:flex; flex-direction:column; gap:14px;">
                <div style="display:flex; gap:12px;">
                    <span style="background:#fff; color:#111; width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px;">📸</span>
                    <div><b style="font-size:13px;">Photo Proof After Delivery</b><br><span style="font-size:11px; color:#9A8C7D;">Real photo when NGO receives it.</span></div>
                </div>
                <div style="display:flex; gap:12px;">
                    <span style="background:#fff; color:#111; width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px;">📞</span>
                    <div><b style="font-size:13px;">Direct Call For Pickup</b><br><span style="font-size:11px; color:#9A8C7D;">Volunteer calls on your phone in 24H.</span></div>
                </div>
                <div style="display:flex; gap:12px;">
                    <span style="background:#fff; color:#111; width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px;">🔒</span>
                    <div><b style="font-size:13px;">Anonymous & Private</b><br><span style="font-size:11px; color:#9A8C7D;">Shown as Anonymous publicly.</span></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="overlay">
    <div style="display:flex; justify-content:space-between; align-items:center; max-width:1100px; margin:0 auto;">
        <h1 style="font-family:'Bricolage Grotesque'; font-size:48px; font-weight:900; color:#fff; margin:0; line-height:0.9;">
            Pick a category<br><span style="color:#FF6A2A;">to donate</span>
        </h1>
        <button onclick="closeO()" style="width:52px; height:52px; border-radius:50%; border:2px solid #fff; background:#fff; color:#000; font-size:22px; cursor:pointer; font-weight:900;">✕</button>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-top:30px; max-width:1100px; margin:30px auto 0;">
        <div class="cat" onclick="pick('Books & Stationery')">
            <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?q=80&w=600">
            <div style="padding:16px;">
                <b>Books</b><br>
                <span style="font-size:11px; color:#666;">12 NGOs need</span><br>
                <button style="margin-top:12px; background:#111; color:#fff; border:0; padding:8px 16px; border-radius:100px; font-weight:800; cursor:pointer;">Select →</button>
            </div>
        </div>
        <div class="cat" onclick="pick('Clothes & Footwear - Washed')">
            <img src="https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?q=80&w=600">
            <div style="padding:16px;">
                <b>Clothes</b><br>
                <span style="font-size:11px; color:#666;">28 NGOs need</span><br>
                <button style="margin-top:12px; background:#FF6A2A; color:#fff; border:0; padding:8px 16px; border-radius:100px; font-weight:800; cursor:pointer;">Select →</button>
            </div>
        </div>
        <div class="cat" onclick="pick('Food - Min 3 days expiry')">
            <img src="https://images.unsplash.com/photo-1593113598332-cd288d649433?q=80&w=600">
            <div style="padding:16px;">
                <b>Food</b><br>
                <span style="font-size:11px; color:#666;">8 NGOs need</span><br>
                <button style="margin-top:12px; background:#111; color:#fff; border:0; padding:8px 16px; border-radius:100px; font-weight:800; cursor:pointer;">Select →</button>
            </div>
        </div>
        <div class="cat" onclick="pick('Medicines - Not expired')">
            <img src="https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?q=80&w=600">
            <div style="padding:16px;">
                <b>Medicines</b><br>
                <span style="font-size:11px; color:#666;">5 NGOs need</span><br>
                <button style="margin-top:12px; background:#111; color:#fff; border:0; padding:8px 16px; border-radius:100px; font-weight:800; cursor:pointer;">Select →</button>
            </div>
        </div>
        <div class="cat" onclick="pick('Money Donation - UPI QR')" style="background:#FFF7ED; border-color:#FF6A2A">
            <img src="https://images.unsplash.com/photo-1553729459-efe14ef6055d?q=80&w=600">
            <div style="padding:16px;">
                <b>💵 Money</b><br>
                <span style="font-size:11px; color:#666;">GPay / PhonePe QR • 80G Receipt</span><br>
                <button style="margin-top:12px; background:#FF6A2A; color:#fff; border:0; padding:8px 16px; border-radius:100px; font-weight:800; cursor:pointer;">Donate Money →</button>
            </div>
        </div>
    </div>
</div>

<script>
const ADMIN_WA = "9824560082";
let isMoney = false;

function openO(){
    document.getElementById('overlay').classList.add('show');
    document.body.style.overflow='hidden';
}
function closeO(){
    document.getElementById('overlay').classList.remove('show');
    document.body.style.overflow='auto';
}

function pick(v){
    document.getElementById('realItem').value=v;
    document.getElementById('tTxt').innerText=v;
    document.getElementById('trig').classList.add('active');
    closeO();
    
    const addr = document.getElementById('dAddr');
    const hint = document.getElementById('addrHint');
    
    if(v.includes('Money')){
        isMoney = true;
        document.getElementById('moneyFields').style.display='block';
        document.getElementById('moneyBox').classList.add('show');
        document.getElementById('mainBtn').innerText = 'Pay Now → Generate QR';
        document.getElementById('mainBtn').type = 'button';
        document.getElementById('mainBtn').onclick = genQR;

        addr.removeAttribute('required');
        addr.placeholder = 'Optional - Leave blank for Money donation';
        addr.style.borderColor = '#9CA3AF';
        addr.style.background = '#F9FAFB';
        hint.classList.add('show');
    } else {
        isMoney = false;
        document.getElementById('moneyFields').style.display='none';
        document.getElementById('moneyBox').classList.remove('show');
        document.getElementById('mainBtn').innerText = 'Donate Now → Get Pickup in 24H';
        document.getElementById('mainBtn').type = 'submit';
        document.getElementById('mainBtn').onclick = null;

        addr.setAttribute('required','required');
        addr.placeholder = 'Pickup address - Chala, Vapi';
        addr.style.borderColor = '#111';
        addr.style.background = '#fff';
        hint.classList.remove('show');
    }
}

function setAmt(a){
    document.getElementById('amount').value=a;
    document.querySelectorAll('.chip').forEach(c=>c.classList.remove('active'));
    event.target.classList.add('active');
    genQR();
}

function genQR(){
    const amt = document.getElementById('amount').value;
    const name = document.getElementById('dName').value;
    const phone = document.getElementById('dPhone').value;

    if(!name || !phone){
        alert('Please enter Name and Phone first');
        return;
    }
    if(!amt || amt < 1){
        alert('Enter amount e.g. 500');
        return;
    }
    
    document.getElementById('qrAmt').innerText = amt;

    const upi = `upi://pay?pa=impactconnect@okicici&pn=ImpactConnect&am=${amt}&cu=INR&tn=Donation`;
    const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(upi)}`;

    document.getElementById('qrImg').src = qrUrl;
    document.getElementById('moneyBox').classList.add('show');
    document.getElementById('moneyBox').scrollIntoView({behavior:'smooth', block:'center'});
    
    document.getElementById('mainBtn').innerText = 'I Paid - Complete Donation';
    document.getElementById('mainBtn').onclick = goToSuccess;
}

function openUPI(app){
    const amt = document.getElementById('amount').value;
    if(!amt){ alert('Enter amount'); return; }
    const upi = `upi://pay?pa=impactconnect@okicici&pn=ImpactConnect&am=${amt}&cu=INR&tn=Donation`;
    window.location.href = upi;
}

function goToSuccess(){
    const name = document.getElementById('dName').value;
    const phone = document.getElementById('dPhone').value;
    const addr = document.getElementById('dAddr').value || "Online UPI";
    const amt = document.getElementById('amount').value;
    const purpose = document.getElementById('purpose').value;
    const item = document.getElementById('realItem').value;
    
    const params = new URLSearchParams({
        donor_name: name,
        phone: phone,
        address: addr,
        amount: amt,
        purpose: purpose,
        item: item,
        id: 'MONEY-' + amt
    });
    
    fetch(window.location.href, {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `donor_name=${encodeURIComponent(name)}
            &phone=${encodeURIComponent(phone)}
            &item=${encodeURIComponent(item)}
            &qty=1
            &address=${encodeURIComponent(addr)}
            &amount=${amt}
            &purpose=${encodeURIComponent(purpose)}
            &donate=1`
    });
    
    window.location.href = 'donation-success.php?' + params.toString();
}

function sendWAReceipt(){
    const amt = document.getElementById('amount').value;
    const name = document.getElementById('dName').value;
    const phone = document.getElementById('dPhone').value;
    const text = `Money Donation - ImpactConnect\nName: ${name}\nPhone: ${phone}\nAmount: ₹${amt}`;
    window.open(`https://wa.me/91${ADMIN_WA}?text=${encodeURIComponent(text)}`,'_blank');
}
</script>
<?php include 'includes/footer.php';?>