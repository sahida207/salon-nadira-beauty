
<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config/databases.php";

$pesan = "";
$jenis_pesan = "";

// Proses tambah layanan
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["tambah_layanan"])) {
    $nama_layanan = trim($_POST["nama_layanan"] ?? "");
    $deskripsi = trim($_POST["deskripsi"] ?? "");
    $harga_input = trim($_POST["harga"] ?? "");

    if ($nama_layanan === "" || $deskripsi === "" || $harga_input === "") {
        $pesan = "Semua data layanan harus diisi.";
        $jenis_pesan = "error";
    } elseif (!ctype_digit($harga_input)) {
        $pesan = "Harga harus berupa angka.";
        $jenis_pesan = "error";
    } else {
        $harga = (int) $harga_input;

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO layanan (nama_layanan, deskripsi, harga)
             VALUES (?, ?, ?)"
        );

        if (!$stmt) {
            $pesan = "Gagal menyiapkan data layanan.";
            $jenis_pesan = "error";
        } else {
            mysqli_stmt_bind_param(
                $stmt,
                "ssi",
                $nama_layanan,
                $deskripsi,
                $harga
            );

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header("Location: admin_layanan.php?berhasil=tambah");
                exit;
            } else {
                $pesan = "Layanan gagal ditambahkan.";
                $jenis_pesan = "error";
                mysqli_stmt_close($stmt);
            }
        }
    }
}

// Proses hapus layanan
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["hapus_layanan"])) {
    $id_layanan = (int) ($_POST["id_layanan"] ?? 0);

    if ($id_layanan > 0) {
        // Periksa apakah layanan sudah digunakan dalam reservasi
        $cek = mysqli_prepare(
            $conn,
            "SELECT COUNT(*) AS jumlah
             FROM reservasi
             WHERE id_layanan = ?"
        );

        if (!$cek) {
            $pesan = "Gagal memeriksa data reservasi.";
            $jenis_pesan = "error";
        } else {
            mysqli_stmt_bind_param($cek, "i", $id_layanan);
            mysqli_stmt_execute($cek);

            $hasil_cek = mysqli_stmt_get_result($cek);
            $data_cek = mysqli_fetch_assoc($hasil_cek);

            mysqli_stmt_close($cek);

            if ((int) $data_cek["jumlah"] > 0) {
                header("Location: admin_layanan.php?gagal=digunakan");
                exit;
            }

            $hapus = mysqli_prepare(
                $conn,
                "DELETE FROM layanan WHERE id_layanan = ?"
            );

            if (!$hapus) {
                header("Location: admin_layanan.php?gagal=hapus");
                exit;
            }

            mysqli_stmt_bind_param($hapus, "i", $id_layanan);

            if (mysqli_stmt_execute($hapus)) {
                mysqli_stmt_close($hapus);
                header("Location: admin_layanan.php?berhasil=hapus");
                exit;
            }

            mysqli_stmt_close($hapus);
            header("Location: admin_layanan.php?gagal=hapus");
            exit;
        }
    }
}

// Notifikasi
if (isset($_GET["berhasil"])) {
    if ($_GET["berhasil"] === "tambah") {
        $pesan = "Layanan berhasil ditambahkan!";
        $jenis_pesan = "sukses";
    } elseif ($_GET["berhasil"] === "hapus") {
        $pesan = "Layanan berhasil dihapus!";
        $jenis_pesan = "sukses";
    } elseif ($_GET["berhasil"] === "update") {
        $pesan = "Data layanan berhasil diperbarui!";
        $jenis_pesan = "sukses";
    }
}

if (isset($_GET["gagal"])) {
    if ($_GET["gagal"] === "digunakan") {
        $pesan = "Layanan tidak dapat dihapus karena sudah digunakan dalam reservasi.";
        $jenis_pesan = "error";
    } elseif ($_GET["gagal"] === "hapus") {
        $pesan = "Layanan gagal dihapus.";
        $jenis_pesan = "error";
    }
}

