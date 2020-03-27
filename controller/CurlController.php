<?php

class CurlController
{
    static $last_http_code;

    /**
     * APIをリクエストする
     * @param $uri
     * @param $data
     * @param string $method
     * @param string $language
     * @return mixed
     */
    public static function request($key, $uri, $data = null, $method = "GET",$language = "ja")
    {

        $endpoint = $uri;
        $options = [
            CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_VERBOSE => false,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Accept: application/json",
                "Authorization: Bearer {$key}",
            ],
        ];

        switch($method){
            case 'PATCH':
            case 'DELETE':
                $options[CURLOPT_CUSTOMREQUEST] = $method;
                break;
            case 'POST':
                $options[CURLOPT_POST] = true;
                break;
            case 'GET':
            default:
                if($data){
                    $query = http_build_query($data);
                    $endpoint .= "?" . $query;
                }
                $options[CURLOPT_CUSTOMREQUEST] = $method;
                break;
        }

	    if($data && $method != 'GET'){
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
        }

	    // if (Config::MODE != "production") {
        //     $fp = fopen(Config::getLogBase() . 'legare_rest_curl.log', 'a');
        //     $options[CURLOPT_VERBOSE] = true;
        //     $options[CURLOPT_STDERR] = $fp;
        // }

	    $ch = curl_init($endpoint);
        curl_setopt_array($ch, $options);

        $result = curl_exec($ch);

        self::$last_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return json_decode($result);
    
    }

}