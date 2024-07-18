<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | Student</title>

    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<?php
include '../db-socket.php';

$courses = fetchCourses($conn);
closeConnection($conn);
?>

<style>
    body, html {
        height: 100%;
        font-family: "Trebuchet MS", Helvetica, sans-serif;
    }
    .bg {
        background-image: url('../assets/bk.jpg');

        height: 100%;

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
                <a class="nav-link" aria-current="page" href="#">Dashboard</a>
                </li>
            </ul>
            </div>
        </div>
    </nav>

    <div class="bg">
        <div class="container card-center">
            <div class="card rounded" style="width: 80rem; height: 35rem;">
                <h1 class="text-center mb-4 mt-4">Bună, Bogdan!</h1>
                <h2 class="ml-3">Cursuriile mele</h2>

                <!-- Tabel cursuri existente -->
                <ul id="myCoursesList" class="list-group mb-4 ml-3 mr-3">
                    <?php if (empty($courses)): ?>
                        <li class="list-group-item">Nu exita cursuri active in acest moment.</li>
                    <?php else: ?>
                        <?php foreach ($courses as $course): ?>
                            <li class="list-group-item">
                                <strong><?php echo htmlspecialchars($course['nume']); ?></strong>: <?php echo htmlspecialchars($course['tags']); ?>
                                <button class="btn btn-info float-right" onclick="openCourse(<?php echo $course['id_curs']; ?>)">Open Course</button>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>

                <!-- Formular inrolare intr-un curs nou -->
                <h2 class="ml-3 mt-3">Înroleaza-te într-un curs nou</h2>
                <form class="ml-3 mr-3" id="enrollForm">
                    <div class="form-group">
                        <label for="courseIdInput">ID Curs:</label>
                        <input type="text" class="form-control" id="courseIdInput" placeholder="Introdu ID-ul cursului">
                    </div>
                    <button type="submit" class="btn btn-primary">Înroleaza-te</button>
                </form>
            </div>
        </div>
    </div>
</body>

<script>
    // functie care redirectioneaza utilizatorul catre pagina cursului
    function openCourse(courseId) {
        window.location.href = `../pagina-curs.php?id=${courseId}`;
    }
</script>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</html>