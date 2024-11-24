<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Achievement</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <div class="certificate-footer">
            <p>Issued by Creative Spark Agency</p>
            <p>www.creativespark.com</p>
        </div>
    </div>

    <!-- Bootstrap JS (optional for interactive elements) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
