<?php

    function get_merged_torrent_results($query, $page)
    {
        global $config;

        require "engines/text/yandex.php";
        require "engines/text/google.php";

        $query = urlencode($query);

        $torrent_urls = array(
            $yandex_url,
            $google_url
        );

        $mh = curl_multi_init();
        $chs = $results = array();

        foreach ($torrent_urls as $url)
        {
            $ch = curl_init($url);
            curl_setopt_array($ch, $config->curl_settings);
            array_push($chs, $ch);
            curl_multi_add_handle($mh, $ch);    
        }

        $running = null;
        do {
            curl_multi_exec($mh, $running);
        } while ($running);

        for ($i=0; count($chs)>$i; $i++)
        {
            $response = curl_multi_getcontent($chs[$i]);

            switch ($i)
            {
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
        echo "<div class=\"text-result-container\">";

        if (!empty($unique_results)) 
        {
            usort($unique_results, function($a, $b) {
                $a_engines = count(explode(",", $a["source"]));
                $b_engines = count(explode(",", $b["source"]));
            
                if ($a_engines == $b_engines) {
                    return 0;
                }
            
                return ($a_engines > $b_engines) ? -1 : 1;
            });
            foreach($unique_results as $result) 
            {
                $title = $result["title"];
                $url = $result["url"];
                $base_url = $result["base_url"];
                $description = $result["description"];
                $domain = parse_url($base_url)["host"];
                $source = $result["source"];


                echo "<div class=\"text-result-wrapper\">";
                echo "<img class=\"favicon-wrapper\" src=\"image_proxy.php?url=https://favicon.yandex.net/favicon/$domain\">";
                echo "<a href=\"$url\">";
                echo urldecode($url);
                echo "<h2>$title</h2>";
                echo "</a>";
                echo "<span>$description</span><br>";
                echo '<div class="engines"> <span>' . $source . '</span>
                <a href="https://web.archive.org/web/https://www.speedtest.net/" class="cache_link" rel="noreferrer">
                    <svg class="ion-icon-small" viewBox="0 0 512 512" aria-hidden="true">
                        <circle cx="256" cy="256" r="32" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"></circle>
                        <circle cx="256" cy="416" r="32" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"></circle>
                        <circle cx="256" cy="96" r="32" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"></circle>
                    </svg>веб-архив</a>&lrm; 
            </div>';
                echo "</div>";
            }
        }
        else
            echo "<p>Ничего не найдено, попробуйте изменить поисковой запрос</p>";

        echo "</div>";
    }

?>
