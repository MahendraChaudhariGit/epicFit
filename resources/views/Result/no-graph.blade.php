
<div class="row pos-of-acc pos-of-acc1 pos-of-accc" style="">

	<!-- Start: Helath section -->
    <div class="col-md-12 col-sm-12" id="health-section-row">
		<div class="panel panel-white no-radius load1 panel-white-me" id="visits">
			
			<div collapse="visits" class="panel-wrapper">
                <div class="panel-body" style="padding: 10%">
                    <center><img src="{{ asset('no-graph.png') }}" alt="no-graph" height="100" width="100" srcset=""></center>
                    <center><h4>There are no stats to graph right now.</h4></center>
                    <center><h6>Try changing the timeframe, or adding new stats.</h6></center>
				</div>
			</div>

		</div>
	</div>
	<!-- End: Helath section -->

</div>
<script>
    $(".filterGraph").attr('month','{{ $pastMonth }}');
	$(".view-calendar").html('{{ $viewCalendar }}')
</script>