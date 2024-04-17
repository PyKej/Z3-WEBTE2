const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');


let username;
let otherPlayers = {};

let x, y;
const size = 20;
const squareColor = getRandomColor(); // Initialize local player color

const ws = new WebSocket("ws://localhost:8282/");

ws.onopen = () => console.log("Connected to the WebSocket server");


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
    x = Math.floor(Math.random() * (canvas.width - size));
    y = Math.floor(Math.random() * (canvas.height - size));

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
