<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SalesToolsInvoicePaymentTypes extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'sales_tools_invoice_payment_types';
    protected $primaryKey = 'stipt_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['stipt_payment_types', 'stipt_business_id'];

}
