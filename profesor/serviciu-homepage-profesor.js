document.addEventListener('DOMContentLoaded', function() {
    // Placeholder data for courses
    const courses = [
        { id: 1, name: 'Course 1', description: 'Description of Course 1', tags: ['Tag1', 'Tag2'] },
        { id: 2, name: 'Course 2', description: 'Description of Course 2', tags: ['Tag3', 'Tag4'] }
    ];

    const coursesTableBody = document.getElementById('continutTabelCursuri');

    // randare tabel cu cursuri
    function randareCursuri() {
        coursesTableBody.innerHTML = '';
        courses.forEach(course => {
            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${course.name}</td>
                <td>${course.description}</td>
                <td>${course.tags.join(', ')}</td>
                <td>
                    <button class="btn btn-warning btn-sm edit-course-btn" data-id="${course.id}">Edit</button>
                    <button class="btn btn-danger btn-sm delete-course-btn" data-id="${course.id}">Delete</button>
                </td>
            `;

            coursesTableBody.appendChild(row);
        });

        // Attach event listeners to edit and delete buttons
        document.querySelectorAll('.edit-course-btn').forEach(button => {
            button.addEventListener('click', function() {
                const courseId = this.getAttribute('data-id');
                // Redirect to edit course page with courseId (implement as needed)
                alert(`Edit course with ID: ${courseId}`);
            });
        });

        document.querySelectorAll('.delete-course-btn').forEach(button => {
            button.addEventListener('click', function() {
                const courseId = this.getAttribute('data-id');
                // Implement delete functionality (e.g., API call to delete course)
                alert(`Delete course with ID: ${courseId}`);
            });
        });
    }

    // Initial render of courses
    randareCursuri();

    // Event listener for the Create New Course button
    document.getElementById('createNewCourseBtn').addEventListener('click', function() {
        // Redirect to create course page (implement as needed)
        alert('Redirect to create new course page');
    });
});