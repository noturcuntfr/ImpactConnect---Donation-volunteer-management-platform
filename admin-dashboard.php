<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role']!='admin'){
    header("Location: login.php");
    exit;
}
include 'config.php';
include 'includes/header.php';

// safe column checker
function hasColumn($conn, $table, $col){
    $res = $conn->query(
        "SHOW COLUMNS FROM `$table` LIKE '$col'"
    );
    return $res && $res->num_rows > 0;
}

$totalUsers = $conn->query(
    "SELECT COUNT(*) as c FROM users"
)->fetch_assoc()['c'];

$totalDonations = $conn->query(
    "SELECT COUNT(*) as c FROM donations"
)->fetch_assoc()['c'];

$pendingPickup = $conn->query(
    "SELECT COUNT(*) as c FROM donations 
     WHERE status!='delivered'"
)->fetch_assoc()['c'];

$donors = $conn->query(
    "SELECT COUNT(*) as c FROM users 
     WHERE LOWER(role)='donor'"
)->fetch_assoc()['c'];

$vols = $conn->query(
    "SELECT COUNT(*) as c FROM users 
     WHERE LOWER(role)='volunteer'"
)->fetch_assoc()['c'];

$ngos = $conn->query(
    "SELECT COUNT(*) as c FROM users 
     WHERE LOWER(role)='ngo'"
)->fetch_assoc()['c'];

$impact = 0;
if(hasColumn($conn,'donations','item_name')){
    $q = $conn->query(
        "SELECT SUM(
            CAST(SUBSTRING_INDEX(item_name,'Rs',-1) AS UNSIGNED)
        ) as s 
        FROM donations 
        WHERE LOWER(item_name) LIKE '%money%'"
    );
    if($q){
        $impact = $q->fetch_assoc()['s'];
    }
}
if(!$impact){
    $impact = 107000;
}

// check what exists in donations
$donHasPhone = hasColumn($conn,'donations','phone');
$donHasEmail = hasColumn($conn,'donations','email');
$donHasDonorName = hasColumn($conn,'donations','donor_name');

function getUserPhone($conn, $userRow){
    global $donHasPhone, $donHasDonorName;

    $p = $userRow['phone'] 
        ?? $userRow['mobile'] 
        ?? $userRow['phone_no'] 
        ?? $userRow['contact'] 
        ?? '';

    if(!empty($p)){
        return $p;
    }

    if(!$donHasPhone || !$donHasDonorName){
        return '';
    }

    $name = $conn->real_escape_string(
        $userRow['name'] ?? $userRow['username'] ?? ''
    );

    if($name!=''){
        $q = $conn->query(
            "SELECT phone FROM donations 
             WHERE donor_name LIKE '%$name%' 
             AND phone!='' 
             ORDER BY id DESC LIMIT 1"
        );
        if($q && $q->num_rows>0){
            return $q->fetch_assoc()['phone'];
        }
    }
    return '';
}
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@700;800&display=swap');

