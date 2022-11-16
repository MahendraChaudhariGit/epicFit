<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
    <h2>Invoice</h2>
    <div>
    	Hi {{$templateData['name']}}, <br>
        Thanks for taking products and services with the Epic Trainer.
        @if($templateData['message'] != '' && $templateData['message'] != null)
        <p>{{$templateData['message']}}</p>
        @endif
       	Please find the attachement of your invoice and let us know in case of any issues.
     </div>
</body>
</html>