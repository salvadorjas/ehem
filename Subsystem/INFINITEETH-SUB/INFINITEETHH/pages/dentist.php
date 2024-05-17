<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-image: url('dentistBG.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        form {
            margin-top: 150px;
            width: 500px;
            background-color: beige;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background-color: rgba(128, 128, 128, 0.5);
            backdrop-filter: blur(10px);
        }
        .navbar-brand img {
            width: 100px;
            margin-right: 5px;
        }
        .navbar-brand {
            font-size: 20px;
            color: white !important;
        }
        .offcanvas-body {
            color: black;
        }
        .navbar-nav .nav-link {
            color: white !important;
        }
        .navbar-toggler {
            color: white !important;
        }
        .container {
            padding-right: 15px;
            padding-left: 15px;
            margin-right: 100px;
            margin-left: 100px;
        }
        .paragraph-container {
            font-family: arial;
            text-align: justify;
            background-color: rgba(128, 128, 128, 0.5);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 10px;
            margin-top: 150px;
            max-width: 80%;
        }
        .homeimg {
            margin-top: 150px;
            margin-right: auto;
            text-align: center;
        }
        .homeimg img {
            max-width: 400px;
            max-height: 300px;
            border-radius: 10px;
        }
        .dentist-header {
            margin-top: 30px;
            text-align: center;
        }
        .card {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .card-body {
            padding: 20px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="logooo-removebg-preview.png" alt="Logo" class="d-inline-block align-top">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarOffcanvasLg" aria-controls="navbarOffcanvasLg" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon bg-white"></span>
        </button>
        <div class="offcanvas offcanvas-end bg-black" tabindex="-1" id="navbarOffcanvasLg" aria-labelledby="navbarOffcanvasLgLabel">
            <div class="offcanvas-header">
                <h1 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Infiniteeth</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0" style="margin-top: 30px;">
                    <li class="nav-item dropdown" style="margin-right: 65px;">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Dropdown
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                    <li class="nav-item" style="margin-right: 65px;">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="paragraph-container">
                <p class="h3">When it comes to your dental health, only the best will provide your desired treatment, which is why you should trust us with your smiles. We'll guide you through every stage of the next phase of your treatments. From your first consultation to a lifetime of maintenance. We believe that a beautiful smile is important to your quality of life. We will work with you to help achieve the best results possible in a fun and relaxed environment.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="homeimg">
                <img src="home img.jpg" alt="Image">
            </div>
        </div>
    </div>
</div>

<div class="container">
    <h2 class="dentist-header">List of Available Dentists</h2>
    <div class="row">
        <?php
        include('../connection/connection.php');
        $con = connection();

        $sql = "SELECT * FROM dentist";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="col-md-4">';
                echo '<div class="card">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $row['fullname'] . '</h5>';
                echo '<p class="card-text"><strong>Email:</strong> ' . $row['email'] . '</p>';
                echo '<p class="card-text"><strong>Working Day Hours:</strong> ' . $row['workingdayhours'] . '</p>';
                echo '<p class="card-text"><strong>Service Offered:</strong> ' . $row['serviceoffered'] . '</p>';
                echo '<p class="card-text"><strong>Specialization:</strong> ' . $row['specialization'] . '</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>No dentists found.</p>';
        }
        mysqli_close($con);
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>