<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use glorifiedking\BusTravel\Bus;
use glorifiedking\BusTravel\Operator;
use glorifiedking\BusTravel\Station;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

class ApiController extends Controller
{
    public function __construct()
    {
        //$this->middleware('web');
        //$this->middleware('auth');
    }

    public function show_debit_test_form()
    {
        return view('bustravel::backend.api_code.test_form');
    }

    public function send_debit_request(Request $request)
    {
        $to = $request->to;
        $from = $request->from;
        $amount = $request->amount;
        $api_username = config('bustravel.payment_gateways.mtn_rw.username');
        $api_password = config('bustravel.payment_gateways.mtn_rw.password');
        $base_api_url = config('bustravel.payment_gateways.mtn_rw.url');
        if(!isset($to) || !isset($from) || !isset($amount))
        {
            return response()->json([
                'error' => 'Invalid Data',
                'message' => 'All fields are required'
            ]);
        }
        
        $ref_id = rand();
        $external_transaction_id = uniqid();
        //send request to mtn url 
        $xml_body = "<?xml version='1.0' encoding='UTF-8' standalone='yes'?>
            <ns2:debitrequest xmlns:ns2='http://www.ericsson.com/em/emm'>
            <fromfri>FRI:".$from."/MSISDN</fromfri>
            <tofri>FRI:".$to."@".$api_username."/SP</tofri>
            <amount>
            <amount>".$amount."</amount>
            <currency>FRW</currency>
            </amount>
            <externaltransactionid>".$external_transaction_id."</externaltransactionid>
            <tomessage>".$from."</tomessage>
            <referenceid>".$ref_id."</referenceid>
            </ns2:debitrequest>";
        $request_uri = $base_api_url."/mot/mm/debit";
       /* $client = new \GuzzleHttp\Client(['verify' => false]);
        $response = $client->request('POST', $request_uri, [
                    ['cert' => ['/home/sslcertificates/197_243_14_94.crt']],
                    'headers' => [
                        'Content-Type' => 'text/xml'
                    ],
                    'body'   => $xml_body
                    ]);
         dd($response);           
            */
        $request_headers = array();
        $auth_header = "Authorization: Basic " . base64_encode($api_username . ':' . $api_password);
        $request_headers[] = $auth_header;
       
                $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_URL, $request_uri);
        curl_setopt($ch, CURLOPT_SSLCERT ,  "/home/sslcertificates/197_243_14_94.crt" );
        curl_setopt($ch, CURLOPT_SSLKEY ,  "/home/sslcertificates/197_243_14_94.pem" );
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT,        15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST,           true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,    $xml_body);
        $result = curl_exec($ch);

        if (curl_errno($ch) > 0) {
            $result = array('errocurl' => curl_errno($ch), 'msgcurl' => curl_error($ch));
            echo curl_error($ch);
            // $result = false;
        }

        curl_close($ch);
       print_r($result); die();
    }

    //save successfull debits
    public function index()
    {
       return 'ok';
    }

    public function get_station_by_name($station_name)
    {
        $stations = Station::where([
            ['name','like',"$station_name%"]
        ])->take(8)->get()->pluck('name','id');
        return $stations;
    }

    public function ussd(Request $request)
    {
        $method = $request->request_method;

        if($method == 'GetStartStationsByName')
        {
            $station = $request->departure_station;
            //validate 
            if(!$station)
            {
                return response()->json([
                    'status' => 'invalid data',
                    'result' => 'departure_station missing'
                ]);
            }
            $result = $this->get_station_by_name($station);
            $status = $result->isEmpty() ? 'failed' : 'success';
            return response()->json([
                'status' => $status,
                'result' => $result
            ]);
            
        }
        else if($method == 'GetEndStationsByName')
        {
            $station = $request->destination_station;
            //validate 
            if(!$station)
            {
                return response()->json([
                    'status' => 'invalid data',
                    'result' => 'destination_station missing'
                ]);
            }
            $result = $this->get_station_by_name($station);
            $status = $result->isEmpty() ? 'failed' : 'success';
            return response()->json([
                'status' => $status,
                'result' => $result
            ]);
        }

        return response()->json([
            'status' => 'invalid data',
            'result' => 'unknown method or method not specified'
        ]);
    }

    
}
