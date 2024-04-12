<?php 
  include 'session/startSession.php';


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
        <h1>Prehľad zaverečných prác</h1>
        <p>
          Curl bude trvať okolo 10-30 sekund, majte strpenie!<br>
          Po dokončení curlu sa zobrazí notifikácia v pravo dole a môžete načitať data (Load data).  
        </p>
        <button class="btn btn-primary" id="curlData">Curl data</button>
        <button class="btn btn-primary" id="loadData">Load data</button>
        
      </div>
    </div>


  </div>

    <!-- Main content -->
    <div class="container mt-4">
    

    <div class="row">
      <div class="col-md-5">
        <label for="typeFilter">Typ projektu:</label>
        <select id="typeFilter" class="form-select">
          <option value="">Zvoľte typ projektu</option>
        </select>
      </div>

      <div class="col-md-5">
        <label for="garantujuce_pracoviskoFilter">Garantujuce pracovisko:</label>
        <select id="garantujuce_pracoviskoFilter" class="form-select">
          <option value="">Zvoľte pracovisko</option>
        </select>
      </div>

      <div class="col-md-5">
        <label for="skolitelFilter">Školitel:</label>
        <select id="skolitelFilter" class="form-select">
          <option value="">Zvoľte školitela</option>
        </select>
      </div> 
      
     <div class="col-md-5">
        <label for="programFilter">Program:</label>
        <select id="programFilter" class="form-select">
          <option value="">Zvoľte program</option>
        </select>
      </div>

      <div class="col-md-2">
        <button id="resetFilters" class="btn btn-secondary">Reštartovať filter</button>
      </div>

    </div>


  <div class="container mt-5">
    <table id="endAssigmentTable" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>Typ práce</th>
                <th>Názov témy</th>
                <th>Vedúci práce</th>
                <th>Garantujuce pracovisko</th>
                <th>Program</th>
                <th>Zameranie</th>
                <th>Obsadenosť</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded here by DataTables -->
            <!-- add button to delete row -->

        </tbody>
    </table>
</div>



<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailModalLabel">Anotácia:</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- This is where the detail number will be displayed -->
        <p id="anotation"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvor</button>
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

  <!-- <script src="js/endAssigment.js"></script> -->



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
      fetch('/api/api.php/endAssigment', {
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
          loadedData = data.projects; // Store the loaded data in the global variable
          console.log("Data curled:", loadedData);

          document.querySelector('.toast-body').textContent = data.message; // Set message text
          document.querySelector('.toast').classList.add('show'); // Show toast
      })
      .catch(error => {
          console.error('Error loading data:', error);
      });
  });



   // Load data when the page loads
   document.getElementById('loadData').addEventListener('click', function() {
          // updateTableRow('Data loaded successfully');
    console.log("Data loaded:", loadedData);

    var table = $('#endAssigmentTable').DataTable({
        data: loadedData,
        columns: [
            {
                data: 'typ'
            },
            {
                data: 'nazov_temy'
            },
            {
                data: 'veduci_prace'
            },
            {
                data: 'garantujuce_pracovisko'
            },
            {
                data: 'program'
            },
            {
                data: 'zameranie'
            },
            {
                data: 'obsadenost'
            },
            {
              // Add a new column for the button
              data: null, // Use null for data to access the entire row object
              render: function(data, type, row) {
                  // Create a button with the detailNumber and pracovisko as data attributes
                  return '<button class="btn btn-info detail-btn" data-detail="' + row.detail + '" data-pracovisko="' + row.pracovisko + '">Ukaž anotáciu</button>';
              },
              orderable: false // Make this column not sortable
            }

            
        ],

        rowCallback: function(row, data) {
            $(row).on('click', function() {
                // console.log("Row clicked: ", data.id); // Log the ID being set
                $('#rowId').val(data.id);
                $('#rowForm').submit();
            });
        }
    });

         // Populate type filter
    var types = [];
    loadedData.forEach(function(item) {
        if (!types.includes(item.typ)) {
            types.push(item.typ);
            $('#typeFilter').append(new Option(item.typ, item.typ));
        }
    });

    // Populate category filter
  var garantujuce_pracovisko = [];
  loadedData.forEach(function (item) {
      if (!garantujuce_pracovisko.includes(item.garantujuce_pracovisko)) {
        garantujuce_pracovisko.push(item.garantujuce_pracovisko);
          $('#garantujuce_pracoviskoFilter').append(new Option(item.garantujuce_pracovisko, item.garantujuce_pracovisko));
      }
    });

    // Populate category filter
  var skolitel = [];
  loadedData.forEach(function (item) {
      if (!skolitel.includes(item.veduci_prace)) {
        skolitel.push(item.veduci_prace);
          $('#skolitelFilter').append(new Option(item.veduci_prace, item.veduci_prace));
      }
    });

    // Populate category filter
  var program = [];
  loadedData.forEach(function (item) {
      if (!program.includes(item.program)) {
        program.push(item.program);
          $('#programFilter').append(new Option(item.program, item.program));
      }
    });
  

    // Filter event typ
    $('#typeFilter').on('change', function () {
        table.column(0).search(this.value).draw();
    });

    // Filter event for category
    $('#garantujuce_pracoviskoFilter').on('change', function () {
        table.column(3).search(this.value).draw();
    });

    // Filter event for category
    $('#skolitelFilter').on('change', function () {
        table.column(2).search(this.value).draw();
    });

    // Filter event for category
    $('#programFilter').on('change', function () {
        table.column(4).search(this.value).draw();
    });


    // Reset filter button
    $('#resetFilters').on('click', function () {
        $('#typeFilter').val('');
        $('#garantujuce_pracoviskoFilter').val('');
        $('#skolitelFilter').val('');
        $('#programFilter').val('');
        table.search('').columns().search('').draw();
    });


    // Event listener for the newly added buttons
    $('#endAssigmentTable tbody').on('click', '.detail-btn', function() {
        let detailNum = $(this).data('detail'); // Get the detail number from the button's data attribute
        let pracovisko = $(this).data('pracovisko'); // Get the pracovisko value
        // // Populate the modal with the detail number
        

        console.log("Detail number clicked:", detailNum, pracovisko);



        fetch(`/api/api.php/endAssigment/${detailNum}/${pracovisko}`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json'
          },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Assuming your server responds with JSON
        })
        .then(data => {
            loadedData = data.annotation; // Store the loaded data in the global variable
            console.log("Annotation curled:", loadedData);

            $('#anotation').text(loadedData); // Set the detail number in the modal
            document.querySelector('.toast-body').textContent = data.message; // Set message text
            document.querySelector('.toast').classList.add('show'); // Show toast

            // Show the modal
            var modal = new bootstrap.Modal(document.getElementById('detailModal'));
            modal.show();

        })
        .catch(error => {
            console.error('Error loading data:', error);
        });


    });

  });



</script>











</body>

</html>