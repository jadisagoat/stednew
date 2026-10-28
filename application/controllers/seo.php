<?php
defined('BASEPATH') or exit('No direct script access allowed');

/* Live sitemap.xml and robots.txt (routes in config/routes.php).
   Built from the database, so new courses / categories / news show up automatically. */
class Seo extends CI_Controller
{
	public function sitemap()
	{
		$base = base_url();
		$urls = array(
			array($base, null, 'weekly', '1.0'),
			array($base . 'aboutus', null, 'monthly', '0.6'),
			array($base . 'contactus', null, 'monthly', '0.6'),
			array($base . 'faqs', null, 'monthly', '0.5'),
		);

		$rows = $this->db->select('id_category, updated_date')->where(array('active' => 1, 'deleted' => 0))->order_by('sort_order')->get('category')->result_array();
		foreach ($rows as $r) $urls[] = array($base . 'category/' . (int) $r['id_category'], $r['updated_date'], 'weekly', '0.8');

		$rows = $this->db->select('id_course, updated_date')->where(array('active' => 1, 'deleted' => 0))->order_by('sort_order')->get('course')->result_array();
		foreach ($rows as $r) $urls[] = array($base . 'coursedetails/' . (int) $r['id_course'], $r['updated_date'], 'weekly', '0.9');

		$rows = $this->db->select('id_news, updated_date')->where('deleted', 0)->order_by('sort_order', 'desc')->get('news')->result_array();
		foreach ($rows as $r) $urls[] = array($base . 'news/' . (int) $r['id_news'], $r['updated_date'], 'monthly', '0.6');

		$x = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
		$x .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";
		foreach ($urls as $u) {
			$loc = htmlspecialchars($u[0], ENT_QUOTES, 'UTF-8');
			$x .= "  <url>\n    <loc>$loc</loc>\n";
			$x .= '    <xhtml:link rel="alternate" hreflang="en-SA" href="' . $loc . '"/>' . "\n";
			if (!empty($u[1]) && strtotime($u[1]) > 0) $x .= '    <lastmod>' . date('Y-m-d', strtotime($u[1])) . "</lastmod>\n";
			$x .= "    <changefreq>{$u[2]}</changefreq>\n    <priority>{$u[3]}</priority>\n  </url>\n";
		}
		$x .= "</urlset>\n";

		header('Content-Type: application/xml; charset=UTF-8');
		header('X-Robots-Tag: noindex');
		echo $x;
	}

	public function robots()
	{
		$base = base_url();
		header('Content-Type: text/plain; charset=UTF-8');
		echo "User-agent: *\n";
		echo "Allow: /\n";
		// private / technical areas
		foreach (array('back_office/', 'manage', 'home/editprofile', 'editprofile', 'wishlist', 'mycourses', 'enrolled_courses', 'resetpassword') as $p) {
			echo "Disallow: /" . ltrim(parse_url($base, PHP_URL_PATH), '/') . $p . "\n";
		}
		echo "\nSitemap: {$base}sitemap.xml\n";
	}
}
