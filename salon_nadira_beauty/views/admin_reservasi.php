
<?php
session_start();
require_once '../config/databases.php';

// Cek login admin
if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit;
}

// Proses perubahan status reservasi
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ubah_status"])) {
    $id_reservasi = (int) ($_POST["id_reservasi"] ?? 0);
    $status = $_POST["status"] ?? "";

    $status_valid = [
        "Menunggu",
        "Dikonfirmasi",
        "Selesai",
        "Dibatalkan"
    ];

    if ($id_reservasi > 0 && in_array($status, $status_valid, true)) {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE reservasi SET status = ? WHERE id_reservasi = ?"
        );

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "si", $status, $id_reservasi);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    header("Location: admin_reservasi.php");
    exit;
}

// Mengamankan output
function aman($data) {
    return htmlspecialchars((string) $data, ENT_QUOTES, 'UTF-8');
}

// Mengubah nomor menjadi format WhatsApp Indonesia
function formatWhatsApp($nomor) {
    $nomor = preg_replace('/\D/', '', (string) $nomor);

    if (substr($nomor, 0, 1) === "0") {
        $nomor = "62" . substr($nomor, 1);
    } elseif (substr($nomor, 0, 1) === "8") {
        $nomor = "62" . $nomor;
    }

    return $nomor;
}

// Mengambil data reservasi
$query = "
    SELECT
        reservasi.*,
        layanan.nama_layanan,
        layanan.harga
    FROM reservasi
    LEFT JOIN layanan
        ON reservasi.id_layanan = layanan.id_layanan
    ORDER BY reservasi.id_reservasi DESC
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Gagal mengambil data reservasi: " . aman(mysqli_error($conn)));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Reservasi - Salon Nadira Beauty</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            padding: 25px;
            background: #fff0f6;
            color: #63334a;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(100, 40, 70, 0.12);
        }

        h1 {
            text-align: center;
            color: #a13f6d;
            margin-bottom: 10px;
        }

        .deskripsi {
            text-align: center;
            color: #777;
            margin-bottom: 25px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 950px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #f0c5d8;
            text-align: center;
            font-size: 14px;
        }

        th {
            background: #d63384;
            color: white;
        }

        tr:nth-child(even) {
            background: #fff5f9;
        }

        .status-form {
            display: flex;
            flex-direction: column;
            gap: 7px;
            min-width: 120px;
        }

        select {
            padding: 8px;
            border: 1px solid #e8a5c5;
            border-radius: 6px;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            border: none;
            border-radius: 7px;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-simpan {
            background: #d63384;
            color: white;
        }

        .btn-simpan:hover {
            background: #a82061;
        }

        .btn-wa {
            display: inline-block;
            margin-top: 7px;
            padding: 9px 12px;
            background: #25D366;
            color: white;
            border-radius: 7px;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
            white-space: nowrap;
        }

        .btn-wa:hover {
            background: #1da851;
        }

        .btn-kembali {
            display: inline-block;
            margin-top: 25px;
            padding: 11px 18px;
            background: #a13f6d;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }

        .btn-kembali:hover {
            background: #812d55;
        }

        .kosong {
            text-align: center;
            padding: 25px;
            color: #888;
        }

        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .container {
                padding: 15px;
            }

            h1 {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Kelola Reservasi</h1>

    <p class="deskripsi">
        Data reservasi pelanggan Salon Nadira Beauty
    </p>

    <div class="table-wrapper">

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pelanggan</th>
                    <th>WhatsApp</th>
                    <th>Layanan</th>
                    <th>Harga</th>
                    <th>Tanggal Reservasi</th>
                    <th>Pesan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

            <?php if (mysqli_num_rows($result) > 0): ?>

                <?php $no = 1; ?>

                <?php while ($data = mysqli_fetch_assoc($result)): ?>

                    <?php
                    $nomor_wa = formatWhatsApp($data["no_whatsapp"]);

                    $pesan_wa = "Halo " . $data["nama_pelanggan"] .
                        ", kami dari Salon Nadira Beauty ingin menghubungi Anda terkait reservasi layanan " .
                        ($data["nama_layanan"] ?? "salon") .
                        " pada tanggal " . $data["tanggal_reservasi"] . ".";

                    $link_wa = "https://wa.me/" . $nomor_wa .
                        "?text=" . urlencode($pesan_wa);
                    ?>

                    <tr>
                        <td><?= $no++; ?></td>

                        <td>
                            <?= aman($data["nama_pelanggan"]); ?>
                        </td>

                        <td>
                            <?= aman($data["no_whatsapp"]); ?>

                            <?php if ($nomor_wa !== ""): ?>
                                <br>

                                <a
                                    href="<?= aman($link_wa); ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn-wa"
                                >
                                    Chat WhatsApp
                                </a>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?= aman($data["nama_layanan"] ?? "Layanan tidak tersedia"); ?>
                        </td>

                        <td>
                            Rp <?= number_format((float)($data["harga"] ?? 0), 0, ',', '.'); ?>
                        </td>

                        <td>
                            <?= aman($data["tanggal_reservasi"]); ?>
                        </td>

                        <td>
                            <?= aman($data["pesan"]); ?>
                        </td>

                        <td>
                            <?= aman($data["status"]); ?>
                        </td>

                        <td>
                            <form
                                method="POST"
                                action=""
                                class="status-form"
                            >
                                <input
                                    type="hidden"
                                    name="id_reservasi"
                                    value="<?= (int)$data["id_reservasi"]; ?>"
                                >

                                <select name="status" required>
                                    <?php
                                    $daftar_status = [
                                        "Menunggu",
                                        "Dikonfirmasi",
                                        "Selesai",
                                        "Dibatalkan"
                                    ];
                                    ?>

                                    <?php foreach ($daftar_status as $pilihan): ?>
                                        <option
                                            value="<?= aman($pilihan); ?>"
                                            <?= ($data["status"] === $pilihan) ? "selected" : ""; ?>
                                        >
                                            <?= aman($pilihan); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <button
                                    type="submit"
                                    name="ubah_status"
                                    class="btn btn-simpan"
                                >
                                    Simpan Status
                                </button>
                            </form>
                        </td>
                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="9" class="kosong">
                        Belum ada data reservasi.
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>
        </table>

    </div>

    <a href="dashboard_admin.php" class="btn-kembali">
        &larr; Kembali ke Dashboard
    </a>

</div>

</body>
</html>

<?php
mysqli_free_result($result);
mysqli_close($conn);
?>
