<?php
    session_start(); 

    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    $action = isset($_GET["a"]) ? $_GET["a"] : "";
    if($action == "d") {
        session_destroy();
        header("Location: index.php");
        exit;
    }

    // logout
    if(isset($_GET["iesire"])) {
        //session_destroy();
        unset($_SESSION["logged_user"]);
        unset($_SESSION["login_time"]);
    }     

    $userAutentificat = isset($_SESSION["user"]);
    $alertLoginFailedVisible = false;

    if($userAutentificat) {
        if($_SESSION["user"]["type"] == "student") {
            header("Location: student/s_dashboard.php");
            exit;
        }
        else if($_SESSION["user"]["type"] == "profesor") {
            header("Location: profesor/p_dashboard.php");
            exit;
        }
    }
    
    if(!$userAutentificat) {
        
        // formularul a fost trimis
        if(isset($_POST["type"]) && $_POST["type"] == "login-student") {
            
            include_once("db-socket.php");
            
            $result = QueryDB('SELECT * FROM `users` WHERE email="'.$_POST["email"].'" AND pass=MD5("'.$_POST["password"].'") AND rol=2');
            var_dump($result);
            if($result->num_rows == 1) {
                
                // userul e autentificat cu succes
                $user = $result->fetch_assoc();
                $_SESSION["user"]["type"] = "student";
                $_SESSION["user"]["cursuri"] = $user["cursuri"];
                $_SESSION["login_time"] = time();
                $_SESSION["user"]["id"] = $user["id"];
                
                header("Location: student/s_dashboard.php");
                exit;
            }
            else {            
                // userul nu s-a autentificat cu succes, userul nu e bun sau parola            
                $alertLoginFailedVisible = true;
            }
        }
        else if(isset($_POST["type"]) && $_POST["type"] == "login-profesor") {
            
            include_once("db-socket.php");
            
            $result = QueryDB('SELECT * FROM `users` WHERE email="'.$_POST["email"].'" AND pass=MD5("'.$_POST["password"].'") AND rol=1');
                   
            if($result->num_rows == 1) {
                
                // userul e autentificat cu succes
                $user = $result->fetch_assoc();
                $_SESSION["user"]["type"] = "profesor";
                $_SESSION["login_time"] = time();
                $_SESSION["user"]["data"] = $user;
                
                header("Location: profesor/p_dashboard.php");
                exit;
            }
            else {            
                // userul nu s-a autentificat cu succes, userul nu e bun sau parola            
                $alertLoginFailedVisible = true;
            }
        }
    }

?>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sandbox - Modern & Multipurpose Bootstrap 5 Template</title>
        <link rel="shortcut icon" href="./assets/img/favicon.png">
        <link rel="stylesheet" href="./assets/css/plugins.css">
        <link rel="stylesheet" href="./assets/css/style.css">
        <link rel="stylesheet" href="./assets/css/colors/sky.css">
        <link rel="preload" href="./assets/css/fonts/thicccboi.css" as="style" onload="this.rel='stylesheet'">
    </head>
    
    <body>
        <nav class="navbar navbar-expand-lg center-nav transparent navbar-light">
            <div class="container flex-lg-row flex-nowrap align-items-center">
            <div class="navbar-collapse offcanvas offcanvas-nav offcanvas-start">
            </div>
            </div>
        </nav>

        <section class="wrapper bg-gradient-primary">
            <div class="container pt-10 pt-md-14 pb-8 text-center">
                <div class="row gx-lg-8 gx-xl-12 gy-10 align-items-center">
                <div class="col-lg-7">
                    <figure><img class="w-auto" src="./assets/img/illustrations/i2.png" srcset="./assets/img/illustrations/i2@2x.png 2x" alt="" /></figure>
                </div>
                <!-- /column -->
                <div class="col-md-10 offset-md-1 offset-lg-0 col-lg-5 text-center text-lg-start">
                    <h1 class="display-1 mb-5 mx-md-n5 mx-lg-0">Lectii online direct de la profesorul tau!</h1>
                    <p class="lead fs-lg mb-7">Platforma online unde profesorii pot creea lectii personalizate direct pentru elevii lor.</p>
                    <span><a class="btn btn-primary rounded-pill me-2" data-bs-toggle="modal" data-bs-target="#modalLoginStudent">Login Student</a></span>
                    <span><a class="btn btn-primary rounded-pill me-2" data-bs-toggle="modal" data-bs-target="#modalLoginProfesor">Login Profesor</a></span>
                </div>
                <!-- /column -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container -->
        </section>

        <!-- Modal login student -->
        <div class="modal fade" id="modalLoginStudent" tabindex="-1" aria-labelledby="modalLoginStudent" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h2>Bine ai revenit!</h2>
                    <p>Conecteaza-te ca si <b>student</b> in LessonsLab.</p>
                    <form action="/index.php" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="email" aria-describedby="email">
                        </div>
                        <div class="mb-3">
                            <label for="parola" class="form-label">Parola</label>
                            <input type="password" name="password" class="form-control" id="parola">
                        </div>
                        <input type="hidden" name="type" value="login-student">
                        <button type="submit" class="btn btn-primary">Conecteaza-te</button>
                    </form>
                </div>
            </div>
            </div>
        </div>
        <!-- Modal login profesor -->
        <div class="modal fade" id="modalLoginProfesor" tabindex="-1" aria-labelledby="modalLoginProfesor" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h2>Bine ai revenit!</h2>
                    <p>Conecteaza-te ca si <b>profesor</b> in LessonsLab.</p>
                    <form action="/index.php" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="email" aria-describedby="email">
                        </div>
                        <div class="mb-3">
                            <label for="parola" class="form-label">Parola</label>
                            <input type="password" name="password" class="form-control" id="parola">
                        </div>
                        <input type="hidden" name="type" value="login-profesor">
                        <button type="submit" class="btn btn-primary">Conecteaza-te</button>
                    </form>
                </div>
            </div>
            </div>
        </div>

        <!-- Scripturi design bootstrap -->
        <script src="./assets/js/plugins.js"></script>
        <script src="./assets/js/theme.js"></script>
    </body>
</html>
