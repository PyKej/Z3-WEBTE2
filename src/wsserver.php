<?php
use Workerman\Worker;
use Workerman\Connection\TcpConnection;

require_once __DIR__ . '/vendor/autoload.php';

$players = []; // Store player states


$ws_worker = new Worker("websocket://0.0.0.0:8282");
$ws_worker->count = 1;

// to handle new connections
$ws_worker->onConnect = function($connection) use (&$players, $ws_worker) {
    $uuid = uniqid();
    $playerData = ["type" => "initS", "uuid" => $uuid];
    
    $connection->send(json_encode($playerData)); // Sending playerData to the New Connection:
};

// Handle Incoming Messages
$ws_worker->onMessage = function(TcpConnection $connection, $data) use ($ws_worker, &$players) {
    $decoded = json_decode($data, true);    // decodes the JSON data into a PHP  associative array

    if ($decoded && $decoded['type'] === 'initP') { // If 'type' == 'initP'

        // create the player and store the first data in it
        $players[$decoded['uuid']] = [
            'color' => $decoded['color'],
            'positions' => [
                ['posX' => $decoded['x'], 'posY' => $decoded['y']]
            ],
            'points' => []  // Initialize points as an empty array
        ];



        send5Points($decoded, $ws_worker);

        // send data of other players to the new player
        sendDataOfOtherPoints($players, $decoded, $connection); // Send points

        sendDataOfOtherPositions($players, $decoded, $connection);  // Send positions
        

        // Modify type to 'move' for broadcasting
        $decoded['type'] = 'move';
        $broadcastData = json_encode($decoded);
        broadcast($ws_worker, $connection, $broadcastData);

        // TODO here write code to send 5 random positions of random points same color as the player on the canvas
        




        

        // send data of other players points to the new player 
        //  simmilar as sendDataOfOtherPositions

   

















    }
    else if ($decoded && $decoded['type'] === 'move') { // If 'type' == 'move'
        // addPosition($players, $decoded['uuid'], $decoded['x'], $decoded['y']); // Add the new position to the players array
        addPosition($players, $decoded['uuid'], $decoded['x'], $decoded['y'], $connection, $ws_worker); // Add the new position to the players array
        broadcast($ws_worker, $connection, $data); // // Broadcast the new player state to all other players

        // TODO testing
        // echo '<broadcast>';
        // print_r($data);
        // echo '<broadcast>';
    }

    // TODO testind
    // echo '<pre>';
    // print_r($players);
    // echo '<pre>';


};

function send5Points($decoded, $ws_worker) {
    $decodedColor = $decoded['color'];
    if ($decodedColor[0] === '#') {
        $decodedColor = substr($decodedColor, 1);
    }

    $r = hexdec(substr($decodedColor, 0, 2));
    $g = hexdec(substr($decodedColor, 2, 2));
    $b = hexdec(substr($decodedColor, 4, 2));

    $darkerColor = [
        'r' => max($r - 80, 0),
        'g' => max($g - 80, 0),
        'b' => max($b - 80, 0)
    ];

    $darkerColorHex = sprintf("#%02x%02x%02x", $darkerColor['r'], $darkerColor['g'], $darkerColor['b']);

    global $players; // Ensure $players is accessible if not already declared with `use`
    for ($i = 0; $i < 5; $i++) {
        $randX = rand(0, 700);
        $randY = rand(0, 500);
        $pointData = [
            "type" => "point",
            "uuid" => $decoded['uuid'],
            "x" => $randX,
            "y" => $randY,
            "color" => $darkerColorHex
        ];

        if (isset($players[$decoded['uuid']])) {
            $players[$decoded['uuid']]['points'][] = ['posX' => $randX, 'posY' => $randY, 'color' => $darkerColorHex];
            echo "Point added: " . print_r($players[$decoded['uuid']]['points'], true);
        } else {
            echo "Error: UUID not found in players array.\n";
        }

        // Broadcast these points to all clients
        broadcast($ws_worker, null, json_encode($pointData));
    }
}



