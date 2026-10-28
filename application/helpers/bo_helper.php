<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Back-office helper: language, dictionary lookups, sidebar map, privileges.
 * Autoloaded via application/config/autoload.php ('bo').
 */

/** Load the dictionary file once. */
function bo_dict_data()
{
    static $data = null;
    if ($data === null) {
        $BO_DICT = array('en' => array(), 'ar' => array());
        $BO_MENU = array();
        $BO_FIELDS = array();
        $BO_MENU_ALIAS = array();
        $BO_LEGACY = array();
        include APPPATH . 'language/bo_dictionary.php';
        $data = array('dict' => $BO_DICT, 'menu' => $BO_MENU, 'fields' => $BO_FIELDS, 'alias' => $BO_MENU_ALIAS, 'legacy' => $BO_LEGACY);
    }
    return $data;
}

/** Current admin language: session -> cookie -> 'en'. */
function bo_lang()
{
    static $lang = null;
    if ($lang !== null) return $lang;
    $l = '';
    $CI =& get_instance();
    if (isset($CI->session) && is_object($CI->session)) {
        $l = (string) $CI->session->userdata('bo_lang');
    }
    if ($l === '' && isset($_COOKIE['bo_lang'])) {
        $l = (string) $_COOKIE['bo_lang'];
    }
    if ($l !== 'ar' && $l !== 'en') $l = 'en'; // English by default
    $lang = $l;
    return $lang;
}

function bo_rtl()
{
    return bo_lang() === 'ar';
}

/** 'pt-ar' when RTL (practice views), '' otherwise. */
function bo_dir_class()
{
    return bo_rtl() ? 'pt-ar' : '';
}

/** Translate a key; falls back to English, then to $fallback or the key itself. */
function bo_t($key, $fallback = null)
{
    $d = bo_dict_data();
    $lang = bo_lang();
    if (isset($d['dict'][$lang][$key])) return $d['dict'][$lang][$key];
    if (isset($d['dict']['en'][$key])) return $d['dict']['en'][$key];
    return ($fallback !== null && $fallback !== '') ? $fallback : $key;
}

/** Caption of a table field: f_<field>, then without the id_ prefix, then "Ucfirst words". */
function bo_label($field)
{
    $d = bo_dict_data();
    $lang = bo_lang();
    $f = strtolower(trim((string) $field));
    if (isset($d['dict'][$lang]['f_' . $f])) return $d['dict'][$lang]['f_' . $f];
    if (strpos($f, 'id_') === 0) {
        $g = substr($f, 3);
        if (isset($d['dict'][$lang]['f_' . $g])) return $d['dict'][$lang]['f_' . $g];
        // foreign key to a module: use the module label
        $m = bo_menu_entry($g);
        if ($m) return $m[$lang];
        $f = $g;
    }
    if (isset($d['dict']['en']['f_' . $f])) return $d['dict']['en']['f_' . $f];
    return ucfirst(str_replace('_', ' ', $f));
}

/** Sidebar/menu entry for a content_type name or used_name (NULL when unknown). */
function bo_menu_entry($name)
{
    $d = bo_dict_data();
    $n = strtolower(trim((string) $name));
    if (isset($d['menu'][$n])) return $d['menu'][$n];
    $s = str_replace('_', ' ', $n);
    if (isset($d['menu'][$s])) return $d['menu'][$s];
    return null;
}

/** Like bo_menu_entry(), but also resolves the legacy spellings of $BO_MENU_ALIAS ("subject", "to do"...). Returns the $BO_MENU key or NULL. */
function bo_menu_key($name)
{
    $d = bo_dict_data();
    $n = strtolower(trim(preg_replace('/\s+/', ' ', (string) $name)));
    if (isset($d['menu'][$n])) return $n;
    $s = str_replace('_', ' ', $n);
    if (isset($d['menu'][$s])) return $s;
    if (isset($d['alias'][$n])) return $d['alias'][$n];
    if (isset($d['alias'][$s])) return $d['alias'][$s];
    return null;
}

/** Current-language text of a legacy English literal ($BO_LEGACY), or NULL. Case-insensitive, keeps a trailing ":" / "*". */
function bo_legacy($text)
{
    static $map = null;
    if (bo_lang() === 'en') return null;
    if ($map === null) {
        $d = bo_dict_data();
        $map = array();
        foreach ($d['legacy'] as $en => $ar) $map[strtolower($en)] = $ar;
    }
    $t = trim(preg_replace('/\s+/u', ' ', str_replace("\xc2\xa0", ' ', (string) $text)));
    $core = trim(preg_replace('/[\s:*]+$/', '', $t));
    $k = strtolower($core);
    if ($k === '' || !isset($map[$k])) return null;
    return $map[$k] . substr($t, strlen($core));
}

/** Whole $BO_MENU map. */
function bo_menu_map()
{
    $d = bo_dict_data();
    return $d['menu'];
}

/**
 * Label of a module in the current language.
 * $kind: 'label' (default) | 'list' | 'new'
 */
