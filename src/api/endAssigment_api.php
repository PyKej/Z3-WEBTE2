<?php
class EndAssigmentAPI {
    protected $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getEndAssigment() {
        require_once('../curl/curlEndAssigment.php');
        return ['message' => 'Curl was succesfull', 'projects' => $projects];
        
    }

    public function getAnnotation($detailNum, $pracovisko) {
        // Your given URL
        $url = 'https://is.stuba.sk/pracoviste/prehled_temat.pl?detail='. $detailNum .';pracoviste='. $pracovisko . ';lang=sk';
        // Initialize cURL session
        $ch = curl_init($url);
    
        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        // Add any additional options needed

         // Set cURL options

        // Execute cURL session and get the content
        $htmlContent = curl_exec($ch);

        // Close cURL session
        curl_close($ch);

        if ($htmlContent) {
            $dom = new DOMDocument();
            @ $dom->loadHTML(mb_convert_encoding($htmlContent, 'HTML-ENTITIES', 'UTF-8'));
            $xpath = new DOMXPath($dom);

            // Adjusted XPath query to potentially match both "Abstrakt" and "Anotacia"
            $queries = [
                '//tr[td[contains(b, "Abstrakt:")]]/td[2]',
                '//tr[td[contains(b, "Anotácia:")]]/td[2]', // Assuming 'Anotacia' follows a similar structure
            ];

            $notEmpty = false;

            foreach ($queries as $query) {
                $nodes = $xpath->query($query);
                if ($nodes->length > 0) {
                    $notEmpty = true;
                    foreach ($nodes as $node) {
                        // echo trim($node->nodeValue) . "\n";
                        $annotation = trim($node->nodeValue);
                    }
                }
            }
        } else {
            echo "Failed to fetch content from the URL.";
        }

        // if abstrakt or anotacia isn't empty return message that curl of abstrakt or anotacia was succesfull
        // else return message that curl of abstrakt or anotacia failed
        if($notEmpty) {
            http_response_code(200);
            return ['message' => 'Curl of annotation was succesfull', 'annotation' => $annotation];
        } else {
            http_response_code(404);
            return ['message' => 'Curl of annotation failed'];
        }

    }
    
}
?>