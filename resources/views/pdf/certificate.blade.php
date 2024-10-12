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
        .qr-code {
            position: absolute;
            bottom: 20px;
            right: 20px;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <img src="{{ public_path('storage/' . $data['data']['image']) }}" alt="Certificado" class="certificate-image">
        @foreach($data['data']['fieldsConfigurations'] as $value)
            <div style="
                position: absolute;
                left: {{ $value['textX'] }}px;
                top: {{ $value['textY'] }}px;
                font-size: {{ $value['textSize'] }}px;
                color: {{ $value['textColor'] }};
                font-family: '{{ $value['fontFamily'] }}', sans-serif;">
                {{ $value['text'] }}
            </div>
        @endforeach
        <div class="qr-code">
            <img src="data:image/png;base64, {{ $data['data']['qrCode'] }}" alt="QR Code">
        </div>
    </div>
</body>
</html>
