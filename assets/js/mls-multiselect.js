/* mls-multiselect.js - Minimal reusable multi-select (v2)
   Fixes: dropdown toggle, select/clear all, multi-init by class, reliable init
   Usage: window.MLSSelectLocation.init('.mls-multiselect');
*/

window.MLSSelectLocation = (function () {
  function $(sel, ctx) { return (ctx || document).querySelector(sel); }
  function $all(sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); }
  function escapeAttr(s){ return String(s).replace(/"/g,'&quot;'); }

  function buildFromSelect(select) {
    const wrapper = document.createElement('div');
    wrapper.className = 'mls-multi-wrapper';

    const display = document.createElement('div');
    display.className = 'mls-multi-display';
    display.setAttribute('tabindex','0');
    display.innerHTML = `
      <div class="mls-placeholder">${select.dataset.placeholder || select.getAttribute('data-placeholder') || mlsTranslations.search_area || 'Select' }</div>
      <div class="mls-tags" aria-hidden="true"></div>
    `;
    wrapper.appendChild(display);

    const dropdown = document.createElement('div');
    dropdown.className = 'mls-multi-dropdown';
    dropdown.innerHTML = `
      <div class="mls-multi-header">
        <button type="button" class="mls-multi-select-all">${mlsTranslations.search_select_dropdown_selectall}</button>
        <button type="button" class="mls-multi-clear-all">${mlsTranslations.search_select_dropdown_clearall}</button>
      </div>
      <div style="padding:8px;">
        <input type="search" class="mls-multi-search" placeholder="${select.dataset.searchPlaceholder || mlsTranslations.search_select_dropdown_search || 'Search...'}">
      </div>
      <ul class="mls-multi-list"></ul>
    `;
    wrapper.appendChild(dropdown);

    select.style.display = 'none';
    select.classList.add('mls-multiselect-hidden');
    select.parentNode.insertBefore(wrapper, select);
    wrapper.appendChild(select);

    return { wrapper, display, dropdown, select };
  }

  function renderList(ctx) {
    const select = ctx.select;
    const list = ctx.dropdown.querySelector('.mls-multi-list');
    list.innerHTML = '';
    const opts = Array.from(select.options).filter(o => o.value !== '');

    if (opts.length === 0) {
      list.innerHTML = '<li class="mls-no-results">No options</li>';
      return;
    }

    opts.forEach(opt => {
      const li = document.createElement('li');
      li.setAttribute('data-value', opt.value);
      li.innerHTML = `
        <label><input type="checkbox" class="mls-checkbox" ${opt.selected ? 'checked' : ''} value="${escapeAttr(opt.value)}">
        <span class="mls-label">${opt.textContent}</span></label>
      `;
      list.appendChild(li);
    });
  }

  function updateDisplay(ctx) {
    const select = ctx.select;
    const tagsWrap = ctx.display.querySelector('.mls-tags');
    const placeholder = ctx.display.querySelector('.mls-placeholder');
    tagsWrap.innerHTML = '';

    const selected = Array.from(select.selectedOptions).filter(o => o.value !== '');
    if (selected.length === 0) {
      placeholder.style.display = '';
    } else {
      placeholder.style.display = 'none';
      const maxTags = 3;
      selected.slice(0, maxTags).forEach(opt => {
        const tag = document.createElement('span');
        tag.className = 'mls-tag';
        tag.textContent = opt.textContent;
        tagsWrap.appendChild(tag);
      });
      if (selected.length > maxTags) {
        const more = document.createElement('span');
        more.className = 'mls-tag';
        more.textContent = `+${selected.length - maxTags} more`;
        tagsWrap.appendChild(more);
      }
    }
  }

  function applyFilter(ctx, txt) {
    txt = (txt || '').trim().toLowerCase();
    const items = ctx.dropdown.querySelectorAll('.mls-multi-list li');
    let visible = 0;
    items.forEach(li => {
      if (li.classList.contains('mls-no-results')) return;
      const label = li.querySelector('.mls-label').textContent.toLowerCase();
      const show = label.indexOf(txt) !== -1;
      li.style.display = show ? '' : 'none';
      if (show) visible++;
    });
    let noRes = ctx.dropdown.querySelector('.mls-no-results');
    if (!visible) {
      if (!noRes) {
        noRes = document.createElement('li');
        noRes.className = 'mls-no-results';
        noRes.textContent = 'No results found';
        ctx.dropdown.querySelector('.mls-multi-list').appendChild(noRes);
      }
      noRes.style.display = '';
    } else if (noRes) {
      noRes.style.display = 'none';
    }
  }

  function syncCheckboxToSelect(ctx, value, isChecked) {
    const opt = ctx.select.querySelector(`option[value="${CSS.escape(value)}"]`);
    if (opt) {
      opt.selected = isChecked;
      const evt = new Event('change', { bubbles: true });
      ctx.select.dispatchEvent(evt);
    }
  }

  function initOne(node) {
    const select = node;
    if (!select) return null;

    if (select.parentNode?.classList.contains('mls-multi-wrapper')) {
      const prevWrapper = select.parentNode;
      prevWrapper.parentNode.insertBefore(select, prevWrapper);
      prevWrapper.remove();
      select.style.display = '';
    }

    const ctx = buildFromSelect(select);
    renderList(ctx);
    updateDisplay(ctx);

    const dropdown = ctx.dropdown;
    const display = ctx.display;
    const search = dropdown.querySelector('.mls-multi-search');
    const list = dropdown.querySelector('.mls-multi-list');

    // toggle dropdown open/close
    display.addEventListener('click', e => {
      e.stopPropagation();
      dropdown.classList.toggle('open');
      if (dropdown.classList.contains('open')) search.focus();
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', e => {
      if (!ctx.wrapper.contains(e.target)) dropdown.classList.remove('open');
    });

    // search filter
    search.addEventListener('input', () => applyFilter(ctx, search.value));

    // select all
    dropdown.querySelector('.mls-multi-select-all').addEventListener('click', e => {
      e.preventDefault();
      const boxes = list.querySelectorAll('input[type=checkbox]:not(:checked):not([disabled])');
      boxes.forEach(cb => { cb.checked = true; syncCheckboxToSelect(ctx, cb.value, true); });
      updateDisplay(ctx);
    });

    // clear all
    dropdown.querySelector('.mls-multi-clear-all').addEventListener('click', e => {
      e.preventDefault();
      const boxes = list.querySelectorAll('input[type=checkbox]:checked');
      boxes.forEach(cb => { cb.checked = false; syncCheckboxToSelect(ctx, cb.value, false); });
      updateDisplay(ctx);
    });

    // checkbox click
    list.addEventListener('change', e => {
      const target = e.target;
      if (target.matches('input[type=checkbox]')) {
        syncCheckboxToSelect(ctx, target.value, target.checked);
        search.value = '';
        applyFilter(ctx, '');
        updateDisplay(ctx);
      }
    });

    // sync external select changes
    select.addEventListener('change', () => updateDisplay(ctx));

    return ctx;
  }

  function init(selector) {
    const nodes = typeof selector === 'string' ? document.querySelectorAll(selector) : [selector];
    const results = [];
    nodes.forEach(sel => {
      const ctx = initOne(sel);
      if (ctx) results.push(ctx);
    });
    return results;
  }

  // return public API
  return { init };
})();

// Auto-init when DOM ready
document.addEventListener('DOMContentLoaded', function(){
  if (window.MLSSelectLocation) window.MLSSelectLocation.init('.mls-multiselect');
});

/* ============================================================
   GROUPED MULTISELECT DROPDOWN (for Property Types)
   ============================================================ */

/**
 * MLSSelecttypesAccordion v2.3
 * Works with <select multiple> having data-parent attributes
 * Supports nested parent/child accordion, search, select/clear all, and synced parent-child selection
 */

(function (window, document) {
  const MLSSelecttypes = {
    init(selector = '.mls_type_sel') {
      document.querySelectorAll(selector).forEach((select) => {
        if (select.dataset.mlsInit) return;
        select.dataset.mlsInit = '1';
        this.createDropdown(select);
      });
    },

    createDropdown(select) {
      select.style.display = 'none';
      const wrapper = document.createElement('div');
      wrapper.className = 'mls-select-wrapper';
      select.parentNode.insertBefore(wrapper, select);
      wrapper.appendChild(select);

      const display = document.createElement('div');
      display.className = 'mls-display';
      display.textContent = mlsTranslations.search_property_type;
      wrapper.appendChild(display);

      const dropdown = document.createElement('div');
      dropdown.className = 'mls-dropdown';
      dropdown.style.display = 'none'; // replaced hidden class logic
      dropdown.innerHTML = `
        <div class="mls-actions">
          <button type="button" class="mls-select-all">${mlsTranslations.search_select_dropdown_selectall}</button>
    <button type="button" class="mls-clear-all">${mlsTranslations.search_select_dropdown_clearall}</button>
    <input type="text" class="mls-search" placeholder="${mlsTranslations.search_select_dropdown_search}">
        </div>
        <ul class="mls-options"></ul>
      `;
      wrapper.appendChild(dropdown);

      const ul = dropdown.querySelector('.mls-options');
      const searchInput = dropdown.querySelector('.mls-search');
      const selectAllBtn = dropdown.querySelector('.mls-select-all');
      const clearAllBtn = dropdown.querySelector('.mls-clear-all');

      // Build parent-child tree
      const options = Array.from(select.options);
      const parents = {};
      const children = {};

      options.forEach((opt) => {
        const parent = opt.dataset.parent?.trim() || '';
        if (parent === '') {
          parents[opt.text] = opt;
        } else {
          if (!children[parent]) children[parent] = [];
          children[parent].push(opt);
        }
      });

      // Build UI
      Object.keys(parents).forEach((label) => {
        const opt = parents[label];
        const li = document.createElement('li');
        li.className = 'mls-parent';
        li.dataset.value = opt.value;
        li.dataset.label = label;

        const hasChildren = !!children[label];
        li.innerHTML = `
          <label><input type="checkbox" value="${opt.value}"> ${label}</label>
          ${hasChildren ? '<span class="mls-toggle">+</span>' : ''}
        `;

        if (hasChildren) {
          const subUl = document.createElement('ul');
          subUl.className = 'mls-child hidden';
          children[label].forEach((child) => {
            const subLi = document.createElement('li');
            subLi.dataset.parent = label;
            subLi.innerHTML = `
              <label><input type="checkbox" value="${child.value}"> ${child.text}</label>
            `;
            subUl.appendChild(subLi);
          });
          li.appendChild(subUl);
        }

        ul.appendChild(li);
      });

      // Restore selected state
     options.forEach((opt) => {
  if (opt.selected) {
    const cb = ul.querySelector(`input[value="${CSS.escape(opt.value)}"]`);
    if (cb) cb.checked = true;
  }
});
this.updateDisplay(select, display);

      // --- Dropdown toggle ---
      display.addEventListener('click', (e) => {
        e.stopPropagation();
        const isVisible = dropdown.style.display === 'block';
        dropdown.style.display = isVisible ? 'none' : 'block';
      });

      document.addEventListener('click', (e) => {
        if (!wrapper.contains(e.target)) dropdown.style.display = 'none';
      });

      // --- Checkbox change logic (Enhanced) ---
      ul.addEventListener('change', (e) => {
        const input = e.target;
        const val = input.value;
        const checked = input.checked;

        // Sync the <select> option
        const opt = Array.from(select.options).find((o) => o.value === val);
        if (opt) opt.selected = checked;

        const li = input.closest('li');
        const isParent = li.classList.contains('mls-parent');
        const parentName = li.dataset.parent || '';

        // --- Parent clicked: update all its children ---
        if (isParent) {
          const label = li.dataset.label;
          const childLis = ul.querySelectorAll(`li[data-parent="${label}"]`);
          childLis.forEach((childLi) => {
            const childCb = childLi.querySelector('input[type="checkbox"]');
            if (childCb) childCb.checked = checked;

            const childOpt = Array.from(select.options).find((o) => o.value === childCb.value);
            if (childOpt) childOpt.selected = checked;
          });
        }

        // --- Child clicked: update parent based on siblings ---
        if (parentName) {
          const siblings = ul.querySelectorAll(`li[data-parent="${parentName}"] input[type="checkbox"]`);
          const allChecked = Array.from(siblings).every((cb) => cb.checked);
          const someChecked = Array.from(siblings).some((cb) => cb.checked);

          const parentLi = ul.querySelector(`li.mls-parent[data-label="${parentName}"]`);
          if (parentLi) {
            const parentCb = parentLi.querySelector('input[type="checkbox"]');
            if (parentCb) {
              parentCb.checked = allChecked;
              const parentOpt = Array.from(select.options).find((o) => o.value === parentCb.value);
              if (parentOpt) parentOpt.selected = allChecked;
            }
          }
        }

        this.updateDisplay(select, display);
      });

      // --- Select/Clear all ---
      selectAllBtn.addEventListener('click', (e) => {
        e.preventDefault();
        ul.querySelectorAll('input[type="checkbox"]').forEach((cb) => (cb.checked = true));
        Array.from(select.options).forEach((opt) => (opt.selected = true));
        this.updateDisplay(select, display);
      });

      clearAllBtn.addEventListener('click', (e) => {
        e.preventDefault();
        ul.querySelectorAll('input[type="checkbox"]').forEach((cb) => (cb.checked = false));
        Array.from(select.options).forEach((opt) => (opt.selected = false));
        this.updateDisplay(select, display);
      });

      // --- Expand/collapse children ---
      ul.addEventListener('click', (e) => {
        if (e.target.classList.contains('mls-toggle')) {
          const parentLi = e.target.closest('li');
          const subUl = parentLi.querySelector('.mls-child');
          subUl.classList.toggle('hidden');
          e.target.textContent = subUl.classList.contains('hidden') ? '+' : '−';
        }
      });

      // --- Search ---
      searchInput.addEventListener('click', (e) => e.stopPropagation());
      searchInput.addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        ul.querySelectorAll('li').forEach((li) => {
          const txt = li.textContent.toLowerCase();
          li.style.display = txt.includes(term) ? '' : 'none';
        });
      });
    },

    updateDisplay(select, display) {
      const selected = Array.from(select.selectedOptions);

      if (selected.length === 0) {
       display.textContent = mlsTranslations.search_property_type || 'Select types...';
      } else {
        display.textContent = selected.map((o) => o.text).join(', ');
      }
    },
  };

  window.MLSSelecttypes = MLSSelecttypes;
})(window, document);


