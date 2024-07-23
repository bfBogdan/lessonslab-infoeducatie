<?php
    session_start();
    if(!isset($_SESSION['user']) && $_SESSION['user']['type'] != 'student') {
        header('location:../index.php');
    }
?>

<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Librarie design bootstrap -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        
        <title>Dashboard Student</title>
    </head>

    <body>
        <!-- Bara de navigare -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">LessonsLab</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">> Dashboard < </a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="#"> Descopera </a>
                    </li>
                    
                </ul>
                <form class="d-flex">
                    <button class="btn btn-outline-success" type="submit">Profilul meu</button>
                </form>
                </div>
            </div>
        </nav>

        <!-- Cursuri active -->
         <h1>Cursuri active</h1>
         <?php
         // preluare cursuri active din baza de date
            $cursuri = [
                ['id' => 1, 'nume' => 'Matematica', 'deadline' => '07.12.2024', 'progres' => 50],
                ['id' => 2, 'nume' => 'Fizica', 'deadline' => '12.12.2024', 'progres' => 30],
                ['id' => 3, 'nume' => 'Chimie', 'deadline' => '15.08.2025', 'progres' => 70],
                ['id' => 4, 'nume' => 'Biologie', 'deadline' => '20.12.2024', 'progres' => 90],
            ];

            // daca nu exista cursuri active...
            if(count($cursuri) == 0) {
                echo 'Nu sunt cursuri active';
            } 

            // daca exista cursuri active...
            else {
                echo '<div class="container">';
                echo '<div class="row">';
                foreach($cursuri as $curs) {
                    echo '<div class="col">';
                    echo '<div class="card d-flex align-items-stretch mt-5" >';
                    echo '<div class="card-body">';
                    echo '<i class="bi bi-calculator-fill" style="font-size: 2rem;"></i>';
                    echo '<h5 class="card-title mt-3">'.$curs['nume'].'</h5>';
                    echo '<p class="card-text">Pana la '.$curs['deadline'].'</p>';
                    echo '<div class="progress"> <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="'.$curs['progres'].'"></div></div>'
                    echo '<a href="#" class="btn btn-primary">Detalii</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                echo '</div>';
                echo '</div>';
            }
         ?>
         <!-- Scripturi design bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    </body>
</html>