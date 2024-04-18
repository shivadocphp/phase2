  <table class="table table-bordered yajra-datatable" style="text-align: center;">
    <thead class="table-success">
        <tr>
            <th>#</th>
            <th>Category</th>
            <th>Gross Sal. Limit</th>
            <th>Basic</th>
            <th>HRA</th>
            <th>EPFO Employer</th>
            <th>EPFO Employee</th>
            <th>ESIC Employer</th>
            <th>ESIC Employee</th>
            <th>PT</th>
            <th>Manage</th>
        </tr>
    </thead>
    <tbody>
        @forelse($payroll_data as $key => $value)
            <tr class="text-center" style="text-align: center;">
                <td > {{ $key + $payroll_data->firstItem()}}</td>
                <td >{{ $value->category }}</td>
                <td >{{ $value->gross_sal_limit }}</td>
                <td >{{ $value->basic_perc }}</td>
                <td >{{ $value->hra_perc }}</td>
                <td >{{ $value->epfo_employer_perc }}</td>
                <td >{{ $value->epfo_employee_perc }}</td>
                <td >{{ $value->esic_employer_perc }}</td>
                <td >{{ $value->esic_employee_perc }}</td>
                <td >{{ $value->pt }}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('payroll.slab.edit', $value->id) }}" data-toggle="tooltip"  data-id="{{ $value->id }}" data-original-title="Edit" class="edit" data-cache="false" data-act_type="editUser"><i class="fas fa-edit" style="color: green" ></i></a>&nbsp;
                        <a href="#" data-endpoint="{{ route('payroll.slab.delete', $value->id) }}" data-async="true" data-toggle="tooltip"  data-id="{{ $value->id }}" data-original-title="Delete" data-act_type="deletePayroll" class="deletePayroll"><i class="fas fa-trash" style="color: red"></i></a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <th scope="row" colspan="10">No Data To List . . . </th>
            </tr>
        @endforelse
    </tbody>
</table>

<br>
<br>
    {!! $payroll_data->links() !!}