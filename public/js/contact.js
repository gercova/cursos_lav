document.addEventListener('DOMContentLoaded', function() {
    // Manejo del formulario de contacto
    const contactForm = document.getElementById('contact-form');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const submitLoading = document.getElementById('submit-loading');
    const formAlert = document.getElementById('form-alert');

    contactForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Validación básica
        if (!validateForm()) {
            return;
        }

        // Mostrar loading
        submitText.classList.add('hidden');
        submitLoading.classList.remove('hidden');
        submitBtn.disabled = true;

        try {
            // Simular envío (en producción, esto sería una petición real)
            await new Promise(resolve => setTimeout(resolve, 2000));

            showAlert('¡Mensaje enviado con éxito! Te contactaremos pronto.', 'success');
            contactForm.reset();

        } catch (error) {
            showAlert('Error al enviar el mensaje. Por favor, intenta nuevamente.', 'error');
        } finally {
            // Ocultar loading
            submitText.classList.remove('hidden');
            submitLoading.classList.add('hidden');
            submitBtn.disabled = false;
        }
    });

    function validateForm() {
        let isValid = true;
        const fields = ['name', 'email', 'subject', 'message'];

        // Limpiar errores anteriores
        fields.forEach(field => {
            const errorElement = document.getElementById(`${field}-error`);
            if (errorElement) {
                errorElement.classList.add('hidden');
            }
        });

        // Validar campos requeridos
        fields.forEach(field => {
            const input = document.getElementById(field);
            if (!input.value.trim()) {
                showFieldError(field, 'Este campo es requerido');
                isValid = false;
            }
        });

        // Validar email
        const email = document.getElementById('email').value;
        if (email && !isValidEmail(email)) {
            showFieldError('email', 'Por favor ingresa un email válido');
            isValid = false;
        }

        return isValid;
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function showFieldError(field, message) {
        const errorElement = document.getElementById(`${field}-error`);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');

            // Resaltar campo con error
            const input = document.getElementById(field);
            input.classList.add('border-red-500');

            // Quitar resaltado después de 3 segundos
            setTimeout(() => {
                input.classList.remove('border-red-500');
            }, 3000);
        }
    }

    function showAlert(message, type) {
        formAlert.textContent = message;
        formAlert.className = `p-4 rounded-lg ${
            type === 'success' ? 'bg-green-100 text-green-700 border border-green-200' :
            'bg-red-100 text-red-700 border border-red-200'
        }`;
        formAlert.classList.remove('hidden');

        // Ocultar alerta después de 5 segundos
        setTimeout(() => {
            formAlert.classList.add('hidden');
        }, 5000);
    }

    // FAQ Accordion
    const faqQuestions = document.querySelectorAll('.faq-question');

    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const answer = this.nextElementSibling;
            const icon = this.querySelector('svg');

            // Toggle respuesta
            answer.classList.toggle('hidden');

            // Rotar ícono
            icon.classList.toggle('rotate-180');

            // Cerrar otras respuestas
            faqQuestions.forEach(otherQuestion => {
                if (otherQuestion !== question) {
                    const otherAnswer = otherQuestion.nextElementSibling;
                    const otherIcon = otherQuestion.querySelector('svg');
                    otherAnswer.classList.add('hidden');
                    otherIcon.classList.remove('rotate-180');
                }
            });
        });
    });

    // Smooth scroll para enlaces internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Efectos de hover en inputs
    const inputs = document.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-blue-200', 'rounded-lg');
        });

        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-blue-200', 'rounded-lg');
        });
    });
});
