<?php
/* ==========================================================================
   NEWS ARTICLE (modern design - backup: news____28_10_2026.php)
   $news        = the news row (title, small_description, description, image, created_date)
   $header_news = latest news already loaded for the header menu -> "More from STED"
   ========================================================================== */

// Clean the CKEditor HTML: drop inline styles/spans, turn <div> lines into paragraphs,
// remove empty lines, turn bold-only lines into sub-headings and
// "line<br>line<br>line" paragraphs into a list (a line ending with ":" becomes its label).
if (!function_exists('mx_rich_html')) {
	function mx_rich_html($html)
	{
		$html = preg_replace('/\s(style|class|align|face|color|size)\s*=\s*"[^"]*"/i', '', (string)$html);
		$html = preg_replace('#</?(span|font)\b[^>]*>#i', '', $html);
		$html = preg_replace('#<(/?)div\b[^>]*>#i', '<$1p>', $html);
		$html = preg_replace('#<br\s*/?>#i', '<br>', $html);
		$html = preg_replace('#<p>(\s|&nbsp;|\xC2\xA0|<br>)*</p>#iu', '', $html);
		$plain = function ($s) { return trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($s), ENT_QUOTES, 'UTF-8'))); };
		$html = preg_replace_callback('#<p>(.*?)</p>#is', function ($m) use ($plain) {
			$inner = trim($m[1]);
			$lines = array();
			foreach (preg_split('#<br>#i', $inner) as $l) if ($plain($l) !== '') $lines[] = trim($l);
			if (!$lines) return '';
			if (count($lines) < 3) {
				if (count($lines) == 1 && preg_match('#^<(strong|b)>(.*?)</\1>\s*:?$#is', $lines[0], $s) && mb_strlen($plain($s[2])) <= 80) {
					return '<h3 class="mx-rich-sub">' . trim($s[2]) . '</h3>';
				}
				return '<p>' . implode('<br>', $lines) . '</p>';
			}
			$out = '';
			if (preg_match('/:\s*$/', $plain($lines[0])) && mb_strlen($plain($lines[0])) <= 90) {
				$out .= '<p class="mx-rich-label">' . preg_replace('/:\s*$/', '', array_shift($lines)) . '</p>';
			}
			foreach ($lines as $l) if (mb_strlen($plain($l)) > 160) return $out . '<p>' . implode('</p><p>', $lines) . '</p>';
			$numbered = true;
			foreach ($lines as $l) if (!preg_match('/^\d+[.)]\s+/', $plain($l))) $numbered = false;
			$items = '';
			foreach ($lines as $l) {
				if ($numbered) {
					$items .= '<li>' . preg_replace('/^\s*\d+[.)]\s+/', '', $l) . '</li>';
				} elseif (preg_match('/^([a-z])[.)]\s+/i', $plain($l), $mk)) {
					$items .= '<li class="is-head" data-mark="' . strtolower($mk[1]) . '">' . preg_replace('/^\s*[a-z][.)]\s+/i', '', $l) . '</li>';
				} else {
					$items .= '<li>' . $l . '</li>';
				}
			}
			$tag = $numbered ? 'ol' : 'ul';
			return $out . '<' . $tag . '>' . $items . '</' . $tag . '>';
		}, $html);
		return trim($html);
	}
}

$mx_has_news = !empty($news) && is_array($news) && trim((string)$news['title'] . strip_tags((string)$news['description'])) !== '';
$mx_title = $mx_has_news ? $news['title'] : 'Article not found';
$mx_summary = $mx_has_news ? $news['small_description'] : '';
$mx_sum_plain = trim(html_entity_decode(strip_tags((string)$mx_summary), ENT_QUOTES, 'UTF-8'));
$mx_sum_plain = trim(preg_replace('/\s+/u', ' ', $mx_sum_plain));
$mx_sum_in_hero = ($mx_sum_plain !== '' && mb_strlen($mx_sum_plain) <= 260);

$mx_img = '';
if ($mx_has_news && !empty($news['image']) && is_file("uploads/news/" . $news['image'])) {
	$mx_img = optimized_image_url('news', $news['image'], 1000);
}

$mx_body = $mx_has_news ? mx_rich_html($news['description']) : '';
// the summary is often repeated as the first paragraph: when the hero already shows it, drop it here
$mx_lead = '';
if (preg_match('#^<p>(.*?)</p>#is', $mx_body, $m)) {
	$first = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($m[1]), ENT_QUOTES, 'UTF-8')));
	if ($mx_sum_plain !== '' && $first === $mx_sum_plain) {
		$mx_body = trim(substr($mx_body, strlen($m[0])));
		if (!$mx_sum_in_hero) $mx_lead = $m[1];
	}
}
if (!$mx_sum_in_hero && $mx_lead === '' && $mx_sum_plain !== '') $mx_lead = htmlspecialchars($mx_sum_plain, ENT_QUOTES, "UTF-8");

