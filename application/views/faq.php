<?php
/* ==========================================================================
   FAQ PAGE (modern design - backup: faq____28_10_2026.php)
   $faqs = rows from the faqs table (title, small_description, icon, description)
   Topic 5 (STED AI) is a link to listapidata, shown to logged-in users only.
   ========================================================================== */

// Clean the CKEditor HTML: drop inline styles, empty paragraphs, the heading that repeats
// the topic title, and turn "1. Something" paragraphs into numbered sub-headings.
if (!function_exists('mx_faq_html')) {
	function mx_faq_html($html, $title)
	{
		$html = preg_replace('/\s(style|class)="[^"]*"/i', '', (string)$html);
		$html = preg_replace('#<p>\s*(&nbsp;|\xC2\xA0|\s)*\s*</p>#iu', '', $html);
		// first heading that only repeats the title
		$plain = function ($s) { return strtolower(trim(preg_replace('/[^a-z0-9]+/i', ' ', html_entity_decode(strip_tags($s), ENT_QUOTES, 'UTF-8')))); };
		if (preg_match('#<h[1-6]>(.*?)</h[1-6]>#is', $html, $m) && $plain($m[1]) !== '') {
			$t = $plain($title);
			$h = $plain($m[1]);
			if ($h === $t || strpos($t, $h) !== false || strpos($h, $t) !== false || strpos($html, $m[0]) < 80) {
				$html = preg_replace('#' . preg_quote($m[0], '#') . '#', '', $html, 1);
			}
		}
		$html = preg_replace_callback('#<p>\s*<strong>\s*(\d+)\.\s*(.*?)</strong>\s*</p>#is', function ($m) {
			return '<h4 class="mx-faq-sub"><span>' . $m[1] . '</span>' . trim($m[2]) . '</h4>';
		}, $html);
		return trim($html);
	}
}

$mx_topics = array();
foreach ($faqs as $row) {
	$is_link = ($row['id_faqs'] == '5');
	if ($is_link && empty($login['idUser'])) continue;
	if (!$is_link && trim(strip_tags($row['description'])) === '') continue;
	$row['is_link'] = $is_link;
	$row['icon_url'] = (!empty($row['icon']) && is_file("uploads/faqs/" . $row['icon'])) ? base_url() . 'uploads/faqs/' . $row['icon'] : '';
	$row['nice_title'] = ($row['title'] === mb_strtoupper($row['title'])) ? str_replace(array(' And ', ' Of ', ' The ', ' To '), array(' and ', ' of ', ' the ', ' to '), mb_convert_case(mb_strtolower($row['title']), MB_CASE_TITLE, 'UTF-8')) : $row['title'];
	$mx_topics[] = $row;
}
$mx_first = null;
foreach ($mx_topics as $t) if (!$t['is_link']) { $mx_first = $t['id_faqs']; break; }

$this->load->view('include/_page_hero', array(
	'ph_title'   => 'Frequently asked questions',
	'ph_eyebrow' => 'Help center',
	'ph_text'    => 'Everything you need to know about learning with STED - your account, courses, exams, certificates and support.',
	'ph_crumbs'  => array(
		array('label' => 'Home', 'url' => base_url()),
		array('label' => 'FAQs'),
	),
	'ph_image'   => '',
	'ph_badge'   => array(),
	'ph_stats'   => array(
		array('icon' => 'fas fa-layer-group', 'value' => count($mx_topics), 'label' => count($mx_topics) == 1 ? 'Help topic' : 'Help topics'),
		array('icon' => 'fas fa-headset', 'value' => 'Support', 'label' => 'Phone, WhatsApp & email'),
	),
));
?>

