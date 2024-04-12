<?php 
  include 'session/startSession.php';

  require_once('db/connect.php');
  $conn = getDBConnection();
  require_once('curl/f_curl.php');

?>

<!DOCTYPE html>
<html lang="en">

<?php include 'parts/head.php'; ?>

<body>
  <!-- Navbar -->
  <?php include 'parts/navbar.php'; ?>

  <!-- Main content -->

  <div class="container">
    <div class="row">
      <div class="col-12">
      <h1>Rozvrh</h1>
        <button class="btn btn-primary" id="curlData">Curl data</button>
        <button class="btn btn-primary" id="sendData">Send data</button>
        <button class="btn btn-primary" id="loadData">Load data</button>
        <button class="btn btn-primary" id="addData">Add data</button>
        
      </div>
    </div>


  </div>

  <div class="container mt-5">
    <table id="timetableTable" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Day</th>
                <th>Type</th>
                <th>Name</th>
                <th>Classroom</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded here by DataTables -->
            <!-- add button to delete row -->

        </tbody>
    </table>
</div>


<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Entry</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Add form fields here. Example: -->
        <form id="editForm">
          <input type="hidden" id="editId">
          <div class="mb-3">
            <label for="editDay" class="form-label">Day</label>
            <input type="text" class="form-control" id="editDay">
          </div>


          <div class="mb-3">
            <label for="editType" class="form-label">Type</label>
            <input type="text" class="form-control" id="editType">
          </div>

          <div class="mb-3">
            <label for="editName" class="form-label">Name</label>
            <input type="text" class="form-control" id="editName">
          </div>

          <div class="mb-3">
            <label for="editClassroom" class="form-label">Classroom</label>
            <input type="text" class="form-control" id="editClassroom">
          </div>
        </form>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveEdit">Save changes</button>
      </div>

    </div>
  </div>
</div>


<div class="modal fade" id="addDataModal" tabindex="-1" aria-labelledby="addDataModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addDataModalLabel">Add New Entry</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addForm">
          <!-- Form Fields -->
          <div class="mb-3">
            <label for="addDay" class="form-label">Day</label>
            <input type="text" class="form-control" id="addDay">
          </div>
          <div class="mb-3">
            <label for="addType" class="form-label">Type</label>
            <input type="text" class="form-control" id="addType">
          </div>
          <div class="mb-3">
            <label for="addName" class="form-label">Name</label>
            <input type="text" class="form-control" id="addName">
          </div>
          <div class="mb-3">
            <label for="addClassroom" class="form-label">Classroom</label>
            <input type="text" class="form-control" id="addClassroom">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="submitAdd">Add</button>
      </div>
    </div>
  </div>
</div>

  <!-- jQuery library - Must be loaded first -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- DataTables JS - Make sure this is the correct DataTables script source -->
  <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

  <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" style="position: fixed; bottom: 20px; right: 20px;">
    <div class="toast-header">
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      <?php
        echo $_SESSION['message'] ?? '';
      ?>
    </div>
  </div>



