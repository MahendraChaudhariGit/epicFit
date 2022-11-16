<!DOCTYPE html>
<html><head>
    <title>Meal</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style type="text/css">
    @import url('https://fonts.googleapis.com/ css?family=Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap');
    body {
        font-family: 'Montserrat', sans-serif !important; 
        color: #5b5b60;  
        background: url('/assets/images/watermark.png');
        background-size: 100% 100%;
        background-repeat: repeat-y;
        -webkit-print-color-adjust: exact; 
        font-size: 12px;
    }
    @media print {
        * {
            -webkit-print-color-adjust: exact !important; /*Chrome, Safari */
            color-adjust: exact !important;  /*Firefox*/
        }
    }          
    @page{
        margin:10px 10px 0px 10px;
    }
    .meals_details h1{
        font-size: 20px;
    }
      .recipe__list-subheading{
    list-style: none !important;
    font-style: normal;
    font-family: inherit;
    font-weight: 400;
    margin-bottom: 5px;
    padding-left: 0px !important;
    margin-left: 0px !important;
  }
  ol li{
    list-style: decimal;
  }
    .breakfast_view {
        margin: 0px auto;
        padding: 10px;
    }
    .breakfast_view h1 {
        text-transform: uppercase;
        font-weight: 700;            
        letter-spacing: 1px;
        color: #253746;
        font-family: 'Montserrat', sans-serif !important;
        font-size: 48px;
        margin-top: 0px;
        margin-bottom: 0px;
    }

    .breakfast_view .mainimg {
        height: 350px;
        overflow: hidden;
    }
    .breakfast_view .mainimg img{
        width: 100%;
        height: 100%;
    }
    .description_section {
        width: 100%;
        height: 30px;
    }
    .description_section ul{
        width: 100%;
        padding: 0px;
        margin-bottom: 0px;
    }
    .bottom_data ul li{
       margin-bottom: 3px;
   }
   .description_section li {
    display: inline-block;
    margin-right: 10px;
    vertical-align: top;
    width: 32%;
}
.description_section .icon {
    display: inline-block;
    margin-right:5px;
    width: 25px;
    vertical-align: top;
}
.description_section .description_data{
    font-size: 12px;
}
.right_hd{
    display: inline-block;
    vertical-align: top;
    margin-top: 0px;
}
.right_hd span.value {
    font-size: 20px;
    display: inline-block;
    font-weight: 700;
    color: #253746;
}
.time-hrs,.time-min{
     font-size: 20px
}
.description_section h3 {
    text-transform: uppercase;
    font-family: 'Montserrat', sans-serif !important;
    color: #253746;
    margin-top: 0px;
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 0px;
}
.description_section h4 {
    font-size: 20px;
    display: inline-block;
    margin-bottom: 0px;
    font-family: 'Montserrat', sans-serif !important;
    color: #253746;
    margin-top: 0px;
    font-weight: 700;
    text-transform: uppercase;
    margin-right: 5px;
    margin-bottom: 0px;
}
.icon img {
    height: 20px;
}
.description_data{
font-size: 12px;
}
.description_data p{
    font-size: 12px;
    font-family: 'Montserrat', sans-serif !important;
}
.bottom_data {
    width: 100%;
}
.bottom_data h2{
    text-transform: uppercase;
    font-size: 20px;
    font-weight: 700;
    font-family: 'Montserrat', sans-serif !important;
    letter-spacing: 1px;
    color: #253746;
    margin-top: 0px;
    margin-bottom: 0px;
    line-height: 30px;
}
.bottom_data h3{
    text-transform: uppercase;
    font-size: 18px;
    font-weight: 700;
    font-family: 'Montserrat', sans-serif !important;
    letter-spacing: 1px;
    color: #253746;
    margin-top: 10px;
    margin-bottom: 0px;
    line-height: 20px;
}
.bottom_data .prepation_box {
  display: inline-block;
  vertical-align: top;
  min-height: 300px;
  width: 48%;
}
.bottom_data .prepation_box:first-child{
    padding-right: 20px;
/*    border-right: 2px solid #253746;*/
}
.bottom_data .prepation_box:last-child{
    padding-left: 20px;
    border-left: 2px solid #253746;
    margin-left: -7px;
}

            .bottom_data .prepation_box p{
                margin-bottom:5px;
                line-height: 14px;
                font-size: 12px;
                color: #666666;
            }
            .bottom_data li{
                font-size: 12px;
                margin-bottom: 0px;
                line-height: 14px;
                color: #666666;
                padding-left: 15px;
                margin-bottom: 0px;
            }
            .bottom_data .prepation_box ol{
                padding: 0px;
                margin-bottom: 5px;
                margin-top: 0px;
            }
            .bottom_data .prepation_box ul {
                padding: 0px;  
                margin-bottom:5px;          
            }
            li{
                font-size: 12px;
            }
            .bootom_area {
                width: 100%;
            }
            .bootom_area h3 {
                text-align: center;
                font-family: 'Montserrat', sans-serif !important;
                font-weight: 500;
                font-size: 18px;
                text-transform: uppercase;
                color: #253746;
                margin-bottom: 0px;
            }
            .bootom_area span{
                color: #253746;
            }
            .bootom_area p{
                text-align: center;
            }
            .bottom-table{
                width:100%;
                border-collapse: collapse;
                page-break-after: avoid;
            }
            .bottom-table th{
                text-transform: uppercase;
                font-weight: 500;
                font-size: 11px;
                padding: 3px;
                text-align: center;
            }
            .bottom-table td{
                font-size: 14px; 
                padding: 3px;
                text-align: center;
            }
            .recipe__list-step {
                padding: 0
            }
            .checkbox-list-checkmark{
                padding-top: 0px;
                margin-top: 0px;
            }
            .checkbox-list{
                padding: 0px;
                margin: 0px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            ol, ul{
                padding-left: 0px;
            }
           
          .checkbox-list-checkmark{
            padding-left: 18px;
            margin-top: -2px;
          }

                .recipe__list-step:before {
                    min-width: 24px;
                    /*  margin: 2px 20px 0 0;*/
                    border-radius: 13px;
                    font-size: 12px;
                    line-height: 22px;
                }
                .recipe__list-step:before {
                    counter-increment: steps;
                    content: counter(steps);
                }
                .bottom_data .prepation_box ol li {
                    list-style: decimal;
                    padding-left: 0px;
                }
                .checkbox-list-checkmark:before{
                    display: none;
                }
                .recipe__list-qty{
                    font-weight: bold;
                }
                .bottom_data .prepation_box ol{
                    padding-left: 15px;
                }
            </style>
        </head>
        <body>

            <div class="breakfast_view">
                <h1>{{$mealData['name']}}</h1>
                <div class="mainimg">
                    <img src="{{public_path('uploads/thumb_'.$mealData['image'])}}">
                </div>
                <div class="description_section">
                    <ul style="height:22px;">
                        <li>
                            <div class="icon">
                              <img src="{{public_path('assets/images/discription-icon.png')}}">
                          </div>
                          <div class="right_hd">
                            <h3 style="margin:0">Description</h3>
                        </div>
                    </li>
                    <li>
                        <div class="icon">
                            <img src="{{public_path('assets/images/time-icon.png')}}">
                        </div>
                        {{-- <h4>Prep<br>Time</h4> --}}
                       <!--  <h4>Time</h4> -->
                        <div class="right_hd" style="display:inline-block;margin-top: 3px;">
                             <h3 style="margin:0;display:inline-block;">Time</h3>
                            @if($mealData['total_hrs'])
                            <span class="value" style="display:inline-block;font-size: 22px;">{{$mealData['total_hrs']}}</span>
                            <span style="font-size:12px;display:inline-block;margin-top: -3px;">@if($mealData['total_hrs'] > 1)Hours @else Hour @endif</span>
                            @endif  
                            @if($mealData['total_mins'])
                            <span class="value" style="display:inline-block;font-size: 22px;">{{$mealData['total_mins']}}</span>
                            <span style="font-size: 12px;display:inline-block;margin-top: -3px;">@if($mealData['total_mins'] > 1)Minutes @else Minute @endif </span>

                            @endif 
                        </div>
                    </li>
                    <li>
                        <div class="icon">
                            <img src="{{public_path('assets/images/serving-icon.png')}}">
                        </div>
                     <!--    <h4>Serving Size</h4> -->
                       <div class="right_hd" style="display:inline-block;font-size: 22px;margin-top: 3px;">
                            <h3 style="margin:0;display:inline-block;">Serving Size</h3>
                            <span class="value" style="font-size: 22px;display:inline-block;">{{$mealData['serving_size']}}</span>
                        </div>
                    </li>
                </ul>            
            </div>
            <div class="description_data" style="margin-top: 5px;margin-bottom: 5px;">
                {!! $mealData['description'] !!}
            </div>
            @php
         
                if($mealData['ingredient_set_no']!= 1){
                    $set_name = json_decode($mealData['ingredient_set_name']);
                }
            @endphp
            <div class="bottom_data" style="display:table">
                <div class="prepation_box" style="display: table-cell;">
                    <h2><img src="{{public_path('assets/images/ingrediant-icon.png')}}" height="20px"> Ingredients</h2>
                        <ul>
                            
                                @if(($mealData['ingredient_set_no'] != 1) && isset($set_name))
                                    <li class="recipe__list-subheading">{{$set_name->set_name_1}}:</li>
                               @endif
                               {{-- {{dd($mealData['mealIngredientSetPart1'])}} --}}
                               @if(count($mealData['mealIngredientSetPart1']) > 0)
                                   @foreach($mealData['mealIngredientSetPart1'] as $ingr_val_1)
                                     <li>
                                       <span style="display: inline-block;">
                                        <span>@if($ingr_val_1->qty != 0){{$ingr_val_1->qty}}@endif</span>{{$ingr_val_1->measurement??''}} {{$ingr_val_1->item??''}}<br>
                                       </span>         
                                   </li> 
                                    @endforeach
                               @endif 
                 
                    
                  </ul>
                   <ul>
                    @if(($mealData['ingredient_set_no'] != 1) && isset($set_name))
                      <li class="recipe__list-subheading">{{$set_name->set_name_2}}:</li>
                    @endif
                    @if(count($mealData['mealIngredientSetPart2']) > 0)
                        @foreach($mealData['mealIngredientSetPart2'] as $ingr_val_2)
                        <li>
                            <span style="display: inline-block;">
                            <span class="">
                            <span>@if($ingr_val_2->qty != 0){{$ingr_val_2->qty}}@endif</span> {{$ingr_val_2->measurement??''}}
                            </span>
                                {{$ingr_val_2->item??''}}<br>
                            </span>
                        
                        </li> 
                        @endforeach
                    @endif 
                  {{-- <li class="recipe__list-subheading">Pudding & Caramelized Bananas:</li>
                    <li>

                    
                       
                        <span style="display: inline-block;">
                          <span class="">
                            <span>1</span> teaspoon
                          </span>
                          mayonnaise<br>
                        </span>
                    
                    </li> --}}
            
                  </ul>
 
            </div>
            <div class="prepation_box" style="display: table-cell;">
            <h2>
                <img src="{{public_path('assets/images/preparation-icon.png')}}" height="20px"> Preparation</h2>
                @if(($mealData['ingredient_set_no'] == 3) && isset($set_name))
                <p class="recipe__list-subheading">{{$set_name->set_name_1}}</p>
                 @endif
      
                  <ol>
                    @if(count($mealData['mealPreparationPart1']) > 0)
                        @foreach($mealData['mealPreparationPart1'] as $key=> $prep_part1)
                            <li class="">
                            {{ $prep_part1['description'] }} 
                            </li>
                        @endforeach
                     @endif
                    {{-- <li class="">
                     Make your own vanilla sugar. (2 cups sugar with 1 tsp vanilla extract or 1 scraped vanilla bean - mixed together thoroughly and stored in airtight container.)
                     
                    </li> --}}
                    
                  </ol>

                  @if(($mealData['ingredient_set_no'] == 3) && isset($set_name))
                  <p class="recipe__list-subheading">{{$set_name->set_name_2}}</p>
                   @endif
                   
                  <ol>
                    @if(count($mealData['mealPreparationPart2']) > 0)
                        @foreach($mealData['mealPreparationPart2'] as $key=> $prep_part2)
                            <li class="">
                                {{ $prep_part2['description'] }}
                            </li>
                        @endforeach
                    @endif
                   
                  </ol>
  <h3><img src="{{public_path('assets/images/preparation-icon.png')}}" height="20px"> Tips</h3>
  {!! $mealData['tips'] !!}
</div>
</div>
<div class="bootom_area">

    <h3><span>Calories:</span> {{ round($mealData['nutritional_information']->energ_kcal/$mealData['serving_size'], 2)}}</h3>
    <table class="bottom-table">
        <tr>
            <th><span>FAT</span>:</th>
            <th><span>SATURATED FAT</span>:</th>
            <th><span>Carbohydrates</span>:</th>
            <th><span>Sugar</span>:</th>
            <th><span>Sodium</span>:</th>
            <th><span>Fiber</span>:</th>
            <th><span>Protein</span>:</th>
            <th><span>Cholesterol</span>:</th>
        </tr>
        <tr>
            <td>
                <span>{{round($mealData['nutritional_information']->fat/$mealData['serving_size'], 2)}}g</span>
            </td>
            <td>
                <span>{{ round($mealData['nutritional_information']->fa_sat/$mealData['serving_size'], 2) }}g</span>
            </td>
            <td>
                <span> {{ round($mealData['nutritional_information']->carbohydrate/$mealData['serving_size'], 2 )}}g</span>
            </td>
            <td>
                <span> {{ round($mealData['nutritional_information']->sugar/$mealData['serving_size'], 2) }}g</span>
            </td>
            <td>
                <span> {{ round($mealData['nutritional_information']->sodium/$mealData['serving_size'], 2) }}mg</span>
            </td>
            <td>
                <span>{{ round($mealData['nutritional_information']->fiber/$mealData['serving_size'], 2) }}g</span>
            </td>
            <td>
                <span>  {{round($mealData['nutritional_information']->protein/$mealData['serving_size'], 2) }}g</span>
            </td>
            <td>
                <span> {{ round($mealData['nutritional_information']->cholesterol/$mealData['serving_size'], 2)}}mg</span>
            </td>
        </tr>
    </table>
</div>        
</div>
</body>
</html>