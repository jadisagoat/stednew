<link rel="stylesheet" href="<?= base_url() ?>sted_theme/assets/vendor/libs/apex-charts/apex-charts.css" />
<script src="<?= base_url() ?>sted_theme/assets/vendor/libs/apex-charts/apexcharts.js"></script>
<?php
/*
 * Back-office dashboard (design only).
 * Uses the same variables from Home::dashboard() and the same AJAX endpoints
 * in back_office/user as before. No queries or data changes here.
 */

if (!function_exists('sd_e')) {
	function sd_e($v)
	{
		return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
	}
}
if (!function_exists('sd_initials')) {
	function sd_initials($name)
	{
		$parts = preg_split('/\s+/', trim((string) $name));
		$out = '';
		foreach ($parts as $p) {
			if ($p !== '') $out .= mb_substr($p, 0, 1, 'UTF-8');
			if (mb_strlen($out, 'UTF-8') >= 2) break;
		}
		return mb_strtoupper($out !== '' ? $out : '?', 'UTF-8');
	}
}
if (!function_exists('sd_t')) {
	/* Dictionary text (key without the dash_st_ prefix), HTML-escaped; {placeholders} are replaced by $repl (raw HTML). */
	function sd_t($key, $repl = array())
	{
		$s = sd_e(bo_t('dash_st_' . $key));
		foreach ($repl as $k => $v) $s = str_replace('{' . $k . '}', $v, $s);
		return $s;
	}
}
if (!function_exists('sd_num')) {
	function sd_num($v, $dec = 0)
	{
		return number_format((float) $v, $dec);
	}
}

// Registration (users vs employees)
$ue = isset($user_employee[0]) ? $user_employee[0] : array();
$registered_users      = isset($ue['registered_users']) ? (int) $ue['registered_users'] : 0;
$total_employees       = isset($ue['total_employees']) ? (int) $ue['total_employees'] : 0;
$percentage_registered = isset($ue['percentage_registered']) ? (float) $ue['percentage_registered'] : 0;
$percentage_registered = max(0, min(100, $percentage_registered));

// Exam results
$eh = isset($exam_history[0]) ? $exam_history[0] : array();
$total_exams       = isset($eh['total_exams']) ? (int) $eh['total_exams'] : 0;
$total_failures    = isset($eh['total_failures']) ? (int) $eh['total_failures'] : 0;
$total_pass_first  = isset($eh['total_pass_first_attempt']) ? (int) $eh['total_pass_first_attempt'] : 0;
$total_pass_later  = isset($eh['total_pass_subsequent_attempts']) ? (int) $eh['total_pass_subsequent_attempts'] : 0;
$pct_fail          = isset($eh['percentage_failures']) ? (float) $eh['percentage_failures'] : 0;
$pct_pass_first    = isset($eh['percentage_pass_first_attempt']) ? (float) $eh['percentage_pass_first_attempt'] : 0;
$pct_pass_later    = isset($eh['percentage_pass_subsequent_attempts']) ? (float) $eh['percentage_pass_subsequent_attempts'] : 0;
$avg_first_score   = isset($eh['average_grade_pass_first_attempt']) ? (float) $eh['average_grade_pass_first_attempt'] : 0;
$pct_pass_total    = $pct_pass_first + $pct_pass_later;

// Counts
$count_users    = is_array($users) ? count($users) : 0;
$count_employee = is_array($employee) ? count($employee) : 0;
$count_courses  = is_array($courses) ? count($courses) : 0;
$count_contents = is_array($content_1) ? count($content_1) : 0;
$count_unread   = is_array($new_message) ? count($new_message) : 0;

$site_logo  = !empty($title_site['image']) ? base_url() . 'uploads/website/' . $title_site['image'] : '';
$site_name  = !empty($title_site['website_title']) ? $title_site['website_title'] : 'STED';
$admin_name = $this->session->userdata('user_name');

$hour = (int) date('G');
$greeting = sd_t($hour < 12 ? 'good_morning' : ($hour < 18 ? 'good_afternoon' : 'good_evening'));
$currentMonth = date('m');
$hero_date = sd_t('date_fmt', array('day' => sd_t('d' . date('w')), 'd' => date('d'), 'month' => sd_t('m' . date('n')), 'y' => date('Y')));
$sd_chev = bo_rtl() ? 'bx-chevron-left' : 'bx-chevron-right';
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap">

