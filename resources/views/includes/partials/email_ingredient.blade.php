<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="x-apple-disable-message-reformatting">
    <meta name="author" content="Steve Mansfield" />
    <style>
    /* RESET */
    /*Samsung Full width issue*/
    #MessageViewBody,
    #MessageWebViewDiv {
        min-width: 100vw;
        margin: 0 !important;
        zoom: 1 !important;
    }

    u+.body a {
        color: inherit;
        text-decoration: none;
        font-size: inherit;
        font-family: inherit;
        font-weight: inherit;
        line-height: inherit;
    }

    /*Samsung Mail Turning dates into blue links*/
    #MessageViewBody a {
        color: inherit;
        text-decoration: none;
        font-size: inherit;
        font-family: inherit;
        font-weight: inherit;
        line-height: inherit;
    }

    /*SFR grey padding issue*/
    th a,
    th a:link,
    th a:visited {
        color: inherit;
        padding-right: 0;
    }

    /*SFR and other clients left align whole email fix*/
    .parent {
        margin: 0 auto;
    }

    /* Android 4.4 margin */
    div[style*="margin: 16px 0"] {
        margin: 0 auto !important;
        font-size: 100% !important;
    }

    a[x-apple-data-detectors] {
        color: inherit !important;
        text-decoration: none !important;
        font-size: inherit !important;
        font-family: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
    }

    #outlook a {
        padding: 0;
    }

    /* Android 4.4 margin */
    div[style*="margin: 16px 0"] {
        margin: 0 auto !important;
        font-size: 100% !important;
    }

    body {
        width: 100% !important;
        -webkit-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
        margin: 0;
        padding: 0
    }

    .ExternalClass {
        width: 100%
    }

    .ExternalClass,
    .ExternalClass div,
    .ExternalClass font,
    .ExternalClass p,
    .ExternalClass span,
    .ExternalClass td {
        line-height: 100%
    }

    table td {
        mso-table-lspace: 0;
        mso-table-rspace: 0
    }

    table th {
        padding: 0 !important;
        font-weight: normal;
    }

    @media only screen and (max-width: 609px) {

        /*Gmail width issue - body must have class="body" */
        u+.body {
            min-width: 100vw;
        }

        .curve-down-new {
            border-bottom-left-radius: 65% 15%;
            border-bottom-right-radius: 65% 15%;
        }

        /*Show Fall backs*/
        .fallback-hamburger {
            display: block !important;
        }

        .fallback-video {
            display: block !important;
        }

        .fallback-interactive {
            display: block !important;
        }


        /*grid*/
        .m-span10 {
            width: 100% !important;
        }

        .m-span9 {
            width: 90% !important;
        }

        .m-span8 {
            width: 80% !important;
        }

        .m-span7 {
            width: 70% !important;
        }

        .m-span6 {
            width: 60% !important;
        }

        .m-span5 {
            width: 50% !important;
        }

        .m-height-5 {
            height: 5px !important;
            line-height: 5px !important;
            font-size: 5px !important;
        }

        .m-height-10 {
            height: 10px !important;
            line-height: 10px !important;
            font-size: 10px !important;
        }

        .m-height-15 {
            height: 15px !important;
            line-height: 15px !important;
            font-size: 15px !important;
        }

        .m-height-20 {
            height: 20px !important;
            line-height: 20px !important;
            font-size: 20px !important;
        }

        .m-height-25 {
            height: 25px !important;
            line-height: 25px !important;
            font-size: 25px !important;
        }

        .m-height-30 {
            height: 30px !important;
            line-height: 30px !important;
            font-size: 30px !important;
        }

        .m-height-35 {
            height: 35px !important;
            line-height: 35px !important;
            font-size: 35px !important;
        }

        .m-height-40 {
            height: 40px !important;
            line-height: 40px !important;
            font-size: 40px !important;
        }

        .m-height-auto {
            height: auto !important;
        }

    }
    </style>

</head>

<body>
    <table align="center" width="100%" bgcolor="#f5f5f5" border="0" cellspacing="0" cellpadding="0" style="width: 100%; background-color: #f5f5f5">
        <tbody>
            <tr>
                <td height="32" style="height: 32px; line-height: 32px; font-size: 32px; mso-line-height-rule: exactly;" colspan="1">&nbsp;</td>
            </tr>
            <tr>
                <td align="center">
                    <table class="m-span9" bgcolor="#ffffff" align="center" width="610" border="0" cellspacing="0" cellpadding="0" style="width: 610px; background-color: #ffffff;">
                        <tbody>
                            <tr>
                                <td align="center">
                                    <!-- Logo -->
                                    <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td height="32" style="height: 32px; line-height: 32px; font-size: 32px; mso-line-height-rule: exactly;" colspan="1">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td align="left" style="padding:10px">
                                                  Hi {{ $clients->firstname }},<br>
                                                  Below are the ingredients for recipe {{$title}}:<br>
                                                  <br>
                                                  @if($old_serve == $new_serve)
                                                    @foreach ($meal_ingredient_list as $item)
                                                        @if($item['qty'] != 0){{$item['qty']}}@endif  {{$item['measurement']}}  {{$item['item']}}<br>    
                                                    @endforeach 
                                                  @else
                                                    @foreach ($meal_ingredient_list as $item)
                                                       @php
                                                         if($item['qty'] != 0){
                                                            $single_qty = $item['qty']/$old_serve;
                                                            $new_qty =  $single_qty*$new_serve;
                                                         }
                                                        
                                                       @endphp
                                                         @if($item['qty'] != 0){{round($new_qty, 2)}} @endif  {{$item['measurement']}}  {{$item['item']}}<br>    
                                                    @endforeach  
                                                  @endif
                                              
                                                  <br>
                                                  Thanks:<br>
                                                  Team EpicFit
                                                </td>
                                            </tr>
                                            <tr>
                                                <td height="32" style="height: 32px; line-height: 32px; font-size: 32px; mso-line-height-rule: exactly;" colspan="1">&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>                                 
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="32" style="height: 32px; line-height: 32px; font-size: 32px; mso-line-height-rule: exactly;" colspan="1">&nbsp;</td>
            </tr>
        </tbody>
    </table>
</body>

</html>