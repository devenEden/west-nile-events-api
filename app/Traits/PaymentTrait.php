<?php

namespace App\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

trait PaymentTrait
{
    //

    public function getApiConfig()
    {

        if (config('app.env') == 'Production') {
            return [
                'api_url' => config('payments.payments_api_url'),
                'token' => 'Bearer ' . config('payments.payments_api_key')
            ];
        }

        return [
            'api_url' => config('payments.payments_sandbox_api_url'),
            'token' => 'Bearer ' . config('payments.payments_sandbox_api_key')
        ];
    }


    public function initiatePaymentCollection(String $phone, Int $amount, $ticketDetails)
    {

        $transactionReference = 'PAY-REF-' . date('Y-m-d') . '-' . strtoupper(bin2hex(random_bytes(3))) . '-' . $ticketDetails['ticket_purchase_id'];

        $payload = [
            "msisdn" => $phone,
            "amount" => $amount,
            "external_reference" => $transactionReference,
            "narration" => $ticketDetails['event_name'] . ' ' . "Ticket Payment",
            "charge_customer" => true
        ];

        $paymentConfig = $this->getApiConfig();

        $apiUrl = $paymentConfig['api_url'] . '/collections';

        return $this->fetchData($apiUrl, 'POST', $payload, ['Authorization' => $paymentConfig['token']]);
    }

    public function fetchData($end_point = '', $method = null, $json = null, $extra_headers = null)
    {

        if (isset($method)) {
            $url = $end_point;
            $client = new Client(['verify' => false]);
            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ];
            $headers = array_merge($headers, $extra_headers ?? []);
            $options = ['headers' => $headers, 'time'];
            $options['json'] = $json ?? "";
            $options['allow_redirects'] = [
                'max' => 10,        // allow at most 10 redirects.
                'strict' => true,      // use "strict" RFC compliant redirects.
                'referer' => true,      // add a Referer header
                'protocols' => ['https', 'http'], // only allow https URLs
                'track_redirects' => true
            ];
            try {
                $response = $client->request($method, $url, $options);

                if ($response->getStatusCode() == 202 || $response->getStatusCode() == 201 || $response->getStatusCode() == 200) {
                    return array(
                        'status' => 'success',
                        'statusCode' => 200,
                        'payload' => $json,
                        'message' => 'Request successful',
                        'data' => json_decode($response->getBody(), true),
                    );
                } else {
                    return array(
                        'status' => 'error',
                        'statusCode' => $response->getStatusCode(),
                        'message' => 'Error ' . $response->getStatusCode() . ': ' . $response->getReasonPhrase() //Server Error on the service'
                    );
                }
            } catch (RequestException $e) {
                report($e);
                if ($e->hasResponse()) {
                    return array(
                        'status' => 'error',
                        'statusCode' => $e->getResponse()->getStatusCode(),
                        'message' => 'Error ' . $e->getResponse()->getStatusCode() . ': ' . $e->getResponse()->getReasonPhrase() //Server Error on the  service'
                    );
                }
            } catch (\Exception $e) {
                report($e);
                // To catch exactly error 400 use
                return array(
                    'status' => 'error',
                    'statusCode' => $e->getCode(),
                    'message' => 'Error ' . $e->getCode() . ': ' . $e->getMessage(),
                );
            } catch (GuzzleException $e) {
                report($e);
                return array(
                    'status' => 'error',
                    'statusCode' => $e->getCode(),
                    'message' => 'Error ' . $e->getCode() . ': ' . $e->getMessage(),
                );
            }
        } else {
            return array(
                'status' => 'error',
                'statusCode' => 405,
                'message' => 'Bad Method',
            );
        }
    }
}


// U0339