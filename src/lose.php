<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Game Over</title>
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-danger" role="alert">
            <h1 class="alert-heading">Game Over! 😢</h1>
            <p>Sorry, you lost the game. You were defeated by <?php echo htmlspecialchars($_GET['winner']); ?>. Better luck next time!</p>
            <hr>
            <p class="mb-0"><a href="index.php" class="btn btn-primary">Try Again</a></p>
        </div>
    </div>
</body>
</html>
