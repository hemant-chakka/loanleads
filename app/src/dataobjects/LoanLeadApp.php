<?php
use SilverStripe\ORM\DataObject;

class LoanLeadApp extends DataObject
{
    private static $db = [
        'LoanType' => 'Varchar(255)',
        'LoanAmount' => 'Currency',
        'RepaymentPeriod' => 'Varchar(255)',
        'SalePrice' => 'Currency',
        'Status' => 'Enum(array("Moderation","Published"), "Moderation")',
        'SellStage' => 'Int'
    ];
    
    private static $has_one = [
        "LoanLead" => "LoanLead"
    ];
    
    private static $has_many = [
        'LeadsPurchased' => 'LeadPurchased',
        'LeadRatings' => 'LeadRating'
    ];
}