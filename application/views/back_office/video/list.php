<?php
// 1. privilege block (content_type 38 = video, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("38");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
// rows = videos of the course kept in the session (video::lists($id_course) sets "id_course")
$rows = (isset($info) && is_array($info)) ? $info : array();
// 2. YouTube cell: the field holds a video id (or a full URL) => link to the video
foreach ($rows as $i => $r) {
    $yt = trim((string) $r["youtube"]);
    $rows[$i]["youtube_url"] = ($yt === '') ? '' : (preg_match('#^https?://#i', $yt) ? $yt : 'https://www.youtube.com/watch?v=' . rawurlencode($yt));
}
// parent chain (legacy breadcrumb: course › <course title> / List video)
$bo_id_course = (int) $this->session->userdata("id_course");
$bo_chain = array(array('label' => bo_module_label('course'), 'url' => 'course'));
if ($bo_id_course > 0) {
    $bo_course = trim((string) $this->fct->getonecell("course", "title", array("id_course" => $bo_id_course)));
    if ($bo_course !== '') $bo_chain[] = array('label' => $bo_course);
}
// legacy sort links: index/title | index/youtube
$bo_order = $this->uri->segment(3) === 'lists' ? $this->uri->segment(5) : $this->uri->segment(4);
// 3. grid config (legacy columns: title, video file (+ delete file), image (+ delete image), course, youtube)
$grid = array(
'module' => 'video', 'key' => 'id_video', 'rows' => $rows, 'title' => $title,
'subtitle' => $bo_chain,
'order' => $bo_order,
'columns' => array('title', 'video_file', 'image', 'id_course', 'youtube'),
'types' => array('video_file' => 'file', 'image' => 'image', 'youtube' => 'link'),
'links' => array('youtube' => array('url' => '{youtube_url}', 'target' => '_blank')),
'files' => 'uploads/video/', 'thumb' => '120x90',
'lookups' => array('id_course' => array('course', 'title', 'id_course')), 'lookups_empty' => bo_t('grid_not_available'),
'widths' => array('image' => 110, 'video_file' => 130),
'hiding' => array('title' => 200, 'image' => 190, 'video_file' => 180, 'youtube' => 170, 'id_course' => 160),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv, 'extra' => ($write_priv == 1) ? array(
    array('label' => bo_t('ct_delete_file'), 'url' => 'video/delete_image/video_file/{video_file}/{id}', 'icon' => 'bx-file',
          'confirm' => bo_t('grid_confirm_delete_file'), 'if' => 'video_file'),
    array('label' => bo_t('site_delete_image'), 'url' => 'video/delete_image/image/{image}/{id}', 'icon' => 'bx-image-alt',
          'confirm' => bo_t('grid_confirm_delete_image'), 'if' => 'image'),
) : array()),
'add' => $write_priv, 'add_label' => bo_module_label('video', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
