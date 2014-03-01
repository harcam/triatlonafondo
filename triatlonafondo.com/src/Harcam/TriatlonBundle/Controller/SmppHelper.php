<?php

namespace NivaShs\SubsBundle\Controller;

use NivaShs\ModelBundle\Entity\ShortCode;

class SmppHelper
{
    protected $shortcode;

    protected $request;
    protected $response;

    public function __construct(ShortCode $shortcode)
    {
        $this->shortcode = $shortcode;
    }

    public function sendSms($msisdn, $content)
    {
        // TODO: Check message length

        // Prepare data
        $data = array('sender' => $this->shortcode->getMsisdn(),
                      'recipient' => $msisdn,
                      'content' => $content);

        // Build JSON
        $json = json_encode($data);

        // Execute JSON
        return $this->executeJson($this->shortcode->getMtEndpoint(), $json);
    }

    /**
     * Execute a JSON HTTP POST request to the specified endpoint
     * @param $endpoint
     * @param $json
     * @return boolean
     */
    private function executeJson($endpoint, $json)
    {
        ## EXECUTE (No SSL for this request)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        //curl_setopt($ch, CURLOPT_VERBOSE, true); // Debug.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $this->response = curl_exec($ch);

        if ($this->response === false) {
            # Only use for serious debugging..
            # throw new RuntimeException("cURL exception: ".curl_errno($ch).": ".curl_error($ch));
            return false;
        }

        // Check for HTTP Code before closing the connection
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Close connection
        curl_close($ch);

        // Verify for a 200 OK status code.
        if ($httpCode == 200) {

            return $this->isResponseOk($this->response);

        } else {
            return false;
        }
    }

    /**
     * Parse the JSON response to extract the error code,
     *   if it is 0 then the request was successful.
     * @param $response
     * @return bool
     */
    private function isResponseOk($response)
    {
        // Parse the response
        $data = json_decode($response, true);

        if( !is_array($data) ){
            // Error: is not valid JSON
            return false;
        }

        // Check for response parameter
        if( !array_key_exists('error', $data) ){
            // Error: missing parameters
            return false;
        }

        $error = $data['error'];

        if( !is_integer($error) ){
            // Error: error code is not integer
            return false;
        }

        // The only "success" error code is 0
        if( intval($error) > 0 ){
            return false;
        } else {
            return true;
        }

    }

}