// Ambil SEMUA data layanan dari database
$query = "
    SELECT id_layanan, nama_layanan, deskripsi, harga
    FROM layanan
    ORDER BY id_layanan DESC
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Gagal mengambil data layanan: " . htmlspecialchars(mysqli_error($conn)));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Layanan - Salon Nadira Beauty</title>

    <link rel="icon" type="image/png" href="../image/favicon.png">
    <link rel="stylesheet" href="../style.css">

    <style>
        body {
            margin: 0;
            padding: 25px 15px;
            font-family: Arial, sans-serif;
            background:
                linear-gradient(
                    rgba(255, 235, 242, 0.88),
                    rgba(255, 235, 242, 0.88)
                ),
                url("../image/background salon nadira beauty.jpeg");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .container-admin {
            width: 100%;
            max-width: 1150px;
            margin: 25px auto;
            padding: 25px;
            box-sizing: border-box;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
        }

        .container-admin h2 {
            margin-top: 0;
            margin-bottom: 25px;
            color: #b83270;
            text-align: center;
        }

        .form-tambah {
            padding: 20px;
            margin-bottom: 25px;
            background: #fff4f8;
            border-radius: 10px;
        }

        .form-tambah h3 {
            margin-top: 0;
            color: #b83270;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
            color: #444;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 11px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 7px;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .form-group textarea {
            min-height: 80px;
            resize: vertical;
        }

        .btn-tambah {
            padding: 11px 20px;
            border: none;
            border-radius: 7px;
            background: #d94f91;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-tambah:hover {
            background: #bd3478;
        }

        .notifikasi {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 7px;
            font-size: 14px;
        }

        .notifikasi.sukses {
            color: #176b36;
            background: #e2f7e8;
        }

        .notifikasi.error {
            color: #a00035;
            background: #ffe3ed;
        }

        .tabel-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 750px;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #eee;
            text-align: left;
            vertical-align: middle;
            overflow-wrap: break-word;
        }

        th {
            background: #d94f91;
            color: white;
            text-align: center;
        }

        td {
            color: #444;
            font-size: 13px;
        }

        tbody tr:nth-child(even) {
            background: #fff4f8;
        }

        tbody tr:hover {
            background: #ffe8f1;
        }

        td:first-child {
            text-align: center;
            width: 6%;
        }

        td:nth-child(2) {
            width: 20%;
        }

        td:nth-child(3) {
            width: 38%;
            line-height: 1.5;
        }

        td:nth-child(4) {
            width: 18%;
            white-space: nowrap;
        }

        td:last-child {
            width: 18%;
            text-align: center;
        }

        .aksi {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
        }

        .btn-edit,
        .btn-hapus {
            display: inline-block;
            padding: 7px 10px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-size: 12px;
            border: none;
            cursor: pointer;
        }

        .btn-edit {
            background: #3498db;
        }

        .btn-edit:hover {
            background: #217db5;
        }

        .btn-hapus {
            background: #e74c3c;
        }

        .btn-hapus:hover {
            background: #c0392b;
        }

        .aksi form {
            margin: 0;
        }

        .kosong {
            padding: 20px;
            text-align: center;
            color: #777;
        }

        .btn-kembali {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 18px;
            border-radius: 7px;
            background: #fce1ed;
            color: #b83270;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-kembali:hover {
            background: #f8cadd;
        }

        @media (max-width: 600px) {
            .container-admin {
                padding: 15px;
            }

            .form-tambah {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

<div class="container-admin">

    <h2>Data Layanan Salon Nadira Beauty</h2>

    <?php if ($pesan !== ""): ?>
        <div class="notifikasi <?= htmlspecialchars($jenis_pesan) ?>">
            <?= htmlspecialchars($pesan) ?>
        </div>
    <?php endif; ?>

    <!-- Form tambah layanan -->
    <div class="form-tambah">

        <h3>Tambah Layanan</h3>

        <form method="POST">

            <div class="form-group">
                <label for="nama_layanan">Nama Layanan</label>
                <input
                    type="text"
                    id="nama_layanan"
                    name="nama_layanan"
                    maxlength="100"
                    placeholder="Masukkan nama layanan"
                    required
                >
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    placeholder="Masukkan deskripsi layanan"
                    required
                ></textarea>
            </div>

            <div class="form-group">
                <label for="harga">Harga (Rp)</label>
                <input
                    type="number"
                    id="harga"
                    name="harga"
                    min="0"
                    placeholder="Masukkan harga layanan"
                    required
                >
            </div>

            <button
                type="submit"
                name="tambah_layanan"
                class="btn-tambah"
            >
                Tambah Layanan
            </button>

        </form>

    </div>

    <!-- Tabel semua layanan -->
    <h3>Daftar Semua Layanan</h3>

    <div class="tabel-wrapper">

        <table>

            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Layanan</th>
                    <th>Deskripsi</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                <?php if (mysqli_num_rows($result) > 0): ?>

                    <?php $no = 1; ?>

                    <?php while ($row = mysqli_fetch_assoc($result)): ?>

                        <tr>
                            <td><?= $no++ ?></td>

                            <td>
                                <?= htmlspecialchars($row["nama_layanan"], ENT_QUOTES, "UTF-8") ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row["deskripsi"], ENT_QUOTES, "UTF-8") ?>
                            </td>

                            <td>
                                Rp <?= number_format((float) $row["harga"], 0, ",", ".") ?>
                            </td>

                            <td>
                                <div class="aksi">

                                    <a
                                        href="edit_layanan.php?id=<?= (int) $row["id_layanan"] ?>"
                                        class="btn-edit"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')"
                                    >
                                        <input
                                            type="hidden"
                                            name="id_layanan"
                                            value="<?= (int) $row["id_layanan"] ?>"
                                        >

                                        <button
                                            type="submit"
                                            name="hapus_layanan"
                                            class="btn-hapus"
                                        >
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="5" class="kosong">
                            Belum ada data layanan.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

    <a href="dashboard_admin.php" class="btn-kembali">
        Kembali ke Dashboard
    </a>

</div>

</body>
</html>

<?php
mysqli_free_result($result);
mysqli_close($conn);
?>
