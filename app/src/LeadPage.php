<?php
use SilverStripe\Control\Director;

class LeadPage extends Page
{
    
    
    
}


class LeadPageController extends PageController
{
    
    private static $allowed_actions = [
        'view',
        'purchase'
    ];
    
    private static $url_handlers = [
        //'lead/$ID' => 'test'
    ];
    
    
    public function init(){
        parent::init();
        if(!$this->request->param('ID'))
            return $this->redirect(Director::baseURL());
    }
    
    public function view(){

      $lead = LoanLead::get()->byID($this->request->param('ID'));
      
      $data = array(
          'Lead' => $lead
      );
      
      return $this->renderWith(array('LeadPage','Page'),$data);
    
    }
    //Show the purchase page
    public function purchase(){
        
        $lead = LoanLead::get()->byID($this->request->param('ID'));
        
        $data = array(
            'Lead' => $lead
        );
        
        return $this->renderWith(array('LeadPage_purchase','Page'),$data);
        
    }
    
}