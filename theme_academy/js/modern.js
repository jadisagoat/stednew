/* ==========================================================================
   STED — MODERN UI LAYER (vanilla JS, no dependencies)
   Hero slider · scroll reveal · counters · cursor spotlight · progress · back-to-top
   ========================================================================== */
(function () {
	'use strict';

	var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var canHover = window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches;

	function $all(sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); }
	function ready(fn) { document.readyState !== 'loading' ? fn() : document.addEventListener('DOMContentLoaded', fn); }

	/* ---------------- Scroll progress + back to top ---------------- */
	function initChrome() {
		var bar = document.createElement('div');
		bar.className = 'mx-progress';
		document.body.appendChild(bar);

		var R = 24, C = 2 * Math.PI * R;
		var top = document.createElement('button');
		top.type = 'button';
		top.className = 'mx-totop';
		top.setAttribute('aria-label', 'Back to top');
		top.innerHTML = '<svg viewBox="0 0 52 52"><circle class="trk" cx="26" cy="26" r="' + R + '"/><circle class="bar" cx="26" cy="26" r="' + R + '" stroke-dasharray="' + C + '" stroke-dashoffset="' + C + '"/></svg><i class="fas fa-arrow-up"></i>';
		document.body.appendChild(top);
		var ring = top.querySelector('.bar');
		top.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' }); });

		var ticking = false;
		function update() {
			ticking = false;
			var h = document.documentElement.scrollHeight - window.innerHeight;
			var p = h > 0 ? Math.min(1, window.pageYOffset / h) : 0;
			bar.style.transform = 'scaleX(' + p + ')';
			ring.style.strokeDashoffset = C * (1 - p);
			top.classList.toggle('is-on', window.pageYOffset > 500);
		}
		window.addEventListener('scroll', function () { if (!ticking) { ticking = true; requestAnimationFrame(update); } }, { passive: true });
		update();
	}

	/* ---------------- Hero slider ---------------- */
	function initHero() {
		var hero = document.getElementById('hero-modern');
		if (!hero) return;

		// Split titles into words for the staggered reveal
		$all('[data-split]', hero).forEach(function (el) {
			var words = el.textContent.trim().split(/\s+/);
			el.textContent = '';
			words.forEach(function (w, i) {
				var outer = document.createElement('span');
				outer.className = 'w';
				var inner = document.createElement('span');
				inner.style.setProperty('--i', i);
				inner.textContent = w;
				outer.appendChild(inner);
				el.appendChild(outer);
				if (i < words.length - 1) el.appendChild(document.createTextNode(' '));
			});
		});

		var slides = $all('.mx-slide', hero);
		var caps = $all('.mx-caption', hero);
		var inds = $all('.mx-ind', hero);
		var n = caps.length;
		var current = 0;
		var interval = parseInt(hero.getAttribute('data-interval'), 10) || 7000;
		hero.style.setProperty('--mx-interval', interval + 'ms');

		function go(i) {
			if (n < 2) return;
			current = (i + n) % n;
			[slides, caps, inds].forEach(function (list) {
				list.forEach(function (el, k) { el.classList.toggle('is-active', k === current); });
			});
		}
		if (n < 2) return;

		// Autoplay is driven by the indicator bar's CSS animation (so it pauses on hover / hidden tab for free)
		if (!reduceMotion) {
			inds.forEach(function (ind) {
				var fill = ind.querySelector('.mx-ind-bar i');
				fill.addEventListener('animationend', function () { if (ind.classList.contains('is-active')) go(current + 1); });
			});
		}
		inds.forEach(function (ind) { ind.addEventListener('click', function () { go(+ind.getAttribute('data-go')); }); });
		var prev = hero.querySelector('.mx-hero-prev'), next = hero.querySelector('.mx-hero-next');
		if (prev) prev.addEventListener('click', function () { go(current - 1); });
		if (next) next.addEventListener('click', function () { go(current + 1); });

		hero.addEventListener('mouseenter', function () { hero.classList.add('is-paused'); });
		hero.addEventListener('mouseleave', function () { hero.classList.remove('is-paused'); });

		// Swipe
		var sx = null;
		hero.addEventListener('touchstart', function (e) { sx = e.touches[0].clientX; }, { passive: true });
		hero.addEventListener('touchend', function (e) {
			if (sx === null) return;
			var dx = e.changedTouches[0].clientX - sx;
			if (Math.abs(dx) > 50) go(current + (dx < 0 ? 1 : -1));
			sx = null;
		});

		// Keyboard (only while the hero is on screen)
		document.addEventListener('keydown', function (e) {
			if (window.pageYOffset > hero.offsetHeight * .6) return;
			var t = e.target && e.target.tagName;
			if (t === 'INPUT' || t === 'TEXTAREA' || t === 'SELECT') return;
			if (e.key === 'ArrowRight') go(current + 1);
			if (e.key === 'ArrowLeft') go(current - 1);
		});
	}

	/* ---------------- Hero parallax (scroll + mouse) ---------------- */
	function initParallax() {
		var hero = document.getElementById('hero-modern');
		if (!hero || reduceMotion) return;
		var inner = hero.querySelector('.mx-hero-inner');
		var visual = hero.querySelector('.mx-hero-visual');
		var ticking = false;

		window.addEventListener('scroll', function () {
			if (ticking) return;
			ticking = true;
			requestAnimationFrame(function () {
				ticking = false;
				var y = window.pageYOffset, h = hero.offsetHeight;
				if (y > h) return;
				inner.style.transform = 'translate3d(0,' + (y * .22) + 'px,0)';
				inner.style.opacity = Math.max(0, 1 - y / (h * .75));
			});
		}, { passive: true });

		if (visual && canHover) {
			hero.addEventListener('mousemove', function (e) {
				var r = hero.getBoundingClientRect();
				var x = (e.clientX - r.left) / r.width - .5;
				var yy = (e.clientY - r.top) / r.height - .5;
				visual.style.transform = 'translate3d(' + (x * -26) + 'px,' + (yy * -20) + 'px,0)';
			});
			hero.addEventListener('mouseleave', function () { visual.style.transform = ''; });
			visual.style.transition = 'transform .8s cubic-bezier(.22,1,.36,1)';
		}
	}

	/* ---------------- Scroll reveal ---------------- */
	function initReveal() {
		if (!('IntersectionObserver' in window) || reduceMotion) return;

		// [selector, extra class, stagger siblings?]
		var map = [
			['[data-reveal]', '', false],
			['.section-title', '', false],
			['.owl-carousel', '', false],          // whole carousel (owl clones items, so never tag items)
			['#about-3 .txt-block, #about-4 .txt-block', 'mx-left', false],
			['#about-3 .img-block, #about-4 .img-block', 'mx-zoom', false],
			['.services-6-txt', '', false],
			['.sbox-6', '', true],
			['.review-3', 'mx-zoom', false],
			['.abox-4', '', true],
			['.video-txt', '', false],
			['.video-3-link .play-btn', 'mx-zoom', false],
			['.sbox-5', '', true],
			['.banner-3-holder', 'mx-zoom', false],
			['.footer .row > [class*="col-"]', '', true]
		];

		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (en) {
				if (en.isIntersecting) {
					en.target.classList.add('is-in');
					io.unobserve(en.target);
				}
			});
		}, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

		var seen = [];
		map.forEach(function (m) {
			$all(m[0]).forEach(function (el) {
				// never hide the bottom copyright bar: it sits in the last pixels of the page and could never "enter" the view
				if (seen.indexOf(el) > -1 || el.closest('.owl-item') || el.closest('.bottom-footer')) return;
				seen.push(el);
				el.classList.add('mx-reveal');
				if (m[1]) el.classList.add(m[1]);
				if (m[2] && !el.style.getPropertyValue('--d')) {
					// stagger by position among the matching siblings in the same row
					var group = $all(m[0], el.parentNode.parentNode).filter(function (x) { return x.parentNode.parentNode === el.parentNode.parentNode; });
					var idx = Math.max(0, group.indexOf(el));
					el.style.setProperty('--d', Math.min(idx * 110, 550) + 'ms');
				}
				io.observe(el);
			});
		});

		// Safety net: once the bottom of the page is reached, show anything still hidden
		window.addEventListener('scroll', function () {
			if (window.innerHeight + window.pageYOffset >= document.documentElement.scrollHeight - 80) {
				$all('.mx-reveal:not(.is-in)').forEach(function (el) { el.classList.add('is-in'); io.unobserve(el); });
			}
		}, { passive: true });
	}

	/* ---------------- Count up ---------------- */
	function initCounters() {
		var els = $all('[data-count]');
		if (!els.length) return;
		function run(el) {
			var target = parseInt(el.getAttribute('data-count'), 10) || 0;
			if (reduceMotion) { el.textContent = target + '+'; return; }
			var start = null, dur = 1800;
			function step(ts) {
				if (!start) start = ts;
				var p = Math.min(1, (ts - start) / dur);
				var eased = p === 1 ? 1 : 1 - Math.pow(2, -10 * p);
				el.textContent = Math.round(target * eased);
				if (p < 1) requestAnimationFrame(step);
				else el.textContent = target + '+';
			}
			requestAnimationFrame(step);
		}
		if (!('IntersectionObserver' in window)) { els.forEach(run); return; }
		if (!reduceMotion) els.forEach(function (el) { el.textContent = '0'; });
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (en) { if (en.isIntersecting) { setTimeout(function () { run(en.target); }, 600); io.unobserve(en.target); } });
		});
		els.forEach(function (el) { io.observe(el); });
	}

	/* ---------------- Cursor spotlight on cards (delegated: works on owl clones too) ---------------- */
	function initSpotlight() {
		if (!canHover || reduceMotion) return;
		var SEL = '.cbox-1, .sbox-5, .abox-4, .mx-feature, .mx-ccard, .mx-qc';
		document.addEventListener('mousemove', function (e) {
			var card = e.target.closest && e.target.closest(SEL);
			if (!card) return;
			if (!card.classList.contains('mx-spot')) card.classList.add('mx-spot');
			var r = card.getBoundingClientRect();
			card.style.setProperty('--mx-x', (e.clientX - r.left) + 'px');
			card.style.setProperty('--mx-y', (e.clientY - r.top) + 'px');
		}, { passive: true });
	}

	/* ---------------- Magnetic buttons ---------------- */
	function initMagnetic() {
		if (!canHover || reduceMotion) return;
		$all('.mx-btn, .mx-hero-nav button').forEach(function (b) {
			b.addEventListener('mousemove', function (e) {
				var r = b.getBoundingClientRect();
				var x = e.clientX - r.left - r.width / 2, y = e.clientY - r.top - r.height / 2;
				b.style.transform = 'translate(' + x * .18 + 'px,' + (y * .3 - 3) + 'px)';
			});
			b.addEventListener('mouseleave', function () { b.style.transform = ''; });
		});
	}

	/* ---------------- WhatsApp button: keep the theme's show/hide, add a class for our CSS ---------------- */
	function initWhatsapp() {
		var wa = document.getElementById('whatsappButton');
		if (!wa) return;
		window.addEventListener('scroll', function () { wa.classList.toggle('is-on', window.pageYOffset > 20); }, { passive: true });
	}

	/* ---------------- Titles: word-by-word reveal ---------------- */
	function initSplitTitles() {
		if (!('IntersectionObserver' in window) || reduceMotion || !document.body.classList.contains('page-home')) return;
		var heads = $all('.section-title > h3, .txt-block > h3, .txt-block > h5.h5-xl, .services-6-txt h3, .video-txt h2, .video-txt h3');
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (en) {
				if (en.isIntersecting) { en.target.classList.add('is-split-in'); io.unobserve(en.target); }
			});
		}, { threshold: 0.2, rootMargin: '0px 0px -40px 0px' });
		heads.forEach(function (h) {
			if (h.closest('.owl-item') || h.closest('#hero-modern') || h.classList.contains('mx-split')) return;
			var n = 0, walker = document.createTreeWalker(h, NodeFilter.SHOW_TEXT, null), nodes = [];
			while (walker.nextNode()) nodes.push(walker.currentNode);
			nodes.forEach(function (t) {
				var parts = t.nodeValue.split(/(\s+)/);
				if (parts.join('').trim() === '') return;
				var frag = document.createDocumentFragment();
				parts.forEach(function (p) {
					if (p === '') return;
					if (/^\s+$/.test(p)) { frag.appendChild(document.createTextNode(' ')); return; }
					var w = document.createElement('span'), s = document.createElement('span');
					w.className = 'mx-w';
					s.style.setProperty('--i', n++);
					s.textContent = p;
					w.appendChild(s);
					frag.appendChild(w);
				});
				t.parentNode.replaceChild(frag, t);
			});
			if (!n) return;
			h.classList.add('mx-split');
			io.observe(h);
		});
		// never leave a title hidden at the bottom of the page
		window.addEventListener('scroll', function () {
			if (window.innerHeight + window.pageYOffset >= document.documentElement.scrollHeight - 80) {
				$all('.mx-split:not(.is-split-in)').forEach(function (el) { el.classList.add('is-split-in'); });
			}
		}, { passive: true });
	}

	/* ---------------- 3D tilt + glare on images and cards (eased in rAF) ---------------- */
	function initTilt() {
		if (!canHover || reduceMotion || !document.body.classList.contains('page-home')) return;
		var SEL = '.img-block, .cbox-1, .cbox-3, .sbox-5, .sbox-6, .abox-4, .mx-feature';
		var states = [], current = null, raf = 0;

		function stateOf(el) {
			for (var i = 0; i < states.length; i++) if (states[i].el === el) return states[i];
			var big = el.classList.contains('img-block');
			var st = { el: el, x: 0, y: 0, tx: 0, ty: 0, max: big ? 9 : 7, glare: null, img: null };
			if (el.parentNode && !el.parentNode.style.perspective) el.parentNode.style.perspective = big ? '1300px' : '900px';
			el.classList.add('mx-tilt');
			if (big && el.querySelector('img') && !el.querySelector('.mx-glare')) {
				var g = document.createElement('span');
				g.className = 'mx-glare';
				el.appendChild(g);
				st.glare = g; st.img = el.querySelector('img');
			}
			states.push(st);
			return st;
		}
		function tick() {
			raf = 0;
			var alive = false;
			states.forEach(function (st) {
				st.x += (st.tx - st.x) * .12;
				st.y += (st.ty - st.y) * .12;
				var d = Math.sqrt(st.x * st.x + st.y * st.y);
				if (d < .002 && st.tx === 0 && st.ty === 0) {
					if (st.el.style.rotate) { st.el.style.rotate = ''; st.el.classList.remove('is-tilting'); }
					st.x = st.y = 0;
					return;
				}
				alive = true;
				// rotate around the axis perpendicular to the cursor offset: the side under the cursor dips in
				st.el.style.rotate = (-st.y).toFixed(4) + ' ' + st.x.toFixed(4) + ' 0 ' + (Math.min(d, .71) * 2 * st.max).toFixed(2) + 'deg';
			});
			if (alive) raf = requestAnimationFrame(tick);
		}
		function kick() { if (!raf) raf = requestAnimationFrame(tick); }

		document.addEventListener('mousemove', function (e) {
			var el = e.target.closest && e.target.closest(SEL);
			if (current && current.el !== el) { current.tx = current.ty = 0; current.el.classList.remove('is-tilting'); current = null; }
			if (!el) { kick(); return; }
			var st = stateOf(el), r = el.getBoundingClientRect();
			var px = (e.clientX - r.left) / r.width, py = (e.clientY - r.top) / r.height;
			st.tx = px - .5; st.ty = py - .5;
			el.style.setProperty('--gx', (px * 100).toFixed(1) + '%');
			el.style.setProperty('--gy', (py * 100).toFixed(1) + '%');
			if (st.glare && st.img) {
				// keep the glare exactly on the picture (some image blocks have padding)
				var gs = st.glare.style, im = st.img;
				gs.left = im.offsetLeft + 'px'; gs.top = im.offsetTop + 'px';
				gs.width = im.offsetWidth + 'px'; gs.height = im.offsetHeight + 'px';
				gs.right = gs.bottom = 'auto';
				el.style.setProperty('--gx', ((e.clientX - im.getBoundingClientRect().left) / im.offsetWidth * 100).toFixed(1) + '%');
				el.style.setProperty('--gy', ((e.clientY - im.getBoundingClientRect().top) / im.offsetHeight * 100).toFixed(1) + '%');
			}
			el.classList.add('is-tilting');
			current = st;
			kick();
		}, { passive: true });
		document.addEventListener('mouseleave', function () { if (current) { current.tx = current.ty = 0; current = null; kick(); } });
	}

	/* ---------------- "Our Mission": mouse depth + cards popping out of the image ---------------- */
	function initMission() {
		var sec = document.getElementById('about-4');
		if (!sec || !document.body.classList.contains('page-home')) return;
		var zone = sec.querySelector(':scope > .container') || sec;
		if (!sec.querySelector('.mx-pop')) return;

		// touch screens / reduced motion: just show the cards when the section scrolls in
		if (!canHover || reduceMotion) {
			if (!('IntersectionObserver' in window)) { sec.classList.add('is-hot'); return; }
			var io = new IntersectionObserver(function (en) {
				if (en[0].isIntersecting) { sec.classList.add('is-hot'); io.disconnect(); }
			}, { threshold: 0.35 });
			io.observe(zone);
			return;
		}

		var x = 0, y = 0, tx = 0, ty = 0, raf = 0;
		function tick() {
			x += (tx - x) * .08;
			y += (ty - y) * .08;
			sec.style.setProperty('--px', x.toFixed(4));
			sec.style.setProperty('--py', y.toFixed(4));
			raf = (Math.abs(tx - x) > .001 || Math.abs(ty - y) > .001) ? requestAnimationFrame(tick) : 0;
		}
		zone.addEventListener('mousemove', function (e) {
			var r = zone.getBoundingClientRect();
			tx = Math.max(-.5, Math.min(.5, (e.clientX - r.left) / r.width - .5));
			ty = Math.max(-.5, Math.min(.5, (e.clientY - r.top) / r.height - .5));
			sec.classList.add('is-hot');
			if (!raf) raf = requestAnimationFrame(tick);
		});
		zone.addEventListener('mouseleave', function () {
			tx = ty = 0;
			sec.classList.remove('is-hot');
			if (!raf) raf = requestAnimationFrame(tick);
		});
	}

	/* ---------------- Cursor ring that trails the mouse (home, mouse devices) ---------------- */
	function initCursor() {
		if (!canHover || reduceMotion || !document.body.classList.contains('page-home')) return;
		var ring = document.createElement('div'), dot = document.createElement('div');
		ring.className = 'mx-cursor';
		dot.className = 'mx-cursor-dot';
		document.body.appendChild(ring);
		document.body.appendChild(dot);
		var mx = -100, my = -100, rx = -100, ry = -100, raf = 0;
		function tick() {
			rx += (mx - rx) * .18;
			ry += (my - ry) * .18;
			ring.style.transform = 'translate3d(' + rx.toFixed(1) + 'px,' + ry.toFixed(1) + 'px,0)';
			raf = (Math.abs(mx - rx) > .3 || Math.abs(my - ry) > .3) ? requestAnimationFrame(tick) : 0;
		}
		document.addEventListener('mousemove', function (e) {
			mx = e.clientX; my = e.clientY;
			dot.style.transform = 'translate3d(' + mx + 'px,' + my + 'px,0)';
			ring.classList.add('is-on'); dot.classList.add('is-on');
			var link = e.target.closest && e.target.closest('a, button, .owl-nav > *, [role="button"], input, label');
			ring.classList.toggle('is-link', !!link);
			if (!raf) raf = requestAnimationFrame(tick);
		}, { passive: true });
		document.addEventListener('mouseleave', function () { ring.classList.remove('is-on'); dot.classList.remove('is-on'); });
		// hide inside the open mega menu / modals, where the ring would sit on top of small targets
		document.addEventListener('mouseover', function (e) {
			var hide = !!(e.target.closest && e.target.closest('.mx-catmenu, .modal, .wsmegamenu'));
			ring.style.visibility = dot.style.visibility = hide ? 'hidden' : '';
		}, { passive: true });
	}

	/* ---------------- Page transitions: navy circle grows from the click, then the next page's preloader takes over ---------------- */
	function initPageTransitions() {
		if (reduceMotion) return;
		var veil = document.createElement('div');
		veil.className = 'mx-leave';
		veil.setAttribute('aria-hidden', 'true');
		veil.innerHTML = '<div class="mx-loader"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>';
		document.body.appendChild(veil);
		var safety = 0;

		function hide() { clearTimeout(safety); veil.classList.remove('is-on'); }

		document.addEventListener('click', function (e) {
			if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
			var a = e.target.closest && e.target.closest('a[href]');
			if (!a) return;
			if ((a.target && a.target !== '_self') || a.hasAttribute('download') || a.hasAttribute('data-toggle') || a.hasAttribute('data-bs-toggle') || a.hasAttribute('data-no-transition')) return;
			var href = a.getAttribute('href');
			if (!href || href.charAt(0) === '#' || /^(javascript|mailto|tel|sms|whatsapp):/i.test(href)) return;
			var url;
			try { url = new URL(a.href, location.href); } catch (err) { return; }
			if (url.origin !== location.origin) return;
			if (url.pathname === location.pathname && url.search === location.search) return;      // same page / anchor
			if (/\.(pdf|zip|rar|docx?|xlsx?|pptx?|csv|png|jpe?g|gif|webp|svg|mp4|mp3)$/i.test(url.pathname)) return;
			if (a.closest('.mfp-wrap, .modal, .wsmobileheader') || /popup|lightbox|image-link/i.test(a.className)) return;

			// the browser keeps showing this page until the next one arrives: cover it meanwhile
			veil.style.setProperty('--x', e.clientX + 'px');
			veil.style.setProperty('--y', e.clientY + 'px');
			void veil.offsetWidth;
			veil.classList.add('is-on');
			clearTimeout(safety);
			safety = setTimeout(hide, 6000);   // never leave the page covered if nothing loads
		});
		// back/forward cache: the page comes back as it was, with the veil still on
		window.addEventListener('pageshow', function (e) { if (e.persisted) hide(); });
	}

	/* ---------------- Home video picture: tilts up into place while scrolling, badges follow the mouse ---------------- */
	function initVideoStage() {
		var stage = document.getElementById('mx-vstage');
		if (!stage || reduceMotion) return;
		var ticking = false;
		function update() {
			ticking = false;
			var r = stage.getBoundingClientRect(), vh = window.innerHeight;
			// 0 while the picture's top is near the bottom of the screen, 1 once it reaches the upper third
			var p = Math.max(0, Math.min(1, (vh * .95 - r.top) / (vh * .6)));
			stage.style.setProperty('--p', p.toFixed(3));
		}
		window.addEventListener('scroll', function () { if (!ticking) { ticking = true; requestAnimationFrame(update); } }, { passive: true });
		window.addEventListener('resize', update);
		update();

		if (!canHover) return;
		stage.addEventListener('mousemove', function (e) {
			var r = stage.getBoundingClientRect();
			stage.style.setProperty('--mx', ((e.clientX - r.left) / r.width - .5).toFixed(3));
			stage.style.setProperty('--my', ((e.clientY - r.top) / r.height - .5).toFixed(3));
		});
		stage.addEventListener('mouseleave', function () {
			stage.style.setProperty('--mx', 0);
			stage.style.setProperty('--my', 0);
		});
	}

	/* ---------------- [data-words]: text fades in word by word when it scrolls into view ---------------- */
	function initWordReveal() {
		var els = $all('[data-words]');
		if (!els.length || reduceMotion || !('IntersectionObserver' in window)) return;
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (en) {
				if (en.isIntersecting) { en.target.classList.add('is-words-in'); io.unobserve(en.target); }
			});
		}, { threshold: 0.2, rootMargin: '0px 0px -40px 0px' });
		els.forEach(function (el) {
			var n = 0, walker = document.createTreeWalker(el, NodeFilter.SHOW_TEXT, null), nodes = [];
			while (walker.nextNode()) nodes.push(walker.currentNode);
			nodes.forEach(function (t) {
				if (t.nodeValue.trim() === '') return;
				var frag = document.createDocumentFragment();
				t.nodeValue.split(/(\s+)/).forEach(function (w) {
					if (w === '') return;
					if (/^\s+$/.test(w)) { frag.appendChild(document.createTextNode(' ')); return; }
					var s = document.createElement('span');
					s.className = 'mx-wd';
					s.style.setProperty('--i', Math.min(n++, 70));
					s.textContent = w;
					frag.appendChild(s);
				});
				t.parentNode.replaceChild(frag, t);
			});
			el.classList.add('mx-words');
			io.observe(el);
		});
		// never leave text hidden at the bottom of the page
		window.addEventListener('scroll', function () {
			if (window.innerHeight + window.pageYOffset >= document.documentElement.scrollHeight - 80) {
				$all('.mx-words:not(.is-words-in)').forEach(function (el) { el.classList.add('is-words-in'); });
			}
		}, { passive: true });
	}

	/* ---------------- Testimonial card: soft light follows the mouse ---------------- */
	function initReviewSpot() {
		var card = document.querySelector('.review-3');
		if (!card || !canHover || reduceMotion) return;
		card.addEventListener('mousemove', function (e) {
			var r = card.getBoundingClientRect();
			card.style.setProperty('--sx', (e.clientX - r.left) + 'px');
			card.style.setProperty('--sy', (e.clientY - r.top) + 'px');
		});
	}

	ready(function () {
		try { initChrome(); } catch (e) { }
		try { initPageTransitions(); } catch (e) { }
		try { initVideoStage(); } catch (e) { }
		try { initWordReveal(); } catch (e) { }
		try { initReviewSpot(); } catch (e) { }
		try { initHero(); } catch (e) { }
		try { initParallax(); } catch (e) { }
		try { initReveal(); } catch (e) { }
		try { initCounters(); } catch (e) { }
		try { initSpotlight(); } catch (e) { }
		try { initMagnetic(); } catch (e) { }
		try { initWhatsapp(); } catch (e) { }
		try { initSplitTitles(); } catch (e) { }
		try { initTilt(); } catch (e) { }
		try { initMission(); } catch (e) { }
		try { initCursor(); } catch (e) { }
	});
})();


