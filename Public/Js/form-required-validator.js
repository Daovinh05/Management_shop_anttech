(function() {
    'use strict';

    var styleId = 'custom-required-validator-style';

    function ensureStyles() {
        if (document.getElementById(styleId)) {
            return;
        }

        var style = document.createElement('style');
        style.id = styleId;
        style.textContent = [
            '.field-error-message{color:#dc2626;font-size:13px;line-height:1.35;margin-top:6px;}',
            '.field-invalid{border-color:#dc2626!important;box-shadow:0 0 0 2px rgba(220,38,38,.12)!important;}',
            '.password-wrapper.field-invalid{border-color:#dc2626!important;box-shadow:0 0 0 2px rgba(220,38,38,.12)!important;}',
            '.password-wrapper.field-invalid input{border-color:transparent!important;box-shadow:none!important;}'
        ].join('');
        document.head.appendChild(style);
    }

    function getLabelText(field) {
        var label = null;

        if (field.id) {
            label = document.querySelector('label[for="' + field.id.replace(/"/g, '\\"') + '"]');
        }

        if (!label) {
            var group = field.closest('.form-group, .input-group, .form-row, .field-group, td, div');
            label = group ? group.querySelector('label') : null;
        }

        var text = field.getAttribute('data-label') || (label ? label.textContent : '') || field.getAttribute('placeholder') || field.name || 'Trường này';
        return text.replace(/\*/g, '').replace(/\s+/g, ' ').trim();
    }

    function getErrorTarget(field) {
        return field.closest('.password-wrapper') || field;
    }

    function clearFieldError(field) {
        var target = getErrorTarget(field);
        target.classList.remove('field-invalid');
        field.classList.remove('field-invalid');

        var id = field.getAttribute('data-error-id');
        if (id) {
            var oldError = document.getElementById(id);
            if (oldError) {
                oldError.remove();
            }
            field.removeAttribute('data-error-id');
        }
    }

    function showFieldError(field) {
        clearFieldError(field);

        var target = getErrorTarget(field);
        var error = document.createElement('div');
        var errorId = 'field-error-' + Math.random().toString(36).slice(2);

        error.id = errorId;
        error.className = 'field-error-message';
        error.textContent = 'Vui lòng nhập ' + getLabelText(field).toLowerCase() + '.';

        target.classList.add('field-invalid');
        field.classList.add('field-invalid');
        field.setAttribute('data-error-id', errorId);
        field.setAttribute('aria-invalid', 'true');

        target.insertAdjacentElement('afterend', error);
    }

    function isRequiredField(field) {
        return field.matches('[data-required="true"], [required]');
    }

    function isEmpty(field) {
        if (field.disabled || field.type === 'hidden') {
            return false;
        }

        if (field.type === 'checkbox' || field.type === 'radio') {
            var form = field.form || document;
            return !form.querySelector('[name="' + field.name.replace(/"/g, '\\"') + '"]:checked');
        }

        if (field.type === 'file') {
            return !field.files || field.files.length === 0;
        }

        return String(field.value || '').trim() === '';
    }

    function validateForm(form) {
        var fields = Array.prototype.slice.call(form.querySelectorAll('input, select, textarea')).filter(isRequiredField);
        var firstInvalid = null;
        var checkedRadioNames = {};

        fields.forEach(function(field) {
            if ((field.type === 'radio' || field.type === 'checkbox') && checkedRadioNames[field.name]) {
                return;
            }

            if (field.type === 'radio' || field.type === 'checkbox') {
                checkedRadioNames[field.name] = true;
            }

            clearFieldError(field);
            field.removeAttribute('aria-invalid');

            if (isEmpty(field)) {
                showFieldError(field);
                if (!firstInvalid) {
                    firstInvalid = field;
                }
            }
        });

        if (firstInvalid) {
            firstInvalid.focus({ preventScroll: true });
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
        }

        return true;
    }

    function bindLiveClear() {
        document.addEventListener('input', function(event) {
            if (event.target && isRequiredField(event.target)) {
                clearFieldError(event.target);
                event.target.removeAttribute('aria-invalid');
            }
        });

        document.addEventListener('change', function(event) {
            if (event.target && isRequiredField(event.target)) {
                clearFieldError(event.target);
                event.target.removeAttribute('aria-invalid');
            }
        });
    }

    function disableNativeValidation() {
        document.querySelectorAll('form').forEach(function(form) {
            form.setAttribute('novalidate', 'novalidate');
        });
    }

    ensureStyles();
    bindLiveClear();

    document.addEventListener('DOMContentLoaded', disableNativeValidation);

    document.addEventListener('submit', function(event) {
        var form = event.target;

        if (!form || !form.matches('form')) {
            return;
        }

        form.setAttribute('novalidate', 'novalidate');

        if (!validateForm(form)) {
            event.preventDefault();
            event.stopImmediatePropagation();
        }
    }, true);
})();
