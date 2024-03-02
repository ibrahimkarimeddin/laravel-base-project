<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Twilio\Rest\Client;

/**
 * Class SmsSService
 * @package App\Services
 */
class SmsService
{
  public static function validate_user_using_message($phone, $code)
  {
        $xml = '<soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
        xmlns:xsd="http://www.w3.org/2001/XMLSchema" 
        xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
        xmlns:sos="http://www.openmindnetworks.com/SoS">
        <soapenv:Header/>
        <soapenv:Body>
        <sos:SubmitSM soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
        <smRequest>
        <!--Optional:-->
        <source>
        <ton>5</ton>
        <npi>1</npi>
        <addr>Rayan Taxi</addr>
        </source>
        <destination>
        <ton>1</ton>
        <npi>1</npi>
        <addr>'.$phone.'</addr>
        </destination>
        <shortMessage>
        <stringData>Your Code is '.$code.'</stringData>
        </shortMessage>
        <registeredDelivery>1</registeredDelivery>
        </smRequest>
        </sos:SubmitSM>
        </soapenv:Body>
        </soapenv:Envelope>';
        $client = new Client([
            'headers' => [
                'Authorization' => 'Basic '. base64_encode('rayantaxi:w%H&42T7')
            ]
        ]);
        $response = $client->request('POST', '208.87.171.50:8065/rayantaxi', [
            'headers' => [
                'Content-Type' => 'application/xml'

            ],
            
            'body' => $xml                         
        ]);
       return  $response->getStatusCode();

        return $data = $response->getBody()->getContents();
  }

  public static function validate_user_using_twilio($country_code,$phone, $code)
  {
    try {
      $phone_with_plus = "+".$country_code.$phone;
      $TWILIO_PHONE = env('TWILIO_PHONE_NUMBER');
      $client = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

      $message = $client->messages->create(
          $phone_with_plus,
          [
              'from' => env('TWILIO_PHONE_NUMBER'),
              'body' => 'Your Verfication Code For Rayan Taxi App is: ' . $code,
          ]
      );
      return $message;
    } catch (Exception $e) {
      return $e->error_log();
    }
  }
}
