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
            ]
        ];

        // send data of other players to the new player
        sendDataOfOtherPositions($players, $decoded, $connection);

        // Modify type to 'move' for broadcasting
        $decoded['type'] = 'move';
        $broadcastData = json_encode($decoded);
        broadcast($ws_worker, $connection, $broadcastData);
    }
    else if ($decoded && $decoded['type'] === 'move') { // If 'type' == 'move'
        addPosition($players, $decoded['uuid'], $decoded['x'], $decoded['y']); // Add the new position to the players array
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
// Function to add a new position
function addPosition(&$players, $uuid, $posX, $posY) {
    if (isset($players[$uuid])) {
        $players[$uuid]['positions'][] = ['posX' => $posX, 'posY' => $posY];
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
        if ($conn !== $connection) {
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

// Add a new position to player1

// // Handle Disconnections
// $ws_worker->onClose = function($connection) use (&$players) {
//     if (isset($connection->uuid)) {
//         unset($players[$connection->uuid]);
//     }
// };

Worker::runAll(); // Start the server
?>
