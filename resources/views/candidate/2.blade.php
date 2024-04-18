<html>

<head>

    <title>How to use select2 for multiple select in laravel 8</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    {{-- selec2 cdn --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body>

<div class="container mt-5">

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" class="form-control" name="title" placeholder="title">
        </div>
        <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Description</label>
            <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3"></textarea>
        </div>

        <div class="form-group mb-3">
            <label for="select2Multiple">Multiple Tags</label>
            <select class="select2-multiple form-control" name="tags[]" multiple="multiple"
                    id="select2Multiple">
                <option value="tag1">tag1</option>
                <option value="tag2">tag2</option>
                <option value="tag3">tag3</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">submit</button>

</div>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Select2 Multiple
        $('.select2-multiple').select2({
            placeholder: "Select",
            allowClear: true
        });

    });

</script>
</body>


</html>
