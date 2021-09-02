<?php

class settings
{

    static function sendCurl($url, $vars = "", $post = 0, $header = 0)
    {
        $user_agent = "Yandex/1.01.001 (compatible; Win16; I)";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        if ($post == 1) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        }
        curl_setopt($ch, CURLOPT_HEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }

    /**
     * Return array associated by value from param $key
     * @param $array array
     * @param $key
     * @return array
     */
    static function get_array_by_key($array, $key)
    {
        $result = array();
        if (!empty($array)) {
            foreach ($array as $value) {
                if (isset($value[$key])) {
                    $result[$value[$key]] = $value;
                } else {
                    return $array;
                }
            }
            return $result;
        }
        return $array;
    }

    static function divNumbers($a, $b, $round = 0)
    {
        if ($b) {
            if ($round) {
                return round($a / $b, $round);
            }
            return round($a / $b);
        }
        return 0;
    }

    static function array_to_xml($data, &$xml_data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (is_numeric($key)) {
                    $key = 'item';
                }
                $subnode = $xml_data->addChild($key);
                self::array_to_xml($value, $subnode);
            } else {
                $xml_data->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }

    static function objectsIntoArray($arrObjData, $arrSkipIndices = [])
    {
        $arrData = [];
        if (is_object($arrObjData)) {
            $arrObjData = get_object_vars($arrObjData);
        }
        if (is_array($arrObjData)) {
            foreach ($arrObjData as $index => $value) {
                if (is_object($value) || is_array($value)) {
                    $value = self::objectsIntoArray($value, $arrSkipIndices);
                }
                if (in_array($index, $arrSkipIndices)) {
                    continue;
                }
                $arrData[$index] = $value;
            }
        }
        return $arrData;
    }

    static function varbinaryToIp($ip)
    {
        $ip = inet_ntop($ip);
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $ip = explode('::ffff:', $ip);
            if (!$ip[0] && substr_count($ip[1], ".") == 3) {
                $ip = $ip[1];
            }
        }
        return $ip;
    }

    /**
     * @param string $postbackLink {lead_id}{status_lead}{utm_source}{utm_medium}{utm_campaign}{utm_content}{utm_term}{money}{currency}
     * @param array $lead
     */
    static function setPostBackLink($postbackLink = '', $lead = []): string
    {
        if ($postbackLink) {
            $postbackLink = str_replace(
                ['{lead_id}','{status_lead}','{utm_source}','{utm_medium}','{utm_campaign}','{utm_content}','{utm_term}','{money}','{currency}'],
                [$lead['unique_id'], $lead['status'], $lead['stream']['unique_id'], $lead['utm_medium'], $lead['utm_campaign'], $lead['utm_content'], $lead['utm_term']],
                $postbackLink
            );
        }
        return $postbackLink;
    }

}