$mx_words = str_word_count(strip_tags($mx_summary . ' ' . ($mx_has_news ? $news['description'] : '')));
$mx_minutes = max(1, (int)ceil($mx_words / 200));
$mx_date = '';
if ($mx_has_news && !empty($news['created_date']) && strtotime($news['created_date']) > 86400) {
	$mx_date = date('M j, Y', strtotime($news['created_date']));
}

// other news (already loaded for the header menu)
$mx_more = array();
if (!empty($header_news) && is_array($header_news)) {
	foreach ($header_news as $row) {
		if ($mx_has_news && (int)$row['id_news'] === (int)$news['id_news']) continue;
		$thumb = '';
		if (!empty($row['image'])) {
			if (is_file("uploads/news/85x85/" . $row['image'])) $thumb = base_url() . 'uploads/news/85x85/' . $row['image'];
			elseif (is_file("uploads/news/" . $row['image'])) $thumb = optimized_image_url('news', $row['image'], 160);
		}
		$row['thumb'] = $thumb;
		$mx_more[] = $row;
		if (count($mx_more) >= 4) break;
	}
}

$mx_url = current_url();
$mx_stats = array();
if ($mx_has_news) {
	if ($mx_date) $mx_stats[] = array('icon' => 'far fa-calendar', 'value' => $mx_date, 'label' => 'Published');
	$mx_stats[] = array('icon' => 'far fa-clock', 'value' => $mx_minutes . ' min', 'label' => 'Reading time');
}

$this->load->view('include/_page_hero', array(
	'ph_title'   => $mx_title,
	'ph_eyebrow' => 'STED News',
	'ph_text'    => $mx_sum_in_hero ? $mx_summary : ($mx_has_news ? 'News and insights from the STED engineering and training team.' : 'This article may have been moved or removed.'),
	'ph_crumbs'  => array(
		array('label' => 'Home', 'url' => base_url()),
		array('label' => 'News'),
		array('label' => mb_strlen($mx_title) > 40 ? mb_substr($mx_title, 0, 38) . '…' : $mx_title),
	),
	'ph_stats'   => $mx_stats,
	'ph_image'   => $mx_img,
	'ph_badge'   => $mx_has_news ? array('icon' => 'fas fa-newspaper', 'value' => 'STED Insights', 'label' => 'Engineering & training') : array(),
));
?>

