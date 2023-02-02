<?php
function get_text_results($query, $page)
{
    global $config;
    $query_encoded = urlencode($query);
    $json = file_get_contents("https://crowdview-next-js.onrender.com/api/search-v3?query=$query");
    $data = json_decode($json, true);
    $results = array();

    foreach ($data["results"] as $item) {
        $title = $item["title"];
        $url = $item["link"];
        $description = $item["snippet"];

        array_push(
            $results,
            array(
                "title" => $title,
                "url" => htmlspecialchars($url),
                "base_url" => htmlspecialchars(get_base_url($url)),
                "description" => $description == null ? 
                "No description was provided for this site." :
                $description
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