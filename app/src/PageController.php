<?php

namespace {

    use SilverStripe\CMS\Controllers\ContentController;
    use SilverStripe\Security\Security;
    use SilverStripe\Security\Authenticator;
    use SilverStripe\Control\Email\Email;
    use SilverStripe\Control\Director;
    use SilverStripe\Control\Session;
    use SilverStripe\View\ArrayData;
    use SilverStripe\Core\Injector\Injector;
    use SilverStripe\Control\HTTPRequest;
use SilverStripe\View\Requirements;
use Dompdf\Dompdf;
                                                                                                                                                
    class PageController extends ContentController
    {
        /**
         * An array of actions that can be accessed via a request. Each array element should be an action name, and the
         * permissions or conditions required to allow the user to access it.
         *
         * <code>
         * [
         *     'action', // anyone can access this action
         *     'action' => true, // same as above
         *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
         *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
         * ];
         * </code>
         *
         * @var array
         */
        private static $allowed_actions = [
            'saveLead',
            'testLime'
        ];

        protected function init()
        {
            parent::init();
            
            if($this->URLSegment == 'register')
                Requirements::javascript("_resources/themes/leads/vendor/jquery/jquery.min.js");
            
            // You can include any CSS or JS required by your project here.
            // See: https://docs.silverstripe.org/en/developer_guides/templates/requirements/
        }
        //Save a lead - called by ajax
        public function saveLead(){
            $data = $this->request->postVars();
            //Save the lead
            $loanLead = new LoanLead();
            $loanLead->FirstName = $data['FIRST_NAME'];
            $loanLead->LastName = $data['LAST_NAME'];
            $loanLead->Email = $data['EMAIL'];
            $loanLead->SAIDNumber = $data['ID_NUMBER'];
            $loanLead->Mobile = $data['MOBILE'];
            //$loanLead->Suburb = $data['FIRST_NAME'];
            $loanLead->City = $data['CITY_LOCALITY'];
            $loanLead->Employment = $data['EMPLOYMENT'];
            $loanLead->EmploymentContact = $data['WORK_TEL'];
            $loanLead->BadCredit = $data['BAD_CREDIT'];
            $loanLead->SubmittedIPAddress = $this->getClientIp();
            $loanLead->ReferredFrom = $data['REFERRED_FROM'];
            $loanLead->Latitude = $data['LATITUDE'];
            $loanLead->Longitude = $data['LONGITUDE'];
            $loanLead->GoogleMapLocation = $this->getGoogleMapLoc($data['LATITUDE'], $data['LONGITUDE']);
            $loanLead->LoanType = $data['LOAN_TYPE'];
            $loanLead->LoanAmount = $data['LOAN_AMOUNT'];
            $loanLead->RepaymentPeriod = $data['LOAN_REPAYMENT'];
            $loanLead->Status = 'Moderation';
            $loanLead->SellStage = 0;
            $loanLead->write();
            //Notify admin
            $email = Email::create()
            ->setHTMLTemplate('Email\\NewLeadAdminEmail')
            ->setData([
                'LoanLead' => $loanLead
            ])
            ->setFrom('webmaster@fixyourweb.com')
            ->setTo('profitech.credit@gmail.com')
            ->setSubject('New lead submiited!');
            
            echo "lead submitted";
            if ($email->send())
                echo ",email sent";
            else
                echo ",email not sent";
            
            if($data['LOAN_AMOUNT'] <= 5400 && $data['BAD_CREDIT'] == 'No'){
                //Post the lead to Lime 24 server
                require Director::baseFolder().'/vendor/autoload.php';
                $client = new \GuzzleHttp\Client(['verify' => false]);
                $response = $client->request('POST', 'https://www.lime24.co.za/partner/createClient', [
                    //$response = $client->request('POST', 'https://www.lime24.co.za/partner/createClient', [
                    'json' => [
                        "firstName"  => $data['FIRST_NAME'],
                        "lastname"  => $data['LAST_NAME'],
                        "birthDate"  => $data['BIRTH_DATE'],
                        "idNumber"  => $data['ID_NUMBER'],
                        "mobilePhone"  => $data['MOBILE'],
                        "email"  => $data['EMAIL'],
                        "parameters"  => [ "token"  => "3fb5f2d883214da190617d72fe49aade", "lead_id"  => $loanLead->ID ]
                    ],
                ]);
                $body = $response->getBody();
                $bodyArr = json_decode($body,true);
                $today = new DateTime();
                $dt = $today->format('Y-m-d H:i:s');
                $body = "$dt $body";
                if($bodyArr['result'] == 'Accepted'){
                
                    file_put_contents(Director::baseFolder().'/public/assets/lime24logs/accepted.log', $body.PHP_EOL , FILE_APPEND | LOCK_EX);
                }
            
                if($bodyArr['result'] == 'Rejected'){
                
                    file_put_contents(Director::baseFolder().'/public/assets/lime24logs/rejected.log', $body.PHP_EOL , FILE_APPEND | LOCK_EX);
                
                }
                
                if($bodyArr['result'] == 'Error'){
                
                    file_put_contents(Director::baseFolder().'/public/assets/lime24logs/error.log', $body.PHP_EOL , FILE_APPEND | LOCK_EX);
                }
            }
            
            die();
        }
        // Function to get the client IP address
        public function getClientIp() {
            $ipaddress = '';
            if (getenv('HTTP_CLIENT_IP'))
                $ipaddress = getenv('HTTP_CLIENT_IP');
            elseif(getenv('HTTP_X_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
            elseif(getenv('HTTP_X_FORWARDED'))
                $ipaddress = getenv('HTTP_X_FORWARDED');
            elseif(getenv('HTTP_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_FORWARDED_FOR');
            elseif(getenv('HTTP_FORWARDED'))
                $ipaddress = getenv('HTTP_FORWARDED');
            elseif(getenv('REMOTE_ADDR'))
                $ipaddress = getenv('REMOTE_ADDR');
            else
                $ipaddress = 'UNKNOWN';
            return $ipaddress;
        }
        //Get google map location address
        public function getGoogleMapLoc($lat,$lng){
            $url = 'https://maps.googleapis.com/maps/api/geocode/json?key='.GOOGLE_MAPS_API_KEY.'&latlng='.trim($lat).','.trim($lng).'&sensor=false';
            $json = @file_get_contents($url);
            $data=json_decode($json);
            $status = $data->status;
            if($status == "OK")
                return $data->results[0]->formatted_address;
            return null;
        }
        //Get paypal api context
        public function getPaypalApiContext(){
            require Director::baseFolder()  . '/vendor/autoload.php';
            // After Step 1
            $apiContext = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    PAYPAL_CLIENT_ID,     // ClientID
                    PAYPAL_CLIENT_SECRET      // ClientSecret
                    )
                );
           /* if(!$params['testMode']){
                $apiContext->setConfig(
                    array(
                        'log.LogEnabled' => true,
                        'log.FileName' => 'PayPal.log',
                        'log.LogLevel' => 'FINE',
                        'mode' => 'live',
                    )
                    );
            }*/
            return $apiContext;
        }
        // Set a custom error message
        //Use these message types: success,info,warning,danger
        public function setMessage($type, $message){
            $request = Injector::inst()->get(HTTPRequest::class);
            $session = $request->getSession();
            $session->set('Message', array(
                'MessageType' => $type,
                'Message' => $message
            ));
        }
        // Get a custom error message
        public function getMessageV2(){
            $request = Injector::inst()->get(HTTPRequest::class);
            $session = $request->getSession();
            if($message = $session->get('Message')){
                $session->clear('Message');
                $array = new ArrayData($message);
                return $array->renderWith('MessageV2');
            }
        }
        //Get PHP constant for template
        public function PHPConstant($const){
            
            return constant($const);
            
        }
        //Format currency
        public function FormatCurrency($val){
            if(fmod($val,1) == 0)
                $val = floor($val);
                
            return "R$val";
        }
        //Get dompdf object
        public function dompdf(){
            require Director::baseFolder().'/vendor/autoload.php';
            // instantiate and use the dompdf class
            return new Dompdf();
            $dompdf->loadHtml('<h1>Hello Hemant Kumar!</h1>');
            
            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('A4', 'landscape');
            
            // Render the HTML as PDF
            $dompdf->render();
            
            // Output the generated PDF to Browser
            //return $dompdf->stream();
            
            $dompdf->stream("dompdf_out.pdf", array("Attachment" => false));
            
            exit(0);
        }
        
        public function testLime(){
            require Director::baseFolder().'/vendor/autoload.php';
            
            $client = new \GuzzleHttp\Client(['verify' => false]);
            
            //$client = new \GuzzleHttp\Client();
            
            $response = $client->request('POST', 'http://80.89.144.194:6500/partner/createClient', [
            //$response = $client->request('POST', 'https://www.lime24.co.za/partner/createClient', [
                'json' => [
                    "firstName"  => "Abigale",
                    "lastname"  => "Lelia",
                    "secondName"  => "Fadel",
                    //"birthDate"  => "1998-11-04",
                    "gender"  => "Male",
                    "race"  => "Black",
                    "idNumber"  => "9811048234346",
                    "birthPlace"  => "Cape Town",
                    "mobilePhone"  => "0945634114",
                    "email"  => "email@co.za",
                    "parameters"  => [ "token"  => "1QkfnsQPLcUftZVkdxxStnFgHMtqV1f3", "lead_id"  => "666","subsource"  => "Stephania Company" ]
                ],
                
            ]);
            
            $body = $response->getBody();
            
            $resArr = json_decode($body,true);
            
            echo "<pre>";
            var_dump($resArr);
            echo "</pre>";
            
            //echo $body;
            die();
            
        }
        
    }
}
