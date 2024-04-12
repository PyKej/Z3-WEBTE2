

<?php

$urls = [ 
    [
        'name' => 'Ústav automobilovej mechatroniky',
        'url' => 'https://is.stuba.sk/pracoviste/prehled_temat.pl?lang=sk;pracoviste=642',
        'zameranieIsSet' => true,
        'pracovisko' => '642',
    ],
    [
        'name' => 'Ústav elektroenergetiky a aplikovanej elektrotechniky',
        'url' => 'https://is.stuba.sk/pracoviste/prehled_temat.pl?lang=sk;pracoviste=548',
        'zameranieIsSet' => true,
        'pracovisko' => '548',
    ],
    [
        'name' => 'Ústav elektroniky a fotoniky',
        'url' => 'https://is.stuba.sk/pracoviste/prehled_temat.pl?lang=sk;pracoviste=549',
        'zameranieIsSet' => false,
        'pracovisko' => '549',
    ],
    [
        'name' => 'Ústav elektrotechniky',
        'url' => 'https://is.stuba.sk/pracoviste/prehled_temat.pl?lang=sk;pracoviste=550',
        'zameranieIsSet' => false,
        'pracovisko' => '550',
    ],
    [
        'name' => 'Ústav informatiky a matematiky',
        'url' => 'https://is.stuba.sk/pracoviste/prehled_temat.pl?lang=sk;pracoviste=816',
        'zameranieIsSet' => true,
        'pracovisko' => '816',
    ],
    [
        'name' => 'Ústavjadroúeho a fyzikálneho inžinierstva',
        'url' => 'https://is.stuba.sk/pracoviste/prehled_temat.pl?lang=sk;pracoviste=817',
        'zameranieIsSet' => true,
        'pracovisko' => '817',
    ],
    [
        'name' => 'Ústav multimediálnych informaˇcných a komunikaˇcných technológí',
        'url' => 'https://is.stuba.sk/pracoviste/prehled_temat.pl?lang=sk;pracoviste=818',
        'zameranieIsSet' => false,
        'pracovisko' => '818',
    ],
    [
        'name' => 'Ústav robotiky a kybernetiky',
        'url' => 'https://is.stuba.sk/pracoviste/prehled_temat.pl?lang=sk;pracoviste=356',
        'zameranieIsSet' => false,
        'pracovisko' => '356',
    ],
            
];

$projects = [];

foreach ($urls as $urlInfo) {
    $url = $urlInfo['url'];
    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    // Add any additional options needed

    // Execute cURL session and get the content
    $htmlContent = curl_exec($ch);

    // Check if any error occurred
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        curl_close($ch);
        continue; // Skip to the next URL if there is an error
    }

    // Close cURL session
    curl_close($ch);

    // Parsing the HTML content
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($htmlContent);
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);

    // XPath query to get rows of the project table
    $rows = $xpath->query('//table/tbody/tr');

    foreach ($rows as $row) {
        // if any field is empty, skip the row
        // add to the if a hrew with the detail number

        if (    empty($xpath->query('.//td[3]', $row)->item(0)->nodeValue) ||
                empty($xpath->query('.//td[4]//a', $row)->item(0)->nodeValue) || 
                empty($xpath->query('.//td[5]', $row)->item(0)->nodeValue) || 
                empty($xpath->query('.//td[6]', $row)->item(0)->nodeValue)
        ) {continue;}


        $detailNumber = null;
            

        // zameranie a detail je iny
        if ($urlInfo['zameranieIsSet']) {
            $zameranie = $xpath->query('.//td[7]', $row)->item(0)->nodeValue;

            $detailNode = $xpath->query('.//td[9]//a/@href', $row)->item(0);
            if ($detailNode) {
                preg_match('/detail=(\d+)/', $detailNode->nodeValue, $matches);
                $detailNumber = $matches[1] ?? null;
            }
            $obsadenost = $xpath->query('.//td[10]', $row)->item(0)->nodeValue;
        }
        else {
            $zameranie = '--';

                // Check if the node exists before trying to get its value
            $detailNode = $xpath->query('.//td[8]//a/@href', $row)->item(0);
            if ($detailNode) {
                preg_match('/detail=(\d+)/', $detailNode->nodeValue, $matches);
                $detailNumber = $matches[1] ?? null;
            }
            $obsadenost = $xpath->query('.//td[9]', $row)->item(0)->nodeValue;
        }

        // check collum obsadenost to have at least if on the left side of / is smaller number than on the right side than don't skip the row, else skip the row
        $obsadenostNew = explode('/', $obsadenost);
        if ($obsadenostNew[1] == ' --' || $obsadenostNew[0] < $obsadenostNew[1]) {

            // Extract the columns from the row
            $typ = $xpath->query('.//td[2]', $row)->item(0)->nodeValue;
            $nazov = $xpath->query('.//td[3]', $row)->item(0)->nodeValue;
            $veduci = $xpath->query('.//td[4]//a', $row)->item(0)->nodeValue;
            $pracovisko = $xpath->query('.//td[5]', $row)->item(0)->nodeValue;
            $program = $xpath->query('.//td[6]', $row)->item(0)->nodeValue;
            
            $projects[] = [
                'typ' => trim($typ),
                'nazov_temy' => trim($nazov),
                'veduci_prace' => trim($veduci),
                'garantujuce_pracovisko' => trim($pracovisko),
                'program' => trim($program),
                'zameranie' => trim($zameranie),
                'detail' => $detailNumber,
                'obsadenost' => trim($obsadenost),
                'pracovisko' => $urlInfo['pracovisko'],
            ];
        }
    }
}


?>