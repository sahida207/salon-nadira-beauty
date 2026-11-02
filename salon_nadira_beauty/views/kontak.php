
<?php
$nomor_whatsapp = '6285168716705';
$alamat = 'Jl. K.H. Ahmad Razak, Tompotika, Kec. Wara, Kota Palopo, Sulawesi Selatan 91922';

$link_whatsapp = 'https://wa.me/' . $nomor_whatsapp;
$link_maps = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($alamat);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kontak - Salon Nadira Beauty</title>

    <link rel="stylesheet" href="../style.css">

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
                linear-gradient(rgba(255, 230, 240, 0.35), rgba(255, 230, 240, 0.35)),
                url("../image/background salon nadira beauty.jpeg");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        header {
            background: rgba(255, 192, 220, 0.92);
            padding: 18px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
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

        .container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .judul {
            text-align: center;
            color: #9b3866;
            font-size: 32px;
            margin-bottom: 15px;
        }

        .deskripsi {
            text-align: center;
            line-height: 1.8;
            margin-bottom: 30px;
        }

        .kontak-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 22px;
        }

        .kontak-card {
            background: rgba(255, 192, 220, 0.85);
            backdrop-filter: blur(5px);
            padding: 30px 25px;
            border-radius: 18px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(100, 40, 70, 0.12);
            line-height: 1.8;
        }

        .kontak-card h2 {
            color: #9b3866;
            margin-top: 0;
        }

        .kontak-card p {
            overflow-wrap: anywhere;
        }

        .btn {
            display: inline-block;
            margin-top: 15px;
            padding: 12px 23px;
            background: #d63384;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn:hover {
            background: #a82061;
            transform: translateY(-2px);
        }

        .kembali {
            display: block;
            width: fit-content;
            margin: 30px auto;
            padding: 12px 23px;
            border: 2px solid #d63384;
            color: #d63384;
            background: rgba(255, 255, 255, 0.85);
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
        }

        .kembali:hover {
            background: #d63384;
            color: white;
        }

        footer {
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

<main class="container">

    <h1 class="judul">Hubungi Kami</h1>

    <p class="deskripsi">
        Terima kasih telah mengunjungi Salon Nadira Beauty.
        Silakan hubungi kami untuk informasi layanan, harga,
        dan reservasi perawatan kecantikan.
    </p>

    <div class="kontak-container">

        <div class="kontak-card">
            <h2>WhatsApp</h2>

            <p>
                Hubungi kami melalui WhatsApp untuk bertanya
                mengenai layanan dan melakukan reservasi.
            </p>

            <p>0851-6871-6705</p>

            <a
                href="<?= htmlspecialchars($link_whatsapp, ENT_QUOTES, 'UTF-8'); ?>"
                target="_blank"
                rel="noopener noreferrer"
                class="btn"
            >
                Hubungi WhatsApp
            </a>
        </div>

        <div class="kontak-card">
            <h2>Alamat Salon</h2>

            <p>
                <?= htmlspecialchars($alamat, ENT_QUOTES, 'UTF-8'); ?>
            </p>

            <a
                href="<?= htmlspecialchars($link_maps, ENT_QUOTES, 'UTF-8'); ?>"
                target="_blank"
                rel="noopener noreferrer"
                class="btn"
            >
                Lihat Lokasi
            </a>
        </div>

    </div>

    <a href="../index.php" class="kembali">
        Kembali ke Beranda
    </a>

</main>

<footer>
    <p>
        &copy; <?= date('Y'); ?> Salon Nadira Beauty.
        Semua Hak Dilindungi.
    </p>
</footer>

</body>
</html>
