
<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config/databases.php";

$id_layanan = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

if ($id_layanan <= 0) {
    header("Location: admin_layanan.php");
    exit;
}

// Ambil data layanan
$stmt = mysqli_prepare(
    $conn,
    "SELECT id_layanan, nama_layanan, deskripsi, harga
     FROM layanan
     WHERE id_layanan = ?"
);

if (!$stmt) {
    die("Gagal menyiapkan query: " . htmlspecialchars($conn->error));
}

mysqli_stmt_bind_param($stmt, "i", $id_layanan);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$data) {
    header("Location: admin_layanan.php");
    exit;
}

$error = "";

// Proses update layanan
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama_layanan = trim($_POST["nama_layanan"] ?? "");
    $deskripsi = trim($_POST["deskripsi"] ?? "");
    $harga_input = trim($_POST["harga"] ?? "");

    if ($nama_layanan === "" || $deskripsi === "" || $harga_input === "") {
        $error = "Semua kolom harus diisi.";
    } elseif (!ctype_digit($harga_input)) {
        $error = "Harga harus berupa angka.";
    } else {
        $harga = (int) $harga_input;

        $update = mysqli_prepare(
            $conn,
            "UPDATE layanan
             SET nama_layanan = ?, deskripsi = ?, harga = ?
             WHERE id_layanan = ?"
        );

        if (!$update) {
            $error = "Gagal menyiapkan perubahan data.";
        } else {
            mysqli_stmt_bind_param(
                $update,
                "ssii",
                $nama_layanan,
                $deskripsi,
                $harga,
                $id_layanan
            );

            if (mysqli_stmt_execute($update)) {
                mysqli_stmt_close($update);
                header("Location: admin_layanan.php?berhasil=update");
                exit;
            } else {
                $error = "Data layanan gagal diperbarui.";
                mysqli_stmt_close($update);
            }
        }
    }

    // Pertahankan isian jika terjadi kesalahan
    $data["nama_layanan"] = $nama_layanan;
    $data["deskripsi"] = $deskripsi;
    $data["harga"] = $harga_input;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Data Layanan - Salon Nadira Beauty</title>

    <link rel="stylesheet" href="../style.css">

    <style>
        body {
            margin: 0;
            padding: 30px 15px;
            font-family: Arial, sans-serif;
            background:
                linear-gradient(rgba(255, 235, 242, 0.88), rgba(255, 235, 242, 0.88)),
                url("../image/background salon nadira beauty.jpeg");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .container-edit {
            width: 100%;
            max-width: 650px;
            margin: 30px auto;
            padding: 30px;
            box-sizing: border-box;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
        }

        .container-edit h2 {
            margin-top: 0;
            margin-bottom: 25px;
            color: #b83270;
            text-align: center;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #444;
            font-weight: bold;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            font-family: Arial, sans-serif;
        }

        .form-group textarea {
            min-height: 110px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #d94f91;
        }

        .pesan-error {
            padding: 12px;
            margin-bottom: 18px;
            color: #a00035;
            background: #ffe3ed;
            border-radius: 8px;
            font-size: 14px;
        }

        .tombol {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-top: 25px;
        }

        .btn-simpan,
        .btn-batal {
            display: inline-block;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-simpan {
            flex: 1;
            color: white;
            background: #d94f91;
        }

        .btn-simpan:hover {
            background: #bd3478;
        }

        .btn-batal {
            color: #b83270;
            background: #fce1ed;
        }

        .btn-batal:hover {
            background: #f8cadd;
        }

        @media (max-width: 480px) {
            .container-edit {
                padding: 20px;
            }

            .tombol {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

<div class="container-edit">

    <h2>Edit Data Layanan</h2>

    <?php if ($error !== ""): ?>
        <div class="pesan-error">
            <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="form-group">
            <label for="nama_layanan">Nama Layanan</label>
            <input
                type="text"
                id="nama_layanan"
                name="nama_layanan"
                maxlength="100"
                value="<?= htmlspecialchars($data["nama_layanan"], ENT_QUOTES, "UTF-8") ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea
                id="deskripsi"
                name="deskripsi"
                required
            ><?= htmlspecialchars($data["deskripsi"], ENT_QUOTES, "UTF-8") ?></textarea>
        </div>

        <div class="form-group">
            <label for="harga">Harga (Rp)</label>
            <input
                type="number"
                id="harga"
                name="harga"
                min="0"
                value="<?= htmlspecialchars((string) $data["harga"], ENT_QUOTES, "UTF-8") ?>"
                required
            >
        </div>

        <div class="tombol">
            <button type="submit" class="btn-simpan">
                Simpan Perubahan
            </button>

            <a href="admin_layanan.php" class="btn-batal">
                Batal
            </a>
        </div>

    </form>

</div>

</body>
</html>

<?php
$conn->close();
?>
