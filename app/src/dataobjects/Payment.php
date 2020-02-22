<?php
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;

class Payment extends DataObject
{
    private static $db = [
        'Amount' => 'Currency',
        'Gateway' => 'Varchar(255)',
        'Reference' => 'Varchar(255)',
        'Status' => 'Enum(array("Pending","Cancelled","Declined","Paid"), "Pending")'
    ];
    
    private static $has_one = [
        "Member" => Member::class
    ];
}