<?php
include "koneksi.php";

// Mendapatkan halaman yang aktif dari POST request, default ke halaman 1
$hlm = (isset($_POST['hlm'])) ? $_POST['hlm'] : 1;
$limit = 3;  // Batasan jumlah item per halaman
$limit_start = ($hlm - 1) * $limit;  // Posisi mulai data untuk query

// Query untuk mengambil data gallery dengan batasan jumlah per halaman
$sql = "SELECT * FROM gallery ORDER BY tanggal DESC LIMIT $limit_start, $limit";
$hasil = $conn->query($sql);
$no = $limit_start + 1;

?>

<table class="table table-hover">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th class="w-25">Nama</th>
            <th class="w-25">Tanggal</th>
            <th class="w-50">Gambar</th>
            <th class="w-25">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $hasil->fetch_assoc()) { ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row["nama"] ?></td>
                <td><?= $row["tanggal"] ?></td>
                <td>
                    <?php if ($row["gambar"] != '') { 
                        if (file_exists('img/' . $row["gambar"])) { ?>
                            <img src="img/<?= $row["gambar"] ?>" width="100">
                        <?php } 
                    } ?>
                </td>
                <td>
                    <!-- Tombol Edit dan Hapus -->
                    <a href="#" title="edit" class="badge rounded-pill text-bg-success" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row["id"] ?>"><i class="bi bi-pencil"></i></a>
                    <a href="#" title="delete" class="badge rounded-pill text-bg-danger" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $row["id"] ?>"><i class="bi bi-x-circle"></i></a>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="modalEdit<?= $row["id"] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Gallery</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="post" action="" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="formGroupExampleInput" class="form-label">Nama</label>
                                            <input type="hidden" name="id" value="<?= $row["id"] ?>">
                                            <input type="text" class="form-control" name="nama" value="<?= $row["nama"] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="formGroupExampleInput2" class="form-label">Ganti Gambar</label>
                                            <input type="file" class="form-control" id="Gambar" name="Gambar" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="formGroupExampleInput3" class="form-label">Gambar Lama</label>
                                            <?php if ($row["gambar"] != '') { 
                                                if (file_exists('img/' . $row["gambar"])) { ?>
                                                    <br><img src="img/<?= $row["gambar"] ?>" width="100">
                                                <?php } 
                                            } ?>
                                            <input type="hidden" name="gambar_lama" value="<?= $row["gambar"] ?>">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <input type="submit" value="Simpan" name="simpan" class="btn btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Hapus -->
                    <div class="modal fade" id="modalHapus<?= $row["id"] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Konfirmasi Hapus Gallery</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="post" action="" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="formGroupExampleInput" class="form-label">Yakin akan menghapus gambar "<strong><?= $row["nama"] ?></strong>"?</label>
                                            <input type="hidden" name="id" value="<?= $row["id"] ?>">
                                            <input type="hidden" name="gambar" value="<?= $row["gambar"] ?>">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <input type="submit" value="Hapus" name="hapus" class="btn btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php
// Menghitung total record gallery untuk pagination
$sql1 = "SELECT * FROM gallery";
$hasil1 = $conn->query($sql1);
$total_records = $hasil1->num_rows;
?>

<!-- Menampilkan jumlah total gallery -->
<p>Total gallery: <?php echo $total_records; ?></p>

<!-- Paginasi -->
<nav class="mb-2">
    <ul class="pagination justify-content-end">
        <?php
        // Hitung jumlah halaman
        $jumlah_page = ceil($total_records / $limit);
        $jumlah_number = 1; // Jumlah halaman ke kanan dan kiri dari halaman yang aktif
        $start_number = ($hlm > $jumlah_number) ? $hlm - $jumlah_number : 1;
        $end_number = ($hlm < ($jumlah_page - $jumlah_number)) ? $hlm + $jumlah_number : $jumlah_page;

        // Tombol "First" dan "Previous"
        if($hlm == 1){
            echo '<li class="page-item disabled"><a class="page-link" href="#">First</a></li>';
            echo '<li class="page-item disabled"><a class="page-link" href="#"><span aria-hidden="true">&laquo;</span></a></li>';
        } else {
            $link_prev = ($hlm > 1) ? $hlm - 1 : 1;
            echo '<li class="page-item halaman" id="1"><a class="page-link" href="#">First</a></li>';
            echo '<li class="page-item halaman" id="'.$link_prev.'"><a class="page-link" href="#"><span aria-hidden="true">&laquo;</span></a></li>';
        }

        // Halaman yang ada
        for($i = $start_number; $i <= $end_number; $i++){
            $link_active = ($hlm == $i) ? ' active' : '';
            echo '<li class="page-item halaman '.$link_active.'" id="'.$i.'"><a class="page-link" href="#">'.$i.'</a></li>';
        }

        // Tombol "Next" dan "Last"
        if($hlm == $jumlah_page){
            echo '<li class="page-item disabled"><a class="page-link" href="#"><span aria-hidden="true">&raquo;</span></a></li>';
            echo '<li class="page-item disabled"><a class="page-link" href="#">Last</a></li>';
        } else {
            $link_next = ($hlm < $jumlah_page) ? $hlm + 1 : $jumlah_page;
            echo '<li class="page-item halaman" id="'.$link_next.'"><a class="page-link" href="#"><span aria-hidden="true">&raquo;</span></a></li>';
            echo '<li class="page-item halaman" id="'.$jumlah_page.'"><a class="page-link" href="#">Last</a></li>';
        }
        ?>
    </ul>
</nav>

<?php $conn->close(); ?>
