<?php
// ─── verify_otp.php ──────────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/config.php";

// Guard: must have a pending session
if (!isset($_SESSION['pending_user'])) {
    header('Location: login.php');
    exit();
}

$gmail = $_SESSION['pending_user'];
$error   = "";
$success = "";

// ─── RESEND OTP ───────────────────────────────────────────────────────────────
if (isset($_POST['resend'])) {
    $otp = rand(100000, 999999);
    mysqli_query($cn, "UPDATE userdetail SET otp='$otp' WHERE gmail='$gmail'");

    $subject = "Your New OTP – Shivi's Stylevana";
    $message = "
    <html><body style='font-family:Poppins,sans-serif;background:#F8F5F2;padding:30px;'>
      <div style='max-width:400px;margin:auto;background:#fff;border-radius:16px;padding:30px;text-align:center;'>
        <h2 style='font-family:Georgia,serif;color:#282c3f;'>Shivi's <span style='color:#D9A299;'>Stylevana</span></h2>
        <p style='color:#666;'>Your new One-Time Password:</p>
        <div style='font-size:36px;font-weight:700;letter-spacing:10px;color:#D9A299;margin:20px 0;'>$otp</div>
        <p style='color:#999;font-size:12px;'>Valid for 10 minutes. Do not share it.</p>
      </div>
    </body></html>";
    $headers  = "MIME-Version: 1.0\r\nContent-type: text/html; charset=UTF-8\r\nFrom: noreply@shivistylevana.com\r\n";
    mail($gmail, $subject, $message, $headers);

    $success = "A new OTP has been sent to <strong>$gmail</strong> ✦";
}