function bo_module_label($name, $kind = 'label')
{
    $lang = bo_lang();
    $e = bo_menu_entry($name);
    if ($e) {
        $label = isset($e[$lang]) ? $e[$lang] : $e['en'];
        if ($kind === 'list') {
            if (isset($e['list_' . $lang])) return $e['list_' . $lang];
            return sprintf(bo_t('menu_list_fmt'), $label);
        }
        $one = isset($e['one_' . $lang]) ? $e['one_' . $lang] : $label;
        if ($kind === 'one') return $one;
        if ($kind === 'new') {
            if (isset($e['new_' . $lang])) return $e['new_' . $lang];
            return sprintf(bo_t('menu_new_fmt'), $one);
        }
        return $label;
    }
    $label = ucwords(str_replace('_', ' ', (string) $name));
    if ($kind === 'one') return $label;
    if ($kind === 'list') return sprintf(bo_t('menu_list_fmt'), $label);
    if ($kind === 'new') return sprintf(bo_t('menu_new_fmt'), $label);
    return $label;
}

/**
 * Privileges of the logged-in user on a content type: array($read, $write, $delete).
 * STED: level 0 users are checked against the priviledge table by role (id_role + id_content); others get full access.
 */
function bo_priv($id_content)
{
    $CI =& get_instance();
    if ((int) $CI->session->userdata('level') === 0) {
        $cond = array('id_role' => $CI->session->userdata('id_role'), 'id_content' => $id_content);
        $read = (int) $CI->fct->getonecell('priviledge', 'read_p', $cond);
        $write = (int) $CI->fct->getonecell('priviledge', 'write_p', $cond);
        $delete = (int) $CI->fct->getonecell('priviledge', 'delete_p', $cond);
        return array($read, $write, $delete);
    }
    return array(1, 1, 1);
}

/**
 * Subset of the dictionary exported to JavaScript as BO_I18N:
 *  - every grid_/form_/msg_/pt_/nav_ key in the current language
 *  - modules: used_name => {label, list, new}
 *  - fields: english caption (lower-cased) => current caption
 *  - legacy: english literal => current literal (empty in English) for the phase-1 translation of old views
 */
function bo_js_dict()
{
    $d = bo_dict_data();
    $lang = bo_lang();
    $out = array();
    foreach ($d['dict']['en'] as $k => $en) {
        if (preg_match('/^(grid|form|msg|pt|nav|dash)_/', $k)) {
            $out[$k] = isset($d['dict'][$lang][$k]) ? $d['dict'][$lang][$k] : $en;
        }
    }
    $out['lang'] = $lang;
    $out['rtl'] = bo_rtl();
    $mods = array();
    foreach ($d['menu'] as $name => $e) {
        $used = str_replace(' ', '_', $name);
        $mods[$used] = array(
            'name'  => $name,
            'label' => bo_module_label($name),
            'list'  => bo_module_label($name, 'list'),
            'new'   => bo_module_label($name, 'new'),
            'one'   => bo_module_label($name, 'one'),
        );
    }
    $out['modules'] = $mods;
    $alias = array();
    foreach ($d['alias'] as $a => $key) $alias[str_replace(' ', '_', $a)] = str_replace(' ', '_', $key);
    $out['alias'] = $alias;
    // field captions keyed by English caption and by field name. A bare "Subject" header means the school
    // subject (material => المادة) on the class/unit/section lists; the message pages use 'fieldnames'
    // (keyed by field name only: subject => الموضوع), see admin_legacy.js
    $fields = array();
    $fieldnames = array();
    foreach ($d['fields'] as $f => $v) {
        $fields[strtolower($v[0])] = ($lang === 'ar') ? $v[1] : $v[0];
        $fields[str_replace('_', ' ', $f)] = ($lang === 'ar') ? $v[1] : $v[0];
        $fieldnames[str_replace('_', ' ', $f)] = ($lang === 'ar') ? $v[1] : $v[0];
    }
    $out['fields'] = $fields;
    $out['fieldnames'] = $fieldnames;
    $legacy = array();
    if ($lang !== 'en') {
        foreach ($d['dict']['en'] as $k => $en) {
            if (preg_match('/^(grid|form|msg)_/', $k) && isset($d['dict'][$lang][$k])) {
                $legacy[trim($en)] = $d['dict'][$lang][$k];
            }
        }
        foreach ($d['legacy'] as $en => $ar) $legacy[$en] = $ar;
    }
    $out['legacy'] = $legacy;
    return $out;
}

/**
 * Translate a controller page title of the form "List X" / "Add X" / "Edit X" / "Add / Edit X" / "New X"
 * (X = content_type name). Unknown patterns are returned unchanged.
 */
