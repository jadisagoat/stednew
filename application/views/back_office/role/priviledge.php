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
$id_role = $id;
$content_type = $this->fct->getAll_1('content_type', 'sort_order');
$cond = array('id_role' => $id_role);
$priviledge = $this->fct->getAll_cond('priviledge', 'sort_order', $cond);
$priv_array = array();
foreach ($priviledge as $val) {
    $priv_array[] = $val["id_content"];
}

// echo "<pre>";
// print_r($priv_array);
// exit();

foreach ($content_type as $val) {
    if (in_array($val["id_content"], $priv_array)) {
        // do nothing	
    } else {
        $_data = array(
            'id_role' => $id_role,
            'id_content' => $val["id_content"],
            'read_p' => 0,
            'write_p' => 0,
            'delete_p' => 0
        );
        $this->db->insert('priviledge', $_data);
    }
}

if (in_array(1000, $priv_array)) {
    // do nothing	
} else {
    $_data = array(
        'id_role' => $id_role,
        'id_content' => 1000,
        'read_p' => 0,
        'write_p' => 0,
        'delete_p' => 0
    );
    $this->db->insert('priviledge', $_data);
}
if (in_array(1001, $priv_array)) {
    // do nothing	
} else {
    $_data = array(
        'id_role' => $id_role,
        'id_content' => 1001,
        'read_p' => 0,
        'write_p' => 0,
        'delete_p' => 0
    );
    $this->db->insert('priviledge', $_data);
}

if (in_array(1002, $priv_array)) {
    // do nothing
} else {
    $_data = array(
        'id_role' => $id_role,
        'id_content' => 1002,
        'read_p' => 0,
        'write_p' => 0,
        'delete_p' => 0
    );
    $this->db->insert('priviledge', $_data);
}