<script>
        let loadedData = []; // Global variable to store loaded data

        document.getElementById('curlData').addEventListener('click', function() {
            fetch('/api/api.php/curlTimetable', {
                method: 'GET',
                headers: {'Accept': 'application/json'}
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json(); // Assuming your server responds with JSON
            })
            .then(data => {
                loadedData = data.subjects; // Store the loaded data in the global variable
                console.log("Data curled:", loadedData);

                document.querySelector('.toast-body').textContent = data.message; // Set message text
                document.querySelector('.toast').classList.add('show'); // Show toast
            })
            .catch(error => {
                console.error('Error loading data:', error);
            });
        });

        document.getElementById('sendData').addEventListener('click', function() {
            
            if (loadedData.length > 0) {
                fetch('/api/api.php/timetable', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(loadedData) // Send the loaded data
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log("Data sent:", data);
                    document.querySelector('.toast-body').textContent = data.message; // Set message text
                    document.querySelector('.toast').classList.add('show'); // Show toast
                })
                .catch(error => {
                    console.error('Error sending data:', error);
                });
            } else {
                console.error('No data loaded to send.');

                document.querySelector('.toast-body').textContent = 'No data loaded to send.'; // Set message text
                document.querySelector('.toast').classList.add('show'); // Show toast
            }
        });


        function updateTableRow(retMessage){
          fetch('/api/api.php/timetable', {
                method: 'GET',
                headers: {'Accept': 'application/json'}
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log("Data loaded:", data);

                // Clear the table body
                document.querySelector('#timetableTable tbody').innerHTML = '';

                // Loop through the loaded data and add it to the table
                let tbody = document.querySelector('#timetableTable tbody');
                tbody.innerHTML = ''; // Clear existing rows
                data.forEach(row => {
                    let tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${row.id}</td>
                        <td>${row.day}</td>
                        <td>${row.type}</td>
                        <td>${row.name}</td>
                        <td>${row.classroom}</td>
                        <td><button class="btn btn-primary edit-row-btn" data-id="${row.id}">Edit</button></td>
                        <td><button class="btn btn-danger delete-row-btn" data-id="${row.id}">Delete</button></td>
                        
                    `;
                    tbody.appendChild(tr);
                });

                attachDeleteButtonEvents(); // Attach event listeners to the delete buttons
                attachEditButtonEvents(); // Attach event listeners to the edit buttons

                document.querySelector('.toast-body').textContent = retMessage;
                document.querySelector('.toast').classList.add('show'); // Show toast
            })
            .catch(error => {
                console.error('Error loading data:', error);
            });
        }
      

        // Load data when the page loads
        document.getElementById('loadData').addEventListener('click', function() {
            updateTableRow('Data loaded successfully');
        });

      function attachDeleteButtonEvents() {
        document.querySelectorAll('.delete-row-btn').forEach(button => {
            button.addEventListener('click', function() {
                let row = button.closest('tr');
                let id = button.getAttribute('data-id');
                
                // Make an API call to delete the row from the server
                fetch(`/api/api.php/timetable/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data.message); // Assuming your API responds with a message

                    document.querySelector('.toast-body').textContent = data.message; // Set message text
                    document.querySelector('.toast').classList.add('show'); // Show toast
                    row.remove(); // Remove the row from the table
                })
                .catch(error => {
                    console.error('Error deleting data:', error);
                });
            });
      });
    }

    function attachEditButtonEvents() {
  document.querySelectorAll('.edit-row-btn').forEach(button => {
    button.addEventListener('click', function() {
      const row = button.closest('tr');
      const id = button.getAttribute('data-id');
      
      // Populate the modal with data from the row
      document.getElementById('editId').value = id;
      document.getElementById('editDay').value = row.cells[1].textContent;
      document.getElementById('editType').value = row.cells[2].textContent;
      document.getElementById('editName').value = row.cells[3].textContent;
      document.getElementById('editClassroom').value = row.cells[4].textContent;

      // Populate other fields similarly
      
      // Show the modal
      var editModal = new bootstrap.Modal(document.getElementById('editModal'));
      editModal.show();
    });
  });
}

// Handle the save changes button click
document.getElementById('saveEdit').addEventListener('click', function() {


  const id = document.getElementById('editId').value;
  const day = document.getElementById('editDay').value;
  const type = document.getElementById('editType').value;
  const name = document.getElementById('editName').value;
  const classroom = document.getElementById('editClassroom').value;
  
  // Prepare the data to be sent to the server
  const editedData = { id, day, type, name, classroom};
  
  // Send the edited data to the server
  fetch(`/api/api.php/timetable/${id}`, {
    method: 'PUT', // or 'PATCH'
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(editedData)
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.json();
  })
  .then(data => {
    updateTableRow(data.message); // Reload the table data

    // Hide the modal
    var editModal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
    editModal.hide();


    // Optionally, refresh the table or update the row in the table directly
    document.querySelector('.toast-body').textContent = data.message; // Set message text
    document.querySelector('.toast').classList.add('show'); // Show toast

    console.log(data.message);
    
  })
  .catch(error => {
    console.error('Error updating data:', error);
  });
});

document.getElementById('addData').addEventListener('click', function() {
  var addModal = new bootstrap.Modal(document.getElementById('addDataModal'));
  addModal.show();
});

document.getElementById('submitAdd').addEventListener('click', function() {
  const day = document.getElementById('addDay').value;
  const type = document.getElementById('addType').value;
  const name = document.getElementById('addName').value;
  const classroom = document.getElementById('addClassroom').value;

  const newData = {
    day: day,
    type: type,
    name: name,
    classroom: classroom
  };
  const jsonData = JSON.stringify([newData]);
  console.log(jsonData);

  fetch('/api/api.php/timetable', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: jsonData
  })
  .then(response => response.json())
  .then(data => {
    // Update the table here, similar to updateTableRow function
    updateTableRow(data.message); // Reload the table data // Assuming updateTableRow() can also handle refreshing the table
    var addModal = bootstrap.Modal.getInstance(document.getElementById('addDataModal'));
    addModal.hide(); // Hide the modal
    document.querySelector('.toast-body').textContent = data.message;
    document.querySelector('.toast').classList.add('show');
  })
  .catch(error => console.error('Error adding data:', error));
});


</script>











</body>

</html>