// ─── VERIFY OTP ───────────────────────────────────────────────────────────────
if (isset($_POST['verify'])) {
    // Combine 6 individual digit fields into one string
    $entered = "";
    for ($i = 1; $i <= 6; $i++) {
        $entered .= isset($_POST["d$i"]) ? preg_replace('/\D/', '', $_POST["d$i"]) : "";
    }

    if (strlen($entered) < 6) {
        $error = "Please enter all 6 digits of your OTP.";
    } else {
        $safe_email = mysqli_real_escape_string($cn, $gmail);
        $result = mysqli_query($cn, "SELECT otp FROM userdetail WHERE gmail='$safe_email'");
        $row    = mysqli_fetch_assoc($result);

        if ($row && $row['otp'] == $entered) {
            // ✅ OTP correct – clear OTP, set real session, redirect
            mysqli_query($cn, "UPDATE userdetail SET otp=NULL WHERE gmail='$safe_email'");
            unset($_SESSION['pending_user']);
            $_SESSION['user'] = $gmail;
            header('Location: after-login.php');
            exit();
        } else {
            $error = "Incorrect OTP. Please try again or resend.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP – Shivi's Stylevana</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --bg:      #F8F5F2;
            --card:    #FFFFFF;
            --accent:  #D9A299;
            --accent2: #c48b81;
            --dark:    #282c3f;
            --muted:   #888;
            --border:  #e8e0db;
            --error:   #c0392b;
            --ok:      #2e7d4f;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            background-image: radial-gradient(circle at 20% 20%, #f5e6e3 0%, transparent 50%),
                              radial-gradient(circle at 80% 80%, #ede8e4 0%, transparent 50%);
        }

        /* ── Brand ── */
        .brand { text-align: center; margin-bottom: 24px; }
        .brand img { width: 52px; margin-bottom: 6px; }
        .brand h1 { font-family: 'Playfair Display', serif; font-size: 28px; color: var(--dark); }
        .brand h1 span { color: var(--accent); }
        .brand p { font-size: 11px; color: var(--muted); letter-spacing: 2px; text-transform: uppercase; margin-top: 2px; }

        /* ── Card ── */
        .card {
            background: var(--card);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,.08), 0 4px 16px rgba(217,162,153,.12);
            padding: 40px 36px;
            width: 100%;
            max-width: 420px;
            text-align: center;
        }

        .lock-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #fce8e6, #f5d5d0);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 28px;
        }

        .card h2 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            color: var(--dark);
            margin-bottom: 8px;
        }
        .card .subtitle { font-size: 12.5px; color: var(--muted); line-height: 1.7; }
        .card .email-tag {
            display: inline-block;
            background: #fce8e6;
            color: var(--accent2);
            font-weight: 600;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 12px;
            margin-top: 6px;
        }

        /* ── Alerts ── */
        .alert {
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 12px;
            margin: 16px 0;
        }
        .alert.error   { background: #fdecea; color: var(--error); border: 1px solid #f5c6c2; }
        .alert.success { background: #eafaf1; color: var(--ok);    border: 1px solid #a9dfbf; }

        /* ── OTP input boxes ── */
        .otp-row {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 24px 0 8px;
        }
        .otp-row input {
            width: 48px;
            height: 56px;
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            color: var(--dark);
            border: 2px solid var(--border);
            border-radius: 12px;
            outline: none;
            background: #fdfbfa;
            transition: border-color .2s, box-shadow .2s, transform .15s;
            caret-color: var(--accent);
            font-family: 'Poppins', sans-serif;
        }
        .otp-row input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(217,162,153,.18);
            transform: translateY(-2px);
        }
        .otp-row input.filled {
            border-color: var(--accent);
            background: #fce8e6;
        }

        /* ── Timer ── */
        .timer-wrap { font-size: 12px; color: var(--muted); margin-bottom: 8px; }
        #timer { font-weight: 600; color: var(--accent2); }

        /* ── Buttons ── */
        .btn-verify {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 50px;
            background: var(--dark);
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .5px;
            cursor: pointer;
            margin-top: 10px;
            transition: background .25s, transform .15s, box-shadow .2s;
            box-shadow: 0 4px 14px rgba(40,44,63,.18);
        }
        .btn-verify:hover {
            background: var(--accent);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(217,162,153,.35);
        }

        .resend-row {
            margin-top: 18px;
            border-top: 1px solid var(--border);
            padding-top: 16px;
            font-size: 12.5px;
            color: var(--muted);
        }
        .resend-row button {
            background: none;
            border: none;
            color: var(--accent2);
            font-weight: 600;
            cursor: pointer;
            font-size: 12.5px;
            font-family: 'Poppins', sans-serif;
            text-decoration: underline;
        }
        .resend-row button:disabled { opacity: .4; cursor: default; text-decoration: none; }

        .back-link {
            display: inline-block;
            margin-top: 14px;
            font-size: 12px;
            color: var(--muted);
            text-decoration: none;
        }
        .back-link:hover { color: var(--accent2); }
    </style>
</head>
<body>

    <div class="brand">
        <img src="logo.png" alt="Stylevana Logo">
        <h1>Shivi's <span>Stylevana</span></h1>
        <p>Where style meets you ✦</p>
    </div>

    <div class="card">
        <div class="lock-icon">🔐</div>
        <h2>Verify Your Email</h2>
        <p class="subtitle">
            We've sent a 6-digit OTP to<br>
            <span class="email-tag"><?= htmlspecialchars($gmail) ?></span>
        </p>

        <?php if ($error):   ?><div class="alert error"><?= $error ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert success"><?= $success ?></div><?php endif; ?>

        <!-- ── Verify OTP form ── -->
        <form method="post" action="" id="otpForm">
            <div class="otp-row">
                <?php for ($i = 1; $i <= 6; $i++): ?>
                    <input type="text" name="d<?= $i ?>" id="d<?= $i ?>"
                           maxlength="1" inputmode="numeric" autocomplete="off"
                           pattern="[0-9]">
                <?php endfor; ?>
            </div>

            <div class="timer-wrap">OTP expires in <span id="timer">10:00</span></div>

            <button type="submit" name="verify" class="btn-verify">Verify & Continue ✦</button>
        </form>

        <!-- ── Resend OTP form ── -->
        <div class="resend-row">
            Didn't receive it?
            <form method="post" action="" style="display:inline;">
                <button type="submit" name="resend" id="resendBtn" disabled>Resend OTP</button>
            </form>
        </div>

        <a href="login.php" class="back-link">← Back to Login</a>
    </div>

    <script>
        // ── Auto-focus next box ──────────────────────────────────────────────
        const inputs = document.querySelectorAll('.otp-row input');
        inputs.forEach((input, idx) => {
            input.addEventListener('input', function () {
                this.value = this.value.replace(/\D/, '');
                if (this.value && idx < inputs.length - 1) {
                    inputs[idx + 1].focus();
                }
                this.classList.toggle('filled', !!this.value);
            });
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Backspace' && !this.value && idx > 0) {
                    inputs[idx - 1].focus();
                    inputs[idx - 1].classList.remove('filled');
                }
            });
            // Allow paste into first box
            input.addEventListener('paste', function (e) {
                const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
                if (pasted.length === 6) {
                    e.preventDefault();
                    inputs.forEach((inp, i) => {
                        inp.value = pasted[i] || '';
                        inp.classList.toggle('filled', !!inp.value);
                    });
                    inputs[5].focus();
                }
            });
        });
        inputs[0].focus();

        // ── Countdown timer ────────────────────────────────────────────────
        let seconds = 600; // 10 minutes
        const timerEl   = document.getElementById('timer');
        const resendBtn = document.getElementById('resendBtn');
        const tick = setInterval(() => {
            seconds--;
            const m = String(Math.floor(seconds / 60)).padStart(2, '0');
            const s = String(seconds % 60).padStart(2, '0');
            timerEl.textContent = `${m}:${s}`;
            if (seconds <= 0) {
                clearInterval(tick);
                timerEl.textContent = "Expired";
                timerEl.style.color = "#c0392b";
                resendBtn.disabled = false;
            }
            if (seconds === 540) resendBtn.disabled = false; // enable resend after 1 min
        }, 1000);
    </script>
</body>
</html>