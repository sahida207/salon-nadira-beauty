<?php
session_start();

// Memeriksa login admin
if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit;
}

// Menghubungkan database
require_once __DIR__ . '/../config/databases.php';

// Menghitung total data
function hitungTotal($conn, $sql) {
    $query = mysqli_query($conn, $sql);

    if (!$query) {
        die("Gagal mengambil data: " . mysqli_error($conn));
    }

    $data = mysqli_fetch_assoc($query);
    mysqli_free_result($query);

    return (int) ($data["total"] ?? 0);
}

$total_reservasi = hitungTotal(
    $conn,
    "SELECT COUNT(*) AS total FROM reservasi"
);

$total_layanan = hitungTotal(
    $conn,
    "SELECT COUNT(*) AS total FROM layanan"
);

$total_menunggu = hitungTotal(
    $conn,
    "SELECT COUNT(*) AS total FROM reservasi
     WHERE status = 'Menunggu'"
);

$total_konfirmasi = hitungTotal(
    $conn,
    "SELECT COUNT(*) AS total FROM reservasi
     WHERE status = 'Dikonfirmasi'"
);

// Mengambil banyak reservasi terbaru
$query_terbaru = mysqli_query(
    $conn,
    "SELECT
        reservasi.id_reservasi,
        reservasi.nama_pelanggan,
        reservasi.tanggal_reservasi,
        reservasi.jam_booking,
        reservasi.status,
        layanan.nama_layanan
     FROM reservasi
     LEFT JOIN layanan
     ON reservasi.id_layanan = layanan.id_layanan
     ORDER BY reservasi.id_reservasi DESC
     LIMIT 5"
);

if (!$query_terbaru) {
    die("Gagal mengambil reservasi terbaru: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - Salon Nadira Beauty</title>

    <link rel="icon" type="image/png" href="../image/favicon.png">
    <link rel="stylesheet" href="../style.css">

    <style>
        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            color: #78334f;

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
        }

        header {
            width: 100%;
            box-sizing: border-box;
            text-align: center;
            padding: 22px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        header h1 {
            color: #a13f6d;
            margin: 0 0 8px;
            text-align: center;
        }

        header p {
            margin: 0;
            text-align: center;
        }

        .dashboard {
            width: 100%;
            max-width: 1100px;
            margin: 20px auto;
            padding: 20px;
            box-sizing: border-box;
        }

        .dashboard h2,
        .dashboard > p {
            text-align: center;
        }

        .dashboard h2 {
            color: #92375f;
        }

        /* KARTU STATISTIK */
        .kartu-container {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 20px;
            margin: 30px auto;
        }

        .kartu {
            background: white;
            padding: 25px 12px;
            min-width: 0;
            border-radius: 12px;
            border-top: 5px solid #e263a0;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            text-align: center;
        }

        .kartu h3 {
            margin: 0 0 10px;
            color: #7a3153;
            font-size: 17px;
        }

        .angka {
            font-size: 35px;
            font-weight: bold;
            color: #c0447b;
        }

        /* MENU ADMIN */
        .menu-admin {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 12px;
            margin: 25px 0;
        }

        .menu-admin a {
            display: inline-block;
            background: #a83267;
            color: white;
            padding: 12px 18px;
            text-decoration: none;
            border-radius: 7px;
        }

        .menu-admin a:hover {
            background: #842650;
        }

        /* TABEL RESERVASI */
        .bagian-terbaru {
            width: 100%;
            overflow-x: auto;
            margin-top: 30px;
            background: white;
            padding: 18px;
            border-radius: 12px;
            box-sizing: border-box;
        }

        .tabel-dashboard {
            width: 100%;
            min-width: 700px;
            border-collapse: collapse;
            background: white;
        }

        .tabel-dashboard th,
        .tabel-dashboard td {
            border: 1px solid #e8c6d5;
            padding: 12px;
            text-align: left;
        }

        .tabel-dashboard th {
            background: #f8d7e5;
            color: #78334f;
        }

        /* TABLET */
        @media (max-width: 900px) {
            .kartu-container {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        /* HP */
        @media (max-width: 480px) {
            .dashboard {
                padding: 12px;
            }

            .kartu-container {
                gap: 12px;
            }

            .kartu {
                padding: 20px 8px;
            }

            .kartu h3 {
                font-size: 14px;
            }

            .angka {
                font-size: 29px;
            }
        }
    </style>
</head>

<body>

<header>
    <h1>Salon Nadira Beauty</h1>
    <p>Dashboard Admin</p>
</header>

<main class="dashboard">

    <h2>Selamat Datang di Dashboard Admin</h2>

    <p>
        Kelola reservasi pelanggan dan layanan
        Salon Nadira Beauty melalui halaman ini.
    </p>

    <!-- STATISTIK DATABASE -->
    <div class="kartu-container">

        <div class="kartu">
            <h3>Total Reservasi</h3>
            <div class="angka">
                <?= $total_reservasi ?>
            </div>
        </div>

        <div class="kartu">
            <h3>Total Layanan</h3>
            <div class="angka">
                <?= $total_layanan ?>
            </div>
        </div>

        <div class="kartu">
            <h3>Menunggu</h3>
            <div class="angka">
                <?= $total_menunggu ?>
            </div>
        </div>

        <div class="kartu">
            <h3>Dikonfirmasi</h3>
            <div class="angka">
                <?= $total_konfirmasi ?>
            </div>
        </div>

    </div>

    <!-- MENU PENGELOLAAN -->
    <h2>Menu Pengelolaan</h2>

    <div class="menu-admin">

        <a href="admin_reservasi.php">
            Data Reservasi
        </a>

        <a href="admin_layanan.php">
            Data Layanan
        </a>

        <a href="laporan_reservasi.php">
            Laporan Reservasi
        </a>

        <a href="../index.php">
            Kembali ke Beranda
        </a>

        <a href="../logout.php"
           onclick="return confirm('Yakin ingin logout?');">
            Logout
        </a>

    </div>

    <!-- RESERVASI TERBARU -->
    <div class="bagian-terbaru">

        <h2>Reservasi Terbaru</h2>

        <table class="tabel-dashboard">

            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Pelanggan</th>
                    <th>Layanan</th>
                    <th>Tanggal Reservasi</th>
                    <th>Jam Booking</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                <?php if (mysqli_num_rows($query_terbaru) > 0): ?>

                    <?php $nomor = 1; ?>

                    <?php while ($row = mysqli_fetch_assoc($query_terbaru)): ?>

                        <tr>
                            <td><?= $nomor++ ?></td>

                            <td>
                                <?= htmlspecialchars($row["nama_pelanggan"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $row["nama_layanan"] ?? "Tidak tersedia"
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row["tanggal_reservasi"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row["jam_booking"] ?? "-") ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row["status"]) ?>
                            </td>
                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="6" style="text-align:center;">
                            Belum ada reservasi.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</main>

<?php
mysqli_free_result($query_terbaru);
mysqli_close($conn);
?>

</body>
</html>