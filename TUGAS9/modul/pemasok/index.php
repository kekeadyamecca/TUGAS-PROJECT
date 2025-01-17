<div class="border-bottom d-flex justify-content-between py-3">
    <h4>Data Pemasok</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus"></i> Tambah Pemasok
    </button>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalTambahLabel">Tambah Data Pemasok</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="modul/pemasok/proses.php?aksi=tambah" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="namapemasok" class="form-label">Nama Pemasok</label>
                        <input type="text" name="namapemasok" class="form-control" placeholder="Masukkan nama pemasok" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" name="alamat" class="form-control" placeholder="Masukkan alamat" required>
                    </div>
                    <div class="mb-3">
                        <label for="telepon" class="form-label">Telepon</label>
                        <input type="text" name="telepon" class="form-control" placeholder="Masukkan telepon" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="" disabled selected>Pilih Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tabel Pemasok -->
<table id="myTable" class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Pemasok</th>
            <th>Alamat</th>
            <th>Telepon</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql_pemasok = "SELECT * FROM pemasok ORDER BY id_pemasok ASC";
        $result_pemasok = $mysqli->query($sql_pemasok);

        if ($result_pemasok && $result_pemasok->num_rows > 0) {
            $no = 0;
            while ($row_pemasok = $result_pemasok->fetch_assoc()) {
                $no++;
                ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><?= htmlspecialchars($row_pemasok['nama_pemasok']); ?></td>
                    <td><?= htmlspecialchars($row_pemasok['alamat']); ?></td>
                    <td><?= htmlspecialchars($row_pemasok['telepon']); ?></td>
                    <td>
                        <span class="badge <?= $row_pemasok['status'] == 1 ? 'text-bg-success' : 'text-bg-danger'; ?>">
                            <?= $row_pemasok['status'] == 1 ? 'Aktif' : 'Tidak Aktif'; ?>
                        </span>
                    </td>
                    <td>
                        <!-- Modal Edit -->
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row_pemasok['id_pemasok']; ?>" class="text-info">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <div class="modal fade" id="modalEdit<?= $row_pemasok['id_pemasok']; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5">Edit Data Pemasok</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="modul/pemasok/proses.php?aksi=edit&id=<?= $row_pemasok['id_pemasok']; ?>" method="post">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="namapemasok" class="form-label">Nama Pemasok</label>
                                                <input type="text" name="namapemasok" class="form-control" value="<?= htmlspecialchars($row_pemasok['nama_pemasok']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="alamat" class="form-label">Alamat</label>
                                                <input type="text" name="alamat" class="form-control" value="<?= htmlspecialchars($row_pemasok['alamat']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="telepon" class="form-label">Telepon</label>
                                                <input type="text" name="telepon" class="form-control" value="<?= htmlspecialchars($row_pemasok['telepon']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="1" <?= $row_pemasok['status'] == 1 ? 'selected' : ''; ?>>Aktif</option>
                                                    <option value="0" <?= $row_pemasok['status'] == 0 ? 'selected' : ''; ?>>Tidak Aktif</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Ubah</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Hapus -->
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $row_pemasok['id_pemasok']; ?>" class="text-danger">
                            <i class="bi bi-trash-fill"></i>
                        </a>
                        <div class="modal fade" id="modalHapus<?= $row_pemasok['id_pemasok']; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5">Hapus Data Pemasok</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin menghapus data pemasok <b><?= htmlspecialchars($row_pemasok['nama_pemasok']); ?></b>?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <form action="modul/pemasok/proses.php?aksi=hapus&id=<?= $row_pemasok['id_pemasok']; ?>" method="post">
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='6' class='text-center'>Tidak ada data pemasok.</td></tr>";
        }
        ?>
    </tbody>
</table>

<script>
    $(document).ready(function () {
        $('#myTable').DataTable();
    });
</script>
