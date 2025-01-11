<?php
include "koneksi.php";  

// Cek jika belum ada user yang login, arahkan ke halaman login
if (!isset($_SESSION['username'])) { 
    header("location:login.php"); 
}
?>

<div class="container">
    <!-- Button trigger modal untuk menambah gallery -->
    <button type="button" class="btn btn-secondary mb-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg"></i> Tambah Gallery
    </button>
    
    <div class="row">
        <div class="table-responsive" id="gallery_data">
            <!-- Data gallery akan dimuat di sini -->
        </div>
    </div>

    <!-- Modal Tambah Gallery -->
    <div class="modal fade" id="modalTambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Gallery</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Tuliskan Nama Gallery" required>
                        </div>
                        <div class="mb-3">
                            <label for="Gambar" class="form-label">Gambar</label>
                            <input type="file" class="form-control" id="Gambar" name="Gambar" required>
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
    <!-- Akhir Modal Tambah-->
</div>

<script>
$(document).ready(function(){
    load_data();
    
    // Fungsi untuk memuat data gallery
    function load_data(hlm = 1) {
        $.ajax({
            url: "gallery_data.php",
            method: "POST",
            data: { hlm: hlm },
            success: function(data) {
                $('#gallery_data').html(data);
            }
        });
    }

    // Logika untuk pagination
    $(document).on('click', '.halaman', function(){
        var hlm = $(this).attr("id");
        load_data(hlm);
    });
});
</script>

<?php
include "upload_foto.php";

// Cek jika tombol simpan diklik
if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $tanggal = date("Y-m-d H:i:s");
    $gambar = '';
    $nama_gambar = $_FILES['Gambar']['name'];

    // Proses upload gambar
    if ($nama_gambar != '') {
        $cek_upload = upload_foto($_FILES["Gambar"]);
        if ($cek_upload['status']) {
            $gambar = $cek_upload['message'];
        } else {
            echo "<script>
                alert('" . $cek_upload['message'] . "');
                document.location='admin.php?page=gallery';
            </script>";
            die;
        }
    }

    // Cek apakah form ini untuk update atau insert
    if (isset($_POST['id'])) {
        // Proses update gallery
        $id = $_POST['id'];

        // Jika gambar baru tidak ada, pakai gambar lama
        if ($nama_gambar == '') {
            $gambar = $_POST['gambar_lama'];
        } else {
            // Jika gambar baru ada, hapus gambar lama
            unlink("img/" . $_POST['gambar_lama']);
        }

        // Query update gallery
        $stmt = $conn->prepare("UPDATE gallery 
                                SET nama =?, gambar =?, tanggal =? 
                                WHERE id =?");
        $stmt->bind_param("sssi", $nama, $gambar, $tanggal, $id);
        $simpan = $stmt->execute();
    } else {
        // Proses insert gallery baru
        $stmt = $conn->prepare("INSERT INTO gallery (nama, gambar, tanggal) 
                                VALUES (?,?,?)");
        $stmt->bind_param("sss", $nama, $gambar, $tanggal);
        $simpan = $stmt->execute();
    }

    // Feedback setelah simpan data
    if ($simpan) {
        echo "<script>
            alert('Simpan data sukses');
            document.location='admin.php?page=gallery';
        </script>";
    } else {
        echo "<script>
            alert('Simpan data gagal');
            document.location='admin.php?page=gallery';
        </script>";
    }

    $stmt->close();
    $conn->close();
}

// Cek jika tombol hapus diklik
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];
    $gambar = $_POST['gambar'];

    // Hapus file gambar jika ada
    if ($gambar != '') {
        unlink("img/" . $gambar);
    }

    // Query untuk menghapus gallery
    $stmt = $conn->prepare("DELETE FROM gallery WHERE id =?");
    $stmt->bind_param("i", $id);
    $hapus = $stmt->execute();

    // Feedback setelah hapus data
    if ($hapus) {
        echo "<script>
            alert('Hapus data sukses');
            document.location='admin.php?page=gallery';
        </script>";
    } else {
        echo "<script>
            alert('Hapus data gagal');
            document.location='admin.php?page=gallery';
        </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