/* ==========================================================================
   Category mega menu (desktop): hover a field -> its categories,
   hover a category -> its courses, type to search all courses.
   ========================================================================== */
(function () {
	function esc(s) {
		return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
			return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
		});
	}

	function highlight(text, q) {
		var i = text.toLowerCase().indexOf(q);
		if (i < 0) return esc(text);
		return esc(text.slice(0, i)) + '<mark>' + esc(text.slice(i, i + q.length)) + '</mark>' + esc(text.slice(i + q.length));
	}

	function init() {
		var menu = document.querySelector('.mx-catmenu');
		if (!menu) return;

		var groupBtns = [].slice.call(menu.querySelectorAll('.mx-cm-groups .mx-cm-item'));
		var catPanes = [].slice.call(menu.querySelectorAll('.mx-cm-cats .mx-cm-pane'));
		var catLinks = [].slice.call(menu.querySelectorAll('.mx-cm-cats .mx-cm-item[data-c]'));
		var coursePanes = [].slice.call(menu.querySelectorAll('.mx-cm-courses .mx-cm-pane'));
		var timer = null;

		function showCat(id) {
			id = String(id);
			coursePanes.forEach(function (p) { p.classList.toggle('is-active', p.getAttribute('data-c') === id); });
			catLinks.forEach(function (a) { a.classList.toggle('is-active', a.getAttribute('data-c') === id); });
		}

		function showGroup(id) {
			id = String(id);
			var pane = null;
			groupBtns.forEach(function (b) { b.classList.toggle('is-active', b.getAttribute('data-g') === id); });
			catPanes.forEach(function (p) {
				var on = p.getAttribute('data-g') === id;
				p.classList.toggle('is-active', on);
				if (on) pane = p;
			});
			var first = pane && pane.querySelector('.mx-cm-item[data-c]');
			showCat(first ? first.getAttribute('data-c') : '');
		}

		// Small hover delay so moving diagonally to the next column does not switch rows
		function bind(el, fn) {
			el.addEventListener('mouseenter', function () {
				clearTimeout(timer);
				timer = setTimeout(fn, 90);
			});
			el.addEventListener('mouseleave', function () { clearTimeout(timer); });
			el.addEventListener('focus', fn);
		}

		groupBtns.forEach(function (b) {
			var fn = function () { showGroup(b.getAttribute('data-g')); };
			bind(b, fn);
			b.addEventListener('click', fn);
		});
		catLinks.forEach(function (a) {
			bind(a, function () { showCat(a.getAttribute('data-c')); });
		});

		if (groupBtns[0]) showGroup(groupBtns[0].getAttribute('data-g'));

		// Search across every course in the menu
		var input = menu.querySelector('.mx-cm-search input');
		var results = menu.querySelector('.mx-cm-results');
		if (!input || !results) return;
		var grid = results.querySelector('.mx-cm-grid');
		var label = results.querySelector('.mx-cm-label');
		var seen = {};
		var all = [];
		[].slice.call(menu.querySelectorAll('.mx-cm-courses .mx-cm-course')).forEach(function (a) {
			if (seen[a.href]) return;
			seen[a.href] = 1;
			all.push({
				href: a.href,
				title: (a.querySelector('.mx-cm-ctitle') || a).textContent.trim(),
				cat: a.getAttribute('data-cat') || ''
			});
		});

		function runSearch() {
			var q = input.value.trim().toLowerCase();
			menu.classList.toggle('is-searching', !!q);
			if (!q) return;
			var hits = all.filter(function (c) {
				return c.title.toLowerCase().indexOf(q) > -1 || c.cat.toLowerCase().indexOf(q) > -1;
			});
			label.textContent = hits.length + (hits.length === 1 ? ' course found' : ' courses found');
			results.classList.toggle('is-empty', !hits.length);
			grid.innerHTML = hits.slice(0, 60).map(function (c) {
				return '<li><a class="mx-cm-course" href="' + esc(c.href) + '">' +
					'<span class="mx-cm-cico"><i class="fa-solid fa-play"></i></span>' +
					'<span class="mx-cm-ctitle">' + highlight(c.title, q) + '<small>' + esc(c.cat) + '</small></span>' +
					'<i class="fa-solid fa-arrow-right mx-cm-go"></i></a></li>';
			}).join('');
		}

		input.addEventListener('input', runSearch);
		input.addEventListener('keydown', function (e) {
			if (e.key === 'Escape') {
				input.value = '';
				runSearch();
				input.blur();
			} else if (e.key === 'Enter') {
				var first = grid.querySelector('a');
				if (first) window.location.href = first.href;
			}
		});
	}

	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
	else init();
})();
