<?php
$yandex_url = "https://search.skydns.ru/search/?query=$query&r=$page";
function get_yandex_results($response, $page)
{
    global $config;
    $xpath = get_xpath($response);
    $results = array();



    foreach ($xpath->query('//*[@class="search-result__wrap"]') as $result) {
        $url = $xpath->evaluate('.//div[@class="search-result__title"]//a/@href', $result)[0];
        
        if ($url == null)
            continue;


        if (!empty($results)) // filter duplicate results, ignore special result
        {
            if (!array_key_exists("special_response", end($results)))
                if (end($results)["url"] == $url->textContent)
                    continue;
        }

        $url = $url->textContent;
        

        $url = check_for_privacy_frontend($url);

        $title = $xpath->evaluate('.//div[@class="search-result__title"]//a', $result)[0];
        $description = $xpath->evaluate('.//div[@class="search-result__passage"]', $result)[0];

        array_push(
            $results,
            array(
                "title" => htmlspecialchars($title->textContent),
                "url" => htmlspecialchars($url),
                "base_url" => htmlspecialchars(get_base_url($url)),
                "description" => $description == null ? 
                "No description was provided for this site." :
                htmlspecialchars($description->textContent),
                "source" => "Yandex"
            )
        );
    }

    //echo "ya " . implode(',',$results);
    

    return $results;
}

?>