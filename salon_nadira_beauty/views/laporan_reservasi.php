
<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config/databases.php";

function aman($data) {
    return htmlspecialchars((string) $data, ENT_QUOTES, "UTF-8");
}

// Mengambil filter
$tanggal_mulai = $_GET["tanggal_mulai"] ?? "";
$tanggal_selesai = $_GET["tanggal_selesai"] ?? "";
$status = $_GET["status"] ?? "";

$pesan_error = "";

// Validasi tanggal
function tanggalValid($tanggal) {
    if ($tanggal === "") {
        return true;
    }

    $date = DateTime::createFromFormat("Y-m-d", $tanggal);

    return $date && $date->format("Y-m-d") === $tanggal;
}

if (!tanggalValid($tanggal_mulai) || !tanggalValid($tanggal_selesai)) {
    $pesan_error = "Format tanggal tidak valid.";
    $tanggal_mulai = "";
    $tanggal_selesai = "";
}

if (
    $tanggal_mulai !== "" &&
    $tanggal_selesai !== "" &&
    $tanggal_mulai > $tanggal_selesai
) {
    $pesan_error = "Tanggal mulai tidak boleh melebihi tanggal selesai.";
    $tanggal_mulai = "";
    $tanggal_selesai = "";
}

// Validasi status
$status_valid = [
    "",
    "Menunggu",
    "Dikonfirmasi",
    "Selesai",
    "Dibatalkan"
];

if (!in_array($status, $status_valid, true)) {
    $status = "";
}

// Query laporan
$sql = "
    SELECT
        reservasi.id_reservasi,
        reservasi.nama_pelanggan,
        reservasi.no_whatsapp,
        reservasi.tanggal_reservasi,
        reservasi.pesan,
        reservasi.status,
        layanan.nama_layanan,
        layanan.harga
    FROM reservasi
    LEFT JOIN layanan
        ON reservasi.id_layanan = layanan.id_layanan
    WHERE
        (? = '' OR reservasi.tanggal_reservasi >= ?)
        AND (? = '' OR reservasi.tanggal_reservasi <= ?)
        AND (? = '' OR reservasi.status = ?)
    ORDER BY
        reservasi.tanggal_reservasi DESC,
        reservasi.id_reservasi DESC
";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Query laporan gagal: " . mysqli_error($conn));
}