<style>
	.sd-dash,
	.sd-dash h1,
	.sd-dash h5,
	.sd-dash h6,
	.sd-dash button,
	.sd-dash select,
	#detailsModal {
		font-family: "Plus Jakarta Sans", "Segoe UI", -apple-system, BlinkMacSystemFont, Roboto, "Helvetica Neue", Arial, sans-serif;
	}

	/* ---------- Tokens ---------- */
	.sd-dash {
		--sd-surface: #ffffff;
		--sd-surface-2: #f6f7fb;
		--sd-border: #e8eaf1;
		--sd-grid: #eef0f5;
		--sd-text: #1d2433;
		--sd-text-2: #4b5468;
		--sd-muted: #8a93a6;
		--sd-shadow: 0 1px 2px rgba(16, 24, 40, .04), 0 4px 16px rgba(16, 24, 40, .06);
		--sd-shadow-hover: 0 2px 4px rgba(16, 24, 40, .06), 0 12px 28px rgba(16, 24, 40, .10);
		--sd-radius: 16px;

		--sd-accent: #00878a;
		--sd-accent-soft: rgba(0, 173, 177, .10);
		--sd-series-1: #00adb1;
		--sd-series-2: #f16624;

		--sd-good: #0ca30c;
		--sd-warning: #fab219;
		--sd-critical: #d03b3b;

		--sd-hero-1: #081d30;
		--sd-hero-2: #0d4f5e;
		--sd-hero-3: #00adb1;
		color: var(--sd-text-2);
	}

	html.dark-style .sd-dash {
		--sd-surface: #232736;
		--sd-surface-2: #1b1e2a;
		--sd-border: #333849;
		--sd-grid: #2d3242;
		--sd-text: #eef0f6;
		--sd-text-2: #c3c8d6;
		--sd-muted: #8d95a8;
		--sd-shadow: 0 1px 2px rgba(0, 0, 0, .3), 0 4px 16px rgba(0, 0, 0, .25);
		--sd-shadow-hover: 0 2px 4px rgba(0, 0, 0, .35), 0 12px 28px rgba(0, 0, 0, .35);
		--sd-accent: #14c6c9;
		--sd-accent-soft: rgba(20, 198, 201, .16);
		--sd-series-1: #14c6c9;
		--sd-series-2: #d95926;
		--sd-hero-1: #081d30;
		--sd-hero-2: #0d4f5e;
		--sd-hero-3: #00878a;
	}

	/* ---------- Layout ---------- */
	.sd-dash .sd-grid {
		display: grid;
		gap: 24px;
	}

	.sd-dash .sd-row-2 {
		display: grid;
		gap: 24px;
		grid-template-columns: minmax(0, 7fr) minmax(0, 5fr);
	}

	@media (max-width: 991.98px) {
		.sd-dash .sd-row-2 {
			grid-template-columns: minmax(0, 1fr);
		}
	}

	/* ---------- Card ---------- */
	.sd-card {
		background: var(--sd-surface);
		border: 1px solid var(--sd-border);
		border-radius: var(--sd-radius);
		box-shadow: var(--sd-shadow);
		display: flex;
		flex-direction: column;
		min-width: 0;
		transition: box-shadow .2s ease;
	}

	.sd-card:hover {
		box-shadow: var(--sd-shadow-hover);
	}

	.sd-card-head {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 12px;
		flex-wrap: wrap;
		padding: 20px 22px 0;
	}

	.sd-card-title {
		margin: 0;
		font-size: 1.02rem;
		font-weight: 600;
		color: var(--sd-text);
		display: flex;
		align-items: center;
		gap: 10px;
	}

	.sd-card-sub {
		margin: 2px 0 0;
		font-size: .82rem;
		color: var(--sd-muted);
	}

	.sd-card-body {
		padding: 18px 22px 22px;
		flex: 1;
		min-width: 0;
	}

	.sd-icon-chip {
		width: 34px;
		height: 34px;
		border-radius: 10px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		background: var(--sd-accent-soft);
		color: var(--sd-accent);
		font-size: 1.15rem;
		flex-shrink: 0;
	}

	.sd-link {
		font-size: .84rem;
		font-weight: 500;
		color: var(--sd-accent) !important;
		display: inline-flex;
		align-items: center;
		gap: 2px;
		white-space: nowrap;
	}

	.sd-link:hover {
		text-decoration: underline;
	}

	/* ---------- Hero ---------- */
	.sd-hero {
		position: relative;
		overflow: hidden;
		border-radius: 20px;
		padding: 28px 30px;
		color: #fff;
		background: linear-gradient(120deg, var(--sd-hero-1) 0%, var(--sd-hero-2) 55%, var(--sd-hero-3) 100%);
		box-shadow: 0 10px 30px rgba(31, 79, 168, .25);
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 24px;
		flex-wrap: wrap;
	}

	.sd-hero::before,
	.sd-hero::after {
		content: "";
		position: absolute;
		border-radius: 50%;
		background: rgba(255, 255, 255, .08);
		pointer-events: none;
	}

	.sd-hero::before {
		width: 340px;
		height: 340px;
		right: -90px;
		top: -170px;
	}

	.sd-hero::after {
		width: 220px;
		height: 220px;
		right: 180px;
		bottom: -150px;
	}

	.sd-hero-main {
		display: flex;
		align-items: center;
		gap: 18px;
		position: relative;
		z-index: 1;
		min-width: 0;
	}

	.sd-hero-logo {
		width: 64px;
		height: 64px;
		border-radius: 16px;
		background: #fff;
		padding: 8px;
		object-fit: contain;
		flex-shrink: 0;
		box-shadow: 0 6px 16px rgba(0, 0, 0, .15);
	}

	.sd-hero-date {
		font-size: .82rem;
		opacity: .85;
		display: flex;
		align-items: center;
		gap: 6px;
	}

	.sd-hero h1 {
		color: #fff;
		font-size: 1.55rem;
		font-weight: 600;
		margin: 4px 0 2px;
		line-height: 1.25;
	}

	.sd-hero p {
		margin: 0;
		opacity: .9;
		font-size: .92rem;
	}

	.sd-hero-actions {
		display: flex;
		gap: 10px;
		flex-wrap: wrap;
		position: relative;
		z-index: 1;
	}

	.sd-hero-btn {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 9px 14px;
		border-radius: 12px;
		background: rgba(255, 255, 255, .14);
		border: 1px solid rgba(255, 255, 255, .22);
		color: #fff !important;
		font-size: .86rem;
		font-weight: 500;
		backdrop-filter: blur(4px);
		transition: background .2s ease, transform .2s ease;
	}

	.sd-hero-btn:hover {
		background: rgba(255, 255, 255, .24);
		transform: translateY(-1px);
	}

	.sd-hero-btn i {
		font-size: 1.1rem;
	}

	.sd-hero-btn .sd-pill-count {
		background: #fff;
		color: var(--sd-hero-1);
		border-radius: 999px;
		font-size: .72rem;
		font-weight: 700;
		padding: 1px 7px;
	}

	/* ---------- KPI tiles ---------- */
	.sd-kpis {
		display: grid;
		gap: 18px;
		grid-template-columns: repeat(6, minmax(0, 1fr));
	}

	@media (max-width: 1399.98px) {
		.sd-kpis {
			grid-template-columns: repeat(3, minmax(0, 1fr));
		}
	}

	@media (max-width: 575.98px) {
		.sd-kpis {
			grid-template-columns: repeat(2, minmax(0, 1fr));
			gap: 12px;
		}
	}

	.sd-kpi {
		background: var(--sd-surface);
		border: 1px solid var(--sd-border);
		border-radius: var(--sd-radius);
		box-shadow: var(--sd-shadow);
		padding: 18px;
		display: flex;
		flex-direction: column;
		gap: 12px;
		color: inherit !important;
		transition: transform .2s ease, box-shadow .2s ease;
		min-width: 0;
	}

	a.sd-kpi:hover {
		transform: translateY(-2px);
		box-shadow: var(--sd-shadow-hover);
	}

	.sd-kpi-top {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 8px;
	}

	.sd-kpi-label {
		font-size: .84rem;
		color: var(--sd-muted);
		font-weight: 500;
	}

	.sd-kpi-value {
		font-size: 1.75rem;
		font-weight: 700;
		color: var(--sd-text);
		line-height: 1;
		letter-spacing: -.01em;
		font-variant-numeric: tabular-nums;
	}

	.sd-kpi-foot {
		font-size: .78rem;
		color: var(--sd-muted);
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.sd-kpi-foot b {
		color: var(--sd-text-2);
		font-weight: 600;
	}

	.sd-kpi .sd-icon-chip.c-blue {
		background: rgba(0, 173, 177, .12);
		color: #00878a;
	}

	.sd-kpi .sd-icon-chip.c-violet {
		background: rgba(98, 80, 214, .12);
		color: #6250d6;
	}

	.sd-kpi .sd-icon-chip.c-aqua {
		background: rgba(27, 175, 122, .12);
		color: #14916a;
	}

	.sd-kpi .sd-icon-chip.c-orange {
		background: rgba(235, 104, 52, .12);
		color: #d4561f;
	}

	.sd-kpi .sd-icon-chip.c-magenta {
		background: rgba(232, 123, 164, .15);
		color: #c94d7c;
	}

	.sd-kpi .sd-icon-chip.c-red {
		background: rgba(208, 59, 59, .12);
		color: #d03b3b;
	}

	html.dark-style .sd-kpi .sd-icon-chip {
		filter: brightness(1.25);
	}

	/* ---------- Exam results ---------- */
	.sd-exam-head {
		display: flex;
		align-items: flex-end;
		justify-content: space-between;
		gap: 16px;
		flex-wrap: wrap;
		margin-bottom: 18px;
	}

	.sd-big {
		font-size: 2.3rem;
		font-weight: 700;
		color: var(--sd-text);
		line-height: 1;
		font-variant-numeric: tabular-nums;
	}

	.sd-big small {
		font-size: .9rem;
		font-weight: 500;
		color: var(--sd-muted);
		margin-left: 6px;
	}

	.sd-badge-soft {
		display: inline-flex;
		align-items: center;
		gap: 4px;
		padding: 4px 10px;
		border-radius: 999px;
		font-size: .78rem;
		font-weight: 600;
		background: var(--sd-surface-2);
		color: var(--sd-text-2);
		border: 1px solid var(--sd-border);
	}

	.sd-stack {
		display: flex;
		height: 14px;
		border-radius: 999px;
		overflow: hidden;
		background: var(--sd-surface-2);
		gap: 2px;
	}

	.sd-stack span {
		height: 100%;
		transition: width .8s cubic-bezier(.2, .8, .2, 1);
	}

	.sd-legend {
		list-style: none;
		margin: 18px 0 0;
		padding: 0;
		display: grid;
		gap: 10px;
	}

	.sd-legend li {
		display: grid;
		grid-template-columns: 32px 1fr auto auto;
		align-items: center;
		gap: 12px;
		padding: 10px 12px;
		border-radius: 12px;
		background: var(--sd-surface-2);
	}

	.sd-legend .sd-dot {
		width: 32px;
		height: 32px;
		border-radius: 10px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		color: #fff;
		font-size: 1rem;
	}

	.sd-legend .sd-l-name {
		color: var(--sd-text);
		font-weight: 500;
		font-size: .9rem;
	}

	.sd-legend .sd-l-count {
		font-variant-numeric: tabular-nums;
		color: var(--sd-text);
		font-weight: 600;
	}

	.sd-legend .sd-l-pct {
		font-variant-numeric: tabular-nums;
		color: var(--sd-muted);
		font-size: .82rem;
		min-width: 52px;
		text-align: right;
	}

	/* ---------- Registration gauge ---------- */
	.sd-gauge-wrap {
		display: flex;
		flex-direction: column;
		align-items: center;
		text-align: center;
	}

	.sd-gauge-meta {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 12px;
		width: 100%;
		margin-top: 6px;
	}

	.sd-gauge-meta div {
		background: var(--sd-surface-2);
		border-radius: 12px;
		padding: 12px;
	}

	.sd-gauge-meta b {
		display: block;
		font-size: 1.25rem;
		color: var(--sd-text);
		font-variant-numeric: tabular-nums;
	}

	.sd-gauge-meta span {
		font-size: .78rem;
		color: var(--sd-muted);
	}

	/* ---------- Controls ---------- */
	.sd-controls {
		display: flex;
		align-items: center;
		gap: 10px;
		flex-wrap: wrap;
	}

	.sd-seg {
		display: inline-flex;
		background: var(--sd-surface-2);
		border: 1px solid var(--sd-border);
		border-radius: 12px;
		padding: 3px;
	}

	.sd-seg button {
		border: 0;
		background: transparent;
		color: var(--sd-muted);
		font-size: .84rem;
		font-weight: 500;
		padding: 6px 14px;
		border-radius: 9px;
		cursor: pointer;
		transition: background .15s ease, color .15s ease;
	}

	.sd-seg button.is-active {
		background: var(--sd-surface);
		color: var(--sd-text);
		box-shadow: 0 1px 3px rgba(16, 24, 40, .12);
	}

	.sd-select {
		appearance: none;
		-webkit-appearance: none;
		background-color: var(--sd-surface-2);
		background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238a93a6' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
		background-repeat: no-repeat;
		background-position: right 12px center;
		border: 1px solid var(--sd-border);
		border-radius: 12px;
		color: var(--sd-text);
		font-size: .84rem;
		font-weight: 500;
		padding: 7px 34px 7px 12px;
		cursor: pointer;
		outline: none;
	}

	.sd-select:focus,
	.sd-seg button:focus-visible {
		box-shadow: 0 0 0 3px var(--sd-accent-soft);
		border-color: var(--sd-accent);
	}

	.sd-summary {
		display: flex;
		gap: 22px;
		flex-wrap: wrap;
		margin-bottom: 8px;
	}

	.sd-summary div span {
		display: block;
		font-size: .78rem;
		color: var(--sd-muted);
	}

	.sd-summary div b {
		font-size: 1.3rem;
		color: var(--sd-text);
		font-variant-numeric: tabular-nums;
	}

	/* ---------- Chart holders ---------- */
	.sd-chart {
		position: relative;
		min-height: 300px;
	}

	.sd-chart .apexcharts-tooltip {
		border-radius: 10px !important;
		box-shadow: var(--sd-shadow-hover) !important;
		border: 1px solid var(--sd-border) !important;
	}

	.sd-state {
		position: absolute;
		inset: 0;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		gap: 8px;
		color: var(--sd-muted);
		font-size: .9rem;
		text-align: center;
		pointer-events: none;
	}

	.sd-state i {
		font-size: 2rem;
		opacity: .6;
	}

	.sd-spinner {
		width: 28px;
		height: 28px;
		border-radius: 50%;
		border: 3px solid var(--sd-grid);
		border-top-color: var(--sd-accent);
		animation: sd-spin .8s linear infinite;
	}

	@keyframes sd-spin {
		to {
			transform: rotate(360deg);
		}
	}

	.sd-chart-legend {
		display: flex;
		gap: 16px;
		flex-wrap: wrap;
		font-size: .82rem;
		color: var(--sd-text-2);
	}

	.sd-chart-legend span {
		display: inline-flex;
		align-items: center;
		gap: 6px;
	}

	.sd-chart-legend i {
		width: 10px;
		height: 10px;
		border-radius: 3px;
		display: inline-block;
	}

	/* ---------- Tables ---------- */
	.sd-table-wrap {
		overflow-x: auto;
		margin: 0 -22px -22px;
	}

	.sd-table {
		width: 100%;
		border-collapse: separate;
		border-spacing: 0;
		font-size: .88rem;
	}

	.sd-table th {
		text-align: left;
		font-size: .74rem;
		text-transform: uppercase;
		letter-spacing: .04em;
		font-weight: 600;
		color: var(--sd-muted);
		padding: 10px 22px;
		background: var(--sd-surface-2);
		border-top: 1px solid var(--sd-border);
		border-bottom: 1px solid var(--sd-border);
		white-space: nowrap;
	}

	.sd-table td {
		padding: 12px 22px;
		border-bottom: 1px solid var(--sd-border);
		color: var(--sd-text-2);
		vertical-align: middle;
	}

	.sd-table tr:last-child td {
		border-bottom: 0;
	}

	.sd-table tbody tr {
		transition: background .15s ease;
	}

	.sd-table tbody tr:hover {
		background: var(--sd-surface-2);
	}

	.sd-person {
		display: flex;
		align-items: center;
		gap: 10px;
		min-width: 0;
	}

	.sd-avatar {
		width: 34px;
		height: 34px;
		border-radius: 50%;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-size: .76rem;
		font-weight: 700;
		flex-shrink: 0;
		background: var(--sd-accent-soft);
		color: var(--sd-accent);
	}

	.sd-avatar.a1 {
		background: rgba(98, 80, 214, .12);
		color: #6250d6;
	}

	.sd-avatar.a2 {
		background: rgba(27, 175, 122, .14);
		color: #14916a;
	}

	.sd-avatar.a3 {
		background: rgba(235, 104, 52, .12);
		color: #d4561f;
	}

	.sd-avatar.a4 {
		background: rgba(232, 123, 164, .15);
		color: #c94d7c;
	}

	.sd-name {
		color: var(--sd-text);
		font-weight: 500;
		white-space: nowrap;
	}

	.sd-course-cell {
		max-width: 260px;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.sd-progress {
		display: flex;
		align-items: center;
		gap: 10px;
		min-width: 130px;
	}

	.sd-progress-track {
		flex: 1;
		height: 8px;
		border-radius: 999px;
		background: var(--sd-surface-2);
		overflow: hidden;
		border: 1px solid var(--sd-border);
	}

	.sd-progress-bar {
		height: 100%;
		border-radius: 999px;
		background: var(--sd-accent);
	}

	.sd-progress-bar.is-done {
		background: var(--sd-good);
	}

	.sd-progress b {
		font-size: .8rem;
		font-weight: 600;
		color: var(--sd-text);
		min-width: 42px;
		text-align: right;
		font-variant-numeric: tabular-nums;
	}

	.sd-grade {
		display: inline-block;
		padding: 3px 10px;
		border-radius: 999px;
		font-size: .76rem;
		font-weight: 600;
		background: var(--sd-accent-soft);
		color: var(--sd-accent);
		white-space: nowrap;
	}

	.sd-date {
		font-size: .8rem;
		color: var(--sd-muted);
		white-space: nowrap;
		font-variant-numeric: tabular-nums;
	}

	.sd-empty-row td {
		text-align: center;
		padding: 36px 22px;
		color: var(--sd-muted);
	}

	/* ---------- Modal ---------- */
	#detailsModal .modal-content {
		border-radius: 18px;
		border: 0;
		overflow: hidden;
	}

	#detailsModal .modal-header {
		background: linear-gradient(120deg, #0d4f5e, #00878a);
		color: #fff;
		padding: 18px 22px;
	}

	#detailsModal .modal-title {
		color: #fff;
		font-weight: 600;
	}

	#detailsModal .btn-close {
		filter: invert(1) grayscale(1) brightness(2);
	}

	.sd-modal-stats {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 12px;
		margin-bottom: 18px;
	}

	.sd-modal-stats div {
		background: var(--bs-gray-100, #f6f7fb);
		border-radius: 12px;
		padding: 12px 14px;
	}

	.sd-modal-stats b {
		display: block;
		font-size: 1.4rem;
	}

	.sd-modal-stats span {
		font-size: .8rem;
		opacity: .7;
	}

	.sd-chip-list {
		display: flex;
		flex-wrap: wrap;
		gap: 6px;
		margin: 6px 0 16px;
	}

	.sd-chip-list span {
		padding: 4px 10px;
		border-radius: 999px;
		font-size: .8rem;
		background: rgba(0, 173, 177, .10);
		color: #0d4f5e;
	}

	html.dark-style .sd-chip-list span {
		color: #9cc4f5;
	}

	/* ---------- Entrance animation ---------- */
	@media (prefers-reduced-motion: no-preference) {
		.sd-dash .sd-anim {
			animation: sd-rise .5s cubic-bezier(.2, .8, .2, 1) both;
		}

		.sd-dash .sd-anim:nth-child(2) {
			animation-delay: .05s;
		}

		.sd-dash .sd-anim:nth-child(3) {
			animation-delay: .1s;
		}

		.sd-dash .sd-anim:nth-child(4) {
			animation-delay: .15s;
		}

		.sd-dash .sd-anim:nth-child(5) {
			animation-delay: .2s;
		}

		.sd-dash .sd-anim:nth-child(6) {
			animation-delay: .25s;
		}
	}

	@keyframes sd-rise {
		from {
			opacity: 0;
			transform: translateY(8px);
		}

		to {
			opacity: 1;
			transform: none;
		}
	}

	@media (max-width: 575.98px) {
		.sd-hero {
			padding: 22px 18px;
		}

		.sd-hero h1 {
			font-size: 1.25rem;
		}

		.sd-hero-logo {
			width: 52px;
			height: 52px;
		}

		.sd-card-head {
			padding: 16px 16px 0;
		}

		.sd-card-body {
			padding: 14px 16px 16px;
		}

		.sd-table-wrap {
			margin: 0 -16px -16px;
		}

		.sd-table th,
		.sd-table td {
			padding-left: 16px;
			padding-right: 16px;
		}
	}

	/* ---------- RTL (Arabic) ---------- */
	[dir=rtl] .sd-dash,
	[dir=rtl] .sd-dash h1,
	[dir=rtl] .sd-dash h5,
	[dir=rtl] .sd-dash h6,
	[dir=rtl] .sd-dash button,
	[dir=rtl] .sd-dash select,
	[dir=rtl] #detailsModal {
		font-family: "Cairo", "Plus Jakarta Sans", "Segoe UI", Tahoma, Arial, sans-serif;
	}

	[dir=rtl] .sd-hero::before {
		right: auto;
		left: -90px;
	}

	[dir=rtl] .sd-hero::after {
		right: auto;
		left: 180px;
	}

	[dir=rtl] .sd-big small {
		margin-left: 0;
		margin-right: 6px;
	}

	[dir=rtl] .sd-legend .sd-l-pct,
	[dir=rtl] .sd-progress b {
		text-align: left;
	}

	[dir=rtl] .sd-table th {
		text-align: right;
		letter-spacing: 0;
	}

	[dir=rtl] .sd-select {
		background-position: left 12px center;
		padding: 7px 12px 7px 34px;
	}

	/* ApexCharts draws its SVG left-to-right; keep the plot LTR (mirroring is done in the chart options)
	   and only the tooltip text right-to-left. */
	[dir=rtl] .sd-chart > div[id],
	[dir=rtl] #sdRegGauge {
		direction: ltr;
	}

	[dir=rtl] .sd-chart .apexcharts-tooltip {
		direction: rtl;
		text-align: right;
	}

	[dir=rtl] .sd-chart .apexcharts-tooltip-marker {
		margin-right: 0;
		margin-left: 10px;
	}
