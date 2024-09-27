<div class="flex">
    <!-- Controles a la izquierda -->
    <div class="w-1/3 p-4 bg-gray-100" x-data="dragText()">

        <form wire:submit='save'>

            <label class="block text-sm font-medium text-gray-700">Subir la imagen del certificado</label>
            <input type="file" wire:model="image" class="block w-full mt-2 mb-4 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">

            <!-- Cambiar el texto -->
            <label class="block text-sm font-medium text-gray-700">Texto del certificado</label>
            <input type="text" wire:model="text" placeholder="Texto aquí" class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

            <!-- Cambiar el tamaño del texto -->
            <label class="block mt-4 text-sm font-medium text-gray-700">Tamaño del texto</label>
            <input type="range" wire:model="textSize" min="10" max="100" class="block w-full mt-1">

            <!-- Cambiar el color del texto -->
            <label class="block mt-4 text-sm font-medium text-gray-700">Color del texto</label>
            <input type="color" wire:model="textColor" class="block w-full h-10 p-0 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">

            <!-- Cambiar la posición del texto mediante inputs -->
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700">Posición X</label>
                <input type="number" wire:model='textX' class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700">Posición Y</label>
                <input type="number" wire:model="textY" class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <button type="submit" class="block px-4 py-2 mt-4 font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">
                Generar Certificado
            </button>

        </form>

    </div>

    <!-- Vista previa a la derecha -->
    <div class="relative w-2/3 p-4">
        @if ($image)
            <!-- Vista previa de la imagen subida -->
            <div class="relative" x-data="dragText()">
                <img src="{{ $image->temporaryUrl() }}" alt="Vista previa del certificado" class="w-full h-auto border border-gray-300 rounded-md" id="previewImage">

                <!-- Campo de texto editable y arrastrable -->
                <div
                    x-bind:style="'left: ' + textX + 'px; top: ' + textY + 'px; position: absolute; color: ' + textColor + '; font-size: ' + textSize + 'px;'"
                    x-text="$wire.text"
                    @mousedown="startDrag"
                    @mousemove="drag($event)"
                    @mouseup="stopDrag"
                    @mouseleave="stopDrag"
                    class="font-bold cursor-move select-none"
                    id="dragText"
                    style="cursor: move;">
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    function dragText() {
        return {
            dragging: false,
            textX: @entangle('textX'),
            textY: @entangle('textY'),
            textSize: @entangle('textSize'),
            textColor: @entangle('textColor'),
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
                    const previewImage = document.getElementById('previewImage');
                    const previewRect = previewImage.getBoundingClientRect();

                    const dragText = document.getElementById('dragText');
                    const textRect = dragText.getBoundingClientRect();
                    const textWidth = textRect.width; // Usar el ancho real del texto
                    const textHeight = textRect.height; // Usar el alto real del texto

                    // Limitar la posición X para que no se salga del área de vista previa
                    this.textX = Math.max(0, Math.min(event.clientX - this.startX, previewRect.width - textWidth));

                    // Limitar la posición Y para que no se salga del área de vista previa
                    this.textY = Math.max(0, Math.min(event.clientY - this.startY, previewRect.height - textHeight));
                }
            },

            // Soltar el texto
            stopDrag() {
                this.dragging = false;
            },
        }
    }
</script>
