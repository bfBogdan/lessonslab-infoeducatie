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
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Librarie design bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
    
    <body>
        <!-- Butoane Modal login student/profesor -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalLoginStudent">LogIn Student</button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalLoginProfesor">LogIn Profesor</button>

        <!-- Modal login student -->
        <div class="modal modal-lg fade" id="modalLoginStudent" tabindex="-1" aria-labelledby="modalLoginStudent" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <img src="/assets/img/illustrations/login.png" alt="imagineLogin">
                        </div>
                        <div class="col">
                            <h2>Bine ai revenit!</h2>
                            <p>Conecteaza-te ca si student in LessonsLab.</p>
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
            </div>
        </div>
        <!-- Modal login profesor -->
        <div class="modal modal-lg fade" id="modalLoginProfesor" tabindex="-1" aria-labelledby="modalLoginProfesor" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <img src="/assets/img/illustrations/login.png" alt="imagineLogin">
                        </div>
                        <div class="col">
                            <h2>Bine ai revenit!</h2>
                            <p>Conecteaza-te ca si profesor in LessonsLab.</p>
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
            </div>
        </div>

        <!-- Scripturi design bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    </body>
</html>