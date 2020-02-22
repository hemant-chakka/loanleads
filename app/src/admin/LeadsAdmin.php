<?php
use SilverStripe\Admin\ModelAdmin;

class LeadsAdmin extends ModelAdmin
{
    
    private static $managed_models = [
        'LoanLead'
    ];
    
    private static $url_segment = 'leads';
    
    private static $menu_title = 'Leads';
    
    public function getList()
    {
        $list = parent::getList();
        
        // Always limit by model class, in case you're managing multiple
        if($this->modelClass == 'LoanLead') {
            $list = $list->sort('Created','DESC') ;
        }
        
        return $list;
    }
}