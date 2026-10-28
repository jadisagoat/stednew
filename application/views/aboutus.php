<?php
/* ==========================================================================
   ABOUT PAGE (modern design - backup: aboutus____28_10_2026.php)
   $aboutus = rows from the about table (title, image, description)
   Each row becomes a chapter; odd chapters sit on a navy band.
   Each chapter shows its picture next to the text;
   a chapter without its own picture gets an editorial layout:
   big opening sentence + text card.
   ========================================================================== */

// Clean the CKEditor HTML: drop inline styles, turn <div> lines into paragraphs,
// remove empty lines, and turn “quoted” lines into quote cards (the line before,
// e.g. "Then came a vision:", becomes the quote label).
if (!function_exists('mx_about_html')) {
	function mx_about_html($html)
	{
		$html = preg_replace('/\s(style|class|align)="[^"]*"/i', '', (string)$html);
		$html = preg_replace('#<(/?)div>#i', '<$1p>', $html);
		$html = preg_replace('#<p>\s*(&nbsp;|\xC2\xA0|<br\s*/?>|\s)*\s*</p>#iu', '', $html);
		$q_open = '(?:&ldquo;|\xE2\x80\x9C|&quot;|")';
		$q_close = '(?:&rdquo;|\xE2\x80\x9D|&quot;|")';
		$html = preg_replace_callback('#(?:<p>\s*([^<]{2,60}?):\s*</p>\s*)?<p>\s*' . $q_open . '(.*?)' . $q_close . '\s*\.?\s*</p>#isu', function ($m) {
			$label = trim($m[1]) !== '' ? '<span class="mx-quote-label">' . trim($m[1]) . '</span>' : '';
			return '<blockquote class="mx-quote">' . $label . '<p>' . trim($m[2]) . '</p></blockquote>';
		}, $html);
		return trim($html);
	}
}

// Split off the opening: the first paragraph, or its first sentence when the paragraph is long.
if (!function_exists('mx_about_lead')) {
	function mx_about_lead($html)
	{
		if (!preg_match('#^<p>(.*?)</p>#is', $html, $m)) return array('', $html);
		$first = trim($m[1]);
		$rest = trim(substr($html, strlen($m[0])));
		if (mb_strlen(strip_tags($first)) > 300 && preg_match('#^(.{80,}?[.!?])\s+(?=\S)#su', $first, $s)) {
			$rest = '<p>' . ucfirst(trim(substr($first, strlen($s[0])))) . '</p>' . $rest;
			$first = trim($s[1]);
		}
		return array($first, $rest);
	}
}

$mx_rows = array();
$mx_services = 0;
$mx_hero_img = '';
foreach ($aboutus as $row) {
	if (trim(strip_tags($row['description'])) === '' && trim($row['title']) === '') continue;
	$row['img_url'] = '';
	$file = "uploads/about/" . $row['image'];
	if (!empty($row['image']) && is_file($file)) {
		$row['img_url'] = base_url() . $file;
	}
	$row['html'] = mx_about_html($row['description']);
	$row['has_list'] = (stripos($row['html'], '<li') !== false);
	$mx_services += substr_count(strtolower($row['html']), '<li');
	$mx_rows[] = $row;
}

$mx_stats = array(
	array('icon' => 'fas fa-flag', 'value' => 'Since 2015', 'label' => 'Riyadh, Saudi Arabia'),
);
if ($mx_services) $mx_stats[] = array('icon' => 'fas fa-screwdriver-wrench', 'value' => $mx_services, 'label' => 'Specialized services');

$this->load->view('include/_page_hero', array(
	'ph_title'   => 'Engineering a better quality of life',
	'ph_eyebrow' => 'About STED',
	'ph_text'    => 'Since 2015, STED has brought MEP engineering, specialized services and hands-on training together - building an ecosystem of forward-thinking engineers, clients and partners.',
	'ph_crumbs'  => array(
		array('label' => 'Home', 'url' => base_url()),
		array('label' => 'About us'),
	),
	'ph_image'   => $mx_hero_img,
	'ph_badge'   => array('icon' => 'fas fa-earth-asia', 'value' => 'Our vision', 'label' => 'A better life and earth through engineering'),
	'ph_stats'   => $mx_stats,
));
?>

