<?php
require_once __DIR__ . "/config.php";
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/vendor/autoload.php';

// ══════════════════════════════════════════════════════
//  HELPER – Send OTP via PHPMailer (Gmail SMTP)
// ══════════════════════════════════════════════════════
function sendOTPEmail($gmail, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'shivanimishra0519@gmail.com';
        $mail->Password   = 'mujj ardk jetj yrku';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('shivanimishra0519@gmail.com', "Shivi's Stylevana");
        $mail->addAddress($gmail);
        $mail->isHTML(true);
        $mail->Subject = "Your OTP – Shivi's Stylevana";
        $mail->Body    = "
        <div style='font-family:Georgia,sans-serif;background:#F8F5F2;padding:40px 20px;'>
          <div style='max-width:420px;margin:auto;background:#fff;border-radius:20px;
                      padding:36px;text-align:center;box-shadow:0 8px 30px rgba(0,0,0,.07);'>
            <h2 style='font-family:Georgia,serif;color:#282c3f;font-size:22px;margin-bottom:4px;'>
              Shivi's <span style='color:#D9A299;'>Stylevana</span>
            </h2>
            <p style='color:#aaa;font-size:11px;letter-spacing:2px;text-transform:uppercase;margin-bottom:24px;'>
              Verification Code
            </p>
            <p style='color:#555;font-size:14px;margin-bottom:16px;'>
              Use the OTP below to complete your verification:
            </p>
            <div style='display:inline-block;background:#fce8e6;border-radius:14px;padding:14px 32px;margin-bottom:20px;'>
              <span style='font-size:38px;font-weight:700;letter-spacing:12px;color:#D9A299;'>$otp</span>
            </div>
            <p style='color:#bbb;font-size:11px;'>Valid for <strong>10 minutes</strong>. Do not share with anyone.</p>
          </div>
        </div>";
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false; // email failed but OTP is still saved in DB
    }
}

