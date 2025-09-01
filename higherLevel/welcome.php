<?php
session_start();
include 'config.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = mysqli_real_escape_string($conn, $_SESSION['student_id']);
$sql = "SELECT * FROM users WHERE student_id = '$student_id'";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);

$isPass = ($data['result_status'] === "ผ่าน");
$result_text = $isPass ? "ผ่าน" : "ไม่ผ่าน";
$encourage = $isPass ? "ยินดีด้วย! 🎉" : "ไม่เป็นไรนะ! พยายามใหม่ครั้งหน้า 💪";

$already_member = false;
$showModal = false;
if ($isPass) {
    $chk = mysqli_query($conn, "SELECT * FROM member WHERE student_id='$student_id'");
    if(mysqli_num_rows($chk) > 0){
        $already_member = true;
    }
}

if(isset($_POST['join_club']) && $isPass && !$already_member){
    $fullname = mysqli_real_escape_string($conn, $data['fullname']);
    $nickname = mysqli_real_escape_string($conn, $data['nickname']);
    $year_level = (int)$data['year_level'];
    $faculty = mysqli_real_escape_string($conn, $data['faculty']);
    $activity_date = mysqli_real_escape_string($conn, $data['activity_date']);
    $score = (int)$data['score'];
    $result_status = mysqli_real_escape_string($conn, $data['result_status']);
    $comment = mysqli_real_escape_string($conn, $data['comment']);

    $insert_sql = "INSERT INTO member 
    (student_id, fullname, nickname, year_level, faculty, activity_date, score, result_status, comment, joined_at)
    VALUES ('$student_id','$fullname','$nickname','$year_level','$faculty','$activity_date','$score','$result_status','$comment', NOW())";

    mysqli_query($conn, $insert_sql);
    $already_member = true;
    $showModal = true;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ผลการออดิชั่น</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600;700&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Kanit', sans-serif;
    margin:0; padding:0;
    min-height:100vh;
    background: url('your-background.jpg') no-repeat center center fixed;
    background-size: cover;
    display:flex; justify-content:center; align-items:center;
}

.main-card {
    background: rgba(0,0,0,0.7);
    border-radius: 25px;
    max-width:700px;
    width:95%;
    padding:40px 30px;
    text-align:center;
    color:#fff;
    position:relative;
    overflow:hidden;
    box-shadow: 0 15px 40px rgba(0,0,0,0.6);
}

.result-banner {
    font-size:2rem;
    font-weight:700;
    padding:25px 20px;
    border-radius:20px;
    margin-bottom:30px;
    display:flex;
    justify-content:center;
    align-items:center;
    gap:20px;
    animation: bounceIn 1s ease;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}
