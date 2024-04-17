<?php include 'parts/head.php'; ?>
<body>
    <header>
        <h1>Chat a.k.a. "Hello world" websocketov</h1>
    </header>


    <?php
    // Check if there is a POST request and if the 'timer' POST variable is set
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['timer'])) {
        $timer = intval($_POST['timer']);  // Convert the timer input to an integer
        // You can now use $timer in your game logic or display
        echo '<p>Timer set to ' . $timer . ' seconds.</p>';
    } else {
        // If the timer is not set, redirect or handle the error appropriately
        echo '<p>Error: Timer not set.</p>';
        // Optionally, redirect back to the index.php or show an error message
        // header('Location: index.php');
        // exit;
    }
    ?>


    <main> 
        <p>Tvoj alias je <span class="pico-color-cyan-500" id="alias"></span></p>
        <div id="msg-block">
        </div>
        <div>
            <textarea id="msg-text" name="bio" placeholder="Tu mozete pisat spravu..."></textarea>
            <button id="send" onclick="sendMessage()">Odosli spravu!</button>
        </div>
    </main>    

    <main> 
        <canvas id="gameCanvas" width="800" height="600"></canvas>
    </main>    

    <script>
    const canvas = document.getElementById('gameCanvas');
    const ctx = canvas.getContext('2d');

    // Set initial position to a random location within the canvas bounds
    let x = Math.floor(Math.random() * (canvas.width - 20));  // Ensure the square spawns fully within the canvas
    let y = Math.floor(Math.random() * (canvas.height - 20));

    const size = 20;  // Size of the square

    // Generate a random color
    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    // Set initial color
    let squareColor = getRandomColor();

    function drawSquare() {
        ctx.fillStyle = squareColor;
        ctx.fillRect(x, y, size, size);  // Draw the square
    }

    document.addEventListener('keydown', function(event) {
        switch (event.key) {
            case 'w':  // Move up
                if (y - 10 >= 0) y -= 10;  // Check boundary before moving
                break;
            case 's':  // Move down
                if (y + 10 + size <= canvas.height) y += 10;  // Check boundary before moving
                break;
            case 'a':  // Move left
                if (x - 10 >= 0) x -= 10;  // Check boundary before moving
                break;
            case 'd':  // Move right
                if (x + 10 + size <= canvas.width) x += 10;  // Check boundary before moving
                break;
        }
        drawSquare();  // Redraw the square at the new position
    });

    drawSquare();  // Initial draw
</script>



 <!-- Bootstrap -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Main JS -->
<script src="main.js"></script>

    <!-- <script src="main.js"></script> -->
</body>
</html>