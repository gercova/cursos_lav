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
             setButtonDisabled(btn,true);
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

            setButtonDisabled(btn,true);
            setButtonLoading(btnSubir, false, 'Subir');
            if (fileInput) setFileInputDisabled(fileInput, false);

            // Limpiar localStorage
            localStorage.removeItem(fileKey);
        },
        onError: (error) => {
            console.error("Error:", error);

            if (fileInput) setFileInputDisabled(fileInput, false);
            setButtonLoading(btnSubir, false, 'Subir');
            setButtonDisabled(btn,true);
            if (error && error.status === 404) { // El link de Vimeo expiró (duran 24h)
                localStorage.removeItem(fileKey);
                alert("El tiempo de subida expiró, intenta de nuevo.");
            } else {
                alert('Ocurrió un error durante la subida. Intenta nuevamente.');
            }
        }
    })
    upload.start();
}