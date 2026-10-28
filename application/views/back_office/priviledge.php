<script>
    $(function() {
        $('.checkAllAuto').click(function() {
            var id = this.id;
            $("INPUT.checkAllAuto" + id + "[type='checkbox']").attr('checked', $('.checkAllAuto_' + id).is(':checked'));
        });
    });
</script>
<?
// set privildge 
// $user_id = $id;
// $content_type = $this->fct->getAll_1('content_type', 'sort_order');
// $cond = array('id_user' => $user_id);
// $priviledge = $this->fct->getAll_cond('priviledge', 'sort_order', $cond);
// $priv_array = array();
// foreach ($priviledge as $val) {
//     $priv_array[] = $val["id_content"];
// }


// foreach ($content_type as $val) {
//     if (in_array($val["id_content"], $priv_array)) {
//         // do nothing	
//     } else {
//         $_data = array(
//             'id_user' => $user_id,
//             'id_content' => $val["id_content"],
//             'read_p' => 1,
//             'write_p' => 1,
//             'delete_p' => 1
//         );
//         $this->db->insert('priviledge', $_data);
//     }
// }

// if (in_array(1000, $priv_array)) {
//     // do nothing	
// } else {
//     $_data = array(
//         'id_user' => $user_id,
//         'id_content' => 1000,
//         'read_p' => 1,
//         'write_p' => 1,
//         'delete_p' => 1
//     );
//     $this->db->insert('priviledge', $_data);
// }
// if (in_array(1001, $priv_array)) {
//     // do nothing	
// } else {
//     $_data = array(
//         'id_user' => $user_id,
//         'id_content' => 1001,
//         'read_p' => 1,
//         'write_p' => 1,
//         'delete_p' => 1
//     );
//     $this->db->insert('priviledge', $_data);
// }

// if (in_array(1002, $priv_array)) {
//     // do nothing
// } else {
//     $_data = array(
//         'id_user' => $user_id,
//         'id_content' => 1002,
//         'read_p' => 1,
//         'write_p' => 1,
//         'delete_p' => 1
//     );
//     $this->db->insert('priviledge', $_data);
// }

// if (in_array(1003, $priv_array)) {
//     // do nothing	
// } else {
//     $_data = array(
//         'id_user' => $user_id,
//         'id_content' => 1003,
//         'read_p' => 1,
//         'write_p' => 1,
//         'delete_p' => 1
//     );
//     $this->db->insert('priviledge', $_data);
// }

