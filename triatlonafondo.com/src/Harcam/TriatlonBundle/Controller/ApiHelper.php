<?php

namespace NivaShs\SubsBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use NivaShs\ModelBundle\Entity\CommErrorLog;
use NivaShs\ModelBundle\Entity\Aggregator;
use NivaShs\ModelBundle\Entity\Product;
use NivaShs\ModelBundle\Entity\Client;

class ApiHelper
{
    private $functionName;
    private $request;
    private $doctrine;
    private $aggregator;
    private $data;

    // TODO: Validate $doctrine object with TypeHinting
    public function __construct($functionName, Request $request, $doctrine)
    {
        $this->functionName = $functionName;
        $this->request = $request;
        $this->doctrine = $doctrine;
    }

    /**
     * Attempts to parse a Request
     * @param $params array
     * @return int
     */
    public function validateRequest(array $params)
    {
        // Check request type
        if( $this->request->getMethod() != 'POST' ){
            // Error: Invalid HTTP method
            return 1;
        }

        // Check request content-type
        if( $this->request->headers->get('content-type') != 'application/json' ){
            // Error: Invalid request content-type
            return 2;
        }

        // Check request body
        if( $this->request->getContent() == '' ){
            // Error: Malformed request, empty request body
            return 3;
        }

        ## VALIDATE JSON REQUEST
        // We simply use json_decode to parse the content of the request and
        //    then replace the request data on the $request object.
        //    This is useful if we ever decide to deprecate JSON in favor of
        //    other request method, for example HTTP POST.
        $this->data = json_decode($this->request->getContent(), true);

        if( !is_array($this->data) ){
            // Error: Bad JSON package
            return 4;
        }

        // Check for JSON structure
        if(!array_key_exists('security', $this->data)
            || !array_key_exists($this->functionName, $this->data)){
            // Error: missing parameters
            return 5;
        }

        // Check for security parameters
        if(!array_key_exists('username', $this->data['security'])
            || !array_key_exists('password', $this->data['security'])){
            // Error: missing parameters
            return 5;
        }

        // Check for parameters in the function's name
        $missing = false;
        foreach($params as $key)
        {
            if(!array_key_exists($key, $this->data[$this->functionName])){
                $missing = true;
                break;
            }
        }
        if($missing){
            // Error: missing parameters
            return 5;
        }

        // No errors found
        return 0;
    }

    public function validateUser()
    {
        // Try to load the aggregator
        $this->aggregator = $this->doctrine->getRepository('NivaShsModelBundle:Aggregator')
                            ->findOneBy(array('username' => $this->data['security']['username']));

        if(!$this->aggregator){
            // Error: aggregator does not exist
            return 10;
        }

        // Check if we'll use hashes, for now, don't check shit.
        //hash('sha512', $this->data[$this->functionName]['password']);
        if($this->data['security']['password'] != $this->aggregator->getPassword()){
            // Error: wrong password
            return 11;
        }

        // No errors found
        return 0;
    }

    public function loadAggregator()
    {
        return $this->aggregator;
    }

    public function loadParameters()
    {
        return $this->data[$this->functionName];
    }

    public function createCommErrorLog($type, $error, Aggregator $aggregator=null, Product $product=null, Client $client=null)
    {
        $log = new CommErrorLog();
        $log->setType($type);
        $log->setError($error);
        $log->setAggregatorId($aggregator);
        $log->setProductId($product);
        $log->setClientId($client);

        return $log;
    }

    /**
     * Generates a valid JSON package containing the error status
     *   and details
     * @param $error
     * @return JsonResponse
     */
    public function generateErrorResponse($error)
    {
        switch($error)
        {
            case 1:
                $json = array('error' => '01',
                    'details' => 'Malformed JSON payload');
                break;
            case 2:
                $json = array('error' => '02',
                    'details' => 'Missing parameter(s) in request');
                break;
            case 3:
                $json = array('error' => '03',
                    'details' => 'Device code does not exist');
                break;
            case 4:
                $json = array('error' => '04',
                    'details' => 'Invalid device API key');
                break;
            case 5:
                $json = array('error' => '05',
                    'details' => 'API access has been disabled for device');
                break;
            case 6:
                $json = array('error' => '06',
                    'details' => 'IMEI does not exist');
                break;
            case 7:
                $json = array('error' => '07',
                    'details' => 'Invalid export mode selected in configuration');
                break;
            case 8:
                $json = array('error' => '06',
                    'details' => 'Misconfigured device region');
                break;
            case 20:
                $json = array('error' => '20',
                    'details' => 'There are no more records to export');
                break;
            default:
                $json = array('error' => '99',
                    'details' => 'Unknown error occurred');
                break;
        }

        return new JsonResponse($json);
    }
}
