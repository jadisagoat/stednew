<?php
$content_name = "course";
$id_content = "7";
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
                    <li><a href="<?= base_url(); ?>back_office/course">course</a> <span class="divider">/</span></li>
                    <li class="active"><?= $title; ?></li>
                </ul>
            </div>
            <div class="hundred pull-left">
                <form action="<?= base_url(); ?>back_office/course/submit" method="post" class="middle-forms" enctype="multipart/form-data">
                    <p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
                        <?
                        if (isset($id)) { ?>
                            <input type="hidden" name="id" value="<?= $id; ?>" />
                        <?
                        } else {
                            $info["title"] = "";
                            $info["image"] = "";
                            $info["top_course"] = "";
                            $info["category"] = "";
                            $info["summary"] = "";
                            $info["description"] = "";
                            $info["requirements"] = "";
                            $info["schedule"] = "";
                            $info["hours"] = "";
                            $info["price"] = "";
                            $info["old_price"] = "";
                            $info["course_brief"] = "";
                            $info["active"] = "";
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
                    <label class="field-title">Image
                    </label>
                    <label>
                        <input type="file" class="input-xxlarge" name="image" />
                        <span>
                            <?
                            if ($info["image"] != "") { ?>
                                <a href="<?= base_url(); ?>uploads/course/<?= $info["image"]; ?>" class="blue gallery" title="<?= $info["title"]; ?>">
                                    show image
                                </a>
                                &nbsp;&nbsp;&nbsp;
                                <a class="cur" onclick="if(confirm('Are you sure you want to delete this image ?')){ window.location=' <?= base_url(); ?>back_office/course/delete_image/image/<?= $info["image"]; ?>/<?= $info["id_course"] ?> ' } ">
                                    <img src="<?= base_url(); ?>images/delete.png" /></a>
                            <? } else {
                                echo "<span class='blue'>No Image Available</span>";
                            } ?>
                        </span>
                        <?= form_error("image", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Top course
                    </label>
                    <label>
                        <select name="top_course" class="input-xxlarge">
                            <option value="0"> - select option - </option>
                            <option value="1" <? if ($info["top_course"] == 1) echo 'selected="selected"'; ?>> Active </option>
                            <option value="0" <? if ($info["top_course"] == 0) echo 'selected="selected"'; ?>> InActive </option>
                        </select>
                        <?= form_error("top_course", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Category
                    </label>
                    <label>

                        <? $items = $this->fct->getAll("category", "sort_order"); ?>
                        <select name="category" class="input-xxlarge">
                            <option value=""> - select category - </option>
                            <? foreach ($items as $valll) { ?>
                                <option value="<?= $valll["id_category"]; ?>" <? if (isset($id)) {
                                                                                    if ($info["id_category"] == $valll["id_category"]) {
                                                                                        echo 'selected="selected"';
                                                                                    }
                                                                                } ?>><?= $valll["title"]; ?></option>
                            <? } ?>
                        </select>
                        <?= form_error("category", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Summary
                    </label>
                    <label>

                        <textarea class="ckeditor" id="summary" rows="15" cols="100" name="summary">
<?= set_value("summary", $info["summary"]); ?>
</textarea>

                        <?= form_error("summary", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Description
                    </label>
                    <label>

                        <textarea class="ckeditor" id="description" rows="15" cols="100" name="description">
<?= set_value("description", $info["description"]); ?>
</textarea>

                        <?= form_error("description", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Requirements
                    </label>
                    <label>

                        <textarea class="ckeditor" id="requirements" rows="15" cols="100" name="requirements">
<?= set_value("requirements", $info["requirements"]); ?>
</textarea>

                        <?= form_error("requirements", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Schedule
                    </label>
                    <label>

                        <div class="input-append date" data-date="21-07-2024" data-date-format="mm/dd/yyyy">
                            <input class="span2" size="16" name="schedule" type="text"
                                value="<?= set_value("schedule", $this->fct->date_out_formate($info["schedule"])); ?>">
                            <span class="add-on"><i class="icon-th"></i></span>
                        </div>
                        <?= form_error("schedule", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Minutes
                    </label>
                    <label>
                        <input type="text" class="input-xxlarge" name="hours" value="<?= set_value("hours", $info["hours"]); ?>" />
                        <?= form_error("hours", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Price
                    </label>
                    <label>
                        <input type="text" class="input-xxlarge" name="price" value="<?= set_value("price", $info["price"]); ?>" />
                        <?= form_error("price", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Old price
                    </label>
                    <label>
                        <input type="text" class="input-xxlarge" name="old_price" value="<?= set_value("old_price", $info["old_price"]); ?>" />
                        <?= form_error("old_price", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Course brief
                    </label>
                    <label>

                        <textarea class="ckeditor" id="course_brief" rows="15" cols="100" name="course_brief">
<?= set_value("course_brief", $info["course_brief"]); ?>
</textarea>

                        <?= form_error("course_brief", "<span class='text-error'>", "</span>"); ?>
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