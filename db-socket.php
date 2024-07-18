<?php
// Database connection parameters
$servername = "localhost";
$username = "u207455012_qgT";
$password = "t:M!9b6|h31H";
$dbname = "u207455012_lessonlab";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch courses
function fetchCourses($conn) {
    $sql = "SELECT id_curs, nume, tags FROM courses";
    $result = $conn->query($sql);

    $courses = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }
    } else {
        echo "No courses found.";
    }
    return $courses;
}

function closeConnection($conn) {
    $conn->close();
}

?>