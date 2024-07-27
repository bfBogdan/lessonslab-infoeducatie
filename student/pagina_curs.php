<?php
    session_start();

    if(!isset($_SESSION['user']) && $_SESSION['user']['type'] != 'student') {
        header('location:../index.php');
    }
    else{
        include_once("../db-socket.php");
        // preluare date curs din db
        $id_curs = $_GET['id'];
        $sectiune_curenta = $_GET['sc'];
        $query = "SELECT * FROM cursuri WHERE id = $id_curs";
        $result = QueryDB($query);
        if($result->num_rows >= 1) {
            $curs = $result->fetch_assoc();
            // preluare lectii din db
            $query = "SELECT * FROM sectiuni WHERE id IN (".$curs['id_sectiuni'].")";
            $result = QueryDB($query);
            if($result->num_rows > 1) {
                $lectii = $result->fetch_all(MYSQLI_ASSOC);
            }
            else if($result->num_rows == 1) {
                $lectii = [$result->fetch_assoc()];
            }
        } else {
            //header('location:../index.php');
        }

        if(isset($_GET['f']) && $_GET['f'] == '1') {
            $idSectiuni = explode(",", $curs['id_sectiuni']);
            $pozitieCursTerminat = array_search($sectiune_curenta, $idSectiuni);
            $cursuriSiLectii = explode(",", $_SESSION['user']['cursuri']);
            $contorPoz = 0;
            foreach ($cursuriSiLectii as $cursSiLectii){
                $cursSiLectii = explode("|", $cursSiLectii);
                if($cursSiLectii[0] == $id_curs){
                    $cursSiLectii[1] = $idSectiuni[$pozitieCursTerminat+1];
                    $cursSiLectii = implode("|", $cursSiLectii);
                    $cursuriSiLectii[$contorPoz] = $cursSiLectii;
                    $cursuriSiLectii = implode(",", $cursuriSiLectii);
                    break;
                }
                $contorPoz++;
            }
            // update sesiune
            $_SESSION['user']['cursuri'] = $cursuriSiLectii;

            // update user in db
            $query = "UPDATE users SET cursuri = '$cursuriSiLectii' WHERE id = ".$_SESSION['user']['id'];
            var_dump($_SESSION);
            QueryDB($query);
            
            header('location:pagina_curs.php?id='.$id_curs.'&sc='.$idSectiuni[$pozitieCursTerminat+1]);
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

    <section class="wrapper bg-light mt-5">
        <div class="container pb-5">
            <div class="card image-wrapper bg-full bg-image bg-overlay bg-overlay-300 mb-10" data-image-src="/assets/img/photos/bg16.png">
            <div class="card-body p-10 p-xl-12">
                <div class="row text-center">
                <div class="col-xl-11 col-xxl-9 mx-auto">
                    <h3 class="display-3 mb-8 px-lg-8 text-white">Curs de <?=$curs['nume'] ?></h3>
                    <h2 class="fs-16 text-uppercase text-white mb-3"><?=$curs['tags'] ?></h2>
                </div>
                <!-- /column -->
                </div>
                <!-- /.row -->
                <div class="d-flex justify-content-center">
                <span><a class="btn btn-white rounded">Continua cursul</a></span>
                </div>
            </div>
            <!--/.card-body -->
            </div>
            <!--/.card -->
        </div>
        <!-- /.container -->
    </section>
    <!-- /section -->

    <div class="container">
        <h3 class="mb-4">Sectiuni curs</h3>
            <?php
                $contor = 1;
                foreach($lectii as $lectie) { 
                    if($contor == $sectiune_curenta) {
                        ?>
                        <a href=<?php echo "pagina_doc.php?idc=".$id_curs."&ids=".$lectie["id"] ?> class="card mb-4 lift">
                            <div class="card-body p-5">
                                <span class="row justify-content-between align-items-center">
                                    <span class="col-md-5 mb-2 mb-md-0 d-flex align-items-center text-body">
                                        <span class="avatar bg-red text-white w-9 h-9 fs-17 me-3"><?=$contor?></span><?=$lectie['titlu'] ?></span>
                                    <span class="col-7 col-md-4 col-lg-3 text-body d-flex align-items-center">
                                        <i class="uil uil-clock me-1"></i> <?=$lectie['read_time'] ?> min </span>
                                    <span class="d-none d-lg-block col-1 text-center text-body">
                                        <i class="uil uil-angle-right-b"></i>
                                    </span>
                                </span>
                            </div>
                        </a>
                        <?php
                    }
                    else{
                        ?>
                        <a href="#" class="card mb-4 lift">
                            <div class="card-body p-5">
                                <span class="row justify-content-between align-items-center">
                                    <span class="col-md-5 mb-2 mb-md-0 d-flex align-items-center text-body">
                                        <span class="avatar bg-ash text-white w-9 h-9 fs-17 me-3"><?=$contor?></span><?=$lectie['titlu'] ?></span>
                                    <span class="col-7 col-md-4 col-lg-3 text-body d-flex align-items-center">
                                        <i class="uil uil-clock me-1"></i> <?=$lectie['read_time'] ?> min </span>
                                    <span class="d-none d-lg-block col-1 text-center text-body">
                                        <i class="uil uil-angle-right-b"></i>
                                    </span>
                                </span>
                            </div>
                        </a>
                        <?php
                    }
                    ?>
                    
            <?php 
                    $contor++;
                }
             ?>
        </a>
    </div>
    <!-- Scripturi design bootstrap -->
    <script src="/assets/js/plugins.js"></script>
    <script src="/assets/js/theme.js"></script>
    </body>
</html>