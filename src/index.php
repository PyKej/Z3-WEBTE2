<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start Game</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="mb-4 text-center">Welcome to the Real-Time Game</h2>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Set Game Timer</h5>
                        <p class="card-text">Enter the duration for your game session below.</p>
                        <form action="game.php" method="post">
                            <div class="mb-3">
                                <label for="timerInput" class="form-label">Game duration in seconds (minimum 30 seconds):</label>
                                <input type="number" class="form-control" id="timerInput" name="timer" min="30" value="60" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Start Game</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS, including Popper (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
