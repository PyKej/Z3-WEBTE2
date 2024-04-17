const nameSpan = document.getElementById('alias');
const msgBlock = document.getElementById('msg-block');
const msgText = document.getElementById('msg-text');

let otherPlayers = {};
// msgBtn.removeAttribute('disabled');
// msgText.addEventListener('input', (e) => {
//     if (e.target.value !== "") {
//         msgBtn.removeAttribute('disabled');
//     } else {
//         msgBtn.setAttribute('disabled', 'true');
//     }
// });

const msgBtn = document.getElementById('send');
let username;

// ws = new WebSocket("wss://node91.webte.fei.stuba.sk:8443/wss");
ws = new WebSocket("ws://localhost:8282/");

ws.onopen = function(e) {
    
};

// ws.onmessage = function(e) {

//     // console.log(e.data)
//     // console.log("Received:", e.data);  // Log incoming messages
//     data = JSON.parse(e.data);
//     if (data.uuid) {
//         nameSpan.innerHTML = `@${data.uuid}`;
//         username = data.uuid;
//     }

//     if (data.type === "move") {
//         // Here you could use different logic or a different function to draw other players based on received data
//         // console.log("Player moved to: ", data.x, data.y);
//     } 
//     else if (data.type === "message") {
//         // console.log(data.payload);
//         newMsg = document.createElement('article');
//         newMsg.classList.add('pico-background-grey-100');
        
//         if (data.sender === username) {
//             newMsg.innerHTML = `<span class="pico-color-cyan-500">@${data.sender}:</span> ${data.payload}`;
//         } else {
//             newMsg.innerHTML = `<span class="pico-color-red-500">@${data.sender}:</span> ${data.payload}`;

//         }

//         msgBlock.appendChild(newMsg);
//     }
// };


const sendMessage = () => {
    if (msgText.value !== "") {
        message = {
            type: "message",
            payload: msgText.value,
            sender: username
        }
        // console.log("Sending:", message);  // Log outgoing messages
        ws.send(JSON.stringify(message));

        msgText.value = "";
        // msgBtn.setAttribute('disabled', 'true');
    }

    // if (msgText.value !== "") {
        // newMsg = document.createElement('article');
        // newMsg.classList.add('pico-background-grey-100');
        // newMsg.innerHTML = `<span class="pico-color-cyan-500">@${username}:</span> ${msgText.value}`;

        // msgBlock.appendChild(newMsg);

        
    // }





    ws.onmessage = function(e) {
        const data = JSON.parse(e.data);
        if (data.uuid && !username) {  // Set username if it's not already set.
            username = data.uuid;
            nameSpan.innerHTML = `@${username}`; // Display the UUID in the alias span.
        }
    
        if (data.type === "move" && data.uuid !== username) {  // Ensure the data is from another player.
            drawOtherPlayer(data.x, data.y, data.uuid);
        } else if (data.type === "message") {
            // Existing code to handle messages.
        }




        // const data = JSON.parse(e.data);
        // if (data.uuid) {
        //     nameSpan.innerHTML = `@${data.uuid}`;
        //     username = data.uuid;
        // }

        // if (data.uuid && data.uuid !== username) {  // Check if the movement data is from another player
        //     if (data.type === "move") {
        //         drawOtherPlayer(data.x, data.y, data.uuid);
        //     }

        // }

        else if (data.type === "message") {
            // console.log(data.payload);
            newMsg = document.createElement('article');
            newMsg.classList.add('pico-background-grey-100');
            
            if (data.sender === username) {
                newMsg.innerHTML = `<span class="pico-color-cyan-500">@${data.sender}:</span> ${data.payload}`;
            } else {
                newMsg.innerHTML = `<span class="pico-color-red-500">@${data.sender}:</span> ${data.payload}`;
    
            }
    
            msgBlock.appendChild(newMsg);
        }








    };
    
    function drawOtherPlayer(x, y, uuid) {
        // Check if this player already exists in otherPlayers object
        if (!otherPlayers[uuid]) {
            otherPlayers[uuid] = { x, y, color: getRandomColor() };
        } else {
            otherPlayers[uuid].x = x;
            otherPlayers[uuid].y = y;
        }
    
        // Redraw the canvas including other players
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        for (let id in otherPlayers) {
            ctx.fillStyle = otherPlayers[id].color;
            ctx.fillRect(otherPlayers[id].x, otherPlayers[id].y, size, size);
        }
        // Redraw local player
        drawSquare();
    }
    
    function drawSquare() {
        ctx.fillStyle = squareColor;
        ctx.fillRect(x, y, size, size);
    }
}