<div class="mx-inner mx-about">

	<?php foreach ($mx_rows as $i => $row) {
		$dark = ($i % 2 == 1);
		$num = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
		$eyebrow = '<span class="mx-eyebrow' . ($dark ? ' mx-eyebrow-light' : '') . '"><span class="mx-pulse"></span>Chapter ' . $num . '</span>'; ?>
		<section class="mx-about-chapter<?php echo $dark ? ' is-dark' : ''; ?>">
			<?php if ($dark) { ?>
				<span class="mx-about-glow" aria-hidden="true"></span>
				<span class="mx-about-dots" aria-hidden="true"></span>
			<?php } ?>
			<div class="container">

				<?php if ($row['img_url']) { /* title + picture (+ quote cards) | text card, both columns the same height */
					$quotes = '';
					$body = $row['html'];
					if (preg_match_all('#<blockquote class="mx-quote">.*?</blockquote>#is', $body, $qm)) {
						$quotes = implode('', $qm[0]);
						$body = trim(str_replace($qm[0], '', $body));
					} ?>
					<div class="row mx-about-pic<?php echo $dark ? ' flex-lg-row-reverse' : ''; ?>">
						<div class="col-lg-6">
							<div class="mx-about-titlebox" data-reveal>
								<?php echo $eyebrow; ?>
								<h2 class="mx-about-title"><?php echo mx_clean($row['title']); ?></h2>
							</div>
							<div class="mx-about-media<?php echo $dark ? ' is-flip' : ''; ?>">
								<span class="mx-about-frame" aria-hidden="true"></span>
								<div class="mx-about-img" data-reveal>
									<img src="<?php echo $row['img_url']; ?>" alt="<?php echo mx_clean($row['title']); ?>" loading="lazy">
								</div>
								<span class="mx-about-num" aria-hidden="true"><?php echo $num; ?></span>
							</div>
							<?php if ($quotes) { /* desktop: under the picture */ ?>
								<div class="mx-about-body mx-about-quotes d-none d-lg-block" data-reveal style="--d:200ms"><?php echo $quotes; ?></div>
							<?php } ?>
						</div>
						<div class="col-lg-6">
							<div class="mx-about-txt" data-reveal style="--d:120ms">
								<?php if ($row['has_list']) { ?>
									<div class="mx-about-body has-list"><?php echo $body; ?></div>
								<?php } else { /* text in a card, opening sentence on top */
									list($lead, $rest) = mx_about_lead($body); ?>
									<div class="mx-about-card mx-about-body">
										<?php if ($lead !== '') { ?><p class="mx-about-lead"><?php echo $lead; ?></p><?php } ?>
										<?php echo $rest; ?>
									</div>
								<?php } ?>
								<?php if ($quotes) { /* phones: after the text */ ?>
									<div class="mx-about-body mx-about-quotes d-lg-none"><?php echo $quotes; ?></div>
								<?php } ?>
							</div>
						</div>
					</div>

				<?php } else { /* editorial: title + opening sentence | text card */
					list($lead, $rest) = $row['has_list'] ? array('', $row['html']) : mx_about_lead($row['html']); ?>
					<div class="row align-items-center">
						<div class="col-lg-5">
							<div class="mx-about-head" data-reveal>
								<span class="mx-about-bignum" aria-hidden="true"><?php echo $num; ?></span>
								<?php echo $eyebrow; ?>
								<h2 class="mx-about-title"><?php echo mx_clean($row['title']); ?></h2>
								<?php if ($lead !== '') { ?><p class="mx-about-lead"><?php echo $lead; ?></p><?php } ?>
							</div>
						</div>
						<?php if (trim(strip_tags($rest, '<blockquote><li>')) !== '') { ?>
							<div class="col-lg-7">
								<div class="mx-about-card mx-about-body<?php echo $row['has_list'] ? ' has-list' : ''; ?>" data-reveal style="--d:140ms"><?php echo $rest; ?></div>
							</div>
						<?php } ?>
					</div>
				<?php } ?>

			</div>
		</section>
	<?php } ?>

	<!-- CTA -->
	<section class="mx-about-end">
		<div class="container">
			<div class="mx-cta" data-reveal>
				<span class="mx-cta-dots" aria-hidden="true"></span>
				<div class="mx-cta-txt">
					<span class="mx-eyebrow mx-eyebrow-light"><span class="mx-pulse"></span>Join us</span>
					<h3>Let's build what's next together</h3>
					<p>Talk to our team about training, MEP services or a partnership.</p>
				</div>
				<div class="mx-cta-actions">
					<a class="mx-btn mx-btn-glass" href="<?php echo base_url(); ?>faqs">FAQs <i class="fas fa-arrow-right"></i></a>
					<a class="mx-btn mx-btn-primary" href="<?php echo base_url(); ?>contactus">Contact us <i class="fas fa-arrow-right"></i></a>
				</div>
			</div>
		</div>
	</section>
</div>
