/* ==========================================================================
   STED dialog - styled replacement for the browser alert / confirm popups.
   Styles: css/modern.css ("Dialog" section). No dependencies.

   mxAlert(text, type?, title?)        -> Promise (resolves when closed)
   mxConfirm(text, options?)           -> Promise<boolean>
   mxDialog({ type, title, text, confirmText, cancelText, cancel, value, copy })

   type: 'info' | 'success' | 'error' | 'warning' | 'confirm' | 'danger'
   window.alert is also routed here, so any leftover alert() looks the same.
   ========================================================================== */
(function () {
	'use strict';

	var TYPES = {
		info:    { icon: 'fa-circle-info',            title: 'Notice' },
		success: { icon: 'fa-check',                  title: 'Done!' },
		error:   { icon: 'fa-xmark',                  title: 'Something went wrong' },
		warning: { icon: 'fa-triangle-exclamation',   title: 'Please check' },
		confirm: { icon: 'fa-question',               title: 'Are you sure?' },
		danger:  { icon: 'fa-trash-can',              title: 'Are you sure?' }
	};
	var nativeAlert = window.alert ? window.alert.bind(window) : null;
	var queue = Promise.resolve();
	var uid = 0;

	function el(tag, cls, text) {
		var n = document.createElement(tag);
		if (cls) n.className = cls;
		if (text != null) n.textContent = text;
		return n;
	}

	function show(o) {
		return new Promise(function (resolve) {
			var t = TYPES[o.type] ? o.type : 'info';
			var id = 'mxDlg' + (++uid);
			var lastFocus = document.activeElement;

			var root = el('div', 'mx-dlg');
			root.setAttribute('role', o.cancel ? 'alertdialog' : 'dialog');
			root.setAttribute('aria-modal', 'true');
			root.setAttribute('aria-labelledby', id + 't');
			root.setAttribute('aria-describedby', id + 'd');

			var card = el('div', 'mx-dlg-card is-' + t);
			var close = el('button', 'mx-dlg-x');
			close.type = 'button';
			close.setAttribute('aria-label', 'Close');
			close.innerHTML = '<i class="fas fa-xmark" aria-hidden="true"></i>';

			var icon = el('div', 'mx-dlg-icon');
			icon.innerHTML = '<i class="fas ' + TYPES[t].icon + '" aria-hidden="true"></i>';

			var title = el('h3', 'mx-dlg-title', o.title || TYPES[t].title);
			title.id = id + 't';
			var text = el('p', 'mx-dlg-text', o.text || '');
			text.id = id + 'd';

			card.appendChild(close);
			card.appendChild(icon);
			card.appendChild(title);
			if (o.text) card.appendChild(text);

			var input = null;
			if (o.value != null) {
				input = el('input', 'mx-dlg-input');
				input.type = 'text';
				input.readOnly = true;
				input.value = o.value;
				card.appendChild(input);
			}

			var actions = el('div', 'mx-dlg-actions');
			var cancelBtn = null;
			if (o.cancel) {
				cancelBtn = el('button', 'mx-dlg-btn mx-dlg-btn-ghost', o.cancelText || 'Cancel');
				cancelBtn.type = 'button';
				actions.appendChild(cancelBtn);
			}
			var okBtn = el('button', 'mx-dlg-btn mx-dlg-btn-main', o.confirmText || (o.cancel ? 'Yes, continue' : 'OK'));
			okBtn.type = 'button';
			actions.appendChild(okBtn);
			card.appendChild(actions);
			root.appendChild(card);
			document.body.appendChild(root);
			document.body.classList.add('mx-dlg-lock');

			var done = false;
			function finish(result) {
				if (done) return;
				done = true;
				root.classList.remove('is-open');
				document.removeEventListener('keydown', onKey, true);
				setTimeout(function () {
					root.remove();
					if (!document.querySelector('.mx-dlg')) document.body.classList.remove('mx-dlg-lock');
					if (lastFocus && lastFocus.focus) { try { lastFocus.focus(); } catch (e) {} }
					resolve(result);
				}, 260);
			}

			function onKey(e) {
				if (e.key === 'Escape') { e.preventDefault(); e.stopPropagation(); finish(false); }
				else if (e.key === 'Tab') {
					// keep focus inside the dialog
					var f = card.querySelectorAll('button, input');
					var first = f[0], last = f[f.length - 1];
					if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
					else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
				}
			}

			okBtn.addEventListener('click', function () {
				if (input && o.copy) {
					input.select();
					try { document.execCommand('copy'); } catch (e) {}
				}
				finish(true);
			});
			if (cancelBtn) cancelBtn.addEventListener('click', function () { finish(false); });
			close.addEventListener('click', function () { finish(false); });
			root.addEventListener('mousedown', function (e) { if (e.target === root) finish(false); });
			document.addEventListener('keydown', onKey, true);

			requestAnimationFrame(function () {
				root.classList.add('is-open');
				if (input) { input.focus(); input.select(); } else okBtn.focus();
			});
		});
	}

	// one dialog at a time - later calls wait for the current one to close
	window.mxDialog = function (o) {
		var p = queue.then(function () { return show(o || {}); });
		queue = p.catch(function () {});
		return p;
	};

	window.mxAlert = function (text, type, title) {
		return window.mxDialog({ type: type || 'info', text: text, title: title });
	};

	window.mxConfirm = function (text, opts) {
		opts = opts || {};
		return window.mxDialog({
			type: opts.danger ? 'danger' : 'confirm',
			text: text,
			title: opts.title,
			confirmText: opts.confirmText,
			cancelText: opts.cancelText,
			cancel: true
		});
	};

	// leftover alert() calls get the same look (note: unlike the native one it does not pause the script)
	window.alert = function (msg) {
		if (!document.body) return nativeAlert && nativeAlert(msg);
		window.mxAlert(msg == null ? '' : String(msg), 'info');
	};
})();
