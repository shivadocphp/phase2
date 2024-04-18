<div class="modal-header">
    <h4 class="modal-title" id="modelHeading">Edit History</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
	<div class="table-responsive">
	    <table class="table table-bordered table-sm table-striped" style="text-align: center;">
		    <thead>
		        <tr>
		            <th>#</th>
		            <th>Employment Mode</th>
		            <th>User</th>
		            <th>Type</th>
	              	<th>Done Time</th>
		        </tr>
		    </thead>
		    <tbody>
		    	@php $i=0; @endphp
		        @forelse($employmentmode_logs as $key => $value)
		            <tr>
		                <td style="text-align: center;"> {{ ++$i}}</td>
		                <td style="text-align: center;">{{ $value->employmentmode }}</td>
		                <td style="text-align: center;">@isset($value->user) {{ $value->user->name }} @endisset</td>
		                <td style="text-align: center;">{{ $value->type }}</td>
		                <td style="text-align: center;"> {{ $value->created_at->format('d-m-Y H:i:s') }}</td>
		                  
		            </tr>
		        @empty
		        <tr>
		            <th scope="row" colspan="9">No Data To List . . . </th>
		        </tr>
		        @endforelse
		    </tbody>
		</table>
	</div>
</div>
<div class="modal-footer ">
    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
</div>