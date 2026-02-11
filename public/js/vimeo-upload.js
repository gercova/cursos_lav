let submittedForm=false;
// Estado de la subida actual (para poder cancelarla)
let currentVimeoUpload = null;
let currentVimeoFileKey = null;
let currentVimeoSuccess=false;
document.getElementById('lessonForm').addEventListener('submit', function(e) {
    /*const vimeoId = document.getElementById('vimeo_id').value;
    if(!vimeoId){
        alert('no has subido ningun video');
        return;
    }*/
    submittedForm = true;
});

// 3. El bloqueador de salida
window.addEventListener('beforeunload', function (e) {
    // Si el video está al 100% y NO se ha enviado el formulario...
    if (currentVimeoSuccess && !submittedForm) {
        e.preventDefault();
        const message='Tienes un video subido pero no has guardado el curso. Si sales ahora, el video se perderá.';

        e.returnValue = message; // Estándar para la mayoría de los navegadores
        return message; // Para algunos navegadores
    }
});

// Helper para mostrar un estado "cargando" en un botón
function setButtonLoading(button, loading, label = null) {
    if (!button) return;
    if (loading) {
        // Guardar html previo
        button.dataset.prevHtml = button.innerHTML;
        const spinner = '<svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>';
        button.innerHTML = (label ? (spinner + label) : (spinner + 'Subiendo...'));
        setButtonDisabled(button, true);
    } else {
        // Restaurar
        button.innerHTML = button.dataset.prevHtml || label || 'Subir';
        setButtonDisabled(button, false);
    }
}
function setButtonDisabled(button, disabled) {
    if (!button) return;
    if (disabled) {
        button.classList.add('opacity-70', 'cursor-not-allowed');
        button.setAttribute('aria-busy', 'true');
        button.disabled = true;
    } else {
        button.classList.remove('opacity-70', 'cursor-not-allowed');
        button.removeAttribute('aria-busy');
        button.disabled = false;
    }

}

// Helper para aplicar clases visuales al input file cuando está deshabilitado
function setFileInputDisabled(input, disabled) {
    if (!input) return;
    if (disabled) {
        input.disabled = true;
        input.classList.add('opacity-50', 'pointer-events-none', 'cursor-not-allowed', 'bg-gray-100');
    } else {
        input.disabled = false;
        input.classList.remove('opacity-50', 'pointer-events-none', 'cursor-not-allowed', 'bg-gray-100');
    }
}
 


