<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceEmailLog extends Model{
    protected $table = 'invoice_email_logs';
	protected $primaryKey = 'iel_id';
    protected $appends = array('emailSendDate', 'emailSendTime');
    private $formatedDateTime = [];

    public function getEmailSendDateAttribute(){
        return $this->calculateEmailSendDate();  
    }

    public function getEmailSendTimeAttribute(){
        return $this->calculateEmailSendTime();  
    }

    private function calculateEmailSendDate(){
        if(!count($this->formatedDateTime))
            $this->calculateFormatedDateTime();
        return $this->formatedDateTime[0];
    }

    private function calculateEmailSendTime(){
        if(!count($this->formatedDateTime))
            $this->calculateFormatedDateTime();
        return $this->formatedDateTime[1];
    }

    private function calculateFormatedDateTime(){
        $this->formatedDateTime = explode(' ', date('d-M-Y H:i:sa', strtotime($this->iel_date_time)));
    }
}