.result-banner.pass { background: linear-gradient(90deg,#00c853,#b2ff59); }
.result-banner.fail { background: linear-gradient(90deg,#d50000,#ff5252); }
.result-banner i { font-size:3.5rem; animation: pulse 1.2s infinite; }

@keyframes bounceIn { 0% { transform: scale(0.5); opacity:0; } 60% { transform: scale(1.2); opacity:1; } 100% { transform: scale(1); } }
@keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.4); } 100% { transform: scale(1); } }

.info-group { text-align:left; margin:15px 0; padding:10px 15px; border-radius:12px; background: rgba(255,255,255,0.05);}
.info-label { font-weight:600; color:#b0bec5; width:180px; display:inline-block; }
.info-value { font-size:1.1rem; color:#eceff1; }

.encourage { font-size:1.5rem; font-weight:700; margin:25px 0; color:#ffd600; text-shadow: 1px 1px 5px #000; }

button.btn-success {
    background: linear-gradient(90deg,#00c853,#b2ff59);
    border:none;
    font-weight:600;
    font-size:1.1rem;
    padding:12px 25px;
    border-radius:15px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
button.btn-success:hover { transform: translateY(-3px); box-shadow: 0 5px 20px rgba(0,0,0,0.5); }

canvas#confetti { position:absolute; top:0; left:0; width:100%; height:100%; pointer-events:none; z-index:0; }

.modal-backdrop.show { opacity:0.8; background:#000; }
.modal-content { background: rgba(30,30,30,0.9); color:#fff; border-radius:20px; text-align:center; padding:30px 20px; }

.member-notice { font-size:1.3rem; font-weight:600; color:#00ffcc; margin:20px 0; text-shadow: 1px 1px 5px #000; }

.welcome-text {
    font-family: 'Kanit', sans-serif;
    font-size: 1.5rem;
    font-weight: 700;
    text-align: center;
    color: #ffd600;
    text-shadow: 2px 2px 12px rgba(0,0,0,0.7);
    margin: 20px 0;
    opacity: 0;
    animation: fadeIn 1.5s forwards;
}
@keyframes fadeIn { to { opacity: 1; } }

@media (max-width:576px){
    .result-banner { font-size:1.5rem; padding:20px; }
    .result-banner i { font-size:2.5rem; }
    .info-value { font-size:1rem; }
    .encourage { font-size:1.2rem; }
    .welcome-text { font-size:1.2rem; }
}
</style>
</head>
<body>

<canvas id="confetti"></canvas>

<div class="main-card">
    <div class="result-banner <?php echo $isPass?'pass':'fail'; ?>">
        <i class="bi <?php echo $isPass?'bi-check-circle-fill':'bi-x-circle-fill'; ?>"></i>
        ผลการออดิชั่น: <?php echo $result_text; ?>
    </div>

    <div class="encourage"><?php echo $encourage; ?></div>

    <div class="info-group"><span class="info-label">ชื่อ:</span> <span class="info-value"><?php echo htmlspecialchars($data['fullname']); ?></span></div>
    <div class="info-group"><span class="info-label">ชื่อเล่น:</span> <span class="info-value"><?php echo htmlspecialchars($data['nickname']); ?></span></div>
    <div class="info-group"><span class="info-label">เลขนักศึกษา:</span> <span class="info-value"><?php echo htmlspecialchars($data['student_id']); ?></span></div>
    <div class="info-group"><span class="info-label">ชั้นปี:</span> <span class="info-value"><?php echo $data['year_level']; ?></span></div>
    <div class="info-group"><span class="info-label">คณะ:</span> <span class="info-value"><?php echo htmlspecialchars($data['faculty']); ?></span></div>
    <div class="info-group"><span class="info-label">วันที่เข้าร่วมกิจกรรม:</span> <span class="info-value"><?php echo $data['activity_date']; ?></span></div>
    <div class="info-group"><span class="info-label">คะแนน:</span> <span class="info-value"><?php echo $data['score']; ?></span></div>
    <div class="info-group"><span class="info-label">ความคิดเห็น:</span> <span class="info-value"><?php echo nl2br(htmlspecialchars($data['comment'])); ?></span></div>

    <?php if($isPass): ?>
        <?php if(!$already_member): ?>
            <form method="POST">
                <button type="submit" name="join_club" class="btn btn-success mt-3">ยืนยันเข้าชมรม</button>
            </form>
        <?php else: ?>
            <div class="member-notice">คุณเป็นสมาชิกชมรม Higher Level รุ่นที่ 26 เรียบร้อยแล้ว 🎉</div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="logout-btn">
        <a href="logout.php" class="btn btn-outline-light btn-lg mt-3">ออกจากระบบ</a>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="memberModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <h2>🎉 คุณได้เข้าชมรมเรียบร้อยแล้ว! 🎉</h2>
      <p class="welcome-text">ยินดีต้อนรับสู่ครอบครัว Higher Level รุ่นที่ 26 🎉</p>
      <button type="button" class="btn btn-success mt-3" data-bs-dismiss="modal">ปิด</button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Confetti animation
if(<?php echo $isPass?'true':'false'; ?>){
    const canvas = document.getElementById('confetti');
    const ctx = canvas.getContext('2d');
    canvas.width = canvas.offsetWidth;
    canvas.height = canvas.offsetHeight;
    const confettiCount = 250;
    const confetti = [];
    for(let i=0;i<confettiCount;i++){
        confetti.push({
            x: Math.random()*canvas.width,
            y: Math.random()*canvas.height - canvas.height,
            r: Math.random()*8+4,
            d: Math.random()*confettiCount,
            color: `hsl(${Math.random()*360},100%,50%)`,
            tilt: Math.random()*10-10
        });
    }

    function drawConfetti(){
        ctx.clearRect(0,0,canvas.width,canvas.height);
        confetti.forEach(c=>{
            ctx.beginPath();
            ctx.lineWidth = c.r/2;
            ctx.strokeStyle = c.color;
            ctx.moveTo(c.x + c.tilt, c.y);
            ctx.lineTo(c.x + c.tilt + c.r, c.y + c.r + c.tilt);
            ctx.stroke();
        });
        updateConfetti();
    }

    let angle=0;
    function updateConfetti(){
        angle += 0.01;
        confetti.forEach((c,i)=>{
            c.y += (Math.cos(angle + c.d) + 3 + c.r/2)/2;
            c.x += Math.sin(angle);
            if(c.y > canvas.height) confetti[i] = {...c, y:-10, x:Math.random()*canvas.width};
        });
    }
    setInterval(drawConfetti, 20);
}

// Show modal if just joined
<?php if($showModal): ?>
const memberModal = new bootstrap.Modal(document.getElementById('memberModal'));
memberModal.show();

// After modal closes, add member notice below card
document.getElementById('memberModal').addEventListener('hidden.bs.modal', () => {
    const notice = document.createElement('div');
    notice.className = 'member-notice';
    notice.innerHTML = 'คุณเป็นสมาชิกชมรม Higher Level รุ่นที่ 26 เรียบร้อยแล้ว 🎉';
    document.querySelector('.main-card').insertBefore(notice, document.querySelector('.logout-btn'));
});
<?php endif; ?>
</script>
</body>
</html>
