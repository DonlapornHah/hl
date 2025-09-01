<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>เข้าสู่ระบบ</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

<!-- ฟอนต์ Kanit -->
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600;700&display=swap" rel="stylesheet" />

<style>
    body {
        height: 100vh;
        background: linear-gradient(135deg, rgba(102,126,234,0.5), rgba(118,75,162,0.5)), 
                    url('pic1.jpg') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Kanit', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
        overflow: hidden;
    }

    /* canvas สำหรับ sparkle */
    #sparkleCanvas {
        position: fixed;
        top:0;
        left:0;
        width:100%;
        height:100%;
        pointer-events:none;
        z-index:0;
    }

    .login-card {
        position: relative;
        z-index:1;
        background: rgba(255, 255, 255, 0.7);
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        padding: 50px 35px;
        max-width: 420px;
        width: 100%;
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        animation: zoomIn 1s ease;
    }
    .login-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 25px 50px rgba(0,0,0,0.4);
    }
    @keyframes zoomIn {
        0% { transform: scale(0.7); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    .system-title {
        font-weight: 700;
        font-size: 1.4rem;
        color: #4b3c97;
        letter-spacing: 2px;
        margin-bottom: 25px;
        user-select: none;
    }
    .logo {
        max-width: 180px;
        margin-bottom: 25px;
        filter: drop-shadow(0 0 5px rgba(0,0,0,0.15));
        animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    h5 {
        margin-bottom: 25px;
        color: #000000;
        font-weight: 600;
    }
    label {
        display: block;
        text-align: left;
        font-weight: 600;
        margin-bottom: 10px;
        color: #555;
        font-size: 17px;
        letter-spacing: 0.03em;
    }
    input[type="text"] {
        width: 100%;
        padding: 14px 18px;
        border-radius: 14px;
        border: 1.8px solid #ccc;
        font-size: 16px;
        font-weight: 400;
        transition: border-color 0.3s ease, box-shadow 0.3s ease, transform 0.2s ease;
        font-family: inherit;
    }
    input[type="text"]:focus {
        outline: none;
        border-color: #6c5ce7;
        box-shadow: 0 0 12px #6c5ce7;
        transform: scale(1.02);
    }
    button[type="submit"] {
        margin-top: 35px;
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #6c5ce7, #4834d4);
        border: none;
        border-radius: 16px;
        color: white;
        font-size: 20px;
        font-weight: 700;
        cursor: pointer;
        letter-spacing: 1px;
        transition: transform 0.2s ease, box-shadow 0.3s ease;
        font-family: inherit;
    }
    button[type="submit"]:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(72,52,212,0.6);
    }
    .footer-text {
        margin-top: 22px;
        font-size: 14px;
        color: #777;
        letter-spacing: 0.05em;
        animation: fadeIn 2s ease 0.5s both;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 480px) {
        .login-card {
            padding: 40px 25px;
        }
        .system-title {
            font-size: 1.2rem;
            margin-bottom: 18px;
        }
        h2 {
            font-size: 1.6rem;
        }
        button[type="submit"] {
            font-size: 18px;
        }
        label {
            font-size: 15px;
        }
    }
</style>
</head>
<body>

<canvas id="sparkleCanvas"></canvas>

<main class="login-card" role="main" aria-label="หน้าเข้าสู่ระบบ">
    <div class="system-title">ระบบประกาศผลการออดิชั่น <br> Higher Level Audition 2025</div>

    <img src="https://i.ibb.co/6JmdbQ8k/image.png" alt="โลโก้ Higher Level" class="logo" />

    <h5>เข้าสู่ระบบด้วยเลขนักศึกษา</h5>

    <form action="process_login.php" method="POST" novalidate>
        <label for="student_id">เลขนักศึกษา :</label>
        <input type="text" name="student_id" id="student_id" placeholder="กรอกเลขนักศึกษา" required aria-required="true" />
        <button type="submit">เข้าสู่ระบบ</button>
    </form>

    <p class="footer-text">© 2025 Higher Level Audition</p>
</main>

<script>
const canvas = document.getElementById('sparkleCanvas');
const ctx = canvas.getContext('2d');
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

const particles = [];
const particleCount = 100;

for(let i=0;i<particleCount;i++){
    particles.push({
        x: Math.random()*canvas.width,
        y: Math.random()*canvas.height,
        r: Math.random()*2 + 1,
        dx: (Math.random()-0.5)*0.5,
        dy: (Math.random()-0.5)*0.5,
        opacity: Math.random()
    });
}

function draw(){
    ctx.clearRect(0,0,canvas.width,canvas.height);
    particles.forEach(p=>{
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI*2);
        ctx.fillStyle = `rgba(255,255,255,${p.opacity})`;
        ctx.fill();
        p.x += p.dx;
        p.y += p.dy;

        if(p.x<0) p.x=canvas.width;
        if(p.x>canvas.width) p.x=0;
        if(p.y<0) p.y=canvas.height;
        if(p.y>canvas.height) p.y=0;
    });
    requestAnimationFrame(draw);
}
draw();

window.addEventListener('resize', ()=>{
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
});
</script>

</body>
</html>
