
<?php
require_once '../config/databases.php';

$id_layanan_pilihan = isset($_GET['id_layanan']) ? (int) $_GET['id_layanan'] : 0;

$query_layanan = mysqli_query($conn, "SELECT * FROM layanan ORDER BY nama_layanan ASC");

if (!$query_layanan) {
    die("Gagal mengambil data layanan: " . htmlspecialchars(mysqli_error($conn)));
}

if ($id_layanan_pilihan > 0) {
    $cek_layanan = mysqli_query(
        $conn,
        "SELECT id_layanan FROM layanan WHERE id_layanan = $id_layanan_pilihan"
    );

    if (!$cek_layanan || mysqli_num_rows($cek_layanan) === 0) {
        $id_layanan_pilihan = 0;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reservasi - Salon Nadira Beauty</title>

    <link rel="stylesheet" href="../style.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: #63334a;
            background:
                linear-gradient(rgba(255, 230, 240, 0.35), rgba(255, 230, 240, 0.35)),
                url("../image/background salon nadira beauty.jpeg");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        /* NAVBAR */
        header {
            width: 100%;
            background: rgba(255, 192, 220, 0.95);
            padding: 18px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            position: relative;
            z-index: 10;
        }

        .logo {
            color: #9b3866;
            font-size: 22px;
            font-weight: bold;
        }

        nav {
            display: flex;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap;
        }

        nav a {
            color: #63334a;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }

        nav a:hover {
            color: #d63384;
        }

        /* BAGIAN FORMULIR */
        .container-reservasi {
            width: 100%;
            max-width: 700px;
            margin: 45px auto;
            padding: 0 20px;
        }

        .judul {
            text-align: center;
            color: #9b3866;
            font-size: 32px;
            margin: 0 0 10px;
        }

        .deskripsi {
            text-align: center;
            line-height: 1.7;
            margin-bottom: 25px;
        }

        .reservasi-box {
            width: 100%;
            background: rgba(255, 192, 220, 0.88);
            backdrop-filter: blur(5px);
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 4px 12px rgba(100, 40, 70, 0.12);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            display: block;
            width: 100%;
            padding: 12px;
            border: 1px solid #d98bad;
            border-radius: 8px;
            font-size: 15px;
            font-family: Arial, sans-serif;
            background: rgba(255, 255, 255, 0.95);
            color: #63334a;
        }

        .form-group textarea {
            min-height: 110px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #d63384;
            box-shadow: 0 0 5px rgba(214, 51, 132, 0.3);
        }

        .btn-kirim {
            display: block;
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 25px;
            background: #d63384;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-kirim:hover {
            background: #a82061;
        }

        /* TOMBOL KEMBALI */
        .area-kembali {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: 25px;
        }

        .btn-kembali {
            display: inline-block;
            width: auto;
            height: auto;
            padding: 11px 22px;
            border: 2px solid #d63384;
            border-radius: 25px;
            color: #d63384;
            background: rgba(255, 255, 255, 0.90);
            text-decoration: none;
            font-weight: bold;
            text-align: center;
        }

        .btn-kembali:hover {
            background: #d63384;
            color: white;
        }

        /* FOOTER */
        footer {
            width: 100%;
            background: rgba(155, 56, 102, 0.92);
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
        }

        @media (max-width: 600px) {
            header {
                justify-content: center;
            }

            nav {
                justify-content: center;
                gap: 12px;
            }

            .reservasi-box {
                padding: 23px;
            }

            .judul {
                font-size: 27px;
            }
        }
    </style>
</head>

<body>

<header>
    <div class="logo">Salon Nadira Beauty</div>

    <nav>
        <a href="../index.php#beranda">Beranda</a>
        <a href="../index.php#tentang">Tentang Kami</a>
        <a href="../index.php#layanan">Layanan</a>
        <a href="../index.php#galeri">Galeri</a>
        <a href="../index.php#kontak">Kontak</a>
        <a href="../login.php">Login Admin</a>
    </nav>
</header>

<main class="container-reservasi">

    <h1 class="judul">Formulir Reservasi</h1>

    <p class="deskripsi">
        Silakan isi formulir di bawah ini untuk melakukan reservasi
        perawatan di Salon Nadira Beauty.
    </p>

    <div class="reservasi-box">

        <form action="proses_reservasi.php" method="POST">

            <div class="form-group">
                <label for="nama_pelanggan">Nama Lengkap</label>
                <input
                    type="text"
                    id="nama_pelanggan"
                    name="nama_pelanggan"
                    placeholder="Masukkan nama lengkap"
                    maxlength="100"
                    required
                >
            </div>

            <div class="form-group">
                <label for="no_whatsapp">Nomor WhatsApp</label>
                <input
                    type="tel"
                    id="no_whatsapp"
                    name="no_whatsapp"
                    placeholder="Contoh: 085123456789"
                    maxlength="20"
                    required
                >
            </div>

            <div class="form-group">
                <label for="id_layanan">Pilih Layanan</label>
                <select id="id_layanan" name="id_layanan" required>
                    <option value="">-- Pilih Layanan --</option>

                    <?php while ($layanan = mysqli_fetch_assoc($query_layanan)): ?>
                        <option
                            value="<?= (int) $layanan['id_layanan']; ?>"
                            <?= $id_layanan_pilihan === (int) $layanan['id_layanan'] ? 'selected' : ''; ?>
                        >
                            <?= htmlspecialchars($layanan['nama_layanan']); ?>
                            - Rp <?= number_format((float) $layanan['harga'], 0, ',', '.'); ?>
                        </option>
                    <?php endwhile; ?>

                </select>
            </div>

            <div class="form-group">
                <label for="tanggal_reservasi">Tanggal Reservasi</label>
                <input
                    type="date"
                    id="tanggal_reservasi"
                    name="tanggal_reservasi"
                    min="<?= date('Y-m-d'); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="pesan">Pesan atau Catatan</label>
                <textarea
                    id="pesan"
                    name="pesan"
                    placeholder="Tuliskan pesan atau permintaan khusus (opsional)"
                ></textarea>
            </div>

            <button type="submit" class="btn-kirim">
                Kirim Reservasi
            </button>

        </form>

    </div>

    <div class="area-kembali">
        <a href="../index.php" class="btn-kembali">
            Kembali ke Beranda
        </a>
    </div>

</main>

<footer>
    <p>&copy; <?= date('Y'); ?> Salon Nadira Beauty. Semua Hak Dilindungi.</p>
</footer>

</body>
</html>
