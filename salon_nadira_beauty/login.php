
<?php
session_start();
require_once "config/databases.php";

// Jika admin sudah login, langsung ke dashboard
if (isset($_SESSION["admin"])) {
    header("Location: views/dashboard_admin.php");
    exit;
}

$error = "";

// Proses login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {
        $error = "Username dan password wajib diisi!";
    } else {
        $query = "SELECT * FROM admin WHERE username = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($admin = mysqli_fetch_assoc($result)) {

                // Mendukung password hash dan password lama berupa teks biasa
                $passwordBenar =
                    password_verify($password, $admin["password"]) ||
                    hash_equals((string)$admin["password"], $password);

                if ($passwordBenar) {
                    session_regenerate_id(true);

                    $_SESSION["admin"] = $admin["username"];
                    $_SESSION["id_admin"] = $admin["id_admin"];
                    $_SESSION["nama_admin"] = $admin["nama_admin"];

                    header("Location: views/dashboard_admin.php");
                    exit;
                } else {
                    $error = "Password yang Anda masukkan salah!";
                }
            } else {
                $error = "Username tidak ditemukan!";
            }

            mysqli_free_result($result);
            mysqli_stmt_close($stmt);
        } else {
            $error = "Terjadi kesalahan pada sistem. Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Admin - Salon Nadira Beauty</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;

            background:
                linear-gradient(
                    rgba(255, 220, 235, 0.65),
                    rgba(255, 220, 235, 0.65)
                ),
                url("image/background salon nadira beauty.jpeg");

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px 35px 25px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(180, 80, 130, 0.25);
            text-align: center;
        }

        .judul {
            color: #d63384;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .subjudul {
            color: #777;
            font-size: 14px;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .form-group {
            text-align: left;
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            color: #8f3765;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 13px;
            border: 1px solid #e8a5c5;
            border-radius: 10px;
            outline: none;
            font-size: 15px;
        }

        .form-group input:focus {
            border-color: #d63384;
            box-shadow: 0 0 5px rgba(214, 51, 132, 0.25);
        }

        .password-wrapper {
            display: flex;
            gap: 8px;
        }

        .password-wrapper input {
            flex: 1;
            min-width: 0;
        }

        .btn-lihat {
            padding: 0 12px;
            border: none;
            border-radius: 10px;
            background: #f4c2d7;
            color: #8f3765;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            margin-top: 8px;
            border: none;
            border-radius: 10px;
            background: #d63384;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: #b9256d;
        }

        .kembali {
            display: block;
            width: 100%;
            margin-top: 18px;
            padding: 12px;
            border: 1px solid #d63384;
            border-radius: 10px;
            color: #d63384;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .kembali:hover {
            background: #d63384;
            color: white;
        }

        .error {
            background: #ffe0e8;
            color: #b4234d;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .footer {
            border-top: 1px solid #f2c6da;
            margin-top: 20px;
            padding-top: 15px;
            color: #8f3765;
            font-size: 13px;
            font-weight: bold;
            text-align: center;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 22px 22px;
            }

            .judul {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

    <div class="login-container">

        <h1 class="judul">Login Admin</h1>

        <p class="subjudul">
            Selamat Datang di<br>
            <strong>Salon Nadira Beauty</strong>
        </p>

        <?php if ($error !== ""): ?>
            <div class="error">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">

            <div class="form-group">
                <label for="username">Username</label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Masukkan username"
                    value="<?= htmlspecialchars($_POST["username"] ?? "", ENT_QUOTES, 'UTF-8'); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>

                <div class="password-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        required
                    >

                    <button
                        type="button"
                        class="btn-lihat"
                        id="btnLihat"
                    >
                        Lihat
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-login">
                Login
            </button>

        </form>

        <a href="index.php" class="kembali">
            &larr; Kembali ke Beranda
        </a>

        <div class="footer">
            &copy; <?= date("Y"); ?> Salon Nadira Beauty
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const passwordInput = document.getElementById("password");
            const tombolLihat = document.getElementById("btnLihat");

            tombolLihat.addEventListener("click", function () {
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    tombolLihat.textContent = "Sembunyikan";
                } else {
                    passwordInput.type = "password";
                    tombolLihat.textContent = "Lihat";
                }
            });
        });
    </script>

</body>
</html>
