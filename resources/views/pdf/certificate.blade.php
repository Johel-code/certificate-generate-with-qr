<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }
        .certificate-container {
            position: relative;
            width: 100%;
            height: 100vh;
        }
        .certificate-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .certificate-text {
            position: absolute;
            left: {{ $textX }}px;
            top: {{ $textY }}px;
            font-size: {{ $textSize }}px;
            color: {{ $textColor }};
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <img src="{{ public_path('storage/' . $image) }}" alt="Certificado" class="certificate-image">
        <div class="certificate-text">{{ $text }}</div>
    </div>
</body>
</html>
