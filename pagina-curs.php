<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db-socket.php';

// preluare id curs din URL
$courseId = isset($_GET['id']);

if ($courseId > 0) {
    // preluare detalii curs
    $sql = "SELECT nume FROM courses WHERE id_curs = $courseId";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $course = $result->fetch_assoc(); // detalii curs
    
    if (!$course) {
        echo "Cursul nu a fost gasit.";
        exit;
    }

    // preluare sectiuni curs
    $sql = "SELECT id, nume, path_fisier, read_time FROM sectiuni WHERE id_curs = $courseId ORDER BY ordine_sectiuni";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $sectiuni = [];
    while ($row = $result->fetch_assoc()) {
        $sectiuni[] = $row; // sectiuni curs una cate una
    }
    
    // preluare intrebari pentru fiecare sectiune
    foreach ($sectiuni as &$sectiune) {
        $ids = $sectiune['id'];
        $sql = "SELECT nume, variante, var_corecta FROM intrebari WHERE id_sectiune = $ids";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $intrebari = [];
        while ($row = $result->fetch_assoc()) {
            $intrebari[] = $row;
        }
        $sectiune['intrebari'] = $intrebari;
    }
    
    $stmt->close();
    closeConnection($conn);
} else {
    echo "Eroare: ID curs invalid!";
    exit;
}

?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Course Page</title>

    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
</head>

<style>
    .pdf {
        width: 100%;
        aspect-ratio: 4 / 3;
    }

    .pdf,
    html,
    body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    body, html {
        height: 100%;
        font-family: "Trebuchet MS", Helvetica, sans-serif;
    }
    .bg {
        /* The image used */
        background-image: url('assets/bk.jpg');

        /* Full height */
        height: 100%;

        /* Center and scale the image nicely */
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
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
                <a class="nav-link active" aria-current="page" href="student/pagina-homepage-student.php">Dashboard</a>
                </li>
            </ul>
            </div>
        </div>
    </nav>

    <div class="bg">
        <div class="container card-center">
            <div class="card overflow-auto" style="width: 70rem; height: 50rem;">
                <!-- nume curs -->
                <h1 class="text-center mb-4 mt-3" id="courseName">Curs: <?php echo $course['nume']; ?></h1>

                <!-- sectiuni curs -->
                <div id="sectiuneContinut" class="ml-3 mr-3">
                <?php
                
                foreach ($sectiuni as $index => $sectiune): ?>
                    <div class="card">
                        <div class="card-header" id="heading<?php echo $index; ?>">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapse<?php echo $index; ?>" aria-expanded="<?php echo ($index === 0) ? 'true' : 'false'; ?>" aria-controls="collapse<?php echo $index; ?>">
                                    <?php echo htmlspecialchars($sectiune['nume']); ?>
                                </button>
                            </h5>
                        </div>

                        <object class="pdf" data="https://isjvrancea.ro/wp-content/uploads/2020/05/Html-suport-de-curs-prof.-Marius-Maciuca.pdf" width="800" height="500"></object>

                        <div id="collapse<?php echo $index; ?>" class="collapse<?php echo ($index === 0) ? ' show' : ''; ?>" aria-labelledby="heading<?php echo $index; ?>" data-parent="#sectiuneContinut">
                            <div class="card-body">
                                <div id="sectionContent<?php echo $index; ?>">
                                    <?php foreach ($sectiune['intrebari'] as $intrebare): ?>
                                        <div class="question">
                                            <p><?php echo htmlspecialchars($intrebare['nume']); ?></p>
                                            <p>Variante: <?php echo htmlspecialchars($intrebare['variante']); ?></p>
                                            <p>Var corecta: <?php echo htmlspecialchars($intrebare['var_corecta']); ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <button class="btn btn-primary prevSectionBtn" data-section="<?php echo $index; ?>">Sectiunea anterioara</button>
                                <button class="btn btn-primary nextSectionBtn" data-section="<?php echo $index; ?>">Sectiunea urmatoare</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>


                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.worker.min.js';
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sections = <?php echo json_encode($sectiuni); ?>;
            let currentSectionIndex = 0;

            const sectiuneContinut = document.getElementById('sectiuneContinut');

            function incarcareSectiuni(sectionIndex) {
                const section = sections[sectionIndex];
                const pathLectie = section.path_fisier;
                const intrebari = section.intrebari;

                const sectionContent = document.getElementById('sectionContent' + sectionIndex);
                sectionContent.innerHTML = '';

                

                // afisare intrebari
                intrebari.forEach((question, questionIndex) => {
                    const questionElement = document.createElement('div');
                    questionElement.innerHTML = `
                        <p>${question.nume}</p>
                        <div>
                            ${question.variante.split(',').map(option => `
                                <label><input type="radio" name="question${sectionIndex}_${questionIndex}" value="${option}"> ${option}</label><br>
                            `).join('')}
                        </div>
                    `;
                    sectionContent.appendChild(questionElement);
                });
            }

            // initializare sectiuni
            incarcareSectiuni(currentSectionIndex);

            sectiuneContinut.addEventListener('click', function(event) {
                if (event.target.classList.contains('prevSectionBtn')) {
                    const sectionIndex = parseInt(event.target.getAttribute('data-section'));
                    if (sectionIndex > 0) {
                        currentSectionIndex = sectionIndex - 1;
                        incarcareSectiuni(currentSectionIndex);
                    }
                } else if (event.target.classList.contains('nextSectionBtn')) {
                    const sectionIndex = parseInt(event.target.getAttribute('data-section'));
                    const selectedOptions = document.querySelectorAll(`input[name^="question${sectionIndex}"]:checked`);
                    if (selectedOptions.length === sections[sectionIndex].intrebari.length) {
                        if (sectionIndex < sections.length - 1) {
                            currentSectionIndex = sectionIndex + 1;
                            incarcareSectiuni(currentSectionIndex);
                        }
                    } else {
                        alert('Raspundeti la toate intrebarile inainte de a trece la sectiunea urmatoare!');
                    }
                }
            });
        });
    </script>
</body>
</html>
