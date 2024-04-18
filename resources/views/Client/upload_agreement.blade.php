@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Edit Client {{ $client->client_code }}: Upload Agreeement</h3>

</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <a href="{{ route('edit.client',$client->id) }}" class="btn btn-med btn-warning" disabled>Basic Details</a>
            <a href="{{ route('edit.clientaddress',$client->id) }}" class="btn btn-med btn-warning">Address
                Details</a>
            <a href="{{ route('edit.clientofficial',$client->id) }}" class="btn btn-med btn-warning">Official
                Details</a>
            <a href="{{ route('edit.clientagreement',$client->id) }}" class="btn btn-med btn-primary"
                style="color: orangered;">Agreement</a>
            <a href="{{ route('edit.clientrequirement',$client->id) }}" class="btn btn-med btn-warning">Requirements</a>
        </div>
        <div class="col-md-4" align="right"><a href="{{ route('all.client') }}" class="btn btn-med btn-success">View
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
    <div c;lass="row">
        <div class="col-md-14">
            <table class="table table-striped">
                <tr>
                    <td>
                        <form action="{{ route('update.client_agreement',$client->id) }}" method="POST"
                            enctype="multipart/form-data">
                            {{method_field('patch')}}
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        Upload Agreement
                                        <input type="file" class="form-control" required name="file" id="myPdf">
                                        @error('file')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <br>
                                        <button type="submit" class="btn btn-primary" value="save_draft_next"
                                            name="save_draft" title="Save">Upload
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <?php
                            if($client_off != null){ 
                                foreach($client_off as $cf){ ?>
                                    <div class="row">
                                        <div class="col-md-11">
                                            <embed src="{{ Storage::url($cf->agreement) }}" width="100%" height="300" />
                                        </div>
                                        <div class="col-md-1">
                                            <a href="{{route('client.delete_agreement',$cf->id)}}" style="color: red"
                                                title="Delete Agreement">
                                                <i class="fa fa-trash" style="color: red"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <br>
                                <?php } 
                            }else{?><div class="row">
                                        <h2>No agreement added yet </h2>
                                    </div>
                            <?php } ?>
                        <div class="col-md=12">
                            <canvas id="pdfViewer"></canvas>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<!-- </td>
</tr>
</table> -->

</div>
</div>
<script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
<script type="text/javascript">
var pdfjsLib = window['pdfjs-dist/build/pdf'];
// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';

$("#myPdf").on("change", function(e) {
    var file = e.target.files[0]
    if (file.type == "application/pdf") {
        var fileReader = new FileReader();
        fileReader.onload = function() {
            var pdfData = new Uint8Array(this.result);
            // Using DocumentInitParameters object to load binary data.
            var loadingTask = pdfjsLib.getDocument({
                data: pdfData
            });
            loadingTask.promise.then(function(pdf) {
                console.log('PDF loaded');

                // Fetch the first page
                var pageNumber = 1;
                pdf.getPage(pageNumber).then(function(page) {
                    console.log('Page loaded');

                    var scale = 1.5;
                    var viewport = page.getViewport({
                        scale: scale
                    });

                    // Prepare canvas using PDF page dimensions
                    var canvas = $("#pdfViewer")[0];
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function() {
                        console.log('Page rendered');
                    });
                });
            }, function(reason) {
                // PDF loading error
                console.error(reason);
            });
        };
        fileReader.readAsArrayBuffer(file);
    }
});
</script>
@endsection