function bo_page_title($title)
{
    $t = trim((string) $title);
    if ($t === '') return $t;
    $lg = bo_legacy($t);
    if ($lg !== null) return $lg;
    if (preg_match('/^(List|Add \/ Edit|Create New|Add New|Add|Edit|New)\s+(.+)$/i', $t, $m)) {
        $verb = strtolower($m[1]);
        $mod = trim($m[2]);
        $qual = '';
        if (preg_match('/^(All|Unread|Archived)\s+(.+)$/i', $mod, $q) && bo_menu_key($q[2]) !== null) {
            $qual = ' (' . bo_t('grid_' . strtolower($q[1])) . ')';
            $mod = $q[2];
        }
        $key = bo_menu_key($mod);
        // a title that abbreviates the current controller ("List official exams" on official_exams_init)
        $CI =& get_instance();
        $cur = (isset($CI->router) && is_object($CI->router)) ? str_replace('_', ' ', strtolower($CI->router->class)) : '';
        if ($cur !== '' && strpos($cur, strtolower($mod) . ' ') === 0 && bo_menu_key($cur) !== null) $key = bo_menu_key($cur);
        if ($key === null) {
            $lg = bo_legacy($mod);
            if ($lg === null) return $t;
            $label = $one = $lg;
        } else {
            $label = bo_module_label($key);
            $one = bo_module_label($key, 'one');
        }
        if ($qual !== '' && bo_lang() === 'en') return $t;
        if ($verb === 'list') return ($key !== null ? bo_module_label($key, 'list') : sprintf(bo_t('grid_list_x'), $label)) . $qual;
        if ($verb === 'add') return sprintf(bo_t('grid_add_x'), $one);
        if ($verb === 'edit') return sprintf(bo_t('grid_edit_x'), $one);
        if ($verb === 'new' || $verb === 'create new' || $verb === 'add new') return sprintf(bo_t('grid_new_x'), $one);
        return sprintf(bo_t('grid_add_edit_x'), $one);
    }
    $key = bo_menu_key($t);
    if ($key !== null) return bo_module_label($key);
    return $t;
}

/**
 * "Last login" value of the navbar. The session keeps the English "d M Y - h:i:s A" string (home.php);
 * it is shown as digits only (Y-m-d H:i) so it reads the same in both languages.
 */
function bo_login_date($raw)
{
    $raw = trim((string) $raw);
    if ($raw === '') return '';
    $dt = DateTime::createFromFormat('d M Y - h:i:s A', $raw);
    if ($dt) return $dt->format('Y-m-d H:i');
    $ts = strtotime($raw);
    return ($ts === false) ? $raw : date('Y-m-d H:i', $ts);
}

/* =====================================================================
 * DevExtreme grid, server mode (includes/dxgrid.php with 'ajax' => true)
 * ---------------------------------------------------------------------
 * A controller exposes:
 *     public function grid_data() {
 *         bo_grid_output(bo_grid_data($this->table, array(
 *             'id_content' => 25,                                   // privilege check (read_p), optional
 *             'columns'    => array('title', 'ip', 'id_material'), // returned/filterable columns (key + sort_order are added)
 *             'search'     => array('title', 'ip'),                // LIKE columns for searchValue (default: text columns)
 *             'lookups'    => array('id_material' => array('material', 'title', 'id_material')),
 *             'where'      => array('id_unit' => $this->session->userdata('id_unit')), // fixed conditions
 *             'order'      => 'id_visitor desc',                   // default sort
 *         )));
 *     }
 * GET parameters (sent by dxgrid.php): skip, take, requireTotalCount, requireGroupCount,
 * sort (JSON [{selector, desc}]), filter (JSON DevExtreme filter expression), group (JSON, header filter),
 * searchValue / searchExpr / searchOperation.
 * Response: {data: [...], totalCount: n, cap: 10000, capped: bool}
 *        or {data: [{key, items: null, count}], groupCount, totalCount} for a group (header filter) request.
 * Only columns returned by SHOW COLUMNS (minus 'exclude', limited to 'columns') can be selected, filtered or
 * sorted; values are escaped; lookups are resolved for the returned page only (one IN query per lookup).
 * ===================================================================== */

/**
 * TRUE for a field that must never reach the browser (grid payloads, grid_data JSON): any name containing
 * password / passwd / pwd / token / secret, or a bare "pass" segment at the end ("pass", "user_pass").
 * Data columns that merely start with "pass" (practice_test.pass_percent, practice_result.passed) are not matched.
 */
function bo_grid_sensitive($field)
{
    return (bool) preg_match('/password|passwd|pwd|token|secret|(^|_)pass$/i', (string) $field);
}

/** Columns of a table: field => kind (int|float|date|datetime|text|other). Cached per request; array() when unknown. */
function bo_table_columns($table)
{
    static $cache = array();
    $table = (string) $table;
    if (!preg_match('/^[A-Za-z0-9_]+$/', $table)) return array();
    if (!isset($cache[$table])) {
        $CI =& get_instance();
        $cols = array();
        if ($CI->db->table_exists($table)) {
            $q = $CI->db->query('SHOW COLUMNS FROM `' . $table . '`');
            foreach ($q->result_array() as $r) {
                $t = strtolower($r['Type']);
                if (preg_match('/^(tiny|small|medium|big)?int\b/', $t) || strpos($t, 'bit') === 0) $k = 'int';
                elseif (preg_match('/^(decimal|float|double|real|numeric)/', $t)) $k = 'float';
                elseif ($t === 'date') $k = 'date';
                elseif (strpos($t, 'datetime') === 0 || strpos($t, 'timestamp') === 0) $k = 'datetime';
                elseif (preg_match('/(char|text|enum|set)/', $t)) $k = 'text';
                else $k = 'other';
                $cols[$r['Field']] = $k;
            }
        }
        $cache[$table] = $cols;
    }
    return $cache[$table];
}

