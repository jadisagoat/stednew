<?php
// 1. privilege block (content_type 23 = user, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("23");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
// rows: user::index() passes the whole user table; password / hashsendcode never reach the page
// (dxgrid only sends the listed columns, and always strips password-like fields)
$rows = (isset($info) && is_array($info)) ? $info : array();
$bo_order = $this->uri->segment(4);
// 2. grid config (legacy columns: name, username, signature, global role, image, active, gender)
$grid = array(
'module' => 'user', 'key' => 'id_user', 'rows' => $rows, 'title' => $title,
'order' => $bo_order,
'columns' => array('name', 'username', 'signature', 'id_role', 'image', 'active', 'id_gender'),
'labels' => array('id_role' => bo_label('role'), 'id_gender' => bo_label('gender')),
'types' => array('signature' => 'image', 'image' => 'image', 'active' => 'status'),
'files' => 'uploads/user/', 'thumb' => '',
'lookups' => array('id_role' => array('role', 'title', 'id_role'), 'id_gender' => array('gender', 'title', 'id_gender')),
'lookups_empty' => '--',
'widths' => array('signature' => 120, 'image' => 100),
'strip' => array('password', 'hashsendcode'),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_module_label('user', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 3. render
$this->load->view('back_office/includes/dxgrid', $grid);

// 4. legacy extra: daily user registrations of a month (bar chart, data from user/get_population_data)
$bo_month = date('m');
?>
<div class="card bo-grid-card mt-4" id="bo_user_reg">
<div class="card-body">
<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
<h5 class="mb-0" id="bo_user_reg_title"><?= bo_t('grid_user_registrations', 'User Registrations') ?></h5>
<select id="monthFilter" class="form-select w-auto" aria-label="<?= htmlspecialchars(bo_t('grid_select_month', 'Select Month'), ENT_QUOTES, 'UTF-8') ?>">
<option value=""><?= bo_t('grid_select_month', 'Select Month') ?></option>
<?php for ($m = 1; $m <= 12; $m++) { $mv = sprintf('%02d', $m); ?>
<option value="<?= $mv ?>"<?= $mv === $bo_month ? ' selected' : '' ?>><?= $mv ?></option>
<?php } ?>
</select>
</div>
<div id="chart" style="height:400px;"></div>
</div>
</div>
<script>
$(function () {
  var title = <?= bo_js(bo_t('grid_user_registrations', 'User Registrations')) ?>;
  var lang = window.BO_LANG || 'en', year = new Date().getFullYear();
  function monthName(m) {
    try { return new Intl.DateTimeFormat(lang, { month: 'long' }).format(new Date(2000, m - 1, 1)); } catch (e) { return String(m); }
  }
  // month names in the current back-office language
  $('#monthFilter option').each(function () { if (this.value) $(this).text(monthName(parseInt(this.value, 10))); });
  var chart = $('#chart').dxChart({
    dataSource: [],
    rtlEnabled: window.BO_RTL === true,
    legend: { visible: false },
    // one bar per day with registrations; get_population_data returns that month of EVERY year, so the axis is
    // discrete (days in date order, labelled dd/MM/yyyy) as in the legacy chart
    series: { type: 'bar', argumentField: 'day', valueField: 'total', color: '#696cff' },
    argumentAxis: { argumentType: 'string', discreteAxisDivisionMode: 'crossLabels', label: { overlappingBehavior: 'rotate', rotationAngle: -45 } },
    valueAxis: { allowDecimals: false, label: { format: 'decimal' } },
    tooltip: { enabled: true, customizeTooltip: function (a) { return { text: a.argumentText + ': ' + a.valueText }; } }
  }).dxChart('instance');
  function load(month) {
    var name = month ? monthName(parseInt(month, 10)) : '';
    $('#bo_user_reg_title').text(title + (name ? ' - ' + name : ''));
    if (!month) { chart.option('dataSource', []); return; }
    $.ajax({ url: <?= bo_js(base_url() . 'back_office/user/get_population_data') ?>, data: { month: month, year: year }, dataType: 'json', cache: false })
      .done(function (r) {
        var data = $.map((r && r.data) || [], function (it) {
          var p = String(it.created_day).split('-');
          return p.length === 3 ? { sortKey: String(it.created_day), day: p[2] + '/' + p[1] + '/' + p[0], total: parseFloat(it.total_exams) || 0 } : null;
        });
        data.sort(function (a, b) { return a.sortKey < b.sortKey ? -1 : (a.sortKey > b.sortKey ? 1 : 0); });
        chart.option('dataSource', data);
      })
      .fail(function () { chart.option('dataSource', []); });
  }
  $('#monthFilter').on('change', function () { load($(this).val()); });
  load($('#monthFilter').val());
});
</script>
<?php
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
