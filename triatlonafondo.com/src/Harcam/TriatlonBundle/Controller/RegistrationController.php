<?php

namespace Harcam\TriatlonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Harcam\TriatlonBundle\Entity\Client;

class RegistrationController extends Controller
{
    public function exportAction(Request $request)
    {
        ## VALIDATE JSON REQUEST
        // We simply use json_decode to parse the content of the request and
        //    then replace the request data on the $request object.
        //    This is useful if we ever decide to deprecate JSON in favor of
        //    other request method, for example HTTP POST.
        $data = json_decode($request->getContent(), true);

        if( is_array($data) )
        {
            // We put it inside the $request object for better compatibility
            $request->request->replace($data);
        } else {
            // Error: Bad JSON package
            return $this->generateErrorResponse(1);
        }

        ## VALIDATE USER REQUEST
        $device = $this->validateUser($request);
        if( is_integer( $device ) )
        {
            // If 'device' is an integer, it's because the validation failed
            return $this->generateErrorResponse($device);
        }

        // Dump parameters
        $test = $request->request->has('test') ? $request->request->get('test') : false;

        ## PREPARE CLIENT LIST
        // Load data from parameters.yml
        $mode = $this->container->getParameter('api_export_mode');
        $clientsPerQuery = $this->container->getParameter('api_export_numbers_per_query');

        // Load all available clubs
        $clubs = $this->getDoctrine()->getRepository('NivaSharedBundle:Club')->findAll();

        // Check export mode
        if($mode == 'organic')
        {
            // Prepare query
            $repository = $this->getDoctrine()->getRepository('NivaSharedBundle:Client');
            $qb = $repository->createQueryBuilder('c')
                ->innerJoin('c.campaignId', 'cp', 'WITH', 'c.campaignId = cp.campaignId')
                ->innerJoin('cp.providerId', 'p', 'WITH', 'cp.providerId = p.providerId')
                ->where("c.status = 'PO'")
                ->andWhere("c.exported = 0") // c.exported IS NOT TRUE
                ->andWhere("c.campaignId IS NOT NULL OR c.campaignId <> ''")
                ->setMaxResults( $clientsPerQuery )
                ->orderBy('c.creationTime', 'DESC');

            // Add region constraints
            $region = $device->getDeviceRegionId();

            if( !$region->getAreaCodes() )
            {
                // Error: Misconfigured device region
                return $this->generateErrorResponse(8);
            }

            $filter = "";
            if( $region->getFilterType() == 'I' )
            {
                // Include Area Codes
                foreach( $region->getAreaCodes() as $a )
                {
                    $filter .= "c.phoneNumber LIKE '$a%' OR ";
                }
                $filter = rtrim($filter, " OR ");
            } else {
                // Exclude Area Codes
                foreach( $region->getAreaCodes() as $a )
                {
                    $filter .= "c.phoneNumber NOT LIKE '$a%' AND ";
                }
                $filter = rtrim($filter, " AND ");
            }

            $qb->andWhere( $filter );

            // Execute query
            $query = $qb->getQuery();
            $clientsRaw = $query->getResult();

            // Check if there were results (if not empty)
            if( empty($clientsRaw) )
            {
                // Error: No more records available to export
                return $this->generateErrorResponse(20);
            }

            // Flag exported clients
            if( !$test )
            {
                foreach($clientsRaw as $client)
                {
                    $client->setExported( true );

                    // Prevent the client from notifying
                    $client->setHasNotified( true );
                    $client->setHasSuppressed( true );
                }

                // Flush updates (will now flush at the end with the log)
                // $this->getDoctrine()->getEntityManager()->flush();
            }

            // Generate client array
            $clients = array();
            $i = 0;
            foreach($clientsRaw as $client)
            {
                $clients[$i]['club'] = $client->getClubId()->getClubId();
                $clients[$i]['phone'] = $client->getPhoneNumber();
                $clients[$i]['ip'] = $client->getClientIp();
                $clients[$i]['useragent'] = $client->getClientUserAgent();
                $i++;
            }


        } elseif($mode == 'forced')
        {
            $n = count($clubs);
            $clientsPerClub = round($clientsPerQuery/$n);

            $clients = array();
            $i = 0;

            // Prepare Region Constraints
            $region = $device->getDeviceRegionId();

            if( !$region->getAreaCodes() )
            {
                // Error: Misconfigured device region
                return $this->generateErrorResponse(8);
            }

            $filter = "";
            if( $region->getFilterType() == 'I' )
            {
                // Include Area Codes
                foreach( $region->getAreaCodes() as $a )
                {
                    $filter .= "c.phoneNumber LIKE '$a%' OR ";
                }
                $filter = rtrim($filter, " OR ");
            } else {
                // Exclude Area Codes
                foreach( $region->getAreaCodes() as $a )
                {
                    $filter .= "c.phoneNumber NOT LIKE '$a%' AND ";
                }
                $filter = rtrim($filter, " AND ");
            }

            // Prepare a Query for each club
            foreach($clubs as $club)
            {
                // Prepare query
                $repository = $this->getDoctrine()->getRepository('NivaSharedBundle:Client');
                $qb = $repository->createQueryBuilder('c')
                    ->innerJoin('c.campaignId', 'cp', 'WITH', 'c.campaignId = cp.campaignId')
                    ->innerJoin('cp.providerId', 'p', 'WITH', 'cp.providerId = p.providerId')
                    ->where("c.status = 'PO'")
                    ->andWhere("c.clubId = :clubId") // THIS LINE IS FOR 'FORCED' MODE
                    ->andWhere( $filter ) // THIS IS PRECOMPUTED IN FORCED MODE
                    ->andWhere("c.exported = 0") // c.exported IS NOT TRUE
                    ->andWhere("c.campaignId IS NOT NULL OR c.campaignId <> ''")
                    ->setMaxResults( $clientsPerClub )
                    ->orderBy('c.creationTime', 'DESC')
                    ->setParameter('clubId', $club->getClubId());

                // Execute query
                $query = $qb->getQuery();
                $clientsRaw = $query->getResult();

                // Flag exported clients
                if( !$test )
                {
                    foreach($clientsRaw as $client)
                    {
                        $client->setExported( true );

                        // Prevent the client from notifying
                        $client->setHasNotified( true );
                        $client->setHasSuppressed( true );
                    }

                    // Flush updates
                    // $this->getDoctrine()->getEntityManager()->flush();
                }

                // Generate client array
                foreach($clientsRaw as $client)
                {
                    $clients[$i]['club'] = $client->getClubId()->getClubId();
                    $clients[$i]['phone'] = $client->getPhoneNumber();
                    $clients[$i]['ip'] = $client->getClientIp();
                    $clients[$i]['useragent'] = $client->getClientUserAgent();
                    $i++;
                }

            }

            // Verify that some clients where exported
            if( empty($clients) )
            {
                // Error: No more records available to export
                return $this->generateErrorResponse(20);
            }


        } else {
            // Error, no valid export mode selected
            return $this->generateErrorResponse(7);
        }

        ## PREPARE CONFIG
        $config = array();
        foreach($clubs as $club)
        {
            $config[ $club->getClubId() ] = $this->buildPortaloneUrl( $club );
        }

        ## LOG THE REQUEST
        $log = new DeviceLog();
        $log->setDeviceId( $device );
        $log->setType('E'); // Type 'Export'
        $log->setIp( $request->getClientIp() );
        $log->setNumbers( count($clients) );

        if( $test )
        {
            $log->setDetails('Test flag enabled');
        }

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist( $log );
        $em->flush();

        ## PREPARE JSON RESPONSE
        $json = array('error' => '00',
            'details' => 'Success',
            'clients' => $clients,
            'config' => $config
        );

        // Return the JSON
        return new JsonResponse($json);
    }

