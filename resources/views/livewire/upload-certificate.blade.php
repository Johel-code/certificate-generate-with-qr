<div class="">
    <div >
        <form wire:submit.prevent='generateCertificate' class="flex justify-center">
            <div class="w-1/4 p-4" x-data="textEditor()">
                <!-- Controles de carga de imagen y CSV existentes -->
                <label class="block text-sm font-medium text-gray-700">Subir la imagen del certificado</label>
                <input type="file" wire:model="image" class="block w-full mt-2 mb-4 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">

                <label class="block text-sm font-medium text-gray-700">Subir archivo CSV con nombres de usuarios</label>
                <input type="file" wire:model="csv" class="block w-full mt-2 mb-4 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">

                <!-- Input para el contenido personalizado -->
                <label class="block text-sm font-medium text-gray-700">Texto personalizado</label>
                {{-- <textarea wire:model="customText" class="block w-full mt-2 mb-4 text-sm text-gray-700 border border-gray-300 rounded-md"></textarea> --}}
                <textarea x-model="customText" class="block w-full mt-2 mb-4 text-sm text-gray-700 border border-gray-300 rounded-md"></textarea>

                {{-- <button @click="boldText" class="px-4 py-2 text-white bg-blue-500 rounded">Negrita</button> --}}
                <!-- Alignment Buttons -->
                {{-- <div class="flex space-x-2">
                    <button @click="alignText = 'text-left'" class="px-4 py-2 bg-gray-200 rounded">Izquierda</button>
                    <button @click="alignText = 'text-center'" class="px-4 py-2 bg-gray-200 rounded">Centro</button>
                    <button @click="alignText = 'text-right'" class="px-4 py-2 bg-gray-200 rounded">Derecha</button>
                </div>

                <!-- Preview Div -->
                <div :class="`text-${alignment} p-4 text-center bg-gray-100 border rounded`">
                    <div x-html="formattedContent"></div>
                </div> --}}
            </div>

            <div class="flex w-3/4" x-data="dragText()">
                @if ($selectedField)

                    @if($selectedField === 'qrCode')
                        <div class="p-4">
                            <label class="block mt-4 text-sm font-medium text-gray-700">Tamaño del QrCode</label>
                            <input type="range" x-model="qrSize" @input="$wire.updateFieldConfiguration('{{ $selectedField }}', { qrSize })" min="10" max="100" class="block w-full mt-1">
                        </div>

                        <div class="p-4">
                            <label class="block mt-4 text-sm font-medium text-gray-700">Posición X</label>
                            <input type="number" x-model="qrX" @input="$wire.updateFieldConfiguration('{{ $selectedField }}', { qrX })" class="block w-full mt-1 border border-gray-300 rounded-md">
                        </div>

                        <div class="p-4">
                            <label class="block mt-4 text-sm font-medium text-gray-700">Posición Y</label>
                            <input type="number" x-model="qrY" @input="$wire.updateFieldConfiguration('{{ $selectedField }}', { qrY })" class="block w-full mt-1 border border-gray-300 rounded-md">
                        </div>
                    @else
                        <div class="p-4">
                            <label class="block mt-4 text-sm font-medium text-gray-700">Tamaño del texto</label>
                            <input type="range" x-model="textSize" @input="$wire.updateFieldConfiguration('{{ $selectedField }}', { textSize })" min="10" max="100" class="block w-full mt-1">
                        </div>

                        <div class="p-4">
                            <label class="block mt-4 text-sm font-medium text-gray-700">Color del texto</label>
                            <input type="color" x-model="textColor" @input="$wire.updateFieldConfiguration('{{ $selectedField }}', { textColor })" class="block w-full h-10 p-0 mt-1 border border-gray-300 rounded-md">
                        </div>

                        <div class="p-4">
                            <label class="block mt-4 text-sm font-medium text-gray-700">Fuente del texto</label>
                            <select x-model="fontFamily" @input="$wire.updateFieldConfiguration('{{ $selectedField }}', { fontFamily })" class="block w-full mt-1 border border-gray-300 rounded-md">
                                <option value="Arial">Arial</option>
                                <option value="Times-Roman">Times-Roman</option>
                                <option value="Courier">Courier</option>
                            </select>
                        </div>

                        <div class="p-4">
                            <label class="block mt-4 text-sm font-medium text-gray-700">Posición X</label>
                            <input type="number" x-model="textX" @input="$wire.updateFieldConfiguration('{{ $selectedField }}', { textX })" class="block w-full mt-1 border border-gray-300 rounded-md">
                        </div>

                        <div class="p-4">
                            <label class="block mt-4 text-sm font-medium text-gray-700">Posición Y</label>
                            <input type="number" x-model="textY" @input="$wire.updateFieldConfiguration('{{ $selectedField }}', { textY })" class="block w-full mt-1 border border-gray-300 rounded-md">
                        </div>

                    @endif

                    <button type="submit" class="block p-4 font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">Generar Certificado</button>
                @endif
            </div>
        </form>

        <div class="flex">
            <div class="w-1/3 p-4 bg-gray-100">
                <div class="w-1/2">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Selecciona un campo para configurar</label>
                        <select wire:model.change="selectedField" class="block w-full mt-1 border border-gray-300 rounded-md">
                            <option value="">Selecciona un campo</option>
                            @foreach ($csvHeaders as $header)
                                <option value="{{ $header }}">{{ $header }}</option>
                            @endforeach
                            <!-- Añadir opción QR -->
                            <option value="qrCode">QR Code</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="relative w-2/3 p-4">
                @if ($image)
                    <div class="relative inline-block">
                        <img src="{{ $image->temporaryUrl() }}" alt="Vista previa del certificado" class="block w-full h-auto border border-gray-300 rounded-md"
                                id="previewImage" style="width: 612px; height: 792px;">

                        <div x-data="resizeableText()">
                        <!-- Cuadro delimitador del texto personalizado -->
                            <div
                                class="absolute border-2 border-gray-400"
                                :style="`top: ${customTextY}px; left: ${customTextX}px; width: ${customTextWidth}px; height: ${customTextHeight}px;`"
                                @mousedown="startDrag('customText', $event)"
                                @mouseup="stopDrag()"
                                @mousemove="handleMouseMove($event)"
                            >
                                <!-- Contenido de texto con estilo personalizado -->
                                <div x-data="textEditor()" >
                                    <div>

                                        <div :class="`text-${alignment}`" :style="`font-size: ${textSize}px; color: ${textColor}; font-family: ${fontFamily};`" x-html="formattedContent"></div>
                                    </div>
                                </div>

                                <!-- Controladores de redimensionamiento (resize handles) -->
                                <div
                                    class="absolute bottom-0 right-0 w-3 h-3 bg-gray-600 cursor-se-resize"
                                    @mousedown="startResize($event)">
                                </div>
                            </div>
                        </div>

                        <div x-data="dragText()">
                            <div class="absolute" :style="`top: ${fieldsConfigurations['qrCode'].qrY}px; left: ${fieldsConfigurations['qrCode'].qrX}px;`">
                                <!-- Previsualización del QR genérico, puedes usar un placeholder o un QR básico -->
                                <img src="data:image/png;base64,{{ $qrCode }}" :style="`width: ${fieldsConfigurations['qrCode'].qrSize}px;`" alt="Vista Previa del QR">
                            </div>
                            <div>
                                <template x-for="(fieldConfig, field) in fieldsConfigurations" :key="field">
                                    <div
                                        @mousedown="startDrag(field, $event)"
                                        @mouseup="stopDrag()"
                                        @mousemove="dragField(field, $event)"
                                        :style="`position: absolute; top: ${fieldConfig.textY}px; left: ${fieldConfig.textX}px; font-size: ${fieldConfig.textSize}px; color: ${fieldConfig.textColor}; font-family: ${fieldConfig.fontFamily};`"
                                        class="draggable-field"
                                        x-text="fieldConfig.text">
                                    </div>
                                </template>



                            </div>

                        </div>

                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function dragText() {
        return {
            textSize: @entangle('textSize'),
            textColor: @entangle('textColor'),
            fontFamily: @entangle('fontFamily'),
            textX: @entangle('textX'),
            textY: @entangle('textY'),
            selectedField: null,
            isDragging: false,
            offsetX: 0,
            offsetY: 0,
            qrSize: @entangle('qrSize'),
            qrX: @entangle('qrX'),
            qrY: @entangle('qrY'),
            fieldsConfigurations: @entangle('fieldsConfigurations'),

            startDrag(field, event) {
                this.selectedField = field;
                this.isDragging = true;
                const fieldConfig = this.fieldsConfigurations[field];
                this.offsetX = event.clientX - (field === 'qrCode' ? fieldConfig.qrX : fieldConfig.textX);
                this.offsetY = event.clientY - (field === 'qrCode' ? fieldConfig.qrY : fieldConfig.textY);
            },
            stopDrag() {
                this.isDragging = false;
            },

            dragField(field, event) {
                if (this.isDragging && this.selectedField === field) {
                    const fieldConfig = this.fieldsConfigurations[field];
                    if (field === 'qrCode') {
                        fieldConfig.qrX = event.clientX - this.offsetX;
                        fieldConfig.qrY = event.clientY - this.offsetY;
                    } else {
                        fieldConfig.textX = event.clientX - this.offsetX;
                        fieldConfig.textY = event.clientY - this.offsetY;
                    }
                }
            },

            updateFieldConfiguration(fieldId, newConfig) {
                // Asegúrate de que no se sobrescriban las configuraciones del QR
                if (fieldId === 'qrCode') {
                    this.fieldsConfigurations['qrCode'] = {
                        ...this.fieldsConfigurations['qrCode'],
                        ...newConfig
                    };
                } else {
                    // Actualiza las configuraciones de texto
                    this.fieldsConfigurations[fieldId] = {
                        ...this.fieldsConfigurations[fieldId],
                        ...newConfig
                    };
                }
            }
        };
    }
    function resizeableText() {
        return {
            customTextWidth: @entangle('customTextWidth'), // Tamaño inicial del cuadro
            customTextHeight: @entangle('customTextHeight'),
            customTextX: @entangle('customTextX'),
            customTextY: @entangle('customTextY'),
            isDragging: false,
            isResizing: false,
            isResizeEnabled: true,
            initialMouseX: 0,
            initialMouseY: 0,
            initialWidth: 0,
            initialHeight: 0,
            textSize: 16,
            textColor: '#000000',
            fontFamily: 'Arial',

            // Función para iniciar el arrastre
            startDrag(field, event) {
                if (!this.isResizing) {  // Evitar el arrastre si se está redimensionando
                    this.isDragging = true;
                    this.initialMouseX = event.clientX;
                    this.initialMouseY = event.clientY;
                }
            },
            stopDrag() {
                this.isDragging = false;
                this.isResizing = false;
            },
            handleMouseMove(event) {
                if (this.isDragging && !this.isResizing) {
                    // Lógica para mover (arrastrar)
                    let deltaX = event.clientX - this.initialMouseX;
                    let deltaY = event.clientY - this.initialMouseY;
                    this.customTextX += deltaX;
                    this.customTextY += deltaY;
                    this.initialMouseX = event.clientX;
                    this.initialMouseY = event.clientY;
                } else if (this.isResizing) {
                    // Lógica para redimensionar
                    let deltaX = event.clientX - this.initialMouseX;
                    let deltaY = event.clientY - this.initialMouseY;
                    this.customTextWidth = this.initialWidth + deltaX;
                    this.customTextHeight = this.initialHeight + deltaY;
                }
            },
            // Función para iniciar el redimensionamiento
            startResize(event) {
                this.isResizing = true;
                this.initialMouseX = event.clientX;
                this.initialMouseY = event.clientY;
                this.initialWidth = this.customTextWidth;
                this.initialHeight = this.customTextHeight;
            },
        }
    }
    function textEditor() {
        return {
            customText: @entangle('customText'), // Nuevo texto personalizado
            alignment: @entangle('alignment'), // Nuevo texto personalizado

            get formattedContent() {
                // Convierte el texto con las etiquetas HTML necesarias para mostrarlo en la vista previa
                return this.customText.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            },

            boldText() {
                const textarea = event.target.previousElementSibling; // Selecciona el textarea
                const start = textarea.selectionStart;
                const end = textarea.selectionEnd;
                const selectedText = this.customText.substring(start, end);

                if (selectedText) {
                    // Añade ** antes y después del texto seleccionado para indicar negrita
                    this.customText = this.customText.slice(0, start) + '**' + selectedText + '**' + this.customText.slice(end);
                }
            },
        }
    }
</script>
