<?php
/**
 * =====================================================================================================
 * Generic DevExtreme list grid for the back office (porting brief section 4).
 * =====================================================================================================
 * A converted module list view = privilege block + $grid array + this view + session cleanup:
 *     list($read_priv, $write_priv, $delete_priv) = bo_priv("21");          // or the legacy block
 *     if ($read_priv != 1) redirect(base_url() . "back_office/home/dashboard");
 *     $grid = array(...options...);
 *     $this->load->view('back_office/includes/dxgrid', $grid);
 *     $this->session->unset_userdata("success_message"); $this->session->unset_userdata("error_message");
 * Never edit this file from a module conversion: everything is configured through the options below.
 * Pass every option you rely on explicitly (CI shares the controller's $data variables with nested views).
 *
 * OPTIONS (default in brackets). URLs written "x/y/{id}" are relative to base_url().'back_office/';
 * a value starting with http:// or https:// is used as is (e.g. base_url().'uploads/x/{value}').
 *  module        [uri segment 2] URL segment: back_office/<module>/{edit,delete,delete_all,sorted,add,grid_data}
 *  key           [id_<module>] primary key field; hidden column, row key, bulk value (cehcklist[])
 *  rows          [$info] inline mode: all rows already loaded by the controller (array of assoc arrays)
 *  ajax          [false] server mode: true => GET back_office/<module>/grid_data, or a URL ('x/grid_data/5').
 *                The controller method answers with bo_grid_output(bo_grid_data(...)) (bo_helper.php).
 *                In ajax mode: paging/sorting/filtering/search/header filters run on the server, row dragging
 *                is off, "select all" selects the current page, group panel is hidden, export loads at most
 *                'max_export' rows and shows a note when capped.
 *  ajax_params   [array()] extra GET parameters sent with every grid_data call (e.g. array('id_unit'=>5))
 *  max_export    [10000] export cap shown in the note (keep equal to bo_grid_data 'max_take')
 *  table         [module] ajax mode only: table used to list columns when 'columns' is not given
 *  title         [List <module>] breadcrumb title (passed through bo_page_title(): "List news" => قائمة الأخبار)
 *  subtitle      [''] trusted HTML after the title, or a parent chain:
 *                array(array('label'=>'Grade 11','url'=>'material/lists/22'), array('label'=>'Physics')) => "Grade 11 › Physics"
 *                (labels escaped, each crumb isolated in <bdi>; the separator is U+203A, mirrored by the browser in RTL)
 *  columns       [keys of rows[0] | table columns] ordered dataFields to show (minus hidden ones). Untyped columns get
 *                dataType "string" (search panel = "contains"); in ajax mode int/float table columns stay "number".
 *  hide          [array()] extra hidden fields; always hidden: key, deleted, created_date, updated_date,
 *                deleted_date, sort_order (list them in 'columns' AND not in 'hide' to show e.g. created_date)
 *  labels        [bo_label(field)] array(field => caption); a lookup caption may be keyed by fk or fk_text
 *  types         [text] array(field => type):
 *                  date      Y-m-d shown dd/MM/yyyy          datetime  shown dd/MM/yyyy HH:mm
 *                  number    numeric, right aligned        yesno     1/0 => Yes/No badge (filterable)
 *                  status    1/0 => Active/Inactive badge  badge     value => label + css, see 'badges'
 *                  image     thumbnail from 'files' (+ 'thumb'), click = popup; not searchable/sortable
 *                  file      "show file" link to 'files'     html      tags stripped, 120 chars
 *                  link      value rendered as a link, see 'links'
 *                  raw       trusted server-side HTML rendered as is; searched/sorted/exported as plain text
 *                            inline, not searchable/sortable in ajax mode (computed with bo_grid_data 'row')
 *  badges        [array()] array(field => array(value => array(label, css) | label)); css = label-success |
 *                label-important | label-warning | label-info (legacy) or any Bootstrap 5 class (bg-label-primary)
 *  links         [array()] array(field => 'url/{id}' | array('url'=>'...', 'target'=>'_blank'));
 *                placeholders: {id} = key, {value} = cell value, {any_field} = that field of the row.
 *                A template that STARTS with a placeholder ('{value}', '{link}') is an external URL taken from the row:
 *                not prefixed, not URL-encoded; "www.x.com" gets http://, javascript:/data: URLs render as plain text.
 *  widths        [auto] array(field => px)          align  [auto] array(field => left|right|center|start|end)
 *  search        [all visible] fields searched by the search panel (others get allowSearch:false)
 *  hiding        [auto] array(field => hidingPriority). Narrow screens hide the lowest priority first. Default: the
 *                LAST data column hides first; the actions column never hides (it was folded into the '...' row before).
 *  files         ['uploads/<module>/'] base folder of image/file columns     thumb [''] thumbnail sub-folder ('85x85')
 *  lookups       [array()] array(fk => array(table, display_field, pk)): shows "<fk>_text" in place of the id.
 *                Inline: resolved here once per distinct id. Ajax: pass the SAME array to bo_grid_data().
 *  lookups_empty [''] inline mode: text shown for id 0 / a missing parent (e.g. bo_t('grid_not_available'))
 *  actions       [edit+delete] array('edit'=>$write_priv, 'delete'=>$delete_priv, 'edit_url'=>'x/edit/{id}',
 *                'delete_url'=>'x/delete/{id}', 'extra'=>array(array('label'=>..., 'url'=>'unit/lists/{id}',
 *                'icon'=>'bx-list-ul', 'confirm'=>'text', 'title'=>'tooltip', 'target'=>'_blank', 'if'=>'field'))) ; 'if'
 *                shows the link only when that row field is truthy. Delete = confirm + GET <module>/delete/{id}.
 *                'confirm' and 'title' may use {field} placeholders (plain row text, e.g. 'Renew "{name}"?').
 *  delete_confirm [grid_confirm_delete] text of the row delete confirm ({field} placeholders allowed)
 *  add           [false] true => "Add <module>" link to <module>/add, or a URL string ('unit/add/18')
 *  add_label     [bo_module_label(module,'new')] caption of the add link (= the sidebar sub-item: "Add Book" / إضافة كتاب)
 *  bulk          [false] checkbox selection + bulk form POST <module>/delete_all (cehcklist[] + check_option).
 *                The <form id="bo_list_form"> wraps ONLY the bulk select + button (not the grid): Enter in the search
 *                panel / any grid input never posts it; its submit event is blocked, boGrid.bulk() sends it.
 *  bulk_options  [delete_all => Delete selected] array(value => label) of the bulk select
 *  bulk_confirm  [grid_confirm_delete_n] confirm of the delete_all bulk option; %s = number of selected rows
 *  reorder       [false] inline mode only: drag rows => GET <module>/sorted?table-1[]=id&... (whole list order).
 *                Dragging is refused (with a toast) while the grid is sorted by any column other than sort_order.
 *  export        [true] Excel (xlsx) export button
 *  toolbar       [array()] extra breadcrumb links: array(array('label'=>..., 'url'=>'messages/unread', 'icon'=>'bx-envelope', 'active'=>1))
 *  empty         [No records] text when there are no rows
 *  order         [sort_order asc | key] initial sort: 'field' or 'field desc' (ajax: also bo_grid_data 'order')
 *  page_size     [10] rows per page (inline pager 5/10/all, ajax pager 10/20/50/100)
 *  keep          [array()] inline mode: extra row fields sent to the page JS although not shown (e.g. a field used
 *                by custom JS). By default the page JS receives ONLY: the key, sort_order, the visible columns
 *                (incl. lookup *_text and raw/badge/html fields), the hidden sort column, every {field} placeholder
 *                of links / edit_url / delete_url / extra urls and every extra 'if' field. Hidden columns are NOT sent.
 *  strip         [array()] fields never shown nor sent (both modes), even when listed in columns/keep.
 *                ALWAYS stripped as well: bo_grid_sensitive() names = /password|passwd|pwd|token|secret|(^|_)pass$/i
 *                (pass_percent / passed stay usable). bo_grid_data() never returns them either.
 *
 * SECURITY: application/hooks/bo_guard.php (post_controller_constructor hook) already requires a session for every
 * back_office request (401 JSON for grid_data / sorted / XHR, else redirect to the login page) and checks the
 * priviledge table for level 0 users (read_p: index/lists/grid_data/export..., write_p: add/edit/submit/sorted...,
 * delete_p: delete/delete_all). Keep the view privilege block anyway: it drives which buttons are shown.
 * bo_grid_data(): malformed date/datetime filter values => HTTP 400 JSON {error:"bad_request"}; any DB failure
 * => HTTP 400 JSON {error:"query"} (logged), never an HTML error page.
 *
 * (a) SIMPLE LIST (inline) - e.g. banner/list.php
 *     list($read_priv, $write_priv, $delete_priv) = bo_priv("12");
 *     if ($read_priv != 1) redirect(base_url() . "back_office/home/dashboard");
 *     $grid = array(
 *       'module' => 'banner', 'key' => 'id_banner', 'rows' => $info, 'title' => $title,
 *       'order' => $this->uri->segment(4),
 *       'columns' => array('title', 'status'),
 *       'types' => array('status' => 'status'),
 *       'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
 *       'add' => $write_priv, 'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
 *     );
 *     $this->load->view('back_office/includes/dxgrid', $grid);
 *     $this->session->unset_userdata("success_message");
 *     $this->session->unset_userdata("error_message");
 *
 * (b) DRILL-DOWN LIST WITH LOOKUPS + EXTRA ACTIONS + PARENT CHAIN - e.g. section/list.php ($info = sections of one unit)
 *     list($read_priv, $write_priv, $delete_priv) = bo_priv("41");
 *     $id_unit = $this->session->userdata("id_unit");                       // set by section::lists($id)
 *     $unit = $this->fct->getonerow("unit", array("id_unit" => $id_unit));
 *     $grid = array(
 *       'module' => 'section', 'key' => 'id_section', 'rows' => $info, 'title' => $title,
 *       'subtitle' => array(array('label' => $this->fct->getonecell("material", "title", array("id_material" => $unit["id_material"])),
 *                                 'url' => 'unit/lists/' . $unit["id_material"]), array('label' => $unit["title"])),
 *       'columns' => array('title', 'id_class', 'id_material', 'id_unit'),
 *       'lookups' => array('id_class' => array('classes', 'title', 'id_classes'),
 *                          'id_material' => array('material', 'title', 'id_material'), 'id_unit' => array('unit', 'title', 'id_unit')),
 *       'actions' => array('edit' => $write_priv, 'delete' => $delete_priv, 'extra' => array(
 *           array('label' => bo_t('menu_summary'), 'url' => 'summary/lists/{id}', 'icon' => 'bx-file'),
 *           array('label' => bo_t('grid_questions'), 'url' => 'questions_from_exams/lists/{id}', 'icon' => 'bx-help-circle'))),
 *       'add' => $write_priv, 'bulk' => $delete_priv, 'reorder' => $write_priv,
 *     );
 *     $this->load->view('back_office/includes/dxgrid', $grid);   // + the two unset_userdata lines
 *
 * (c) LIST WITH IMAGES / FILES - e.g. books/list.php
 *     $grid = array(
 *       'module' => 'books', 'key' => 'id_books', 'rows' => $info, 'title' => $title,
 *       'columns' => array('title', 'photo', 'pdf', 'id_language'),
 *       'types' => array('photo' => 'image', 'pdf' => 'file'),
 *       'files' => 'uploads/books/', 'thumb' => '',                       // thumbs in uploads/books/<thumb>/ when set
 *       'lookups' => array('id_language' => array('language', 'title', 'id_language')),
 *       'widths' => array('photo' => 90, 'pdf' => 120),
 *       'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
 *       'add' => $write_priv, 'bulk' => $delete_priv, 'reorder' => $write_priv,
 *     );
 *     $this->load->view('back_office/includes/dxgrid', $grid);   // + the two unset_userdata lines
 *
 * (d) AJAX (SERVER) MODE - big tables, e.g. visitor (controller index() no longer loads rows: $data["info"] = array())
 *     view:   $grid = array(
 *               'module' => 'visitor', 'key' => 'id_visitor', 'ajax' => true, 'title' => $title,
 *               'columns' => array('title', 'ip', 'id_material', 'id_class', 'date1'),
 *               'types' => array('date1' => 'date'),
 *               'lookups' => array('id_material' => array('material', 'title', 'id_material'),
 *                                  'id_class' => array('classes', 'title', 'id_classes')),
 *               'search' => array('title', 'ip', 'id_material', 'id_class'),
 *               'order' => 'id_visitor desc',
 *               'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
 *               'add' => $write_priv, 'bulk' => $delete_priv, 'export' => true);
 *             $this->load->view('back_office/includes/dxgrid', $grid);
 *     controller:  public function grid_data() {
 *               bo_grid_output(bo_grid_data($this->table, array('id_content' => 25,
 *                 'columns' => array('title', 'ip', 'id_material', 'id_class', 'date1'), 'search' => array('title', 'ip'),
 *                 'lookups' => array(...same as the view...), 'order' => 'id_visitor desc'))); }
 *     drill-down: 'where' => array('id_unit' => $this->session->userdata('id_unit')) in bo_grid_data, or
 *                 'ajax' => 'section/grid_data/' . $id_unit in the view and grid_data($id) in the controller.
 * =====================================================================================================
 */
