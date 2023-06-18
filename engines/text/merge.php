<?php

function get_merged_torrent_results($query, $page)
{
    global $config;

    require "engines/text/yandex.php";
    require "engines/text/google.php";

    //$query = urlencode($query);

    $torrent_urls = array(
        $yandex_url,
        $google_url
    );

    $mh = curl_multi_init();
    $chs = $results = array();

    foreach ($torrent_urls as $url) {
        $ch = curl_init($url);
        curl_setopt_array($ch, $config->curl_settings);
        array_push($chs, $ch);
        curl_multi_add_handle($mh, $ch);
    }



    $special_search = $page ? 0 : check_for_special_search($query);
    $special_ch = null;
    $url = null;
    if ($special_search != 0) {
        switch ($special_search) {
            case 1:
                $url = "https://cdn.moneyconvert.net/api/latest.json";
                break;
            case 2:
                $split_query = explode(" ", $query);
                $reversed_split_q = array_reverse($split_query);
                $word_to_define = $reversed_split_q[1];
                $url = "https://api.dictionaryapi.dev/api/v2/entries/en/$word_to_define";
                break;
            case 5:
                $url = "https://wttr.in/@" . $_SERVER["REMOTE_ADDR"] . "?format=j1";
                break;
            case 6:
                $url = "https://check.torproject.org/torbulkexitlist";
                break;
            case 7:
                $url = "https://ru.wikipedia.org/w/api.php?format=json&action=query&prop=extracts%7Cpageimages&exintro&explaintext&redirects=1&pithumbsize=500&titles=$query";
                break;
        }

        if ($url != NULL) {
            $special_ch = curl_init($url);
            curl_setopt_array($special_ch, $config->curl_settings);
            curl_multi_add_handle($mh, $special_ch);
        }
    }

    $running = null;
    do {
        curl_multi_exec($mh, $running);
    } while ($running);

    $unique_results = array();
    if ($special_search != 0) {
        $special_result = null;

        switch ($special_search) {
            case 1:
                require "engines/special/currency.php";
                $special_result = currency_results($query, curl_multi_getcontent($special_ch));
                break;
            case 2:
                require "engines/special/definition.php";
                $special_result = definition_results($query, curl_multi_getcontent($special_ch));
                break;

            case 3:
                require "engines/special/ip.php";
                $special_result = ip_result();
                break;
            case 4:
                require "engines/special/user_agent.php";
                $special_result = user_agent_result();
                break;
            case 5:
                require "engines/special/weather.php";
                $special_result = weather_results(curl_multi_getcontent($special_ch));
                print_r($special_result);
                break;
            case 6:
                require "engines/special/tor.php";
                $special_result = tor_result(curl_multi_getcontent($special_ch));
                break;
            case 7:
                require "engines/special/wikipedia.php";
                $special_result = wikipedia_results($query, curl_multi_getcontent($special_ch));
                break;
        }

        if ($special_result != null)
            array_push($unique_results, $special_result);
    }

    for ($i = 0; count($chs) > $i; $i++) {
        $response = curl_multi_getcontent($chs[$i]);

        switch ($i) {
            case 0:
                $temp_results = get_yandex_results($response, $page);

                break;
            case 1:
                $temp_results = get_google_results($response, $page);
                break;
        }

        foreach ($temp_results as $result) {
            $domain = $result["url"];

            if (!isset($unique_results[$domain])) {
                $unique_results[$domain] = $result;
            } else {
                $unique_results[$domain]["source"] .= ", " . $result["source"];
            }
        }
    }

    return $unique_results;
}

function print_merged_torrent_results($unique_results)
{

    if (array_key_exists(0, $unique_results)) {
        $special = $unique_results[0];
        if (array_key_exists("special_response", $special)) {
            $response = $special["special_response"]["response"];
            $source = $special["special_response"]["source"];

            echo "<p class=\"special-result-container\">";
            if (array_key_exists("image", $special["special_response"])) {
                $image_url = $special["special_response"]["image"];
                echo "<img src=\"image_proxy.php?url=$image_url\">";
            }
            echo $response;
            if ($source)
                echo "<a href=\"" . urldecode($source) . "\" target=\"_blank\">" . urldecode($source) . "</a>";
            echo "</p>";

            array_shift($unique_results);
        }
    }





    echo "<div class=\"text-result-container\">";

    if (!empty($unique_results)) {
        usort($unique_results, function ($a, $b) {
            $a_engines = count(explode(",", $a["source"]));
            $b_engines = count(explode(",", $b["source"]));

            if ($a_engines == $b_engines) {
                return 0;
            }

            return ($a_engines > $b_engines) ? -1 : 1;
        });
        foreach ($unique_results as $result) {
            $title = $result["title"];
            $url = $result["url"];
            $base_url = $result["base_url"];
            $description = $result["description"];
            $domain = parse_url($base_url)["host"];
            $source = $result["source"];


            echo "<div class=\"text-result-wrapper\">";
            echo "<img class=\"favicon-wrapper\" src=\"image_proxy.php?url=https://favicon.yandex.net/favicon/$domain\" async>";
            echo "<a href=\"$url\">";
            echo urldecode($url);
            echo "<h2>$title</h2>";
            echo "</a>";
            echo "<span>$description</span><br>";
            echo '<div class="engines"> <span>' . $source . '</span>
                <a href="https://web.archive.org/web/' . $url . '" class="cache_link" rel="noreferrer">
                    <svg class="ion-icon-small" viewBox="0 0 512 512" aria-hidden="true">
                        <circle cx="256" cy="256" r="32" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"></circle>
                        <circle cx="256" cy="416" r="32" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"></circle>
                        <circle cx="256" cy="96" r="32" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"></circle>
                    </svg>веб-архив</a>&lrm; 
            </div>';
            echo "</div>";
        }
    } else
        echo "<p>Ничего не найдено, попробуйте изменить поисковой запрос</p>";

    echo "</div>";
}

?>