<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>志愿服务证明</title>
    <style>
        @font-face {
            font-family: 'Firefly Sung';
            font-style: normal;
            font-weight: normal;
        }

        body {
            font-family: sans-serif;
            padding: 20px;
        }

        .border-container {
            border: 5px double #333;
            padding: 40px;
            height: 900px;
            position: relative;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .title {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 18px;
            color: #555;
        }

        .content {
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 30px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th, .table td {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
            font-size: 14px;
        }

        .table th {
            background-color: #f0f0f0;
        }

        .footer {
            position: absolute;
            bottom: 60px;
            right: 60px;
            text-align: right;
        }

        .stamp {
            margin-top: 10px;
            font-weight: bold;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="border-container">
        <div class="header">
            <div class="title">CERTIFICATE OF HONOR</div>
            <div class="subtitle">Volunteer Service Certificate</div>
        </div>

        <div class="content">
            <p>Honored Volunteer: <strong>{{ $user->name }}</strong></p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;We appreciate your active participation in the campus public welfare activities.As of <strong>{{ $date }}</strong>, you have  participated in a cumulative total of <strong>{{ $registrations->count() }}</strong> volunteer service activities, with a total service duration of <strong>{{ $totalHours }}</strong> hours.</p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;This certificate is hereby granted as a token of recognition and encouragement.</p>
        </div>

        <h4 style="text-align: center">Detailed Service Record: </h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Activity Name</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Service Hours</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $reg)
                <tr>
                    <td>{{ $reg->activity->title }}</td>
                    <td>{{ $reg->activity->start_time->format('Y-m-d') }}</td>
                    <td>{{ $reg->activity->location }}</td>
                    <td>{{ $reg->activity->end_time->floatDiffInHours($reg->activity->start_time) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>Issued by: Campus Volunteer Service Center</p>
            <p>{{ $date }}</p>
        </div>
    </div>
</body>
</html>