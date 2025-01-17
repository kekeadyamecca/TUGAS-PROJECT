<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    include "../../koneksi.php";
    $aksi = $_GET['aksi'];

    if ($aksi == "stok-awal") {
        $barang = $mysqli->real_escape_string($_POST['barang']);
        $stokawal = (int) $_POST['stok_awal'];
        $deskripsi = $mysqli->real_escape_string($_POST['deskripsi']);

        $sql = "INSERT INTO persediaan (id_barang, stok_awal, stok_masuk, stok_keluar, stok_akhir, deskripsi_persediaan) 
                VALUES ('$barang', '$stokawal', 0, 0, '$stokawal', '$deskripsi')";
    } elseif ($aksi == "stok-masuk") {
        $idpersediaan = (int) $_GET['id'];
        $sql_persediaan = "SELECT * FROM persediaan WHERE id_persediaan=$idpersediaan";
        $result_persediaan = $mysqli->query($sql_persediaan);
        $row_persediaan = $result_persediaan->fetch_assoc();

        if (!$row_persediaan) {
            die("Data tidak ditemukan: " . $mysqli->error);
        }

        $stokmasuk = (int) $_POST['stok_masuk'];
        $deskripsi = $mysqli->real_escape_string($_POST['deskripsi']);
        $stokawal = $row_persediaan['stok_akhir'];
        $stokakhir = $stokawal + $stokmasuk;

        $sql = "UPDATE persediaan 
                SET stok_awal='$stokawal', stok_masuk='$stokmasuk', stok_keluar=0, stok_akhir='$stokakhir', deskripsi_persediaan='$deskripsi' 
                WHERE id_persediaan='$idpersediaan'";
    } elseif ($aksi == "stok-keluar") {
        $idpersediaan = (int) $_GET['id'];
        $sql_persediaan = "SELECT * FROM persediaan WHERE id_persediaan=$idpersediaan";
        $result_persediaan = $mysqli->query($sql_persediaan);
        $row_persediaan = $result_persediaan->fetch_assoc();

        if (!$row_persediaan) {
            die("Data tidak ditemukan: " . $mysqli->error);
        }

        $stokkeluar = (int) $_POST['stok_keluar'];
        $deskripsi = $mysqli->real_escape_string($_POST['deskripsi']);
        $stokawal = $row_persediaan['stok_akhir'];

        if ($stokkeluar > $stokawal) {
            die("Error: Stok keluar tidak bisa lebih besar dari stok tersedia.");
        }

        $stokakhir = $stokawal - $stokkeluar;

        $sql = "UPDATE persediaan 
                SET stok_awal='$stokawal', stok_masuk=0, stok_keluar='$stokkeluar', stok_akhir='$stokakhir', deskripsi_persediaan='$deskripsi' 
                WHERE id_persediaan='$idpersediaan'";
    }

    if (!$mysqli->query($sql)) {
        die("Error SQL: " . $mysqli->error);
    }
    header('Location: ../../dashboard.php?modul=persediaan');
}
?>
