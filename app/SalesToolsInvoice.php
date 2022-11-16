<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesToolsInvoice extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sales_tools_invoices';
    protected $primaryKey = 'sti_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['payTerms', 'invTitle', 'bussReg', 'bussRegType', 'payInst', 'nxtInvNum', 'invType1', 'invType2', 'taxName', 'taxRate', 'taxUsuage', 'overrideAll', 'payType1', 'payType2', 'payType3', 'payType4'];

}
