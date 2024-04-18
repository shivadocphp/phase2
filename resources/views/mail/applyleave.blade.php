<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply Leave Notification</title>
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
            <h2 class="card-title">Apply Leave Notification</h2>
        </div>
        <!-- Card body -->
        <div class="card-body">
            <img src="{{ url('backend/assets/images/brand/jsc_logo.png') }}" alt="Company Logo" style="max-width: 100%; display: block; margin: 0 auto;">
            <p>Dear {{ $emp_name }},</p>
            <p>This is to inform you that you Applied a leave successfully.</p>
            <p>Employee code: {{ $emp_code }}</p>
            <p>Employee shift: {{ $shift_id }}</p>
            <p>Leave Date: {{ $required_from }}</p>
            <p>Leave Type: {{ $leave_type_name }}</p>
            <p>Total Days: {{ $requested_days }}</p>
            <p>Leave Status: {{ $leave_status }}</p>
            <p>Reason: {{ $reason }}</p>
            <p>Please ensure to adhere to the company's attendance policies in the future.</p>
            <p>Thank you,</p>
            <p>[Your Company Name]</p>
        </div>
    </div>
</body>
</html>
