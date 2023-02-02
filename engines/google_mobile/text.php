<?php
function get_text_results($query, $page)
{
    global $config;

    $mh = curl_multi_init();
    $query_encoded = urlencode($query);
    $results = array();

    $domain = $config->google_domain;
    $language = isset($_COOKIE["google_language"]) ? htmlspecialchars($_COOKIE["google_language"]) : $config->google_language;

    $url = "https://www.google.$domain/search?&q=$query_encoded&start=$page&hl=$language&lr=lang_$language&asearch=arc&async=use_ac:true,_fmt:prog";

    if (isset($_COOKIE["safe_search"])) {
        $url .= "&safe=medium";
    }

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => [
            "Accept: */*",
            "User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:91.0) Gecko/20100101 Firefox/91.0"
        ],
    ]);

    $response = curl_exec($curl);

    curl_close($curl);

    $xpath = get_xpath($response);


    /*
    $nodes = $xpath->query("/html/body/div[7]/div/div[11]/div/div[1]/div[1]/p/a");
    
    echo "Показаны результаты по запросу " . $nodes[0]->nodeValue . "<br>";
    echo "Искать вместо этого " . $nodes[1]->nodeValue . "<br";
    echo $nodes[1]->getAttribute("href") . "<br>";
    foreach ($nodes as $i => $node) {
    echo $node->nodeValue . "<br>";
    $href = $node->getAttribute("href");
    echo $href . PHP_EOL . "<br>";
    }
    */
    foreach ($xpath->query("//div//div[contains(@class, 'g')]") as $result) {
        $url = $xpath->evaluate(".//div[@class='yuRUbf']//a/@href", $result)[0];

        if ($url == null)
            continue;

        if (!empty($results)) // filter duplicate results, ignore special result
        {
            if (!array_key_exists("special_response", end($results)))
                if (end($results)["url"] == $url->textContent)
                    continue;
        }

        $url = mb_convert_encoding($url->textContent, "windows-1252", "UTF-8");

        $url = check_for_privacy_frontend($url);

        $title = $xpath->evaluate(".//h3", $result)[0];
        $title = mb_convert_encoding($title->textContent, "windows-1252", "UTF-8");
        $description = $xpath->evaluate(".//div[contains(@class, 'VwiC3b')]", $result)[0];
        $description = mb_convert_encoding($description->textContent, "windows-1252", "UTF-8");

        array_push(
            $results,
            array(
                "title" => htmlspecialchars($title),
                "url" => htmlspecialchars($url),
                "base_url" => htmlspecialchars(get_base_url($url)),
                "description" => $description == null ? 
                "No description was provided for this site." :
                htmlspecialchars($description)
            )
        );
    }

    return $results;
}

function print_text_results($results)
{
    echo "<div class=\"text-result-container\">";

    foreach ($results as $result) {
        $title = $result["title"];
        $url = $result["url"];
        $base_url = $result["base_url"];
        $description = $result["description"];
        $domain = parse_url($base_url)["host"];

        echo "<div class=\"text-result-wrapper\">";
        echo "<img class=\"favicon-wrapper\" src=\"image_proxy.php?url=https://favicon.yandex.net/favicon/$domain\">";
        echo "<a href=\"$url\">";
        echo urldecode($url);
        echo "<h2>$title</h2>";
        echo "</a>";
        echo "<span>$description</span>";
        echo "</div>";
    }

    echo "</div>";
}
?>