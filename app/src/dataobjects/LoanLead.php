<?php
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Security;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Group;
use SilverStripe\Control\Email\Email;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\ORM\FieldType\DBField;

class LoanLead extends DataObject
{
    private static $db = [
        'FirstName' => 'Varchar(255)',
        'LastName' => 'Varchar(255)',
				'NameVerified' => 'Boolean',
        'Email' => 'Varchar(255)',
				'EmailVerified' => 'Boolean',
        'SAIDNumber' => 'Varchar(255)',
				'IDVerified' => 'Boolean',
        'Mobile' => 'Varchar(100)',
				'MobileVerified' => 'Boolean',
        'Suburb' => 'Varchar(255)',
        'City' => 'Varchar(255)',
        'Employment' => 'Varchar(100)',
				'EmploymentVerified' => 'Boolean',
        'EmploymentContact' => 'Varchar(255)',
				'EmploymentContactVerified' => 'Boolean',
        'BadCredit' => 'Varchar(100)',
        'SubmittedIPAddress' => 'Varchar(255)',
        'ReferredFrom' => 'Varchar(255)',
        'Latitude' => 'Varchar(100)',
        'Longitude' => 'Varchar(100)',
        'GoogleMapLocation' => 'Varchar(255)',
        'LoanType' => 'Varchar(255)',
        'LoanAmount' => 'Currency',
        'RepaymentPeriod' => 'Varchar(255)',
        'SalePrice' => 'Currency',
        'Status' => 'Enum(array("Moderation","Published"), "Moderation")',
        'SellStage' => 'Int',
        'BuyersNotified' => 'Boolean'
    ];
    
    private static $has_many = [
        'LeadsPurchased' => 'LeadPurchased',
        'LeadRatings' => 'LeadRating'
    ];
    
    private static $summary_fields = [
        'FirstName',
        'LastName',
        'LoanType',
        'LoanAmount',
        'LeadAge',
        'Status'
        
    ];
    
    private static $field_labels = [
        'LeadAge' => 'Age' 
    ];
    
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->replaceField('BuyersNotified', ReadonlyField::create('BuyersNotified'));
        return $fields;
    }
    
    public function onBeforeWrite()
    {
        $member = Security::getCurrentUser();
        
        if($this->isInDb()) {
            if (!Permission::checkMember($member, 'CMS_ACCESS')) {
                //user_error('Lead updates not allowed for non-admins', E_USER_ERROR);
                //exit();
            }
        }
        
        if($this->SalePrice > 0 && $this->Status == 'Published' && !$this->original['BuyersNotified']){

            $this->BuyersNotified = 1;
        
        }
        // CAUTION: You are required to call the parent-function, otherwise
        // SilverStripe will not execute the request.
        parent::onBeforeWrite();
    }
    
    public function onAfterWrite(){
        parent::onAfterWrite();
        
        if($this->SalePrice > 0 && $this->Status == 'Published' && !$this->original['BuyersNotified']){
            $buyerGroup = Group::get()->filter(array(
                'Code' => 'buyer'
            ))->first();
            $members = $buyerGroup->Members();
            //Notify all buyers
            foreach ($members as $member){
                $categories = $member->EmailPreferences;
                
                if(!$categories){
                    $categories = json_decode($member->EmailPreferences);
                    
                    if(!in_array($this->LoanType, $categories))
                        continue;
                }
                
                if (filter_var($member->Email, FILTER_VALIDATE_EMAIL)) {
                    $email = Email::create()
                    ->setHTMLTemplate('Email\\NewLeadBuyerEmail')
                    ->setData([
                        'Member' => $member,
                        'LoanLead' => $this
                    ])
                    ->setFrom('webmaster@fixyourweb.com')
                    ->setTo($member->Email)
                    ->setSubject('New lead submiited!');
                    $email->send();
                }
            }
        } 
    }
    //Get the current member rating
    public function MemberRating(){
        $member = Security::getCurrentUser();
        $rating = LeadRating::get()->filter(array(
            'MemberID' => $member->ID,
            'LoanLeadID' => $this->ID
        ))->first();
        if($rating)
            return $rating->Rating;
        return 0;
    }
    //Get the new sale price for 2nd, 3rd sales..
    public function NewSalePrice(){
        if($this->SellStage == 0)
            return $this->SalePrice;
        
        if($this->SellStage == 1)
            return $this->SalePrice/2;
            
        if($this->SellStage >= 2)
            return $this->SalePrice/4;

    }
    //Get the page link
    public function Link(){
        
        return '/lead/view/'.$this->ID;
    }
    //Get the lead age
    public function LeadAge(){
        $created = new DateTime($this->Created);
        $now = new DateTime('now');
        $interval = $now->diff($created);
        
        $years =  $interval->y;
        $months =  $interval->m;
        $days =  $interval->d;
        $hours = $interval->h;
        $mins = $interval->i;
        
        if($years){
            if($months)
                $str = "$years years $months months";
            else
                $str = "$years years";
        }elseif($months){
            if($days)
                $str = "$months months $days days";
            else
                $str = "$months months";
        }elseif($days){
            if($hours)
                $str = "$days days $hours hrs";
            else
                $str = "$days days";
        }elseif($hours){
            if($mins)
                $str = "$hours hrs $mins mins";
            else
                $str = "$hours days";
        }elseif($mins){
            $str = "$mins mins";
        }else{
            $str = "just now";
        }
        
        return DBField::create_field('Varchar', $str, 'LeadAge');
   }
    //Check if the current member purchased this lead
    public function MemberPurchased(){
        $member = Security::getCurrentUser();
        if($member){
            return LeadPurchased::get()->filter(array(
                'MemberID' => $member->ID,
                'LoanLeadID' => $this->ID
            ))->first();
        }
        return false;
    }
    //Hide info when no lead not purchased yet
    public function HideInfo($str){
        if(!$str)
            return $str;
        
        if($this->MemberPurchased())
            return $str;
        
        $length = strlen($str);
        
        $hide = $length-4;
        
        if($hide > 5)
            $starStr = str_repeat('*', $hide);
        else
            $starStr = str_repeat('*', 5);
        
        $start = substr($str, 0,1);
        
        $end = substr($str, -1,1);
        
        if($length <= 5)
            return $starStr;
    
        return $start.$starStr.$end;
        
    }
}