</style>

<div class="sd-dash">
	<div class="sd-grid">

		<!-- ===== Hero ===== -->
		<section class="sd-hero sd-anim">
			<div class="sd-hero-main">
				<?php if ($site_logo) { ?>
					<img src="<?= sd_e($site_logo); ?>" alt="<?= sd_e($site_name); ?>" class="sd-hero-logo" />
				<?php } ?>
				<div style="min-width:0">
					<div class="sd-hero-date"><i class="bx bx-calendar"></i><?= $hero_date; ?></div>
					<h1><?= $admin_name ? sd_t('greeting_name', array('greeting' => $greeting, 'name' => '<bdi>' . sd_e($admin_name) . '</bdi>')) : $greeting; ?> 👋</h1>
					<p><?= sd_t('hero_sub', array('site' => '<bdi>' . sd_e($site_name) . '</bdi>')); ?></p>
				</div>
			</div>
			<div class="sd-hero-actions">
				<a class="sd-hero-btn" href="<?= base_url(); ?>back_office/user"><i class="bx bx-group"></i><?= sd_t('btn_users'); ?></a>
				<a class="sd-hero-btn" href="<?= base_url(); ?>back_office/course"><i class="bx bx-book-open"></i><?= sd_t('btn_courses'); ?></a>
				<a class="sd-hero-btn" href="<?= base_url(); ?>back_office/exam_history"><i class="bx bx-task"></i><?= sd_t('btn_exam_history'); ?></a>
				<a class="sd-hero-btn" href="<?= base_url(); ?>back_office/contactform"><i class="bx bx-envelope"></i><?= sd_t('btn_messages'); ?>
					<?php if ($count_unread > 0) { ?><span class="sd-pill-count"><?= $count_unread; ?></span><?php } ?>
				</a>
			</div>
		</section>

		<!-- ===== KPI tiles ===== -->
		<section class="sd-kpis">
			<a class="sd-kpi sd-anim" href="<?= base_url(); ?>back_office/user">
				<div class="sd-kpi-top">
					<span class="sd-kpi-label"><?= sd_t('kpi_users'); ?></span>
					<span class="sd-icon-chip c-blue"><i class="bx bx-user-check"></i></span>
				</div>
				<div class="sd-kpi-value"><?= sd_num($count_users); ?></div>
				<div class="sd-kpi-foot"><?= sd_t('kpi_users_foot', array('n' => '<b>' . sd_num($registered_users) . '</b>')); ?></div>
			</a>
			<a class="sd-kpi sd-anim" href="<?= base_url(); ?>back_office/employee">
				<div class="sd-kpi-top">
					<span class="sd-kpi-label"><?= sd_t('kpi_employees'); ?></span>
					<span class="sd-icon-chip c-violet"><i class="bx bx-id-card"></i></span>
				</div>
				<div class="sd-kpi-value"><?= sd_num($count_employee); ?></div>
				<div class="sd-kpi-foot"><?= sd_t('kpi_employees_foot', array('n' => '<b>' . sd_num($percentage_registered, 1) . '%</b>')); ?></div>
			</a>
			<a class="sd-kpi sd-anim" href="<?= base_url(); ?>back_office/course">
				<div class="sd-kpi-top">
					<span class="sd-kpi-label"><?= sd_t('kpi_courses'); ?></span>
					<span class="sd-icon-chip c-aqua"><i class="bx bx-book-open"></i></span>
				</div>
				<div class="sd-kpi-value"><?= sd_num($count_courses); ?></div>
				<div class="sd-kpi-foot"><?= sd_t('kpi_courses_foot'); ?></div>
			</a>
			<a class="sd-kpi sd-anim" href="<?= base_url(); ?>back_office/content">
				<div class="sd-kpi-top">
					<span class="sd-kpi-label"><?= sd_t('kpi_contents'); ?></span>
					<span class="sd-icon-chip c-orange"><i class="bx bx-play-circle"></i></span>
				</div>
				<div class="sd-kpi-value"><?= sd_num($count_contents); ?></div>
				<div class="sd-kpi-foot"><?= sd_t('kpi_contents_foot', array('n' => '<b>' . ($count_courses ? sd_num($count_contents / $count_courses, 1) : 0) . '</b>')); ?></div>
			</a>
			<a class="sd-kpi sd-anim" href="<?= base_url(); ?>back_office/exam_history">
				<div class="sd-kpi-top">
					<span class="sd-kpi-label"><?= sd_t('kpi_exams'); ?></span>
					<span class="sd-icon-chip c-magenta"><i class="bx bx-task"></i></span>
				</div>
				<div class="sd-kpi-value"><?= sd_num($total_exams); ?></div>
				<div class="sd-kpi-foot"><?= sd_t('kpi_exams_foot', array('n' => '<b>' . sd_num($pct_pass_total, 1) . '%</b>')); ?></div>
			</a>
			<a class="sd-kpi sd-anim" href="<?= base_url(); ?>back_office/contactform">
				<div class="sd-kpi-top">
					<span class="sd-kpi-label"><?= sd_t('kpi_unread'); ?></span>
					<span class="sd-icon-chip c-red"><i class="bx bx-envelope"></i></span>
				</div>
				<div class="sd-kpi-value"><?= sd_num($count_unread); ?></div>
				<div class="sd-kpi-foot"><?= sd_t('kpi_unread_foot', array('n' => '<b>' . (is_array($messages) ? sd_num(count($messages)) : 0) . '</b>')); ?></div>
			</a>
		</section>

		<!-- ===== Exam results + Registration ===== -->
		<section class="sd-row-2">
			<div class="sd-card">
				<div class="sd-card-head">
					<div>
						<h5 class="sd-card-title"><span class="sd-icon-chip"><i class="bx bx-bar-chart-alt-2"></i></span><?= sd_t('exam_results'); ?></h5>
						<p class="sd-card-sub"><?= sd_t('exam_results_sub'); ?></p>
					</div>
					<a class="sd-link" href="<?= base_url(); ?>back_office/exam_history"><?= sd_t('view_all'); ?> <i class="bx <?= $sd_chev; ?>"></i></a>
				</div>
				<div class="sd-card-body">
					<div class="sd-exam-head">
						<div class="sd-big"><?= sd_num($total_exams); ?><small><?= sd_t('exams_unit'); ?></small></div>
						<?php if ($avg_first_score > 0) { ?>
							<span class="sd-badge-soft"><i class="bx bx-star"></i><?= sd_t('avg_first_score', array('n' => sd_num($avg_first_score, 1))); ?></span>
						<?php } ?>
					</div>
					<div class="sd-stack" role="img" aria-label="<?= sd_t('exam_aria', array('a' => sd_num($pct_pass_first, 1), 'b' => sd_num($pct_pass_later, 1), 'c' => sd_num($pct_fail, 1))); ?>">
						<span class="sd-js-bar" data-w="<?= $pct_pass_first; ?>" style="width:0;background:var(--sd-good)"></span>
						<span class="sd-js-bar" data-w="<?= $pct_pass_later; ?>" style="width:0;background:var(--sd-series-1)"></span>
						<span class="sd-js-bar" data-w="<?= $pct_fail; ?>" style="width:0;background:var(--sd-critical)"></span>
					</div>
					<ul class="sd-legend">
						<li>
							<span class="sd-dot" style="background:var(--sd-good)"><i class="bx bx-check-double"></i></span>
							<span class="sd-l-name"><?= sd_t('pass_first'); ?></span>
							<span class="sd-l-count"><?= sd_num($total_pass_first); ?></span>
							<span class="sd-l-pct"><?= sd_num($pct_pass_first, 1); ?>%</span>
						</li>
						<li>
							<span class="sd-dot" style="background:var(--sd-series-1)"><i class="bx bx-redo"></i></span>
							<span class="sd-l-name"><?= sd_t('pass_later'); ?></span>
							<span class="sd-l-count"><?= sd_num($total_pass_later); ?></span>
							<span class="sd-l-pct"><?= sd_num($pct_pass_later, 1); ?>%</span>
						</li>
						<li>
							<span class="sd-dot" style="background:var(--sd-critical)"><i class="bx bx-x"></i></span>
							<span class="sd-l-name"><?= sd_t('failed'); ?></span>
							<span class="sd-l-count"><?= sd_num($total_failures); ?></span>
							<span class="sd-l-pct"><?= sd_num($pct_fail, 1); ?>%</span>
						</li>
					</ul>
				</div>
			</div>

			<div class="sd-card">
				<div class="sd-card-head">
					<div>
						<h5 class="sd-card-title"><span class="sd-icon-chip"><i class="bx bx-user-plus"></i></span><?= sd_t('emp_reg'); ?></h5>
						<p class="sd-card-sub"><?= sd_t('emp_reg_sub'); ?></p>
					</div>
				</div>
				<div class="sd-card-body sd-gauge-wrap">
					<div id="sdRegGauge" style="width:100%;max-width:280px;min-height:210px"></div>
					<div class="sd-gauge-meta">
						<div><b><?= sd_num($registered_users); ?></b><span><?= sd_t('registered'); ?></span></div>
						<div><b><?= sd_num(max(0, $total_employees - $registered_users)); ?></b><span><?= sd_t('not_registered'); ?></span></div>
					</div>
				</div>
			</div>
		</section>

		<!-- ===== Daily activity ===== -->
		<section class="sd-card">
			<div class="sd-card-head">
				<div>
					<h5 class="sd-card-title"><span class="sd-icon-chip"><i class="bx bx-line-chart"></i></span><span id="sdActivityTitle"><?= sd_t('act_reg_title'); ?></span></h5>
					<p class="sd-card-sub"><?= sd_t('act_sub'); ?></p>
				</div>
				<div class="sd-controls">
					<div class="sd-seg" role="tablist" aria-label="<?= sd_t('act_type'); ?>">
						<button type="button" class="is-active" data-sd-metric="registrations" role="tab" aria-selected="true"><?= sd_t('registrations'); ?></button>
						<button type="button" data-sd-metric="enrollments" role="tab" aria-selected="false"><?= sd_t('enrollments'); ?></button>
					</div>
					<select id="sdMonth" class="sd-select" aria-label="<?= sd_t('month'); ?>">
						<?php for ($m = 1; $m <= 12; $m++) {
							$mv = str_pad($m, 2, '0', STR_PAD_LEFT); ?>
							<option value="<?= $mv; ?>" <?= $mv == $currentMonth ? 'selected' : ''; ?>><?= sd_t('m' . $m); ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="sd-card-body">
				<div class="sd-summary">
					<div><span><?= sd_t('total_month'); ?></span><b id="sdActTotal">–</b></div>
					<div><span><?= sd_t('busiest_day'); ?></span><b id="sdActPeak">–</b></div>
					<div><span><?= sd_t('active_days'); ?></span><b id="sdActDays">–</b></div>
				</div>
				<div class="sd-chart" style="min-height:320px">
					<div id="sdActivityChart"></div>
					<div class="sd-state" id="sdActivityState">
						<div class="sd-spinner"></div>
					</div>
				</div>
			</div>
		</section>

		<!-- ===== Course activity + Ratings ===== -->
		<section class="sd-row-2">
			<div class="sd-card">
				<div class="sd-card-head">
					<div>
						<h5 class="sd-card-title"><span class="sd-icon-chip"><i class="bx bx-library"></i></span><?= sd_t('course_activity'); ?></h5>
						<p class="sd-card-sub"><?= sd_t('course_activity_sub'); ?></p>
					</div>
					<div class="sd-chart-legend">
						<span><i style="background:var(--sd-series-1)"></i><?= sd_t('read_content'); ?></span>
						<span><i style="background:var(--sd-series-2)"></i><?= sd_t('took_exam'); ?></span>
					</div>
				</div>
				<div class="sd-card-body">
					<div class="sd-chart">
						<div id="sdCourseChart"></div>
						<div class="sd-state" id="sdCourseState">
							<div class="sd-spinner"></div>
						</div>
					</div>
				</div>
			</div>

			<div class="sd-card">
				<div class="sd-card-head">
					<div>
						<h5 class="sd-card-title"><span class="sd-icon-chip"><i class="bx bx-star"></i></span><?= sd_t('course_ratings'); ?></h5>
						<p class="sd-card-sub"><?= sd_t('course_ratings_sub'); ?></p>
					</div>
				</div>
				<div class="sd-card-body">
					<div class="sd-chart">
						<div id="sdRatingChart"></div>
						<div class="sd-state" id="sdRatingState">
							<div class="sd-spinner"></div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- ===== Tables ===== -->
		<section class="sd-row-2">
			<div class="sd-card">
				<div class="sd-card-head">
					<div>
						<h5 class="sd-card-title"><span class="sd-icon-chip"><i class="bx bx-trophy"></i></span><?= sd_t('course_progress'); ?></h5>
						<p class="sd-card-sub"><?= sd_t('course_progress_sub'); ?></p>
					</div>
					<a class="sd-link" href="<?= base_url(); ?>back_office/user_content"><?= sd_t('view_all'); ?> <i class="bx <?= $sd_chev; ?>"></i></a>
				</div>
				<div class="sd-card-body">
					<div class="sd-table-wrap">
						<table class="sd-table">
							<thead>
								<tr>
									<th><?= sd_t('learner'); ?></th>
									<th><?= sd_t('course'); ?></th>
									<th><?= sd_t('completion'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if (empty($data_content)) { ?>
									<tr class="sd-empty-row">
										<td colspan="3"><?= sd_t('no_course_activity'); ?>.</td>
									</tr>
								<?php } ?>
								<?php foreach ((array) $data_content as $i => $row) {
									$pct = isset($row['completion_percentage']) ? (float) $row['completion_percentage'] : 0;
									$pct = max(0, min(100, $pct)); ?>
									<tr>
										<td>
											<div class="sd-person">
												<span class="sd-avatar a<?= $i % 5; ?>"><?= sd_e(sd_initials($row['name'])); ?></span>
												<span class="sd-name"><?= sd_e($row['name']); ?></span>
											</div>
										</td>
										<td>
											<div class="sd-course-cell" title="<?= sd_e($row['coursetitle']); ?>"><?= sd_e($row['coursetitle']); ?></div>
										</td>
										<td>
											<div class="sd-progress">
												<div class="sd-progress-track">
													<div class="sd-progress-bar<?= $pct >= 100 ? ' is-done' : ''; ?>" style="width:<?= $pct; ?>%"></div>
												</div>
												<b><?= sd_num($pct, $pct == floor($pct) ? 0 : 1); ?>%</b>
											</div>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="sd-card">
				<div class="sd-card-head">
					<div>
						<h5 class="sd-card-title"><span class="sd-icon-chip"><i class="bx bx-history"></i></span><?= sd_t('recent_exams'); ?></h5>
						<p class="sd-card-sub"><?= sd_t('recent_exams_sub'); ?></p>
					</div>
					<a class="sd-link" href="<?= base_url(); ?>back_office/exam_history"><?= sd_t('view_all'); ?> <i class="bx <?= $sd_chev; ?>"></i></a>
				</div>
				<div class="sd-card-body">
					<div class="sd-table-wrap">
						<table class="sd-table">
							<thead>
								<tr>
									<th><?= sd_t('learner'); ?></th>
									<th><?= sd_t('grade'); ?></th>
									<th><?= sd_t('date'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if (empty($data_exam)) { ?>
									<tr class="sd-empty-row">
										<td colspan="3"><?= sd_t('no_exams'); ?>.</td>
									</tr>
								<?php } ?>
								<?php foreach ((array) $data_exam as $i => $row) { ?>
									<tr>
										<td>
											<div class="sd-person">
												<span class="sd-avatar a<?= ($i + 2) % 5; ?>"><?= sd_e(sd_initials($row['name'])); ?></span>
												<div style="min-width:0">
													<div class="sd-name"><?= sd_e($row['name']); ?></div>
													<div class="sd-date sd-course-cell" style="max-width:200px" title="<?= sd_e($row['course']); ?>"><?= sd_e($row['course']); ?></div>
												</div>
											</div>
										</td>
										<td><span class="sd-grade"><?= sd_e($row['grade']); ?></span></td>
										<td><span class="sd-date"><?= str_replace(' ', ' &middot; ', sd_e($row['created_date'])); ?></span></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</section>

	</div>
</div>

<!-- Course details modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="detailsModalLabel"><?= sd_t('course_details'); ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= sd_t('close'); ?>"></button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal"><?= sd_t('close'); ?></button>
			</div>
		</div>
	</div>
</div>

<script>
	(function($) {
		var BASE = "<?= base_url(); ?>";
		var root = document.querySelector('.sd-dash');
		var RTL = !!window.BO_RTL;

		/* Dictionary text (key without the dash_st_ prefix) from window.BO_I18N; {placeholders} replaced from r. */
		function T(k, r) {
			var s = (window.BO_I18N && BO_I18N['dash_st_' + k]) || k;
			if (r) Object.keys(r).forEach(function(p) {
				s = s.split('{' + p + '}').join(r[p]);
			});
			return s;
		}

		function num(v) {
			return Number(v).toLocaleString('en-US');
		}

		function monthName(m, short) {
			return T((short ? 'mon' : 'm') + parseInt(m, 10));
		}

		function tok(name) {
			return getComputedStyle(root).getPropertyValue(name).trim();
		}

		function isDark() {
			return document.documentElement.classList.contains('dark-style');
		}

		function esc(s) {
			return String(s == null ? '' : s).replace(/[&<>"']/g, function(c) {
				return {
					'&': '&amp;',
					'<': '&lt;',
					'>': '&gt;',
					'"': '&quot;',
					"'": '&#39;'
				} [c];
			});
		}

		function setState(id, kind, text) {
			var el = document.getElementById(id);
			if (!el) return;
			if (kind === 'hide') {
				el.style.display = 'none';
				return;
			}
			el.style.display = 'flex';
			el.innerHTML = kind === 'loading' ?
				'<div class="sd-spinner"></div>' :
				'<i class="bx ' + (kind === 'error' ? 'bx-error-circle' : 'bx-bar-chart-square') + '"></i><span>' + esc(text) + '</span>';
		}

		function base(extra) {
			var o = {
				chart: {
					toolbar: {
						show: false
					},
					fontFamily: 'inherit',
					foreColor: tok('--sd-muted'),
					animations: {
						enabled: true,
						speed: 450
					},
					zoom: {
						enabled: false
					}
				},
				grid: {
					borderColor: tok('--sd-grid'),
					strokeDashArray: 0,
					xaxis: {
						lines: {
							show: false
						}
					},
					yaxis: {
						lines: {
							show: true
						}
					},
					padding: {
						left: 8,
						right: 8
					}
				},
				dataLabels: {
					enabled: false
				},
				legend: {
					show: false
				},
				tooltip: {
					theme: isDark() ? 'dark' : 'light'
				},
				states: {
					hover: {
						filter: {
							type: 'darken',
							value: 0.88
						}
					}
				}
			};
			return $.extend(true, o, extra || {});
		}

		/* ---------- Exam stacked bar ---------- */
		requestAnimationFrame(function() {
			document.querySelectorAll('.sd-js-bar').forEach(function(b) {
				var w = parseFloat(b.getAttribute('data-w')) || 0;
				b.style.width = w + '%';
				if (w <= 0) b.style.display = 'none';
			});
		});

		/* ---------- Registration gauge ---------- */
		(function() {
			var el = document.getElementById('sdRegGauge');
			if (!el || typeof ApexCharts === 'undefined') return;
			new ApexCharts(el, {
				chart: {
					type: 'radialBar',
					height: 250,
					fontFamily: 'inherit',
					sparkline: {
						enabled: true
					}
				},
				series: [<?= round($percentage_registered, 1); ?>],
				labels: [T('of_employees')],
				colors: [tok('--sd-series-1')],
				plotOptions: {
					radialBar: {
						startAngle: -120,
						endAngle: 120,
						hollow: {
							size: '64%'
						},
						track: {
							background: tok('--sd-grid'),
							strokeWidth: '100%'
						},
						dataLabels: {
							name: {
								offsetY: 26,
								color: tok('--sd-muted'),
								fontSize: '13px',
								fontWeight: 500
							},
							value: {
								offsetY: -12,
								color: tok('--sd-text'),
								fontSize: '30px',
								fontWeight: 700,
								formatter: function(v) {
									return parseFloat(v).toFixed(1).replace(/\.0$/, '') + '%';
								}
							}
						}
					}
				},
				stroke: {
					lineCap: 'round'
				}
			}).render();
		})();

		/* ---------- Daily activity (registrations / enrollments) ---------- */
		var activity = {
			metric: 'registrations',
			chart: null,
			req: null,
			cfg: {
				registrations: {
					url: 'back_office/user/get_population_data',
					x: 'created_day',
					y: 'total_exams',
					title: T('act_reg_title'),
					label: T('registrations'),
					empty: 'no_registrations'
				},
				enrollments: {
					url: 'back_office/user/get_enrollment_user_vy_day',
					x: 'enrollment_date',
					y: 'total_enrollments',
					title: T('act_enr_title'),
					label: T('enrollments'),
					empty: 'no_enrollments'
				}
			}
		};

		function fmtDay(d) {
			var p = String(d).split('-');
			if (p.length !== 3) return d;
			return parseInt(p[2], 10) + ' ' + monthName(p[1], true);
		}

		function loadActivity() {
			var c = activity.cfg[activity.metric];
			var month = $('#sdMonth').val();
			$('#sdActivityTitle').text(c.title);
			setState('sdActivityState', 'loading');
			if (activity.req) activity.req.abort();
			activity.req = $.ajax({
				url: BASE + c.url,
				method: 'GET',
				dataType: 'json',
				data: {
					month: month,
					year: new Date().getFullYear()
				}
			}).done(function(res) {
				var rows = (res && res.data) ? res.data.slice() : [];
				rows = rows.map(function(r) {
					return {
						x: r[c.x],
						y: parseFloat(r[c.y]) || 0
					};
				}).sort(function(a, b) {
					return new Date(a.x) - new Date(b.x);
				});

				var total = 0,
					peak = null;
				rows.forEach(function(r) {
					total += r.y;
					if (!peak || r.y > peak.y) peak = r;
				});
				$('#sdActTotal').text(num(total));
				$('#sdActPeak').text(peak ? fmtDay(peak.x) + ' (' + num(peak.y) + ')' : '–');
				$('#sdActDays').text(rows.length);
				// RTL: time runs right-to-left, so the chart shows the days in reverse order
				var shown = RTL ? rows.slice().reverse() : rows;

				if (activity.chart) {
					activity.chart.destroy();
					activity.chart = null;
				}
				if (!rows.length) {
					setState('sdActivityState', 'empty', T(c.empty, {
						month: monthName(month)
					}));
					return;
				}
				setState('sdActivityState', 'hide');
				activity.chart = new ApexCharts(document.getElementById('sdActivityChart'), base({
					chart: {
						type: 'bar',
						height: 320
					},
					series: [{
						name: c.label,
						data: shown.map(function(r) {
							return r.y;
						})
					}],
					colors: [tok('--sd-series-1')],
					plotOptions: {
						bar: {
							columnWidth: rows.length > 20 ? '70%' : '45%',
							borderRadius: 4
						}
					},
					xaxis: {
						categories: shown.map(function(r) {
							return fmtDay(r.x);
						}),
						axisBorder: {
							show: false
						},
						axisTicks: {
							show: false
						},
						labels: {
							rotate: -45,
							hideOverlappingLabels: true,
							style: {
								fontSize: '11px'
							}
						},
						tooltip: {
							enabled: false
						}
					},
					yaxis: {
						opposite: RTL,
						labels: {
							formatter: function(v) {
								return Math.round(v);
							}
						},
						forceNiceScale: true,
						min: 0
					},
					tooltip: {
						y: {
							formatter: function(v) {
								return num(v);
							}
						}
					}
				}));
				activity.chart.render();
			}).fail(function(xhr, status) {
				if (status === 'abort') return;
				setState('sdActivityState', 'error', T('load_error'));
			});
		}

		$('.sd-seg button').on('click', function() {
			$('.sd-seg button').removeClass('is-active').attr('aria-selected', 'false');
			$(this).addClass('is-active').attr('aria-selected', 'true');
			activity.metric = $(this).data('sd-metric');
			loadActivity();
		});
		$('#sdMonth').on('change', loadActivity);
		loadActivity();

		/* ---------- Course activity (grouped horizontal bars) ---------- */
		var courseRows = [];

		function shortLabel(s, n) {
			s = String(s || '');
			return s.length > n ? s.slice(0, n - 1) + '…' : s;
		}

		function listChips(str) {
			var items = String(str || '').split(', ').filter(Boolean);
			if (!items.length) return '<p class="text-muted mb-3">' + esc(T('nobody')) + '</p>';
			return '<div class="sd-chip-list">' + items.map(function(u) {
				return '<span>' + esc(u) + '</span>';
			}).join('') + '</div>';
		}

		function showCourse(d) {
			$('#detailsModalLabel').text(d.course_title);
			$('#detailsModal .modal-body').html(
				'<div class="sd-modal-stats">' +
				'<div><b>' + esc(d.total_contents_read) + '</b><span>' + esc(T('m_read')) + '</span></div>' +
				'<div><b>' + esc(d.total_exam) + '</b><span>' + esc(T('m_exam')) + '</span></div>' +
				'</div>' +
				'<h6 class="mb-1">' + esc(T('read_content')) + '</h6>' + listChips(d.users_list) +
				'<h6 class="mb-1">' + esc(T('m_exam_h')) + '</h6>' + listChips(d.exam_users_list)
			);
			var m = document.getElementById('detailsModal');
			if (window.bootstrap && bootstrap.Modal) {
				bootstrap.Modal.getOrCreateInstance(m).show();
			} else {
				$(m).modal('show');
			}
		}

		$.ajax({
			url: BASE + 'back_office/user/get_contents_read_data',
			method: 'GET',
			dataType: 'json',
			data: {
				month: $('#sdMonth').val(),
				year: new Date().getFullYear()
			}
		}).done(function(res) {
			courseRows = (res && res.data) ? res.data : [];
			if (!courseRows.length) {
				setState('sdCourseState', 'empty', T('no_course_activity'));
				return;
			}
			setState('sdCourseState', 'hide');
			new ApexCharts(document.getElementById('sdCourseChart'), base({
				chart: {
					type: 'bar',
					height: Math.max(300, courseRows.length * 52 + 40),
					events: {
						dataPointSelection: function(e, ctx, cfg) {
							var d = courseRows[cfg.dataPointIndex];
							if (d) showCourse(d);
						}
					}
				},
				series: [{
					name: T('read_content'),
					data: courseRows.map(function(r) {
						return parseInt(r.total_contents_read, 10) || 0;
					})
				}, {
					name: T('took_exam'),
					data: courseRows.map(function(r) {
						return parseInt(r.total_exam, 10) || 0;
					})
				}],
				colors: [tok('--sd-series-1'), tok('--sd-series-2')],
				plotOptions: {
					bar: {
						horizontal: true,
						barHeight: '62%',
						borderRadius: 4,
						dataLabels: {
							position: RTL ? 'bottom' : 'top'
						}
					}
				},
				stroke: {
					show: true,
					width: 2,
					colors: [tok('--sd-surface')]
				},
				dataLabels: {
					enabled: true,
					offsetX: RTL ? -18 : 18,
					style: {
						fontSize: '11px',
						fontWeight: 600,
						colors: [tok('--sd-text-2')]
					}
				},
				grid: {
					xaxis: {
						lines: {
							show: true
						}
					},
					yaxis: {
						lines: {
							show: false
						}
					}
				},
				xaxis: {
					categories: courseRows.map(function(r) {
						return r.course_title;
					}),
					axisBorder: {
						show: false
					},
					axisTicks: {
						show: false
					},
					labels: {
						formatter: function(v) {
							return Math.round(v);
						}
					}
				},
				yaxis: {
					opposite: RTL,
					reversed: RTL,
					labels: {
						maxWidth: 190,
						style: {
							colors: tok('--sd-text-2'),
							fontSize: '12px'
						},
						formatter: function(v) {
							return shortLabel(v, 28);
						}
					}
				},
				tooltip: {
					shared: true,
					intersect: false,
					x: {
						formatter: function(v, o) {
							var d = courseRows[o.dataPointIndex];
							return d ? d.course_title : v;
						}
					},
					y: {
						formatter: function(v) {
							return T('learners_n', {
								n: num(v)
							});
						}
					}
				}
			})).render();
		}).fail(function() {
			setState('sdCourseState', 'error', T('load_error'));
		});

		/* ---------- Course ratings ---------- */
		$.ajax({
			url: BASE + 'back_office/user/get_rating',
			method: 'GET',
			dataType: 'json'
		}).done(function(res) {
			var rows = ((res && res.data) || []).map(function(r) {
				return {
					t: r.country,
					v: r.area === null ? null : Math.round(parseFloat(r.area) * 10) / 10
				};
			}).sort(function(a, b) {
				return (b.v || 0) - (a.v || 0);
			});
			if (!rows.length) {
				setState('sdRatingState', 'empty', T('no_top_courses'));
				return;
			}
			setState('sdRatingState', 'hide');
			new ApexCharts(document.getElementById('sdRatingChart'), base({
				chart: {
					type: 'bar',
					height: Math.max(300, rows.length * 40 + 40)
				},
				series: [{
					name: T('avg_rating'),
					data: rows.map(function(r) {
						return r.v || 0;
					})
				}],
				colors: [tok('--sd-series-1')],
				plotOptions: {
					bar: {
						horizontal: true,
						barHeight: '55%',
						borderRadius: 4,
						dataLabels: {
							position: RTL ? 'bottom' : 'top'
						}
					}
				},
				dataLabels: {
					enabled: true,
					offsetX: RTL ? -26 : 26,
					formatter: function(v, o) {
						return rows[o.dataPointIndex].v === null ? T('no_ratings') : v.toFixed(1) + ' ★';
					},
					style: {
						fontSize: '11px',
						fontWeight: 600,
						colors: [tok('--sd-text-2')]
					}
				},
				grid: {
					xaxis: {
						lines: {
							show: true
						}
					},
					yaxis: {
						lines: {
							show: false
						}
					},
					padding: RTL ? {
						left: 40
					} : {
						right: 40
					}
				},
				xaxis: {
					categories: rows.map(function(r) {
						return r.t;
					}),
					min: 0,
					max: 6,
					tickAmount: 6,
					axisBorder: {
						show: false
					},
					axisTicks: {
						show: false
					},
					labels: {
						formatter: function(v) {
							v = parseFloat(v);
							var r = Math.round(v);
							return (Math.abs(v - r) < 0.01 && r <= 5) ? r : '';
						}
					}
				},
				yaxis: {
					opposite: RTL,
					reversed: RTL,
					labels: {
						maxWidth: 170,
						style: {
							colors: tok('--sd-text-2'),
							fontSize: '12px'
						},
						formatter: function(v) {
							return shortLabel(v, 24);
						}
					}
				},
				tooltip: {
					x: {
						formatter: function(v, o) {
							return rows[o.dataPointIndex].t;
						}
					},
					y: {
						formatter: function(v, o) {
							return rows[o.dataPointIndex].v === null ? T('no_ratings_yet') : v.toFixed(1) + ' / 5';
						}
					}
				}
			})).render();
		}).fail(function() {
			setState('sdRatingState', 'error', T('load_error'));
		});
	})(jQuery);
</script>
