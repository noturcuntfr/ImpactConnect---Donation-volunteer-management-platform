<?php
$name = $_GET['donor_name'] 
    ?? $_GET['name'] 
    ?? 'Donor';

$phone = $_GET['phone'] ?? '';
$amt = $_GET['amount'] ?? '';
$purpose = $_GET['purpose'] ?? '';
$addr = $_GET['address'] ?? '';
$item = $_GET['item'] ?? '';
$id = $_GET['id'] ?? 'IC-'.rand(1000,9999);

$isMoney = strpos($item, 'Money') !== false 
    || !empty($amt);
?>
<style>
.card{
    max-width:600px;
    margin:40px auto;
    background:#fff;
    border:2px solid #111;
    border-radius:24px;
    padding:32px;
    box-shadow:8px 8px 0 #111;
    text-align:center;
    font-family:Inter
}
.wa-btn-big{
    background:#25D366;
    color:#fff;
    border:2px solid #111;
    padding:16px;
    border-radius:14px;
    font-weight:900;
    width:100%;
    cursor:pointer;
    box-shadow:4px 4px 0 #111;
    margin-top:16px
}
</style>

<div class="card">
    <div style="
        width:60px; 
        height:60px; 
        background:#00C950; 
        border-radius:50%; 
        display:flex; 
        align-items:center; 
        justify-content:center; 
        color:#fff; 
        font-size:28px; 
        margin:0 auto
    ">✓</div>

    <h1 style="font-family:'Bricolage Grotesque'; margin:16px 0 8px">
        <?php echo $isMoney 
            ? "Payment Requested!" 
            : "Donation Locked!"; ?>
    </h1>

    <p style="color:#666; font-size:14px">
        ID: <?php echo $id; ?> | 
        <?php echo $isMoney 
            ? "₹$amt for $purpose" 
            : "$item"; ?>
    </p>
    
    <div style="
        background:#F5F1EB; 
        border-radius:14px; 
        padding:16px; 
        text-align:left; 
        margin-top:20px; 
        font-size:13px
    ">
        <b>Donor:</b> 
        <?php echo htmlspecialchars($name); ?><br>
        <b>Phone:</b> 
        <?php echo htmlspecialchars($phone); ?><br>
        <b><?php echo $isMoney 
            ? "Amount:" 
            : "Address:"; ?></b> 
        <?php echo $isMoney 
            ? "₹$amt" 
            : htmlspecialchars($addr); ?>
    </div>

    <?php if($isMoney): ?>
        <p style="font-size:13px; margin-top:16px; color:#666">
            1. You paid via UPI QR<br>
            2. Click below to send screenshot on WhatsApp 
            to get 80G receipt
        </p>
        <button class="wa-btn-big" onclick="sendWA()">
            📲 Send Payment Details on WhatsApp for 80G Receipt
        </button>
    <?php else: ?>
        <p style="font-size:13px; margin-top:16px; color:#666">
            Volunteer will call you on 
            <?php echo $phone; ?> in 24H
        </p>
        <button class="wa-btn-big" onclick="sendWA()">
            📲 Share Donation Details on WhatsApp
        </button>
    <?php endif; ?>

    <button 
        onclick="window.location='donation.php'" 
        style="
            margin-top:12px; 
            background:#fff; 
            border:2px solid #111; 
            padding:12px; 
            border-radius:12px; 
            width:100%; 
            font-weight:800; 
            cursor:pointer
        "
    >
        Donate More
    </button>
</div>

<script>
const ADMIN_WA = "8160456336";

function sendWA(){
    const name = "<?php echo addslashes($name); ?>";
    const phone = "<?php echo addslashes($phone); ?>";
    const amt = "<?php echo addslashes($amt); ?>";
    const purpose = "<?php echo addslashes($purpose); ?>";
    const addr = "<?php echo addslashes($addr); ?>";
    const item = "<?php echo addslashes($item); ?>";
    const id = "<?php echo addslashes($id); ?>";
    
    let text = "";
    if("<?php echo $isMoney ? '1' : '0'; ?>" === "1"){
        text = `Money Donation - ImpactConnect 
(ID: ${id})

Name: ${name}
Phone: ${phone}
Amount: Rs ${amt}
Purpose: ${purpose}
Address: ${addr}

I paid via UPI QR. 
Please send 80G receipt.`;
    } else {
        text = `New Donation - ImpactConnect 
(ID: ${id})

Name: ${name}
Phone: ${phone}
Item: ${item}
Address: ${addr}

Please arrange pickup.`;
    }
    
    const url = `https://wa.me/${ADMIN_WA}?text=${encodeURIComponent(text)}`;
    window.open(url, '_blank');
}
</script>