body{
    background:#F5F1EB!important
}
.wrap{
    max-width:1180px;
    margin:0 auto;
    padding:26px 4%
}
.top{
    background:#111;
    border:3px solid #111;
    border-radius:22px;
    padding:26px 28px;
    color:#fff;
    box-shadow:8px 8px 0 #FF6A2A;
}
.top h1{
    font-family:'Bricolage Grotesque';
    font-size:28px;
    margin:0
}
.top p{
    color:#9CA3AF;
    font-size:12px;
    margin:6px 0 0
}
.stats{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:14px;
    margin:18px 0;
}
.stat{
    border:2.5px solid #111;
    border-radius:18px;
    padding:16px 18px;
    background:#fff;
    box-shadow:5px 5px 0 #111;
}
.stat b{
    font-size:24px;
    font-family:'Bricolage Grotesque';
    display:block
}
.stat span{
    font-size:10px;
    font-weight:800;
    color:#6B7280;
    text-transform:uppercase
}
.stat.dark{
    background:#111;
    color:#fff;
}
.stat.dark span{
    color:#9CA3AF;
}
.stat.yellow{
    background:#FEF3C7;
}
.stat.green{
    background:#D1FAE5;
}
.grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:16px;
}
.card{
    background:#fff;
    border:2.5px solid #111;
    border-radius:20px;
    box-shadow:6px 6px 0 #111;
    overflow:hidden;
}
.card-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:14px 18px;
    background:#FFFBF5;
    border-bottom:2px solid #111;
}
.card-head b{
    font-family:'Bricolage Grotesque';
    font-size:14px;
}
.btnM{
    padding:8px 14px;
    border-radius:100px;
    border:2px solid #111;
    font-weight:800;
    font-size:11px;
    cursor:pointer;
}
.btnM.black{
    background:#111;
    color:#fff;
}
.btnM.orange{
    background:#FF6A2A;
    color:#fff;
}
table{
    width:100%;
    border-collapse:collapse
}
th{
    background:#111;
    color:#fff;
    font-size:10px;
    text-transform:uppercase;
    padding:10px 14px;
    text-align:left
}
td{
    padding:11px 14px;
    font-size:12px;
    border-top:1.5px solid #eee
}
.role{
    background:#E5E7EB;
    padding:3px 9px;
    border-radius:100px;
    font-size:10px;
    font-weight:800;
}
.badge{
    padding:4px 10px;
    border-radius:100px;
    font-size:10px;
    font-weight:800;
}
.b-pending{ background:#FEF3C7; }
.b-delivered{ background:#D1FAE5; }
.b-picked{ background:#DBEAFE; }
.connect{
    display:flex;
    gap:5px;
    flex-wrap:wrap;
    margin-top:4px;
}
.cbtn{
    padding:4px 9px;
    border-radius:100px;
    font-size:10px;
    font-weight:800;
    text-decoration:none;
    border:1.5px solid #111;
    display:inline-flex;
}
.c-call{ background:#111; color:#fff; }
.c-wa{ background:#25D366; color:#fff; border-color:#25D366; }
.c-mail{ background:#fff; color:#111; }

#userModal, #donModal{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.7);
    backdrop-filter:blur(5px);
    z-index:9999;
    align-items:center;
    justify-content:center;
    padding:20px;
}
#userModal.show, #donModal.show{
    display:flex;
}
.modal{
    background:#fff;
    border:3px solid #111;
    border-radius:22px;
    box-shadow:10px 10px 0 #111;
    width:100%;
    max-width:980px;
    max-height:85vh;
    overflow:hidden;
    display:flex;
    flex-direction:column;
}
.modal-head{
    padding:16px 20px;
    border-bottom:2px solid #111;
    display:flex;
    justify-content:space-between;
    align-items:center;
    background:#FFFBF5;
}
.modal-body{ overflow:auto; }
.close{
    width:32px;
    height:32px;
    border-radius:100px;
    border:2px solid #111;
    background:#fff;
    font-weight:900;
    cursor:pointer;
}
</style>

<div class="wrap">
    <div class="top">
        <h1>Admin Dashboard 🛡️ - ImpactConnect</h1>
        <p>Total Users: <?= $totalUsers ?> • Vapi, Daman, Silvassa region</p>
    </div>

    <div class="stats">
        <div class="stat">
            <b><?= $totalUsers ?></b>
            <span>DONORS: <?= $donors ?> • VOLS: <?= $vols ?> • NGOS: <?= $ngos ?></span>
        </div>
        <div class="stat dark">
            <b><?= $totalDonations ?></b>
            <span>TOTAL DONATIONS</span>
        </div>
        <div class="stat yellow">
            <b><?= $pendingPickup ?></b>
            <span>PENDING PICKUP</span>
        </div>
        <div class="stat green">
            <b>₹<?= $impact ?></b>
            <span>IMPACT VALUE</span>
        </div>
    </div>

    <div class="grid">
        <!-- Recent Users -->
        <div class="card">
            <div class="card-head">
                <b>Recent Users</b>
                <button class="btnM black" onclick="openM('userModal')">
                    Manage All
                </button>
            </div>
            <table>
                <tr><th>NAME</th><th>ROLE</th><th>CONNECT</th></tr>
                <?php
                $r=$conn->query("SELECT * FROM users ORDER BY id DESC LIMIT 6");
                while($row=$r->fetch_assoc()){
                    $name=$row['name']??$row['username']??'User';
                    $role=$row['role']??'donor';
                    $email=$row['email']??'';
                    $phone=getUserPhone($conn,$row);

                    echo "<tr>
                        <td style='font-weight:700'>
                            $name<br>
                            <span style='font-size:10px;color:#888'>$email</span>
                        </td>
                        <td><span class='role'>$role</span></td>
                        <td><div class='connect'>";

                    if($phone){
                        echo "<a class='cbtn c-call' href='tel:$phone'>📞 $phone</a>
                              <a class='cbtn c-wa' href='https://wa.me/91$phone' target='_blank'>WA</a>";
                    }
                    if($email){
                        echo "<a class='cbtn c-mail' href='mailto:$email'>✉️ Email</a>";
                    }
                    if(!$phone && !$email){
                        echo "<span style='font-size:11px;color:#999'>No contact saved</span>";
                    }
                    echo "</div></td></tr>";
                }
                ?>
            </table>
        </div>

        <!-- Recent Donations -->
        <div class="card">
            <div class="card-head">
                <b>Recent Donations</b>
                <button class="btnM orange" onclick="openM('donModal')">
                    View All
                </button>
            </div>
            <table>
                <tr><th>Donor / Contact</th><th>Item</th><th>Status</th></tr>
                <?php
                $r=$conn->query("SELECT * FROM donations ORDER BY id DESC LIMIT 6");
                while($row=$r->fetch_assoc()){
                    $st=strtolower($row['status']);
                    $cls=$st=='pending'?'b-pending':($st=='delivered'?'b-delivered':'b-picked');
                    $dphone = $donHasPhone ? ($row['phone']??'') : '';
                    $demail = $donHasEmail ? ($row['email']??'') : '';

                    echo "<tr>
                        <td><b>{$row['donor_name']}</b><br>
                            <span style='font-size:10px'>$dphone $demail</span>
                        </td>
                        <td style='font-size:11px'>".substr($row['item_name'],0,28)."</td>
                        <td><span class='badge $cls'>$st</span></td>
                    </tr>";
                }
                ?>
            </table>
        </div>
    </div>
</div>

<!-- USER MODAL -->
<div id="userModal" onclick="if(event.target.id=='userModal')closeM('userModal')">
    <div class="modal">
        <div class="modal-head">
            <h3 style="margin:0;font-family:'Bricolage Grotesque'">Manage All Users 👥</h3>
            <button class="close" onclick="closeM('userModal')">✕</button>
        </div>
        <div class="modal-body">
            <table>
                <tr><th>ID</th><th>Name</th><th>Role</th><th>Contact Info</th><th>Connect</th><th>Action</th></tr>
                <?php
                $r=$conn->query("SELECT * FROM users ORDER BY id DESC");
                while($row=$r->fetch_assoc()){
                    $id=$row['id'];
                    $name=$row['name']??$row['username']??'User';
                    $role=$row['role'];
                    $email=$row['email']??'';
                    $phone=getUserPhone($conn,$row);

                    $contactInfo = ($phone ? "📞 $phone<br>" : "") . ($email ? "✉️ $email" : "No contact");
                    $btns = "";
                    if($phone){
                        $btns .= "<a class='cbtn c-call' href='tel:$phone'>Call</a> 
                                  <a class='cbtn c-wa' href='https://wa.me/91$phone' target='_blank'>WA</a> ";
                    }
                    if($email){
                        $btns .= "<a class='cbtn c-mail' href='mailto:$email'>Mail</a>";
                    }

                    echo "<tr>
                        <td>$id</td>
                        <td><b>$name</b></td>
                        <td><span class='role'>$role</span></td>
                        <td style='font-size:11px'>$contactInfo</td>
                        <td><div class='connect'>$btns</div></td>
                        <td><a href='?delete_user=$id' onclick=\"return confirm('Delete?')\" style='color:red;font-size:10px;font-weight:800'>Delete</a></td>
                    </tr>";
                }
                ?>
            </table>
        </div>
    </div>
</div>

<!-- DONATION MODAL -->
<div id="donModal" onclick="if(event.target.id=='donModal')closeM('donModal')">
    <div class="modal">
        <div class="modal-head">
            <h3 style="margin:0;font-family:'Bricolage Grotesque'">All Donations 📦</h3>
            <button class="close" onclick="closeM('donModal')">✕</button>
        </div>
        <div class="modal-body">
            <table>
                <tr><th>ID</th><th>Donor</th><th>Contact</th><th>Item</th><th>Status</th></tr>
                <?php
                $r=$conn->query("SELECT * FROM donations ORDER BY id DESC");
                while($row=$r->fetch_assoc()){
                    $st=strtolower($row['status']);
                    $cls=$st=='pending'?'b-pending':($st=='delivered'?'b-delivered':'b-picked');
                    $dphone = $donHasPhone ? ($row['phone']??'') : '';
                    $demail = $donHasEmail ? ($row['email']??'') : '';

                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td><b>{$row['donor_name']}</b></td>
                        <td style='font-size:11px'>
                            📞 $dphone<br>
                            ✉️ $demail<br>";
                    if($dphone){
                        echo "<a href='tel:$dphone' class='cbtn c-call'>Call</a> 
                              <a href='https://wa.me/91$dphone' target='_blank' class='cbtn c-wa'>WA</a>";
                    }
                    echo "</td>
                        <td>{$row['item_name']}</td>
                        <td><span class='badge $cls'>$st</span></td>
                    </tr>";
                }
                ?>
            </table>
        </div>
    </div>
</div>

<script>
function openM(id){
    document.getElementById(id).classList.add('show');
    document.body.style.overflow='hidden';
}
function closeM(id){
    document.getElementById(id).classList.remove('show');
    document.body.style.overflow='';
}
</script>

<?php
if(isset($_GET['delete_user'])){
    $uid=(int)$_GET['delete_user'];
    $conn->query("DELETE FROM users WHERE id=$uid");
    echo "<script>window.location='admin-dashboard.php'</script>";
}
include 'includes/footer.php';
?>