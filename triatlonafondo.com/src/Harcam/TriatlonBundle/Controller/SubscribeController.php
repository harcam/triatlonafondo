<?php

namespace NivaShs\SubsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use NivaShs\ModelBundle\Entity\Client;
use NivaShs\ModelBundle\Entity\CommLog;

use NivaShs\SubsBundle\Controller\ApiHelper;

class SubscribeController extends Controller
{

    public function subscribeAction(Request $request)
    {
        // Prepare Doctrine and its EntityManager
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        // Define this functions parameters
        $params = array('product', 'msisdn');

        ////// BEGIN: ApiHelper Functions //////
        // Initialize an ApiHelper instance
        $helper = new ApiHelper('subscribe', $request, $doctrine);

        // Validate the integrity of the request
        $error = $helper->validateRequest($params);
        if($error){
            // Do not log this kind of error..
            return $helper->generateErrorResponse($error);
        }

        // Validate the username & password
        $error = $helper->validateUser();
        if($error){
            // Do not log this kind of error..
            return $helper->generateErrorResponse($error);
        }

        $aggregator = $helper->loadAggregator();

        // Extract parameters
        $data = $helper->loadParameters();
        ////// END: ApiHelper functions //////

        // Check if the product exists
        $product = $doctrine->getRepository('NivaShsModelBundle:Product')
                    ->findOneBy(array('code' => $data['product']));
        if(!$product){
            // Error: Could not load product
            $log = $helper->createCommErrorLog('SU', 20, $aggregator, null, null);
            $em->persist($log); $em->flush();
            return $helper->generateErrorResponse(20);
        }

        // Check if the product belongs to the aggregator
        if( $product->getAggregatorId()->getAggregatorId() != $aggregator->getAggregatorId() ){
            // Error: Product does not belong to aggregator
            $log = $helper->createCommErrorLog('SU', 13, $aggregator, $product, null);
            $em->persist($log); $em->flush();
            return $helper->generateErrorResponse(13);
        }

        // Check if the number is not blacklisted
        $blacklist = $doctrine->getRepository("NivaShsModelBundle:Blacklist")
            ->findOneBy(array('msisdn' => $data['msisdn']));
        if($blacklist){
            if($blacklist->getType() == 'I'){
                // Error: client is not valid for the carrier
                $log = $helper->createCommErrorLog('SU', 31, $aggregator, $product, null);
                $log->setDetails($data['msisdn']);
                $em->persist($log); $em->flush();
                return $helper->generateErrorResponse(31);
            } elseif($blacklist->getType() == 'P'){
                // Error: permanent blacklist
                $log = $helper->createCommErrorLog('SU', 32, $aggregator, $product, null);
                $log->setDetails($data['msisdn']);
                $em->persist($log); $em->flush();
                return $helper->generateErrorResponse(32);
            }
        }

        // Check if the client exists
        $client = $doctrine->getRepository("NivaShsModelBundle:Client")
            ->findOneBy(array("productId" => $data['product'], "msisdn" => $data['msisdn']));

        // If not, initialize a new client
        if(!$client) {
            $client = new Client();
            $client->setProductId($product);
            $client->setMsisdn($data['msisdn']);
        }

        // Check if the client is already subscribed
        if($client->isSubscribed()){
            // Error: client is already subscribed
            $log = $helper->createCommErrorLog('SU', 30, $aggregator, $product, $client);
            $em->persist($log); $em->flush();
            return $helper->generateErrorResponse(30);
        }

        // ALL TESTS OK, PROCESS THE CLIENT
        // Add the client to the database
        $client->setStatus('T');

        $now = new \DateTime('now');
        $client->setTrialTime($now);

        // Prepare CommLog
        $log = new CommLog();
        $log->setType('SU');
        $log->setClientId($client);

        // Flush changes
        $em->persist($client);
        $em->persist($log);
        $em->flush();

        // Return success response
        $json = array('error' => '00', 'details' => 'Success');
        return new JsonResponse($json);
    }

    public function unsubscribeAction(Request $request)
    {
        // Prepare Doctrine and its EntityManager
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        // Define this functions parameters
        $params = array('product', 'msisdn');

        ////// BEGIN: ApiHelper Functions //////
        // Initialize an ApiHelper instance
        $helper = new ApiHelper('unsubscribe', $request, $doctrine);

        // Validate the integrity of the request
        $error = $helper->validateRequest($params);
        if($error){
            // Do not log this kind of error..
            return $helper->generateErrorResponse($error);
        }

        // Validate the username & password
        $error = $helper->validateUser();
        if($error){
            // Do not log this kind of error..
            return $helper->generateErrorResponse($error);
        }

        $aggregator = $helper->loadAggregator();

        // Extract parameters
        $data = $helper->loadParameters();
        ////// END: ApiHelper functions //////

        // Check if the product exists
        $product = $doctrine->getRepository('NivaShsModelBundle:Product')
            ->findOneBy(array('code' => $data['product']));
        if(!$product){
            // Error: Could not load product
            $log = $helper->createCommErrorLog('UN', 20, $aggregator, null, null);
            $em->persist($log); $em->flush();
            return $helper->generateErrorResponse(20);
        }

        // Check if the product belongs to the aggregator
        if( $product->getAggregatorId()->getAggregatorId() != $aggregator->getAggregatorId() ){
            // Error: Product does not belong to aggregator
            $log = $helper->createCommErrorLog('UN', 13, $aggregator, $product, null);
            $em->persist($log); $em->flush();
            return $helper->generateErrorResponse(13);
        }

        // Check if the client exists
        $client = $doctrine->getRepository("NivaShsModelBundle:Client")
            ->findOneBy(array("productId" => $data['product'], "msisdn" => $data['msisdn']));

        // If not, initialize a new client
        if(!$client) {
            // Error: client is not subscribed to the product
            $log = $helper->createCommErrorLog('UN', 33, $aggregator, $product, null);
            $log->setDetails($data['msisdn']);
            $em->persist($log); $em->flush();
            return $helper->generateErrorResponse(33);
        }

        // Check if the client is already subscribed
        if($client->getStatus() == 'C'){
            // Error: client has already cancelled
            $log = $helper->createCommErrorLog('UN', 34, $aggregator, $product, $client);
            $log->setDetails($data['msisdn']);
            $em->persist($log); $em->flush();
            return $helper->generateErrorResponse(34);
        }

        // ALL TESTS OK, PROCESS THE CLIENT
        // Add the client to the database
        $client->setStatus('C');

        $now = new \DateTime('now');
        $client->setCancellationTime($now);

        // Prepare CommLog
        $log = new CommLog();
        $log->setType('UN');
        $log->setClientId($client);

        // Flush changes
        $em->persist($client);
        $em->persist($log);
        $em->flush();

        // Return success response
        $json = array('error' => '00', 'details' => 'Success');
        return new JsonResponse($json);

    }


}