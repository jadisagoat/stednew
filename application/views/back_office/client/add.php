<?php
$content_name = "client";
$id_content = "4";
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
                    <li><a href="<?= base_url(); ?>back_office/client">client</a> <span class="divider">/</span></li>
                    <li class="active"><?= $title; ?></li>
                </ul>
            </div>
            <div class="hundred pull-left">
                <form action="<?= base_url(); ?>back_office/client/submit" method="post" class="middle-forms" enctype="multipart/form-data">
                    <p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
                        <?
                        if (isset($id)) { ?>
                            <input type="hidden" name="id" value="<?= $id; ?>" />
                        <?
                        } else {
                            $info["title"] = "";
                            $info["email"] = "";
                            $info["profession"] = "";
                            $info["nationality"] = "";
                            $info["resident"] = "";
                            $info["phone"] = "";
                            $info["gender"] = "";
                            $info["company"] = "";
                            $info["password"] = "";
                            $info["role"] = "";
                            $info["image"] = "";
                            $info["user"] = "";
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

                    <label class="field-title">Title&nbsp;<em>*</em>:
                    </label>
                    <label>
                        <input type="text" class="input-xxlarge" name="title" value="<?= set_value("title", $info["title"]); ?>" />
                        <?= form_error("title", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Email
                    </label>
                    <label>
                        <input type="text" class="input-xxlarge" name="email" value="<?= set_value("email", $info["email"]); ?>" />
                        <?= form_error("email", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Profession
                    </label>
                    <label>
                        <input type="text" class="input-xxlarge" name="profession" value="<?= set_value("profession", $info["profession"]); ?>" />
                        <?= form_error("profession", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Nationality
                    </label>
                    <label>

                        <? $items = $this->fct->getAll("countries", "sort_order"); ?>
                        <select name="nationality" class="input-xxlarge">
                            <option value=""> - select countries - </option>
                            <? foreach ($items as $valll) { ?>
                                <option value="<?= $valll["id_countries"]; ?>" <? if (isset($id)) {
                                                                                    if ($info["id_countries"] == $valll["id_countries"]) {
                                                                                        echo 'selected="selected"';
                                                                                    }
                                                                                } ?>><?= $valll["title"]; ?></option>
                            <? } ?>
                        </select>
                        <?= form_error("nationality", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Resident
                    </label>
                    <label>
                        <input type="text" class="input-xxlarge" name="resident" value="<?= set_value("resident", $info["resident"]); ?>" />
                        <?= form_error("resident", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Phone
                    </label>
                    <label>
                        <input type="text" class="input-xxlarge" name="phone" value="<?= set_value("phone", $info["phone"]); ?>" />
                        <?= form_error("phone", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Gender
                    </label>
                    <label>

                        <? $items = $this->fct->getAll("gender", "sort_order"); ?>
                        <select name="gender" class="input-xxlarge">
                            <option value=""> - select gender - </option>
                            <? foreach ($items as $valll) { ?>
                                <option value="<?= $valll["id_gender"]; ?>" <? if (isset($id)) {
                                                                                if ($info["id_gender"] == $valll["id_gender"]) {
                                                                                    echo 'selected="selected"';
                                                                                }
                                                                            } ?>><?= $valll["title"]; ?></option>
                            <? } ?>
                        </select>
                        <?= form_error("gender", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Company
                    </label>
                    <label>
                        <input type="text" class="input-xxlarge" name="company" value="<?= set_value("company", $info["company"]); ?>" />
                        <?= form_error("company", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Password
                    </label>
                    <label>
                        <input type="text" class="input-xxlarge" name="password" value="<?= set_value("password", $info["password"]); ?>" />
                        <?= form_error("password", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Role
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
                    <label class="field-title">Image
                    </label>
                    <label>
                        <input type="file" class="input-xxlarge" name="image" />
                        <span>
                            <?
                            if ($info["image"] != "") { ?>
                                <a href="<?= base_url(); ?>uploads/client/<?= $info["image"]; ?>" class="blue gallery" title="<?= $info["title"]; ?>">
                                    show image
                                </a>
                                &nbsp;&nbsp;&nbsp;
                                <a class="cur" onclick="if(confirm('Are you sure you want to delete this image ?')){ window.location=' <?= base_url(); ?>back_office/client/delete_image/image/<?= $info["image"]; ?>/<?= $info["id_client"] ?> ' } ">
                                    <img src="<?= base_url(); ?>images/delete.png" /></a>
                            <? } else {
                                echo "<span class='blue'>No Image Available</span>";
                            } ?>
                        </span>
                        <?= form_error("image", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">User
                    </label>
                    <label>

                        <? $items = $this->fct->getAll("user", "sort_order"); ?>
                        <select name="user" class="input-xxlarge">
                            <option value=""> - select user - </option>
                            <? foreach ($items as $valll) { ?>
                                <option value="<?= $valll["id_user"]; ?>" <? if (isset($id)) {
                                                                                if ($info["id_user"] == $valll["id_user"]) {
                                                                                    echo 'selected="selected"';
                                                                                }
                                                                            } ?>><?= $valll["title"]; ?></option>
                            <? } ?>
                        </select>
                        <?= form_error("user", "<span class='text-error'>", "</span>"); ?>
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