<div>
    <!-- Subir la imagen -->
    <input type="file" wire:model="image">

    @if ($image)
        <!-- Vista previa de la imagen subida -->
        <div class="relative" x-data="dragText()" style="width: 100%; height: auto;">
            <img src="{{ $image->temporaryUrl() }}" alt="Image" class="w-full h-auto">

            <!-- Campo de texto editable y arrastrable -->
            <div
                x-bind:style="'left: ' + textX + 'px; top: ' + textY + 'px; position: absolute; color: ' + textColor + '; font-size: ' + textSize + 'px;'"
                x-text="$wire.text"
                @mousedown="startDrag"
                @mousemove="drag($event)"
                @mouseup="stopDrag"
                @mouseleave="stopDrag"
                class="cursor-move select-none"
                style="cursor: move;">
            </div>
        </div>

        <!-- Controles de personalización -->
        <div class="mt-4" x-data="dragText()">
            <!-- Cambiar el texto -->
            <input type="text" wire:model="text" placeholder="Texto aquí">

            <!-- Cambiar el tamaño del texto -->
            <input type="range" wire:model="textSize" min="10" max="100" x-model="textSize"> <!-- Añadido x-model para Alpine.js -->

            <!-- Cambiar el color del texto -->
            <input type="color" wire:model="textColor" x-model="textColor"> <!-- Añadido x-model para Alpine.js -->

            <!-- Cambiar la posición del texto mediante inputs -->
            <label>X: <input type="number" x-model="textX"></label> <!-- Usamos x-model para enlazar con Alpine.js -->
            <label>Y: <input type="number" x-model="textY"></label> <!-- Usamos x-model para enlazar con Alpine.js -->
        </div>

        <div>
            <label>Datos: {{ $textColor }}, {{ $textSize }}, {{ $textX }}, {{ $textY }}</label>
        </div>
    @endif

    <script>
        function dragText() {
            return {
                dragging: false,
                textX: @entangle('textX'), // Sincronizar Alpine.js con Livewire
                textY: @entangle('textY'),
                textSize: @entangle('textSize') || 16, // Sincronizar tamaño del texto y establecer valor predeterminado
                textColor: @entangle('textColor') || '#000000', // Sincronizar color del texto y establecer valor predeterminado
                startX: 0,
                startY: 0,

                // Iniciar el arrastre
                startDrag(event) {
                    this.dragging = true;
                    this.startX = event.clientX - this.textX;
                    this.startY = event.clientY - this.textY;
                },

                // Mover el texto
                drag(event) {
                    if (this.dragging) {
                        this.textX = event.clientX - this.startX;
                        this.textY = event.clientY - this.startY;
                    }
                },

                // Soltar el texto
                stopDrag() {
                    this.dragging = false;
                }
            }
        }
    </script>
</div>