    public function updateAction(Request $request)
    {
        ## VALIDATE JSON REQUEST
        // We simply use json_decode to parse the content of the request and
        //    then replace the request data on the $request object.
        //    This is useful if we ever decide to deprecate JSON in favor of
        //    other request method, for example HTTP POST.
        $data = json_decode($request->getContent(), true);

        if( is_array($data) )
        {
            // We put it inside the $request object for better compatibility
            $request->request->replace($data);
        } else {
            // Error: Bad JSON package
            return $this->generateErrorResponse(1);
        }

        ## VALIDATE USER REQUEST
        $device = $this->validateUser($request);
        if( is_integer( $device ) )
        {
            // If 'device' is an integer, it's because the validation failed
            return $this->generateErrorResponse($device);
        }

        ## GENERATE DEVICELOG
        // Check if the parameters are valid
        if( !$request->request->has('ip') ||
            !$request->request->has('ipSource') ||
            !$request->request->has('pos') ||
            !$request->request->has('posSource') ||
            !$request->request->has('numbers') ||
            !$request->request->has('version') )
        {
            // Error: Missing Parameters
            return $this->generateErrorResponse(2);
        }

        // Prepare Log
        $log = new DeviceLog();
        $log->setDeviceId( $device );
        $log->setType('U'); // Type 'Export'
        $log->setIp( $request->request->get('ip') );
        $log->setIpSource( $request->request->get('ipSource') );
        $log->setPosition( $request->request->get('pos') );
        $log->setPositionSource( $request->request->get('posSource') );
        $log->setNumbers( $request->request->get('numbers') );
        $log->setVersion( $request->request->get('version') );

        // Update device's last* information
        $device->setLastUpdate( new \Datetime("now") );
        if( $request->request->get('ipSource') == 1 ) $device->setLastUpdateMobile( true );
            else $device->setLastUpdateMobile( false );
        $device->setLastVersion( $request->request->get('version') );

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist( $log );
        $em->flush();

        ## PREPARE JSON RESPONSE
        $json = array('error' => '00',
            'details' => 'Success'
        );

        // Return the JSON
        return new JsonResponse($json);

    }


}