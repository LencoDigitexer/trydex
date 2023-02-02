<?php
    return (object) array(

        "edit_settings" => "1",

        // e.g.: fr -> https://google.fr/
        "google_domain" => "ru",

        // Google results will be in this language
        "google_language" => "ru",

        // Google API KEY
        "google_api_key" => "AIzaSyD5pOomDzG3HQFld-lo13It_8phxj_Y4Xc",
        "google_api_app" => "f1dd122f9a13648a6",

        "disable_bittorent_search" => false,
        "bittorent_trackers" => "&tr=http%3A%2F%2Fnyaa.tracker.wf%3A7777%2Fannounce&tr=udp%3A%2F%2Fopen.stealth.si%3A80%2Fannounce&tr=udp%3A%2F%2Ftracker.opentrackr.org%3A1337%2Fannounce&tr=udp%3A%2F%2Fexodus.desync.com%3A6969%2Fannounce&tr=udp%3A%2F%2Ftracker.torrent.eu.org%3A451%2Fannounce",

        "disable_hidden_service_search" => false,

        /*
            Preset privacy friendly frontends for users, these can be overwritten by users in settings
            e.g.: "invidious" => "https://yewtu.be",
        */
        "invidious" => "", // youtube
        "bibliogram" => "", // instagram
        "rimgo" => "", // imgur
        "scribe" => "", // medium
        "librarian" => "", // odysee
        "gothub" => "", // github
        "nitter" => "", // twitter
        "libreddit" => "", // reddit
        "proxitok" => "", // tiktok
        "wikiless" => "", // wikipedia
        "quetre" => "", // quora
        "libremdb" => "", // imdb,
        "breezewiki" => "", // fandom,
        "anonymousoverflow" => "", // stackoverflow

        /*
            To send requests trough a proxy uncomment CURLOPT_PROXY and CURLOPT_PROXYTYPE:

            CURLOPT_PROXYTYPE options:

                CURLPROXY_HTTP
                CURLPROXY_SOCKS4
                CURLPROXY_SOCKS4A
                CURLPROXY_SOCKS5
                CURLPROXY_SOCKS5_HOSTNAME

            !!! ONLY CHANGE THE OTHER OPTIONS IF YOU KNOW WHAT YOU ARE DOING !!!
        */
        "curl_settings" => array(
            // CURLOPT_PROXY => "ip:port",
            // CURLOPT_PROXYTYPE => CURLPROXY_HTTP,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_USERAGENT => "Mozilla/5.0 (X11; Linux x86_64; rv:91.0) Gecko/20100101 Firefox/91.0",
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_PROTOCOLS => CURLPROTO_HTTPS | CURLPROTO_HTTP,
            CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTPS | CURLPROTO_HTTP,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_TIMEOUT => 18,
            CURLOPT_VERBOSE => false
        )

    );
?>