// Function to add a new position
// function addPosition(&$players, $uuid, $posX, $posY) {
//     if (isset($players[$uuid])) {
//         $players[$uuid]['positions'][] = ['posX' => $posX, 'posY' => $posY];
//     } else {
//         echo "Player not found.";
//     }
// }



function addPosition(&$players, $uuid, $posX, $posY, $connection, $ws_worker) {
    if (isset($players[$uuid])) {
        $players[$uuid]['positions'][] = ['posX' => $posX, 'posY' => $posY];
        // Check for collisions after adding the new position
        $collisionUuid = checkCollision($players, $uuid, $posX, $posY);
        if ($collisionUuid !== false) {
            // Collision detected, handle win logic here
            $winMessage = json_encode([
                'type' => 'win',
                'winner' => $uuid,
                'loser' => $collisionUuid
            ]);
            broadcast($ws_worker, null, $winMessage); // Broadcast win message to all clients

            // Reset the game state
            $players = [];

        }
    } else {
        echo "Player not found.";
    }
}












// TODO only to show
function readAllPositions($players) {
     // readAllPositions($players); // TODO testing how to call
    foreach ($players as $uuid => $playerData) {
        echo "Player " . $uuid . " positions:\n";
        foreach ($playerData['positions'] as $position) {
            echo "X: " . $position['posX'] . ", Y: " . $position['posY'] . "\n";
        }
        echo "\n"; // Adds a newline for better readability
    }
}

// TODO Function to remove a position
function removePosition(&$players, $uuid, $positionIndex) {
    if (isset($players[$uuid]['positions'][$positionIndex])) {
        unset($players[$uuid]['positions'][$positionIndex]);
        // Optionally reindex the positions array if strict sequential keys are needed
        $players[$uuid]['positions'] = array_values($players[$uuid]['positions']);
    } else {
        echo "Position not found.";
    }
}

function broadcast($ws_worker, $connection, $data){
    foreach ($ws_worker->connections as $conn) {
        if ($connection === null || $conn !== $connection) {
            $conn->send($data); // Send the data to all other connections
            // sendDataOfOtherPositions($players, $decoded, $conn); 
        }
    }
}

// send the data to all other connections
function sendDataOfOtherPositions($players, $decoded, $connection) {
    foreach ($players as $playerUUID => $playerData) {
        if ($playerUUID !== $decoded['uuid']) {
            foreach ($playerData['positions'] as $position) {
                // echo "X: " . $position['posX'] . ", Y: " . $position['posY'] . "\n";
                // send here every position of the players like that $connection->send(json_encode($playerData));
                // type: type, uuid: username, x, y, color: squareColor
                $data = ["type" => "move", "uuid" => $playerUUID, "x" => $position['posX'], "y" => $position['posY'], "color" => $playerData['color']];
                $connection->send(json_encode($data));
            }
        }
    }
}

function sendDataOfOtherPoints($players, $decoded, $connection) {
    foreach ($players as $playerUUID => $playerData) {
        if ($playerUUID !== $decoded['uuid']) { // Ensure not sending data to self
            foreach ($playerData['points'] as $point) {
                $pointData = [
                    "type" => "point",
                    "uuid" => $playerUUID,
                    "x" => $point['posX'],
                    "y" => $point['posY'],
                    "color" => $point['color']
                ];
                $connection->send(json_encode($pointData));
            }
        }
    }
}


function checkCollision($players, $currentUuid, $posX, $posY) {
    foreach ($players as $uuid => $playerData) { // Iterate through all players
        if ($uuid !== $currentUuid) { // Don't check against self
            foreach ($playerData['positions'] as $position) { // Iterate through all positions of the player
                if ($position['posX'] === $posX && $position['posY'] === $posY) { // Check for collision
                    return $uuid; // Return the UUID of the player with whom the collision occurred
                }
            }
        }
    }
    return false;
}

// Add a new position to player1

// Handle Disconnections
// $ws_worker->onClose = function($connection) use (&$players) {
//     if (isset($connection->uuid)) {
//         unset($players[$connection->uuid]);
//     }
// };

Worker::runAll(); // Start the server
?>
