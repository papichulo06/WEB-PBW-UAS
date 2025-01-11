<?php
include "koneksi.php";  

// Cek jika belum ada user yang login, arahkan ke halaman login
if (!isset($_SESSION['username'])) { 
    header("location:login.php"); 
}
?>

<div class="container">
    <!-- Button trigger modal untuk menambah profil -->
    <button type="button" class="btn btn-secondary mb-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg"></i> Tambah Profil
    </button>
    
    <div class="row">
        <div class="table-responsive" id="profile_data">
            <!-- Data profil akan dimuat di sini -->
        </div>
    </div>

    <!-- Modal Tambah Profil -->
    <div class="modal fade" id="modalTambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Profil</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="Username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="Username" name="Username" placeholder="Tuliskan Username" required>
                        </div>
                        <div class="mb-3">
                            <label for="Password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="Password" name="Password" placeholder="Tuliskan Password" required>
                        </div>
                        <div class="mb-3">
                            <label for="Foto" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="Foto" name="Foto">
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
</div>
<form method="post" action="" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $row['id'] ?>"> <!-- Menambahkan hidden ID -->
    <!-- Form input lainnya -->
</form>
<form method="post" action="" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $row['id'] ?>"> <!-- Menambahkan hidden ID -->
    <input type="hidden" name="foto" value="<?= $row['foto'] ?>"> <!-- Menambahkan foto lama -->
    <!-- Form input lainnya -->
</form>

<script>
$(document).ready(function(){
    load_data();
    
    // Fungsi untuk memuat data profil
    function load_data(hlm = 1) {
        $.ajax({
            url: "profile_data.php",
            method: "POST",
            data: { hlm: hlm },
            success: function(data) {
                $('#profile_data').html(data);
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
    $username = $_POST['Username'];
    $password = $_POST['Password'];
    $foto = '';
    $nama_foto = $_FILES['Foto']['name'];

    // Proses upload foto
    if ($nama_foto != '') {
        $cek_upload = upload_foto($_FILES["Foto"]);
        if ($cek_upload['status']) {
            $foto = $cek_upload['message'];
        } else {
            echo "<script>
                alert('" . $cek_upload['message'] . "');
                document.location='profile.php';
            </script>";
            die;
        }
    }

    // Proses insert data pengguna baru
    $password_encrypted = md5($password);
    $stmt = $conn->prepare("INSERT INTO user (username, password, foto) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password_encrypted, $foto);
    $simpan = $stmt->execute();
   

    // Feedback setelah simpan data
    if ($simpan) {
        echo "<script>
            alert('Simpan data sukses');
            document.location='profile.php';
        </script>";
    } else {
        echo "<script>
            alert('Simpan data gagal');
            document.location='profile.php';
        </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
<?php
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $username = $_POST['Username'];
    $password = $_POST['Password'];
    $fotoLama = $_POST['foto_lama'];

    // Proses upload foto jika ada
    if ($_FILES['Foto']['name'] != '') {
        $foto = $_FILES['Foto']['name'];
        $tmp = $_FILES['Foto']['tmp_name'];
        $path = "img/" . $foto;
        move_uploaded_file($tmp, $path);
    } else {
        $foto = $fotoLama;
    }

    // Jika password diisi, update password
    if (!empty($password)) {
        $password_encrypted = md5($password);
        $sql = "UPDATE user SET username='$username', password='$password_encrypted', foto='$foto' WHERE id='$id'";
    } else {
        $sql = "UPDATE user SET username='$username', foto='$foto' WHERE id='$id'";
    }



    if ($conn->query($sql)) {
        echo "<script>alert('Data berhasil diupdate!'); </script>";
    } else {
        echo "<script>alert('Gagal update data!');</script>";
    }
}
?>
<?php
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];
    $foto = $_POST['foto'];

    // Hapus file foto jika ada
    if ($foto != '' && file_exists("img/$foto")) {
        unlink("img/$foto");
    }

    // Hapus data dari database
    $sql = "DELETE FROM user WHERE id='$id'";

    if ($conn->query($sql)) {
        echo "<script>alert('Data berhasil dihapus!'); </script>";
    } else {
        echo "<script>alert('Gagal menghapus data!'); </script>";
    }
}
?>



