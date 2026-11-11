<?php
require_once '../config/databases.php';

// Hanya menerima pengiriman form POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: reservasi.php");
    exit;
}

// Ambil data dari form
$nama_pelanggan = trim($_POST['nama_pelanggan'] ?? '');
$no_whatsapp = trim($_POST['no_whatsapp'] ?? '');
$id_layanan = (int) ($_POST['id_layanan'] ?? 0);
$tanggal_reservasi = trim($_POST['tanggal_reservasi'] ?? '');
$jam_booking = trim($_POST['jam_booking'] ?? '');
$pesan = trim($_POST['pesan'] ?? '');

// Validasi data wajib
$data_kosong = [];

if ($nama_pelanggan === '') {
    $data_kosong[] = 'Nama pelanggan';
}

if ($no_whatsapp === '') {
    $data_kosong[] = 'Nomor WhatsApp';
}

if ($id_layanan <= 0) {
    $data_kosong[] = 'Pilihan layanan';
}

if ($tanggal_reservasi === '') {
    $data_kosong[] = 'Tanggal reservasi';
}

if ($jam_booking === '') {
    $data_kosong[] = 'Jam booking';
}

if (!empty($data_kosong)) {
    echo "<script>
        alert('Data belum terbaca: " . implode(', ', $data_kosong) . "');
        window.location='reservasi.php';
    </script>";
    exit;
}

// Validasi nomor WhatsApp
if (!preg_match('/^[0-9+]{10,16}$/', $no_whatsapp)) {
    echo "<script>
        alert('Nomor WhatsApp tidak valid!');
        window.location='reservasi.php';
    </script>";
    exit;
}

// Validasi tanggal
$date = DateTime::createFromFormat('Y-m-d', $tanggal_reservasi);

if (!$date || $date->format('Y-m-d') !== $tanggal_reservasi) {
    echo "<script>
        alert('Tanggal reservasi tidak valid!');
        window.location='reservasi.php';
    </script>";
    exit;
}

// Validasi jam booking
$time = DateTime::createFromFormat('H:i', $jam_booking);

if (!$time || $time->format('H:i') !== $jam_booking) {
    echo "<script>
        alert('Jam booking tidak valid!');
        window.location='reservasi.php';
    </script>";
    exit;
}

// Pastikan layanan tersedia
$cek = $conn->prepare(
    "SELECT id_layanan FROM layanan WHERE id_layanan = ?"
);

if (!$cek) {
    die("Terjadi kesalahan database.");
}

$cek->bind_param("i", $id_layanan);
$cek->execute();

if ($cek->get_result()->num_rows === 0) {
    $cek->close();
    $conn->close();

    echo "<script>
        alert('Layanan tidak ditemukan!');
        window.location='reservasi.php';
    </script>";
    exit;
}

$cek->close();

// Status awal reservasi
$status = "Menunggu";

// Simpan reservasi ke database
$stmt = $conn->prepare(
    "INSERT INTO reservasi
    (nama_pelanggan, no_whatsapp, id_layanan,
    tanggal_reservasi, jam_booking, pesan, status)
    VALUES (?, ?, ?, ?, ?, ?, ?)"
);

if (!$stmt) {
    die("Terjadi kesalahan saat menyiapkan penyimpanan reservasi.");
}

$stmt->bind_param(
    "ssissss",
    $nama_pelanggan,
    $no_whatsapp,
    $id_layanan,
    $tanggal_reservasi,
    $jam_booking,
    $pesan,
    $status
);

// Jika berhasil disimpan
if ($stmt->execute()) {

    $stmt->close();
    $conn->close();

    ?>

    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reservasi Berhasil - Salon Nadira Beauty</title>

        <link rel="stylesheet" href="../style.css">

        <style>
            .konfirmasi-container {
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 25px;
                box-sizing: border-box;
            }

            .konfirmasi-box {
                width: 100%;
                max-width: 550px;
                padding: 40px 30px;
                box-sizing: border-box;
                text-align: center;
                background: rgba(255, 255, 255, 0.90);
                border: 1px solid #f2b6d0;
                border-radius: 20px;
                box-shadow: 0 8px 25px rgba(120, 40, 75, 0.15);
            }

            .ikon-berhasil {
                width: 85px;
                height: 85px;
                margin: 0 auto 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                background: #fce4ef;
                color: #d63384;
                font-size: 48px;
            }

            .konfirmasi-box h1 {
                color: #c21868;
                margin-bottom: 15px;
            }

            .konfirmasi-box p {
                color: #63334a;
                line-height: 1.7;
            }

            .status-reservasi {
                display: inline-block;
                margin: 15px 0;
                padding: 9px 20px;
                border-radius: 20px;
                background: #fff0c2;
                color: #8a6200;
                font-weight: bold;
            }

            .tombol-konfirmasi {
                display: inline-block;
                margin-top: 15px;
                padding: 12px 25px;
                border-radius: 25px;
                background: #d63384;
                color: white;
                text-decoration: none;
                font-weight: bold;
            }

            .tombol-konfirmasi:hover {
                background: #b8226b;
            }
        </style>
    </head>

    <body>

        <div class="konfirmasi-container">
            <div class="konfirmasi-box">

                <div class="ikon-berhasil">✓</div>

                <h1>Reservasi Berhasil!</h1>

                <p>
                    Terima kasih,
                    <strong><?= htmlspecialchars($nama_pelanggan) ?></strong>.
                </p>

                <p>
                    Permintaan reservasi kamu berhasil dikirim
                    ke Salon Nadira Beauty.
                </p>

                <p>
                    Tanggal:
                    <strong><?= htmlspecialchars($tanggal_reservasi) ?></strong>
                </p>

                <p>
                    Jam Booking:
                    <strong><?= htmlspecialchars($jam_booking) ?></strong>
                </p>

                <div class="status-reservasi">
                    Status: Menunggu Konfirmasi
                </div>

                <p>
                    Silakan tunggu konfirmasi dari admin.
                </p>

                <a href="../index.php" class="tombol-konfirmasi">
                    Kembali ke Beranda
                </a>

            </div>
        </div>

    </body>
    </html>

    <?php

} else {

    $stmt->close();
    $conn->close();

    echo "<script>
        alert('Reservasi gagal dikirim. Silakan coba kembali.');
        window.location='reservasi.php';
    </script>";

    exit;
}
?>