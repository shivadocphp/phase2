<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave status update Notification</title>
    <style>
        /* Style for the card */
        .card {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        /* Style for the card header */
        .card-header {
            background-color: #f0f0f0;
            padding: 10px 20px;
            border-radius: 8px 8px 0 0;
        }
        /* Style for the card title */
        .card-title {
            color: #333333;
            font-size: 24px;
            margin: 0;
        }
        /* Style for the card body */
        .card-body {
            padding: 20px 0;
        }
        /* Style for the paragraphs */
        .card-body p {
            color: #333333;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- Card container -->
    <div class="card">
        <!-- Card header -->
        <div class="card-header">
            <h2 class="card-title">Leave status update Notification</h2>
        </div>
        <!-- Card body -->
        <div class="card-body">
            <img src="{{ url('backend/assets/images/brand/jsc_logo.png') }}" alt="Company Logo" style="max-width: 100%; display: block; margin: 0 auto;">
            <p>Dear {{$name}},</p>
            <p>This is to inform you that your Applied leave is cancelled.</p>
            <p>Employee code: {{$emp_code}}</p>
            <p>Employee shift: {{$shift_id}}</p>
            <p>Leave Date: {{$approved_from}} - {{$approved_to}}</p>
            <p>Leave Type: {{$leave_type_name->leavetype}}</p>
            <p>Total Days Req.: {{$requested_days}}</p>
            <p>Total Days App.: {{$approved_days}}</p>
            <p>Leave Status: {{$leave_status}}</p>
            <p>Comments: {{$comments}}</p>
            <p>Please ensure to adhere to the company's attendance policies in the future.</p>
            <p>Thank you,</p>
            <p>[Your Company Name]</p>
        </div>
    </div>
</body>
</html>
