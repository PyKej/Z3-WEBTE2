<?php
require_once('../db/connect.php'); // Database configuration
require_once('timetable_api.php'); // The logic for handling timetable data
require_once('endAssigment_api.php'); // The logic for handling end assigment data

header("Content-Type: application/json");

$conn = getDBConnection();
$timetable = new TimetableAPI($conn);
$endAssigment = new EndAssigmentAPI($conn);

$method = $_SERVER['REQUEST_METHOD'];
// $endpoint = $_SERVER['REQUEST_URI'];

$uri = $_SERVER['REQUEST_URI'];
$endpoint = substr($uri, strpos($uri, '/api.php/') + strlen('/api.php'));

switch ($method) {
    case 'GET':
        if($endpoint == '/curlTimetable') { // Adapt this endpoint to your requirements
            http_response_code(200);
            echo json_encode($timetable->getCurlTimetable());
        }
        else if($endpoint == '/timetable') { // Adapt this endpoint to your requirements
            http_response_code(200);
            echo json_encode($timetable->getTimeTable());
        }
        else if ($endpoint == '/endAssigment'){
            http_response_code(200);
            echo json_encode($endAssigment->getEndAssigment());
        }
        else if (preg_match('/^\/endAssigment\/(\d+)\/(\d+)$/', $endpoint, $matches)) {
            $detailNum = $matches[1];
            $pracovisko = $matches[2];
            echo json_encode($endAssigment->getAnnotation($detailNum, $pracovisko));
        } 
        else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
            
        }
        break;
    case 'POST':
        if($endpoint == '/timetable') { // Adapt this endpoint for creating a new timetable entry
            // echo $data; // TODO Remove this line
            http_response_code(201);
            $data = json_decode(file_get_contents('php://input'), true);
            // echo $data; // TODO Remove this line
            echo json_encode($timetable->createSubject($data));
        }
        else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'DELETE':
        // Assuming URL pattern /api.php/timetable/{id} for deletion
        if(preg_match('/^\/timetable\/(\d+)$/', $endpoint, $matches)) {
            $id = $matches[1];
            if($timetable->deleteSubject($id)) {
                http_response_code(200);
                echo json_encode(['message' => 'Subject deleted successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Subject not found']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
        case 'PUT':
            // Assuming URL pattern /api.php/timetable/{id} for updates
            if(preg_match('/^\/timetable\/(\d+)$/', $endpoint, $matches)) {
                $id = $matches[1];
                $data = json_decode(file_get_contents('php://input'), true);
                if($timetable->updateSubject($id, $data)) {
                    http_response_code(200);
                    echo json_encode(['message' => 'Subject updated successfully']);
                } else {
                    http_response_code(404);
                    echo json_encode(['message' => 'Subject not found']);
                }
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Not Found']);
            }
        break;
    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}

?>