<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../db-socket.php';

// Inseram datele cursului
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $numeCurs = $_POST['numeCurs'];
    $descriereCurs = $_POST['descriereCurs'];
    $taguriCurs = $_POST['taguriCurs'];
    $idCurs = rand(1000, 9999);
    echo $numeCurs;
    echo $descriereCurs;
    $sqlCurs = "INSERT INTO courses (nume, descriere, tags, id_profesor, id_curs, id_sectiuni) VALUES (?, ?, ?,1, ?, '0')";
    $stmtCurs = $conn->prepare($sqlCurs);
    $stmtCurs->bind_param("ssss", $numeCurs, $descriereCurs, $taguriCurs, $idCurs);


    if ($stmtCurs->execute()) {

        foreach ($_POST as $key => $value) {
            if (strpos($key, 'numeSectiune') === 0) {
                $numarSectiune = str_replace('numeSectiune', '', $key);
                $numeSectiune = $value;
                $timpCitire = $_POST["timpCitire$numarSectiune"];
                $incarcareLectie = $_FILES["incarcareLectie$numarSectiune"]['name'];

                // Salvam fisierul PDF
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES["incarcareLectie$numarSectiune"]["name"]);
                move_uploaded_file($_FILES["incarcareLectie$numarSectiune"]["tmp_name"], $target_file);

                // Inseram sectiunea
                $idSectiune = rand(10000, 99999);
                $sqlSectiune = "INSERT INTO sectiuni (id_curs, nume, read_time, path_fisier) VALUES (?, ?, ?, ?)";
                $stmtSectiune = $conn->prepare($sqlSectiune);
                $stmtSectiune->bind_param("ssis", $idCurs, $numeSectiune, $timpCitire, $target_file);

                if ($stmtSectiune->execute()) {
                    if (isset($_POST["intrebare$numarSectiune"])) {
                        foreach ($_POST["intrebare$numarSectiune"] as $indexIntrebare => $textIntrebare) {
                            $sqlIntrebare = "INSERT INTO intrebari (id_sectiune, nume) VALUES (?, ?)";
                            $stmtIntrebare = $conn->prepare($sqlIntrebare);
                            $stmtIntrebare->bind_param("is", $idSectiune, $textIntrebare);

                            
                        }
                    }
                }
            }
        }

        echo "Cursul a fost creat cu succes!";
    } else {
        echo "Eroare: " . $stmtCurs->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creare curs nou</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
    body, html {
        height: 100%;
    }
    .card-center {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }
</style>

<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary">  
        <div class="container-fluid">
            <a class="navbar-brand" href="https://lessonslab.bit4soft.com">LessonsLab</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                <a class="nav-link" aria-current="page" href="/profesor/pagina-homepage-profesor.php">Dashboard</a>
                </li>
            </ul>
            </div>
        </div>
    </nav>


    <div class="container card-center">
        <div class="card" style="width: 60rem; height: 30rem;">
            <h1 class="text-center mb-4 overflow-scroll">Creare curs</h1>
            <form id="formularCurs" action="pagina-creare-curs-nou.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="numeCurs">Nume curs</label>
                    <input type="text" class="form-control" id="numeCurs" name="numeCurs" placeholder="Introduceți numele cursului">
                </div>
                <div class="form-group">
                    <label for="descriereCurs">Descriere curs</label>
                    <textarea class="form-control" id="descriereCurs" name="descriereCurs" rows="3" placeholder="Introduceți descrierea cursului"></textarea>
                </div>
                <div class="form-group">
                    <label for="taguriCurs">Tag-uri curs</label>
                    <input type="text" class="form-control" id="taguriCurs" name="taguriCurs" placeholder="Introduceți tag-urile cursului, separate prin virgule">
                </div>
                <div id="containerSectiuni" class="mt-4"></div>
                <button type="button" class="btn btn-secondary mb-4" id="adaugaSectiuneBtn">Adaugă secțiune</button>
                <button type="submit" class="btn btn-success mt-4" id="creareCursBtn">Creare curs</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('adaugaSectiuneBtn').addEventListener('click', function() {
            let numarSectiuni = document.querySelectorAll('.sectiune').length + 1;
            let containerSectiune = document.createElement('div');
            containerSectiune.classList.add('sectiune', 'border', 'p-3', 'mb-4');

            let headerSectiune = document.createElement('h4');
            headerSectiune.textContent = `Secțiunea ${numarSectiuni}`;

            let grupNumeSectiune = document.createElement('div');
            grupNumeSectiune.classList.add('form-group');

            let etichetaNumeSectiune = document.createElement('label');
            etichetaNumeSectiune.textContent = 'Nume secțiune';

            let inputNumeSectiune = document.createElement('input');
            inputNumeSectiune.type = 'text';
            inputNumeSectiune.classList.add('form-control', 'nume-sectiune');
            inputNumeSectiune.placeholder = 'Introduceți numele secțiunii';
            inputNumeSectiune.name = `numeSectiune${numarSectiuni}`;

            grupNumeSectiune.appendChild(etichetaNumeSectiune);
            grupNumeSectiune.appendChild(inputNumeSectiune);

            let grupTimpCitire = document.createElement('div');
            grupTimpCitire.classList.add('form-group');

            let etichetaTimpCitire = document.createElement('label');
            etichetaTimpCitire.textContent = 'Timp de citire (minute)';

            let inputTimpCitire = document.createElement('input');
            inputTimpCitire.type = 'number';
            inputTimpCitire.classList.add('form-control', 'timp-citire');
            inputTimpCitire.placeholder = 'Introduceți timpul de citire în minute';
            inputTimpCitire.name = `timpCitire${numarSectiuni}`;

            grupTimpCitire.appendChild(etichetaTimpCitire);
            grupTimpCitire.appendChild(inputTimpCitire);

            let grupIncarcareLectie = document.createElement('div');
            grupIncarcareLectie.classList.add('form-group');

            let etichetaIncarcareLectie = document.createElement('label');
            etichetaIncarcareLectie.textContent = 'Încărcați lecția (PDF)';

            let inputIncarcareLectie = document.createElement('input');
            inputIncarcareLectie.type = 'file';
            inputIncarcareLectie.classList.add('form-control-file', 'incarcare-lectie');
            inputIncarcareLectie.accept = '.pdf';
            inputIncarcareLectie.name = `incarcareLectie${numarSectiuni}`;

            grupIncarcareLectie.appendChild(etichetaIncarcareLectie);
            grupIncarcareLectie.appendChild(inputIncarcareLectie);

            let grupIntrebari = document.createElement('div');
            grupIntrebari.classList.add('form-group');

            let etichetaIntrebari = document.createElement('label');
            etichetaIntrebari.textContent = 'Adăugați întrebări';

            let textareaIntrebari = document.createElement('textarea');
            textareaIntrebari.classList.add('form-control', 'textarea-intrebari');
            textareaIntrebari.rows = 2;
            textareaIntrebari.placeholder = 'Introduceți întrebarea';
            textareaIntrebari.name = `intrebare${numarSectiuni}[]`;

            let divOptiuniRaspuns = document.createElement('div');
            divOptiuniRaspuns.classList.add('mt-2', 'div-optiuni-raspuns');

            let etichetaOptiuniRaspuns = document.createElement('label');
            etichetaOptiuniRaspuns.textContent = 'Opțiuni de răspuns:';

            let grupOptiuneRaspuns = document.createElement('div');
            grupOptiuneRaspuns.classList.add('input-group', 'mb-2');

            let inputOptiuneRaspuns = document.createElement('input');
            inputOptiuneRaspuns.type = 'text';
            inputOptiuneRaspuns.classList.add('form-control', 'optiune-raspuns');
            inputOptiuneRaspuns.placeholder = 'Introduceți o opțiune';
            inputOptiuneRaspuns.name = `optiuneRaspuns${numarSectiuni}[]`;

            let divInputGroupAppend = document.createElement('div');
            divInputGroupAppend.classList.add('input-group-append');

            let spanInputGroupText = document.createElement('span');
            spanInputGroupText.classList.add('input-group-text');

            let checkboxRaspunsCorect = document.createElement('input');
            checkboxRaspunsCorect.type = 'checkbox';
            checkboxRaspunsCorect.classList.add('checkbox-raspuns-corect');
            checkboxRaspunsCorect.name = `checkboxRaspunsCorect${numarSectiuni}[]`;

            spanInputGroupText.appendChild(checkboxRaspunsCorect);
            divInputGroupAppend.appendChild(spanInputGroupText);
            grupOptiuneRaspuns.appendChild(inputOptiuneRaspuns);
            grupOptiuneRaspuns.appendChild(divInputGroupAppend);

            divOptiuniRaspuns.appendChild(etichetaOptiuniRaspuns);
            divOptiuniRaspuns.appendChild(grupOptiuneRaspuns);

            let butonAdaugaOptiune = document.createElement('button');
            butonAdaugaOptiune.type = 'button';
            butonAdaugaOptiune.classList.add('btn', 'btn-secondary', 'mb-3');
            butonAdaugaOptiune.textContent = 'Adaugă o altă opțiune';
            butonAdaugaOptiune.addEventListener('click', function() {
                let grupNouOptiuneRaspuns = document.createElement('div');
                grupNouOptiuneRaspuns.classList.add('input-group', 'mb-2');

                let inputNouOptiuneRaspuns = document.createElement('input');
                inputNouOptiuneRaspuns.type = 'text';
                
                inputNouOptiuneRaspuns.classList.add('form-control', 'optiune-raspuns');
                inputNouOptiuneRaspuns.placeholder = 'Introduceți o opțiune';
                inputNouOptiuneRaspuns.name = `optiuneRaspuns${numarSectiuni}[]`;

                let divNouInputGroupAppend = document.createElement('div');
                divNouInputGroupAppend.classList.add('input-group-append');

                let spanNouInputGroupText = document.createElement('span');
                spanNouInputGroupText.classList.add('input-group-text');

                let checkboxNouRaspunsCorect = document.createElement('input');
                checkboxNouRaspunsCorect.type = 'checkbox';
                checkboxNouRaspunsCorect.classList.add('checkbox-raspuns-corect');
                checkboxNouRaspunsCorect.name = `checkboxRaspunsCorect${numarSectiuni}[]`;

                spanNouInputGroupText.appendChild(checkboxNouRaspunsCorect);
                divNouInputGroupAppend.appendChild(spanNouInputGroupText);
                grupNouOptiuneRaspuns.appendChild(inputNouOptiuneRaspuns);
                grupNouOptiuneRaspuns.appendChild(divNouInputGroupAppend);

                divOptiuniRaspuns.appendChild(grupNouOptiuneRaspuns);
            });

            grupIntrebari.appendChild(etichetaIntrebari);
            grupIntrebari.appendChild(textareaIntrebari);
            grupIntrebari.appendChild(divOptiuniRaspuns);
            grupIntrebari.appendChild(butonAdaugaOptiune);

            containerSectiune.appendChild(headerSectiune);
            containerSectiune.appendChild(grupNumeSectiune);
            containerSectiune.appendChild(grupTimpCitire);
            containerSectiune.appendChild(grupIncarcareLectie);
            containerSectiune.appendChild(grupIntrebari);

            document.getElementById('containerSectiuni').appendChild(containerSectiune);
        });
    </script>
</body>
</html>
