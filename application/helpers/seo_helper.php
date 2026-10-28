<?php
defined('BASEPATH') or exit('No direct script access allowed');

/* ==========================================================================
   SEO for the public site, targeted at Saudi Arabia.
   seo_head($vars) is called from include/_head.php with the view variables,
   and prints <title>, meta description, canonical, robots, hreflang (en-SA),
   geo tags, Open Graph / Twitter cards and JSON-LD (organization, course,
   article, breadcrumbs). No controller has to change: the page type comes
   from $content and the page data the controller already passes.
   ========================================================================== */

if (!function_exists('seo_org')) {
	// fixed business facts (same as the contact page / footer)
	function seo_org()
	{
		return array(
			'name'     => 'STED Training Center',
			'phone'    => '+966112543739',
			'whatsapp' => '+966509296490',
			'email'    => 'info@stedtraining.com',
			'city'     => 'Riyadh',
			'region'   => 'Riyadh Province',
			'country'  => 'SA',
			'lat'      => '24.796701',
			'lng'      => '46.633455',
		);
	}
}

if (!function_exists('seo_text')) {
	// HTML -> one clean line, cut at a word boundary
	function seo_text($html, $max = 158)
	{
		$t = html_entity_decode(strip_tags((string) $html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
		$t = trim(preg_replace('/\s+/u', ' ', str_replace("\xC2\xA0", ' ', $t)));
		$t = preg_replace('/^(Description|Overview)\s*:\s*/i', '', $t);
		if (mb_strlen($t, 'UTF-8') <= $max) return $t;
		$cut = mb_substr($t, 0, $max - 1, 'UTF-8');
		$sp = mb_strrpos($cut, ' ', 0, 'UTF-8');
		if ($sp !== false && $sp > $max * 0.6) $cut = mb_substr($cut, 0, $sp, 'UTF-8');
		return rtrim($cut, " ,.;:-") . '…';
	}
}

if (!function_exists('seo_img')) {
	// absolute URL of an upload, or '' when the file is missing
	function seo_img($folder, $file)
	{
		$file = (string) $file;
		if ($file === '' || $file === '0' || !is_file('uploads/' . $folder . '/' . $file)) return '';
		return base_url() . 'uploads/' . $folder . '/' . rawurlencode($file);
	}
}

if (!function_exists('seo_build')) {
	function seo_build($v)
	{
		$org  = seo_org();
		$base = base_url();
		$gi   = isset($v['generalinfo']) && is_array($v['generalinfo']) ? $v['generalinfo'] : array();
		$page = isset($v['content']) ? (string) $v['content'] : '';
		$CI   = get_instance();
		$uid  = (int) $CI->uri->segment($CI->uri->total_segments()); // id at the end of the URL
		$logo = seo_img('website', isset($gi['image']) ? $gi['image'] : '');

		$s = array(
			'title'       => 'STED Training Center | Professional Engineering & HSE Training in Saudi Arabia',
			'description' => 'STED Training Center in Riyadh offers online professional training across Saudi Arabia: testing & commissioning, MEP, electrical, HSE, quality management and project skills, with certificates.',
			'keywords'    => array('training center Riyadh', 'professional training Saudi Arabia', 'commissioning training KSA', 'MEP courses Saudi Arabia', 'HSE training Riyadh', 'online courses with certificate', 'engineering courses KSA'),
			'canonical'   => $base,
			'index'       => false,
			'type'        => 'website',
			'image'       => $logo,
			'crumbs'      => array(),
			'schema'      => array(),
		);

		switch ($page) {
			case 'home':
				$s['index'] = true;
				break;

			case 'course-details':
				$c = isset($v['coursedetail']) && is_array($v['coursedetail']) ? $v['coursedetail'] : array();
				if (empty($c['id_course'])) break;
				$title = trim((string) $c['coursetitle']);
				$cat   = trim((string) (isset($c['categtitle']) ? $c['categtitle'] : ''));
				$desc  = seo_text(isset($c['summary']) && trim(strip_tags($c['summary'])) !== '' ? $c['summary'] : (isset($c['description']) ? $c['description'] : ''), 150);
				$url   = $base . 'coursedetails/' . (int) $c['id_course'];
				$s['index']       = true;
				$s['title']       = $title . ' Course in Saudi Arabia | STED';
				$s['description'] = $desc !== '' ? $desc : ($title . ' online training course' . ($cat ? ' (' . $cat . ')' : '') . ' by STED Training Center, Riyadh. Learn at your own pace and earn a certificate.');
				$s['canonical']   = $url;
				$s['image']       = seo_img('course', isset($c['image']) ? $c['image'] : '') ?: $logo;
				array_unshift($s['keywords'], $title . ' course', $title . ' training Saudi Arabia');
				if ($cat) $s['keywords'][] = $cat . ' training KSA';
				if ($cat && !empty($c['id_category'])) $s['crumbs'][] = array($cat, $base . 'category/' . (int) $c['id_category']);
				$s['crumbs'][] = array($title, $url);

				$course = array(
					'@type'       => 'Course',
					'name'        => $title,
					'description' => $s['description'],
					'url'         => $url,
					'inLanguage'  => 'en',
					'provider'    => array('@id' => $base . '#organization'),
					'hasCourseInstance' => array_filter(array(
						'@type'          => 'CourseInstance',
						'courseMode'     => 'Online',
						'courseWorkload' => (float) $c['hours'] > 0 ? 'PT' . (float) $c['hours'] . 'H' : null,
						'location'       => array('@type' => 'VirtualLocation', 'url' => $url),
					)),
				);
				if ($s['image']) $course['image'] = $s['image'];
				if ($cat) $course['about'] = $cat;
				if (isset($c['price']) && (float) $c['price'] == 0) {
					$course['isAccessibleForFree'] = true;
					$course['offers'] = array('@type' => 'Offer', 'category' => 'Free', 'price' => 0, 'priceCurrency' => 'SAR', 'availability' => 'https://schema.org/InStock', 'url' => $url);
				}
				$s['schema'][] = $course;
				break;

			case 'category':
			case 'category-details':
				$info = $page === 'category'
					? (isset($v['categoryinfo']) ? $v['categoryinfo'] : array())
					: (isset($v['subcategoryinfo']) ? $v['subcategoryinfo'] : array());
				if (is_array($info) && isset($info[0])) $info = (array) $info[0]; // search() returns rows
				if (empty($info) || !is_array($info) || empty($info['title'])) break;
				$id    = $page === 'category' && isset($info['id_category']) ? (int) $info['id_category'] : $uid;
				$title = trim((string) $info['title']);
				$url   = $base . ($page === 'category' ? 'category/' : 'categorydetails/') . $id;
				$desc  = seo_text(isset($info['description']) ? $info['description'] : '', 150);
				$s['index']       = true;
				$s['title']       = $title . ' Training Courses in Saudi Arabia | STED';
				$s['description'] = $desc !== '' ? $desc : ($title . ' training courses online by STED Training Center in Riyadh, Saudi Arabia. Practical modules for engineers and technicians, with certificates.');
				$s['canonical']   = $url;
				$s['image']       = seo_img('category', isset($info['image']) ? $info['image'] : '') ?: $logo;
				array_unshift($s['keywords'], $title . ' courses', $title . ' training Saudi Arabia', $title . ' training Riyadh');
				$s['crumbs'][] = array($title, $url);
				$s['schema'][] = array('@type' => 'CollectionPage', 'name' => $title . ' Training Courses', 'url' => $url, 'description' => $s['description'], 'isPartOf' => array('@id' => $base . '#website'));
				break;

			case 'news':
				$n = isset($v['news']) && is_array($v['news']) ? $v['news'] : array();
				if (empty($n['id_news']) || trim((string) $n['title']) === '') break;
				$url = $base . 'news/' . (int) $n['id_news'];
				$s['index']       = true;
				$s['type']        = 'article';
				$s['title']       = trim($n['title']) . ' | STED Saudi Arabia';
				$s['description'] = seo_text(trim(strip_tags((string) $n['small_description'])) !== '' ? $n['small_description'] : $n['description']) ?: $s['description'];
				$s['canonical']   = $url;
				$s['image']       = seo_img('news', isset($n['image']) ? $n['image'] : '') ?: $logo;
				$s['crumbs'][]    = array(trim($n['title']), $url);
				$art = array(
					'@type' => 'Article', 'headline' => mb_substr(trim($n['title']), 0, 110, 'UTF-8'), 'description' => $s['description'],
					'url' => $url, 'mainEntityOfPage' => $url, 'inLanguage' => 'en',
					'author' => array('@id' => $base . '#organization'), 'publisher' => array('@id' => $base . '#organization'),
				);
				if ($s['image']) $art['image'] = $s['image'];
				if (!empty($n['created_date']) && strtotime($n['created_date']) > 0) $art['datePublished'] = date('c', strtotime($n['created_date']));
				if (!empty($n['updated_date']) && strtotime($n['updated_date']) > 0) $art['dateModified'] = date('c', strtotime($n['updated_date']));
				$s['schema'][] = $art;
				break;

			case 'aboutus':
				$s['index']       = true;
				$s['title']       = 'About Us | STED Training Center Riyadh, Saudi Arabia';
				$s['description'] = 'Learn about STED Training Center in Riyadh: practical training in testing & commissioning, MEP, HSE and quality for engineers and technicians across Saudi Arabia.';
				$s['canonical']   = $base . 'aboutus';
				$s['crumbs'][]    = array('About Us', $s['canonical']);
				$s['schema'][]    = array('@type' => 'AboutPage', 'name' => 'About STED Training Center', 'url' => $s['canonical'], 'about' => array('@id' => $base . '#organization'));
				break;

			case 'contactus':
				$s['index']       = true;
				$s['title']       = 'Contact Us | STED Training Center Riyadh, Saudi Arabia';
				$s['description'] = 'Contact STED Training Center in Riyadh, Saudi Arabia. Call ' . $org['phone'] . ', WhatsApp ' . $org['whatsapp'] . ' or email ' . $org['email'] . ' about our training courses.';
				$s['canonical']   = $base . 'contactus';
				$s['crumbs'][]    = array('Contact Us', $s['canonical']);
				$s['schema'][]    = array('@type' => 'ContactPage', 'name' => 'Contact STED Training Center', 'url' => $s['canonical'], 'about' => array('@id' => $base . '#organization'));
				break;

			case 'faq':
				$s['index']       = true;
				$s['title']       = 'FAQ | STED Training Center Saudi Arabia';
				$s['description'] = 'Answers to common questions about STED Training Center courses, enrollment, exams and certificates for learners in Saudi Arabia.';
				$s['canonical']   = $base . 'faqs';
				$s['crumbs'][]    = array('FAQ', $s['canonical']);
				$faqs = array();
				if (!empty($v['faqs']) && is_array($v['faqs'])) {
					foreach ($v['faqs'] as $f) {
						$f = (array) $f;
						$q = seo_text(isset($f['title']) ? $f['title'] : (isset($f['question']) ? $f['question'] : ''), 300);
						$a = seo_text(!empty($f['description']) ? $f['description'] : (isset($f['small_description']) ? $f['small_description'] : ''), 1000);
						if ($q !== '' && $a !== '') $faqs[] = array('@type' => 'Question', 'name' => $q, 'acceptedAnswer' => array('@type' => 'Answer', 'text' => $a));
					}
				}
				if ($faqs) $s['schema'][] = array('@type' => 'FAQPage', 'url' => $s['canonical'], 'mainEntity' => $faqs);
				break;

			case 'teacher-profile':
				$t = isset($v['lecturerinfo']) && is_array($v['lecturerinfo']) ? $v['lecturerinfo'] : array();
				if (empty($t['name']) || !$uid) break;
				$url = $base . 'teacherprofile/' . $uid;
				$s['index']       = true;
				$s['type']        = 'profile';
				$s['title']       = trim($t['name']) . ' - Instructor | STED Training Center';
				$s['description'] = seo_text(isset($t['aboutme']) ? $t['aboutme'] : '') ?: (trim($t['name']) . ', instructor at STED Training Center in Riyadh, Saudi Arabia. View courses and profile.');
				$s['canonical']   = $url;
				$s['image']       = seo_img('user', isset($t['image']) ? $t['image'] : '') ?: $logo;
				$s['crumbs'][]    = array(trim($t['name']), $url);
				break;
		}

		$s['keywords'] = array_values(array_unique($s['keywords']));
		return $s;
	}
}

if (!function_exists('seo_head')) {
	function seo_head($v)
	{
		$s    = seo_build($v);
		$org  = seo_org();
		$base = base_url();
		$e    = function ($x) { return htmlspecialchars((string) $x, ENT_QUOTES, 'UTF-8'); };
		$gi   = isset($v['generalinfo']) && is_array($v['generalinfo']) ? $v['generalinfo'] : array();

		$o = array();
		$o[] = '<title>' . $e($s['title']) . '</title>';
		$o[] = '<meta name="description" content="' . $e($s['description']) . '">';
		$o[] = '<meta name="keywords" content="' . $e(implode(', ', $s['keywords'])) . '">';
		$o[] = '<meta name="author" content="' . $e($org['name']) . '">';
		$o[] = '<meta name="robots" content="' . ($s['index'] ? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' : 'noindex, nofollow') . '">';
		if ($s['index']) {
			$o[] = '<link rel="canonical" href="' . $e($s['canonical']) . '">';
			// English pages aimed at Saudi Arabia
			$o[] = '<link rel="alternate" hreflang="en-SA" href="' . $e($s['canonical']) . '">';
			$o[] = '<link rel="alternate" hreflang="x-default" href="' . $e($s['canonical']) . '">';
		}

		// local / geo signals
		$o[] = '<meta name="geo.region" content="SA-01">';
		$o[] = '<meta name="geo.placename" content="Riyadh, Saudi Arabia">';
		$o[] = '<meta name="geo.position" content="' . $org['lat'] . ';' . $org['lng'] . '">';
		$o[] = '<meta name="ICBM" content="' . $org['lat'] . ', ' . $org['lng'] . '">';
		$o[] = '<meta name="theme-color" content="#081D30">';

		// social cards
		$o[] = '<meta property="og:site_name" content="' . $e($org['name']) . '">';
		$o[] = '<meta property="og:locale" content="en_SA">';
		$o[] = '<meta property="og:type" content="' . $e($s['type']) . '">';
		$o[] = '<meta property="og:title" content="' . $e($s['title']) . '">';
		$o[] = '<meta property="og:description" content="' . $e($s['description']) . '">';
		$o[] = '<meta property="og:url" content="' . $e($s['index'] ? $s['canonical'] : current_url()) . '">';
		if ($s['image']) {
			$o[] = '<meta property="og:image" content="' . $e($s['image']) . '">';
			$o[] = '<meta property="og:image:alt" content="' . $e($s['title']) . '">';
		}
		$o[] = '<meta name="twitter:card" content="' . ($s['image'] && $s['image'] !== seo_img('website', isset($gi['image']) ? $gi['image'] : '') ? 'summary_large_image' : 'summary') . '">';
		$o[] = '<meta name="twitter:title" content="' . $e($s['title']) . '">';
		$o[] = '<meta name="twitter:description" content="' . $e($s['description']) . '">';
		if ($s['image']) $o[] = '<meta name="twitter:image" content="' . $e($s['image']) . '">';

		// structured data (only on indexable pages)
		if ($s['index']) {
			$same = array();
			foreach (array('facebook', 'linkedin', 'twitter', 'youtube', 'instagram') as $k) {
				if (!empty($gi[$k]) && $gi[$k] !== '0' && filter_var($gi[$k], FILTER_VALIDATE_URL)) $same[] = $gi[$k];
			}
			$orgNode = array(
				'@type' => 'EducationalOrganization', '@id' => $base . '#organization',
				'name' => $org['name'], 'url' => $base,
				'email' => $org['email'], 'telephone' => $org['phone'],
				'address' => array('@type' => 'PostalAddress', 'addressLocality' => $org['city'], 'addressRegion' => $org['region'], 'addressCountry' => $org['country']),
				'geo' => array('@type' => 'GeoCoordinates', 'latitude' => (float) $org['lat'], 'longitude' => (float) $org['lng']),
				'areaServed' => array('@type' => 'Country', 'name' => 'Saudi Arabia'),
				'contactPoint' => array(array('@type' => 'ContactPoint', 'telephone' => $org['phone'], 'contactType' => 'customer service', 'areaServed' => 'SA', 'availableLanguage' => array('English', 'Arabic'))),
			);
			$logo = seo_img('website', isset($gi['image']) ? $gi['image'] : '');
			if ($logo) $orgNode['logo'] = $logo;
			if ($same) $orgNode['sameAs'] = $same;

			$graph = array($orgNode, array('@type' => 'WebSite', '@id' => $base . '#website', 'url' => $base, 'name' => $org['name'], 'inLanguage' => 'en-SA', 'publisher' => array('@id' => $base . '#organization')));
			if ($s['crumbs']) {
				$items = array(array('@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $base));
				foreach ($s['crumbs'] as $i => $c) $items[] = array('@type' => 'ListItem', 'position' => $i + 2, 'name' => $c[0], 'item' => $c[1]);
				$graph[] = array('@type' => 'BreadcrumbList', 'itemListElement' => $items);
			}
			foreach ($s['schema'] as $node) $graph[] = $node;
			$json = json_encode(array('@context' => 'https://schema.org', '@graph' => $graph), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP);
			if ($json) $o[] = '<script type="application/ld+json">' . $json . '</script>';
		}

		echo implode("\n", $o) . "\n";
	}
}
