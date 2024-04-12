<?php
$dom = new DOMDocument();
@$dom->loadHTML($response); // Suppress warnings from malformed HTML
$xpath = new DOMXPath($dom);

$subjects = [];

// Query for all rows in the timetable
$rows = $xpath->query("//tbody/tr[not(contains(@class, 'rozvrh-sep'))]"); // Exclude separator rows

foreach ($rows as $row) {
    // Extract the day from the first cell of the row
    $dayNode = $xpath->query(".//td[contains(@class, 'zahlavi')]", $row)->item(0);
    if ($dayNode) {
        $day = $dayNode->nodeValue;

        // Find all subject nodes (td elements) for this day
        $subjectNodes = $xpath->query(".//td[contains(@class, 'rozvrh-pred') or contains(@class, 'rozvrh-cvic')]", $row);
        
        foreach ($subjectNodes as $node) {
            $type = strpos($node->getAttribute('class'), 'rozvrh-pred') !== false ? 'prednáška' : 'cvičenie';
            $nameLinks = $xpath->query(".//a", $node);
            $name = '';
            $classroom = '';

            if ($nameLinks->length > 0) {
                // Assuming the first link contains the classroom
                $classroom = trim(explode(' ', $nameLinks->item(0)->nodeValue)[0]);
                // Assuming the second link contains the subject name
                if ($nameLinks->length > 1) {
                    $name = $nameLinks->item(1)->nodeValue;
                }
            }

            // Append this subject to the subjects array
            $subjects[] = [
                'day' => $day,
                'type' => $type,
                'name' => $name,
                'classroom' => $classroom,
            ];
        }
    }
}


// TODO urob 
// $jsonData = json_encode($subjects, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
// file_put_contents('../curl/subjects.json', $jsonData);

?>