/** One grid request parameter: from $params when it is an array, else GET, then POST. $json decodes JSON strings. */
function bo_grid_param($params, $name, $json = false)
{
    if (is_array($params)) {
        $v = isset($params[$name]) ? $params[$name] : null;
    } else {
        $v = isset($_GET[$name]) ? $_GET[$name] : (isset($_POST[$name]) ? $_POST[$name] : null);
    }
    if ($json && is_string($v)) {
        $d = json_decode($v, true);
        return ($d === null && trim($v) !== 'null') ? $v : $d;
    }
    return $v;
}

/** A DB value formatted for the JSON response according to its column kind (dates as ISO strings). */
function bo_grid_value($v, $kind)
{
    if ($v === null) return null;
    switch ($kind) {
        case 'int':      return is_numeric($v) ? (int) $v : $v;
        case 'float':    return is_numeric($v) ? (float) $v : $v;
        case 'date':     return ($v === '' || strpos($v, '0000-00-00') === 0) ? null : substr($v, 0, 10);
        case 'datetime': return ($v === '' || strpos($v, '0000-00-00') === 0) ? null : str_replace(' ', 'T', substr($v, 0, 19));
    }
    return $v;
}

/** Thrown by the grid helpers for a malformed request (bo_grid_data answers HTTP 400). */
class BoGridBadRequest extends Exception {}

/**
 * A filter value normalised for a column kind (dates arrive as "yyyy-MM-ddTHH:mm:ss[.fff][Z|+hh:mm]").
 * Date/datetime values must be real calendar dates (regex + checkdate, time 00:00:00-23:59:59), otherwise
 * BoGridBadRequest is thrown: MySQL 8 rejects an invalid DATE literal in a comparison with an error.
 */
function bo_grid_norm($v, $kind)
{
    if (is_bool($v)) return $v ? '1' : '0';
    if ($v === null) return null;
    if (is_array($v) || is_object($v)) throw new BoGridBadRequest('value');
    $v = (string) $v;
    if (($kind === 'date' || $kind === 'datetime') && trim($v) !== '') {
        if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})(?:[T ](\d{2}):(\d{2})(?::(\d{2})(?:\.\d{1,6})?)?)?(?:Z|[+-]\d{2}:?\d{2})?$/', trim($v), $m)
            || !checkdate((int) $m[2], (int) $m[3], (int) $m[1]) || (int) $m[1] < 1000
            || (isset($m[4]) && $m[4] !== '' && ((int) $m[4] > 23 || (int) $m[5] > 59 || (isset($m[6]) && $m[6] !== '' && (int) $m[6] > 59)))) {
            throw new BoGridBadRequest('date');
        }
        $d = $m[1] . '-' . $m[2] . '-' . $m[3];
        if ($kind === 'date') return $d;
        $tm = (isset($m[4]) && $m[4] !== '') ? $m[4] . ':' . $m[5] . ':' . ((isset($m[6]) && $m[6] !== '') ? $m[6] : '00') : '00:00:00';
        return $d . ' ' . $tm;
    }
    return $v;
}