<div class="mx-inner mx-faq">
	<section class="mx-faq-section">
		<div class="container">
			<div class="row">

				<!-- Topics -->
				<div class="col-lg-4">
					<div class="mx-faq-nav" data-reveal>
						<span class="mx-faq-nav-label">Browse topics</span>
						<div class="mx-faq-topics" role="tablist" aria-orientation="vertical">
							<?php foreach ($mx_topics as $i => $t) {
								$on = ($t['id_faqs'] == $mx_first);
								$inner = '<span class="mx-faq-ico">' . ($t['icon_url'] ? '<img src="' . $t['icon_url'] . '" alt="">' : '<i class="fas fa-circle-question"></i>') . '</span>'
									. '<span class="mx-faq-ttl"><b>' . mx_clean($t['nice_title']) . '</b><small>' . mx_clean($t['small_description']) . '</small></span>'
									. '<i class="fas ' . ($t['is_link'] ? 'fa-arrow-up-right-from-square' : 'fa-chevron-right') . ' mx-faq-arrow" aria-hidden="true"></i>';
								if ($t['is_link']) { ?>
									<a class="mx-faq-topic" href="<?php echo base_url(); ?>listapidata"><?php echo $inner; ?></a>
								<?php } else { ?>
									<button type="button" class="mx-faq-topic<?php echo $on ? ' is-on' : ''; ?>" role="tab" id="mx-faq-t<?php echo $t['id_faqs']; ?>" aria-controls="tab-<?php echo $t['id_faqs']; ?>" aria-selected="<?php echo $on ? 'true' : 'false'; ?>" data-tab="tab-<?php echo $t['id_faqs']; ?>"><?php echo $inner; ?></button>
							<?php }
							} ?>
						</div>
					</div>
				</div>

				<!-- Content -->
				<div class="col-lg-8">
					<?php foreach ($mx_topics as $t) {
						if ($t['is_link']) continue;
						$on = ($t['id_faqs'] == $mx_first); ?>
						<article class="mx-faq-panel<?php echo $on ? ' is-on' : ''; ?>" id="tab-<?php echo $t['id_faqs']; ?>" role="tabpanel" aria-labelledby="mx-faq-t<?php echo $t['id_faqs']; ?>"<?php echo $on ? '' : ' hidden'; ?>>
							<header class="mx-faq-head">
								<span class="mx-faq-ico mx-faq-ico-lg"><?php echo $t['icon_url'] ? '<img src="' . $t['icon_url'] . '" alt="">' : '<i class="fas fa-circle-question"></i>'; ?></span>
								<div>
									<h2><?php echo mx_clean($t['nice_title']); ?></h2>
									<p><?php echo mx_clean($t['small_description']); ?></p>
								</div>
							</header>
							<div class="mx-faq-body">
								<?php echo mx_faq_html($t['description'], $t['title']); ?>
							</div>
						</article>
					<?php } ?>
				</div>

			</div>

			<!-- Still have questions -->
			<div class="mx-cta" data-reveal>
				<span class="mx-cta-dots" aria-hidden="true"></span>
				<div class="mx-cta-txt">
					<span class="mx-eyebrow mx-eyebrow-light"><span class="mx-pulse"></span>Still need help?</span>
					<h3>Didn't find your answer?</h3>
					<p>Our team is happy to help - send us a message or chat with us on WhatsApp.</p>
				</div>
				<div class="mx-cta-actions">
					<a class="mx-btn mx-btn-glass" href="https://wa.me/966509296490" target="_blank" rel="noopener">WhatsApp <i class="fab fa-whatsapp"></i></a>
					<a class="mx-btn mx-btn-primary" href="<?php echo base_url(); ?>contactus">Contact us <i class="fas fa-arrow-right"></i></a>
				</div>
			</div>
		</div>
	</section>
</div>

<script>
	(function() {
		var tabs = [].slice.call(document.querySelectorAll('.mx-faq-topic[data-tab]'));
		if (!tabs.length) return;

		function show(id, scroll) {
			var panel = document.getElementById(id);
			if (!panel) return;
			tabs.forEach(function(t) {
				var on = t.getAttribute('data-tab') === id;
				t.classList.toggle('is-on', on);
				t.setAttribute('aria-selected', on ? 'true' : 'false');
			});
			[].slice.call(document.querySelectorAll('.mx-faq-panel')).forEach(function(p) {
				var on = p === panel;
				p.hidden = !on;
				p.classList.toggle('is-on', on);
			});
			// phones: the content sits under the topic list, bring it into view
			if (scroll && window.innerWidth < 992) {
				window.scrollTo({ top: panel.getBoundingClientRect().top + window.pageYOffset - 90, behavior: 'smooth' });
			}
		}

		tabs.forEach(function(t) {
			t.addEventListener('click', function() {
				var id = t.getAttribute('data-tab');
				show(id, true);
				if (history.replaceState) history.replaceState(null, '', '#' + id);
			});
		});
		// open a topic from the link (#tab-2)
		if (/^#tab-\d+$/.test(location.hash)) show(location.hash.slice(1), false);
	})();
</script>
