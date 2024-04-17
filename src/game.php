<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'parts/head.php'; ?>
    <title>Real-Time Multiplayer Game</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* Light grey background */
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        canvas {
            border: 2px solid #333; /* Slight border around the canvas */
        }
        #gameInfo {
            position: fixed;
            top: 20px;
            left: 20px;
            background: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div id="gameInfo" class="container">
        <h1 class="h3">Real-Time Multiplayer Game</h1>
        <p class="lead">Your alias is: <span class="fw-bold text-primary" id="alias">@placeholder</span></p>
    </div>

    <canvas id="gameCanvas" width="800" height="600"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="main.js"></script>
</body>
</html>
