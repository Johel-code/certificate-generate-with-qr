<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado de {{ $user }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="w-full max-w-2xl p-6 bg-white rounded-lg shadow-lg">
        <h1 class="mb-4 text-2xl font-bold text-center">Certificado para {{ $user }}</h1>

        <div class="flex justify-center">
            <!-- Aquí podrías incluir la imagen del certificado o cualquier información adicional -->
            <embed src="{{ asset('storage/certificates/' . $user . '.pdf') }}" type="application/pdf" width="100%" height="600px" />
        </div>

        {{-- <div class="flex justify-center mt-4">
            <img src="data:image/png;base64, {{ $qrCode }}" alt="QR Code" class="w-32 h-32">
        </div> --}}

        <div class="mt-4 text-center">
            <a href="{{ asset('storage/certificates/' . $user . '.pdf') }}" target="_blank" class="text-blue-500 hover:underline">Descargar Certificado</a>
        </div>
    </div>
</body>
</html>
