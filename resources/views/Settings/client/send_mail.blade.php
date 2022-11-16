<!DOCTYPE html>
        <html>
        
        <head>
            <title>Email</title>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </head>
        
        <body>
            <div style="width: 650px;margin:0px auto;border: 1px solid #b5b5b5;box-sizing: border-box;padding:20px;border-radius: 3px;">
                <div style="padding: 10px 0px;font-size: 14px;border-bottom: 1px solid #b5b5b5;background: #e8e8e8;border-radius: 3px;font-family: arial">
                <center><h2>EPICFIT Client Access Details</h2></center>
                </div>
                <div style="padding: 15px 15px;font-size: 14px;border: 1px solid #b5b5b5;font-family: arial">
                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="first_name" class="strong"><strong>Name </strong></label>
                               <br> {{ @$request['first_name'] }} {{ @$request['last_name']}}
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <label for="client_status" class="strong"><strong>What is client status</strong></label>
                                <br> {{ @$request['client_status'] }}
                            </div>
                        </div>
                        @if(!empty(@$request['gender']))
                        <div class="form-group">
                            <div>
                                <label for="" class="strong"><strong>Gender</strong></label>
                                <br> {{ @$request['gender'] }}
                            </div>
                        </div>
                        @endif
                        @if(!empty($goals) )
                        <div class="form-group ">
                            <div>
                                <label for="goalHealthWellness" class="strong"><strong>What are your goals</strong></label>
                                <br>  {{ $goals}}
                            </div>
                        </div>
                        @endif
                        @if(!empty(@$request['day']) )
                        <div class="form-group clearfix">
                            <div>
                                <label for="day" class="strong"><strong>DOB</strong></label>
                                <br> {{ @$request['year'] }}-{{ @$request['month'] }}-{{ @$request['day'] }}
                            </div>
                            
                        </div>
                        @endif

                        @if(!empty(@$request['referrer']))
                        <div class="form-group">
                            <label class="strong" for="referrer"><strong>Where did you hear about EPIC?</strong></label>
                            <br> {{ @$request['referrer'] }}
                        </div>
                        @endif

                        @if(!empty($referencewhere))
                        <div class="form-group referencewhere">
                            <label class="strong"><strong>From where?</strong></label>
                            <br> {{ $referencewhere }}
                        </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        @if(!empty($otherName))
                        <div class="form-group otherName hidden">
                            <label class="strong"><strong>Source</strong></label>
                            <br> {{ $otherName }}
                        </div>
                        @endif
                        @if(!empty(@$request['referralNetwork']))
                        <div>
                            <label class="strong"><strong>Referred by?</strong></label>
                            <br> {{ @$request['referralNetwork'] }}
                        </div>
                        @endif
                        @if(!empty($referral_name))
                        <div>
                            <label class="strong"><strong>Referrel Name</strong></label>
                            <br> {{ $referral_name }}
                        </div>
                        @endif
                        <div class="form-group">
                            <div>
                                <label class="strong"><strong>Email</strong></label>
                                <br> {{ @$request['email'] }}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="checkbox clip-check check-primary m-b-0">
                                <label for="login_with_email_client">
                                    <strong>Allow client to log in with email</strong> 
                                </label>
                                <br> {{ $allow}}
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div>
                                <label for="numb" class="strong"><strong>Please provide your phone number *</strong></label>
                                <br> {{ @$request['numb'] }}
                            </div>
                        </div>
                        @if(!empty(@$request['client_notes']))
                        <div class="form-group">
                            <div>
                                <label for="client_notes" class="strong"><strong>Client Notes</strong></label>
                                <br> {{ @$request['client_notes'] }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div style="padding: 15px 0px;font-size: 14px;font-family: arial">
                <p style="margin-bottom: 5px">Thanks,</p>
                <p style="margin-top: 0px">The EPICFIT Team</p>
            </div>
            <div style="padding: 15px 0px;text-align:center;font-family: arial;border-top: 1px solid #b5b5b5;">
                <p style="font-size: 12px;margin-bottom: 0px">© Epic Fit Fitness Centre – Albany Fitness Classes, 2019</p>
                <p style="font-size: 12px;margin-top: 5px;margin-bottom: 0px;">Studio Alpha 1- Suite 1, 4 Arrenway Drive Albany,
                    Auckland</p>
            </div>
            </div>
        </body>
        
        </html>