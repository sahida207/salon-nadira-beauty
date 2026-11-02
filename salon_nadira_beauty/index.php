
<?php
require_once 'config/databases.php';

$query_layanan = mysqli_query($conn, "SELECT * FROM layanan ORDER BY id_layanan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salon Nadira Beauty</title>
    <link rel="stylesheet" href="style.css">

    <style>
        * {
            box-sizing: border-box;
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: #63334a;
            background:
                linear-gradient(rgba(255,230,240,.40), rgba(255,230,240,.40)),
                url("image/background salon nadira beauty.jpeg");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        /* NAVBAR */
        header {
            background: rgba(255,192,220,.92);
            padding: 12px 6%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
            color: #9b3866;
        }

        nav {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
        }

        nav a {
            color: #63334a;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
        }

        nav a:hover {
            color: #d63384;
        }

        section {
            padding: 35px 5%;
            scroll-margin-top: 75px;
        }

        .judul {
            text-align: center;
            color: #9b3866;
            font-size: 28px;
            margin: 0 0 25px;
        }

        /* BERANDA */
        .hero {
            min-height: 230px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 35px 5%;
        }

        .hero-content {
            width: 100%;
            max-width: 750px;
            padding: 25px;
            border-radius: 18px;
            background: rgba(255,192,220,.90);
            backdrop-filter: blur(5px);
        }

        .hero h1 {
            color: #9b3866;
            font-size: 32px;
            margin: 0 0 12px;
        }

        .hero p {
            line-height: 1.7;
            font-size: 15px;
        }

        .btn {
            display: inline-block;
            padding: 10px 21px;
            margin-top: 8px;
            border-radius: 25px;
            background: #d63384;
            color: white;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            transition: .3s;
        }

        .btn:hover {
            background: #a82061;
        }

        /* GRID GAMBAR BERANDA */
        .beranda-container {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .beranda-card {
            background: rgba(255,192,220,.93);
            padding: 12px;
            border-radius: 14px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(100,40,70,.12);
        }

        .beranda-card img {
            width: 100%;
            height: 210px;
            object-fit: cover;
            display: block;
            border-radius: 10px;
        }

        .beranda-card h3 {
            color: #9b3866;
            margin: 13px 0 7px;
            font-size: 18px;
        }

        .beranda-card p {
            font-size: 14px;
            line-height: 1.6;
            margin: 0 5px 8px;
        }

        /* TENTANG KAMI */
        .tentang-box {
            max-width: 900px;
            margin: auto;
            padding: 25px;
            border-radius: 16px;
            background: rgba(255,192,220,.91);
            text-align: center;
            line-height: 1.8;
        }

        /* LAYANAN */
        .layanan-container {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            align-items: stretch;
        }

        .layanan-card {
            background: rgba(255,192,220,.94);
            padding: 20px 15px;
            border-radius: 14px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(100,40,70,.12);
        }

        .layanan-card h3 {
            color: #9b3866;
            margin: 0 0 10px;
        }

        .layanan-card p {
            font-size: 14px;
            line-height: 1.6;
        }

        .harga {
            display: block;
            color: #d63384;
            font-size: 16px;
            font-weight: bold;
            background: rgba(255,255,255,.65);
            padding: 9px;
            border-radius: 8px;
            margin: 12px 0;
        }

        /* GALERI */
        .galeri-container {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .galeri-card {
            background: rgba(255,192,220,.93);
            padding: 10px;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(100,40,70,.12);
        }

        .galeri-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            display: block;
            border-radius: 10px;
        }

        /* KONTAK */
        .kontak-container {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            max-width: 950px;
            margin: auto;
        }

        .kontak-card {
            padding: 22px;
            border-radius: 14px;
            background: rgba(255,192,220,.93);
            text-align: center;
            line-height: 1.7;
        }

        .kontak-card h3 {
            color: #9b3866;
        }

        /* FOOTER */
        footer {
            background: rgba(155,56,102,.93);
            color: white;
            text-align: center;
            padding: 18px;
            margin-top: 20px;
        }

        /* TABLET */
        @media (max-width: 900px) {
            .layanan-container,
            .galeri-container {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        /* HP */
        @media (max-width: 600px) {
            header {
                justify-content: center;
                text-align: center;
            }

            nav {
                justify-content: center;
                gap: 12px;
            }

            nav a {
                font-size: 12px;
            }

            .hero h1 {
                font-size: 26px;
            }

            .beranda-container,
            .layanan-container,
            .galeri-container,
            .kontak-container {
                grid-template-columns: 1fr;
            }

            .beranda-card img,
            .galeri-card img {
                height: 220px;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <header>
        <div class="logo">Salon Nadira Beauty</div>

        <nav>
            <a href="#beranda">Beranda</a>
            <a href="#tentang">Tentang Kami</a>
            <a href="#layanan">Layanan</a>
            <a href="#galeri">Galeri</a>
            <a href="#kontak">Kontak</a>
            <a href="login.php">Login Admin</a>
        </nav>
    </header>

    <!-- BERANDA -->
    <section class="hero" id="beranda">
        <div class="hero-content">
            <h1>Selamat Datang di Salon Nadira Beauty</h1>

            <p>
                Tampil cantik, segar, dan percaya diri bersama Salon Nadira Beauty.
                Kami menyediakan berbagai layanan kecantikan untuk membantu
                Anda mendapatkan penampilan terbaik.
            </p>

            <a href="views/reservasi.php" class="btn">
                Reservasi Sekarang
            </a>
        </div>
    </section>

    <!-- GAMBAR BERANDA -->
    <section>
        <div class="beranda-container">

            <div class="beranda-card">
                <img src="image/make up wisuda.jpg" alt="Make Up Wisuda">
                <h3>Make Up Wisuda</h3>
                <p>
                    Layanan tata rias khusus wisuda untuk membuat penampilan
                    lebih cantik, anggun, dan percaya diri di hari istimewa.
                </p>
            </div>

            <div class="beranda-card">
                <img src="image/perawatan rambut.jpg" alt="Perawatan Rambut">
                <h3>Perawatan Rambut</h3>
                <p>
                    Perawatan rambut untuk membantu menjaga rambut tetap
                    sehat, lembut, terawat, dan terlihat indah.
                </p>
            </div>

            <div class="beranda-card">
                <img src="image/perawatan wajah.jpg" alt="Perawatan Wajah">
                <h3>Perawatan Wajah</h3>
                <p>
                    Perawatan wajah untuk membantu membersihkan dan merawat
                    kulit agar terasa lebih segar dan bersih.
                </p>
            </div>

            <div class="beranda-card">
                <img src="image/makeup.jpg" alt="Make Up Profesional">
                <h3>Make Up Profesional</h3>
                <p>
                    Tampil lebih cantik dan menawan dengan sentuhan make up
                    yang elegan. Riasan disesuaikan dengan karakter wajah
                    dan kebutuhan acara agar kamu semakin percaya diri.
        </p>
        </div>

            </div>

        </div>
    </section>

    <!-- TENTANG KAMI -->
    <section id="tentang">
        <h2 class="judul">Tentang Kami</h2>

        <div class="tentang-box">
            <p>
                Salon Nadira Beauty merupakan salon kecantikan yang menyediakan
                berbagai layanan untuk membantu pelanggan tampil lebih cantik,
                sehat, dan percaya diri.
            </p>

            <p>
                Kami berkomitmen memberikan pelayanan yang ramah dan nyaman
                dengan memperhatikan kebutuhan setiap pelanggan.
            </p>
        </div>
    </section>

    <!-- LAYANAN -->
    <section id="layanan">
        <h2 class="judul">Layanan Kami</h2>

        <div class="layanan-container">

            <?php if ($query_layanan && mysqli_num_rows($query_layanan) > 0): ?>

                <?php while ($layanan = mysqli_fetch_assoc($query_layanan)): ?>

                    <div class="layanan-card">
                        <h3>
                            <?= htmlspecialchars($layanan['nama_layanan']); ?>
                        </h3>

                        <p>
                            <?= nl2br(htmlspecialchars($layanan['deskripsi'])); ?>
                        </p>

                        <div class="harga">
                            Harga: Rp <?= number_format((float)$layanan['harga'], 0, ',', '.'); ?>
                        </div>

                        <a
                            href="views/reservasi.php?id_layanan=<?= (int)$layanan['id_layanan']; ?>"
                            class="btn"
                        >
                            Reservasi
                        </a>
                    </div>

                <?php endwhile; ?>

            <?php else: ?>

                <div class="layanan-card">
                    <h3>Belum Ada Layanan</h3>
                    <p>Silakan cek kembali layanan kami nanti.</p>
                </div>

            <?php endif; ?>

        </div>
    </section>

    <!-- GALERI -->
    <section id="galeri">
        <h2 class="judul">Galeri Salon</h2>

        <div class="galeri-container">

            <div class="galeri-card">
                <img src="image/galeri salon 1.jpeg" alt="Galeri Salon 1">
            </div>

            <div class="galeri-card">
                <img src="image/galeri salon 2.jpeg" alt="Galeri Salon 2">
            </div>

            <div class="galeri-card">
                <img src="image/galeri salon 3.jpeg" alt="Galeri Salon 3">
            </div>

            <div class="galeri-card">
                <img src="image/galeri 4.jpeg" alt="Galeri Salon 4">
            </div>

            <div class="galeri-card">
                <img src="image/galeri 5.jpeg" alt="Galeri Salon 5">
            </div>

            <div class="galeri-card">
                <img src="image/galeri 6.jpeg" alt="Galeri Salon 6">
            </div>

        </div>
    </section>

    <!-- KONTAK -->
    <section id="kontak">
        <h2 class="judul">Hubungi Kami</h2>

        <div class="kontak-container">

            <div class="kontak-card">
                <h3>WhatsApp</h3>
                <p>
                    Hubungi kami untuk bertanya mengenai layanan
                    dan melakukan reservasi.
                </p>

                <a
                    href="https://wa.me/6285168716705"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn"
                >
                    Hubungi WhatsApp
                </a>
            </div>

            <div class="kontak-card">
                <h3>Alamat Salon</h3>
                <p>
                    Jl. K.H. Ahmad Razak, Tompotika, Kec. Wara,
                    Kota Palopo, Sulawesi Selatan 91922.
                </p>

                <a
                    href="https://www.google.com/maps/search/?api=1&query=Jl.+K.H.+Ahmad+Razak%2C+Tompotika%2C+Kec.+Wara%2C+Kota+Palopo%2C+Sulawesi+Selatan+91922"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn"
                >
                    Lihat Lokasi
                </a>
            </div>

        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <p>
            &copy; <?= date('Y'); ?> Salon Nadira Beauty.
            Semua Hak Dilindungi.
        </p>
    </footer>

</body>
</html>
