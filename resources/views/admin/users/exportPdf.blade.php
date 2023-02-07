<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title> PDF</title>
    <style>
        @font-face {
            font-family: "ipaexm";
            font-style: normal;
            font-weight: 300;
            src: url("font/ipaexg.ttf");
            font-display: swap;
        }
        body {
            font-family: ipaexm;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #04AA6D;
            color: white;
        }
        
    </style>
</head>
<body>
    <h2>{{ $title }}</h2>
    <p>{{ $date }}</p>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
        </tr>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone }}</td>
            <td>{{ $user->address }}</td>
        </tr>
        @endforeach
    </table>
  
</body>
</html> 