<?php
include 'koneksi.php';

if (isset($_GET['id_buku'])) {
    $id_buku = mysqli_real_escape_string($koneksi, $_GET['id_buku']);
    $query = "SELECT * FROM buku WHERE id_buku='$id_buku'";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);
    echo json_encode($data);
}
?>