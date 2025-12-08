document.addEventListener('DOMContentLoaded', function() {
    // Efectos visuales para todos los inputs
    const formInputs = document.querySelectorAll('input, select, textarea');
    formInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.classList.add('ring-2', 'ring-blue-500', 'border-transparent');
        });

        input.addEventListener('blur', function() {
            this.classList.remove('ring-2', 'ring-blue-500', 'border-transparent');
        });
    });

    // Validación en tiempo real del DNI
    const dniInput = document.getElementById('dni');
    if (dniInput) {
        dniInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 8) {
                this.value = this.value.slice(0, 8);
            }
        });
    }

    // Validación en tiempo real del teléfono
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    // Mostrar/ocultar password
    const passwordInputs = ['password', 'password_confirmation'];

    passwordInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            const toggleButton = document.createElement('button');
            toggleButton.type = 'button';
            toggleButton.innerHTML = `
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            `;
            toggleButton.className = 'absolute right-3 top-1/2 transform focus:outline-none';

            input.parentElement.classList.add('relative');
            input.parentElement.appendChild(toggleButton);

            toggleButton.addEventListener('click', function() {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);

                this.innerHTML = type === 'password' ? `
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                ` : `
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                `;
            });
        }
    });

    // Validación de fortaleza de contraseña
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        const strengthMeter = document.createElement('div');
        strengthMeter.className = 'mt-2 text-xs';
        passwordInput.parentElement.appendChild(strengthMeter);

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let message = '';
            let color = 'text-gray-500';

            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/\d/)) strength++;
            if (password.match(/[^a-zA-Z\d]/)) strength++;

            switch(strength) {
                case 0:
                    message = 'Muy débil';
                    color = 'text-red-500';
                    break;
                case 1:
                    message = 'Débil';
                    color = 'text-orange-500';
                    break;
                case 2:
                    message = 'Regular';
                    color = 'text-yellow-500';
                    break;
                case 3:
                    message = 'Fuerte';
                    color = 'text-green-500';
                    break;
                case 4:
                    message = 'Muy fuerte';
                    color = 'text-green-600';
                    break;
            }

            strengthMeter.innerHTML = `Fortaleza: <span class="${color} font-medium">${message}</span>`;
        });
    }
});
