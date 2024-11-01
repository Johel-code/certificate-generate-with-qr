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
            opacity: {{ $data['opacity'] }};
            object-fit: cover;
        }
        .text-area {
            position: absolute;
            left: {{ $data['customTextX'] }}px;
            top: {{ $data['customTextY'] }}px;
            width: {{ $data['customTextWidth'] }}px;
            height: {{ $data['customTextHeight'] }}px;
            font-size: {{ $data['customTextSize'] }}px;
            color: {{ $data['customTextColor'] }};
            font-family: {{ $data['customFontFamily'] }};
            text-align: {{ $data['alignment'] }};
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <img src="{{ public_path('storage/' . $data['image']) }}" alt="Certificado" class="certificate-image">
        @foreach($data['fieldsConfigurations'] as $value)
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
        <div class="text-area">
            {!! $data['customText'] !!}
        </div>
    </div>
</body>
</html>
