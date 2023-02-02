<?php
function get_image_results($query, $page)
{
    global $config;
    $query_encoded = urlencode($query);

    //$page = $page / 10 + 1; // qwant has a different page system


    $google_api_key = $config->google_api_key;
    $google_api_app = $config->google_api_app;
    $json = file_get_contents("https://www.googleapis.com/customsearch/v1?key=$google_api_key&cx=$google_api_app&lr=lang_ru&searchType=image&q=$query_encoded&start=$page");

    //$url = "https://lite.qwant.com/?q=$query&t=images&p=$page";
    $data = json_decode($json, true);

    $results = array();

    foreach ($data["items"] as $item) {
        $thumbnail = $item["image"]["thumbnailLink"];
        $alt = $item["snippet"];
        $real_url = $item["image"]["contextLink"];

        array_push(
            $results,
            array(
                "thumbnail" => urldecode(htmlspecialchars($thumbnail)),
                "alt" => htmlspecialchars($alt),
                "url" => htmlspecialchars($real_url)
            )
        );

    }

    return $results;
}

function print_image_results($results)
{
    echo "<div class=\"image-result-container\">";

    foreach ($results as $result) {
        $thumbnail = urlencode($result["thumbnail"]);
        $alt = $result["alt"];
        $url = $result["url"];

        echo "<a title=\"$alt\" href=\"$url\" target=\"_blank\">";
        echo "<img src=\"image_proxy.php?url=$thumbnail\">";
        echo "</a>";
    }

    echo "</div>";
}
?>