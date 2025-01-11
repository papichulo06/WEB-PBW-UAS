<?php
include "koneksi.php"; 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tugas PBW UAS</title>
    <link rel="icon" href="img/logo.png" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
      crossorigin="anonymous"
    />
    <style>
      :root {
        --primary-color: #6482ad;
        --secondary-color: #7fa1c3;
        --background-light: #e2dad6;
        --background-dark: #f5eded;
      }

      body {
        background-color: var(--background-light);
        color: var(--primary-color);
      }

      .navbar {
        background-color: var(--primary-color);
      }

      .navbar-brand,
      .nav-link {
        color: white;
      }

      .nav-link:hover {
        color: var(--background-dark);
      }

      .btn-theme {
        border: none;
        margin-left: 10px;
      }

      .hero {
        background-color: var(--background-dark);
        color: var(--primary-color);
      }

      .card {
        border: none;
        background-color: var(--secondary-color);
        color: white;
      }

      footer {
        background-color: var(--primary-color);
        color: white;
      }

      footer a {
        color: white;
        margin: 0 10px;
      }

      .schedule-card {
        background-color: var(--secondary-color);
        color: white;
        border: none;
      }

      .schedule-card-header {
        background-color: var(--primary-color);
        color: white;
      }

      .about-section {
        background-color: var(--background-dark);
        color: var(--primary-color);
      }

      .about-img {
        border: 5px solid var(--primary-color);
        border-radius: 50%;
      }
    </style>
  </head>
  <body>
    <!-- NAV BEGIN -->
    <nav class="navbar navbar-expand-lg sticky-top">
      <div class="container">
        <a class="navbar-brand fw-bold" href="#">Scent Sanctuary</a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="#home">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#article">Articles</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#gallery">Gallery</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#schedule">Member</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#about">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="login.php">Login</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- NAV END -->

    <!-- HERO BEGIN -->
    <section id="hero" class="hero text-center py-5">
      <div class="container">
        <h1 class="display-4 fw-bold">Discover the Essence of Elegance</h1>
        <p class="lead">Welcome to Scent Sanctuary, where every fragrance tells a story. Our carefully curated collection of perfumes combines the finest ingredients with timeless artistry, creating scents that captivate and inspire. Whether you seek a bold signature scent or a subtle whisper of sophistication, explore our range and find your perfect match.</p>
        <div id="datetime" class="mt-3"></div>
      </div>
    </section>
    <!-- HERO END -->

    <!-- ARTICLES BEGIN -->
    <section id="article" class="py-5">
      <div class="container">
        <h2 class="text-center fw-bold mb-4">Latest Articles</h2>
        <div class="row">
          <?php
          $sql = "SELECT * FROM article ORDER BY tanggal DESC";
          $hasil = $conn->query($sql);

          while ($row = $hasil->fetch_assoc()) {
          ?>
            <div class="col-md-4 mb-4">
              <div class="card h-100">
                <img src="img/<?= $row['gambar'] ?>" class="card-img-top" alt="Article Image" />
                <div class="card-body">
                  <h5 class="card-title"><?= $row['judul'] ?></h5>
                  <p class="card-text"><?= substr($row['isi'], 0, 100) ?>...</p>
                </div>
                <div class="card-footer">
                  <small class="text-white">Published on: <?= $row['tanggal'] ?></small>
                </div>
              </div>
            </div>
          <?php
          }
          ?>
        </div>
      </div>
    </section>
    <!-- ARTICLES END -->

    <!-- GALLERY BEGIN -->
    <section id="gallery" class="py-5 bg-secondary">
      <div class="container">
        <h2 class="text-center fw-bold text-white mb-4">Gallery</h2>
        <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="img/123.webp"  class="d-block w-100" alt="..." />
            </div>
            <div class="carousel-item">
              <img src="img/12.webp" class="d-block w-100" alt="..." />
            </div>
            <div class="carousel-item">
              <img src="img/11.webp" class="d-block w-100" alt="..." />
            </div>
          </div>
          <button
            class="carousel-control-prev"
            type="button"
            data-bs-target="#carouselExample"
            data-bs-slide="prev"
          >
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button
            class="carousel-control-next"
            type="button"
            data-bs-target="#carouselExample"
            data-bs-slide="next"
          >
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>
    </section>
    <!-- GALLERY END -->

  <!-- SCHEDULE BEGIN -->
  <section id="schedule" class="d-flex justify-content-center align-items-center text-center vh-100">
  <div class="container">
    <h2 class="text-center fw-bold mb-4">Member</h2>
    <div class="row row-cols-1 row-cols-md-4 g-4 justify-content-center">
      <div class="col">
        <div class="card schedule-card">
          <div class="card-header schedule-card-header">
            Deni Kurniawan 
            <br>  
            A11.2023.15417 
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card schedule-card">
          <div class="card-header schedule-card-header">
            Egi indra raditya 
            <br>  
            A11.2023.15438
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card schedule-card">
          <div class="card-header schedule-card-header">
            Mochammad Prasetyawan
            <br>
            A11.2023.15439
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

  <!-- SCHEDULE END -->



    <!-- ABOUT BEGIN -->
    <section id="about" class="about-section text-center py-5">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-4">
            <img src="img/Logo_udinus1.jpg" class="about-img w-100" alt="Profile Image" />
          </div>
          <div class="col-md-8 text-md-end">
            <h3 class="fw-bold">KELOMPOK PEMROGRAMAN BERBASIS WEB</h3>
            <p>Student of Informatics Engineering<br />Universitas Dian Nuswantoro</p>
          </div>
        </div>
      </div>
    </section>
    <!-- ABOUT END -->

    <!-- FOOTER BEGIN -->
    <footer class="text-center py-4">
      <div class="container">
        <p>PBW UAS &copy; 2024</p>
        <div>
          <a href="#"><i class="bi bi-instagram"></i></a>
          <a href="#"><i class="bi bi-twitter"></i></a>
          <a href="#"><i class="bi bi-whatsapp"></i></a>
        </div>
      </div>
    </footer>
    <!-- FOOTER END -->

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
      crossorigin="anonymous"
    ></script>
    <script>
      const datetime = new Date();
      document.getElementById("datetime").innerText = datetime.toDateString();
    </script>
  

</body>
</html>