/** SQL for one [field, op, value] condition; NULL when the field or operator is not allowed. */
function bo_grid_cond($field, $op, $value, &$ctx)
{
    $CI =& get_instance();
    $db = $CI->db;
    if (!is_string($field)) return null;
    $op = strtolower(trim((string) $op));
    if ($op === '==') $op = '=';
    if ($op === '!=') $op = '<>';
    $neg = in_array($op, array('<>', 'notcontains', 'noneof'), true);

    // lookup display column (<fk>_text) -> IN / NOT IN on the foreign key
    if (isset($ctx['lookups'][$field])) {
        $lk = $ctx['lookups'][$field];
        $fk = '`' . $ctx['table'] . '`.`' . $lk['fk'] . '`';
        if ($value === null || $value === '' || (is_array($value) && !count($value))) {
            if (in_array($op, array('contains', 'notcontains', 'startswith', 'endswith'), true)) return null;
            $blank = '(' . $fk . ' IS NULL OR ' . $fk . ' = 0)';
            return $neg ? '(NOT ' . $blank . ')' : $blank;
        }
        if (!is_array($value) && !in_array($op, array('=', '<>', 'contains', 'notcontains', 'startswith', 'endswith'), true)) return null;
        $db->select($lk['pk'])->from($lk['table']);
        if (is_array($value)) {
            $db->where_in($lk['display'], array_map('strval', $value));
        } else {
            $s = (string) $value;
            if ($op === 'contains' || $op === 'notcontains') $db->like($lk['display'], $s, 'both');
            elseif ($op === 'startswith') $db->like($lk['display'], $s, 'after');
            elseif ($op === 'endswith') $db->like($lk['display'], $s, 'before');
            else $db->where($lk['display'], $s);
        }
        $db->limit(2000);
        $ids = array();
        foreach ($db->get()->result_array() as $r) $ids[(int) $r[$lk['pk']]] = 1;
        if (!count($ids)) return $neg ? null : '(1 = 0)';
        // positive conditions carry "IS NOT NULL" so that ["!", cond] (header filter "exclude") keeps the NULL rows
        $in = $fk . ' IN (' . implode(',', array_keys($ids)) . ')';
        return $neg ? '(' . $fk . ' IS NULL OR NOT ' . $in . ')' : '(' . $in . ' AND ' . $fk . ' IS NOT NULL)';
    }

    if (!isset($ctx['cols'][$field])) return null;
    $kind = $ctx['kinds'][$field];
    $c = '`' . $ctx['table'] . '`.`' . $field . '`';

    if ($op === 'anyof' || $op === 'noneof') {
        $vals = array();
        foreach ((array) $value as $v) $vals[] = $db->escape((string) bo_grid_norm($v, $kind));
        if (!count($vals)) return null;
        $in = $c . ' IN (' . implode(',', $vals) . ')';
        return $op === 'anyof' ? '(' . $in . ' AND ' . $c . ' IS NOT NULL)' : '(' . $c . ' IS NULL OR NOT ' . $in . ')';
    }
    if (is_array($value)) return null;
    $like = in_array($op, array('contains', 'notcontains', 'startswith', 'endswith'), true);
    // LIKE on a date column compares text: no date validation; every other operator gets a validated date
    $v = ($like && ($kind === 'date' || $kind === 'datetime')) ? (is_scalar($value) ? (string) $value : null) : bo_grid_norm($value, $kind);

    switch ($op) {
        case '=':
            if ($v === null || $v === '') return '(' . $c . ' IS NULL' . ($kind === 'text' ? ' OR ' . $c . " = ''" : '') . ')';
            if ($kind === 'datetime' && substr($v, 11) === '00:00:00') return '(DATE(' . $c . ') = ' . $db->escape(substr($v, 0, 10)) . ' AND ' . $c . ' IS NOT NULL)';
            return '(' . $c . ' = ' . $db->escape($v) . ' AND ' . $c . ' IS NOT NULL)';
        case '<>':
            if ($v === null || $v === '') return '(' . $c . ' IS NOT NULL' . ($kind === 'text' ? ' AND ' . $c . " <> ''" : '') . ')';
            return '(' . $c . ' IS NULL OR ' . $c . ' <> ' . $db->escape($v) . ')';
        case '<': case '>': case '<=': case '>=':
            if ($v === null || $v === '') return null;
            return '(' . $c . ' ' . $op . ' ' . $db->escape($v) . ' AND ' . $c . ' IS NOT NULL)';
        case 'contains': case 'notcontains': case 'startswith': case 'endswith':
            if ($v === null || $v === '') return null;
            $l = $db->escape_like_str($v);
            $pat = ($op === 'startswith') ? $l . '%' : (($op === 'endswith') ? '%' . $l : '%' . $l . '%');
            if ($op === 'notcontains') return '(' . $c . ' IS NULL OR ' . $c . " NOT LIKE '" . $pat . "')";
            return '(' . $c . " LIKE '" . $pat . "' AND " . $c . ' IS NOT NULL)';
    }
    return null;
}

/** SQL for a DevExtreme filter expression (nested arrays, "and"/"or", ["!", expr]); NULL when nothing applies. */
function bo_grid_filter_sql($f, &$ctx, $depth = 0)
{
    if (!is_array($f) || !count($f) || $depth > 25) return null;
    $f = array_values($f);
    if ($f[0] === '!' && count($f) === 2) {
        $s = bo_grid_filter_sql($f[1], $ctx, $depth + 1);
        return $s === null ? null : '(NOT ' . $s . ')';
    }
    if (is_string($f[0]) && !in_array(strtolower($f[0]), array('and', 'or'), true)) {
        if (count($f) === 2 && !is_array($f[1])) return bo_grid_cond($f[0], '=', $f[1], $ctx);
        if (count($f) === 3 && is_string($f[1])) return bo_grid_cond($f[0], $f[1], $f[2], $ctx);
        return null;
    }
    $out = '';
    $join = 'AND';
    foreach ($f as $item) {
        if (is_string($item)) {
            $l = strtoupper($item);
            if ($l === 'AND' || $l === 'OR') $join = $l;
            continue;
        }
        $s = bo_grid_filter_sql($item, $ctx, $depth + 1);
        if ($s === null) continue;
        $out = ($out === '') ? $s : $out . ' ' . $join . ' ' . $s;
        $join = 'AND';
    }
    return $out === '' ? null : '(' . $out . ')';
}