if (in_array(1003, $priv_array)) {
    // do nothing	
} else {
    $_data = array(
        'id_role' => $id_role,
        'id_content' => 1003,
        'read_p' => 0,
        'write_p' => 0,
        'delete_p' => 0
    );
    $this->db->insert('priviledge', $_data);
}

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
                        <a href="<?= base_url(); ?>back_office/role">List Roles</a>
                        <span class="divider">/</span>
                    </li>
                    <li class="active"><?= $title; ?></li>
                </ul>
            </div>
            <div class="hundred pull-left">
                <form action="<?= base_url(); ?>back_office/role/submit_privi" method="post" class="middle-forms">
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
                        <label class="field-title"><input type="checkbox" id="checkAllAuto" "checked=checkedayman" />&nbsp;Select All</label>
                        <ul class="nav nav-list">
                            <!-- <li class="even">
                                <? $cond = array('id_role' => $id, 'id_content' => 1000);
                                $priv_item1 = $this->fct->getonerow('priviledge', $cond); ?>
                                <div class="well well-small">
                                    <input type="checkbox" class="checkAllAuto checkAllAuto_1000" id="1000" name="cehcklist[1000]" value="1" "checked=checkedayman" />&nbsp;&nbsp;
                                    <span class="lead">USERS SECTION :&nbsp;&nbsp;</span>
                                    <input type="checkbox" class="checkAllAuto1000" name="read1000" value="1" <? if ($priv_item1["read_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Read&nbsp;&nbsp;
                                    <input type="checkbox" class="checkAllAuto1000" name="write1000" value="1" <? if ($priv_item1["write_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Write&nbsp;&nbsp;
                                    <input type="checkbox" class="checkAllAuto1000" name="delete1000" value="1" <? if ($priv_item1["delete_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Delete&nbsp;&nbsp;
                                    <input type="hidden" value="1000" name="section1000" />
                                </div>
                            </li>

                            <li class="even">
                                <? $cond = array('id_role' => $id, 'id_content' => 1001);
                                $priv_item1 = $this->fct->getonerow('priviledge', $cond); ?>
                                <div class="well well-small">
                                    <input type="checkbox" class="checkAllAuto checkAllAuto_1001" id="1001" name="cehcklist[1001]" value="1001" "checked=checkedayman" />&nbsp;&nbsp;
                                    <span class="lead">VIEWS SECTION :&nbsp;&nbsp;</span>
                                    <input type="checkbox" class="checkAllAuto1001" name="read1001" value="1" <? if ($priv_item1["read_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Read&nbsp;&nbsp;
                                    <input type="checkbox" class="checkAllAuto1001" name="write1001" value="1" <? if ($priv_item1["write_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Write&nbsp;&nbsp;
                                    <input type="checkbox" class="checkAllAuto1001" name="delete1001" value="1" <? if ($priv_item1["delete_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Delete&nbsp;&nbsp;
                                    <input type="hidden" value="1001" name="section1001" />
                                </div>
                            </li>

                            <li>
                                <? $cond = array('id_role' => $id, 'id_content' => 1002);
                                $priv_item1 = $this->fct->getonerow('priviledge', $cond); ?>
                                <div class="well well-small">
                                    <input type="checkbox" class="checkAllAuto checkAllAuto_1002" id="1002" name="cehcklist[1002]" value="1002" "checked=checkedayman" />&nbsp;&nbsp;
                                    <span class="lead">MODULES SECTION :&nbsp;&nbsp;</span>
                                    <input type="checkbox" class="checkAllAuto1002" name="read1002" value="1" <? if ($priv_item1["read_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Read&nbsp;&nbsp;
                                    <input type="checkbox" class="checkAllAuto1002" name="write1002" value="1" <? if ($priv_item1["write_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Write&nbsp;&nbsp;
                                    <input type="checkbox" class="checkAllAuto1002" name="delete1002" value="1" <? if ($priv_item1["delete_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Delete&nbsp;&nbsp;
                                    <input type="hidden" value="1002" name="section1002" />
                                </div>
                            </li>
                            <li>
                                <? $cond = array('id_role' => $id, 'id_content' => 1003);
                                $priv_item1 = $this->fct->getonerow('priviledge', $cond); ?>
                                <div class="well well-small">
                                    <input type="checkbox" class="checkAllAuto checkAllAuto_1003" id="1003" name="cehcklist[1003]" value="1003" "checked=checkedayman" />&nbsp;&nbsp;
                                    <span class="lead">RECYCLE BIN SECTION :&nbsp;&nbsp;</span>
                                    <input type="checkbox" class="checkAllAuto1003" name="read3" value="1" <? if ($priv_item1["read_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Read
                                    &nbsp;&nbsp;
                                    <input type="checkbox" class="checkAllAuto1003" name="write3" value="1" <? if ($priv_item1["write_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Write
                                    &nbsp;&nbsp;
                                    <input type="checkbox" class="checkAllAuto1003" name="delete3" value="1" <? if ($priv_item1["delete_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Delete
                                    <input type="hidden" value="1003" name="section1003" />
                                </div>
                            </li> -->
                            <?
                            $i = 3;
                            foreach ($attr as $val) {
                                if ($val["position"] != 0) {
                                    $i++;
                                    $cond = array('id_role' => $id, 'id_content' => $val["id_content"]);
                                    $priv_item = $this->fct->getonerow('priviledge', $cond);
                            ?>
                                    <li>
                                        <div class="well well-small">
                                            <input type="checkbox" class="checkAllAuto checkAllAuto_<?= $val["id_content"]; ?>" id="<?= $val["id_content"]; ?>" value="<?= $val["id_content"]; ?>" name="cehcklist[<?= $val["id_content"]; ?>]" "checked=checkedayman" />&nbsp;&nbsp;
                                            <span class="lead"><?= strtoupper($val["name"]); ?> SECTION :&nbsp;&nbsp;</span>
                                            <input type="checkbox" class="checkAllAuto<?= $val["id_content"]; ?>" name="read<?= $val["id_content"]; ?>" value="1" <? if ($priv_item["read_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Read
                                            &nbsp;&nbsp;
                                            <input type="checkbox" class="checkAllAuto<?= $val["id_content"]; ?>" name="write<?= $val["id_content"]; ?>" value="1" <? if ($priv_item["write_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Write
                                            &nbsp;&nbsp;
                                            <input type="checkbox" class="checkAllAuto<?= $val["id_content"]; ?>" name="delete<?= $val["id_content"]; ?>" value="1" <? if ($priv_item["delete_p"] == 1) echo 'checked="checked"'; ?> />&nbsp;Delete
                                            &nbsp;&nbsp;
                                            <input type="hidden" value="<?= $val["id_content"]; ?>" name="section<?= $val["id_content"]; ?>" />
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