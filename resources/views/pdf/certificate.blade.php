<html>
    <head>
        <meta charset="utf-8">
    </head>

    <body style="position: relative; width: 100%; height: 100%;">

        <div style="
            position: absolute;
            top: {{ $positionY }}px;
            left: {{ $positionX }}px;
            font-size: {{ $fontSize }}px;
            color: {{ $fontColor }};
        ">
            {{ $name }}
        </div>
        @if ($logo)
            <img src="{{ $logo }}" style="
                position: absolute;
                top: 20px; /* Ajusta según sea necesario */
                left: 20px; /* Ajusta según sea necesario */
                width: 100px; /* Tamaño del logo */
            ">
        @endif
    </body>
</html>