if (!function_exists('bo_js')) {
    function bo_js($v) { return json_encode($v, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE); }
}
if (!function_exists('bo_grid_url')) {
    /** back_office-relative URL template => absolute (http(s):// kept as is). */
    function bo_grid_url($u) {
        $u = (string) $u;
        if (preg_match('#^https?://#i', $u)) return $u;
        return base_url() . 'back_office/' . ltrim(preg_replace('#^/?back_office/#', '', $u), '/');
    }
}
$g_module  = (isset($module) && is_string($module) && $module !== '') ? $module : $this->uri->segment(2);
$g_key     = (isset($key) && is_string($key) && $key !== '') ? $key : 'id_' . $g_module;
$g_base    = base_url();

// server (ajax) mode
$g_ajax_url = '';
if (!empty($ajax)) {
    $g_ajax_url = ($ajax === true || $ajax === 1 || $ajax === '1') ? bo_grid_url($g_module . '/grid_data') : bo_grid_url($ajax);
}
$g_is_ajax  = $g_ajax_url !== '';
$g_ajax_params = (isset($ajax_params) && is_array($ajax_params)) ? $ajax_params : array();
$g_cap      = (isset($max_export) && (int) $max_export > 0) ? (int) $max_export : 10000;
$g_table    = (isset($table) && is_string($table) && $table !== '') ? $table : $g_module;
$g_tcols    = $g_is_ajax ? bo_table_columns($g_table) : array();