?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span2">
            <? $this->load->view('back_office/includes/left_box'); ?>
        </div>

        <div class="span10">
            <div class="span10-fluid">
                <ul class="breadcrumb">
                    <li>
                        <a href="<?= base_url(); ?>back_office/users">List Users</a>
                        <span class="divider">/</span>
                    </li>
                    <li class="active"><?= $title; ?></li>
                </ul>
            </div>
            <div class="hundred pull-left">
                <form action="<?= base_url(); ?>back_office/users/submit_privi" method="post" class="middle-forms">
                    <?
                    if (isset($id)) {
                        echo '<input type="hidden" name="id" value="' . $id . '" />';
                    } else {
                        $info = array('name' => '', 'email' => '', 'phone' => '', 'address' => '', 'username' => '', 'password' => '');
                    }
                    ?>
                    <? if ($this->session->userdata('success_message')) { ?>
                        <div class="alert alert-success">
                            <?= $this->session->userdata('success_message'); ?>
                        </div>
                    <? } ?>
                    <? if ($this->session->userdata('error_message')) { ?>
                        <div class="alert alert-error">
                            <?= $this->session->userdata('error_message'); ?>
                        </div>
                    <? } ?>
                    </p>
                    <fieldset>
                        <label class="field-title"><input type="checkbox" id="checkAllAuto" checked="checked" />&nbsp;Select All</label>
                        <ul class="nav nav-list">
                            <li class="even">
                                <? $cond = array('id_user' => $id, 'id_content' => 1000);
                                $priv_item1 = $this->fct->getonerow('priviledge', $cond); ?>
                                <div class="well well-small">
                                    <input type="checkbox" class="checkAllAuto checkAllAuto_0" id="0" name="cehcklist[0]" value="1000" checked="checked" />&nbsp;&nbsp;
                                    <span class="lead">USERS SECTION :&nbsp;&nbsp;</span>
                                    <input type="checkbox" class="checkAllAuto0" name="read0" value="1" <? if ($priv_item1["read_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Read&nbsp;&nbsp;
                                    <input type="checkbox" class="checkAllAuto0" name="write0" value="1" <? if ($priv_item1["write_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Write&nbsp;&nbsp;
                                    <input type="checkbox" class="checkAllAuto0" name="delete0" value="1" <? if ($priv_item1["delete_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Delete&nbsp;&nbsp;
                                    <input type="hidden" value="1000" name="section0" />
                                </div>
                            </li>
                            <li>
                                <? $cond = array('id_user' => $id, 'id_content' => 1001);
                                $priv_item1 = $this->fct->getonerow('priviledge', $cond); ?>
                                <div class="well well-small">
                                    <input type="checkbox" class="checkAllAuto checkAllAuto_1" id="1" name="cehcklist[1]" value="1001" checked="checked" />&nbsp;&nbsp;
                                    <span class="lead">VIEWS SECTION :&nbsp;&nbsp;</span>
                                    <input type="checkbox" class="checkAllAuto1" name="read1" value="1" <? if ($priv_item1["read_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Read&nbsp;&nbsp;
                                    <input type="checkbox" class="checkAllAuto1" name="write1" value="1" <? if ($priv_item1["write_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Write&nbsp;&nbsp;
                                    <input type="checkbox" class="checkAllAuto1" name="delete1" value="1" <? if ($priv_item1["delete_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Delete&nbsp;&nbsp;
                                    <input type="hidden" value="1001" name="section1" />
                                </div>
                            </li>
                            <li>
                                <? $cond = array('id_user' => $id, 'id_content' => 1002);
                                $priv_item1 = $this->fct->getonerow('priviledge', $cond); ?>
                                <div class="well well-small">
                                    <input type="checkbox" class="checkAllAuto checkAllAuto_2" id="2" name="cehcklist[2]" value="1002" checked="checked" />&nbsp;&nbsp;
                                    <span class="lead">MODULES SECTION :&nbsp;&nbsp;</span>
                                    <input type="checkbox" class="checkAllAuto2" name="read2" value="1" <? if ($priv_item1["read_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Read&nbsp;&nbsp;
                                    <input type="checkbox" class="checkAllAuto2" name="write2" value="1" <? if ($priv_item1["write_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Write&nbsp;&nbsp;
                                    <input type="checkbox" class="checkAllAuto2" name="delete2" value="1" <? if ($priv_item1["delete_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Delete&nbsp;&nbsp;
                                    <input type="hidden" value="1002" name="section2" />
                                </div>
                            </li>
                            <li>
                                <? $cond = array('id_user' => $id, 'id_content' => 1003);
                                $priv_item1 = $this->fct->getonerow('priviledge', $cond); ?>
                                <div class="well well-small">
                                    <input type="checkbox" class="checkAllAuto checkAllAuto_3" id="3" name="cehcklist[3]" value="1003" checked="checked" />&nbsp;&nbsp;
                                    <span class="lead">RECYCLE BIN SECTION :&nbsp;&nbsp;</span>
                                    <input type="checkbox" class="checkAllAuto3" name="read3" value="1" <? if ($priv_item1["read_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Read
                                    &nbsp;&nbsp;
                                    <input type="checkbox" class="checkAllAuto3" name="write3" value="1" <? if ($priv_item1["write_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Write
                                    &nbsp;&nbsp;
                                    <input type="checkbox" class="checkAllAuto3" name="delete3" value="1" <? if ($priv_item1["delete_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Delete
                                    <input type="hidden" value="1003" name="section3" />
                                </div>
                            </li>
                            <?
                            $i = 3;
                            foreach ($attr as $val) {
                                if ($val["position"] != 0) {
                                    $i++;
                                    $cond = array('id_user' => $id, 'id_content' => $val["id_content"]);
                                    $priv_item = $this->fct->getonerow('priviledge', $cond);
                            ?>
                                    <li>
                                        <div class="well well-small">
                                            <input type="checkbox" class="checkAllAuto checkAllAuto_<?= $i; ?>" id="<?= $i; ?>" value="<?= $i; ?>" name="cehcklist[<?= $i; ?>]" checked="checked" />&nbsp;&nbsp;
                                            <span class="lead"><?= strtoupper($val["name"]); ?> SECTION :&nbsp;&nbsp;</span>
                                            <input type="checkbox" class="checkAllAuto<?= $i; ?>" name="read<?= $i; ?>" value="1" <? if ($priv_item["read_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Read
                                            &nbsp;&nbsp;
                                            <input type="checkbox" class="checkAllAuto<?= $i; ?>" name="write<?= $i; ?>" value="1" <? if ($priv_item["write_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Write
                                            &nbsp;&nbsp;
                                            <input type="checkbox" class="checkAllAuto<?= $i; ?>" name="delete<?= $i; ?>" value="1" <? if ($priv_item["delete_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Delete
                                            &nbsp;&nbsp;
                                            <input type="hidden" value="<?= $val["id_content"]; ?>" name="section<?= $i; ?>" />
                                        </div>
                                    </li>
                            <? }
                            } ?>
                        </ul>
                    </fieldset>
                    <p class="pull-left"><input type="image" src="<?= base_url(); ?>images/bt-send-form.png" class="btn btn-primary" /></p>
                </form>
            </div>
        </div>
    </div>
    <style>
        ul.nav-list li {
            width: 100%;
            margin-bottom: 10px;
            ;
        }
    </style>
    <? $this->session->unset_userdata('success_message'); ?>
    <? $this->session->unset_userdata('error_message'); ?>