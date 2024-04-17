<!DOCTYPE html>
<html lang="en">


<?php include 'parts/head.php'; ?>



<body>
    <header>
        <h1>Chat a.k.a. "Hello world" websocketov</h1>
    </header>

    <div class="container mt-4">
        <h2>Set Game Timer</h2>
        <form action="game.php" method="post">
            <div class="mb-3">
                <label for="timerInput" class="form-label">Enter game duration in seconds (minimum 30 seconds):</label>
                <input type="number" class="form-control" id="timerInput" name="timer" min="30" value="60" required>
            </div>
            <button type="submit" class="btn btn-primary">Set Timer</button>
        </form>
    </div>


      <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Main JS -->
    <script src="main.js"></script>


</body>
</html>