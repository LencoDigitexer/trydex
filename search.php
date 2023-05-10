<?php 
    require "misc/header.php";

    $config = require "config.php";
    require "misc/tools.php";
?>

<title>
<?php
  $query = htmlspecialchars(trim($_REQUEST["q"]));
  echo $query;
?> - TrydeX</title>
</head>
    <body>
        <form class="sub-search-container" method="get" autocomplete="off">
            <h1 class="logomobile"><a class="no-decoration" href="./">Tryde<span class="X">X</span></a></h1>
            <input type="text" name="q"
                <?php
                    $query_encoded = urlencode($query);

                    if (1 > strlen($query) || strlen($query) > 256)
                    {
                        header("Location: ./");
                        die();
                    }

                    echo "value=\"$query\"";
                ?>
            >
            <br>
            <?php
                foreach($_REQUEST as $key=>$value)
                {
                    if ($key != "q" && $key != "p" && $key != "t")
                    {
                        echo "<input type=\"hidden\" name=\"" . htmlspecialchars($key) . "\" value=\"" . htmlspecialchars($value) . "\"/>";
                    }
                }

                $type = isset($_REQUEST["t"]) ? (int) $_REQUEST["t"] : 0;
                echo "<button class=\"hide\" name=\"t\" value=\"$type\"/></button>";
            ?>
            <button type="submit" class="hide"></button>
            <input type="hidden" name="p" value="0">
            <div class="sub-search-button-wrapper">
                <?php
                    $categories = array("Поиск", "Форумы", "Видео");

                    foreach ($categories as $category)
                    {
                        $category_index = array_search($category, $categories);

                        if (($config->disable_bittorent_search && $category_index == 3) ||
                            ($config->disable_hidden_service_search && $category_index ==4))
                        {
                            continue;
                        }

                        echo "<a " . (($category_index == $type) ? "class=\"active\"" : "") . "href=\"/search.php?q=" . $query . "&p=0&t=" . $category_index . "\"><img src=\"static/images/" . $category . "_result.png\" alt=\"" . $category . " result\" />" . ucfirst($category)  . "</a>";
                    }
                ?>
            </div>
        <hr>
        </form>

        <?php

            $page = isset($_REQUEST["p"]) ? (int) $_REQUEST["p"] : 0;

            $start_time = microtime(true);
            switch ($type)
            {
                case 0:
                    require "engines/text/merge.php";
                    $results = get_merged_torrent_results($query_encoded, $page);
                    print_elapsed_time($start_time);
                    print_merged_torrent_results($results);
                    break;
                
                case 1:
                    require "engines/crowdview/forums.php";
                    $results = get_text_results($query_encoded, $page);
                    print_elapsed_time($start_time);
                    print_text_results($results);
                    break;

                case 2:
                    require "engines/brave/video.php";
                    $results = get_video_results($query_encoded);
                    print_elapsed_time($start_time);
                    print_video_results($results);
                    break;

                case 3:
                    if ($config->disable_bittorent_search)
                        echo "<p class=\"text-result-container\">The host disabled this feature! :C</p>";
                    else
                    {
                        require "engines/bittorrent/merge.php";
                        $results = get_merged_torrent_results($query_encoded);
                        print_elapsed_time($start_time);
                        print_merged_torrent_results($results);
                    }
                    break;

                case 4:
                    if ($config->disable_hidden_service_search)
                        echo "<p class=\"text-result-container\">The host disabled this feature! :C</p>";
                    else
                    {
                        require "engines/ahmia/hidden_service.php";
                        $results = get_hidden_service_results($query_encoded);
                        print_elapsed_time($start_time);
                        print_hidden_service_results($results);
                    }
                    break;

                case 5:
                    
                        

                    $query_parts = explode(" ", $query);
                    $last_word_query = end($query_parts);
                    if (substr($query, 0, 1) == "!" || substr($last_word_query, 0, 1) == "!")
                        check_ddg_bang($query);
                    isset($_COOKIE["google_language"]) ? $cookie_engine = $_COOKIE["engines"] : $cookie_engine = "google";
                    isset($_COOKIE["engines"]) ? $cookie_engine = $_COOKIE["engines"] : $cookie_engine = "google";
                    if($cookie_engine == "google"){
                        require "engines/google_mobile/text.php";
                    } else {
                        require "engines/yandex/text.php";
                    }
                    $results = get_text_results($query, $page);
                    print_elapsed_time($start_time);
                    print_text_results($results);
                    break;
                    
                    break;
                
                case 6:
                    
                        require "engines/text/test.php";
                        $results = get_merged_torrent_results($query_encoded, $page);
                        print_elapsed_time($start_time);
                        print_merged_torrent_results($results);
                    
                    break;

                default:
                    isset($_COOKIE["google_language"]) ? $cookie_engine = $_COOKIE["engines"] : $cookie_engine = "google";
                    isset($_COOKIE["engines"]) ? $cookie_engine = $_COOKIE["engines"] : $cookie_engine = "google";
                    if($cookie_engine == "google"){
                        require "engines/google_mobile/text.php";
                    } else {
                        require "engines/yandex/text.php";
                    }
                    $results = get_text_results($query_encoded, $page);
                    print_text_results($results);
                    print_elapsed_time($start_time);
                    break;
            }


            if (2 > $type)
            {
                echo "<div class=\"next-page-button-wrapper\">";

                    if ($page != 0)
                    {
                        print_next_page_button("&lt;&lt;", 0, $query, $type);
                        print_next_page_button("&lt;", $page - 10, $query, $type);
                    }

                    for ($i=$page / 10; $page / 10 + 10 > $i; $i++)
                        print_next_page_button($i + 1, $i * 10, $query, $type);

                    print_next_page_button("&gt;", $page + 10, $query, $type);

                echo "</div>";
            }
        ?>

<?php require "misc/footer.php"; ?>
