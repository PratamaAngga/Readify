<?php
include 'koneksi.php';
$judul = "";
$pengarang = "";
$penerbit = "";
$tahun_terbit = "";
$stok = "";
$deskripsi = "";
$sukses     = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if (isset($_POST['simpan'])) {
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $penerbit = $_POST['penerbit'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $stok = $_POST['stok'];
    $deskripsi = $_POST['deskripsi'];

    // Proses gambar
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $imageData = file_get_contents($_FILES['gambar']['tmp_name']);
        $base64Image = base64_encode($imageData);
    } else {
        $base64Image = null;
    }

    // Simpan ke database
    $sql = "INSERT INTO buku (judul, pengarang, penerbit, tahun_terbit, stok, deskripsi, gambar) VALUES ('$judul', '$pengarang', '$penerbit', '$tahun_terbit', '$stok', '$deskripsi', '$base64Image')";

    $result = mysqli_query($koneksi, $sql);
    if ($result) {
        $sukses = "Berhasil menyimpan data buku!";
    } else {
        $error = "Gagal menyimpan data buku: " . mysqli_error($koneksi);
    }
}

// $sql1 = "SELECT * FROM buku";
// $result1 = mysqli_query($koneksi, $sql1);

// while ($row = mysqli_fetch_assoc($result1)) {
//     echo "<h3>" . htmlspecialchars($row['judul']) . "</h3>";
//     echo "<p>Pengarang: " . htmlspecialchars($row['pengarang']) . "</p>";
//     echo "<p>Penerbit: " . htmlspecialchars($row['penerbit']) . "</p>";
//     echo "<p>Tahun: " . htmlspecialchars($row['tahun_terbit']) . "</p>";
//     echo "<p>Stok: " . htmlspecialchars($row['stok']) . "</p>";
//     echo "<p>Deskripsi: " . htmlspecialchars($row['deskripsi']) . "</p>";
    
//     // Tampilkan gambar
//     if (!empty($row['gambar'])) {
//         echo '<img src="data:image/jpeg;base64,' . $row['gambar'] . '" alt="Gambar Buku" style="max-width:200px;"><br><br>';
//     } else {
//         echo 'Tidak ada gambar.<br><br>';
//     }
// }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Buku - Admin</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap5.css">
    <!-- bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <style>
        .mx-auto {width: 800px;}
        .card {margin-top: 10px;}
    </style>
</head>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-admin sidebar sidebar-light accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/admin/dashboard">
                <img src="../img/logo aplikasi billa 1.png" />
            </a>

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="/admin/dashboard">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Nav Item - Data Pengguna -->
            <li class="nav-item">
                <a class="nav-link" href="Data-Pengguna">
                    <i class="bi bi-people"></i>
                    <span>Data Pengguna</span></a>
            </li>
        </ul>
        <!-- End of Sidebar -->
    <div class="mx-auto">
    <!-- // untuk masukkan data -->
        <div class="card">
            <div class="card-header">
                Create / Edit Data
            </div>
            <div class="card-body">
                <?php 
                if ($error) {
                    ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                    <?php
                    header("refresh:5; url=index.php"); //5detik
                }
                ?>
                <?php 
                if ($sukses) {
                    ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                    <?php
                    header("refresh:5; url=index.php");
                }
                ?>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul Buku</label>
                        <input type="text" class="form-control" name="judul" id="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="pengarang" class="form-label">Pengarang</label>
                        <input type="text" class="form-control" name="pengarang" id="pengarang" required>
                    </div>
                    <div class="mb-3">
                        <label for="penerbit" class="form-label">Penerbit</label>
                        <input type="text" class="form-control" name="penerbit" id="penerbit" required>
                    </div>
                    <div class="mb-3">
                        <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                        <input type="number" class="form-control" name="tahun_terbit" id="tahun_terbit" required>
                    </div>
                    <div class="mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" class="form-control" name="stok" id="stok" required>
                    </div>
                    <div class="mb-3">
                        <label for="stok" class="form-label">Deskripsi</label>
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Deskripsi Buku" id="deskripsi" name="deskripsi" style="height: 100px" required></textarea>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="gambar" class="form-label">Upload Gambar (Cover)</label>
                        <input type="file" class="form-control" name="gambar" id="gambar" accept="image/*" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" name="simpan" class="btn btn-primary">Simpan Buku</button>
                    </div>
                </form>
            </div>
        </div>
</body>
</html>