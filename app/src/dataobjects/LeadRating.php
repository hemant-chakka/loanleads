<?php
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;

class LeadRating extends DataObject
{
    private static $db = [
        'Rating' => 'Int'
    ];
    
    private static $has_one = [
        "Member" => Member::class,
        "LoanLead" => "LoanLead"
    ];
}