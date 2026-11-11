
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Galeri - Salon Nadira Beauty</title>

    <link rel="stylesheet" href="../style.css">

    <style>
        /* BACKGROUND HALAMAN GALERI */
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

        /* JUDUL */
        main h2 {
            text-align: center;
            color: #d63384;
            margin-top: 30px;
            margin-bottom: 15px;
        }

        main > p {
            text-align: center;
            color: #78334f;
            margin-bottom: 20px;
        }

        /* GALERI */
        .galeri {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
            gap: 20px;
            padding: 25px 15px;
            max-width: 1000px;
            margin: 0 auto;
        }

        /* KARTU GALERI */
        .galeri .galeri-item {
            width: 180px;
            max-width: 180px;
            flex: 0 0 180px;
            box-sizing: border-box;
            padding: 10px;
            background: rgba(255, 240, 245, 0.95);
            border-radius: 12px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        /* FOTO GALERI */
        .galeri .galeri-item img {
            display: block;
            width: 100%;
            height: 130px;
            object-fit: contain;
            border-radius: 8px;
            margin: 0 auto;
        }

        /* KETERANGAN FOTO */
        .galeri .galeri-item p {
            color: #8b3a62;
            font-weight: bold;
            font-size: 13px;
            margin: 10px 0 0;
        }

        /* RESPONSIVE HP */
        @media (max-width: 450px) {
            body {
                background-attachment: scroll;
            }

            .galeri {
                gap: 12px;
                padding: 15px 8px;
            }

            .galeri .galeri-item {
                width: 150px;
                max-width: 150px;
                flex-basis: 150px;
            }

            .galeri .galeri-item img {
                height: 110px;
            }
        }
    </style>
</head>

<body>

<header>
    <h1>Salon Nadira Beauty</h1>
    <p>Galeri Dokumentasi</p>
</header>

<nav>
    <a href="../index.php">Beranda</a>
    <a href="tentang.php">Tentang Kami</a>
    <a href="layanan.php">Layanan</a>
    <a href="galeri.php">Galeri</a>
    <a href="kontak.php">Kontak</a>
</nav>

<main>

    <h2>Galeri Salon</h2>

    <p>
        Dokumentasi kegiatan dan suasana Salon Nadira Beauty.
    </p>

    <div class="galeri">

        <div class="galeri-item">
            <img src="../image/galeri salon 1.jpeg"
                 alt="Galeri Salon 1">
            <p>Dokumentasi Salon Nadira Beauty</p>
        </div>

        <div class="galeri-item">
            <img src="../image/galeri salon 2.jpeg"
                 alt="Galeri Salon 2">
            <p>Dokumentasi Salon Nadira Beauty</p>
        </div>

        <div class="galeri-item">
            <img src="../image/galeri salon 3.jpeg"
                 alt="Galeri Salon 3">
            <p>Dokumentasi Salon Nadira Beauty</p>
        </div>

        <div class="galeri-item">
            <img src="../image/galeri 4.jpeg"
                 alt="Galeri Salon 4">
            <p>Dokumentasi Salon Nadira Beauty</p>
        </div>

        <div class="galeri-item">
            <img src="../image/galeri 5.jpeg"
                 alt="Galeri Salon 5">
            <p>Dokumentasi Salon Nadira Beauty</p>
        </div>

        <div class="galeri-item">
            <img src="../image/galeri 6.jpeg"
                 alt="Galeri Salon 6">
            <p>Dokumentasi Salon Nadira Beauty</p>
        </div>

    </div>

</main>

<footer>
    <p>&copy; 2026 Salon Nadira Beauty</p>
</footer>

</body>
</html>
