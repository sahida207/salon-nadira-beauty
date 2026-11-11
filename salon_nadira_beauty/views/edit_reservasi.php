<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit;
}

include "../config/databases.php";

$id = (int)($_GET["id"] ?? 0);
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST["nama_pelanggan"] ?? "");
    $wa = trim($_POST["no_whatsapp"] ?? "");
    $layanan = (int)($_POST["id_layanan"] ?? 0);
    $tanggal = $_POST["tanggal_reservasi"] ?? "";
    $jam = $_POST["jam_booking"] ?? "";
    $pesan = trim($_POST["pesan"] ?? "");
    $status = $_POST["status"] ?? "Menunggu";

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE reservasi
         SET nama_pelanggan=?,
             no_whatsapp=?,
             id_layanan=?,
             tanggal_reservasi=?,
             jam_booking=?,
             pesan=?,
             status=?
         WHERE id_reservasi=?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssissssi",
        $nama,
        $wa,
        $layanan,
        $tanggal,
        $jam,
        $pesan,
        $status,
        $id
    );

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: admin_reservasi.php");
        exit;
    } else {
        $error = "Data gagal diperbarui. Silakan coba lagi.";
        mysqli_stmt_close($stmt);
    }
}

$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM reservasi WHERE id_reservasi=?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$data) {
    exit("Data reservasi tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Reservasi - Salon Nadira Beauty</title>

    <link rel="stylesheet" href="../style.css">
</head>

<body>

<header>
    <h1>Salon Nadira Beauty</h1>
    <p>Edit Data Reservasi</p>
</header>

<main>
    <h2>Edit Reservasi Pelanggan</h2>

    <?php if ($error != ""): ?>
        <p><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Nama Pelanggan</label>
        <input type="text" name="nama_pelanggan"
               value="<?= htmlspecialchars($data['nama_pelanggan']) ?>"
               required>

        <label>Nomor WhatsApp</label>
        <input type="text" name="no_whatsapp"
               value="<?= htmlspecialchars($data['no_whatsapp']) ?>"
               required>

        <label>Pilih Layanan</label>
        <select name="id_layanan" required>
            <?php
            $layananQuery = mysqli_query(
                $conn,
                "SELECT id_layanan, nama_layanan FROM layanan"
            );

            while ($layanan = mysqli_fetch_assoc($layananQuery)) {
                $selected = (
                    $data['id_layanan'] == $layanan['id_layanan']
                ) ? "selected" : "";

                echo "<option value='" . (int)$layanan['id_layanan'] . "' $selected>"
                    . htmlspecialchars($layanan['nama_layanan']) .
                    "</option>";
            }
            ?>
        </select>

        <label>Tanggal Reservasi</label>
        <input type="date" name="tanggal_reservasi"
               value="<?= htmlspecialchars($data['tanggal_reservasi']) ?>"
               required>

        <label>Jam Booking</label>
        <input type="time" name="jam_booking"
               value="<?= htmlspecialchars(substr($data['jam_booking'] ?? '', 0, 5)) ?>"
               required>

        <label>Pesan</label>
        <textarea name="pesan"><?= htmlspecialchars($data['pesan'] ?? '') ?></textarea>

        <label>Status</label>
        <select name="status" required>
            <?php
            $statuses = ["Menunggu", "Dikonfirmasi", "Selesai", "Dibatalkan"];

            foreach ($statuses as $statusOption) {
                $selected = ($data['status'] == $statusOption) ? "selected" : "";

                echo "<option value='" . htmlspecialchars($statusOption) . "' $selected>"
                    . htmlspecialchars($statusOption) .
                    "</option>";
            }
            ?>
        </select>

        <button type="submit" class="tombol">
            Simpan Perubahan
        </button>

        <a href="admin_reservasi.php" class="tombol">
            Batal
        </a>

    </form>
</main>

</body>
</html>