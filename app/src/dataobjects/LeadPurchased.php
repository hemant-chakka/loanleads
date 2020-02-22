<?php
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;

class LeadPurchased extends DataObject
{
    private static $db = [
        'Price' => 'Currency'
    ];
    
    private static $has_one = [
        "Member" => Member::class,
        "LoanLead" => "LoanLead"
    ];
}