<?php
class TimetableAPI {
    protected $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function createSubject($data) {
        // echo 'Mydata: ' . $data . '<br>';
        $stmt = $this->db->prepare('INSERT INTO timetable (day, type, name, classroom) VALUES (:day, :type, :name, :classroom)');

        // check if the data is an array
        if (!is_array($data)) {
            http_response_code(400);
            

            return ['message' => 'Invalid data'];

        }

        foreach ($data as $row) {
            $stmt->execute([
                'day' => $row['day'],
                'type' => $row['type'],
                'name' => $row['name'],
                'classroom' => $row['classroom'],
            ]);
        }
        return ['message' => 'Subject created successfully'];
    }

    public function getCurlTimetable() {
        require_once('../curl/curlTimetable.php');
        // echo json_encode(['message' => 'Niceeee']);
        return ['message' => 'Curl was succesfull', 'subjects' => $subjects];
    }

    public function getTimeTable() {
        $stmt = $this->db->query('SELECT * FROM timetable');
        $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $subjects;
    }

    public function deleteSubject($id) {
        $stmt = $this->db->prepare('DELETE FROM timetable WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

     // In TimetableAPI class
    public function updateSubject($id, $data) {
        // Construct and execute an SQL UPDATE statement using $data
        // Return true if successful, false otherwise
        $stmt = $this->db->prepare('UPDATE timetable SET day = :day, type = :type, name = :name, classroom = :classroom WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':day', $data['day'], PDO::PARAM_STR);
        $stmt->bindParam(':type', $data['type'], PDO::PARAM_STR);
        $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindParam(':classroom', $data['classroom'], PDO::PARAM_STR);
        return $stmt->execute();
        
    }
    
}
?>