<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>志愿服务证明</title>
    <style>
        @font-face {
            font-family: 'SimSun';
            font-style: normal;
            font-weight: normal;
        }

        body {
            font-family: sans-serif,'SimSun';
            padding: 20px;
        }
        .border-container {
            border: 5px double #333;
            padding: 40px;
            margin-bottom: 30px;
            position: relative;
            
        }
        /* 移除固定高度，让内容自动撑开 */
        .chinese-section {
            page-break-after: always; /* 中文部分后强制分页 */
        }
        .english-section {
            /* 移除固定高度 */
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
            margin-bottom: 60px; /* 为footer留出空间 */
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
            position: relative; /* 改为相对定位 */
            text-align: right;
            margin-top: 40px;
            padding-top: 20px;
        }

        .stamp {
            margin-top: 10px;
            font-weight: bold;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <!-- 中文部分 -->
    <div class="border-container chinese-section">
        <!-- 中文内容保持不变 -->
        <div class="header">
            <div class="title">志愿服务荣誉证书</div>
            <div class="subtitle">志愿服务证明</div>
        </div>

        <div class="content">
            <p>尊敬的志愿者：<strong>{{ $user->name }}</strong></p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;感谢您积极参与校园公益活动。截至<strong>{{ $date }}</strong>，您累计参与志愿服务活动<strong>{{ $registrations->count() }}</strong>次，总服务时长为<strong>{{ $totalHours }}</strong>小时。</p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;特发此证，以资鼓励。</p>
        </div>

        <h4 style="text-align: center">服务记录详情：</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>活动名称</th>
                    <th>日期</th>
                    <th>地点</th>
                    <th>服务时长（小时）</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $reg)
                <tr>
                    <td>{{ $reg->activity->title }}</td>
                    <td>{{ $reg->activity->start_time->format('Y-m-d') }}</td>
                    <td>{{ $reg->activity->location }}</td>
                    <td>{{ number_format($reg->activity->end_time->floatDiffInHours($reg->activity->start_time), 2, '.', '') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>签发单位：校园志愿服务中心</p>
            <p>{{ $date }}</p>
        </div>
    </div>

    <!-- 英文部分 -->
    <div class="border-container english-section">
        <!-- 英文内容保持不变 -->
        <div class="header">
            <div class="title">CERTIFICATE OF HONOR</div>
            <div class="subtitle">Volunteer Service Certificate</div>
        </div>

        <div class="content">
            <p>Honored Volunteer: <strong>{{ $user->name }}</strong></p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;We appreciate your active participation in campus public welfare activities. As of <strong>{{ $date }}</strong>, you have participated in a cumulative total of <strong>{{ $registrations->count() }}</strong> volunteer service activities, with a total service duration of <strong>{{ $totalHours }}</strong> hours.</p>
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
                    <td>{{ number_format($reg->activity->end_time->floatDiffInHours($reg->activity->start_time), 2, '.', '') }}</td>
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
