/**
 * Alpine.js Validation Plugin
 * Converted from com.opencode.Validation (jQuery)
 *
 * Usage:
 *   1. Include Alpine.js and this file (this file first)
 *   2. Register the plugin: Alpine.plugin(AlpineValidation)
 *   3. Use x-validate on a wrapper element, x-data on same or parent element
 *
 * Errors are stored reactively in $store.validation.errors keyed by name/id/data-field:
 *   <input class="req" name="email" />
 *   <span x-text="$store.validation.errors.email"></span>
 *   <span x-show="$store.validation.hasErrors">Has errors</span>
 *
 * $validate() still returns true/false for use in submit handlers.
 */

const AlpineValidation = (Alpine) => {
  // ── Default rules ────────────────────────────────────────────────────────────
  let rules = {
    req:      { req: true },
    float:    { regexp: /^[-+]?\d*\.?\d+$/,               msg: 'Invalid floating point value' },
    int:      { regexp: /^[-+]?\d+$/,                     msg: 'Invalid integer value' },
    unsigned: { regexp: /^[^-]*$/,                        msg: 'The value cannot be negative' },
    nonzero:  { func: (el) => parseFloat(el.value) === 0, msg: 'The value cannot be zero' },
    decimal:  { regexp: /^[-+]?((\d+(\.\d+)?)|(\.\d+))$/, msg: 'Invalid decimal value' },
    email:    { regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,      msg: 'Invalid email address' },
  };

  let additionalCheck = null;

  // ── Reactive state — единственный источник истины ────────────────────────────
  // ВАЖНО: Alpine.store() сам оборачивает объект в Proxy.
  // Никогда не переприсваиваем errors = {} — только мутируем ключи.
  Alpine.store('validation', {
    errors: {},

    get hasErrors() {
      return Object.keys(this.errors).length > 0;
    },

    clearErrors(field = null) {
      if (field) {
        delete this.errors[field];
      } else {
        // Удаляем ключи по одному — не заменяем объект целиком
        Object.keys(this.errors).forEach((k) => delete this.errors[k]);
      }
    },

    addRules(...ruleSets) {
      ruleSets.forEach((set) => Object.assign(rules, set));
    },

    delRules(...classNames) {
      classNames.forEach((name) => delete rules[name]);
    },

    flushRules() {
      rules = {};
    },

    addCheck(check) {
      if (typeof check !== 'function') throw new Error('The check is not a function');
      if (check.length !== 2)          throw new Error('The check function must have two parameters');
      additionalCheck = check;
    },

    delCheck() {
      additionalCheck = null;
    },

    validate(root = document) {
      // Очищаем ключи по одному — Alpine отслеживает мутации Proxy
      Object.keys(this.errors).forEach((k) => delete this.errors[k]);

      let valid = true;
      let firstInvalidEl = null;

      for (const [className, rule] of Object.entries(rules)) {
        const fields = root.querySelectorAll(`.${className}`);

        for (const el of fields) {
          const value    = el.value.trim();
          const fieldKey = el.dataset.field || el.name || el.id || null;
          let   error    = null;

          // if you don't want to use global trim. uncomment line below and comment if (!value) {, also remove trim from const value = el.value.trim();
          // if (!value || (rule.req && !value.trim())) {
          if (!value) {
            if (rule.req) {
              const labelText =
                el.title ||
                (el.id && root.querySelector(`label[for="${el.id}"]`)?.textContent?.trim());
              error = labelText
                ? `${labelText} is required`
                : 'Please fill in the required fields.';
            }
          } else if (rule.regexp && !rule.regexp.test(value)) {
            error = rule.msg || 'Invalid value.';
          } else if (rule.func) {
            const result = rule.func(el);
            if (result) error = rule.msg || result;
          }

          if (error) {
            if (additionalCheck && additionalCheck(el, rule)) continue;

            // Пишем напрямую в this.errors — Alpine видит мутацию через Proxy
            if (fieldKey && !this.errors[fieldKey]) this.errors[fieldKey] = error;

            if (!firstInvalidEl) firstInvalidEl = el;
            valid = false;
          }
        }
      }

      if (firstInvalidEl) {
        const tabber = root.querySelector('#element-tabber');
        if (tabber) {
          const tab = firstInvalidEl.closest('.tabbertab');
          if (tab) {
            const tabs  = [...tabber.querySelectorAll(':scope > .tabbertab')];
            const index = tabs.indexOf(tab);
            if (index !== -1 && tabber._tabber) tabber._tabber.tabShow(index);
          }
        }
        firstInvalidEl.focus();
        if (typeof firstInvalidEl.select === 'function') firstInvalidEl.select();
      }

      return valid;
    },

    toString() {
      return Object.entries(rules)
        .map(([className, rule]) => {
          const parts = [];
          if (rule.req)    parts.push('Required');
          if (rule.regexp) parts.push(`RegExp: ${rule.regexp}`);
          if (rule.func)   parts.push('Has check function');
          if (rule.msg)    parts.push(`Error Message: ${rule.msg}`);
          return `.${className}: ${parts.join('; ')}`;
        })
        .join('\n');
    },
  });

  // ── x-validate directive ─────────────────────────────────────────────────────
  Alpine.directive('validate', (el) => {
    el._alpineValidationRoot = el;
  });

  // ── $validate() — запускает валидацию, возвращает true/false ─────────────────
  Alpine.magic('validate', (el) => {
    return () => {
      let root = el;
      while (root && !root.hasAttribute('x-validate')) {
        root = root.parentElement;
      }
      return Alpine.store('validation').validate(root || document);
    };
  });

  // ── $validation — шортханд для $store.validation ─────────────────────────────
  Alpine.magic('validation', () => Alpine.store('validation'));
};

// ── Export ───────────────────────────────────────────────────────────────────
export default AlpineValidation;

if (typeof window !== 'undefined') {
  window.AlpineValidation = AlpineValidation;
  document.addEventListener('alpine:init', () => {
    if (window.Alpine) window.Alpine.plugin(AlpineValidation);
  });
}
