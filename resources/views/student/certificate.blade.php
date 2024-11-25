<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Achievement</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: url('path/to/background-image.jpg') no-repeat center;
            background-size: cover;
            padding: 50px;
        }

        .certificate-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 50px;
            border: 5px solid #133E87;
            border-radius: 10px;
            max-width: 800px;

            margin: auto;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
        }

        .certificate-title {
            font-size: 36px;
            color: #133E87;
            font-weight: bold;
        }

        .certificate-body {
            font-size: 18px;
            color: #555;
        }

        .certificate-name {
            font-size: 28px;
            font-weight: bold;
            color: #000;
            margin: 20px 0;
        }

        .certificate-program {
            font-size: 22px;
            font-weight: bold;
            color: #133E87;
        }

        .certificate-score {
            font-size: 24px;
            font-weight: bold;
            color: #28A745;
            margin-top: 10px;
        }

        .certificate-footer {
            margin-top: 30px;
            font-size: 14px;
            color: #777;
        }
        #table-element{
            width: 100%;

        }
        /* #table-element thead tr th{
            border-left: 1px black solid;
            border-right: 1px black solid;
        } */
        .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            overflow: hidden;
        }

        .card-header {
            background-color: #133E87;
            color: #fff;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }

        .card-body {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        thead {
            background-color: #608BC1;
            color: #fff;
        }

        th, td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }

        tbody tr:nth-child(even) {
            background-color: #f3f3e0;
        }

        tbody tr:hover {
            background-color: #e8e8e8;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            font-size: 14px;
            border-radius: 5px;
            color: #fff;
            font-weight: bold;
        }

        .bg-success {
            background-color: #28a745;
        }

        .bg-danger {
            background-color: #dc3545;
        }
    </style>
</head>

<body>
    <div class="certificate-container text-center">
        <h1 class="certificate-title">Certificate of Achievement</h1>
        <p class="certificate-body">This is to certify that</p>
        <div class="certificate-name">{{ $name }}</div>
        <p class="certificate-body">has successfully completed</p>
        <p class="certificate-program">{{ $program }}</p>
        <p class="certificate-body">with a final score of</p>
        <p class="certificate-score">{{ $final_score }}</p>
        <p class="certificate-body">on {{ $date }}</p>

    </div>
    <div class="certificate-container text-center">
        <div class="card">
            <div class="card-header">
                Detail Competency Element
            </div>
            <div class="card-body">
                <table id="table-element">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Criteria</th>
                            <th>Competency Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Example data -->
                        @foreach ($elements as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->competency_element->criteria }}</td>
                            <td>
                                <span class="badge {{ $item->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $item->status == 1 ? 'Competent' : 'Not Competent' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <!-- Bootstrap JS (optional for interactive elements) -->
    <script src="{{ asset('bootstrap/js/bootstrap.js') }}"></script>
</body>

</html>
