<?php
    session_start();

    if(!isset($_SESSION['user']) && $_SESSION['user']['type'] != 'student') {
        header('location:../index.php');
    }
    else{
        include_once("../db-socket.php");
        // preluare date curs din db
        $id_curs = $_GET['idc'];
        $sectiune_curenta = $_GET['ids'];
        $query = "SELECT * FROM sectiuni WHERE id = $sectiune_curenta";
        $result = QueryDB($query);
        if($result->num_rows == 1) {
            $sectiune = $result->fetch_assoc();
        } else {
            header('location:../index.php');
        }

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

        <title></title>
    </head>

    <body>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg classic navbar-light navbar-bg-light">
            <div class="container flex-lg-row flex-nowrap align-items-center">
                <div class="navbar-brand w-100">
                <a href="../index.php">
                    <img src="/logo.png" srcset="/logo.png 2x" alt="" />
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

        <!-- Sectiune informatii lectie -->
        <section class="wrapper bg-light mt-5">
            <div class="container pb-5">
                <div class="card image-wrapper bg-full bg-image bg-overlay bg-overlay-300 mb-10" data-image-src="/bg16.png">
                <div class="card-body p-10 p-xl-12">
                    <div class="row text-center">
                    <div class="col-xl-11 col-xxl-9 mx-auto">
                        <h3 class="display-3 mb-8 px-lg-8 text-white"><?=$sectiune['titlu'] ?></h3>
                        <h2 class="fs-16 text-uppercase text-white mb-3"><i class="uil uil-clock me-1"></i><?=$sectiune['read_time'] ?> min</h2>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </section>

        <!-- Sectiune lectie -->
        <div class="container">
            <h2>Parcurge lectia de mai jos</h2>
            <object class="pdf" data=<?="../cursuri/".$sectiune["path_fisier"] ?> style="width: 100%;" height="800"></object>

            <div class="row mt-10 mb-10">
                <div class="col-md-6">
                    <a href="s_dashboard.php" class="btn btn-soft-primary">Inapoi</a>
                </div>
                <div class="col-md-6 text-end">
                    <?php echo '<a href="pagina_curs.php?id='.$id_curs.'&sc='.$sectiune_curenta.'&f=1" class="btn btn-primary">Finalizare</a>' ?>
                    
                </div>
            </div>
        </div>
    </body>
</html>