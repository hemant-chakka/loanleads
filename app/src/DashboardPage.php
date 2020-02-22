<?php
use SilverStripe\Security\Security;
use SilverStripe\Control\Director;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\ExecutePayment;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

class DashboardPage extends Page
{
    //Get the leads purchased by the member
    public function LeadsPurchased(){
        $member = Security::getCurrentUser();
        return $member->LeadsPurchased()->sort('Created','DESC');
        
    }
    
}


class DashboardPageController extends PageController
{
    
    private static $allowed_actions = [
        'paypalCreatePayment',
        'paypalExecutePayment',
        'purchaseLead',
        'rateLead',
        'invoice',
        'pdf'
    ];
    
    private static $url_handlers = [
        //'lead/$ID' => 'test'
    ];
    
    public function init(){
        parent::init();
    }
    //Create a paypal payment
    public function paypalCreatePayment(){
        
        if(!$_REQUEST['Amount']){
            $this->setMessage('warning', "Invalid amount!");
            return $this->redirectBack();
        }
        
        $member = Security::getCurrentUser();
        $payment = new \Payment();
        $payment->Amount = $_REQUEST['Amount'];
        $payment->Gateway = 'Paypal';
        $payment->Status = 'Pending';
        $payment->MemberID = $member->ID;
        $payment->write();
        
        $apiContext = $this->getPaypalApiContext();
        
        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod('paypal');
        
        
        $usd = $this->convertCurrency($_REQUEST['Amount']);
        if(!$usd){
            $this->setMessage('warning', "Could not process, please try again!");
            return $this->redirect('/dashboard');
        }
        
        $amount = new \PayPal\Api\Amount();
        $amount->setTotal($usd);
        $amount->setCurrency('USD');
        
        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount);
        
        $redirectUrls = new \PayPal\Api\RedirectUrls();
        $redirectUrls->setReturnUrl(Director::absoluteBaseURL()."dashboard/paypalExecutePayment?pid=".$payment->ID)
        ->setCancelUrl(Director::absoluteBaseURL()."dashboard?status=cancel");
        
        $payment = new \PayPal\Api\Payment();
        $payment->setIntent('sale')
        ->setPayer($payer)
        ->setTransactions(array($transaction))
        ->setRedirectUrls($redirectUrls);
        
