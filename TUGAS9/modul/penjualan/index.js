$(document).ready(function () {
    $('#tabelDataPenjualan').DataTable();
    updateTabelPenjualan();
});

function pilihBarang() {
    var select = document.getElementById('barang');
    var selectedOption = select.options[select.selectedIndex];

    var idBarang = selectedOption.value;
    var stok = selectedOption.getAttribute('data-stok');
    var harga = selectedOption.getAttribute('data-harga');

    document.getElementById('stok').value = stok;
    document.getElementById('harga').value = harga;
}

function hitungtotalharga() {
    var jumlah = parseFloat(document.getElementById('jumlah').value) || 0;
    var harga = parseFloat(document.getElementById('harga').value) || 0;

    var totalharga = jumlah * harga;
    document.getElementById('totalharga').value = totalharga;
}

function tambahItem() {
    var barang = document.getElementById('barang');
    var idBarang = barang.value;
    var namabarang = barang.options[barang.selectedIndex].getAttribute('nama-barang');
    var jumlah = parseFloat(document.getElementById('jumlah').value) || 0;
    var harga = parseFloat(document.getElementById('harga').value) || 0;
    var totalharga = parseFloat(document.getElementById('totalharga').value) || 0;

    if (!idBarang || jumlah <= 0) {
        alert("Harap pilih barang dan masukkan jumlah yang valid!");
        return;
    }

    var penjualan = JSON.parse(localStorage.getItem('penjualan')) || [];
    penjualan.push({
        idbarang: idBarang,
        namabarang: namabarang,
        jumlah: jumlah,
        harga: harga,
        totalharga: totalharga,
    });
    localStorage.setItem('penjualan', JSON.stringify(penjualan));

    updateTabelPenjualan();
    kosongkanForm();
}

function kosongkanForm() {
    document.getElementById('barang').value = '';
    document.getElementById('stok').value = '';
    document.getElementById('jumlah').value = '';
    document.getElementById('harga').value = '';
    document.getElementById('totalharga').value = '';
}

function resetPenjualan() {
    if (confirm("Apakah Anda yakin ingin mereset semua data penjualan?")) {
        localStorage.removeItem('penjualan');
        localStorage.removeItem('totalbayar');
        localStorage.removeItem('bayar');
        localStorage.removeItem('kembalian');
        location.reload();
    }
}

function hitungTotalBayar() {
    var penjualan = JSON.parse(localStorage.getItem('penjualan')) || [];
    var totalBayar = 0;

    penjualan.forEach((item) => {
        totalBayar += parseFloat(item.totalharga) || 0;
    });

    localStorage.setItem('totalbayar', totalBayar);

    var totalBayarIDR = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(totalBayar);

    document.getElementById('totalBayar').textContent = totalBayarIDR;
    document.getElementById('modalTotalBayar').value = totalBayar;
}

function updateTabelPenjualan() {
    var penjualan = JSON.parse(localStorage.getItem('penjualan')) || [];
    var tabelPenjualan = $('#tabelPenjualan').DataTable();

    tabelPenjualan.clear();
    penjualan.forEach((item, index) => {
        tabelPenjualan.row.add([
            index + 1,
            item.namabarang,
            item.jumlah,
            item.harga,
            item.totalharga,
        ]).draw(false);
    });

    hitungTotalBayar();
}

function simpanPenjualan() {
    var penjualan = JSON.parse(localStorage.getItem('penjualan')) || [];
    var totalbayar = parseFloat(localStorage.getItem('totalbayar')) || 0;
    var bayar = parseFloat(localStorage.getItem('bayar')) || 0;
    var kembalian = parseFloat(localStorage.getItem('kembalian')) || 0;

    if (penjualan.length === 0 || totalbayar <= 0) {
        alert("Tidak ada data penjualan untuk disimpan.");
        return;
    }

    $.ajax({
        url: 'modul/penjualan/proses.php',
        method: 'POST',
        data: {
            penjualan: JSON.stringify(penjualan),
            totalbayar: totalbayar,
            bayar: bayar,
            kembalian: kembalian,
        },
        success: function (response) {
            alert("Data penjualan berhasil disimpan!");
            resetPenjualan();
        },
        error: function (error) {
            console.error("Error saat menyimpan penjualan:", error);
            alert("Gagal menyimpan data penjualan.");
        }
    });
}

function hitungKembalian() {
    var bayar = parseFloat(document.getElementById('bayar').value) || 0;
    var totalBayar = parseFloat(localStorage.getItem('totalbayar')) || 0;
    var kembalian = bayar - totalBayar;

    localStorage.setItem('bayar', bayar);
    localStorage.setItem('kembalian', kembalian);

    document.getElementById('kembalian').value = kembalian >= 0 ? kembalian.toFixed(2) : 0;
}