$g_rows    = $g_is_ajax ? array() : (isset($rows) ? $rows : (isset($info) ? $info : array()));
if (!is_array($g_rows)) $g_rows = array();
$g_rows    = array_values($g_rows);
$g_title   = isset($title) ? bo_page_title($title) : bo_module_label($g_module, 'list');
$g_subtitle= isset($subtitle) ? $subtitle : '';
$g_hide    = isset($hide) && is_array($hide) ? $hide : array();
$g_columns = isset($columns) && is_array($columns) ? array_values($columns) : null;
$g_labels  = isset($labels) && is_array($labels) ? $labels : array();
$g_types   = isset($types) && is_array($types) ? $types : array();
$g_files   = (isset($files) && is_string($files)) ? rtrim($files, '/') . '/' : 'uploads/' . $g_module . '/';
$g_thumb   = (isset($thumb) && is_string($thumb)) ? trim($thumb, '/') : '';
$g_lookups = isset($lookups) && is_array($lookups) ? $lookups : array();
$g_badges  = isset($badges) && is_array($badges) ? $badges : array();
$g_links   = isset($links) && is_array($links) ? $links : array();
$g_widths  = isset($widths) && is_array($widths) ? $widths : array();
$g_align   = isset($align) && is_array($align) ? $align : array();
$g_search  = (isset($search) && is_array($search)) ? $search : null;
$g_actions = isset($actions) && is_array($actions) ? $actions : array('edit' => 1, 'delete' => 1);
$g_edit    = !empty($g_actions['edit']);
$g_delete  = !empty($g_actions['delete']);
$g_edit_url   = !empty($g_actions['edit_url']) ? bo_grid_url($g_actions['edit_url']) : bo_grid_url($g_module . '/edit/{id}');
$g_delete_url = !empty($g_actions['delete_url']) ? bo_grid_url($g_actions['delete_url']) : bo_grid_url($g_module . '/delete/{id}');
$g_extra   = (isset($g_actions['extra']) && is_array($g_actions['extra'])) ? $g_actions['extra'] : array();
$g_add     = !empty($add);
$g_add_url = ($g_add && is_string($add) && !is_numeric($add)) ? bo_grid_url($add) : bo_grid_url($g_module . '/add');
$g_add_label = (isset($add_label) && is_string($add_label) && $add_label !== '') ? $add_label : bo_module_label($g_module, 'new');
$g_bulk    = !empty($bulk);
$g_bulk_options = (isset($bulk_options) && is_array($bulk_options) && count($bulk_options)) ? $bulk_options : array('delete_all' => bo_t('grid_delete_selected'));
$g_reorder = !empty($reorder) && !$g_is_ajax;
$g_export  = !isset($export) || !empty($export);
$g_toolbar = isset($toolbar) && is_array($toolbar) ? $toolbar : array();
$g_empty   = isset($empty) ? $empty : bo_t('grid_no_records');
$g_order   = (isset($order) && $order !== false && $order !== null && is_scalar($order)) ? trim((string) $order) : '';
$g_order_dir = 'asc';
if (preg_match('/^(\S+)\s+(asc|desc)$/i', $g_order, $g_m)) { $g_order = $g_m[1]; $g_order_dir = strtolower($g_m[2]); }
$g_page_size = (isset($page_size) && (int) $page_size > 0) ? (int) $page_size : 10;
$g_rtl     = bo_rtl();
$g_keep    = (isset($keep) && is_array($keep)) ? array_values($keep) : array();
$g_strip_opt = (isset($strip) && is_array($strip)) ? array_values($strip) : array();
$g_hiding  = (isset($hiding) && is_array($hiding)) ? $hiding : array();
$g_lookups_empty = (isset($lookups_empty) && is_string($lookups_empty)) ? $lookups_empty : '';
$g_delete_confirm = (isset($delete_confirm) && is_string($delete_confirm) && $delete_confirm !== '') ? $delete_confirm : bo_t('grid_confirm_delete');
$g_bulk_confirm = (isset($bulk_confirm) && is_string($bulk_confirm) && $bulk_confirm !== '') ? $bulk_confirm : bo_t('grid_confirm_delete_n');