mysqli_stmt_bind_param(
    $stmt,
    "ssssss",
    $tanggal_mulai,
    $tanggal_mulai,
    $tanggal_selesai,
    $tanggal_selesai,
    $status,
    $status
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

// Menyimpan data reservasi
$data_reservasi = [];

$total_reservasi = 0;
$total_menunggu = 0;
$total_dikonfirmasi = 0;
$total_selesai = 0;
$total_dibatalkan = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $data_reservasi[] = $row;
    $total_reservasi++;

    if ($row["status"] === "Menunggu") {
        $total_menunggu++;
    } elseif ($row["status"] === "Dikonfirmasi") {
        $total_dikonfirmasi++;
    } elseif ($row["status"] === "Selesai") {
        $total_selesai++;
    } elseif ($row["status"] === "Dibatalkan") {
        $total_dibatalkan++;
    }
}

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laporan Reservasi - Salon Nadira Beauty</title>

    <link rel="stylesheet" href="../style.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;

            background:
                linear-gradient(
                    rgba(255, 240, 245, 0.92),
                    rgba(255, 240, 245, 0.92)
                ),
                url("../image/background salon nadira beauty.jpeg");

            background-size: cover;
            background-position: center;
            background-attachment: fixed;

            color: #633b4d;
        }

        /* CONTAINER UTAMA */

        .laporan-container {
            width: 95%;
            max-width: 1250px;
            margin: 25px auto;
        }

        /* JUDUL DAN MENU */

        .laporan-top {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 40px;
        }

        .laporan-header {
            width: 310px;
            min-width: 310px;
            padding: 17px 20px;
            background: #b85c83;
            color: white;
            border-radius: 15px;
            text-align: center;
        }

        .laporan-header h1 {
            margin: 0 0 7px;
            font-size: 27px;
            white-space: nowrap;
        }

        .laporan-header p {
            margin: 0;
            font-size: 14px;
        }

        /* MENU NAVIGASI */

        .laporan-menu {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .laporan-menu a {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            background: #b85c83;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            min-height: 42px;
            border-radius: 8px;
            font-size: 13px;
            white-space: nowrap;
            transition: 0.3s;
        }

        .laporan-menu a:hover {
            background: #914365;
            transform: translateY(-2px);
        }

        /* FILTER DAN RINGKASAN */

        .laporan-content {
            width: 100%;
            max-width: 800px;
            margin: 0 auto 30px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            align-items: stretch;
        }

        .laporan-panel {
            background: rgba(255, 255, 255, 0.96);
            padding: 22px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .laporan-panel h2 {
            margin: 0 0 14px;
            color: #a34470;
            font-size: 22px;
        }

        /* FILTER */

        .laporan-filter-group {
            margin-bottom: 22px;
        }

        .laporan-filter-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .laporan-filter-group input,
        .laporan-filter-group select {
            width: 100%;
            padding: 11px;
            border: 1px solid #ddb3c5;
            border-radius: 8px;
            background: white;
            color: #633b4d;
            font-size: 14px;
        }

        .laporan-filter-group input:focus,
        .laporan-filter-group select:focus {
            outline: none;
            border-color: #b85c83;
        }

        /* TOMBOL */

        .laporan-btn {
            display: inline-block;
            padding: 11px 15px;
            border: none;
            border-radius: 8px;
            background: #b85c83;
            color: white;
            font-size: 13px;
            text-decoration: none;
            cursor: pointer;
        }

        .laporan-btn:hover {
            background: #914365;
        }

        .laporan-btn-reset {
            background: #777;
        }

        .laporan-btn-cetak {
            background: #548c68;
        }

        /* RINGKASAN */

        .laporan-ringkasan {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .laporan-kartu {
            background: #fff0f5;
            border-left: 5px solid #b85c83;
            padding: 18px;
            border-radius: 10px;
        }

        .laporan-kartu h3 {
            font-size: 14px;
            margin: 0 0 10px;
        }

        .laporan-kartu p {
            font-size: 25px;
            font-weight: bold;
            margin: 0;
            color: #a34470;
        }

        /* DETAIL RESERVASI */

        .laporan-detail {
            width: 100%;
            background: rgba(255, 255, 255, 0.96);
            padding: 22px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .laporan-aksi {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 15px;
        }

        .laporan-aksi h2 {
            margin: 0;
            color: #a34470;
            font-size: 22px;
        }

        .laporan-aksi p {
            margin: 7px 0 0;
        }

        .laporan-table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .laporan-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        .laporan-table th,
        .laporan-table td {
            padding: 12px;
            border: 1px solid #e8cbd7;
            text-align: left;
            font-size: 13px;
        }

        .laporan-table th {
            background: #b85c83;
            color: white;
        }

        .laporan-table tr:nth-child(even) {
            background: #fff5f8;
        }

        /* STATUS */

        .laporan-status {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .menunggu {
            background: #fff0c2;
            color: #856404;
        }

        .dikonfirmasi {
            background: #d5eaff;
            color: #155b91;
        }

        .selesai {
            background: #d6f2dc;
            color: #246b35;
        }

        .dibatalkan {
            background: #f8d7da;
            color: #842029;
        }

        .laporan-kosong {
            text-align: center !important;
            padding: 25px !important;
            color: #777;
        }

        .laporan-error {
            background: #f8d7da;
            color: #842029;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        /* TAMPILAN HP */

        @media (max-width: 850px) {
            .laporan-top {
                flex-direction: column;
                gap: 15px;
            }

            .laporan-header {
                width: 100%;
                min-width: 0;
                max-width: 350px;
            }

            .laporan-menu {
                width: 100%;
            }

            .laporan-content {
                max-width: 100%;
            }
        }

        @media (max-width: 550px) {
            .laporan-content {
                grid-template-columns: 1fr;
            }

            .laporan-header h1 {
                font-size: 25px;
            }

            .laporan-menu a {
                flex: 1 1 auto;
            }

            .laporan-container {
                width: 92%;
            }
        }

        /* CETAK LAPORAN */

        @media print {
            body {
                background: white;
                color: black;
            }

            .laporan-top,
            .laporan-filter-panel,
            .laporan-btn-cetak {
                display: none !important;
            }

            .laporan-container {
                width: 100%;
                max-width: none;
                margin: 0;
            }

            .laporan-content {
                display: block;
                max-width: none;
            }

            .laporan-panel,
            .laporan-detail {
                box-shadow: none;
                border: 1px solid #ddd;
            }

            .laporan-table {
                min-width: 0;
            }

            .laporan-table th {
                background: #ddd !important;
                color: black !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .laporan-table th,
            .laporan-table td {
                font-size: 10px;
                padding: 7px;
            }
        }
    </style>
</head>

<body>

<div class="laporan-container">

    <!-- JUDUL DAN MENU -->

    <div class="laporan-top">

        <div class="laporan-header">
            <h1>Laporan Reservasi</h1>
            <p>Salon Nadira Beauty</p>
        </div>

        <div class="laporan-menu">

            <a href="dashboard_admin.php">Dashboard</a>

            <a href="admin_reservasi.php">Data Reservasi</a>

            <a href="admin_layanan.php">Data Layanan</a>

            <a href="../index.php">Halaman Utama</a>

            <a href="../logout.php">Logout</a>

        </div>

    </div>

    <!-- FILTER DAN RINGKASAN -->

    <div class="laporan-content">

        <!-- FILTER LAPORAN -->

        <div class="laporan-panel laporan-filter-panel">

            <h2>Filter Laporan</h2>

            <?php if ($pesan_error !== ""): ?>
                <div class="laporan-error">
                    <?= aman($pesan_error); ?>
                </div>
            <?php endif; ?>

            <form method="GET" action="laporan_reservasi.php">

                <div class="laporan-filter-group">
                    <label>Tanggal Mulai</label>

                    <input
                        type="date"
                        name="tanggal_mulai"
                        value="<?= aman($tanggal_mulai); ?>"
                    >
                </div>

                <div class="laporan-filter-group">
                    <label>Tanggal Selesai</label>

                    <input
                        type="date"
                        name="tanggal_selesai"
                        value="<?= aman($tanggal_selesai); ?>"
                    >
                </div>

                <div class="laporan-filter-group">
                    <label>Status Reservasi</label>

                    <select name="status">

                        <option value="">Semua Status</option>

                        <option value="Menunggu"
                            <?= $status === "Menunggu" ? "selected" : ""; ?>>
                            Menunggu
                        </option>

                        <option value="Dikonfirmasi"
                            <?= $status === "Dikonfirmasi" ? "selected" : ""; ?>>
                            Dikonfirmasi
                        </option>

                        <option value="Selesai"
                            <?= $status === "Selesai" ? "selected" : ""; ?>>
                            Selesai
                        </option>

                        <option value="Dibatalkan"
                            <?= $status === "Dibatalkan" ? "selected" : ""; ?>>
                            Dibatalkan
                        </option>

                    </select>
                </div>

                <button type="submit" class="laporan-btn">
                    Tampilkan Laporan
                </button>

                <a
                    href="laporan_reservasi.php"
                    class="laporan-btn laporan-btn-reset"
                >
                    Reset
                </a>

            </form>

        </div>

        <!-- RINGKASAN RESERVASI -->

        <div class="laporan-panel">

            <h2>Ringkasan Reservasi</h2>

            <div class="laporan-ringkasan">

                <div class="laporan-kartu">
                    <h3>Total Reservasi</h3>
                    <p><?= $total_reservasi; ?></p>
                </div>

                <div class="laporan-kartu">
                    <h3>Menunggu</h3>
                    <p><?= $total_menunggu; ?></p>
                </div>

                <div class="laporan-kartu">
                    <h3>Dikonfirmasi</h3>
                    <p><?= $total_dikonfirmasi; ?></p>
                </div>

                <div class="laporan-kartu">
                    <h3>Selesai</h3>
                    <p><?= $total_selesai; ?></p>
                </div>

                <div class="laporan-kartu">
                    <h3>Dibatalkan</h3>
                    <p><?= $total_dibatalkan; ?></p>
                </div>

            </div>

        </div>

    </div>

    <!-- DETAIL DATA RESERVASI -->

    <div class="laporan-detail">

        <div class="laporan-aksi">

            <div>
                <h2>Detail Data Reservasi</h2>

                <p>
                    Jumlah data:
                    <strong><?= $total_reservasi; ?> reservasi</strong>
                </p>
            </div>

            <button
                type="button"
                class="laporan-btn laporan-btn-cetak"
                onclick="window.print()"
            >
                Cetak / Simpan PDF
            </button>

        </div>

        <div class="laporan-table-wrapper">

            <table class="laporan-table">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Reservasi</th>
                        <th>Nama Pelanggan</th>
                        <th>No. WhatsApp</th>
                        <th>Layanan</th>
                        <th>Harga</th>
                        <th>Tanggal</th>
                        <th>Pesan</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (count($data_reservasi) > 0): ?>

                        <?php $no = 1; ?>

                        <?php foreach ($data_reservasi as $data): ?>

                            <?php
                            $status_class = strtolower(
                                str_replace(" ", "", $data["status"] ?? "")
                            );
                            ?>

                            <tr>

                                <td><?= $no++; ?></td>

                                <td>
                                    <?= aman($data["id_reservasi"]); ?>
                                </td>

                                <td>
                                    <?= aman($data["nama_pelanggan"]); ?>
                                </td>

                                <td>
                                    <?= aman($data["no_whatsapp"]); ?>
                                </td>

                                <td>
                                    <?= aman(
                                        $data["nama_layanan"]
                                        ?? "Layanan tidak tersedia"
                                    ); ?>
                                </td>

                                <td>
                                    <?= $data["harga"] !== null
                                        ? "Rp" . number_format(
                                            (float) $data["harga"],
                                            0,
                                            ",",
                                            "."
                                        )
                                        : "-"; ?>
                                </td>

                                <td>
                                    <?= !empty($data["tanggal_reservasi"])
                                        ? date(
                                            "d-m-Y",
                                            strtotime($data["tanggal_reservasi"])
                                        )
                                        : "-"; ?>
                                </td>

                                <td>
                                    <?= aman($data["pesan"] ?: "-"); ?>
                                </td>

                                <td>
                                    <span class="laporan-status <?= aman($status_class); ?>">
                                        <?= aman($data["status"] ?? "-"); ?>
                                    </span>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="9" class="laporan-kosong">
                                Belum ada data reservasi yang sesuai dengan filter.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>

<?php
$conn->close();
?>
