<?php
$content_name = "priviledge_role_permissions";
$id_content = "22";
if ($this->session->userdata("level") == 0) {
    $cond = array("id_role" => $this->session->userdata("id_role"), "id_content" => $id_content);
    $write_priv = $this->fct->getonecell("priviledge", "write_p", $cond);
} else {
    $write_priv = 1;
}
if ($write_priv != 1) {
    redirect(base_url() . "back_office/home/dashboard");
}
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span2">
            <? $this->load->view("back_office/includes/left_box"); ?>
        </div>
        <div class="span10">
            <div class="span10-fluid">
                <ul class="breadcrumb">
                    <li><a href="<?= base_url(); ?>back_office/priviledge_role_permissions">priviledge role permissions</a> <span class="divider">/</span></li>
                    <li class="active"><?= $title; ?></li>
                </ul>
            </div>
            <div class="hundred pull-left">
                <form action="<?= base_url(); ?>back_office/priviledge_role_permissions/submit" method="post" class="middle-forms" enctype="multipart/form-data">
                    <p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
                        <?
                        if (isset($id)) { ?>
                            <input type="hidden" name="id" value="<?= $id; ?>" />
                        <?
                        } else {
                            $info["role"] = "";
                            $info["permissions"] = "";
                            $info["read_p"] = "";
                            $info["write_p"] = "";
                            $info["delete_p"] = "";
                        }
                        ?>
                        <? if ($this->session->userdata("success_message")) { ?>
                    <div class="alert alert-success">
                        <?= $this->session->userdata("success_message"); ?>
                    </div>
                <? } ?>
                <? if ($this->session->userdata("error_message")) { ?>
                    <div class="alert alert-error">
                        <?= $this->session->userdata("error_message"); ?>
                    </div>
                <? } ?>
                </p>
                <fieldset>
                    <legend></legend>

                    <label class="field-title">Role&nbsp;<em>*</em>:
                    </label>
                    <label>

                        <? $items = $this->fct->getAll("role", "sort_order"); ?>
                        <select name="role" class="input-xxlarge">
                            <option value=""> - select role - </option>
                            <? foreach ($items as $valll) { ?>
                                <option value="<?= $valll["id_role"]; ?>" <? if (isset($id)) {
                                                                                if ($info["id_role"] == $valll["id_role"]) {
                                                                                    echo 'selected="selected"';
                                                                                }
                                                                            } ?>><?= $valll["title"]; ?></option>
                            <? } ?>
                        </select>
                        <?= form_error("role", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Permissions&nbsp;<em>*</em>:
                    </label>
                    <label>

                        <? $items = $this->fct->getAll("permissions", "sort_order"); ?>
                        <select name="permissions" class="input-xxlarge">
                            <option value=""> - select permissions - </option>
                            <? foreach ($items as $valll) { ?>
                                <option value="<?= $valll["id_permissions"]; ?>" <? if (isset($id)) {
                                                                                        if ($info["id_permissions"] == $valll["id_permissions"]) {
                                                                                            echo 'selected="selected"';
                                                                                        }
                                                                                    } ?>><?= $valll["title"]; ?></option>
                            <? } ?>
                        </select>
                        <?= form_error("permissions", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Read_p
                    </label>
                    <label>
                        <select name="read_p" class="input-xxlarge">
                            <option value="0"> - select option - </option>
                            <option value="1" <? if ($info["read_p"] == 1) echo 'selected="selected"'; ?>> Active </option>
                            <option value="0" <? if ($info["read_p"] == 0) echo 'selected="selected"'; ?>> InActive </option>
                        </select>
                        <?= form_error("read_p", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Write_p
                    </label>
                    <label>
                        <select name="write_p" class="input-xxlarge">
                            <option value="0"> - select option - </option>
                            <option value="1" <? if ($info["write_p"] == 1) echo 'selected="selected"'; ?>> Active </option>
                            <option value="0" <? if ($info["write_p"] == 0) echo 'selected="selected"'; ?>> InActive </option>
                        </select>
                        <?= form_error("write_p", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Delete_p
                    </label>
                    <label>
                        <select name="delete_p" class="input-xxlarge">
                            <option value="0"> - select option - </option>
                            <option value="1" <? if ($info["delete_p"] == 1) echo 'selected="selected"'; ?>> Active </option>
                            <option value="0" <? if ($info["delete_p"] == 0) echo 'selected="selected"'; ?>> InActive </option>
                        </select>
                        <?= form_error("delete_p", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    </ol>
                </fieldset>
                <p class="align-right">
                    <input type="image" src="<?= base_url(); ?>images/bt-send-form.png" value="Save Changes" class="btn btn-primary" />
                </p>
                <span class="clearFix">&nbsp;</span>
                </form>
            </div>
        </div>
    </div>
    <? $this->session->unset_userdata("success_message"); ?>
    <? $this->session->unset_userdata("error_message"); ?>