// lookups: FK -> "<fk>_text" (inline: resolved here once per distinct id; ajax: returned by bo_grid_data)
$g_lookup_fields = array();
foreach ($g_lookups as $g_field => $g_lk) {
    if (!is_array($g_lk) || count($g_lk) < 3) continue;
    list($g_ltable, $g_display, $g_pk) = array_values($g_lk);
    if (!$g_is_ajax) {
        $g_cache = array();
        foreach ($g_rows as $g_i => $g_r) {
            $g_id = isset($g_r[$g_field]) ? $g_r[$g_field] : '';
            if ($g_id === '' || $g_id === null || (int) $g_id === 0) { $g_rows[$g_i][$g_field . '_text'] = $g_lookups_empty; continue; }
            if (!array_key_exists($g_id, $g_cache)) {
                $g_cache[$g_id] = (string) $this->fct->getonecell($g_ltable, $g_display, array($g_pk => $g_id));
                if ($g_cache[$g_id] === '') $g_cache[$g_id] = $g_lookups_empty;
            }
            $g_rows[$g_i][$g_field . '_text'] = $g_cache[$g_id];
        }
    }
    $g_lookup_fields[$g_field] = $g_field . '_text';
    if (!isset($g_labels[$g_field . '_text'])) $g_labels[$g_field . '_text'] = isset($g_labels[$g_field]) ? $g_labels[$g_field] : bo_label($g_field);
    foreach (array('g_widths', 'g_align') as $g_opt) {
        if (isset(${$g_opt}[$g_field]) && !isset(${$g_opt}[$g_field . '_text'])) ${$g_opt}[$g_field . '_text'] = ${$g_opt}[$g_field];
    }
    if (is_array($g_search) && in_array($g_field, $g_search, true)) $g_search[] = $g_field . '_text';
}

// inline value normalisation: 1/0 types and numeric badge keys as integers, dates as ISO strings
foreach ($g_types as $g_f => $g_ty) {
    $g_int = ($g_ty === 'yesno' || $g_ty === 'status');
    if ($g_ty === 'badge' && isset($g_badges[$g_f]) && is_array($g_badges[$g_f])) {
        $g_int = true;
        foreach (array_keys($g_badges[$g_f]) as $g_bk) if (!is_int($g_bk)) $g_int = false;
    }
    foreach ($g_rows as $g_i => $g_r) {
        if (!array_key_exists($g_f, $g_r)) continue;
        if ($g_int && is_numeric($g_r[$g_f])) $g_rows[$g_i][$g_f] = (int) $g_r[$g_f];
        elseif ($g_ty === 'date' || $g_ty === 'datetime') {
            $g_v = (string) $g_r[$g_f];
            $g_rows[$g_i][$g_f] = ($g_v === '' || strpos($g_v, '0000-00-00') === 0) ? null : ($g_ty === 'date' ? substr($g_v, 0, 10) : str_replace(' ', 'T', substr($g_v, 0, 19)));
        }
    }
}

$g_default_hidden = array('deleted', 'created_date', 'updated_date', 'deleted_date', 'sort_order', $g_key);
$g_hidden = array_merge($g_hide, array_keys($g_lookup_fields));
if ($g_columns === null) {
    $g_hidden = array_merge($g_hidden, $g_default_hidden);
    $g_columns = $g_is_ajax ? array_keys($g_tcols) : (count($g_rows) ? array_keys($g_rows[0]) : array('title'));
    // remove the generated *_text helpers from the raw key list; they are inserted at the FK position below
    $g_columns = array_values(array_diff($g_columns, array_values($g_lookup_fields)));
} else {
    // an explicit column list may show created_date & co, never the key / sort_order / deleted flags
    $g_hidden = array_merge($g_hidden, array('deleted', 'deleted_date', 'sort_order', $g_key));
}
$g_visible = array();
foreach ($g_columns as $g_c) {
    if (isset($g_lookup_fields[$g_c])) { $g_visible[] = $g_lookup_fields[$g_c]; continue; }
    if (in_array($g_c, $g_hidden, true)) continue;
    $g_visible[] = $g_c;
}
// never shown, never sent: the 'strip' option and every sensitive-looking field (bo_grid_sensitive())
$g_never = function ($f) use ($g_strip_opt, $g_key) {
    return $f !== $g_key && (in_array($f, $g_strip_opt, true) || bo_grid_sensitive($f));
};
$g_visible = array_values(array_filter($g_visible, function ($f) use ($g_never) { return !$g_never($f); }));
$g_has_sort = $g_is_ajax ? isset($g_tcols['sort_order']) : (count($g_rows) && array_key_exists('sort_order', $g_rows[0]));
$g_known = $g_is_ajax ? $g_tcols : (count($g_rows) ? $g_rows[0] : array());
if (isset($g_lookup_fields[$g_order])) $g_order = $g_lookup_fields[$g_order];
$g_sort_js = ', sortOrder: "' . $g_order_dir . '", sortIndex: 0';

// column definitions (JS source)
$g_cols_js = array();
$g_nosearch = $g_is_ajax ? ', allowSearch: false' : '';
$g_cols_js[] = '{ dataField: ' . bo_js($g_key) . ', visible: false, showInColumnChooser: false, allowExporting: false' . $g_nosearch
             . ($g_order === $g_key ? $g_sort_js : '') . ' }';
