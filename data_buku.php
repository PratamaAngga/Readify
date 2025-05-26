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

if ($op == 'delete') {
    $id_buku = $_GET['id_buku'];
    $sql5 = "DELETE FROM buku WHERE id_buku = '$id_buku'";
    $q5 = mysqli_query($koneksi, $sql5);
    if ($q5) {
        $sukses = "Berhasil hapus data";
        header("refresh:3; url=data_buku.php");
    } else {
        $error = "Gagal menghapus data";
    }
}

if (isset($_POST['simpan_edit'])) {
    $id_buku = $_POST['edit_id_buku'];
    $judul = $_POST['edit_judul'];
    $pengarang = $_POST['edit_pengarang'];
    $penerbit = $_POST['edit_penerbit'];
    $tahun_terbit = $_POST['edit_tahun_terbit'];
    $deskripsi = $_POST['edit_deskripsi'];
    $stok = $_POST['edit_stok'];

    // Proses gambar
    if (isset($_FILES['edit_gambar']) && $_FILES['edit_gambar']['error'] == 0) {
        $imageData = file_get_contents($_FILES['edit_gambar']['tmp_name']);
        $base64Image = base64_encode($imageData);
    } else {
        $base64Image = null;
    }

    if ($judul && $pengarang && $penerbit && $tahun_terbit && $deskripsi && $stok) {
        if ($base64Image !== null) {
            $sql4 = "UPDATE buku SET judul = '$judul', pengarang = '$pengarang', penerbit = '$penerbit', tahun_terbit = '$tahun_terbit', stok = '$stok', deskripsi = '$deskripsi', gambar = '$base64Image' WHERE id_buku = '$id_buku'";     
        } else {
            $sql4 = "UPDATE buku SET judul = '$judul', pengarang = '$pengarang', penerbit = '$penerbit', tahun_terbit = '$tahun_terbit', stok = '$stok', deskripsi = '$deskripsi' WHERE id_buku = '$id_buku'";     
        }
        $q4 = mysqli_query($koneksi, $sql4);
        if ($q4) {
            $sukses = "Berhasil merubah data!";
            header("refresh:3; url=data_buku.php");
        } else {
            $error = "Gagal merubah data: " . mysqli_error($koneksi);
        }
    } else {
        $error = "Masukkan semua data";
    }
}

