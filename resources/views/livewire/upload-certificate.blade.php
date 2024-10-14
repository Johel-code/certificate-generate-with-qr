<div class="">
    <div x-data="dragText()">
        <form wire:submit.prevent='generateCertificate' class="flex justify-center">
            <div class="w-1/4 p-4">
                <!-- Controles de carga de imagen y CSV existentes -->
                <label class="block text-sm font-medium text-gray-700">Subir la imagen del certificado</label>
                <input type="file" wire:model="image" class="block w-full mt-2 mb-4 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">

                <label class="block text-sm font-medium text-gray-700">Subir archivo CSV con nombres de usuarios</label>
                <input type="file" wire:model="csv" class="block w-full mt-2 mb-4 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
            </div>

            <div class="flex w-3/4">
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
                    <div class="relative inline-block" x-data="dragText()">
                        <img src="{{ $image->temporaryUrl() }}" alt="Vista previa del certificado" class="block w-full h-auto border border-gray-300 rounded-md" id="previewImage">

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
</script>