if ($g_has_sort) {
    $g_sort = ($g_order === '' || $g_order === 'sort_order') ? $g_sort_js : '';
    $g_cols_js[] = '{ dataField: "sort_order", visible: false, showInColumnChooser: false, allowExporting: false' . $g_nosearch . ', dataType: "number"' . $g_sort . ' }';
}
if ($g_order !== '' && $g_order !== $g_key && $g_order !== 'sort_order' && !in_array($g_order, $g_visible, true) && array_key_exists($g_order, $g_known)) {
    $g_cols_js[] = '{ dataField: ' . bo_js($g_order) . ', visible: false, showInColumnChooser: false, allowExporting: false' . $g_nosearch . $g_sort_js . ' }';
}
$g_no_ops = ', allowSorting: false, allowFiltering: false, allowHeaderFiltering: false, allowGrouping: false, allowSearch: false';
$g_strip_cols = array();
$g_nvis = count($g_visible);
foreach ($g_visible as $g_vi => $g_f) {
    $g_type = isset($g_types[$g_f]) ? $g_types[$g_f] : '';
    $g_caption = isset($g_labels[$g_f]) ? $g_labels[$g_f] : bo_label($g_f);
    $g_def  = 'dataField: ' . bo_js($g_f) . ', caption: ' . bo_js($g_caption);
    if ($g_order !== '' && $g_order === $g_f) $g_def .= $g_sort_js;
    if (is_array($g_search) && !in_array($g_f, $g_search, true)) $g_def .= ', allowSearch: false';
    $g_w = '';
    switch ($g_type) {
        case 'date':
            $g_def .= ', dataType: "date", format: "dd/MM/yyyy"' . ($g_is_ajax ? ', allowHeaderFiltering: false' : '');
            $g_w = 120;
            break;
        case 'datetime':
            $g_def .= ', dataType: "datetime", format: "dd/MM/yyyy HH:mm"' . ($g_is_ajax ? ', allowHeaderFiltering: false' : '');
            $g_w = 150;
            break;
        case 'yesno':
        case 'status':
            $g_on  = $g_type === 'status' ? bo_t('grid_active') : bo_t('grid_yes');
            $g_off = $g_type === 'status' ? bo_t('grid_inactive') : bo_t('grid_no');
            $g_def .= ', dataType: "number", alignment: "center", lookup: { dataSource: [{ v: 1, t: ' . bo_js($g_on) . ' }, { v: 0, t: ' . bo_js($g_off) . ' }], valueExpr: "v", displayExpr: "t" }'
                    . ', cellTemplate: function (c, o) { c.append(boGrid.badge(o.value, ' . bo_js($g_on) . ', ' . bo_js($g_off) . ')); }';
            $g_w = 110;
            break;
        case 'badge':
            $g_items = array();
            foreach ((isset($g_badges[$g_f]) && is_array($g_badges[$g_f]) ? $g_badges[$g_f] : array()) as $g_bv => $g_b) {
                $g_items[] = array('v' => $g_bv, 't' => is_array($g_b) ? (string) $g_b[0] : (string) $g_b, 'c' => (is_array($g_b) && isset($g_b[1])) ? (string) $g_b[1] : 'label-info');
            }
            $g_def .= ', alignment: "center", lookup: { dataSource: ' . bo_js($g_items) . ', valueExpr: "v", displayExpr: "t" }'
                    . ', cellTemplate: function (c, o) { c.append(boGrid.badgeMap(o.value, ' . bo_js($g_items) . ')); }';
            $g_w = 120;
            break;
        case 'image':
            $g_def .= $g_no_ops . ', alignment: "center", cellTemplate: function (c, o) { c.append(boGrid.image(o.value)); }';
            $g_w = 100;
            break;
        case 'file':
            $g_def .= $g_no_ops . ', cellTemplate: function (c, o) { c.append(boGrid.file(o.value)); }';
            $g_w = 130;
            break;
        case 'html':
            $g_def .= ', dataType: "string"';
            if ($g_is_ajax) {
                $g_def .= ', cellTemplate: function (c, o) { c.text(stripHtml(o.value).substring(0, 120)); }';
                $g_strip_cols[] = $g_f;
            } else {
                $g_def .= ', calculateCellValue: function (r) { return stripHtml(r[' . bo_js($g_f) . ']).substring(0, 120); }';
            }
            break;
        case 'raw':
            if ($g_is_ajax) {
                $g_def .= $g_no_ops;
                $g_strip_cols[] = $g_f;
            } else {
                $g_def .= ', dataType: "string", calculateCellValue: function (r) { return stripHtml(r[' . bo_js($g_f) . ']); }';
            }
            $g_def .= ', encodeHtml: false, cellTemplate: function (c, o) { c.html(o.data[' . bo_js($g_f) . '] == null ? "" : String(o.data[' . bo_js($g_f) . '])); }';
            break;
        case 'link':
            $g_l = isset($g_links[$g_f]) ? $g_links[$g_f] : '';
            $g_lu = is_array($g_l) ? (isset($g_l['url']) ? $g_l['url'] : '') : $g_l;
            $g_lt = (is_array($g_l) && isset($g_l['target'])) ? $g_l['target'] : '';
            $g_lu = ($g_lu === '' || substr($g_lu, 0, 1) === '{') ? $g_lu : bo_grid_url($g_lu);
            $g_def .= ', dataType: "string", cellTemplate: function (c, o) { c.append(boGrid.link(o.data, o.value, ' . bo_js($g_lu) . ', ' . bo_js($g_lt) . ')); }';
            break;
        case 'number':
            $g_def .= ', dataType: "number"';
            $g_w = 110;
            break;
        default:
            // untyped columns: text => dataType "string" so the search panel / filter row use "contains"
            // (ajax mode: numeric table columns stay numbers; lookup *_text and unknown fields are strings)
            $g_kind = ($g_is_ajax && isset($g_tcols[$g_f])) ? $g_tcols[$g_f] : 'text';
            $g_def .= ($g_kind === 'int' || $g_kind === 'float') ? ', dataType: "number"' : ', dataType: "string"';
            break;
    }
    if (isset($g_widths[$g_f]) && (int) $g_widths[$g_f] > 0) $g_w = (int) $g_widths[$g_f];
    if ($g_w !== '') $g_def .= ', width: ' . (int) $g_w;
    // explicit priorities stay below the automatic ones (100 + position), so views may still set 0, 1... in JS
    $g_def .= ', hidingPriority: ' . (isset($g_hiding[$g_f]) ? (int) $g_hiding[$g_f] : 100 + ($g_nvis - 1 - $g_vi));
    if (isset($g_align[$g_f])) {
        $g_a = strtolower($g_align[$g_f]);
        if ($g_a === 'start') $g_a = $g_rtl ? 'right' : 'left';
        if ($g_a === 'end') $g_a = $g_rtl ? 'left' : 'right';
        if (in_array($g_a, array('left', 'right', 'center'), true)) $g_def .= ', alignment: "' . $g_a . '"';
    }
    $g_cols_js[] = '{ ' . $g_def . ' }';
}
if ($g_edit || $g_delete || count($g_extra)) {
    $g_cols_js[] = '{ caption: ' . bo_js(bo_t('grid_actions')) . ', name: "bo_actions", minWidth: 90, allowSorting: false, allowFiltering: false, allowHeaderFiltering: false, allowGrouping: false, allowExporting: false, allowReordering: false, allowSearch: false, alignment: "center"'
                 . ', cellTemplate: function (c, o) { c.append(boGrid.actions(o.data)); } }';
}
$g_extra_js = array();
foreach ($g_extra as $g_x) {
    $g_extra_js[] = array(
        'label' => isset($g_x['label']) ? $g_x['label'] : bo_t('grid_manage'),
        'url' => isset($g_x['url']) ? bo_grid_url($g_x['url']) : '',
        'icon' => isset($g_x['icon']) ? $g_x['icon'] : 'bx-list-ul',
        'confirm' => isset($g_x['confirm']) ? $g_x['confirm'] : '',
        'title' => isset($g_x['title']) ? $g_x['title'] : '',
        'target' => isset($g_x['target']) ? $g_x['target'] : '',
        'if' => isset($g_x['if']) ? $g_x['if'] : '',
    );
}

