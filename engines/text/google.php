<?php

$domain = $config->google_domain;
$language = isset($_COOKIE["google_language"]) ? htmlspecialchars($_COOKIE["google_language"]) : $config->google_language;
$google_url = "https://www.google.$domain/search?&q=$query&start=$page&hl=$language&lr=lang_$language&asearch=arc&async=use_ac:true,_fmt:prog";

if (isset($_COOKIE["safe_search"])) {
    $google_url .= "&safe=medium";
}

function get_google_results($response, $page)
{
    global $config;


    $results = array();
    $xpath = get_xpath($response);



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

        $url = mb_convert_encoding($url->textContent, "ISO-8859-1", "UTF-8");

        $url = check_for_privacy_frontend($url);

        $title = $xpath->evaluate(".//h3", $result)[0];
        $title = mb_convert_encoding($title->textContent, "ISO-8859-1", "UTF-8");
        $description = $xpath->evaluate(".//div[contains(@class, 'VwiC3b')]", $result)[0];
        $description = mb_convert_encoding($description->textContent, "ISO-8859-1", "UTF-8");

        array_push(
            $results,
            array(
                "title" => htmlspecialchars($title),
                "url" => htmlspecialchars($url),
                "base_url" => htmlspecialchars(get_base_url($url)),
                "description" => $description == null ? 
                "No description was provided for this site." :
                htmlspecialchars($description),
                "source" => "Google"
            )
        );
    }

    

    return $results;
}

?>
