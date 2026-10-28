/* ==========================================================================
   admin_legacy.js - hkgravity back office
   Compatibility shims so the 2012 views (jQuery 1.7 / Bootstrap 2 / tableDnD / lightbox)
   keep working under jQuery 3.4.1 + Bootstrap 5 + DevExtreme, plus the helpers used by
   includes/dxgrid.php and the phase-1 Arabic translation of legacy pages.
   Loaded last, at the end of <body> (includes/footer.php).
   ========================================================================== */
(function ($, window, document) {
  'use strict';
  if (!$) return;

  var I18N = window.BO_I18N || {};
  var LANG = window.BO_LANG || 'ar';
  var BASE = window.BO_BASE || '';

  function t(key, fallback) { return (I18N && I18N[key] !== undefined) ? I18N[key] : (fallback !== undefined ? fallback : key); }
  function fmt(str) { var a = Array.prototype.slice.call(arguments, 1), i = 0; return String(str).replace(/%[sd]/g, function () { return a[i++]; }).replace(/%%/g, '%'); }

  /* ---------- #11 CKEditor language (before the window-load auto replace) ---------- */
  if (window.CKEDITOR) {
    try { window.CKEDITOR.config.language = LANG; window.CKEDITOR.config.width = '100%'; } catch (e) {}
  }

  /* ---------- #7 jQuery 1.x .toggle(fn, fn) polyfill ---------- */
  var _toggle = $.fn.toggle;
  $.fn.toggle = function () {
    var args = arguments;
    if (args.length >= 2 && typeof args[0] === 'function' && typeof args[1] === 'function') {
      var fns = Array.prototype.slice.call(args);
      return this.each(function () {
        var i = 0, el = this;
        $(el).on('click.bo_toggle', function (e) {
          var fn = fns[i % fns.length];
          i++;
          return fn.apply(el, [e]);
        });
      });
    }
    return _toggle.apply(this, args);
  };

  /* ---------- #2 tableDnD -> SortableJS (same API, same serialize() output) ---------- */
  $.tableDnD = $.tableDnD || {};
  $.tableDnD._last = null;
  $.tableDnD.serialize = function () {
    var tbl = $.tableDnD._last;
    if (!tbl) return '';
    return $(tbl).find('tbody tr[id]').map(function () { return tbl.id + '[]=' + this.id; }).get().join('&');
  };
  $.tableDnD.serializeTable = function (tbl) { $.tableDnD._last = tbl; return $.tableDnD.serialize(); };
  $.fn.tableDnD = function (opts) {
    opts = opts || {};
    return this.each(function () {
      var table = this, tb = table.tBodies && table.tBodies[0];
      if (!tb || !window.Sortable) return;
      if (table._boSortable) { try { table._boSortable.destroy(); } catch (e) {} }
      table._boSortable = new window.Sortable(tb, {
        animation: 120,
        draggable: 'tr',
        filter: 'input,select,a,button,textarea',
        preventOnFilter: false,
        onStart: function () { $.tableDnD._last = table; },
        onEnd: function (evt) {
          $.tableDnD._last = table;
          if (opts.onDrop) opts.onDrop(table, evt && evt.item ? evt.item : null);
        }
      });
      $.tableDnD._last = table;
    });
  };
  $.fn.tableDnDUpdate = function () { return this; };

  /* ---------- #6 legacy Bootstrap 2 modals: wrap in .modal-dialog > .modal-content ---------- */
  function wrapModal(el) {
    var $m = $(el);
    if (!$m.hasClass('modal') || $m.children('.modal-dialog').length) return;
    $m.removeClass('hide');
    var $content = $('<div class="modal-content"></div>').append($m.contents());
    $m.append($('<div class="modal-dialog modal-lg"></div>').append($content));
  }
  // early $.fn.modal so view scripts that run on DOM ready (before Bootstrap 5 registers its jQuery
  // interface) still get a real modal; Bootstrap replaces this function on DOMContentLoaded.
  if (!$.fn.modal && window.bootstrap && window.bootstrap.Modal) {
    $.fn.modal = function (action) {
      return this.each(function () {
        wrapModal(this);
        var m = window.bootstrap.Modal.getOrCreateInstance(this);
        if (action === 'hide') m.hide(); else if (action === 'toggle') m.toggle(); else if (action === 'show' || action === undefined || typeof action === 'object') m.show();
      });
    };
  }

  /* ---------- #10 BS2 glyph -> boxicons ---------- */
  var ICONS = {
    'icon-edit': 'bx-edit', 'icon-remove-sign': 'bx-x-circle', 'icon-th': 'bx-calendar', 'icon-envelope': 'bx-envelope',
    'icon-envelope-closed': 'bx-envelope', 'icon-envelope-opened': 'bx-message-detail', 'icon-book': 'bx-book',
    'icon-list': 'bx-list-ul', 'icon-list-alt': 'bx-list-check', 'icon-user': 'bx-user', 'icon-chevron-up': 'bx-chevron-up',
    'icon-chevron-down': 'bx-chevron-down', 'icon-film': 'bx-film', 'icon-signal': 'bx-bar-chart-alt-2',
    'icon-arrow-left': 'bx-arrow-back', 'icon-cart': 'bx-cart', 'icon-comment': 'bx-comment', 'icon-delete-message': 'bx-trash',
    'icon-filter': 'bx-filter', 'icon-globe': 'bx-globe', 'icon-home': 'bx-home-circle', 'icon-info-sign': 'bx-info-circle',
    'icon-lock': 'bx-lock', 'icon-off': 'bx-power-off', 'icon-refresh': 'bx-refresh', 'icon-star-on': 'bx-star', 'icon-star': 'bx-star',
    'icon-tasks': 'bx-task', 'icon-trash': 'bx-trash', 'icon-wrench': 'bx-wrench', 'icon-remove': 'bx-x', 'icon-plus': 'bx-plus',
    'icon-ok': 'bx-check', 'icon-search': 'bx-search', 'icon-download': 'bx-download', 'icon-download-alt': 'bx-download',
    'icon-upload': 'bx-upload', 'icon-picture': 'bx-image', 'icon-calendar': 'bx-calendar', 'icon-cog': 'bx-cog',
    'icon-eye-open': 'bx-show', 'icon-share': 'bx-share', 'icon-arrow-right': 'bx-right-arrow-alt', 'icon-folder-open': 'bx-folder-open',
    'icon-folder-close': 'bx-folder', 'icon-file': 'bx-file', 'icon-print': 'bx-printer', 'icon-pencil': 'bx-pencil',
    'icon-question-sign': 'bx-help-circle', 'icon-exclamation-sign': 'bx-error-circle', 'icon-th-list': 'bx-list-ul',
    'icon-th-large': 'bx-grid-alt', 'icon-zoom-in': 'bx-zoom-in', 'icon-zoom-out': 'bx-zoom-out', 'icon-minus': 'bx-minus'
  };
  function remapIcons(root) {
    $(root || document).find('i[class*="icon-"], span[class*="icon-"]').each(function () {
      var cls = this.className.split(/\s+/), out = [], hit = false;
      for (var i = 0; i < cls.length; i++) {
        if (ICONS[cls[i]]) { out.push(ICONS[cls[i]]); hit = true; }
        else if (cls[i] === 'icon-white' || /^icon-/.test(cls[i]) || cls[i] === 'icon32' || cls[i] === 'icon-color') { /* drop */ }
        else if (cls[i]) out.push(cls[i]);
      }
      if (hit) { out.unshift('bx'); this.className = out.join(' '); }
    });
  }

  /* ---------- #5 BS2 -> BS5 data attributes ---------- */
  function upgradeAttributes(root) {
    var $r = $(root || document);
    $.each(['toggle', 'dismiss', 'parent', 'target', 'placement', 'content', 'original-title'], function (_, a) {
      $r.find('[data-' + a + ']').each(function () {
        if (!this.hasAttribute('data-bs-' + a)) this.setAttribute('data-bs-' + a, this.getAttribute('data-' + a));
      });
    });
    // BS2 read data-parent on the toggle; BS5 reads data-bs-parent on the collapse element itself
    $r.find('[data-bs-toggle="collapse"][data-bs-parent]').each(function () {
      var parent = this.getAttribute('data-bs-parent'), sel = this.getAttribute('data-bs-target') || this.getAttribute('href') || '';
      this.removeAttribute('data-bs-parent');
      this.removeAttribute('data-parent');
      if (/^#[\w-]+$/.test(sel)) {
        var target = document.getElementById(sel.slice(1));
        if (target && !target.hasAttribute('data-bs-parent')) target.setAttribute('data-bs-parent', parent);
      }
    });
    // users.php: plain "Create New User" link in the breadcrumb -> same button as the other lists
    if (!document.getElementById('topLink')) {
      $r.find('ul.breadcrumb li.pull-right > a:only-child:not(.btn)').filter(function () { return /\/add\/?$/.test(this.getAttribute('href') || ''); }).first().attr('id', 'topLink');
    }
    // Sneat main1.js toggles .active on the closest .accordion-item on every collapse event
    $r.find('.accordion-group').addClass('accordion-item');
    $r.find('.collapse.in').addClass('show');
    $r.find('.modal.hide').removeClass('hide');
    $r.find('.modal').each(function () { wrapModal(this); });
    $r.find('[data-bs-toggle="dropdown"] + .dropdown-menu').addClass('dropdown-menu-end');
  }

  /* ---------- #9 image submit buttons -> real buttons ---------- */
  function replaceImageSubmits(root) {
    $(root || document).find('input[type=image].btn, input[type=image][src*="bt-send-form"], input[type=image][src*="bt-login"]').each(function () {
      var $b = $('<button type="submit" class="btn btn-primary bo-img-submit"></button>').text(t('form_save', 'Save Changes'));
      if (this.name) $b.attr('name', this.name);
      $(this).replaceWith($b);
    });
  }

  /* ---------- #3 image popup (replaces lightbox 0.5) ---------- */
  window.popup = function (imageUrl) {
    $('#popup-image img').attr('src', imageUrl);
    $('#popup-image').css('display', 'block');
  };
  window.popup_close = function () { $('#popup-image').css('display', 'none'); };

  /* ---------- #13 grid helpers ---------- */
  window.stripHtml = function (html) {
    if (html === null || html === undefined) return '';
    var tmp = document.createElement('DIV');
    tmp.innerHTML = String(html);
    return (tmp.textContent || tmp.innerText || '').replace(/\s+/g, ' ').trim();
  };
  window.bo_delete_row = function (module, id) {
    if (!window.confirm(t('grid_confirm_delete'))) return false;
    window.location = BASE + 'back_office/' + module + '/delete/' + id;
    return false;
  };
  if (typeof window.delete_row !== 'function') {
    window.delete_row = function (module, id) {
      if (id === undefined) return false; // legacy pages define their own delete_row(id)
      window.location = BASE + 'back_office/' + module + '/delete/' + id;
      return false;
    };
  }

  /* ---------- #12 phase-1 translation of legacy pages (Arabic only) ---------- */
  var VERB_RE = /^(List|Add \/ Edit|Create New|Add New|Add|Edit|New)\s+(.+)$/i;
  var QUAL_RE = /^(All|Unread|Archived)\s+(.+)$/i;
  var SECTION_RE = /^(.+?)\s+Section$/i;
  function normalize(s) { return String(s).replace(/\u00a0/g, ' ').replace(/\s+/g, ' ').trim(); }
  var LEGACY = null;
  function legacyMap() {
    if (LEGACY) return LEGACY;
    LEGACY = {};
    var src = I18N.legacy || {};
    for (var k in src) if (Object.prototype.hasOwnProperty.call(src, k)) LEGACY[normalize(k).toLowerCase()] = src[k];
    return LEGACY;
  }
  // allowAlias: legacy spellings such as "subject" / "to do" only count after a verb ("List subject"),
  // so a bare "Subject" column header stays a field caption (الموضوع)
  function moduleKey(name, allowAlias) {
    var mods = I18N.modules || {}, alias = I18N.alias || {};
    var used = normalize(name).toLowerCase().replace(/ /g, '_');
    if (mods[used]) return used;
    if (allowAlias && alias[used] && mods[alias[used]]) return alias[used];
    return null;
  }
  function currentModuleKey() {
    var m = /\/back_office\/([a-z_]+)/.exec(window.location.pathname || '');
    return m ? moduleKey(m[1]) : null;
  }
  function moduleLabel(name, kind, allowAlias) {
    var k = moduleKey(name, allowAlias);
    return k ? I18N.modules[k][kind || 'label'] : null;
  }
  // "Laungage *:" -> core "laungage", tail "*:" (kept on output)
  function splitTail(s) {
    var i = s.search(/[\s:*]+$/);
    return i > 0 ? { core: s.slice(0, i), tail: s.slice(i).replace(/^\s+/, '') } : { core: s, tail: '' };
  }
  // message pages: "Subject" is the message subject (الموضوع), elsewhere the school subject (المادة)
  var MSG_PAGE = /\/back_office\/(messages|orderonline|contactform)(\/|$)/.test(window.location.pathname || '');
  function translatePhrase(raw) {
    var fields = I18N.fields || {}, legacy = legacyMap();
    if (MSG_PAGE && I18N.fieldnames) fields = $.extend({}, fields, I18N.fieldnames);
    var s = normalize(raw);
    if (!s) return null;
    var low = s.toLowerCase();
    if (legacy[low] !== undefined) return legacy[low];
    var st = splitTail(s), core = st.core.toLowerCase();
    if (st.tail && legacy[core] !== undefined) return legacy[core] + st.tail;
    var m = VERB_RE.exec(s);
    if (m) {
      var verb = m[1].toLowerCase(), rest = m[2], qual = '', q = QUAL_RE.exec(rest);
      if (q && moduleKey(q[2], true)) { qual = ' (' + t('grid_' + q[1].toLowerCase(), q[1]) + ')'; rest = q[2]; }
      var key = moduleKey(rest, true), label = null, one = null, list = null;
      // a title that abbreviates the current controller ("List official exams" on official_exams_init)
      var cur = currentModuleKey(), restKey = normalize(rest).toLowerCase().replace(/ /g, '_');
      if (cur && cur !== key && cur.indexOf(restKey + '_') === 0) key = cur;
      if (key) { label = I18N.modules[key].label; one = I18N.modules[key].one || label; list = I18N.modules[key].list; }
      else if (legacy[rest.toLowerCase()] !== undefined) { label = one = legacy[rest.toLowerCase()]; }
      if (label) {
        if (verb === 'list') return (list || fmt(t('grid_list_x'), label)) + qual;
        if (verb === 'add') return fmt(t('grid_add_x'), one);
        if (verb === 'edit') return fmt(t('grid_edit_x'), one);
        if (verb === 'new' || verb === 'create new' || verb === 'add new') return fmt(t('grid_new_x'), one);
        return fmt(t('grid_add_edit_x'), one);
      }
    }
    m = SECTION_RE.exec(s);
    if (m) { var sl = moduleLabel(m[1], 'label', true); if (sl) return fmt(t('grid_section_x', '%s'), sl); }
    var ml = moduleLabel(s);
    if (ml) return ml;
    core = core.replace(/^[\s:*]+/g, '');
    if (fields[core] !== undefined) return fields[core] + st.tail;
    return null;
  }
  var MEMO = {};
  function translateCached(s) {
    if (Object.prototype.hasOwnProperty.call(MEMO, s)) return MEMO[s];
    return (MEMO[s] = translatePhrase(s));
  }
  var SKIP_SEL = '.dx-datagrid, .dx-widget, script, style, textarea';
  // leading separators ("/ List unit" after a breadcrumb link) and surrounding blanks are kept as they are
  var PARTS_RE = /^([\s\u00a0\/|]*)([\s\S]*?)([\s\u00a0]*)$/;
  function translateTextNodes(el, attrs) {
    if (el.closest && el.closest(SKIP_SEL)) return;
    for (var node = el.firstChild; node; node = node.nextSibling) {
      if (node.nodeType !== 3) continue;
      var p = PARTS_RE.exec(node.nodeValue);
      if (!p || !p[2]) continue;
      var out = translateCached(p[2]);
      if (out !== null) node.nodeValue = p[1] + out + p[3];
    }
    if (attrs) {
      for (var i = 0; i < attrs.length; i++) {
        var v = el.getAttribute(attrs[i]);
        if (v) { var o = translateCached(v); if (o !== null) el.setAttribute(attrs[i], o); }
      }
    }
  }
  // breadcrumb links to a module ("Subject" -> back_office/material) take that module's label
  function translateBreadcrumbLinks(root) {
    $(root || document).find('ul.breadcrumb a[href*="back_office/"]').each(function () {
      if (this.id === 'topLink' || this.children.length || /bbtnb/.test(this.className)) return;
      // crumbs of a grid parent chain (dxgrid subtitle) already carry the right text ("Subjects" / المواد)
      if ($(this).closest('.bo-subtitle').length) return;
      if (legacyMap()[normalize(this.textContent).toLowerCase()] !== undefined) return;
      var m = /back_office\/([a-z_]+)\/?$/.exec(this.getAttribute('href') || '');
      var label = m ? moduleLabel(m[1]) : null;
      if (label && normalize(this.textContent)) this.textContent = label;
    });
  }
  var TR_SEL = [
    'ul.breadcrumb li', 'ul.breadcrumb li a', '#topLink', 'p.alert-info', 'div.alert-info', '.alert-success', '.alert-error',
    'th', 'th a', 'thead td', 'tfoot td', 'a.table-edit-link', 'a.table-delete-link', 'a.table-activate-link',
    'select[name=check_option] option', '.btn_mrg span', 'tr.odd td', 'a.gallery', 'small.blue', 'span.blue',
    'label.field-title', 'legend', 'button.btn', 'a.btn', 'span.btn', 'input[type=submit].btn', '.modal-footer .btn', 'h4', 'h3', 'h2.logo',
    'label.checkbox', 'label.radio', 'td small', 'option', 'a.accordion-toggle', 'ul.nav-list li.active a', 'ul.nav-list li.active a span',
    'span.label', 'a.cur', 'a.button', 'a.button span'
  ].join(',');
  function translateLegacy(root) {
    if (LANG !== 'ar') return;
    var r = root || document;
    translateBreadcrumbLinks(r);
    // one querySelectorAll + memoised phrases: long legacy tables (visitor, 55k rows) repeat the same few strings
    var els = (r.querySelectorAll ? r : document).querySelectorAll(TR_SEL);
    for (var i = 0; i < els.length; i++) {
      var el = els[i];
      if (el.tagName === 'INPUT') { var o = translateCached(el.value); if (o !== null) el.value = o; continue; }
      translateTextNodes(el, ['title', 'placeholder']);
    }
  }
  // English: legacy add/edit pages print the controller title as is ("Add official exam"); give their breadcrumb the
  // same Title-case module wording as the <title> (bo_page_title) and the list's add link ("Add Official Exam")
  function titleBreadcrumbsEn(root) {
    if (LANG !== 'en') return;
    var r = root || document;
    translateBreadcrumbLinks(r);
    var els = (r.querySelectorAll ? r : document).querySelectorAll('ul.breadcrumb li');
    for (var i = 0; i < els.length; i++) {
      if (els[i].closest && els[i].closest('.bo-subtitle, ' + SKIP_SEL)) continue;
      for (var node = els[i].firstChild; node; node = node.nextSibling) {
        if (node.nodeType !== 3) continue;
        var p = PARTS_RE.exec(node.nodeValue), s = p ? normalize(p[2]) : '';
        if (!s || (!VERB_RE.test(s) && !moduleKey(s))) continue;
        var out = translateCached(s);
        if (out !== null) node.nodeValue = p[1] + out + p[3];
      }
    }
  }
  if (LANG === 'ar') {
    // inline onclick="return confirm('Are you sure ...')" of the legacy lists
    var _confirm = window.confirm;
    window.confirm = function (msg) {
      var o = null;
      try { o = translateCached(String(msg)); } catch (e) {}
      return _confirm.call(window, o !== null ? o : msg);
    };
  }

  /* ---------- run now: footer.php loads this file after the page content ---------- */
  // jQuery 3 runs ready callbacks asynchronously and in registration order, so view scripts such as
  // practice_result's "#popup=failed" deep link run before our ready handler: the legacy modals must be
  // wrapped (.modal-dialog) before that, or Bootstrap 5 builds a Modal without a dialog.
  try { upgradeAttributes(document); } catch (e) {}

  /* ---------- DOM ready ---------- */
  $(function () {
    upgradeAttributes(document);
    remapIcons(document);
    replaceImageSubmits(document);
    translateLegacy(document);
    titleBreadcrumbsEn(document);

    // #1 check-all (scoped to the form, .prop semantics)
    $(document).on('change', '#checkAllAuto', function () {
      var $f = $(this).closest('form');
      ($f.length ? $f : $(document)).find('input[type=checkbox][name^=cehcklist]').prop('checked', this.checked);
    });
    $(document).on('click', '.checkAllAuto', function () {
      var id = this.id;
      $('INPUT.checkAllAuto' + id + "[type='checkbox']").prop('checked', $('.checkAllAuto_' + id).is(':checked'));
    });

    // #3 image popup
    $(document).on('click', 'a.gallery', function (e) {
      var href = this.getAttribute('href');
      if (href && /\.(jpe?g|png|gif|webp|bmp)(\?.*)?$/i.test(href)) { e.preventDefault(); window.popup(href); }
    });
    $(document).on('click', '#popup-image .close-button, #popup-image', function (e) {
      if (e.target === this || $(e.target).hasClass('close-button')) window.popup_close();
    });
    $(document).on('keyup', function (e) { if (e.key === 'Escape') window.popup_close(); });

    // #4 datepicker (eyecon build; .input-append.date[data-date-format])
    if ($.fn.datepicker) {
      $('.date').each(function () { try { $(this).datepicker(); } catch (e) {} });
      $(document).on('click', '.input-append.date .add-on', function () { $(this).siblings('input').trigger('focus'); });
    }

    // #8 cheap no-ops kept for safety
    $('.btn-minimize').on('click', function (e) {
      e.preventDefault();
      var $target = $(this).parent().parent().next('.box-content');
      $target.slideToggle();
    });
    $('.btn-close.btn-round').on('click', function (e) { e.preventDefault(); $(this).parent().parent().parent().fadeOut(); });
    $('#myTab a').on('click', function (e) {
      e.preventDefault();
      if (window.bootstrap && window.bootstrap.Tab) window.bootstrap.Tab.getOrCreateInstance(this).show();
    });

    // recycle bin "Open All / Close All" (#open_all1): toggle .show on every group instead of the BS2 height
    var $openAll = $('#open_all1');
    if ($openAll.length && $('.recycle_col').length) {
      var allOpen = false;
      var setLabel = function () { $openAll.html((allOpen ? '&and;' : '&or;') + '&nbsp;' + t(allOpen ? 'grid_close_all' : 'grid_open_all', allOpen ? 'Close All' : 'Open All')); };
      $openAll.off('click.bo_toggle').on('click', function (e) {
        e.preventDefault();
        allOpen = !allOpen;
        $('.recycle_col').each(function () {
          this.style.height = '';
          $(this).toggleClass('show', allOpen);
          $('[href="#' + this.id + '"]').toggleClass('collapsed', !allOpen).attr('aria-expanded', allOpen ? 'true' : 'false');
          $(this).closest('.accordion-item').toggleClass('active', allOpen);
        });
        setLabel();
      });
      setLabel();
    }

    // legacy .close buttons inside modal headers (data-dismiss copied above)
    $(document).on('click', '.modal .close:not([data-bs-dismiss])', function () {
      var $m = $(this).closest('.modal');
      if ($m.length && window.bootstrap) window.bootstrap.Modal.getOrCreateInstance($m[0]).hide();
    });
  });

  // expose for pages that render content later (ajax)
  window.boLegacy = { upgradeAttributes: upgradeAttributes, remapIcons: remapIcons, replaceImageSubmits: replaceImageSubmits, translate: translateLegacy, wrapModal: wrapModal, t: t, fmt: fmt };
})(window.jQuery, window, document);
