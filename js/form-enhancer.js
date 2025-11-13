document.addEventListener('DOMContentLoaded', () => {
  const forms = document.querySelectorAll('form[data-enhance-style]');

  forms.forEach(form => {
    const framework = form.dataset.enhanceStyle;

    switch (framework) {
      case 'bootstrap':
        applyBootstrap(form);
        break;
      case 'tailwind':
        applyTailwind(form);
        break;
      case 'material':
        applyMaterial(form);
        break;
      default:
        console.warn(`Unknown style framework: ${framework}`);
    }
  });
});

function applyBootstrap(form) {
  form.querySelectorAll('div').forEach(div => div.classList.add('mb-3'));

  form.querySelectorAll('label').forEach(label => label.classList.add('form-label'));

  form.querySelectorAll('input, select, textarea').forEach(el => {
    const type = el.getAttribute('type');
    if (type === 'checkbox' || type === 'radio') {
      el.classList.add('form-check-input');
    } else {
      el.classList.add('form-control');
    }
  });

  form.querySelectorAll('button, input[type="submit"]').forEach(btn => {
    btn.classList.add('btn', 'btn-primary');
  });
}

function applyTailwind(form) {
  form.querySelectorAll('div').forEach(div => div.classList.add('mb-4'));

  form.querySelectorAll('label').forEach(label => {
    label.classList.add('block', 'text-gray-700', 'text-sm', 'font-medium', 'mb-2');
  });

  form.querySelectorAll('input, select, textarea').forEach(el => {
    el.classList.add(
      'block', 'w-full', 'rounded-md', 'border-gray-300', 'shadow-sm',
      'focus:border-indigo-500', 'focus:ring-indigo-500', 'text-sm'
    );
  });

  form.querySelectorAll('button, input[type="submit"]').forEach(btn => {
    btn.classList.add(
      'px-4', 'py-2', 'bg-indigo-600', 'text-white', 'rounded-md',
      'hover:bg-indigo-700', 'focus:outline-none', 'focus:ring-2',
      'focus:ring-indigo-500', 'focus:ring-offset-2'
    );
  });
}

function applyMaterial(form) {
  form.querySelectorAll('div').forEach(div => div.classList.add('mdc-form-field'));

  form.querySelectorAll('input, select, textarea').forEach(el => {
    el.classList.add('mdc-text-field__input');
  });

  form.querySelectorAll('label').forEach(label => {
    label.classList.add('mdc-floating-label');
  });
}
