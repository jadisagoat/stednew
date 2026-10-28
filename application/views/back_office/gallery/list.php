<?php
// 1. privilege block (content_type 37 = gallery, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("37");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
// rows = images of the course kept in the session (gallery::lists($id_course) sets "id_course")
$rows = (isset($info) && is_array($info)) ? $info : array();
// parent chain: Courses › <course title>
$bo_id_course = (int) $this->session->userdata("id_course");
$bo_chain = array(array('label' => bo_module_label('course'), 'url' => 'course'));
if ($bo_id_course > 0) {
    $bo_course = trim((string) $this->fct->getonecell("course", "title", array("id_course" => $bo_id_course)));
    if ($bo_course !== '') $bo_chain[] = array('label' => $bo_course);
}
// legacy sort link: index/title
$bo_order = $this->uri->segment(3) === 'lists' ? $this->uri->segment(5) : $this->uri->segment(4);
// 3. grid config (legacy columns: title, image (+ delete image), course)
$grid = array(
'module' => 'gallery', 'key' => 'id_gallery', 'rows' => $rows, 'title' => $title,
'subtitle' => $bo_chain,
'order' => $bo_order,
'columns' => array('title', 'image', 'id_course'),
'types' => array('image' => 'image'),
'files' => 'uploads/gallery/', 'thumb' => '600x480',
'lookups' => array('id_course' => array('course', 'title', 'id_course')), 'lookups_empty' => bo_t('grid_not_available'),
'widths' => array('image' => 130),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv, 'extra' => ($write_priv == 1) ? array(
    array('label' => bo_t('site_delete_image'), 'url' => 'gallery/delete_image/image/{image}/{id}', 'icon' => 'bx-image-alt',
          'confirm' => bo_t('grid_confirm_delete_image'), 'if' => 'image'),
) : array()),
'add' => $write_priv, 'add_label' => bo_module_label('gallery', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