if (isset($_POST['simpan'])) {
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $penerbit = $_POST['penerbit'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $deskripsi = $_POST['deskripsi'];
    $stok = $_POST['stok'];

    // Proses gambar
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $imageData = file_get_contents($_FILES['gambar']['tmp_name']);
        $base64Image = base64_encode($imageData);
    } else {
        $base64Image = null;
    }

    if ($judul && $pengarang && $penerbit && $tahun_terbit && $deskripsi && $stok && $base64Image) {
        // Simpan ke database
        $sql = "INSERT INTO buku (judul, pengarang, penerbit, tahun_terbit, stok, deskripsi, gambar) VALUES ('$judul', '$pengarang', '$penerbit', '$tahun_terbit', '$stok', '$deskripsi', '$base64Image')";
    
        $result = mysqli_query($koneksi, $sql);
        if ($result) {
            $sukses = "Berhasil menyimpan data buku!";
            header("refresh:3; url=data_buku.php");
        } else {
            $error = "Gagal menyimpan data buku: " . mysqli_error($koneksi);
        }
    } else {
        $error = "Masukkan semua data";
    }
}

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
                    <i class="bi bi-house-door"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Nav Item - Data Pengguna -->
            <li class="nav-item">
                <a class="nav-link" style="color: #c25b4a; background-color: #fff4e3; border-color: 2px solid #c25b4a:" href="data_buku.php">
                    <i class="bi bi-book" style="color: #c25b4a;"></i>
                    <span>Data Buku</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Data-Pengguna">
                    <i class="bi bi-people"></i>
                    <span>Data Pengguna</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Data-Pengguna">
                    <i class="bi bi-card-list"></i>
                    <span>Data Peminjaman</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Data-Pengguna">
                    <i class="bi bi-bookmarks"></i>
                    <span>Data Kategori</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Data-Pengguna">
                    <i class="bi bi-journal-plus"></i>
                    <span>Data Donasi Buku</span></a>
            </li>
        </ul>
        <!-- End of Sidebar -->

        <div class="pemisah" style="width: 2px; background-color:#727272; min-height: 100vh"></div>

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
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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

                <!-- Modal Tambah Buku -->
                <div class="modal fade" id="modalTambahBuku" tabindex="-1" aria-labelledby="modalTambahBukuLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahBukuLabel">Tambah Buku</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form Tambah Buku -->
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
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" id="deskripsi" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="gambar" class="form-label">Upload Gambar (Cover)</label>
                            <input type="file" class="form-control" name="gambar" id="gambar" accept="image/*" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="simpan" class="btn btn-primary">Simpan Buku</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        </div>
                        </form>
                    </div>
                    </div>
                </div>
                </div>

                <!-- Modal Edit Buku -->
                <div class="modal fade" id="modalEditBuku" tabindex="-1" aria-labelledby="modalEditBukuLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditBukuLabel">Edit Buku</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form Edit Buku -->
                        <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="edit_id_buku" id="edit_id_buku">
                        <div class="mb-3">
                            <label for="edit_judul" class="form-label">Judul Buku</label>
                            <input type="text" class="form-control" name="edit_judul" value="<?= htmlspecialchars($judul) ?>" id="edit_judul" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_pengarang" class="form-label">Pengarang</label>
                            <input type="text" class="form-control" name="edit_pengarang" value="<?= htmlspecialchars($pengarang) ?>" id="edit_pengarang" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_penerbit" class="form-label">Penerbit</label>
                            <input type="text" class="form-control" name="edit_penerbit" value="<?= htmlspecialchars($penerbit) ?>" id="edit_penerbit" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_tahun_terbit" class="form-label">Tahun Terbit</label>
                            <input type="number" class="form-control" name="edit_tahun_terbit" value="<?= htmlspecialchars($tahun_terbit) ?>" id="edit_tahun_terbit" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_stok" class="form-label">Stok</label>
                            <input type="number" class="form-control" name="edit_stok" value="<?= htmlspecialchars($stok) ?>" id="edit_stok" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="edit_deskripsi" value="<?= htmlspecialchars($deskripsi) ?>" id="edit_deskripsi" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Gambar Saat Ini:</label><br>
                            <img id="preview_gambar" src="" alt="Gambar Buku"  width="100">
                        </div>
                        <div class="mb-3">
                            <label for="edit_gambar" class="form-label">Upload Gambar (Cover)</label>
                            <input type="file" class="form-control" name="edit_gambar" id="edit_gambar" accept="image/*">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="simpan_edit" class="btn btn-primary">Simpan Buku</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        </div>
                        </form>
                    </div>
                    </div>
                </div>
                </div>

                <!-- Modal Show Buku -->
                <div class="modal fade" id="modalShowBuku" tabindex="-1" aria-labelledby="modalShowBukuLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditBukuLabel">Detail Buku</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form Edit Buku -->
                        <form action="">
                        <input type="hidden" name="show_id_buku" id="show_id_buku">
                        <div class="mb-3">
                            <label for="show_judul" class="form-label">Judul Buku</label>
                            <input type="text" class="form-control" name="show_judul" value="<?= htmlspecialchars($judul) ?>" id="show_judul" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="show_pengarang" class="form-label">Pengarang</label>
                            <input type="text" class="form-control" name="show_pengarang" value="<?= htmlspecialchars($pengarang) ?>" id="show_pengarang" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="show_penerbit" class="form-label">Penerbit</label>
                            <input type="text" class="form-control" name="show_penerbit" value="<?= htmlspecialchars($penerbit) ?>" id="show_penerbit" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="show_tahun_terbit" class="form-label">Tahun Terbit</label>
                            <input type="number" class="form-control" name="show_tahun_terbit" value="<?= htmlspecialchars($tahun_terbit) ?>" id="show_tahun_terbit" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="show_stok" class="form-label">Stok</label>
                            <input type="number" class="form-control" name="show_stok" value="<?= htmlspecialchars($stok) ?>" id="show_stok" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="show_deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="show_deskripsi" value="<?= htmlspecialchars($deskripsi) ?>" id="show_deskripsi" rows="3" readonly></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Gambar Saat Ini:</label><br>
                            <img id="show_gambar" src="" alt="Gambar Buku"  width="100">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                        </form>
                    </div>
                    </div>
                </div>
                </div>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Button Print Laporan -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <a href="#" id="btn-tambah" data-bs-toggle="modal" data-bs-target="#modalTambahBuku"><button class="btn-tambah">
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
                                                <button type="button" class="btn btn-warning btn-sm editBtn" data-id_buku="<?php echo $r2['id_buku']; ?>" data-toggle="modal" data-target="#modalEditBuku"><i class="bi bi-pencil"></i></button>
                                                <button type="button" class="btn btn-primary btn-sm showBtn" data-id_buku="<?php echo $r2['id_buku']; ?>" data-toggle="modal" data-target="#modalShowBuku"><i class="bi bi-eye"></i></button>
                                                <a href="data_buku.php?op=delete&id_buku=<?php echo $id_buku ?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger btn-sm"><i class="bi bi-trash3"></i></button></a>
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
    <script>
        $(document).ready(function () {
            $('#tabelBuku').DataTable();
            $('.editBtn').click(function() {
                const id_buku = $(this).data('id_buku');
                $.ajax({
                url: 'get_buku.php',
                type: 'GET',
                data: { id_buku: id_buku },
                dataType: 'json',
                success: function(data) {
                    $('#edit_id_buku').val(data.id_buku);
                    $('#edit_judul').val(data.judul);
                    $('#edit_pengarang').val(data.pengarang);
                    $('#edit_penerbit').val(data.penerbit);
                    $('#edit_tahun_terbit').val(data.tahun_terbit);
                    $('#edit_stok').val(data.stok);
                    $('#edit_deskripsi').val(data.deskripsi);
                    $('#preview_gambar').attr('src', 'data:image/jpeg;base64,' + data.gambar);
                    $('#modalEditBuku').modal('show');
                },
                error: function() {
                    alert('Gagal mengambil data.');
                }
                });
            });
            $('.showBtn').click(function() {
                const id_buku = $(this).data('id_buku');
                $.ajax({
                url: 'get_buku.php',
                type: 'GET',
                data: { id_buku: id_buku },
                dataType: 'json',
                success: function(data) {
                    $('#show_id_buku').val(data.id_buku);
                    $('#show_judul').val(data.judul);
                    $('#show_pengarang').val(data.pengarang);
                    $('#show_penerbit').val(data.penerbit);
                    $('#show_tahun_terbit').val(data.tahun_terbit);
                    $('#show_stok').val(data.stok);
                    $('#show_deskripsi').val(data.deskripsi);
                    $('#show_gambar').attr('src', 'data:image/jpeg;base64,' + data.gambar);
                    $('#modalShowBuku').modal('show');
                },
                error: function() {
                    alert('Gagal mengambil data.');
                }
                });
            });
        });
    </script>
</body>
</html>