/**
 * Server side of the DevExtreme grid (ajax mode). Returns an array for bo_grid_output().
 * $opts:
 *   key        primary key (default id_<table>)
 *   columns    fields returned/filterable/sortable (default: all columns); key + sort_order always added
 *   exclude    fields never returned (e.g. array('password'))
 *   search     columns searched with LIKE for searchValue (default: the text columns of 'columns')
 *   where      fixed AR conditions, e.g. array('id_unit' => 5, 'status >' => 0)  (bare column names are table-qualified)
 *   where_in   array(field => array(values))  (an empty array matches nothing)
 *   lookups    array(fk => array(table, display_field, pk)) -> adds "<fk>_text" (searchable, sortable, header filter)
 *   order      default sort when the grid sends none: 'field desc, field2' or array(field => 'asc'|'desc')
 *              (default sort_order asc when the column exists); the key is always the last tie-breaker
 *   deleted    true (default): adds deleted = 0 when the table has that column
 *   id_content content_type id: 403 unless the user has read_p (bo_priv)
 *   max_take   rows per request cap, also the export cap (default 10000)
 *   row        callable($row) => $row applied to every returned row (computed / raw HTML columns)
 *   params     array of request parameters instead of $_GET/$_POST (tests)
 * Always: bo_grid_sensitive() columns (password, token, secret...) are never selected or returned.
 * Errors: a malformed date/datetime filter value (regex + checkdate) => _status 400 {error: "bad_request"};
 * any DB failure (mysqli exception, or a failed query with db_debug temporarily off) => _status 400 {error: "query"},
 * logged with log_message('error'). bo_grid_output() sends the status, so the user never sees an HTML error page.
 */
function bo_grid_data($table, $opts = array())
{
    $CI =& get_instance();
    $db = $CI->db;
    // CI's db_debug would print an HTML error page and exit: errors are turned into JSON instead
    $debug = $db->db_debug;
    $db->db_debug = FALSE;
    try {
        $res = bo_grid_data_run($table, $opts);
    } catch (BoGridBadRequest $e) {
        $res = array('_status' => 400, 'error' => 'bad_request', 'detail' => $e->getMessage(), 'data' => array(), 'totalCount' => 0);
    } catch (Throwable $e) {   // mysqli_sql_exception and any other failure: never shown to the user
        log_message('error', 'bo_grid_data(' . $table . '): ' . get_class($e) . ': ' . $e->getMessage());
        $res = array('_status' => 400, 'error' => 'query', 'data' => array(), 'totalCount' => 0);
    }
    if (isset($res['_status']) && (int) $res['_status'] >= 400 && method_exists($db, '_reset_select')) $db->_reset_select();
    $db->db_debug = $debug;
    return $res;
}

