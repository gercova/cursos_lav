<div class="mx-auto {{$class}}" id="content-file-upload">
    <label for="video" class="block text-sm font-medium text-gray-700 mb-2">
        Video
    </label>
<!-- Contenedor input + botón -->
    <div class="flex items-center gap-4">
        <input type="hidden" id="vimeo_id" name="vimeo_id" value="{{old('vimeo_id',$vimeo_id)}}">
        <input
            type="file"
            id="video"
            name="video" value="{{ old('video') }}" 
            accept="video/*" 
            class="block w-full text-sm text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-lg file:border-0
                file:text-sm file:font-semibold
                file:bg-gray-100 file:text-gray-700
                hover:file:bg-gray-200
                border border-gray-300 rounded-lg"
        />
        <!-- Botón subir -->
        <button
            type="button"
            class="inline-flex items-center h-10 px-4 text-sm font-semibold text-white
            bg-green-600 rounded-lg
            hover:bg-green-700
            focus:outline-none focus:ring-2 focus:ring-green-500"
            id="btnUpload" onclick="startDirectUpload()"
        >
        Subir
        </button> 
        <!-- Botón cancelar (oculto hasta que empiece la subida) -->
        <button
            type="button"
            id="btnCancelVimeo"
            onclick="cancelVimeoUpload()"
            class="hidden inline-flex items-center h-10 px-3 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
        >
            Detener
        </button>
    </div>
    @error('video')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
    <p class="text-xs text-gray-500 mt-1">Soporta archivos .mp4</p>

    <!-- Progreso -->
    <div class="mt-4 hidden" id="content-progress-bar">
        <div class="flex justify-between text-xs text-gray-600 mb-1">
            <span>Progreso</span>
            <span id="progress-text">0%</span>
        </div>

        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
            <div
                id="upload-progress-bar"
                class="bg-green-600 h-3 rounded-full transition-all duration-300"
                style="width: 0%"
            ></div>
        </div>
    </div>
    <!-- FILE INFO -->
    <!-- <div id="file-info" class="mt-2 border rounded-xl px-5 py-2 bg-gray-50">

        <div class="flex justify-between items-center">
            <div>
            <p id="file-name" class="font-semibold text-gray-800"></p>
            <p id="file-size" class="text-sm text-gray-500"></p>
            </div>

            <div class="flex items-center gap-4">
            <span id="percent" class="font-semibold text-gray-700">0%</span>

            <button id="cancelBtn"
                    class="w-8 h-8 rounded-lg bg-gray-200 hover:bg-gray-300 flex items-center justify-center">
                ✕
            </button>
            </div>
        </div>

        
        <div class="w-full bg-gray-200 rounded-full h-3 mt-4 overflow-hidden">
            <div id="progress-bar"
                class="bg-green-600 h-3 rounded-full transition-all duration-300"
                style="width: 0%">
            </div>
        </div>

        <p id="statusText" class="text-sm text-gray-600 mt-3">
            Esperando...
        </p>

    </div>-->
</div>