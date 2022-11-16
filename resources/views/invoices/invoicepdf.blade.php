@php
$invoice = $invoiceData['invoice'];
$invoiceItems = $invoiceData['invoice_items'];
$salesToolsInvoice = $invoiceData['sales_tools_invoice'];
$taxData = $invoiceData['tax_data'];
$businessData = $invoiceData['business_data'];
@endphp
<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{$invoice['invoice_no']}}</title>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css?family=Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap');
        body{
            font-family: 'Montserrat', sans-serif;
             color: #5b5b60;
        }
        @media print{
            @page{
                margin:20px 10px 20px 25px;
                padding: 10px;
            }
        }
        @page{
            margin:20px 10px 20px 25px;
            padding: 10px;
        }
        p{
            font-size: 13px;
        }
        .logo{
            width: 90%;
        }
        .logo img{
            max-height: 200px;
        }
        .top_data {
            width: 100%;
            margin-top: 20px;
            margin-bottom:20px;
        }
        .left_data{
            width: 30%;
            display: inline-block;
            vertical-align: top;
        }
        .left_data h3 {
            text-transform: uppercase;
            margin: 0px;
            font-weight: 800;
              color: #253746;
            font-size: 24px;
            font-family: 'Montserrat', sans-serif;
        }
        .left_data p {
            font-family: 'Montserrat', sans-serif;
            color: #5b5b60;
            margin: 0px;
            margin-bottom: 10px;
        }
        .left_data strong{
          color: #253746;
        }
        .right_data{
            width: 60%;
            display: inline-block;
            vertical-align: top;
        }
        .right_data p{
            margin-bottom: 0px;
            line-height: 16px;
        }
        .right_data .lefthd {
            width: 200px;
            text-align: right;
            display: inline-block;
            border-right: 1px solid #253746;
            padding-right: 10px;
            margin-right: 10px;
            text-transform: uppercase;
            font-weight: 400;
            font-family: 'Montserrat', sans-serif;
            color: #5b5b60;
            font-size: 14px;
            vertical-align: top;
        }
        .right_data .righttext{
            width:200px;
            font-family: 'Montserrat', sans-serif;
            color: #5b5b60;
            font-size: 14px;
            display: inline-block;
            vertical-align: top;
        }
        .order_details{
            width: 100%;
        }
        .order_details table{
            width: 100%;
            color: #5b5b60;
        }
        .order_details th {
            text-align: center;
            text-transform: uppercase;
            border: 1px solid #999;
            padding: 10px 5px;
             color: #253746;
        }
        .order_details td {
            text-align: center;         
            padding: 15px 5px;
            border: 1px solid #999;         
        }
        .productlist td{
            padding: 3px 5px;
            font-size: 13px;
        }

        .order_details .border_tb{
            border-top: 0px;
            border-bottom: 0px;
        }
        .order_details td strong{
          color: #253746;
        }
        .order_details span{
            font-size: 13px;
            margin-top: 15px;
            display: inline-block;
        }
        .order_details .border_b{
            border-bottom: 0px;
        }
        .order_details .border_t{
            border-top: 0px;
        }
        .order_details .border_l{
            border-left: 0px;
        }
        .order_details .border_r{
            border-right: 0px;
        }
        .footer_detail{
            width: 100%;
            margin-bottom: 20px;
            margin-top: 30px;
        }
        .footer_detail .leftside{
            width: 49%;
            display: inline-block;
            vertical-align: top;
        }
        .footer_detail .rightside{
            width: 50%;
            display: inline-block;
            vertical-align: top;
        }
        .footer_detail p{
            margin: 0px;
            color: #5b5b60;
        }
        .footer_detail h3 {
            margin: 0px;
            font-size: 18px;
            text-transform: uppercase;
            color: #253746;
        }
        .bottom_footer{
            width: 100%;
            text-align: center;
        }
        .bottom_footer p{
            margin: 0px;
            color: #5b5b60;
        }
        .mainleft{
            width:80%;
            float: left;
        }
        .mainright{
            width: 18%;
            float: right;
            position: relative;
            height: 850px;
        }
        .location {
            text-align: center;
            float: left;
            margin-left: 50px;
        }
        .location strong {
            text-transform: uppercase;
            color: #253746;
            font-size: 15px;
            font-weight: 800;
            font-family: 'Montserrat', sans-serif;
        }
        .location p{
            font-size: 13px;
        }
        p{
            margin: 0px;
        }
        .verticletext {
            position: absolute;
            top: 0px;
            left: 0px;
        }
    </style>
