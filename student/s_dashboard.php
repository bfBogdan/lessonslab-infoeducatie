<?php
    session_start();
    if(!isset($_SESSION['user']) && $_SESSION['user']['type'] != 'student') {
        header('location:../index.php');
    }
    else{
        include_once("../db-socket.php");
        if($_GET['a'] == 'ac') {
            $idCurs = $_POST['cod-curs'];
            $query = 'SELECT * FROM `cursuri` WHERE `id` = '.$idCurs;
            $result = QueryDB($query);
            if($result->num_rows == 1) {
                // adaugare curs in lista de cursuri a studentului
                $curs = $result->fetch_assoc();
                $cursuri = $_SESSION['user']['cursuri'];
                $cursuri .= ','.$curs['id'].'|1';
                $_SESSION['user']['cursuri'] = $cursuri;

                // actualizare in baza de date
                $query = 'UPDATE `users` SET `cursuri` = "'.$cursuri.'" WHERE `id` = '.$_SESSION['user']['id'];
                QueryDB($query);
            }
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
             
        <title>Dashboard Student</title>
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
                    <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#modal-curs-nou">+ Adauga curs nou</a></li>
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

        <!-- Modal adauga curs nou -->
        <div class="modal fade" id="modal-curs-nou" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content text-center">
                <div class="modal-body">
                    <h5 class="mb-4">Adauga un curs nou</h5>
                    <form action="s_dashboard.php?a=ac" method="POST">
                        <div class="form-floating mb-4">
                            <input id="inputCodCurs" type="text" class="form-control" placeholder="Text Input">
                            <label for="inputCodCurs" name="cod-curs">ID CURS</label>
                        </div>
                        <a href="#" class="btn btn-expand btn-primary rounded-pill" type="submit">
                            <i class="bi bi-plus-circle"></i>
                            <span>Adauga curs</span>
                        </a>
                    </form>
                </div>
                </div>
            </div>
        </div>
        <!-- /. Modal adauga curs nou -->

        <div class="container">

            <!-- Sectiunea cursuri active -->
            <h1>Cursuri active</h1>
            <?php
                // preluare cursuri active din baza de date

                $idCursuri = '';
                if(isset($_SESSION['user']['cursuri'])) {
                    // preluare id-uri cursuri din string si transformare in array
                    $cursuriString = $_SESSION['user']['cursuri'];
                    $cursuriArray = explode(',', $cursuriString);
                    $idCursuri = implode(',', array_map(function($curs) {
                        $cursData = explode('|', $curs);
                        return $cursData[0];
                    }, $cursuriArray));

                    $idLectii = implode(',', array_map(function($curs) {
                        $cursData = explode('|', $curs);
                        return $cursData[1];
                    }, $cursuriArray));

                    // query cursuri din baza de date
                    $query = 'SELECT * FROM `cursuri` WHERE `id` IN ('.$idCursuri.')';
                    $result = QueryDB($query);
                    if($result->num_rows > 1) {
                        $cursuri = $result->fetch_assoc();
                        var_dump($cursuri);
                    }
                    else if ($result->num_rows == 1) {
                        $cursuri = [$result->fetch_assoc()];
                    }
                    else {
                        $cursuri = [];
                    }
                }
                else {
                    $cursuri = [];
                }

                // daca nu exista cursuri active...
                if(count($cursuri) == 0) {
                    echo 'Nu sunt cursuri active';
                } 

                // daca exista cursuri active...
                else {
                    echo '<div class="swiper-container mb-10" data-margin="30" data-nav="true" data-dots="true" data-items-xl="3" data-items-md="2" data-items-xs="1">';
                    echo '<div class="swiper">';
                    echo '<div class="swiper-wrapper">';
                    $counter = 0;
                    $val_lectii_curs = array();
                    $idLectii = explode(',', $idLectii);
                    
                    $counter = 0;
                    foreach($cursuri as $curs) {
                        // preluare progres curs
                        $query = 'SELECT * FROM `sectiuni` WHERE `id` = '.$idLectii[$counter];
                        $result = QueryDB($query);
                        if($result->num_rows != 0) {
                            $lectii_curs = $result->fetch_assoc();
                            array_push($val_lectii_curs, [$lectii_curs, $curs]);
                        }

                        echo '<div class="swiper-slide" style="max-height: 100px;">';
                        echo '<div class="col">';
                        echo '<div class="card d-flex align-items-stretch mt-5" >';
                        echo '<div class="card-body">';
                        echo '<i class="'.$curs['icon'].'" style="font-size: 2rem;"></i>';
                        echo '<h5 class="card-title mt-3">'.$curs['nume'].'</h5>';
                        echo '<p class="card-text">'.$curs['descriere'].'</p>';
                        //echo '<div class="progress"> <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="'.$curs['progres'].'"></div></div>'
                        echo '<a href="pagina_curs.php?id='.$curs['id'].'&sc='.$idLectii[$counter].'" class="btn btn-primary">Detalii</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';

                        $counter++;
                    }
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            ?>
            <!-- /. Sectiunea cursuri active -->

            <!-- Sectiunea continua lectiile -->
            <?php
                // daca nu exista lectii active...
                if(count($val_lectii_curs) == 0) {
                    echo 'Nu sunt lectii active';
                } 

                // daca exista lectii active...
                else {
                    echo '<h3 class="mb-4">Continua lectiile</h3>';
                    echo '<a href="#" class="card mb-4 lift">';
                    foreach($val_lectii_curs as $lectie) {
                        ?>
                        <div class="card-body p-5">
                            <span class="row justify-content-between align-items-center">
                                <span class="col-md-5 mb-2 mb-md-0 d-flex align-items-center text-body">
                                    <span class="avatar bg-red text-white w-9 h-9 fs-17 me-3"><i class="<?=$lectie[1]['icon'] ?>"></i></span><?=$lectie[0]['titlu'] ?></span>
                                <span class="col-5 col-md-3 text-body d-flex align-items-center">
                                    <i class="bi bi-file-earmark-post me-1"></i></i> <?=$lectie[1]['nume'] ?> </span>
                                <span class="col-7 col-md-4 col-lg-3 text-body d-flex align-items-center">
                                    <i class="uil uil-clock me-1"></i> <?=$lectie[0]['read_time'] ?> min </span>
                                <span class="d-none d-lg-block col-1 text-center text-body">
                                    <i class="uil uil-angle-right-b"></i>
                                </span>
                            </span>
                        </div>
                        <?php
                    }
                }
            ?>
            <!-- /. Sectiunea continua lectiile -->
        
        </div>

         <!-- Scripturi design bootstrap -->
         <script src="/assets/js/plugins.js"></script>
         <script src="/assets/js/theme.js"></script>
    </body>
</html>