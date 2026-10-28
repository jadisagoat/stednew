<?php
$content_name = "user_course_rating";
$id_content = "39";
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
                    <li><a href="<?= base_url(); ?>back_office/user_course_rating">user course rating</a> <span class="divider">/</span></li>
                    <li class="active"><?= $title; ?></li>
                </ul>
            </div>
            <div class="hundred pull-left">
                <form action="<?= base_url(); ?>back_office/user_course_rating/submit" method="post" class="middle-forms" enctype="multipart/form-data">
                    <p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
                        <?
                        if (isset($id)) { ?>
                            <input type="hidden" name="id" value="<?= $id; ?>" />
                        <?
                        } else {
                            $info["user"] = "";
                            $info["course"] = "";
                            $info["rating"] = "";
                            $info["feedback"] = "";
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
                                                                            } ?>><?= $valll["name"]; ?></option>
                            <? } ?>
                        </select>
                        <?= form_error("user", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Course
                    </label>
                    <label>

                        <? $items = $this->fct->getAll("course", "sort_order"); ?>
                        <select name="course" class="input-xxlarge">
                            <option value=""> - select course - </option>
                            <? foreach ($items as $valll) { ?>
                                <option value="<?= $valll["id_course"]; ?>" <? if (isset($id)) {
                                                                                if ($info["id_course"] == $valll["id_course"]) {
                                                                                    echo 'selected="selected"';
                                                                                }
                                                                            } ?>><?= $valll["title"]; ?></option>
                            <? } ?>
                        </select>
                        <?= form_error("course", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Rating
                    </label>
                    <label>
                        <input type="text" class="input-xxlarge" name="rating" value="<?= set_value("rating", $info["rating"]); ?>" />
                        <?= form_error("rating", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Feedback
                    </label>
                    <label>

                        <textarea class="ckeditor" id="feedback" rows="15" cols="100" name="feedback">
<?= set_value("feedback", $info["feedback"]); ?>
</textarea>

                        <?= form_error("feedback", "<span class='text-error'>", "</span>"); ?>
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