</head>
<body>
    <div class="mainleft">
        <div class="logo">
            <img src="{{public_path('uploads/'.$businessData['logo'])}}">
        </div>
        <div class="top_data">
            <div class="left_data">
                <h3>{{$salesToolsInvoice['sti_title']}}</h3>
                <p>{{$invoice['client_name']}} <br>
                    @if($invoice['addr1'] != '' && $invoice['addr1'] != null)
                    {{$invoice['addr1']}} <br>
                    @endif
                    @if($invoice['addr2'] != '' && $invoice['addr2'] != null)
                    {{$invoice['addr2']}} <br>
                    @endif
                    @if($invoice['city'] != '' && $invoice['city'] != null)
                    {{$invoice['city']}} <br>
                    @endif
                    @if($invoice['state'] != '' && $invoice['state'] != null)
                    {{Country::getStateName($invoice['country'], $invoice['state'])}} <br>
                    @endif
                    @if($invoice['country'] != '' && $invoice['country'] != null)
                    {{Country::getCountryName($invoice['country'])}} <br>
                    @endif
                    @if($invoice['postalCode'] != '' && $invoice['postalCode'] != null)
                    {{$invoice['postalCode']}}
                    @endif
                </p>
                <p>
                    <strong>Note:</strong><br>
                    @if($invoice['note'] != '' && $invoice['note'] != null)
                    {{$invoice['note']}}
                    @endif
                </p>
            </div>
            <div class="right_data">
                <div class="lefthd">
                        <p>Tax Invoice Number</p>
                        <p>Invoice Date</p>
                        <p>Payment Due Date</p>
                        <p>Our Order Number</p>
                        <p>Your Order Number</p>
                        <p>Term</p>
                        <p>Sale Rep</p>
                        @if($invoice['delivery_type'] == 'delivery')
                        <p>Shipped Via</p>
                        @endif
                        <p>Delivery Details</p>
                        @if($invoice['delivery_type'] == 'delivery')
                        <p>Contact</p>
                        @endif
                </div>
                <div class="righttext">
                    <p>#{{$invoice['invoice_no']}}</p>
                    <p>{{dbDateToDateString($invoice['invoice_date'])}}</p>
                    <p>{{dbDateToDateString($invoice['due_date'])}}</p>
                    <p>{{$invoice['order_number']}}</p>
                    <p>{{$invoice['cust_order_number']}}</p>
                    <p>{{termOfSale($invoice['termsOfSale'])}}</p>
                    <p>{{$invoice['staffName']}}</p>
                    @if($invoice['delivery_type'] == 'delivery')
                    <p>{{$invoice['shipped_via']}}</p>
                    @endif
                    <p>{{ucfirst($invoice['delivery_type'])}}</p>
                    @if($invoice['delivery_type'] == 'delivery')
                    <p>{{$invoice['contact_person']}}</p>
                    @endif
                </div>

            </div>
        </div>
        <div class="order_details">
            <table  cellspacing="0" cellpadding="0">
                <tr>
                    <th class="border_r">QTY</th>
                    <th class="border_r">Description</th>
                    <th class="border_r">Unit Price</th>
                    <th>Amount</th>
                </tr>
                @foreach($invoiceItems as $key => $invoiceItem)
                <tr class="productlist">
                    <td class=" border_tb border_r">{{$invoiceItem['quantity']}}</td>
                    <td class=" border_tb border_r">{{$invoiceItem['desc']}}</td>
                    <td class=" border_tb border_r">{{isset($invoiceItem['unit_price']) && $invoiceItem['unit_price'] < 0 ? '-$'.number_format(abs($invoiceItem['unit_price']), 2):'$'.number_format($invoiceItem['unit_price'], 2)}}</td>
                    <td class=" border_tb">{{isset($invoiceItem['unit_price']) && $invoiceItem['unit_price'] < 0 ? '-$'.(number_format(abs($invoiceItem['unit_price']), 2))*$invoiceItem['quantity']:'$'.(number_format($invoiceItem['unit_price'], 2)*$invoiceItem['quantity'])}}</td>
                </tr>
                @endforeach
                <tr class="productlist">
                    <td class=" border_tb border_r">&nbsp;</td>
                    <td class=" border_tb border_r">&nbsp;</td>
                    <td class=" border_tb border_r">&nbsp;</td>
                    <td class=" border_tb">&nbsp;</td>
                </tr>
                <tr>
                    <td class="border_b border_r"></td>
                    <td class="border_b border_r"></td>
                    <td class="border_b border_r" style="text-transform: uppercase;">Subtotal</td>
                    <td class="border_b ">${{$invoice['total'] - $invoice['tax-amount']}}</td>
                </tr>
                <tr>
                    <td class="border_b border_r"></td>
                    <td class="border_b border_r" style="text-transform: uppercase;">{{$taxData['mtax_label']}}: {{$taxData['mtax_rate']}}%</td>
                    <td class="border_b border_r" style="text-transform: uppercase;">Tax</td>
                    <td class="border_b ">${{$invoice['tax-amount']}}</td>
                </tr>
                <tr>
                    <td class="border_b border_r"></td>
                    <td class="border_b border_r"></td>
                    <td class="border_b border_r" style="text-transform: uppercase;">Additional</td>
                    <td class="border_b ">$0</td>
                </tr>
                <tr>
                    <td class="border_b border_r"></td>
                    <td class="border_b border_r"></td>
                    <td class="border_b border_r" style="text-transform: uppercase;">Total</td>
                    <td class="border_b ">${{$invoice['total']}}</td>
                </tr>
                <tr>
                    <td class="border_b border_r"></td>
                    <td class="border_b border_r"></td>
                    <td class="border_b border_r" style="text-transform: uppercase;">Amount Paid</td>
                    <td class="border_b ">${{ $invoice['paid_amount'] ?? '' }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="border_b border_r border_l" style="text-align: right;padding-right: 30px;text-transform: uppercase;">
                        {{$salesToolsInvoice['sti_payment_notes']}}
                    </td>
                    <td class="">
                        <strong>${{number_format($invoice['total'] - $invoice['paid_amount'], 2)}}</strong><br>
                        <span style="width: 100%;text-align: center;">Pay This Amount</span>
                    </td>
                </tr>
            </table>
        </div>
        <div class="footer_detail">
            <div class="leftside">
                <h3>Direct All Enquiries To:</h3>
                @if($salesToolsInvoice['query_contact_name'] != '' && $salesToolsInvoice['query_contact_name'] != null)
                <p>{{$salesToolsInvoice['query_contact_name']}}</p>
                @endif
                @if($salesToolsInvoice['query_contact_phone'] != '' && $salesToolsInvoice['query_contact_phone'] != null)
                <p>{{$salesToolsInvoice['query_contact_phone']}}</p>
                @endif
                @if($salesToolsInvoice['query_contact_email'] != '' && $salesToolsInvoice['query_contact_email'] != null)
                <p>{{$salesToolsInvoice['query_contact_email']}}</p>
                @endif
            </div>
            <div class="rightside">
                <h3>Make All Payment To:</h3>
                @if($salesToolsInvoice['payment_company_name'] != '' && $salesToolsInvoice['payment_company_name'] != null)
                <p>{{$salesToolsInvoice['payment_company_name']}}</p>
                @endif
                @if($salesToolsInvoice['payment_bank'] != '' && $salesToolsInvoice['payment_bank'] != null)
                <p>{{$salesToolsInvoice['payment_bank']}}</p>
                @endif
                @if($salesToolsInvoice['bank_account_number'] != '' && $salesToolsInvoice['bank_account_number'] != null)
                <p>{{$salesToolsInvoice['bank_account_number']}}</p>
                @endif
                @if($salesToolsInvoice['payment_country'] != '' && $salesToolsInvoice['payment_country'] != null)
                <p>{{Country::getCountryName($salesToolsInvoice['payment_country'])}}</p>
                @endif
            </div>
        </div>
        <div class="bottom_footer">
            <p>M: {{$businessData['phone']}} &nbsp; E: {{$businessData['email']}}</p><p>{{$businessData['trading_name']}} &nbsp; GST number: {{$salesToolsInvoice['buss_gst']}}</p>
        </div>
    </div>
    <div class="mainright">
        <div class="verticletext">
            <img src="{{public_path('assets/images/righttext.jpg')}}">
        </div>
        <div class="location">
            <p>
                <strong>{{$businessData['trading_name']}}</strong><br>
                @if($businessData['address_line_one'] != '' && $businessData['address_line_one'] != null)
                {{$businessData['address_line_one']}} <br>
                @endif
                @if($businessData['address_line_two'] != '' && $businessData['address_line_two'] != null)
                {{$businessData['address_line_two']}} <br>
                @endif
                @if($businessData['city'] != '' && $businessData['city'] != null)
                {{$businessData['city']}} <br>
                @endif
                @if($businessData['state'] != '' && $businessData['state'] != null)
                {{Country::getStateName($businessData['country'], $businessData['state'])}} <br>
                @endif
                @if($businessData['postal_code'] != '' && $businessData['postal_code'] != null)
                {{$businessData['postal_code']}} <br>
                @endif
                @if($businessData['country'] != '' && $businessData['country'] != null)
                {{Country::getCountryName($businessData['country'])}}
                @endif
            </p>
        </div>
    </div>
</body>
</html>