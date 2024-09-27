<div>
    <!-- Subir la imagen -->
    <input type="file" wire:model="image">

    @if ($image)
        <!-- Vista previa de la imagen subida -->
        <div class="relative" x-data="{ textX: @entangle('textX'), textY: @entangle('textY'), textColor: @entangle('textColor'), textSize: @entangle('textSize') }">
            <img src="{{ $image->temporaryUrl() }}" alt="Image" class="w-full h-auto">

            <!-- Campo de texto editable -->
            <div
                x-bind:style="'left: ' + textX + 'px; top: ' + textY + 'px; position: absolute; color: ' + textColor + '; font-size: ' + textSize + 'px;'"
                x-text="$wire.text"
                @mousedown.prevent="$el.addEventListener('mousemove', moveText)"
                @mouseup.prevent="$el.removeEventListener('mousemove', moveText)">
            </div>
        </div>

        <!-- Controles de personalización -->
        <div class="mt-4">
            <!-- Cambiar el texto -->
            <input type="text" wire:model="text" placeholder="Texto aquí">

            <!-- Cambiar el tamaño del texto -->
            <input type="range" wire:model="textSize" min="10" max="100">

            <!-- Cambiar el color del texto -->
            <input type="color" wire:model="textColor">

            <!-- Cambiar la posición del texto -->
            <label>X: <input type="number" wire:model="textX"></label>
            <label>Y: <input type="number" wire:model="textY"></label>
        </div>
    @endif

    <script>
        function moveText(event) {
            let textElement = event.target;
            textElement.style.left = event.clientX + 'px';
            textElement.style.top = event.clientY + 'px';
        }
    </script>
</div>