        // After Step 3
        try {
            $payment->create($apiContext);
            //echo $payment;
            return $this->redirect($payment->getApprovalLink());
            //echo "\n\nRedirect user to approval_url: " . $payment->getApprovalLink() . "\n";
        }
        catch (\PayPal\Exception\PayPalConnectionException $ex) {
            // This will print the detailed information on the exception.
            //REALLY HELPFUL FOR DEBUGGING
            //echo $ex->getData();
        }
        $this->setMessage('warning', "Could not process, please try again!");
        return $this->redirect('/dashboard');
    }
    //Convert ZAR to USD
    public function convertCurrency($amount){
        // Fetching JSON
        $req_url = 'https://api.exchangerate-api.com/v4/latest/ZAR';
        $response_json = file_get_contents($req_url);
        
        // Continuing if we got a result
        if(false !== $response_json) {
            // Try/catch for json_decode operation
            try {
                // Decoding
                $response_object = json_decode($response_json);
                // YOUR APPLICATION CODE HERE, e.g.
                $base_price = $amount; // Your price in USD
                $usd = round(($base_price * $response_object->rates->USD), 2);
                return $usd;
            }
            catch(Exception $e) {
                // Handle JSON parse error...
                //echo $e->getMessage();
            }
        }
        return false;
    }
    //Excecute a paypal payment
    public function paypalExecutePayment(){
        $apiContext = $this->getPaypalApiContext();
        $member = Security::getCurrentUser();
        
        // Get payment object by passing paymentId
        $paymentId = $_GET['paymentId'];
        $payment = Payment::get($paymentId, $apiContext);
        $payerId = $_GET['PayerID'];
        
        $sitePayId = $_GET['pid'];
        
        // Execute payment with payer ID
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);
        
        try {
            // Execute payment
            $result = $payment->execute($execution, $apiContext);
            $sitePayment = \Payment::get()->byID($sitePayId);
            $sitePayment->Status = 'Paid';
            $sitePayment->Reference = $paymentId;
            $sitePayment->write();
            
            $member->Balance += $sitePayment->Amount;
            $member->write();
            $this->setMessage('success', "Money loaded successfully.");
            return $this->redirect('/dashboard');
            //$am = $result->transactions[0];
            //var_dump($am->amount);
            //die();
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
        } catch (Exception $ex) {
            die($ex);
        }
    }
    //Buyer purchase lead
    public function purchaseLead(){
        $leadId = $this->request->param('ID');
        
        if(isset($leadId)){
            $lead = LoanLead::get()->byID($leadId);
            $member = Security::getCurrentUser();
            
            if($lead->NewSalePrice() > $member->Balance){
                $this->setMessage('warning', "You dont have enough balance in your account, please load money!");
                return $this->redirect('/dashboard');
            }
            
            if($lead->SellStage >= 3){
                $this->setMessage('warning', "Lead expired!");
                return $this->redirect('/dashboard');
            }
            
            $leadPurchased = LeadPurchased::get()->filter(array(
                'MemberID' => $member->ID,
                'LoanLeadID' => $leadId
            ))->first();
            
            if($leadPurchased){
                $this->setMessage('warning', "You have purchased this lead already!");
                return $this->redirect('/dashboard');
            }
            
            $leadPurchased = new LeadPurchased();
            $leadPurchased->Price = $lead->NewSalePrice();
            $leadPurchased->MemberID = $member->ID;
            $leadPurchased->LoanLeadID = $leadId;
            $leadPurchased->write();
            
            $member->Balance = $member->Balance - $lead->NewSalePrice();
            $member->write();
            
            $lead->SellStage += 1;
            $lead->write();
            
            $this->setMessage('success', "Lead purchased successfully.");
            return $this->redirect('/dashboard');
        }
        $this->setMessage('warning', "Could not procees purchase,try again later.");
        return $this->redirect('/dashboard');
    }
    //Buyer rate lead- called using ajax    
    public function rateLead(){
        $leadId = $this->request->param('ID');
        $rating = $this->request->getVar('rating');
        if(isset($leadId) && isset($rating) ){
            $lead = LoanLead::get()->byID($leadId);
            $member = Security::getCurrentUser();
            
            $leadRating = LeadRating::get()->filter(array(
                'MemberID' => $member->ID,
                'LoanLeadID' => $leadId
            ))->first();
            if($leadRating){
                echo 'rated';
                die();
            }
                
            $leadRating = new LeadRating();
            $leadRating->Rating = $rating;
            $leadRating->MemberID = $member->ID;
            $leadRating->LoanLeadID = $leadId;
            $leadRating->write();
            echo "success";
            die();
        }
        echo 'fail';
        die();
    }
    // View invoice
    public function invoice(){
        $leadPurchase = LeadPurchased::get()->byID($this->request->param('ID'));
        
        $data = array(
            'LeadPurchase' => $leadPurchase
        );
        
        return $this->renderWith(array('DashboardPage_invoice','Page'),$data);
    }
    
    public function pdf(){
        $leadPurchase = LeadPurchased::get()->byID($this->request->param('ID'));
        $dompdf = $this->dompdf();
        $invoiceHTML = $this->renderWith('InvoiceHTML',array('LeadPurchase' => $leadPurchase));
        
        $dompdf->loadHtml($invoiceHTML);
        
        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');
        
        // Render the HTML as PDF
        $dompdf->render();
        
        // Output the generated PDF to Browser
        //return $dompdf->stream();
        
        $dompdf->stream("invoice.pdf");
        
        exit(0);
    }
}