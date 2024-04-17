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
        <!-- <div id="msg-block">
        </div>
        <div>
            <textarea id="msg-text" name="bio" placeholder="Tu mozete pisat spravu..."></textarea>
            <button id="send" onclick="sendMessage()">Odosli spravu!</button>
        </div> -->
    </main>    

    <main> 
        <canvas id="gameCanvas" width="800" height="600"></canvas>
    </main>    






    <script>
    const canvas = document.getElementById('gameCanvas');
    const ctx = canvas.getContext('2d');
    let x = Math.floor(Math.random() * (canvas.width - 20));
    let y = Math.floor(Math.random() * (canvas.height - 20));
    const size = 20;
    let squareColor = getRandomColor();
    let movements = [];  // Array to store all movements

    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    function drawSquare() {
        ctx.fillStyle = squareColor;
        ctx.fillRect(x, y, size, size);
    }

    function updatePosition(newX, newY) {
        x = newX;
        y = newY;
        drawSquare();
        movements.push({ x: x, y: y });  // Store position in the movements array

        // Include the player's unique identifier with the movement data
        if (ws.readyState === WebSocket.OPEN) {
            ws.send(JSON.stringify({ type: "move", x: x, y: y, uuid: username }));
        }
    }


    document.addEventListener('keydown', function(event) {
        let newX = x, newY = y;
        switch (event.key) {
            case 'w': newY -= 10; break;
            case 's': newY += 10; break;
            case 'a': newX -= 10; break;
            case 'd': newX += 10; break;
        }
        if (newX >= 0 && newX + size <= canvas.width && newY >= 0 && newY + size <= canvas.height) {
            updatePosition(newX, newY);
        }
    });

    drawSquare();
</script>



 <!-- Bootstrap -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Main JS -->
<script src="main.js"></script>

    <!-- <script src="main.js"></script> -->
</body>
</html>