<?php
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;

class LeadsHolder extends Page
{
    
    
    public function PubLeads(){
        
        if(isset($_GET['filter']) && isset($_GET['value'])){
            $filter = $_GET['filter'];
            $val = $_GET['value'];
            
            if($filter == 'category'){
                return LoanLead::get()->filter(array(
                    'LoanType' => $val,
                    'Status' => 'Published'
                ))->sort('Created','DESC');
            }
            
            if($filter == 'amount'){
                $filter = array(
                    'Status' => 'Published'
                );
                
                if($val == '10')
                    $filter['LoanAmount:LessThan'] = 10000.00;
                
                if($val == '20'){
                    $filter['LoanAmount:GreaterThanOrEqual'] = 10000.00;
                    $filter['LoanAmount:LessThanOrEqual'] = 30000.00;
                }
                
                if($val == '30')
                    $filter['LoanAmount:GreaterThan'] = 30000.00;
                
                return LoanLead::get()->filter($filter)->sort('Created','DESC');
            }
            
            if($filter == 'posted'){
                $date = new DateTime();
                
                if($val == '24')
                    $date->modify('-24 hours');
                
                if($val == '48')
                   $date->modify('-48 hours');
                
                $created = $date->format('Y-m-d H:i:s');
                
                return LoanLead::get()->filter(array(
                    'Created:GreaterThanOrEqual' => $created,
                    'Status' => 'Published'
                ))->sort('Created','DESC');
            }
            
            if($filter == 'location'){
                return LoanLead::get()->filter(array(
                    'City' => $val,
                    'Status' => 'Published'
                ))->sort('Created','DESC');
            }
        }
        
        return LoanLead::get()->filter(array(
            'Status' => 'Published'
        ))->sort('Created','DESC');
    }
    
    public function GetCategories(){
        $categories = LOAN_CATEGORIES;
        $cats = new ArrayList();
        foreach($categories as $category){
            $cats->push(new ArrayData(array(
                'Category' => $category
            )));
        }
        return $cats;
    }
}