<div class="mx-inner mx-news">
	<section class="mx-news-section">
		<div class="container">

			<?php if ($mx_has_news) { ?>
				<div class="row">
					<div class="col-lg-8">
						<article class="mx-news-card" data-reveal>
							<div class="mx-news-meta">
								<span><i class="fas fa-newspaper"></i> News</span>
								<?php if ($mx_date) { ?><span><i class="far fa-calendar"></i> <?php echo $mx_date; ?></span><?php } ?>
								<span><i class="far fa-clock"></i> <?php echo $mx_minutes; ?> min read</span>
							</div>

							<?php if ($mx_img) { ?>
								<figure class="mx-news-cover d-lg-none">
									<img src="<?php echo $mx_img; ?>" alt="<?php echo mx_clean($mx_title); ?>">
								</figure>
							<?php } ?>

							<?php if ($mx_lead !== '') { ?>
								<div class="mx-news-lead"><?php echo $mx_lead; ?></div>
							<?php } ?>

							<?php if ($mx_body !== '') { ?>
								<div class="mx-rich"><?php echo $mx_body; ?></div>
							<?php } ?>

							<div class="mx-news-share">
								<span><i class="fas fa-share-nodes"></i>Share this article</span>
								<a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo rawurlencode($mx_url); ?>" target="_blank" rel="noopener" aria-label="Share on LinkedIn"><i class="fab fa-linkedin-in"></i></a>
								<a href="https://wa.me/?text=<?php echo rawurlencode(strip_tags($mx_title) . ' ' . $mx_url); ?>" target="_blank" rel="noopener" aria-label="Share on WhatsApp"><i class="fab fa-whatsapp"></i></a>
								<a href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode($mx_url); ?>&amp;text=<?php echo rawurlencode(strip_tags($mx_title)); ?>" target="_blank" rel="noopener" aria-label="Share on X"><i class="fab fa-x-twitter"></i></a>
								<a href="mailto:?subject=<?php echo rawurlencode(strip_tags($mx_title)); ?>&amp;body=<?php echo rawurlencode($mx_url); ?>" aria-label="Share by email"><i class="far fa-envelope"></i></a>
								<button type="button" class="mx-news-copy" data-url="<?php echo htmlspecialchars($mx_url, ENT_QUOTES, 'UTF-8'); ?>" aria-label="Copy link" title="Copy link"><i class="fas fa-link"></i></button>
							</div>
						</article>
					</div>

					<div class="col-lg-4">
						<aside class="mx-news-aside">
							<div class="mx-info-card" data-reveal style="--d:120ms">
								<span class="mx-info-dots" aria-hidden="true"></span>
								<h3>Learn with STED</h3>
								<ul class="mx-info-list">
									<li><i class="fas fa-laptop"></i><span><b>100% online</b><small>Study at your own pace</small></span></li>
									<li><i class="fas fa-certificate"></i><span><b>Certificates</b><small>After passing the exam</small></span></li>
									<li><i class="fas fa-helmet-safety"></i><span><b>Industry experts</b><small>MEP &amp; commissioning engineers</small></span></li>
								</ul>
								<a class="mx-btn mx-btn-primary" href="<?php echo base_url(); ?>contactus">Ask about training <i class="fas fa-arrow-right"></i></a>
							</div>

							<?php if ($mx_more) { ?>
								<div class="mx-news-more" data-reveal style="--d:200ms">
									<span class="mx-news-more-label">More from STED</span>
									<?php foreach ($mx_more as $row) { ?>
										<a href="<?php echo base_url(); ?>news/<?php echo (int)$row['id_news']; ?>">
											<span class="mx-news-thumb"><?php if ($row['thumb']) { ?><img src="<?php echo $row['thumb']; ?>" alt="" loading="lazy"><?php } else { ?><i class="fas fa-newspaper"></i><?php } ?></span>
											<span><b><?php echo mx_clean($row['title'], 70); ?></b><small>Read article <i class="fas fa-arrow-right"></i></small></span>
										</a>
									<?php } ?>
								</div>
							<?php } ?>
						</aside>
					</div>
				</div>

			<?php } else { ?>
				<div class="mx-empty" data-reveal>
					<span class="mx-empty-ico"><i class="fas fa-newspaper"></i></span>
					<h3>We couldn't find this article</h3>
					<p>It may have been moved or removed. Have a look at our latest news or go back to the home page.</p>
					<a class="mx-btn mx-btn-primary" href="<?php echo base_url(); ?>">Back to home <i class="fas fa-arrow-right"></i></a>
				</div>
				<?php if ($mx_more) { ?>
					<div class="row mx-qc-row">
						<?php foreach (array_slice($mx_more, 0, 3) as $i => $row) { ?>
							<div class="col-md-6 col-lg-4 d-flex">
								<a class="mx-qc" href="<?php echo base_url(); ?>news/<?php echo (int)$row['id_news']; ?>" data-reveal style="--d:<?php echo $i * 100; ?>ms">
									<span class="mx-qc-ico"><i class="fas fa-newspaper"></i></span>
									<small>News</small>
									<b><?php echo mx_clean($row['title'], 70); ?></b>
									<span class="mx-qc-go">Read article <i class="fas fa-arrow-right"></i></span>
								</a>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
			<?php } ?>

			<!-- CTA -->
			<div class="mx-cta" data-reveal>
				<span class="mx-cta-dots" aria-hidden="true"></span>
				<div class="mx-cta-txt">
					<span class="mx-eyebrow mx-eyebrow-light"><span class="mx-pulse"></span>Work with STED</span>
					<h3>Have a project or a team to train?</h3>
					<p>Talk to our engineers about MEP services, commissioning or hands-on training.</p>
				</div>
				<div class="mx-cta-actions">
					<a class="mx-btn mx-btn-glass" href="<?php echo base_url(); ?>aboutus">About STED <i class="fas fa-arrow-right"></i></a>
					<a class="mx-btn mx-btn-primary" href="<?php echo base_url(); ?>contactus">Contact us <i class="fas fa-arrow-right"></i></a>
				</div>
			</div>

		</div>
	</section>
</div>

<script>
	// copy the article link
	(function() {
		var btn = document.querySelector('.mx-news-copy');
		if (!btn) return;
		btn.addEventListener('click', function() {
			var url = btn.getAttribute('data-url'), ico = btn.querySelector('i');
			function copyBox() {
				mxDialog({ type: 'info', title: 'Copy this link', value: url, copy: true, confirmText: 'Copy link' }).then(function(ok) { if (ok) done(); });
			}
			function done() {
				btn.classList.add('is-done');
				ico.className = 'fas fa-check';
				setTimeout(function() { btn.classList.remove('is-done'); ico.className = 'fas fa-link'; }, 1800);
			}
			if (navigator.clipboard && window.isSecureContext) {
				navigator.clipboard.writeText(url).then(done, copyBox);
			} else {
				copyBox();
			}
		});
	})();
</script>
