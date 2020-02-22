<?php


use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\FieldList;
use UndefinedOffset\NoCaptcha\Forms\NocaptchaField;
use SilverStripe\Forms\ListboxField;


class MemberExtension extends DataExtension {
    
    private static $db = array(
        'Company' => 'Varchar(200)',
        'Position' => 'Varchar(200)',
        'Mobile' => 'Varchar(100)',
        'WorkPhone' => 'Varchar(100)',
        'Balance' => 'Currency',
        'EmailPreferences' => 'Text'
        
    );
    
    private static $has_many = [
        'Payments' => 'Payment',
        'LeadsPurchased' => 'LeadPurchased',
        'LeadRatings' => 'LeadRating'
    ];
    
    public function updateMemberFormFields(FieldList $fields) {
        $fields->push(new TextField('Company', 'Company'));
        $fields->push(new TextField('Position', 'Position'));
        $fields->push(new TextField('Mobile', 'Mobile'));
        $fields->push(new TextField('WorkPhone', 'Work Phone'));
        $fields->push(new TextField('Balance', 'Balance'));
        $fields->push(new ListboxField('EmailPreferences', 'Email Preferences',LOAN_CATEGORIES));
        $fields->push(new NocaptchaField('Captcha'));
        
    }
    
}