// ══════════════════════════════════════════════════════
//  AJAX HANDLERS  (all POST requests land here)
// ══════════════════════════════════════════════════════
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    $action = $_POST['action'];

    // ── 1. Login: check credentials → generate & send OTP ──
    if ($action === 'send_login_otp') {
        $gmail    = mysqli_real_escape_string($cn, trim($_POST['gmail']   ?? ''));
        $password = trim($_POST['password'] ?? '');

        $res = mysqli_query($cn, "SELECT gmail FROM userdetail WHERE gmail='$gmail' AND password='$password'");
        if (mysqli_num_rows($res) === 0) {
            echo json_encode(['success' => false, 'msg' => 'Incorrect email or password.']);
            exit();
        }
        $otp = rand(100000, 999999);
        mysqli_query($cn, "UPDATE userdetail SET otp='$otp' WHERE gmail='$gmail'");
        $sent = sendOTPEmail($gmail, $otp);
        echo json_encode(['success' => true, 'sent' => $sent]);
        exit();
    }

    // ── 2. Login: verify OTP ──
    if ($action === 'verify_login_otp') {
        $gmail = mysqli_real_escape_string($cn, trim($_POST['gmail'] ?? ''));
        $otp   = mysqli_real_escape_string($cn, trim($_POST['otp']   ?? ''));

        $res = mysqli_query($cn, "SELECT otp FROM userdetail WHERE gmail='$gmail'");
        $row = mysqli_fetch_assoc($res);
        if ($row && $row['otp'] != '' && $row['otp'] == $otp) {
            mysqli_query($cn, "UPDATE userdetail SET otp=NULL, is_verified=1 WHERE gmail='$gmail'");
            $_SESSION['user'] = $gmail;
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'msg' => 'Incorrect OTP. Please try again.']);
        }
        exit();
    }

    // ── 3. Signup: upload photo (separate step so we get filename before insert) ──
    if ($action === 'upload_photo') {
        $filename = '';
        if (!empty($_FILES['userphoto']['name'])) {
            $filename = time() . '_' . basename($_FILES['userphoto']['name']);
            if (!is_dir('uploads')) { mkdir('uploads', 0777, true); }
            move_uploaded_file($_FILES['userphoto']['tmp_name'], 'uploads/' . $filename);
        }
        echo json_encode(['success' => true, 'filename' => $filename]);
        exit();
    }

    // ── 4. Signup: insert user → generate & send OTP ──
    if ($action === 'signup_send_otp') {
        $name     = mysqli_real_escape_string($cn, trim($_POST['name']            ?? ''));
        $address  = mysqli_real_escape_string($cn, trim($_POST['address']         ?? ''));
        $mobile   = mysqli_real_escape_string($cn, trim($_POST['mobilenumber']    ?? ''));
        $state    = mysqli_real_escape_string($cn, trim($_POST['state']           ?? ''));
        $city     = mysqli_real_escape_string($cn, trim($_POST['city']            ?? ''));
        $pincode  = mysqli_real_escape_string($cn, trim($_POST['pincode']         ?? ''));
        $age      = (int)trim($_POST['age'] ?? 0);
        $gmail    = mysqli_real_escape_string($cn, trim($_POST['gmail']           ?? ''));
        $password = trim($_POST['password']         ?? '');
        $confirm  = trim($_POST['confirmpassword']  ?? '');
        $photo    = mysqli_real_escape_string($cn, trim($_POST['saved_photo']     ?? ''));

        if ($password !== $confirm) {
            echo json_encode(['success' => false, 'msg' => 'Passwords do not match.']);
            exit();
        }
        $chk = mysqli_query($cn, "SELECT gmail FROM userdetail WHERE gmail='$gmail'");
        if (mysqli_num_rows($chk) > 0) {
            echo json_encode(['success' => false, 'msg' => 'This email is already registered!']);
            exit();
        }

        $otp = rand(100000, 999999);
        $sql = "INSERT INTO userdetail
                  (name, address, mobilenumber, state, city, pincode, age, gmail, password, confirmpassword, userphoto, otp, is_verified)
                VALUES
                  ('$name','$address','$mobile','$state','$city','$pincode',$age,'$gmail','$password','$confirm','$photo','$otp',0)";

        if (mysqli_query($cn, $sql)) {
            $sent = sendOTPEmail($gmail, $otp);
            echo json_encode(['success' => true, 'sent' => $sent]);
        } else {
            echo json_encode(['success' => false, 'msg' => 'DB Error: ' . mysqli_error($cn)]);
        }
        exit();
    }

    // ── 5. Signup: verify OTP ──
    if ($action === 'verify_signup_otp') {
        $gmail = mysqli_real_escape_string($cn, trim($_POST['gmail'] ?? ''));
        $otp   = mysqli_real_escape_string($cn, trim($_POST['otp']   ?? ''));

        $res = mysqli_query($cn, "SELECT otp FROM userdetail WHERE gmail='$gmail'");
        $row = mysqli_fetch_assoc($res);
        if ($row && $row['otp'] != '' && $row['otp'] == $otp) {
            mysqli_query($cn, "UPDATE userdetail SET otp=NULL, is_verified=1 WHERE gmail='$gmail'");
            $_SESSION['user'] = $gmail;
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'msg' => 'Incorrect OTP. Please try again.']);
        }
        exit();
    }

    // ── 6. Resend OTP ──
    if ($action === 'resend_otp') {
        $gmail = mysqli_real_escape_string($cn, trim($_POST['gmail'] ?? ''));
        $otp   = rand(100000, 999999);
        mysqli_query($cn, "UPDATE userdetail SET otp='$otp' WHERE gmail='$gmail'");
        $sent = sendOTPEmail($gmail, $otp);
        echo json_encode(['success' => true, 'sent' => $sent]);
        exit();
    }

    echo json_encode(['success' => false, 'msg' => 'Unknown action.']);
    exit();
}
// ══════════════════════════════════════════════════════
//  END AJAX — HTML starts below
// ══════════════════════════════════════════════════════
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login / Sign Up – Shivi's Stylevana</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,500&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

    :root {
      --bg:      #F8F5F2;
      --card:    #FFFFFF;
      --accent:  #D9A299;
      --accent2: #c48b81;
      --dark:    #282c3f;
      --muted:   #999;
      --border:  #e8e0db;
      --error:   #c0392b;
      --ok:      #27ae60;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: var(--bg);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 28px 16px;
      background-image:
        radial-gradient(ellipse at 15% 15%, #f5e6e3 0%, transparent 55%),
        radial-gradient(ellipse at 85% 85%, #ede8e4 0%, transparent 55%);
    }

    /* ── Brand ─────────────────────────────────── */
    .brand { text-align:center; margin-bottom:22px; }
    .brand img {
      width:58px; margin-bottom:6px;
      filter: drop-shadow(0 3px 8px rgba(217,162,153,.4));
    }
    .brand h1 {
      font-family:'Playfair Display',serif;
      font-size:30px; color:var(--dark); letter-spacing:.5px;
    }
    .brand h1 span { color:var(--accent); }
    .brand p {
      font-size:11px; color:var(--muted);
      letter-spacing:2.5px; text-transform:uppercase; margin-top:3px;
    }

    /* ── Card ──────────────────────────────────── */
    .card {
      background: var(--card);
      border-radius: 26px;
      box-shadow: 0 24px 64px rgba(0,0,0,.09), 0 4px 18px rgba(217,162,153,.14);
      padding: 38px 34px;
      width: 100%;
      max-width: 450px;
    }

    .panel { display:none; }
    .panel.active { display:block; animation: fadeSlide .35s ease; }
    @keyframes fadeSlide {
      from { opacity:0; transform:translateY(10px); }
      to   { opacity:1; transform:translateY(0); }
    }

    .panel-title {
      font-family:'Playfair Display',serif;
      font-size:24px; color:var(--dark);
      text-align:center; margin-bottom:4px;
    }
    .panel-sub {
      font-size:12px; color:var(--muted);
      text-align:center; margin-bottom:22px; letter-spacing:.4px;
    }

    /* ── Alerts ────────────────────────────────── */
    .alert {
      padding:10px 14px; border-radius:10px;
      font-size:12px; margin-bottom:14px;
      text-align:center; display:none;
    }
    .alert.show    { display:block; }
    .alert.error   { background:#fdecea; color:var(--error); border:1px solid #f5c6c2; }
    .alert.success { background:#eafaf1; color:var(--ok);    border:1px solid #a9dfbf; }
    .alert.info    { background:#fce8e6; color:var(--accent2); border:1px solid #f5cec9; }

    /* ── Form fields ────────────────────────────── */
    .form-group { margin-bottom:14px; }
    .form-group label {
      display:block; font-size:10.5px; font-weight:600;
      color:var(--muted); text-transform:uppercase;
      letter-spacing:1px; margin-bottom:6px;
    }
    .form-group input {
      width:100%; padding:11px 14px;
      border:1.5px solid var(--border); border-radius:10px;
      font-family:'Poppins',sans-serif; font-size:13px;
      color:var(--dark); outline:none; background:#fdfbfa;
      transition:border-color .2s, box-shadow .2s;
    }
    .form-group input:focus {
      border-color:var(--accent);
      box-shadow:0 0 0 3px rgba(217,162,153,.15);
    }
    .form-group input::placeholder { color:#ccc; }

    .row-2 { display:grid; grid-template-columns:1fr 1fr; gap:10px; }

    /* ── Scroll area (signup form) ──────────────── */
    .scroll-area {
      max-height:310px; overflow-y:auto;
      padding-right:6px; margin-bottom:2px;
    }
    .scroll-area::-webkit-scrollbar { width:3px; }
    .scroll-area::-webkit-scrollbar-thumb { background:var(--accent); border-radius:10px; }

    /* ══ OTP SECTION – slides down inline ══════════ */
    .otp-section {
      overflow: hidden;
      max-height: 0;
      opacity: 0;
      transition: max-height .55s cubic-bezier(.4,0,.2,1), opacity .4s ease;
      pointer-events: none;
    }
    .otp-section.open {
      max-height: 340px;
      opacity: 1;
      pointer-events: auto;
    }

    .otp-divider {
      display:flex; align-items:center; gap:10px; margin:20px 0 16px;
    }
    .otp-divider::before, .otp-divider::after {
      content:''; flex:1; height:1px; background:var(--border);
    }
    .otp-divider span {
      font-size:11px; color:var(--muted);
      text-transform:uppercase; letter-spacing:1.5px; white-space:nowrap;
    }

    .otp-sent-badge {
      background:#fce8e6; border:1px solid #f5cec9;
      border-radius:10px; padding:10px 14px;
      font-size:12px; color:var(--accent2);
      text-align:center; margin-bottom:16px;
    }
    .otp-sent-badge strong { color:var(--dark); }

    /* 6-box OTP */
    .otp-boxes {
      display:flex; justify-content:center; gap:9px; margin-bottom:10px;
    }
    .otp-boxes input {
      width:46px; height:52px;
      text-align:center; font-size:21px; font-weight:700;
      color:var(--dark); border:2px solid var(--border);
      border-radius:11px; outline:none; background:#fdfbfa;
      transition:border-color .2s, box-shadow .2s, transform .15s;
      font-family:'Poppins',sans-serif; caret-color:var(--accent);
    }
    .otp-boxes input:focus {
      border-color:var(--accent);
      box-shadow:0 0 0 3px rgba(217,162,153,.18);
      transform:translateY(-2px);
    }
    .otp-boxes input.filled { border-color:var(--accent); background:#fce8e6; }

    .otp-meta {
      display:flex; justify-content:space-between;
      align-items:center; font-size:11.5px; color:var(--muted); margin-bottom:4px;
    }
    .otp-meta .timer-val { font-weight:600; color:var(--accent2); }
    .resend-btn {
      background:none; border:none; color:var(--accent2);
      font-size:11.5px; font-weight:600; cursor:pointer;
      font-family:'Poppins',sans-serif; text-decoration:underline;
    }
    .resend-btn:disabled { opacity:.4; cursor:default; text-decoration:none; }

    /* ── Buttons ────────────────────────────────── */
    .auth-btn {
      width:100%; padding:13px; border:none;
      border-radius:50px; background:var(--dark); color:#fff;
      font-family:'Poppins',sans-serif; font-size:13px;
      font-weight:600; letter-spacing:.5px; cursor:pointer;
      margin-top:16px; position:relative;
      transition:background .25s, transform .15s, box-shadow .2s;
      box-shadow:0 4px 14px rgba(40,44,63,.18);
    }
    .auth-btn:hover {
      background:var(--accent); transform:translateY(-1px);
      box-shadow:0 6px 20px rgba(217,162,153,.4);
    }
    .auth-btn:active  { transform:translateY(0); }
    .auth-btn:disabled { opacity:.6; cursor:not-allowed; transform:none; }

    .btn-spinner {
      display:none; width:15px; height:15px;
      border:2px solid rgba(255,255,255,.35);
      border-top-color:#fff; border-radius:50%;
      animation:spin .7s linear infinite;
      position:absolute; right:20px; top:50%; margin-top:-7.5px;
    }
    .auth-btn.loading .btn-spinner { display:block; }
    @keyframes spin { to { transform:rotate(360deg); } }

    /* ── Switch ─────────────────────────────────── */
    .switch-form {
      margin-top:20px; text-align:center;
      border-top:1px solid var(--border); padding-top:16px;
      font-size:12.5px; color:var(--muted);
    }
    .switch-form button {
      background:none; border:none; color:var(--accent2);
      font-weight:600; cursor:pointer; font-size:12.5px;
      text-decoration:underline; font-family:'Poppins',sans-serif;
    }
  </style>
</head>
<body>

<div class="brand">
  <img src="logo.png" alt="Stylevana Logo">
  <h1>Shivi's <span>Stylevana</span></h1>
  <p>Where style meets you ✦</p>
</div>

<div class="card">

  <!-- ═══════════════ LOGIN PANEL ════════════════ -->
  <div class="panel active" id="loginPanel">
    <h2 class="panel-title">Welcome Back 💕</h2>
    <p class="panel-sub">Sign in to your account</p>

    <div class="alert error"   id="loginError"></div>
    <div class="alert success" id="loginSuccess"></div>

    <div class="form-group">
      <label>Email Address</label>
      <input type="email" id="loginGmail" placeholder="yourname@gmail.com">
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" id="loginPassword" placeholder="••••••••">
    </div>

    <button class="auth-btn" id="loginSendBtn" onclick="loginSendOTP()">
      Send OTP & Continue →
      <span class="btn-spinner"></span>
    </button>

    <!-- OTP slides in below password ── -->
    <div class="otp-section" id="loginOtpSection">
      <div class="otp-divider"><span>Enter OTP sent to your email</span></div>
      <div class="otp-sent-badge" id="loginOtpBadge"></div>

      <div class="otp-boxes" id="loginOtpBoxes">
        <input type="text" maxlength="1" inputmode="numeric">
        <input type="text" maxlength="1" inputmode="numeric">
        <input type="text" maxlength="1" inputmode="numeric">
        <input type="text" maxlength="1" inputmode="numeric">
        <input type="text" maxlength="1" inputmode="numeric">
        <input type="text" maxlength="1" inputmode="numeric">
      </div>

      <div class="otp-meta">
        <span>Expires in <span class="timer-val" id="loginTimerVal">10:00</span></span>
        <button class="resend-btn" id="loginResendBtn" disabled onclick="resendOTP('login')">Resend OTP</button>
      </div>

      <button class="auth-btn" id="loginVerifyBtn" onclick="loginVerifyOTP()">
        Verify & Login ✦
        <span class="btn-spinner"></span>
      </button>
    </div>
    <!-- /OTP section -->

    <div class="switch-form">
      <p>New here? <button type="button" onclick="switchPanel('signupPanel')">Create Account</button></p>
    </div>
  </div>

  <!-- ═══════════════ SIGNUP PANEL ═══════════════ -->
  <div class="panel" id="signupPanel">
    <h2 class="panel-title">Join Stylevana 🌸</h2>
    <p class="panel-sub">Fill in your details to get started</p>

    <div class="alert error"   id="signupError"></div>
    <div class="alert success" id="signupSuccess"></div>

    <div class="scroll-area">
      <div class="form-group">
        <label>Full Name</label>
        <input type="text" id="s_name" placeholder="Shivi Sharma">
      </div>
      <div class="row-2">
        <div class="form-group">
          <label>Mobile</label>
          <input type="text" id="s_mobile" maxlength="10" placeholder="9876543210">
        </div>
        <div class="form-group">
          <label>Age</label>
          <input type="number" id="s_age" placeholder="22" min="10" max="100">
        </div>
      </div>
      <div class="form-group">
        <label>Profile Photo</label>
        <input type="file" id="s_photo" accept="image/*">
      </div>
      <div class="form-group">
        <label>Address</label>
        <input type="text" id="s_address" placeholder="House / Street / Colony">
      </div>
      <div class="row-2">
        <div class="form-group">
          <label>City</label>
          <input type="text" id="s_city" placeholder="Mumbai">
        </div>
        <div class="form-group">
          <label>State</label>
          <input type="text" id="s_state" placeholder="Maharashtra">
        </div>
      </div>
      <div class="form-group">
        <label>Pin Code</label>
        <input type="text" id="s_pincode" placeholder="400001">
      </div>
      <div class="form-group">
        <label>Gmail</label>
        <input type="email" id="s_gmail" placeholder="yourname@gmail.com">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" id="s_password" placeholder="Min 8 characters">
      </div>
      <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" id="s_confirm" placeholder="Repeat password">
      </div>
    </div><!-- /scroll-area -->

    <button class="auth-btn" id="signupSendBtn" onclick="signupSendOTP()">
      Register & Get OTP →
      <span class="btn-spinner"></span>
    </button>

    <!-- OTP slides in below Register button ── -->
    <div class="otp-section" id="signupOtpSection">
      <div class="otp-divider"><span>Verify your email</span></div>
      <div class="otp-sent-badge" id="signupOtpBadge"></div>

      <div class="otp-boxes" id="signupOtpBoxes">
        <input type="text" maxlength="1" inputmode="numeric">
        <input type="text" maxlength="1" inputmode="numeric">
        <input type="text" maxlength="1" inputmode="numeric">
        <input type="text" maxlength="1" inputmode="numeric">
        <input type="text" maxlength="1" inputmode="numeric">
        <input type="text" maxlength="1" inputmode="numeric">
      </div>

      <div class="otp-meta">
        <span>Expires in <span class="timer-val" id="signupTimerVal">10:00</span></span>
        <button class="resend-btn" id="signupResendBtn" disabled onclick="resendOTP('signup')">Resend OTP</button>
      </div>

      <button class="auth-btn" id="signupVerifyBtn" onclick="signupVerifyOTP()">
        Verify & Complete Registration ✦
        <span class="btn-spinner"></span>
      </button>
    </div>
    <!-- /OTP section -->

    <div class="switch-form">
      <p>Already have an account? <button type="button" onclick="switchPanel('loginPanel')">Login</button></p>
    </div>
  </div>

</div><!-- /card -->

<script>
// ─────────────────────────────────────────────────────
//  PANEL SWITCH
// ─────────────────────────────────────────────────────
function switchPanel(id) {
  document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
  document.getElementById(id).classList.add('active');
}

// ─────────────────────────────────────────────────────
//  ALERT HELPERS
// ─────────────────────────────────────────────────────
function showAlert(id, type, msg) {
  const el = document.getElementById(id);
  el.className = 'alert ' + type + ' show';
  el.innerHTML = msg;
}
function clearAlert(...ids) {
  ids.forEach(id => { const el = document.getElementById(id); el.className='alert'; el.innerHTML=''; });
}

// ─────────────────────────────────────────────────────
//  OTP BOX INTERACTIONS
// ─────────────────────────────────────────────────────
function initOTPBoxes(boxesId) {
  const inputs = [...document.querySelectorAll('#' + boxesId + ' input')];
  inputs.forEach((inp, idx) => {
    inp.addEventListener('input', function () {
      this.value = this.value.replace(/\D/, '');
      this.classList.toggle('filled', !!this.value);
      if (this.value && idx < inputs.length - 1) inputs[idx + 1].focus();
    });
    inp.addEventListener('keydown', function (e) {
      if (e.key === 'Backspace' && !this.value && idx > 0) {
        inputs[idx - 1].value = '';
        inputs[idx - 1].classList.remove('filled');
        inputs[idx - 1].focus();
      }
    });
    inp.addEventListener('paste', function (e) {
      const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
      if (pasted.length === 6) {
        e.preventDefault();
        inputs.forEach((i, x) => { i.value = pasted[x] || ''; i.classList.toggle('filled', !!i.value); });
        inputs[5].focus();
      }
    });
  });
}
initOTPBoxes('loginOtpBoxes');
initOTPBoxes('signupOtpBoxes');

function getOTP(boxesId) {
  return [...document.querySelectorAll('#' + boxesId + ' input')].map(i => i.value).join('');
}
function clearOTP(boxesId) {
  document.querySelectorAll('#' + boxesId + ' input').forEach(i => { i.value = ''; i.classList.remove('filled'); });
}

// ─────────────────────────────────────────────────────
//  COUNTDOWN TIMER
// ─────────────────────────────────────────────────────
const timerHandles = {};
function startTimer(valId, resendBtnId) {
  if (timerHandles[valId]) clearInterval(timerHandles[valId]);
  const tv  = document.getElementById(valId);
  const btn = document.getElementById(resendBtnId);
  btn.disabled = true;
  tv.style.color = '';
  let s = 600;
  timerHandles[valId] = setInterval(() => {
    s--;
    tv.textContent = String(Math.floor(s/60)).padStart(2,'0') + ':' + String(s%60).padStart(2,'0');
    if (s === 540) btn.disabled = false;   // allow resend after 1 min
    if (s <= 0) {
      clearInterval(timerHandles[valId]);
      tv.textContent = 'Expired';
      tv.style.color = '#c0392b';
    }
  }, 1000);
}

// ─────────────────────────────────────────────────────
//  LOADING STATE
// ─────────────────────────────────────────────────────
function setLoading(btnId, state) {
  const btn = document.getElementById(btnId);
  btn.disabled = state;
  btn.classList.toggle('loading', state);
}

// ─────────────────────────────────────────────────────
//  POST HELPER
// ─────────────────────────────────────────────────────
function post(data) {
  const fd = new FormData();
  for (const k in data) fd.append(k, data[k]);
  return fetch('login.php', { method:'POST', body:fd }).then(r => r.json());
}

// ─────────────────────────────────────────────────────
//  LOGIN – STEP 1: Send OTP
// ─────────────────────────────────────────────────────
function loginSendOTP() {
  clearAlert('loginError', 'loginSuccess');
  const gmail    = document.getElementById('loginGmail').value.trim();
  const password = document.getElementById('loginPassword').value.trim();
  if (!gmail || !password) { showAlert('loginError','error','Please enter your email and password.'); return; }

  setLoading('loginSendBtn', true);
  post({ action:'send_login_otp', gmail, password })
    .then(d => {
      setLoading('loginSendBtn', false);
      if (d.success) {
        document.getElementById('loginOtpBadge').innerHTML = '📧 OTP sent to <strong>' + gmail + '</strong>';
        document.getElementById('loginOtpSection').classList.add('open');
        clearOTP('loginOtpBoxes');
        startTimer('loginTimerVal', 'loginResendBtn');
        setTimeout(() => document.querySelector('#loginOtpBoxes input').focus(), 300);
        if (!d.sent) showAlert('loginError','error','⚠️ Credentials OK but email failed. Check PHPMailer config.');
      } else {
        showAlert('loginError','error', d.msg);
      }
    })
    .catch(() => { setLoading('loginSendBtn',false); showAlert('loginError','error','Network error.'); });
}

// ─────────────────────────────────────────────────────
//  LOGIN – STEP 2: Verify OTP
// ─────────────────────────────────────────────────────
function loginVerifyOTP() {
  clearAlert('loginError','loginSuccess');
  const gmail = document.getElementById('loginGmail').value.trim();
  const otp   = getOTP('loginOtpBoxes');
  if (otp.length < 6) { showAlert('loginError','error','Please enter all 6 digits.'); return; }

  setLoading('loginVerifyBtn', true);
  post({ action:'verify_login_otp', gmail, otp })
    .then(d => {
      setLoading('loginVerifyBtn', false);
      if (d.success) {
        showAlert('loginSuccess','success','✅ Login successful! Redirecting…');
        clearInterval(timerHandles['loginTimerVal']);
        setTimeout(() => { window.location.href = 'after-login.php'; }, 1000);
      } else {
        showAlert('loginError','error', d.msg);
        clearOTP('loginOtpBoxes');
        setTimeout(() => document.querySelector('#loginOtpBoxes input').focus(), 50);
      }
    })
    .catch(() => { setLoading('loginVerifyBtn',false); showAlert('loginError','error','Network error.'); });
}

// ─────────────────────────────────────────────────────
//  SIGNUP – STEP 1: Upload photo → Register → Send OTP
// ─────────────────────────────────────────────────────
function signupSendOTP() {
  clearAlert('signupError','signupSuccess');
  const name    = document.getElementById('s_name').value.trim();
  const gmail   = document.getElementById('s_gmail').value.trim();
  const pass    = document.getElementById('s_password').value.trim();
  const confirm = document.getElementById('s_confirm').value.trim();
  const mobile  = document.getElementById('s_mobile').value.trim();

  if (!name || !gmail || !pass || !confirm || !mobile) {
    showAlert('signupError','error','Name, Mobile, Gmail & Password are required.'); return;
  }
  if (pass !== confirm) { showAlert('signupError','error','Passwords do not match.'); return; }

  setLoading('signupSendBtn', true);

  // Step A – upload photo
  const photoFD = new FormData();
  photoFD.append('action', 'upload_photo');
  const photoFile = document.getElementById('s_photo').files[0];
  if (photoFile) photoFD.append('userphoto', photoFile);

  fetch('login.php', { method:'POST', body: photoFD })
    .then(r => r.json())
    .then(pd => {
      const saved_photo = pd.filename || '';
      // Step B – register + OTP
      return post({
        action:'signup_send_otp', name, gmail,
        password: pass, confirmpassword: confirm,
        mobilenumber: mobile,
        address:  document.getElementById('s_address').value.trim(),
        city:     document.getElementById('s_city').value.trim(),
        state:    document.getElementById('s_state').value.trim(),
        pincode:  document.getElementById('s_pincode').value.trim(),
        age:      document.getElementById('s_age').value.trim() || 0,
        saved_photo
      });
    })
    .then(d => {
      setLoading('signupSendBtn', false);
      if (d.success) {
        document.getElementById('signupOtpBadge').innerHTML = '📧 OTP sent to <strong>' + gmail + '</strong>';
        document.getElementById('signupOtpSection').classList.add('open');
        clearOTP('signupOtpBoxes');
        startTimer('signupTimerVal','signupResendBtn');
        setTimeout(() => document.querySelector('#signupOtpBoxes input').focus(), 300);
        if (!d.sent) showAlert('signupError','error','⚠️ Registered but email failed. Check PHPMailer config.');
      } else {
        showAlert('signupError','error', d.msg);
      }
    })
    .catch(() => { setLoading('signupSendBtn',false); showAlert('signupError','error','Network error.'); });
}

// ─────────────────────────────────────────────────────
//  SIGNUP – STEP 2: Verify OTP
// ─────────────────────────────────────────────────────
function signupVerifyOTP() {
  clearAlert('signupError','signupSuccess');
  const gmail = document.getElementById('s_gmail').value.trim();
  const otp   = getOTP('signupOtpBoxes');
  if (otp.length < 6) { showAlert('signupError','error','Please enter all 6 digits.'); return; }

  setLoading('signupVerifyBtn', true);
  post({ action:'verify_signup_otp', gmail, otp })
    .then(d => {
      setLoading('signupVerifyBtn', false);
      if (d.success) {
        showAlert('signupSuccess','success','🎉 Welcome to Stylevana! Redirecting…');
        clearInterval(timerHandles['signupTimerVal']);
        setTimeout(() => { window.location.href = 'after-login.php'; }, 1200);
      } else {
        showAlert('signupError','error', d.msg);
        clearOTP('signupOtpBoxes');
        setTimeout(() => document.querySelector('#signupOtpBoxes input').focus(), 50);
      }
    })
    .catch(() => { setLoading('signupVerifyBtn',false); showAlert('signupError','error','Network error.'); });
}

// ─────────────────────────────────────────────────────
//  RESEND OTP
// ─────────────────────────────────────────────────────
function resendOTP(panel) {
  const gmail = panel === 'login'
    ? document.getElementById('loginGmail').value.trim()
    : document.getElementById('s_gmail').value.trim();

  post({ action:'resend_otp', gmail }).then(d => {
    if (!d.success) return;
    if (panel === 'login') {
      clearOTP('loginOtpBoxes');
      startTimer('loginTimerVal','loginResendBtn');
      showAlert('loginSuccess','info','📧 New OTP sent to your email!');
      setTimeout(() => document.querySelector('#loginOtpBoxes input').focus(), 50);
    } else {
      clearOTP('signupOtpBoxes');
      startTimer('signupTimerVal','signupResendBtn');
      showAlert('signupSuccess','info','📧 New OTP sent to your email!');
      setTimeout(() => document.querySelector('#signupOtpBoxes input').focus(), 50);
    }
  });
}

// ─────────────────────────────────────────────────────
//  ENTER KEY SUPPORT
// ─────────────────────────────────────────────────────
document.addEventListener('keydown', e => {
  if (e.key !== 'Enter') return;
  const loginActive  = document.getElementById('loginPanel').classList.contains('active');
  const signupActive = document.getElementById('signupPanel').classList.contains('active');
  const loginOtpOpen = document.getElementById('loginOtpSection').classList.contains('open');

  if (loginActive  && loginOtpOpen)  { loginVerifyOTP(); }
  else if (loginActive)              { loginSendOTP();   }
  else if (signupActive) {
    const signupOtpOpen = document.getElementById('signupOtpSection').classList.contains('open');
    if (signupOtpOpen) signupVerifyOTP();
    // no Enter auto-submit on signup form — prevent accidental submit
  }
});
</script>
</body>
</html>