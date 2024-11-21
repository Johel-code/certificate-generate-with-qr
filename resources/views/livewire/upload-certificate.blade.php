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
                <textarea x-model="customText" class="block w-full mt-2 mb-4 text-sm text-gray-700 border border-gray-300 rounded-md"></textarea>

                <!-- Inputs dinamicos para varias areas de texto -->
                {{-- <template x-for="(fieldConfig, field) in fieldsConfigurations" :key="field">
                    <div>
                        <template x-if="fieldConfig.type === 'area'">
                            <div class="mt-4">
                                <label class="" x-text="fieldConfig.label"></label>
                                <textarea
                                    :id="field"
                                    x-model="fieldConfig.text"
                                    class="block w-full mt-2 mb-4 text-sm text-gray-700 border border-gray-300 rounded-md"
                                ></textarea>
                            </div>
                        </template>
                    </div>
                </template> --}}

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
                @if(!$selectedField && $image)
                    <div class="mt-4">
                        <label for="opacityRange" class="block text-gray-700">Transparencia:</label>
                        <input type="range" id="opacityRange" min="0" max="1" step="0.1" x-model="opacity" class="w-full mt-2">
                    </div>
                @endif
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
                    @elseif( $selectedField === 'textArea')
                        <div class="p-4">
                            <label class="block mt-4 text-sm font-medium text-gray-700">Tamaño del texto</label>
                            <input type="range" x-model="customTextSize" @input="$wire.updateFieldConfiguration('{{ $selectedField }}', { textSize })" min="10" max="100" class="block w-full mt-1">
                        </div>

                        <div class="p-4">
                            <label class="block mt-4 text-sm font-medium text-gray-700">Color del texto</label>
                            <input type="color" x-model="customTextColor" @input="$wire.updateFieldConfiguration('{{ $selectedField }}', { textColor })" class="block w-full h-10 p-0 mt-1 border border-gray-300 rounded-md">
                        </div>

                        <div class="p-4">
                            <label class="block mt-4 text-sm font-medium text-gray-700">Fuente del texto</label>
                            <select x-model="customFontFamily" @input="$wire.updateFieldConfiguration('{{ $selectedField }}', { fontFamily })" class="block w-full mt-1 border border-gray-300 rounded-md">
                                <option value="Helvetica">Helvetica</option>
                                <option value="Times-Roman">Times-Roman</option>
                                <option value="Courier">Courier</option>
                            </select>
                        </div>

                        <div x-data="textEditor()" class="p-4 text-center">
                            <label class="block mt-4 text-sm font-medium text-gray-700">Alineación</label>
                            <div class="flex mt-1 space-x-2">
                                <button type="button" @click="alignment = 'left'; $wire.updateFieldConfiguration('{{ $selectedField }}', { alignment })" class="px-4 py-2 border rounded-md" :class="{ 'bg-gray-300': alignment === 'left' }">
                                    <i class="text-xl fas fa-align-left"></i>
                                </button>
                                <button type="button" @click="alignment = 'center'; $wire.updateFieldConfiguration('{{ $selectedField }}', { alignment })" class="px-4 py-2 border rounded-md" :class="{ 'bg-gray-300': alignment === 'center' }">
                                    <i class="text-xl fas fa-align-center"></i>
                                </button>
                                <button type="button" @click="alignment = 'right'; $wire.updateFieldConfiguration('{{ $selectedField }}', { alignment })" class="px-4 py-2 border rounded-md" :class="{ 'bg-gray-300': alignment === 'right' }">
                                    <i class="text-xl fas fa-align-right"></i>
                                </button>
                            </div>
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
                                <option value="Helvetica">Helvetica</option>
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

                    @endif
                </div>
            </form>
            <div class="flex justify-center mt-6">
                <button wire:click.prevent="generateCertificate" class="px-6 py-3 font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">
                    Generar Certificado
                </button>
            </div>

        <div class="flex">
            <div class="w-1/3 p-4 bg-gray-100">
                <div class="w-1/2">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Selecciona un campo para configurar</label>
                        <select wire:model.change="selectedField" class="block w-full mt-1 border border-gray-300 rounded-md">
                            <option value="">Selecciona un campo</option>
                            @foreach ($fieldsConfigurations as $field => $config)
                                <option value="{{ $field }}">{{ $config['label'] }}</option>
                            @endforeach
                            <!-- Añadir opción QR -->
                            {{-- <option value="qrCode">QR Code</option> --}}
                        </select>
                    </div>

                    <div x-data="certificateEditor()">
                        <label class="block text-sm font-medium text-gray-700">Número de firmas</label>
                        <input type="number" wire:model.change="signatureCount" min="1" class="block w-full mt-2 mb-4 text-sm text-gray-700 border border-gray-300 rounded-md">

                    </div>

                    <!-- Boton para agregar Area de texto -->
                    {{-- <div>
                        <button wire:click='addTextArea' class="p-2 text-white bg-blue-500 rounded">
                            Agregar Área de Texto
                        </button>
                    </div> --}}
                </div>
            </div>

            <div class="relative w-2/3 p-4">
                @if ($image)
                    <div class="relative inline-block">
                        <img x-data="dragText()" :style="{ opacity: opacity }" src="{{ $image->temporaryUrl() }}" alt="Vista previa del certificado" class="block w-full h-auto border border-gray-300 rounded-md"
                                id="previewImage" style="width: 612px; height: 792px;">

                        <!-- Cuadro delimitador para firmas -->
                         <div x-data="certificateEditor()">

                            <div 
                                class="relative border-2 border-blue-400"
                                style="width: {{ $containerWidth }}px; height: {{ $containerHeight }}px;"
                                @mousedown="startDrag($event)"
                                @mouseup="stopDrag()"
                                @mousemove="handleMouseMove($event)"
                            >

                                @php
                                    $rows = ceil($signatureCount / 3); // Máximo 3 firmas por fila
                                    $signaturesArray = array_chunk($signatures, 3);
                                @endphp

                                @foreach ($signaturesArray as $index => $row)
                                
                                    <div class="flex {{ count($row) < 3 ? 'justify-center' : 'justify-between'}} mb-4"
                                        style="width: 100%;"
                                    >
                                        @foreach ($row as $signature)
                                            <div 
                                                class="flex flex-col items-center justify-center text-center border rounded p-2 bg-gray-100"
                                                style="
                                                    width: {{ $signatureWidth }}px;
                                                    height: 100px;
                                                "
                                            >
                                                <div>
                                                    <div class="border-b mb-2">Firma</div>
                                                    <div class="font-bold">Juan Pérez</div>
                                                    <div>Decano</div>
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                    </div>
                                @endforeach
                                <!-- Distribuir las firmas -->
                                
                            </div>
                         </div>
                        
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
                                    <div :class="`text-${alignment}`" :style="`font-size: ${customTextSize}px; color: ${customTextColor}; font-family: ${customFontFamily};`" x-html="formattedContent"></div>
                                </div>

                                <!-- Controladores de redimensionamiento (resize handles) -->
                                <div
                                    class="absolute bottom-0 right-0 w-3 h-3 bg-gray-600 cursor-se-resize"
                                    @mousedown="startResize($event)">
                                </div>
                            </div>
                        </div>

                        <div x-data="dragText()">

                            <!-- Qr Code -->
                            {{-- <div class="absolute" :style="`top: ${fieldsConfigurations['qrCode'].qrY}px; left: ${fieldsConfigurations['qrCode'].qrX}px;`">
                                <!-- Previsualización del QR genérico, puedes usar un placeholder o un QR básico -->
                                <img src="data:image/png;base64,{{ $qrCode }}" :style="`width: ${fieldsConfigurations['qrCode'].qrSize}px;`" alt="Vista Previa del QR">
                            </div> --}}

                            <div>
                                <template x-for="(fieldConfig, field) in fieldsConfigurations" :key="field">
                                    <template x-if="fieldConfig.type === 'text'">
                                        <div
                                            @mousedown="startDrag(field, $event)"
                                            @mouseup="stopDrag()"
                                            @mousemove="dragField(field, $event)"
                                            :style="`position: absolute; top: ${fieldConfig.textY}px; left: ${fieldConfig.textX}px; font-size: ${fieldConfig.textSize}px; color: ${fieldConfig.textColor}; font-family: ${fieldConfig.fontFamily};`"
                                            class="draggable-field"
                                            x-text="fieldConfig.text">
                                        </div>
                                    </template>

                                    <!-- Area de texto en vista previa -->
                                    {{-- <template x-if="fieldConfig.type === 'area'">
                                        <div x-data="resizeableText()">
                                            <div
                                                class="absolute border-2 border-gray-400"
                                                :style="`position: absolute; top: ${fieldConfig.textY}px; left: ${fieldConfig.textX}px; width: ${fieldConfig.width}px; height: ${fieldConfig.height}px; font-size: ${fieldConfig.textSize}px; color: ${fieldConfig.textColor}; font-family: ${fieldConfig.fontFamily};`"
                                                @mousedown="startDrag(field, $event)"
                                                @mouseup="stopDrag()"
                                                @mousemove="handleMouseMove($event)"
                                            >

                                                <div x-data="textEditor()">
                                                    <div :class="`text-${fieldConfig.aligment}`" :style="`font-size: ${fieldConfig.textSize}px; color: ${fieldConfig.textColor}; font-family: ${fieldConfig.fontFamily};`" x-html="formattedContent"></div>
                                                </div>

                                                <div
                                                    class="absolute bottom-0 right-0 w-3 h-3 bg-gray-600 cursor-se-resize"
                                                    @mousedown="startResize($event)">
                                                </div>

                                            </div>
                                        </div>
                                    </template> --}}
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
            opacity: @entangle('opacity'),
            customTextSize: @entangle('customTextSize'),
            customTextColor: @entangle('customTextColor'),
            customFontFamily: @entangle('customFontFamily'),

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
            customTextSize: @entangle('customTextSize'),
            customTextColor: @entangle('customTextColor'),
            customFontFamily: @entangle('customFontFamily'),
            alignment: @entangle('alignment'), // Nuevo texto personalizado
            // fieldsConfigurations: @entangle('fieldsConfigurations'),
            // selectedField: @entangle('selectedField'),
            // customText: '',

            get formattedContent() {
                // Convierte el texto con las etiquetas HTML necesarias para mostrarlo en la vista previa
                return this.customText
                    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>') //todo implementar saltos de linea
                    .replace(/\n/g, '<br>');
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
    function certificateEditor() {
        return {
            delimX: 50,
            delimY: 50,
            delimWidth: 500,
            delimHeight: 300,
            signatureWidth: 150,
            signatureHeight: 100,
            isDragging: false,
            mouseX: 0,
            mouseY: 0,
            signatureCount: 1,
            signatures: [],

            init() {
                this.$watch('signatureCount', () => {
                    this.calculateSignatures();
                });

                this.$el.addEventListener('update-signatures', (event) => {
                    this.signatureCount = event.detail;
                    this.calculateSignatures();
                });
            },

            startDrag(event) {
                this.isDragging = true;
                this.mouseX = event.clientX;
                this.mouseY = event.clientY;
            },
            stopDrag() {
                this.isDragging = false;
            },
            handleMouseMove(event) {
                if (this.isDragging) {
                    this.delimX += event.clientX - this.mouseX;
                    this.delimY += event.clientY - this.mouseY;
                    this.mouseX = event.clientX;
                    this.mouseY = event.clientY;
                }
            },
            // Método para calcular las posiciones de las firmas
            calculateSignatures() {
                // Calculamos las filas y columnas disponibles
                const rows = Math.floor(this.delimHeight / this.signatureHeight);
                const cols = Math.floor(this.delimWidth / this.signatureWidth);
                const maxSignatures = rows * cols;

                // Si el número de firmas es mayor que el espacio disponible, lo limitamos
                this.signatureCount = Math.min(this.signatureCount, maxSignatures);

                // Creamos el arreglo de firmas con posiciones
                const newSignatures = [];
                for (let i = 0; i < this.signatureCount; i++) {
                    const row = Math.floor(i / cols);  // Determina la fila
                    const col = i % cols;  // Determina la columna

                    // Calculamos las posiciones X e Y
                    const signature = {
                        x: col * this.signatureWidth + (this.delimWidth % this.signatureWidth) / 2,
                        y: row * this.signatureHeight + (this.delimHeight % this.signatureHeight) / 2,
                    };

                    // Añadimos la firma al arreglo
                    newSignatures.push(signature);
                }

                this.signatures = newSignatures;

                // Verificamos en la consola si las firmas se están calculando correctamente
                this.$nextTick(() => {
                    console.log('Firmas calculadas:', this.signatures);
                });
            },
            
        };
    }
</script>