/** Body of bo_grid_data(); a failed query (db_debug off) throws, so the caller can answer HTTP 400. */
function bo_grid_data_run($table, $opts)
{
    $CI =& get_instance();
    $db = $CI->db;
    $o = array_merge(array(
        'key' => 'id_' . $table, 'columns' => null, 'exclude' => array(), 'search' => null,
        'where' => array(), 'where_in' => array(), 'lookups' => array(), 'order' => null,
        'deleted' => true, 'id_content' => null, 'max_take' => 10000, 'row' => null, 'params' => null,
    ), $opts);

    if (!$CI->session->userdata('user_id')) return array('_status' => 401, 'error' => 'auth', 'data' => array(), 'totalCount' => 0);
    if ($o['id_content'] !== null && $o['id_content'] !== '') {
        list($read) = bo_priv($o['id_content']);
        if ((int) $read !== 1) return array('_status' => 403, 'error' => 'forbidden', 'data' => array(), 'totalCount' => 0);
    }
    $all = bo_table_columns($table);
    if (!count($all)) return array('_status' => 500, 'error' => 'table', 'data' => array(), 'totalCount' => 0);

    // whitelist
    $cols = is_array($o['columns']) ? array_values(array_intersect($o['columns'], array_keys($all))) : array_keys($all);
    $cols = array_values(array_diff($cols, (array) $o['exclude']));
    $cols = array_values(array_filter($cols, function ($c) use ($o) { return $c === $o['key'] || !bo_grid_sensitive($c); }));
    foreach (array($o['key'], 'sort_order') as $must) {
        if (isset($all[$must]) && !in_array($must, $cols, true)) $cols[] = $must;
    }
    $lookups = array();
    foreach ((array) $o['lookups'] as $fk => $lk) {
        if (!isset($all[$fk]) || !is_array($lk) || count($lk) < 3) continue;
        list($lt, $ld, $lp) = array_values($lk);
        if (!preg_match('/^[A-Za-z0-9_]+$/', $lt) || !preg_match('/^[A-Za-z0-9_]+$/', $ld) || !preg_match('/^[A-Za-z0-9_]+$/', $lp)) continue;
        if (!in_array($fk, $cols, true)) $cols[] = $fk;
        $lookups[$fk . '_text'] = array('fk' => $fk, 'table' => $lt, 'display' => $ld, 'pk' => $lp);
    }
    $kinds = array();
    foreach ($cols as $c) $kinds[$c] = $all[$c];
    $ctx = array('table' => $table, 'cols' => array_flip($cols), 'kinds' => $kinds, 'lookups' => $lookups);
    $p = $o['params'];

    // filter + search -> SQL strings (lookup ids are resolved here, before the main query is built)
    $conds = array();
    $filter = bo_grid_param($p, 'filter', true);
    if (is_array($filter)) {
        $s = bo_grid_filter_sql($filter, $ctx);
        if ($s !== null) $conds[] = $s;
    }
    $sv = bo_grid_param($p, 'searchValue');
    if (is_string($sv) && trim($sv) !== '') {
        $default = array();
        if (is_array($o['search'])) $default = array_values(array_intersect($o['search'], array_merge($cols, array_keys($lookups))));
        else foreach ($cols as $c) if ($kinds[$c] === 'text') $default[] = $c;
        $se = bo_grid_param($p, 'searchExpr', true);
        if (is_string($se) && $se !== '') $se = array($se);
        $fields = (is_array($se) && count($se)) ? array_values(array_intersect($se, array_merge($default, array_keys($lookups)))) : $default;
        $sop = bo_grid_param($p, 'searchOperation');
        if (!in_array($sop, array('contains', 'startswith', 'endswith', '='), true)) $sop = 'contains';
        $parts = array();
        foreach ($fields as $i => $sf) {
            if ($i) $parts[] = 'or';
            $parts[] = array($sf, $sop, trim($sv));
        }
        $s = bo_grid_filter_sql($parts, $ctx);
        $conds[] = ($s === null) ? '(1 = 0)' : $s;
    }

    $t = $table;
    $apply = function () use ($db, $o, $t, $all, $conds) {
        $db->from($t);
        if ($o['deleted'] && isset($all['deleted'])) $db->where($t . '.deleted', 0);
        foreach ((array) $o['where'] as $k => $v) {
            $first = strtok(trim($k), ' ');
            $db->where(isset($all[$first]) ? $t . '.' . trim($k) : $k, $v);
        }
        foreach ((array) $o['where_in'] as $k => $vals) {
            $vals = array_values((array) $vals);
            if (!count($vals)) { $db->where('(1 = 0)', null, false); continue; }
            $db->where_in(isset($all[$k]) ? $t . '.' . $k : $k, $vals);
        }
        foreach ($conds as $sql) $db->where($sql, null, false);
    };

    // header-filter request: distinct values of one column (or of a lookup text)
    $group = bo_grid_param($p, 'group', true);
    if (is_array($group) && count($group)) {
        $g = array_values($group);
        $g = $g[0];
        $gsel = is_array($g) ? (isset($g['selector']) ? $g['selector'] : '') : (string) $g;
        $desc = is_array($g) && !empty($g['desc']) && $g['desc'] !== 'false';
        $out = array();
        $plain = is_string($gsel) && isset($ctx['cols'][$gsel]) && !in_array($kinds[$gsel], array('date', 'datetime'), true) && !(is_array($g) && !empty($g['groupInterval']));
        if ($plain || (is_string($gsel) && isset($lookups[$gsel]))) {
            $col = $plain ? $gsel : $lookups[$gsel]['fk'];
            $apply();
            $db->select('`' . $t . '`.`' . $col . '` AS bo_k, COUNT(*) AS bo_c', false);
            $db->group_by($t . '.' . $col);
            $db->order_by($t . '.' . $col, $desc ? 'desc' : 'asc');
            $db->limit(2000);
            $res = $db->get()->result_array();
            if (!$plain) {
                $lk = $lookups[$gsel];
                $ids = array();
                foreach ($res as $r) if ((int) $r['bo_k']) $ids[] = (int) $r['bo_k'];
                $names = array();
                if (count($ids)) {
                    $db->select($lk['pk'] . ', ' . $lk['display'])->from($lk['table'])->where_in($lk['pk'], $ids);
                    foreach ($db->get()->result_array() as $r) $names[(int) $r[$lk['pk']]] = (string) $r[$lk['display']];
                }
                $merged = array();
                foreach ($res as $r) {
                    $id = (int) $r['bo_k'];
                    $txt = ($id && isset($names[$id])) ? $names[$id] : '';
                    $merged[$txt] = (isset($merged[$txt]) ? $merged[$txt] : 0) + (int) $r['bo_c'];
                }
                if ($desc) krsort($merged, SORT_NATURAL | SORT_FLAG_CASE); else ksort($merged, SORT_NATURAL | SORT_FLAG_CASE);
                foreach ($merged as $txt => $n) $out[] = array('key' => ($txt === '' ? null : (string) $txt), 'items' => null, 'count' => $n);
            } else {
                foreach ($res as $r) $out[] = array('key' => bo_grid_value($r['bo_k'], $kinds[$col]), 'items' => null, 'count' => (int) $r['bo_c']);
            }
        }
        $total = 0;
        foreach ($out as $r) $total += $r['count'];
        $count = count($out);
        $skip = max(0, (int) bo_grid_param($p, 'skip'));
        $take = (int) bo_grid_param($p, 'take');
        if ($skip || $take > 0) $out = array_slice($out, $skip, $take > 0 ? $take : null);
        return array('data' => $out, 'groupCount' => $count, 'totalCount' => $total);
    }

    // paging (no take = "load everything", used by the exporter: capped at max_take)
    $max = max(1, (int) $o['max_take']);
    $skip = max(0, (int) bo_grid_param($p, 'skip'));
    $take = (int) bo_grid_param($p, 'take');
    $unbounded = ($take <= 0 || $take > $max);
    if ($unbounded) $take = $max;
    $rtc = strtolower((string) bo_grid_param($p, 'requireTotalCount'));
    $total = null;
    if ($rtc === 'true' || $rtc === '1' || $unbounded) {
        $apply();
        $total = (int) $db->count_all_results();
    }

    // sort: grid sort, else 'order', else sort_order; lookups sort on the joined display column
    $sorts = bo_grid_param($p, 'sort', true);
    if (is_array($sorts) && isset($sorts['selector'])) $sorts = array($sorts);
    if (!is_array($sorts) || !count($sorts)) {
        $sorts = array();
        $ord = $o['order'];
        if (is_string($ord) && trim($ord) !== '') {
            foreach (explode(',', $ord) as $part) {
                $bits = preg_split('/\s+/', trim($part));
                $sorts[] = array('selector' => $bits[0], 'desc' => isset($bits[1]) && strtolower($bits[1]) === 'desc');
            }
        } elseif (is_array($ord) && count($ord)) {
            foreach ($ord as $f => $dir) $sorts[] = array('selector' => $f, 'desc' => strtolower((string) $dir) === 'desc');
        } elseif (isset($all['sort_order'])) {
            $sorts[] = array('selector' => 'sort_order', 'desc' => false);
        }
    }
    $apply();
    $sel = array();
    foreach ($cols as $c) $sel[] = '`' . $t . '`.`' . $c . '`';
    $db->select(implode(', ', $sel), false);
    $used = array();
    $joins = 0;
    foreach ($sorts as $s) {
        $ss = is_array($s) ? (isset($s['selector']) ? $s['selector'] : '') : $s;
        if (!is_string($ss) || $ss === '' || isset($used[$ss])) continue;
        $dir = (is_array($s) && !empty($s['desc']) && $s['desc'] !== 'false') ? 'desc' : 'asc';
        if (isset($ctx['cols'][$ss])) {
            $db->order_by($t . '.' . $ss, $dir);
            $used[$ss] = 1;
        } elseif (isset($lookups[$ss])) {
            $lk = $lookups[$ss];
            $al = 'bolk' . ($joins++);
            $db->join($lk['table'] . ' AS ' . $al, $al . '.' . $lk['pk'] . ' = ' . $t . '.' . $lk['fk'], 'left');
            $db->order_by($al . '.' . $lk['display'], $dir);
            $used[$ss] = 1;
        }
    }
    if (!isset($used[$o['key']]) && isset($all[$o['key']])) $db->order_by($t . '.' . $o['key'], 'asc');
    $db->limit($take, $skip);
    $rows = $db->get()->result_array();

    // typed values, lookups (this page only), row callback
    foreach ($rows as $i => $r) {
        foreach ($r as $f => $v) if (isset($kinds[$f])) $rows[$i][$f] = bo_grid_value($v, $kinds[$f]);
    }
    foreach ($lookups as $tf => $lk) {
        $ids = array();
        foreach ($rows as $r) if ((int) $r[$lk['fk']]) $ids[(int) $r[$lk['fk']]] = 1;
        $names = array();
        if (count($ids)) {
            $db->select($lk['pk'] . ', ' . $lk['display'])->from($lk['table'])->where_in($lk['pk'], array_keys($ids));
            foreach ($db->get()->result_array() as $r) $names[(int) $r[$lk['pk']]] = (string) $r[$lk['display']];
        }
        foreach ($rows as $i => $r) {
            $id = (int) $r[$lk['fk']];
            $rows[$i][$tf] = ($id && isset($names[$id])) ? $names[$id] : '';
        }
    }
    if (is_callable($o['row'])) {
        foreach ($rows as $i => $r) $rows[$i] = call_user_func($o['row'], $r);
    }
    $res = array('data' => $rows);
    if ($total !== null) $res['totalCount'] = $total;
    $res['cap'] = $max;
    $res['capped'] = $unbounded && $total !== null && $total > $max;
    return $res;
}

/** Send a bo_grid_data() result as JSON (HTTP 401/403/500 for its error results). */
function bo_grid_output($res)
{
    $CI =& get_instance();
    $status = isset($res['_status']) ? (int) $res['_status'] : 200;
    unset($res['_status']);
    if ($status !== 200) $CI->output->set_status_header($status);
    $CI->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
    $CI->output->set_content_type('application/json');
    $CI->output->set_output(json_encode($res, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE | JSON_PARTIAL_OUTPUT_ON_ERROR));
}
