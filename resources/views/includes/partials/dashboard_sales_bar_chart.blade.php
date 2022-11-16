<div class="col-md-12">
<div class="tabbable">
<ul id="myTab2" class="nav nav-tabs">
	<li class="active">
		<a href="#sales" data-toggle="tab">
			SALES
		</a>
	</li>
	<li>
		<a href="#productivity" data-toggle="tab"> 
			 PRODUCTIVITY 
		</a>
	</li>
	 <li>
		<a href="#price" data-toggle="tab">
		    TOTAL PRICE
		</a>    
	</li>
</ul>
<div class="tab-content">
	<div class="tab-pane fade in active" id="sales">

                <div class="row p-t-10"> 
                  	<div class="col-xs-12" style="height: 400px;">        
		            	<canvas id="salesbar" style="position:relative;"></canvas>
		            	<div class="pull-right now-style sales-cls text-center" id="now-tooltip" ><strong>TODAY</strong></div>
		          	</div>
		          	<div class="col-md-12">
			            <div class="sale-last">
					        <div class="last-next-style">
					         	<div class="area-7days">LAST 7 DAYS</div>
					        </div>
				        </div>
				        <div class="sale-next" >
					        <div class="last-next-style">
					         	<div class="area-7days">NEXT 7 DAYS</div>
					        </div>
				        </div>
			        </div>  
		        </div>

               	<div class="row m-t-20">
                    <div class="col-xs-12 inline">
                        <!--Start: legend -->
                        <div id="sales-legend" class="chart-legend"></div>
                        <!--End: legend -->
                    </div>
               	</div>	   
	</div>
    <div class="tab-pane fade" id="productivity">
	        <div class="row">
	           	<div class="col-xs-12 " style="height: 400px;">         
		         	<canvas id="productivitybar" width="700" height="300" style="position:relative;"></canvas>
		         	<div class="pull-right now-style sales-cls text-center"><strong>TODAY</strong></div>
		       	</div>
		       	<div class="col-md-12">
			            <div class="sale-last">
					        <div class="last-next-style">
					         	<div class="area-7days">LAST 7 DAYS<br>
				         		<span id="last7-per-data"></span>% booked
				         	</div>
					        </div>
				        </div>
				        <div class="sale-next" >
					        <div class="last-next-style">
					         	<div class="area-7days">NEXT 7 DAYS<br>
				         		<span id="next7-per-data"></span>% booked
				         	</div>
					        </div>
				        </div>
			        </div>  
		       	<!-- <div class="col-md-12">
		            <div class="productivity-last">
				        <div class="last-next-style">
				         	<div class="area-7days">LAST 7 DAYS<br>
				         		<span id="last7-per-data"></span>% booked
				         	</div>
				        </div>
			        </div>
			        <div clas="productivity-next">
				        <div class="last-next-style">
				         	<div class="area-7days">NEXT 7 DAYS<br>
				         		<span id="next7-per-data"></span>% booked
				         	</div>
				        </div>
			        </div>
		        </div>   -->
		    </div>
	        <!-- <div class="row">
		        <div class="col-xs-6 productivity-last7">
			         <div class="last-next-style">
			         	<div class="product-7days area-7days text-center">LAST 7 DAYS<br><span id="last7-per-data"></span>% booked</div>
			         </div>
		         </div>
		         <div class="col-xs-6">
			         <div class="last-next-style">
			         	<div class="product-7days area-7days text-center">NEXT 7 DAYS<br><span id="next7-per-data"></span>% booked</div>
			         </div>
		         </div>
	         </div> -->
	        <div class="row m-t-40">
	            <div class="col-xs-12 inline">
                    <!--Start: legend -->
                	<div id="productivity-legend" class="chart-legend"></div>
                    <!--End: legend -->
	            </div>
	        </div>		
	</div>
	<div class="tab-pane fade" id="price">
	    <div class="row">
       	  	<div class="col-md-1 col-sm-1">
       	  	 <div>SERVICES</div>
       	  	 <div class="number-style">${{ $last_7days_price }}</div>
       	  	 <div>
       	  	   @if($last_14days_price > 0)
	       	  	    <span data-toggle="tooltip" data-placement="top" title="vs. previous 7 days" class="epic-tooltip tooltipclass" rel="tooltip">
		       	  	   <font color="<?php echo $last_7days_price > $last_14days_price ? '#61C561':'#ff4401';?>">
			       	  	  <i class="fa fa-chevron-circle-down"></i>
			       	  	    ${{ $last_14days_price }}  
			       	   </font>	  
		       	  	 </span>
	       	  	@else
		       	  	 <sapn data-toggle="tooltip" data-placement="top" title="vs. previous 7 days" class="epic-tooltip tooltipclass" rel="tooltip">
       	  	             No change
       	  	         </sapn>
		       	@endif 
       	  	 </div>
       	  	</div>
       	  	<div class="col-md-1 col-sm-1">
       	  		<div class="math-symbole">+</div>
       	  	</div>
       	  	<div class="col-md-1 col-sm-1">
       	  	 <div>PRODUCTS</div>
       	  	 <div class="number-style">$0</div>
       	  	 <div>
                 <sapn data-toggle="tooltip" data-placement="top" title="vs. previous 7 days" class="epic-tooltip tooltipclass" rel="tooltip">
       	  	       No change
       	  	    </sapn>
       	  	 </div>
       	  	</div>
       	  	<div class="col-md-1 col-sm-1">
       	  	 	<div class="math-symbole">+</div>
       	  	</div>
       	  	<div class="col-md-1 col-sm-1">
       	  	 <div>PACKAGES</div>
       	  	 <div class="number-style">$0</div>
       	  	 <div>
                 <sapn data-toggle="tooltip" data-placement="top" title="vs. previous 7 days" class="epic-tooltip tooltipclass" rel="tooltip">
       	  	       No change
       	  	    </sapn>
       	  	 </div>
       	  	</div>
       	  	<div class="col-md-1 col-sm-1">
       	  		<div class="math-symbole">-</div>
       	  	</div>
       	  	<div class="col-md-1 col-sm-1">
       	  	 <div>DISCOUNTS</div>
       	  	 <div class="number-style">$0</div>
       	  	 <div>
                 <sapn data-toggle="tooltip" data-placement="top" title="vs. previous 7 days" class="epic-tooltip tooltipclass" rel="tooltip">
       	  	       No change
       	  	    </sapn>
       	  	 </div>
       	  	</div>
       	  	<div class="col-md-1 col-sm-1">
       	  	 	<div class="math-symbole">=</div>
       	  	</div>
       	  	<div class="col-md-2 vr-line col-sm-2">
       	  	 <div>TOTAL SALES</div>
       	  	 <div class="number-style">${{ $last_7days_price }}</div>
       	  	 <div>
       	  	   @if($last_14days_price > 0)
	       	  	    <span data-toggle="tooltip" data-placement="top" title="vs. previous 7 days" class="epic-tooltip tooltipclass" rel="tooltip">
		       	  	   <font color="<?php echo $last_7days_price > $last_14days_price ? '#61C561':'#ff4401';?>">
			       	  	  <i class="fa fa-chevron-circle-down"></i>
			       	  	    ${{ $last_14days_price }}  
			       	   </font>	  
		       	  	 </span>
	       	  	@else
		       	  	 <sapn data-toggle="tooltip" data-placement="top" title="vs. previous 7 days" class="epic-tooltip tooltipclass" rel="tooltip">
       	  	             No change
       	  	         </sapn>
		       	@endif 
       	  	 </div>
       	  	</div>
       	  	<div class="col-md-2  col-sm-2">
       	  	 <div>AVERAGE SALE</div>
       	  	 <div class="number-style">${{ round($last7daysAverageSale,2) }}</div>
       	  	 <div>
       	  	 
	       	  	    <span data-toggle="tooltip" data-placement="top" title="vs. previous 7 days" class="epic-tooltip tooltipclass" rel="tooltip">
		       	  	   <font color="<?php echo $last_7days_price > $last_14days_price ? '#61C561':'#ff4401';?>">
			       	  	  <i class="fa fa-chevron-circle-down"></i>
			       	  	    ${{ round($averageSale,2) }}  
			       	   </font>	  
		       	  	 </span>
	       	
       	  	 </div>
       	  	</div>
	    </div>
	</div> 
</div>
</div>
</div>






