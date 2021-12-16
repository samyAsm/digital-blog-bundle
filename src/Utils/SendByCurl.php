<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dhi\BlogBundle\Utils;

/**
 * Description of SendByCurl
 *
 * @author Aubry Yvan
 */
class SendByCurl
{
    /**
     * @var string
     */
    private $endPoint;

    /**
     * @var string 
     */
    private $statusCode;

    /**
     * Constructor method
     *
     * @param string $endPoint
     *
     * @return void
     */
    public function __construct(string $endPoint)
    {
        $this->endPoint = $endPoint;
    }

    /**
     * Send CURL POST Request
     *
     * @param string $data data to send
     * @param string $format data's format to send
     * @param array $headers the HTTP Header
     * @param string $encoding data's format encoding
     *
     * @return string|boolean|null|array
     */
    public function sendPOST($data, string $format = '', array $headers =[], string $encoding = 'json')
    {
        return $this->send($data, 'POST', $format, $headers, $encoding);
    }

    /**
     * send CURL GET Request
     *
     * @param string $format data's format to send
     * @param array $headers the HTTP Header
     * @param string $encoding data's format encoding
     *
     * @return string|boolean|null|array
     */
    public function sendGET(string $format, array $headers = [], string $encoding = 'json' )
    {
        return $this->send(array(), 'GET', $format, $headers, $encoding);
    }

    /**
     * send the data by curl
     *
     * @param mixed $data data to send
     * @param string $method GET or POST
     * @param string $format data's format to send
     * @param array $headers the HTTP Header
     * @param string $encoding data's format encoding
     *
     * @return string|boolean|null
     */
    private function send($data, string $method, string $format, array $headers, string $encoding )
    {
        $curl = curl_init();

        switch ($format) {
            case 'json':
                $dataString = json_encode($data);
                $headers[]  = "Accept: application/json";
                $headers[]  = "Content-Type: application/json";
                $headers[]  = "Content-Length: ".strlen($dataString);
                break;

            case 'http_build':
                $dataString = http_build_query($data);
                $headers[]  = "Content-Type: application/x-www-form-urlencoded";
                break;

            default :
                $dataString = $data;
                $headers[]  = "cache-control: no-cache";
                $headers[]  = "Content-Type: application/x-www-form-urlencoded";
                break;
        }

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL            => $this->endPoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 13,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => $method,
                CURLOPT_POSTFIELDS     => $dataString,
                CURLOPT_HTTPHEADER     => $headers,
            )
        );

        $results = curl_exec($curl);
        $this->statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); 
        curl_close($curl);

        switch ($encoding) {
            case 'xml':
                $resultJson = json_decode(simplexml_load_string($results));

                return json_decode($resultJson, true);

            case 'json':
                return json_decode($results, true);

            default :
                return $results;
        }
    }

    /**
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
