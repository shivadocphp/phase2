@extends('admin.admin_master')
@section('admin')
<style>
#outer {
    width: 100%;
    text-align: center;
}

.inner {
    display: inline-block;
}
</style>
<div class="page-header">
    <h3 class="page-title"> Edit Client {{ $client->client_code }}: Address</h3>

</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <a href="{{ route('edit.client',$client->id) }}" class="btn btn-med btn-primary" disabled>Basic Details</a>
            <a href="{{ route('edit.clientaddress',$client->id) }}" class="btn btn-med btn-new-full"
                style="color: orangered;">Address Details</a>
            <a href="{{ route('edit.clientofficial',$client->id) }}" class="btn btn-med btn-primary">Official
                Details</a>
            <a href="{{ route('edit.clientagreement',$client->id) }}" class="btn btn-med btn-primary">Agreement</a>
            <a href="{{ route('edit.clientrequirement',$client->id) }}" class="btn btn-med btn-primary">Requirements</a>
        </div>
        <div class="col-md-4" align="right"><a href="{{ route('all.client') }}" class="btn btn-med btn-primary">View
                Clients</a>
        </div>
    </div>
    <div>
        @if(session('success'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session('error') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
    </div>
    <div class="col-md-14">
        <div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-14 col-sm-14">
                        <table class="table-bordered">
                            <thead>
                                <tr style="text-align: center">
                                    <th class="col-md-2">Address</th>
                                    <th class="col-md-1">State</th>
                                    <th class="col-md-1">City</th>
                                    <th class="col-md-1">Country</th>
                                    <th class="col-md-1">Pincode</th>
                                    <th class="col-md-2">Started Year & Month</th>
                                    <th class="col-md-2">GST</th>
                                    <th class="col-md-1">Type</th>
                                    <th class="col-md-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <form action="{{ route('store.client_address') }}" method="POST">
                                    @csrf
                                    <tr>
                                        <input type="hidden" name="client_id" value="{{ $client->id }}">
                                        <td><textarea name="address" class="form-control" required></textarea></td>
                                        <td><select name="state_id" class="form-control" id="state">
                                                <option value="">-Select-</option>
                                                @foreach($state as $st)
                                                <option value="{{ $st->id }}">{{$st->state}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><select name="city_id" class="form-control" id="city">
                                            </select>
                                        </td>
                                        <td><select name="country_id" class="form-control" id="country">
                                                @foreach($country as $c)
                                                <option value="{{ $c->id }}" <?php if ($c->country == "India") {
                                                        echo "selected";
                                                    }?>>{{$c->country}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="pincode" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" class="form-control">
                                        </td>
                                        <td><input type="text" name="start_mon_year" class="form-control" placeholder="Jan 1996"></td>
                                        <td><input type="text" name="gst" class="form-control"></td>
                                        <td><select name="address_type" class="form-control">
                                                <option value="Branch Office">Branch</option>
                                                <option value="Head Office">Head</option>
                                            </select>
                                        </td>
                                        <td style="text-align: center;">
                                            <button type="submit" class="btn btn-sm btn-primary" value="save_draft_next" name="save" title="Save">
                                                <icon class="fa fa-save"></icon>
                                            </button>
                                        </td>
                                    </tr>
                                </form>
                                <?php if($i == 1){
                                ?>
                                <input type="hidden" id="count" value="{{ count($client_address) }}">
                                @for($k=0;$k<count($client_address);$k++) 
                                <form action="{{ route('update.client_address',$client_address[$k]['id']) }}" method="POST">
                                    {{method_field('patch')}}
                                    @csrf
                                    <tr>
                                        <input type="hidden" name="loopid" value="{{$k}}">
                                        <input type="hidden" name="client_id"
                                            value="{{$client_address[$k]['client_id'] }}" id="client_id{{$k}}">
                                        <td><textarea name="address"
                                                class="form-control">{{ $client_address[$k]['address'] }}</textarea>
                                        </td>
                                        <td><select name="state" class="form-control state" id="state{{$k}}"
                                                key="{{$k}}" keycity="{{$client_address[$k]['city_id']}}">
                                                <option value="">-Select-</option>
                                                @foreach($state as $st)
                                                <option value="{{ $st->id }}" <?php if ($st->id == $client_address[$k]['state_id']) {
                                                            echo "selected";
                                                        }?>>{{$st->state}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><select name="city" class="form-control city" id="city{{$k}}">

                                                <?php  $cities = \Illuminate\Support\Facades\DB::table('cities')->where('state_id',$client_address[$k]['state_id'])->get();
                                                foreach($cities as $c){ ?>
                                                <option value="{{ $c->id }}"
                                                    <?php if($c->id==$client_address[$k]['city_id']){ echo "selected";} ?>>
                                                    {{ $c->city }}</option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td><select name="country_id" class="form-control" id="country_id">
                                                <option value="">--Select Country--</option>
                                                @foreach($country as $c)
                                                <option value="{{ $c->id }}" <?php if ($client_address[$k]['country_id'] == $c->id) {
                                                            echo "selected";
                                                        }?>>{{$c->country}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" name="pincode" class="form-control"
                                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                maxlength="6" value="{{$client_address[$k]['pincode']}}"></td>
                                        <td><input type="text" name="start_mon_year" class="form-control"
                                                value="{{$client_address[$k]['start_mon_year']}}"></td>
                                        <td><input type="text" name="gst" class="form-control"
                                                value="{{$client_address[$k]['gst']}}"></td>
                                        <td><select name="address_type" class="form-control">
                                                <option value="Branch Office" <?php if ($client_address[$k]['address_type'] == "Branch Office") {
                                                        echo "selected";
                                                    }?>>Branch
                                                </option>
                                                <option value="Head Office" <?php if ($client_address[$k]['address_type'] == "Head Office") {
                                                        echo "selected";
                                                    }?>>Head
                                                </option>
                                            </select>
                                        </td>
                                        <td style="text-align: center;">
                                            <div id="outer">
                                                <div class="inner">
                                                    <button type="submit" class="btn btn-sm btn-info"
                                                        value="save_draft_next" name="edit" title="Update">
                                                        <icon class="fa fa-edit"></icon>
                                                    </button>
                                                </div>
                                                <div class="inner">
                                                    <button type="button" class="btn btn-sm btn-danger" value="save_draft_next" name="delete" title="Delete">
                                                        <a href="{{ route('delete.clientaddress',$client_address[$k]['id']) }}" style="color:white">
                                                            <icon class="fa fa-trash"></icon>
                                                        </a></button>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </form>
                                    @endfor
                                    <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#state').on('change', function() {
        var idlevel = this.value;
        $("#city").html('');
        $.ajax({
            url: "{{url('api/fetch-city')}}",
            type: "POST",
            data: {
                state_id: idlevel,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
                $('#city').html('<option value="">-Select-</option>');
                $.each(result.city, function(key, value) {
                    $("#city").append('<option value="' + value.id + '">' + value.city + '</option>');
                });
                //$('#city-dd').html('<option value="">Select City</option>');
            }
        });
    });
    $(".state").click(function(e) {
        var val = $(this).val();
        var key = $(this).attr('key');
        var cityval = $(this).attr('keycity');
        $("#city" + key).html('');
        if (val != '' && val != undefined) {
            $.ajax({
                url: "{{url('api/fetch-city')}}",
                type: "POST",
                data: {
                    state_id: val,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    var html = '';
                    html += '<option value="">-Select-</option>';
                    $.each(result.city, function(key, value) {
                        html += '<option value="' + value

                            .id + '"  >' + value.city + '</option>';
                    });
                    console.log(html);
                    $("#city" + key).html(html);

                }

            });
        }
        e.preventDefault();
    });
});
</script>
@endsection