// inline payload trimming: only the key, sort_order, the visible columns (lookup *_text, raw/badge fields included),
// the hidden sort column, the {field} placeholders of links/actions, 'if' fields and 'keep' reach the page JS.
if (!$g_is_ajax && count($g_rows)) {
    $g_send = array($g_key => 1, 'sort_order' => 1);
    if ($g_order !== '') $g_send[$g_order] = 1;
    foreach (array_merge($g_visible, $g_keep) as $g_f) $g_send[$g_f] = 1;
    $g_tpls = array($g_edit_url, $g_delete_url);
    foreach ($g_links as $g_l) $g_tpls[] = is_array($g_l) ? (isset($g_l['url']) ? $g_l['url'] : '') : $g_l;
    $g_tpls[] = $g_delete_confirm;
    foreach ($g_extra_js as $g_x) {
        $g_tpls[] = $g_x['url'];
        $g_tpls[] = $g_x['confirm'];
        $g_tpls[] = $g_x['title'];
        if ($g_x['if'] !== '') $g_send[$g_x['if']] = 1;
    }
    foreach ($g_tpls as $g_tpl) {
        if (preg_match_all('/\{(\w+)\}/', (string) $g_tpl, $g_pm)) {
            foreach ($g_pm[1] as $g_f) if ($g_f !== 'id' && $g_f !== 'value') $g_send[$g_f] = 1;
        }
    }
    foreach (array_keys($g_send) as $g_f) if ($g_never($g_f)) unset($g_send[$g_f]);
    foreach ($g_rows as $g_i => $g_r) $g_rows[$g_i] = array_intersect_key($g_r, $g_send);
}
// MySQL returns numbers as strings: make the key and sort_order real numbers so they sort 2 < 10 (not "10" < "2")
foreach ($g_rows as $g_i => $g_r) {
    foreach (array($g_key, 'sort_order') as $g_f) {
        if (isset($g_r[$g_f]) && is_string($g_r[$g_f]) && is_numeric($g_r[$g_f])) $g_rows[$g_i][$g_f] = $g_r[$g_f] + 0;
    }
}
?>
<div class="span10-fluid">
<ul class="breadcrumb">
<li class="active"><?= $g_title ?><?php
if (is_array($g_subtitle) && count($g_subtitle)) {
    // U+203A is a bidi-mirrored character: it is drawn as "‹" in RTL, so one separator serves both directions
    $g_sep = ' &rsaquo; ';
    $g_parts = array();
    foreach ($g_subtitle as $g_s) {
        $g_sl = htmlspecialchars(is_array($g_s) ? (isset($g_s['label']) ? (string) $g_s['label'] : '') : (string) $g_s, ENT_QUOTES, 'UTF-8');
        $g_parts[] = '<bdi>' . ((is_array($g_s) && !empty($g_s['url'])) ? '<a href="' . htmlspecialchars(bo_grid_url($g_s['url']), ENT_QUOTES, 'UTF-8') . '">' . $g_sl . '</a>' : $g_sl) . '</bdi>';
    }
    echo ' <small class="muted bo-subtitle">' . implode($g_sep, $g_parts) . '</small>';
} elseif (is_string($g_subtitle) && $g_subtitle !== '') {
    echo ' <small class="muted bo-subtitle">' . $g_subtitle . '</small>';
}
?></li>
<?php if (count($g_toolbar) || $g_add) { ?>
<li class="pull-right bo-breadcrumb-tools">
<?php foreach ($g_toolbar as $g_tb) { ?>
<a href="<?= htmlspecialchars(bo_grid_url($g_tb['url']), ENT_QUOTES, 'UTF-8') ?>" class="<?= !empty($g_tb['active']) ? 'fw-bold' : '' ?>"><?php if (!empty($g_tb['icon'])) { ?><i class="bx <?= $g_tb['icon'] ?>"></i> <?php } ?><?= $g_tb['label'] ?></a>
<?php } ?>
<?php if ($g_add) { ?>
<a href="<?= htmlspecialchars($g_add_url, ENT_QUOTES, 'UTF-8') ?>" id="topLink" title="<?= htmlspecialchars($g_add_label, ENT_QUOTES, 'UTF-8') ?>"><i class="bx bx-plus"></i> <?= $g_add_label ?></a>
<?php } ?>
</li>
<?php } ?>
</ul>
</div>
<?php if ($this->session->userdata("success_message")) { ?>
<div class="alert alert-success"><?= $this->session->userdata("success_message") ?></div>
<?php } ?>
<?php if ($this->session->userdata("error_message")) { ?>
<div class="alert alert-error"><?= $this->session->userdata("error_message") ?></div>
<?php } ?>
<div id="result"></div>
<div class="card bo-grid-card<?= $g_is_ajax ? ' bo-grid-ajax' : '' ?>">
<div class="card-body">
<div id="gridContainer"></div>
<?php if ($g_bulk) { ?>
<form action="<?= htmlspecialchars(bo_grid_url($g_module . '/delete_all'), ENT_QUOTES, 'UTF-8') ?>" method="post" name="list_form" id="bo_list_form">
<div class="bo-bulk">
<select class="form-select" name="check_option" id="bo_check_option">
<option value="option1"><?= bo_t('grid_bulk_options') ?></option>
<?php foreach ($g_bulk_options as $g_v => $g_l) { ?>
<option value="<?= $g_v ?>"><?= $g_l ?></option>
<?php } ?>
</select>
<a class="btn btn-primary btn_mrg" id="bo_bulk_btn" style="cursor:pointer;"><span><?= bo_t('grid_perform') ?></span></a>
<small class="text-muted" id="bo_bulk_count"></small>
</div>
</form>
<?php } ?>
</div>
</div>
<script>
var boGrid = (function () {
  var cfg = {
    module: <?= bo_js($g_module) ?>, key: <?= bo_js($g_key) ?>, base: <?= bo_js($g_base) ?>,
    files: <?= bo_js($g_base . $g_files) ?>, thumb: <?= bo_js($g_thumb) ?>,
    edit: <?= $g_edit ? 'true' : 'false' ?>, del: <?= $g_delete ? 'true' : 'false' ?>, extra: <?= bo_js($g_extra_js) ?>,
    editUrl: <?= bo_js($g_edit_url) ?>, deleteUrl: <?= bo_js($g_delete_url) ?>,
    delConfirm: <?= bo_js($g_delete_confirm) ?>, bulkConfirm: <?= bo_js($g_bulk_confirm) ?>,
    bulk: <?= $g_bulk ? 'true' : 'false' ?>, reorder: <?= $g_reorder ? 'true' : 'false' ?>, exp: <?= $g_export ? 'true' : 'false' ?>,
    empty: <?= bo_js($g_empty) ?>, ajax: <?= bo_js($g_ajax_url) ?>, params: <?= bo_js((object) $g_ajax_params) ?>, cap: <?= (int) $g_cap ?>,
    pageSize: <?= (int) $g_page_size ?>, strip: <?= bo_js($g_strip_cols) ?>
  };
  var rows = <?= bo_js($g_rows) ?>;
  function i18n(k, d) { return (window.BO_I18N && window.BO_I18N[k] !== undefined) ? window.BO_I18N[k] : d; }
  function fmt(s) { var a = Array.prototype.slice.call(arguments, 1), i = 0; return String(s).replace(/%s/g, function () { return a[i++]; }); }
  function esc(s) { return $('<div>').text(s == null ? '' : String(s)).html(); }
  // plain-text placeholders (confirm / tooltip texts): no URL encoding
  function fillText(s, d) {
    return String(s == null ? '' : s).replace(/\{(\w+)\}/g, function (m, f) { var v = f === 'id' ? d[cfg.key] : d[f]; return v == null ? '' : String(v); });
  }
  // external URL from a row field ('{value}' / '{link}' templates): javascript:, data:... are refused
  function extUrl(tpl, d, value) {
    var u = $.trim(String(tpl).replace(/\{(\w+)\}/g, function (m, f) { var v = f === 'id' ? d[cfg.key] : (f === 'value' ? value : d[f]); return v == null ? '' : String(v); }));
    if (u === '') return '';
    if (/^(https?:|mailto:|tel:|\/|#)/i.test(u)) return u;
    if (/^[a-z][a-z0-9+.\-]*:/i.test(u)) return '';
    return 'http://' + u;
  }
  function fill(url, d, value) {
    return String(url).replace(/\{(\w+)\}/g, function (m, f) {
      var v = f === 'id' ? d[cfg.key] : (f === 'value' ? value : d[f]);
      return encodeURIComponent(v == null ? '' : v);
    });
  }
  var api = {
    cfg: cfg, rows: rows, grid: null,
    badge: function (v, on, off) {
      return $('<span></span>').addClass('label ' + (parseInt(v, 10) === 1 ? 'label-success' : 'label-important')).text(parseInt(v, 10) === 1 ? on : off);
    },
    badgeMap: function (v, items) {
      for (var i = 0; i < items.length; i++) {
        if (String(items[i].v) === String(v)) {
          var c = items[i].c || 'label-info';
          return $('<span></span>').addClass((/^label-/.test(c) ? 'label ' : 'badge ') + c).text(items[i].t);
        }
      }
      return $('<span></span>').text(v == null ? '' : v);
    },
    link: function (d, v, url, target) {
      if (v == null || v === '') return '';
      if (!url) return $('<span></span>').text(v);
      var href = url.charAt(0) === '{' ? extUrl(url, d, v) : fill(url, d, v);
      if (!href) return $('<span></span>').text(v);
      var $a = $('<a class="bo-cell-link"></a>').attr('href', href).text(v);
      if (target) $a.attr('target', target);
      return $a;
    },
    image: function (v) {
      if (!v) return $('<span class="text-muted"></span>').text(i18n('grid_no_image', 'No image'));
      var full = cfg.files + v, src = cfg.thumb ? cfg.files + cfg.thumb + '/' + v : full;
      return $('<img class="bo-thumb" alt="" />').attr('src', src).attr('title', v).on('click', function () { window.popup(full); })
        .on('error', function () { if (this.src !== full) this.src = full; });
    },
    file: function (v) {
      if (!v) return $('<span class="text-muted"></span>').text(i18n('grid_no_file', 'No file'));
      return $('<a target="_blank"></a>').attr('href', cfg.files + v).html('<i class="bx bx-file"></i> ' + esc(i18n('grid_show_file', 'show file')));
    },
    actions: function (d) {
      var id = d[cfg.key], $w = $('<div class="bo-actions text-nowrap"></div>');
      $.each(cfg.extra, function (_, x) {
        if (x['if'] && !d[x['if']]) return;
        var $a = $('<a class="bo-extra-link table-edit-link"></a>').attr('href', fill(x.url, d)).html('<i class="bx ' + esc(x.icon) + '"></i> ' + esc(x.label));
        if (x.target) $a.attr('target', x.target);
        if (x.title) $a.attr('title', fillText(x.title, d));
        if (x.confirm) $a.on('click', function (e) { if (!window.confirm(fillText(x.confirm, d))) e.preventDefault(); });
        $w.append($a);
      });
      if (cfg.edit) $w.append($('<a class="table-edit-link"></a>').attr('href', fill(cfg.editUrl, d)).html('<i class="bx bx-edit"></i> ' + esc(i18n('grid_edit', 'Edit'))));
      if (cfg.del) $w.append($('<a class="table-delete-link cur"></a>').html('<i class="bx bx-x-circle"></i> ' + esc(i18n('grid_delete', 'Delete'))).on('click', function () {
        if (window.confirm(fillText(cfg.delConfirm, d))) window.location = fill(cfg.deleteUrl, d);
        return false;
      }));
      return $w;
    },
    store: function () {
      return new DevExpress.data.CustomStore({
        key: cfg.key,
        load: function (lo) {
          var p = $.extend({}, cfg.params), d = $.Deferred();
          $.each(['skip', 'take', 'requireTotalCount', 'requireGroupCount', 'searchValue', 'searchOperation'], function (_, k) {
            if (lo[k] !== undefined && lo[k] !== null && lo[k] !== '') p[k] = lo[k];
          });
          $.each(['sort', 'filter', 'group', 'searchExpr'], function (_, k) { if (lo[k]) p[k] = JSON.stringify(lo[k]); });
          $.ajax({ url: cfg.ajax, data: p, dataType: 'json', cache: false }).done(function (r) {
            api.last = r;
            d.resolve({ data: r.data || [], totalCount: r.totalCount, groupCount: r.groupCount });
          }).fail(function (x) {
            if (x.status === 401) { window.location = cfg.base + 'back_office/home'; return; }
            d.reject(i18n('grid_load_error', 'Could not load the data.'));
          });
          return d.promise();
        }
      });
    },
    // reordering is only meaningful in sort_order order: refuse the drag while another column sorts the grid
    dragStart: function (e) {
      var g = e.component;
      for (var i = 0; i < g.columnCount(); i++) {
        var c = g.columnOption(i);
        if (c && c.sortOrder && c.dataField !== 'sort_order') {
          e.cancel = true;
          if (window.DevExpress && DevExpress.ui && DevExpress.ui.notify) DevExpress.ui.notify(i18n('grid_clear_sort_to_reorder', 'Clear the column sort first to reorder the rows.'), 'warning', 3000);
          return;
        }
      }
    },
    reorder: function (e) {
      var visible = e.component.getVisibleRows();
      if (!visible[e.toIndex]) return;
      var target = visible[e.toIndex].data;
      var from = rows.indexOf(e.itemData), to = rows.indexOf(target);
      if (from < 0 || to < 0) return;
      rows.splice(from, 1);
      rows.splice(to, 0, e.itemData);
      $.each(rows, function (i, r) { r.sort_order = i; });
      var qs = $.map(rows, function (r) { return 'table-1[]=' + encodeURIComponent(r[cfg.key]); }).join('&');
      $.get(cfg.base + 'back_office/' + cfg.module + '/sorted?' + qs).always(function () { e.component.refresh(); });
    },
    bulk: function () {
      var grid = api.grid, opt = $('#bo_check_option').val();
      var keys = grid.getSelectedRowKeys();
      if (!keys.length) { window.alert(i18n('grid_select_rows', 'Select at least one row.')); return false; }
      if (!opt || opt === 'option1') { $('#bo_check_option').focus(); return false; }
      if (opt === 'delete_all' && !window.confirm(fmt(cfg.bulkConfirm, keys.length))) return false;
      var $f = $('#bo_list_form');
      $f.find('input[name="cehcklist[]"]').remove();
      $.each(keys, function (_, k) { $f.append($('<input type="hidden" name="cehcklist[]" />').val(k)); });
      $f[0].submit();
      return false;
    },
    exportCell: function (o) {
      if (o.gridCell && o.gridCell.rowType === 'data' && o.gridCell.column && cfg.strip.indexOf(o.gridCell.column.dataField) > -1) {
        o.excelCell.value = stripHtml(o.gridCell.value);
      }
    },
    exporting: function (e) {
      var name = String(cfg.module).replace(/[\\\/\?\*\[\]:]/g, '_').substring(0, 31) || 'list';
      var wb = new ExcelJS.Workbook(), ws = wb.addWorksheet(name);
      var opt = { component: e.component, worksheet: ws, autoFilterEnabled: true, customizeCell: api.exportCell };
      if (cfg.ajax) {
        var total = e.component.totalCount();
        if (total > cfg.cap) {
          var msg = fmt(i18n('grid_export_capped', 'The export is limited to the first %s of %s rows.'), cfg.cap, total);
          ws.getCell('A1').value = msg;
          ws.getCell('A1').font = { bold: true, color: { argb: 'FFC00000' } };
          opt.topLeftCell = { row: 3, column: 1 };
          if (window.DevExpress && DevExpress.ui && DevExpress.ui.notify) DevExpress.ui.notify(msg, 'warning', 6000);
        }
      }
      DevExpress.excelExporter.exportDataGrid(opt).then(function () {
        wb.xlsx.writeBuffer().then(function (buf) { saveAs(new Blob([buf], { type: 'application/octet-stream' }), cfg.module + '.xlsx'); });
      });
      e.cancel = true;
    }
  };
  return api;
})();
$(function () {
  var ajax = !!boGrid.cfg.ajax;
  var opts = {
    dataSource: ajax ? boGrid.store() : boGrid.rows,
    columns: [<?= implode(",\n      ", $g_cols_js) ?>],
    showBorders: true,
    rtlEnabled: window.BO_RTL === true,
    selection: boGrid.cfg.bulk ? { mode: "multiple", showCheckBoxesMode: "always", selectAllMode: ajax ? "page" : "allPages" } : { mode: "single" },
    wordWrapEnabled: true,
    allowColumnReordering: true,
    allowColumnResizing: true,
    columnAutoWidth: true,
    rowAlternationEnabled: true,
    hoverStateEnabled: true,
    columnHidingEnabled: true,
    columnChooser: { enabled: true },
    filterRow: { visible: false },
    headerFilter: { visible: true },
    searchPanel: { visible: true, width: 200, placeholder: (window.BO_I18N && BO_I18N.grid_search) || "Search" },
    groupPanel: { visible: !ajax },
    grouping: { autoExpandAll: false },
    sorting: { mode: "multiple" },
    scrolling: { rowRenderingMode: ajax ? "standard" : "virtual" },
    paging: { pageSize: boGrid.cfg.pageSize },
    pager: { visible: true, allowedPageSizes: ajax ? [10, 20, 50, 100] : [5, 10, "all"], showPageSizeSelector: true, showInfo: true, showNavigationButtons: true },
    noDataText: boGrid.cfg.empty,
    export: { enabled: boGrid.cfg.exp, formats: ["xlsx"] },
    onExporting: boGrid.exporting,
    onSelectionChanged: function (e) { $('#bo_bulk_count').text(e.selectedRowKeys.length ? e.selectedRowKeys.length + ' ' + ((window.BO_I18N && BO_I18N.grid_selected) || 'selected') : ''); }
  };
  if (ajax) {
    opts.remoteOperations = { paging: true, sorting: true, filtering: true, grouping: true, groupPaging: false, summary: false };
    opts.dateSerializationFormat = "yyyy-MM-ddTHH:mm:ss";
  } else {
    opts.keyExpr = boGrid.cfg.key;
  }
  if (boGrid.cfg.reorder) {
    opts.rowDragging = { allowReordering: true, showDragIcons: true, dropFeedbackMode: "push", onDragStart: boGrid.dragStart, onReorder: boGrid.reorder };
  }
  boGrid.grid = $("#gridContainer").dxDataGrid(opts).dxDataGrid("instance");
  $('#bo_bulk_btn').on('click', boGrid.bulk);
  // the bulk form is only sent by boGrid.bulk() (form.submit() fires no submit event): Enter never posts it
  $('#bo_list_form').on('submit', function (e) { e.preventDefault(); return false; });
});
</script>
