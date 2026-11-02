
<?php
include __DIR__ . "/../config/databases.php";

$query_layanan = mysqli_query(
    $conn,
    "SELECT id_layanan, nama_layanan, deskripsi, harga
     FROM layanan
     ORDER BY id_layanan ASC"
);

if (!$query_layanan) {
    die("Gagal mengambil data: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Layanan - Salon Nadira Beauty</title>

    <link rel="stylesheet" href="../style.css">

    <style>
        /* BACKGROUND HALAMAN LAYANAN */
        body {
            background-color: #fff5f8;

            background-image:
                linear-gradient(
                    rgba(255, 230, 241, 0.55),
                    rgba(255, 230, 241, 0.55)
                ),
                url("../image/background salon nadira beauty.jpeg");

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
        }

        /* BAGIAN RESERVASI */
        .bagian-reservasi {
            text-align: center;
            margin: 25px 10px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.85);
            border-radius: 15px;
        }

        .bagian-reservasi h3 {
            color: #d63384;
            margin-bottom: 10px;
        }

        .bagian-reservasi p {
            margin-bottom: 15px;
        }

        .tombol-reservasi {
            display: inline-block;
            margin: 10px auto;
            padding: 12px 25px;
            background: #e75480;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: 0.3s;
        }

        .tombol-reservasi:hover {
            background: #c2185b;
        }

        /* JUDUL LAYANAN */
        main > h2 {
            text-align: center;
            color: #d63384;
            margin: 30px 0 20px;
        }

        /* DAFTAR LAYANAN */
        .daftar-layanan {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }

        /* KARTU LAYANAN */
        .layanan-item {
            width: 250px;
            padding: 20px;
            background: rgba(255, 240, 245, 0.95);
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.12);
            box-sizing: border-box;
            transition: 0.3s;
        }

        .layanan-item:hover {
            transform: translateY(-5px);
        }

        .layanan-item h3 {
            color: #a13d69;
            margin-bottom: 12px;
        }

        .layanan-item p {
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .harga {
            color: #a13d69;
            font-weight: bold;
            font-size: 18px;
        }

        @media (max-width: 768px) {
            body {
                background-attachment: scroll;
            }

            .layanan-item {
                width: 100%;
                max-width: 350px;
            }
        }
    </style>
</head>

<body>

<header>
    <h1>Salon Nadira Beauty</h1>
    <p>Layanan Perawatan Kecantikan</p>
</header>

<nav>
    <a href="../index.php">Beranda</a>
    <a href="tentang.php">Tentang Kami</a>
    <a href="layanan.php">Layanan</a>
    <a href="galeri.php">Galeri</a>
    <a href="kontak.php">Kontak</a>
</nav>

<div class="bagian-reservasi">
    <h3>Ingin Melakukan Perawatan?</h3>
    <p>Pilih layanan sesuai kebutuhanmu.</p>

    <a href="reservasi.php" class="tombol-reservasi">
        Reservasi Sekarang
    </a>
</div>

<main>

    <h2>Layanan dan Harga</h2>

    <div class="daftar-layanan">

        <?php if (mysqli_num_rows($query_layanan) > 0): ?>

            <?php while ($layanan = mysqli_fetch_assoc($query_layanan)): ?>

                <div class="layanan-item">

                    <h3>
                        <?= htmlspecialchars($layanan['nama_layanan']) ?>
                    </h3>

                    <p>
                        <?= htmlspecialchars($layanan['deskripsi'] ?? '') ?>
                    </p>

                    <p class="harga">
                        Rp<?= number_format(
                            (float)$layanan['harga'],
                            0,
                            ',',
                            '.'
                        ) ?>
                    </p>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <p>Belum ada layanan yang tersedia.</p>

        <?php endif; ?>

    </div>

    <div class="bagian-reservasi">
        <h3>Sudah Menentukan Pilihan?</h3>
        <p>Silakan lakukan reservasi melalui website kami.</p>

        <a href="reservasi.php" class="tombol-reservasi">
            Buat Reservasi
        </a>
    </div>

</main>

<footer>
    <p>&copy; 2026 Salon Nadira Beauty</p>
</footer>

</body>
</html>
