<?php
$content_name = "category";
$id_content = "5";
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
                    <li><a href="<?= base_url(); ?>back_office/category">category</a> <span class="divider">/</span></li>
                    <li class="active"><?= $title; ?></li>
                </ul>
            </div>
            <div class="hundred pull-left">
                <form action="<?= base_url(); ?>back_office/category/submit" method="post" class="middle-forms" enctype="multipart/form-data">
                    <p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
                        <?
                        if (isset($id)) { ?>
                            <input type="hidden" name="id" value="<?= $id; ?>" />
                        <?
                        } else {
                            $info["title"] = "";
                            $info["group_category"] = "";
                            $info["active"] = "";
                            $info["is_in_footer"] = "";
                            $info["image"] = "";
                            $info["description"] = "";
                            $info["section"] = "";
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
                    <label class="field-title">group category
                    </label>
                    <label>

                        <? $items = $this->fct->getAll("group_category", "sort_order"); ?>
                        <select name="group_category" class="input-xxlarge">
                            <option value=""> - select group category - </option>
                            <? foreach ($items as $valll) { ?>
                                <option value="<?= $valll["id_group_category"]; ?>" <? if (isset($id)) {
                                                                                        if ($info["id_group_category"] == $valll["id_group_category"]) {
                                                                                            echo 'selected="selected"';
                                                                                        }
                                                                                    } ?>><?= $valll["title"]; ?></option>
                            <? } ?>
                        </select>
                        <?= form_error("group_category", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Active
                    </label>
                    <label>
                        <select name="active" class="input-xxlarge">
                            <option value="0"> - select option - </option>
                            <option value="1" <? if ($info["active"] == 1) echo 'selected="selected"'; ?>> Active </option>
                            <option value="0" <? if ($info["active"] == 0) echo 'selected="selected"'; ?>> InActive </option>
                        </select>
                        <?= form_error("active", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Is in footer
                    </label>
                    <label>
                        <select name="is_in_footer" class="input-xxlarge">
                            <option value="0"> - select option - </option>
                            <option value="1" <? if ($info["is_in_footer"] == 1) echo 'selected="selected"'; ?>> Active </option>
                            <option value="0" <? if ($info["is_in_footer"] == 0) echo 'selected="selected"'; ?>> InActive </option>
                        </select>
                        <?= form_error("is_in_footer", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Image
                    </label>
                    <label>
                        <input type="file" class="input-xxlarge" name="image" />
                        <span>
                            <?
                            if ($info["image"] != "") { ?>
                                <a href="<?= base_url(); ?>uploads/category/<?= $info["image"]; ?>" class="blue gallery" title="<?= $info["title"]; ?>">
                                    show image
                                </a>
                                &nbsp;&nbsp;&nbsp;
                                <a class="cur" onclick="if(confirm('Are you sure you want to delete this image ?')){ window.location=' <?= base_url(); ?>back_office/category/delete_image/image/<?= $info["image"]; ?>/<?= $info["id_category"] ?> ' } ">
                                    <img src="<?= base_url(); ?>images/delete.png" /></a>
                            <? } else {
                                echo "<span class='blue'>No Image Available</span>";
                            } ?>
                        </span>
                        <?= form_error("image", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Description
                    </label>
                    <label>

                        <textarea class="ckeditor" id="description" rows="15" cols="100" name="description">
<?= set_value("description", $info["description"]); ?>
</textarea>

                        <?= form_error("description", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Section
                    </label>
                    <label>

                        <? $items = $this->fct->getAll("home_sections", "sort_order"); ?>
                        <select name="section" class="input-xxlarge">
                            <option value=""> - select home sections - </option>
                            <? foreach ($items as $valll) { ?>
                                <option value="<?= $valll["id_home_sections"]; ?>" <? if (isset($id)) {
                                                                                        if ($info["id_home_sections"] == $valll["id_home_sections"]) {
                                                                                            echo 'selected="selected"';
                                                                                        }
                                                                                    } ?>><?= $valll["title"]; ?></option>
                            <? } ?>
                        </select>
                        <?= form_error("section", "<span class='text-error'>", "</span>"); ?>
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