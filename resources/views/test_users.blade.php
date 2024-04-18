<!DOCTYPE html>
<html>

<head>
    <title>User DataTable</title>
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css"> -->

    <!-- Include DataTables Buttons CSS from CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.1/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.0/css/buttons.dataTables.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">


    <!-- <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
     <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet"> -->



</head>

<body>
    <!-- <a href="{{ route('export.users') }}" class="btn btn-primary">Export Users</a> -->
    <button type="button" class="btn btn-new" id="export-excel-button" title="Export">Export Users</button>


    <div class="card-body">
        <div class="col-md-14">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-4">
                        <select class="form-control js-example-basic-single" name="user_id" id="user_id">
                            <option value="">--Select Company</option>
                            <option value="all">All</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="button" class="btn btn-new" id="search" title="Search Invoice">Filter</button>
                        <button type="button" class="btn btn-new" id="reset" title="Reset Filter">Reset</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <table id="users-table" class="display nowrap">
        <thead style="background-color: #ff751a">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
    </table>


    <!-- <table id="example" class="display nowrap" style="width:100%">
    <thead>
        <tr>
            <th>Name</th>
            <th>Age</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>John Doe</td>
            <td>30</td>
            <td>john@example.com</td>
        </tr>
    </tbody>
</table> -->

    <script src="https://cdn.datatables.net/buttons/2.1.2/js/buttons.server-side.min.js"></script>


    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.0.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.0/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.print.min.js"></script>


    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script> -->


    <script>
        $(document).ready(function() {
            fill_datatable();

            function fill_datatable(user_id = '') {
                $('#users-table').DataTable({
                    processing: true,
                    // serverSide: true,
                    responsive: true,
                    deferRender: true,
                    // ajax: "{{ route('test_users.index') }}",


                    ajax: {
                        url: "{{ route('test_users_list.index') }}",
                        data: {
                            user_id: user_id,
                        }
                    },

                    //             layout: {
                    //     topStart: {
                    //         buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    //     }
                    // },

                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        // {
                        //     data: 'id',
                        //     name: 'id'
                        // },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'updated_at',
                            name: 'updated_at'
                        },
                        // {
                        // data: 'action',
                        // name: 'action',
                        //     orderable: true,
                        //     searchable: true
                        // },
                    ],
                    language: {
                        emptyTable: "No data available in the table"
                    },
                    initComplete: function() {
                        if (table.rows().count() === 0) {
                            $('#export-excel-button').hide(); // Hide the export button
                        }
                    }
                });
            }


            $('#search').click(function() {
                var user_id = $('#user_id').val();
                if (user_id != '') {
                    $('#users-table').DataTable().destroy();
                    fill_datatable(user_id);
                } else {
                    alert("Select all filter options");
                }
            });

            $('#reset').click(function() {
                $('#user_id').val('');
                $('#users-table').DataTable().destroy();
                fill_datatable();
            });

            $('#export-excel-button').click(function() {
                // var export = $('#export-excel-button').val();
                var user_id = $('#user_id').val();
                // var url = "{{ route('export.users') }}";          // if to not add flters
                var url = "{{ route('test_users_list.index') }}"; // if to add flters
                url += "?export=export";
                if (user_id !== '') {
                    url += "&user_id=" + user_id;
                }
                window.location.href = url;
            });

        });
    </script>





    <!-- to use default button -->
    <script type="text/javascript">
        //     $(document).ready(function() {
        //     $('#example').DataTable({
        //         processing: true,
        //             // serverSide: true,
        //         // dom: 'Bfrtip',
        //         // buttons: ['excel', 'csv', 'pdf']
        //         // dom: 'Bfrtip',
        //         //   buttons: [
        //         //         { extend: 'excelHtml5', text: 'Download' }
        //         //   ],

        //to get the default button
        //         layout: {
        //         topStart: {
        //             buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        //         }
        //     }

        //     });
        // });
    </script>

</body>

</html>