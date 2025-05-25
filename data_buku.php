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
    
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
</head>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-admin sidebar sidebar-light accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/admin/dashboard">
                <img src="image/Readify..png" />
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

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Buku</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="" class="img-fluid" id="gambar-buku">
                                </div>
                                <div class="col-md-8">
                                    <ul class="list-group">
                                        <li class="list-group-item mb-4">
                                            <h3 id="judul-buku"></h3>
                                        </li>
                                        <label for="kategori-buku">Kategori :</label>
                                        <li class="list-group-item mb-4" id="kategori-buku"></li>
                                        <label for="kategori-buku">Penulis :</label>
                                        <li class="list-group-item mb-4" id="penulis-buku"></li>
                                        <label for="kategori-buku">Penerbit :</label>
                                        <li class="list-group-item mb-4" id="penerbit-buku"></li>
                                        <label for="kategori-buku">Tahun Terbit :</label>
                                        <li class="list-group-item mb-4" id="terbit-buku"></li>
                                        <label for="kategori-buku">Stok :</label>
                                        <li class="list-group-item mb-4" id="stok-buku"></li>
                                        <label for="kategori-buku">Deskripsi :</label>
                                        <li class="list-group-item mb-4" id="deskripsi-buku"></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <div class="header-navbar">
                        <h2>Data Buku</h2>
                        <p>Data Buku</p>
                    </div>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">!</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div
                                class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown" id="notifAdmin"
                                >
                                <h6 class="dropdown-header">Notifikasi</h6>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="mr-3 img-profile rounded-circle" src="../img/undraw_profile.svg" />
                                <span class="d-none d-lg-inline text-gray-600 small" id="user"> (Petugas)</span>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" style="cursor: pointer;" onclick="logout()">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Keluar
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Button Print Laporan -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <a href="/admin/Tambah-Buku"><button class="btn-tambah">
                                <i class="bi bi-plus-lg"></i>Tambah Buku
                            </button></a>
                    </div>

                    <!-- Content Row -->
                    <!-- Project Card Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-orange">
                                List Data Buku
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="tabelBuku">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Judul</th>
                                            <th scope="col">Pengarang</th>
                                            <th scope="col">Penerbit</th>
                                            <th scope="col">Tahun Terbit</th>
                                            <th scope="col">Stok</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql2 = "select * from buku order by id_buku";
                                        $q2   = mysqli_query($koneksi,$sql2);
                                        $urut = 1;
                                        while ($r2 = mysqli_fetch_array($q2)) {
                                            $id_buku = $r2['id_buku'];
                                            $judul = $r2['judul'];
                                            $pengarang = $r2['pengarang'];
                                            $penerbit = $r2['penerbit'];
                                            $tahun_terbit = $r2['tahun_terbit'];
                                            $stok = $r2['stok'];
                                        ?>
                                        <tr>
                                            <td><?php echo $urut++ ?></td>
                                            <td><?php echo $judul ?></td>
                                            <td><?php echo $pengarang ?></td>
                                            <td><?php echo $penerbit ?></td>
                                            <td><?php echo $tahun_terbit ?></td>
                                            <td><?php echo $stok ?></td>
                                            <td>
                                                <a href="index.php?op=edit&id=<?php echo $id_buku ?>"><button type="button" class="btn btn-warning btn-sm">Edit</button></a>
                                                <a href="index.php?op=delete&id=<?php echo $id_buku ?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger btn-sm">Delete</button></a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
    </div>   
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
    </div>
    <script>
        $(document).ready(function () {
            $('#tabelBuku').DataTable();
        });
    </script>
</body>
</html>