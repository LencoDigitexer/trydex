<?php
    function tor_result($response)
    {
            $formatted_response = "Похоже, вы не используете Tor";
            if (strpos($response, $_SERVER["REMOTE_ADDR"]) !== false)
            {
                $formatted_response = "Похоже, вы используете Tor";
            }

            $source = "https://check.torproject.org";
            return array(
                "special_response" => array(
                    "response" => $formatted_response,
                    "source" => $source
                )
            );
    }
?>
