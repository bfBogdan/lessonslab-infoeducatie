<?php
    session_start();
    if(!isset($_SESSION['user']) && $_SESSION['user']['type'] != 'profesor') {
        header('location:../index.php');
    }
    else{
        include_once("../db-socket.php");

        if(isset($_POST['nume_curs'])) {
            $nume_curs = $_POST['nume_curs'];
            $descriere_curs = $_POST['descriere_curs'];
            $tags = $_POST['tags'];

            $contor = 0;
            $id_lectii = array();
            foreach($_POST['nume_lectie'] as $lectie){
                $nume_lectie = $lectie;
                $fisier_lectie = $_POST['fisier_lectie'][$contor];
                $read_time = $_POST['read_time'][$contor];
                $id_lectie = rand(1000, 9999);
                array_push($id_lectii, $id_lectie);
                $contor++;

                $query = "INSERT INTO sectiuni (id, path_fisier, titlu, read_time) VALUES ('$id_lectie', '$fisier_lectie', '$nume_lectie', '$read_time')";
                $result = QueryDB($query);

                if($result) {
                }
                else {
                    echo 'Eroare la adaugarea lectiei';
                }
            }

            $id_profesor = $_SESSION['user']['data']['id'];
            $id_sectiuni = implode(',', $id_lectii);
            $query = "INSERT INTO cursuri (id_profesor, nume, descriere, id_sectiuni, tags, icon) VALUES ('$id_profesor', '$nume_curs', '$descriere_curs', '$id_sectiuni', '$tags', 'bi bi-laptop')";
            $result = QueryDB($query);

            if($result) {
                echo 'Cursul a fost adaugat cu succes';
            }
            else {
                echo 'Eroare la adaugarea cursului';
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
                <a href="../index.php">
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
            <h2>Creeaza un curs nou</h2>
            <form id="form" action="curs_nou.php" method="POST">
                <div class="mb-3">
                    <label for="nume_curs" class="form-label">Nume curs</label>
                    <input type="text" name="nume_curs" class="form-control" id="nume_curs" aria-describedby="nume_curs">
                </div>
                <div class="mb-3">
                    <label for="descriere_curs" class="form-label">Descriere curs</label>
                    <textarea name="descriere_curs" class="form-control" id="descriere_curs" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="materie" class="form-label">Tag-uri</label>
                    <input type="text" name="materie" class="form-control" id="materie">
                </div>
                <button type="button" class="btn btn-expand btn-primary rounded-pill" onclick="adaugaLectie()" >
                    <i class="uil uil-arrow-right"></i>
                    <span>Adauga lectie</span>
                </button>
                <button type="button" onclick="adaugaChestionar()" class="btn btn-expand btn-soft-primary rounded-pill">
                    <i class="uil uil-arrow-right"></i>
                    <span>Adauga chestionar intrebari</span>
                </a>
                <button type="submit" class="btn btn-expand btn-primary">
                    <i class="uil uil-arrow-right"></i>
                    <span>Salveaza curs</span>
                </button>
            </form>

        </div>

        <script>
            let counterLectie = 0;
            let counterChestionar = 0;
            function adaugaLectie() {
                const div = document.createElement('div');
                div.className = 'mb-3';

                // label + input nume lectie
                const label = document.createElement('label');
                label.className = 'form-label';
                label.for = 'nume_lectie[]';
                label.innerText = 'Nume lectie';
                const input = document.createElement('input');
                input.type = 'text';
                input.name = 'nume_lectie[]';
                input.className = 'form-control';
                input.id = 'nume_lectie[]';

                // label + input upload fisier
                const label2 = document.createElement('label');
                label2.className = 'form-label';
                label2.for = 'fisier_lectie[]';
                label2.innerText = 'Fisier lectie';
                const input2 = document.createElement('input');
                input2.type = 'file';
                input2.name = 'fisier_lectie[]';
                input2.className = 'form-control';
                input2.id = 'fisier_lectie[]';

                // label + input nume lectie
                const label3 = document.createElement('label');
                label3.className = 'form-label';
                label3.for = 'read_time[]';
                label3.innerText = 'Timp parcurgere lectie (min)';
                const input3 = document.createElement('input');
                input3.type = 'text';
                input3.name = 'read_time[]';
                input3.className = 'form-control';
                input3.id = 'read_time[]';


                div.appendChild(label);
                div.appendChild(input);
                div.appendChild(label2);
                div.appendChild(input2);
                div.appendChild(label3);
                div.appendChild(input3);

                document.getElementById('form').appendChild(div);

                counterLectie++;
                
            }

            function adaugaChestionar() {
                const div = document.createElement('div');
                div.className = 'mb-3';

                // label + input titlu chestionar
                const label = document.createElement('label');
                label.className = 'form-label';
                label.for = 'titlu_chestionar[]';
                label.innerText = 'Titlu chestionar';
                const input = document.createElement('input');
                input.type = 'text';
                input.name = 'titlu_chestionar[]';
                input.className = 'form-control';
                input.id = 'titlu_chestionar[]';

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-primary';
                btn.innerText = '+ Adauga intrebare';
                btn.onclick = adaugaIntrebare();


                div.appendChild(label);
                div.appendChild(input);
                div.appendChild(btn);

                document.getElementById('form').appendChild(div);

                counterLectie++;
            }

            function adaugaIntrebare() {
                const div = document.createElement('div');
                div.className = 'mb-3';

                // label + input intrebare
                const label4 = document.createElement('label');
                label4.className = 'form-label';
                label4.for = 'intrebare[]';
                label4.innerText = 'Intrebare';
                const input4 = document.createElement('input');
                input4.type = 'text';
                input4.name = 'intrebare[]';
                input4.className = 'form-control';
                input4.id = 'intrebare[]';

                // buton adauga raspuns
                const btn2 = document.createElement('button');
                btn2.type = 'button';
                btn2.className = 'btn btn-primary';
                btn2.innerText = '+ Adauga raspuns';
                btn2.onclick = adaugaRaspuns();

                div.appendChild(label4);
                div.appendChild(input4);
                div.appendChild(btn2);
            }

            function adaugaRaspuns() {
                const div = document.createElement('div');
                div.className = 'mb-3';

                // label + input raspuns
                const label = document.createElement('label');
                label.className = 'form-label';
                label.for = 'raspuns[]';
                label.innerText = 'Raspuns';
                const input = document.createElement('input');
                input.type = 'text';
                input.name = 'raspuns[]';
                input.className = 'form-control';
                input.id = 'raspuns[]';

                // checkbox corect
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.name = 'corect';
                checkbox.id = 'corect';

                div.appendChild(label);
                div.appendChild(input);
                div.appendChild(checkbox);
            }
        </script>

    </body>
</html>
