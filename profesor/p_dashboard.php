<?php
    session_start();
    if(!isset($_SESSION['user']) && $_SESSION['user']['type'] != 'profesor') {
        header('location:../index.php');
    }
    else{
        include_once("../db-socket.php");

    }
?>

<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="/assets/css/plugins.css">
        <link rel="stylesheet" href="/assets/css/style.css">
        <link rel="stylesheet" href="/assets/css/colors/sky.css">
        <link rel="preload" href="/assets/css/fonts/urbanist.css" as="style" onload="this.rel='stylesheet'">
        <!-- Bootstrap icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
             
        <title>Dashboard Profesor</title>
    </head>


    <body>
        
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg classic navbar-light navbar-bg-light">
            <div class="container flex-lg-row flex-nowrap align-items-center">
                <div class="navbar-brand w-100">
                <a href="./index.html">
                    <!-- <img src="./assets/img/logo.png" srcset="./assets/img/logo@2x.png 2x" alt="" /> -->
                     <h2>LessonsLab</h2>
                </a>
                </div>
                <div class="navbar-collapse offcanvas offcanvas-nav offcanvas-start">
                <div class="offcanvas-header d-lg-none">
                    <a href="./index.html"><img src="./assets/img/logo-light.png" srcset="./assets/img/logo-light@2x.png 2x" alt="" /></a>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body ms-lg-auto d-flex flex-column h-100">
                    <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="#">Acasa</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Descopera</a></li>
                    <li class="nav-item"><a class="nav-link" href="curs_nou.php">+ Creeaza curs nou</a></li>
                </div>
                <!-- /.offcanvas-body -->
                </div>
                <!-- /.navbar-collapse -->
                <div class="navbar-other ms-lg-4">
                <ul class="navbar-nav flex-row align-items-center ms-auto">
                    <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"><i class="bi bi-person-circle"></i></a>
                        <ul class="dropdown-menu">
                        <li class="nav-item"><a class="dropdown-item" href="#">Profilul meu</a></li>
                        <li class="nav-item"><a class="dropdown-item" href="../index.php?a=d">Deconecteaza-te</a></li>
                        </ul>
                    </li>
                </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <h1>Salut, <?= $_SESSION['user']['data']['prenume'] ?></h1>
            <h2 class="mt-5">Cursurile tale</h2>

            <?php
            
            if(isset($_SESSION['user']['data']['cursuri'])) {
                $result = QueryDB('SELECT * FROM `cursuri` WHERE id IN ('.$_SESSION['user']['data']['cursuri'].')');
                if($result->num_rows > 1) {
                    $cursuri = $result->fetch_assoc();

                }
                else if ($result->num_rows == 1) {
                    $cursuri = [$result->fetch_assoc()];
                }
                else {
                    $cursuri = [];
                }

                if(count($cursuri) == 0){
                    echo '<p>Nu ai niciun curs creat inca. <a href="#" data-bs-toggle="modal" data-bs-target="#modal-curs-nou">Creeaza unul acum</a></p>';
                }
                else {
                    echo '<a href="#" class="card mb-4 lift">';
                    $counter = 1;
                    foreach($cursuri as $curs) {
                        ?>
                        <div class="card-body p-5">
                            <span class="row justify-content-between align-items-center">
                                <span class="col-md-5 mb-2 mb-md-0 d-flex align-items-center text-body">
                                    <span class="avatar bg-red text-white w-9 h-9 fs-17 me-3"><?=$counter?></span><?=$curs['nume'] ?></span>
                                <span class="col-5 col-md-3 text-body d-flex align-items-center">
                                    <i class="bi bi-person-fill me-1"></i> - studenti inrolati </span>
                                <span class="col-7 col-md-4 col-lg-3 text-body d-flex align-items-center">
                                    ID curs: <?=$curs['id'] ?> </span>
                                <span class="d-none d-lg-block col-1 text-center text-body">
                                    Actiuni <i class="uil uil-angle-right-b"></i>
                                </span>
                            </span>
                        </div>
                        <?php
                    }
                }


            }
            else {
                echo '<p>Nu ai niciun curs creat inca. <a href="#" data-bs-toggle="modal" data-bs-target="#modal-curs-nou">Creeaza unul acum</a></p>';
            }

            ?>
        </div>

    </body>
</html>