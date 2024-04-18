
    <table class="table table-bordered">
        <thead>
            <tr style="background-color: #ff751a">
                <th style="font-weight: bold;">No</th>
                <th style="font-weight: bold;">Name</th>
                <th style="font-weight: bold;">Mobile</th>
                <th style="font-weight: bold;">Whatsapp</th>
                <th style="font-weight: bold;">Email</th>
                <th style="font-weight: bold;">Skills</th>
                <th style="font-weight: bold;">Callback Date</th>
                <th style="font-weight: bold;">Callback Time</th>
                <th  style="font-weight: bold;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($candidates_data as $key => $row)
                @php 
                $skill_array = ''; 
                $candidate_name = isset($row->candidate_basic_detail) ? $row->candidate_basic_detail->candidate_name : '';
                @endphp
                @isset($row->candidate_basic_detail)
                    @php
                        $skill = json_decode($row->candidate_basic_detail->skills);
                        $skill_names = array();
                        foreach($skills as $s){
                            if(in_array($s->id, $skill)){
                                $skill_names[] = $s->skill;
                            }
                        }
                        $skill_array = implode(',', $skill_names);
                    @endphp
                    
                @endisset
                <tr>
                    <td style="text-align: center;"> {{ $key + $candidates_data->firstItem()}}</td>
                    <td style="text-align: center;"> @isset($row->candidate_basic_detail) {{ $row->candidate_basic_detail->candidate_name }} @endisset</td>
                    <td style="text-align: center;"> @isset($row->candidate_basic_detail) {{ $row->candidate_basic_detail->contact_no }} @endisset</td>
                    <td style="text-align: center;"> @isset($row->candidate_basic_detail) {{ $row->candidate_basic_detail->whatsapp_no }} @endisset</td>
                    <td style="text-align: center;"> @isset($row->candidate_basic_detail) {{ $row->candidate_basic_detail->candidate_email }} @endisset</td>
                    <td style="text-align: center;"> {{ $skill_array }} </td>
                    <td style="text-align: center;"> {{ $row->call_back_date }}</td>
                    <td style="text-align: center;"> {{ $row->call_back_time }}</td>
                    <td style="text-align: center;"><a href="{{ route('edit_detail.candidate', [$row->candidate_id, $candidate_name]) }}" title="Click here to view the positions the candidate is processed for"><i class="si si-docs"></i></a></td>
                    {{-- <td style="text-align: center;"> {{ $row->user->name }}</td> --}}
                        
                </tr>
            @empty
            <tr>
                <th scope="row" colspan="6">No Data To List . . . </th>
            </tr>
            @endforelse
        </tbody>
    </table>

<br>
<br>
    {!! $candidates_data->links() !!}