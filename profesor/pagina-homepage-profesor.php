<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../db-socket.php';

$course = fetchCourses($conn);

?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Teacher Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
    body, html {
        height: 100%;
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
            <div class="card" style="width: 60rem; height: 30rem;">
                <h1 class="text-center mb-4 mt-4">Bună, Bogdan!</h1>
                <p class="text-center mb-4">-Cursurile mele-</p>

                <a class="btn btn-primary mb-4 ml-3 mr-3" role="button" href="pagina-creare-curs-nou.php" id="createNewCourseBtn">Creaza un curs nou</a>

                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nume curs</th>
                            <th>Descriere</th>
                            <th>Tag-uri</th>
                            <th>ID Curs</th>
                            <th>Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody id="continutTabelCursuri">
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    

</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var dateCursuri = <?php echo json_encode($course); ?>;

        console.log(dateCursuri);

        const coursesTableBody = document.getElementById('continutTabelCursuri');

        // randare tabel cu cursuri
        function randareCursuri() {
            coursesTableBody.innerHTML = '';
            dateCursuri.forEach(course => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td>${course.nume}</td>
                    <td>${course.descriere}</td>
                    <td>${course.tags.join}</td>
                    <td>${course.id_curs}</td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-course-btn" data-id="${course.id}">Edit</button>
                        <button class="btn btn-danger btn-sm delete-course-btn" data-id="${course.id}">Delete</button>
                    </td>
                `;

                coursesTableBody.appendChild(row);
            });

            document.querySelectorAll('.edit-course-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const courseId = this.getAttribute('data-id');
                    alert(`Edit course with ID: ${courseId}`);
                });
            });

            document.querySelectorAll('.delete-course-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const courseId = this.getAttribute('data-id');
                    alert(`Delete course with ID: ${courseId}`);
                });
            });
        }

        randareCursuri();

    });
</script>

</html>
