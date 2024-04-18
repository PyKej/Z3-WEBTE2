const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');

// const gameTimerElement = document.getElementById('gameTimer');
let gameDuration = 60; // Default duration in seconds





let username;
let otherPlayers = {};

let x, y;
const size = 10;
const squareColor = getRandomColor(); // Initialize local player color

const ws = new WebSocket("ws://localhost:8282/");




document.addEventListener('DOMContentLoaded', function() {
    const gameTimerElement = document.getElementById('gameTimer');
    gameDuration = parseInt(gameTimerElement.value, 10) || 60; // Set default to 60 seconds if parsing fails
    console.log("Game Duration:", gameDuration);

    ws.onopen = () => {
        console.log("Connected to the WebSocket server");
        // Start the game timer after the connection is established
        setTimeout(() => {
            // Time has expired
            console.log("Game duration has ended.");
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({ type: "endTime", uuid: username }));
            }
        }, gameDuration * 1000); // Convert seconds to milliseconds
    };
});



console.log ("gameDuration:" . gameDuration);

// when message is received from the WebSocket server
ws.onmessage = (e) => {
    const data = JSON.parse(e.data);

    if (!username && data.type === "initS") {
        username = data.uuid; // Set username
        document.getElementById('alias').textContent = '@' + username; // Set username in the UI
        setupInitialPosition(); // Set initial position and draw on canvas
    }

    if (data.type === "move" && data.uuid !== username) {
        drawOtherPlayer(data);
    }
    else if (data.type === "point") {
        drawOtherPlayer(data);
    }

    // TODO - Handle the win and lose cases
    else if (data.type === "win") {
        if (username === data.winner) {
            window.location.href = `win.php`;
        } else {
            window.location.href = `lose.php`;
        }
    }
    else if (data.type === "winTime") {
        if (username === data.winner) {
            window.location.href = `winTime.php`;
        } else {
            window.location.href = `loseTime.php`;
        }
    }
};



function getRandomColor() { // Generate random color for the square
    const letters = '0123456789ABCDEF';
    return '#' + Array.from({length: 6}, () => letters[Math.floor(Math.random() * 16)]).join('');
}



// Draw square on canvas
function draw() { // Draw square on canvas
    ctx.fillStyle = squareColor; // Set square color
    ctx.fillRect(x, y, size, size); // Draw square

    Object.values(otherPlayers).forEach(p => {
        ctx.fillStyle = p.color;
        ctx.fillRect(p.x, p.y, size, size);
    });

    console.log(otherPlayers);
}


function setupInitialPosition() {
    // Ensure x and y are multiples of 10
    x = Math.floor((Math.random() * ((canvas.width - size) / 10))) * 10;
    y = Math.floor((Math.random() * ((canvas.height - size) / 10))) * 10;
    console.log("positions: ", x," ", y);
    updatePosition('initP', x, y);
}



function updatePosition(type, newX, newY) {
    x = newX;
    y = newY;
    draw();
    if (ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({ type: type, uuid: username, x, y, color: squareColor }));
    }
}



function drawOtherPlayer({ x, y, uuid, color }) {
    otherPlayers[uuid] = { x, y, color };
    draw();
}


document.addEventListener('keydown', (event) => {
    let newX = x, newY = y;
    switch (event.key) {
        case 'w': newY -= 10; break;
        case 's': newY += 10; break;
        case 'a': newX -= 10; break;
        case 'd': newX += 10; break;
    }
    if (newX >= 0 && newX + size <= canvas.width && newY >= 0 && newY + size <= canvas.height) {
        updatePosition('move', newX, newY);
    }
});
