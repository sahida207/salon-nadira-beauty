
<?php
session_start();
require_once "../config/databases.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - Salon Nadira Beauty</title>
    <link rel="stylesheet" href="../style.css">

    <style>
        .tentang-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 35px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 18px;
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.12);
        }

        .tentang-container h2 {
            text-align: center;
            color: #a74470;
            margin-bottom: 25px;
        }

        .tentang-container h3 {
            color: #a74470;
            margin-top: 25px;
            margin-bottom: 10px;
        }

        .tentang-container p {
            text-align: justify;
            line-height: 1.9;
            margin-bottom: 15px;
            color: #444;
        }

        .tentang-highlight {
            background: #fff0f6;
            border-left: 5px solid #e99abd;
            padding: 18px;
            margin: 25px 0;
            border-radius: 8px;
        }

        .kembali {
            display: block;
            width: fit-content;
            margin: 30px auto 0;
            padding: 11px 25px;
            background: #d96e9e;
            color: white;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
        }

        .kembali:hover {
            background: #ad4775;
        }

        @media (max-width: 600px) {
            .tentang-container {
                margin: 20px 12px;
                padding: 22px;
            }
        }
    </style>
</head>

<body>

<header>
    <div class="navbar">
        <h2>Salon Nadira Beauty</h2>

        <nav>
            <a href="../index.php#beranda">Beranda</a>
            <a href="../index.php#tentang">Tentang Kami</a>
            <a href="../index.php#layanan">Layanan</a>
            <a href="../index.php#galeri">Galeri</a>
            <a href="../index.php#kontak">Kontak</a>
            <a href="../login.php">Login Admin</a>
        </nav>
    </div>
</header>

<main>
    <div class="tentang-container">

        <h2>Tentang Salon Nadira Beauty</h2>

        <p>
            Salon Nadira Beauty merupakan usaha yang bergerak di bidang
            jasa kecantikan dan perawatan diri. Kami hadir untuk memberikan
            pelayanan kecantikan bagi pelanggan yang ingin merawat diri,
            menjaga penampilan, serta meningkatkan rasa percaya diri.
            Kami percaya bahwa setiap orang memiliki kecantikan dan
            keunikan tersendiri yang dapat ditampilkan melalui perawatan
            yang tepat.
        </p>

        <p>
            Salon Nadira Beauty menyediakan berbagai pilihan layanan
            kecantikan, mulai dari tata rias wajah hingga perawatan rambut
            dan wajah. Setiap layanan ditujukan untuk membantu pelanggan
            mendapatkan penampilan yang sesuai dengan kebutuhan dan
            keinginan masing-masing. Kami juga berusaha memberikan
            pelayanan yang ramah agar pelanggan merasa nyaman selama
            melakukan perawatan.
        </p>

        <div class="tentang-highlight">
            <p>
                Bagi kami, kecantikan bukan hanya tentang penampilan luar,
                tetapi juga tentang bagaimana seseorang merasa nyaman,
                percaya diri, dan bahagia dengan dirinya sendiri.
                Karena itu, kami ingin menjadikan pengalaman perawatan
                di Salon Nadira Beauty sebagai pengalaman yang
                menyenangkan bagi setiap pelanggan.
            </p>
        </div>

        <h3>Pelayanan dan Perawatan Kecantikan</h3>

        <p>
            Kami menyediakan layanan make up untuk berbagai kebutuhan,
            termasuk make up wisuda dan acara tertentu. Selain itu,
            tersedia pula layanan perawatan rambut untuk membantu
            menjaga keindahan dan kesehatan rambut, serta perawatan
            wajah sebagai bagian dari kegiatan merawat diri.
        </p>

        <p>
            Kami memahami bahwa setiap pelanggan memiliki kebutuhan
            yang berbeda. Oleh karena itu, pelanggan dapat memilih
            layanan yang tersedia sesuai dengan keperluan dan
            kenyamanan masing-masing. Kami berusaha memberikan
            pelayanan dengan penuh perhatian agar pelanggan
            mendapatkan pengalaman yang baik.
        </p>

        <h3>Kenyamanan Pelanggan</h3>

        <p>
            Kepuasan dan kenyamanan pelanggan merupakan hal yang
            penting bagi Salon Nadira Beauty. Kami berusaha menciptakan
            suasana salon yang nyaman, memberikan pelayanan yang
            bersahabat, serta memperhatikan kebutuhan pelanggan
            selama proses perawatan berlangsung.
        </p>

        <p>
            Kami juga terbuka terhadap saran dan masukan dari pelanggan
            sebagai bahan untuk meningkatkan kualitas pelayanan.
            Dengan begitu, kami dapat terus belajar dan mengembangkan
            layanan agar semakin sesuai dengan harapan pelanggan.
        </p>

        <h3>Website Salon Nadira Beauty</h3>

        <p>
            Website Salon Nadira Beauty dibuat sebagai media informasi
            yang memudahkan pelanggan mengenal salon dan mengetahui
            layanan kecantikan yang tersedia. Melalui website ini,
            pelanggan dapat melihat informasi layanan, mengetahui
            gambaran hasil perawatan melalui galeri foto, serta
            memperoleh informasi kontak salon.
        </p>

        <p>
            Website ini juga menyediakan fitur reservasi online
            sehingga pelanggan dapat mengajukan pemesanan layanan
            tanpa harus datang langsung hanya untuk melakukan
            pendaftaran. Pelanggan cukup mengisi data reservasi
            melalui formulir yang tersedia, kemudian menunggu
            konfirmasi dari pihak salon.
        </p>

        <p>
            Selain memberikan kemudahan bagi pelanggan, website ini
            juga membantu pengelolaan informasi salon agar lebih
            teratur. Informasi layanan dan data reservasi dapat
            dikelola melalui halaman admin sehingga proses
            pengelolaan salon menjadi lebih praktis.
        </p>

        <h3>Komitmen Kami</h3>

        <p>
            Salon Nadira Beauty akan terus berusaha memberikan
            pelayanan yang baik dan mengembangkan layanan kecantikan
            sesuai dengan kebutuhan pelanggan. Kami berharap
            kehadiran salon ini dapat menjadi salah satu pilihan
            bagi masyarakat yang membutuhkan layanan perawatan
            dan tata rias kecantikan.
        </p>

        <p>
            Terima kasih telah mengenal dan mempercayai
            Salon Nadira Beauty. Kami senang dapat menjadi bagian
            dari perjalanan pelanggan dalam merawat diri dan
            menampilkan penampilan terbaiknya.
        </p>

        <a href="../index.php" class="kembali">Kembali ke Beranda</a>

    </div>
</main>

<footer>
    <p>&copy; 2026 Salon Nadira Beauty. All Rights Reserved.</p>
</footer>

</body>
</html>