// Cancela la subida activa (si existe) y restaura la UI
async function cancelVimeoUpload() {
    let txtconfirm='seguro que desea detener la subida del video?';
    if(currentVimeoSuccess){
        txtconfirm='seguro que desea eliminar el  video?';
    }
    if(!confirm(txtconfirm)) return;
    //if (!currentVimeoUpload && !currentVimeoFileKey) return;
    // si es detener la subida
    if(!currentVimeoSuccess){
        try {
            if (currentVimeoUpload && typeof currentVimeoUpload.abort === 'function') {
                currentVimeoUpload.abort();
            }
        } catch (e) {
            console.error('Error abortando la subida:', e);
        }
        cleanUpload();
    }

    if(currentVimeoSuccess){
        const btnCancel = document.getElementById('btnCancelVimeo');
        const vimeoId = document.getElementById('vimeo_id').value;
        if(!vimeoId) return;
        setButtonLoading(btnCancel, true, 'Eliminando...');
        try {
            await axios.delete(API_URL+`/admin/vimeo/${vimeoId}`);
            
            cleanUpload();
            setButtonLoading(btnCancel, false, 'Eliminar');
            alert("Video eliminado. Puedes seleccionar uno nuevo.");
        } catch (error) {
            alert("Error al eliminar el video de Vimeo.");
            setButtonLoading(btnCancel, false, 'Eliminar');
        }
    }
    

    
}
async function cancelEditVimeoUpload(){
    if(!confirm('seguro que desea eliminar el  video?')) return;
    const btnCancel = document.getElementById('btnEditCancelVimeo');
    const vimeoId = document.getElementById('vimeo_id').value;
    if(!vimeoId) return;
    setButtonLoading(btnCancel, true, 'Eliminando...');
    try {
        await axios.delete(API_URL+`/admin/vimeo/${vimeoId}`);
        
        cleanUpload();
        document.getElementById('content-file-upload').classList.remove('hidden');
        document.getElementById('current-video-preview').classList.add('hidden');
        setButtonLoading(btnCancel, false, 'Eliminar');
        alert("Video eliminado. Puedes seleccionar uno nuevo.");
    } catch (error) {
        alert("Error al eliminar el video de Vimeo.");
        setButtonLoading(btnCancel, false, 'Eliminar');
    }
}
function cleanUpload(){
    const btnSubir = document.getElementById('btnUpload');
    const btn = document.getElementById('btnSave');
    const fileInput = document.getElementById('video');

    // Limpiar UI
    const progressBar = document.getElementById('upload-progress-bar');
    const progressText = document.getElementById('progress-text');
    const content = document.getElementById('content-progress-bar');
    const btnCancel = document.getElementById('btnCancelVimeo');

    if (progressBar) progressBar.style.width = '0%';
    if (progressText) progressText.innerText = '0%';
    if (content) content.classList.add('hidden');
    if (btnCancel) btnCancel.classList.add('hidden');
    document.getElementById('vimeo_id').value='';

    if (fileInput){
        setFileInputDisabled(fileInput, false);
        fileInput.value='';
    }
    if (btnSubir) setButtonLoading(btnSubir, false, 'Subir');
    if (btn) setButtonDisabled(btn, false);

    // Limpiar localStorage
    if (currentVimeoFileKey) {
        localStorage.removeItem(currentVimeoFileKey);
        localStorage.removeItem(currentVimeoFileKey + '-uri');
    }

    currentVimeoUpload = null;
    currentVimeoFileKey = null;
    currentVimeoSuccess=false;
}
async function startDirectUpload() {
    const file = document.getElementById('video').files[0];
    if (!file) return alert("Selecciona un archivo");

    const fileKey = `tus-resumable-${file.name}-${file.size}`;
    let uploadUrl = localStorage.getItem(fileKey);
    let vimeoUri = localStorage.getItem(fileKey + '-uri');

    const btnSubir = document.getElementById('btnUpload');
    const btn = document.getElementById('btnSave');
    const fileInput = document.getElementById('video');
    setButtonDisabled(btn,true);
    setButtonLoading(btnSubir, true, 'Subiendo...');

    if (fileInput) setFileInputDisabled(fileInput, true);
    if (!uploadUrl) {
        console.log("Iniciando subida nueva...");
        const response = await axios.post(API_URL + '/admin/vimeo/upload-link', {
            size: file.size,
            name: file.name
        });
        if (response.data.upload_link == null) {
            alert('ocurrio un error');
            setButtonLoading(btnSubir, false, 'Subir');
            setButtonDisabled(btn,false);
            if (fileInput) setFileInputDisabled(fileInput, false);
            return;
        }
        uploadUrl = response.data.upload_link;
        vimeoUri = response.data.vimeo_uri;

        // Guardamos en localStorage por si acaso se corta
        localStorage.setItem(fileKey, uploadUrl);
        localStorage.setItem(fileKey + '-uri', vimeoUri);
    } else {
        console.log("Reanudando subida desde:", uploadUrl);
    }
    document.getElementById('content-progress-bar').classList.remove("hidden");
    // Mostrar botón cancelar y guardar key para cancelación
    const btnCancel = document.getElementById('btnCancelVimeo');
    if (btnCancel) btnCancel.classList.remove('hidden');
    btnCancel.innerHTML='Detener';
    currentVimeoFileKey = fileKey;
    currentVimeoSuccess=false;
    const upload = new tus.Upload(file, {
        endpoint: uploadUrl, // Requerido pero ignorado si usamos uploadUrl directo
        uploadUrl: uploadUrl,
        retryDelays: [0, 3000, 5000, 10000],
        onProgress: (bytesUploaded, bytesTotal) => {
            const percentage = ((bytesUploaded / bytesTotal) * 100).toFixed(2);
            document.getElementById('upload-progress-bar').style.width = percentage + '%';
            document.getElementById("progress-text").innerText = `${percentage}%`;
        },
        onSuccess: () => {
            console.log("¡Subida completa a Vimeo!");
            // 1. Extraer el ID numérico del URI (ej: "/videos/1234567" -> "1234567")
            const vimeoId = vimeoUri.replace('/videos/', '');
            document.getElementById('vimeo_id').value = vimeoId;

            setButtonDisabled(btn,false);
            setButtonLoading(btnSubir, false, 'Subir');
            setButtonDisabled(btnSubir,true);
            //if (fileInput) setFileInputDisabled(fileInput, false);

            // Limpiar localStorage
            localStorage.removeItem(fileKey);
            currentVimeoUpload = null;
            currentVimeoFileKey = null;
            currentVimeoSuccess=true;
            btnCancel.innerHTML='Eliminar';
        },
        onError: (error) => {
            console.error("Error:", error);

            if (fileInput) setFileInputDisabled(fileInput, false);
            setButtonLoading(btnSubir, false, 'Subir');
            setButtonDisabled(btn,false);
            // Ocultar botón cancelar y limpiar estado
            const btnCancel = document.getElementById('btnCancelVimeo');
            if (btnCancel) btnCancel.classList.add('hidden');
            if (currentVimeoFileKey) {
                localStorage.removeItem(currentVimeoFileKey);
                localStorage.removeItem(currentVimeoFileKey + '-uri');
            }
            currentVimeoUpload = null;
            currentVimeoFileKey = null;
            if (error && error.status === 404) { // El link de Vimeo expiró (duran 24h)
                localStorage.removeItem(fileKey);
                alert("El tiempo de subida expiró, intenta de nuevo.");
            } else {
                alert('Ocurrió un error durante la subida. Intenta nuevamente.');
            }
        }
    })
        // Guardamos referencia para permitir cancelación
    currentVimeoUpload = upload;
    upload.start();
}

