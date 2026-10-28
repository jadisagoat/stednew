<?php
$content_name = "exam";
$id_content = "10";
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
                    <li><a href="<?= base_url(); ?>back_office/exam">exam</a> <span class="divider">/</span></li>
                    <li class="active"><?= $title; ?></li>
                </ul>
            </div>
            <div class="hundred pull-left">
                <form action="<?= base_url(); ?>back_office/exam/submit" method="post" class="middle-forms" enctype="multipart/form-data">
                    <p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
                        <?
                        if (isset($id)) { ?>
                            <input type="hidden" name="id" value="<?= $id; ?>" />
                        <?
                        } else {
                            $info["title"] = "";
                            $info["course"] = "";
                            $info["start_date"] = "";
                            $info["start_date_time"] = "";
                            $info["close_date"] = "";
                            $info["close_date_time"] = "";
                            $info["time"] = "";
                            $info["number_of_questions"] = "";
                            $info["number_of_attempts"] = "";
                            $info["hide_students"] = "";
                            $info["description"] = "";
                            $info["image"] = "";
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
                    <!-- <label class="field-title">Start date
</label>
<label>

    <div class="input-append date" data-date="24-08-2024" data-date-format="mm/dd/yyyy">
    <input class="span2" size="16" name="start_date" type="text" 
    value="<?= set_value("start_date", $this->fct->date_out_formate($info["start_date"])); ?>">
    <span class="add-on"><i class="icon-th"></i></span>
    </div>
<?= form_error("start_date", "<span class='text-error'>", "</span>"); ?>
</label>
<label class="field-title">Start date time
</label>
<label>
<input type="text" class="input-xxlarge" name="start_date_time" value="<?= set_value("start_date_time", $info["start_date_time"]); ?>" />
<?= form_error("start_date_time", "<span class='text-error'>", "</span>"); ?>
</label>
<label class="field-title">Close date
</label>
<label>

    <div class="input-append date" data-date="24-08-2024" data-date-format="mm/dd/yyyy">
    <input class="span2" size="16" name="close_date" type="text" 
    value="<?= set_value("close_date", $this->fct->date_out_formate($info["close_date"])); ?>">
    <span class="add-on"><i class="icon-th"></i></span>
    </div>
<?= form_error("close_date", "<span class='text-error'>", "</span>"); ?>
</label> -->
                    <!-- <label class="field-title">Close date time
                    </label>
                    <label>
                        <input type="text" class="input-xxlarge" name="close_date_time" value="<?= set_value("close_date_time", $info["close_date_time"]); ?>" />
                        <?= form_error("close_date_time", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Time
                    </label>
                    <label>
                        <input type="text" class="input-xxlarge" name="time" value="<?= set_value("time", $info["time"]); ?>" />
                        <?= form_error("time", "<span class='text-error'>", "</span>"); ?>
                    </label> -->
                    <label class="field-title">Number of questions
                    </label>
                    <label>
                        <input type="text" class="input-xxlarge" name="number_of_questions" value="<?= set_value("number_of_questions", $info["number_of_questions"]); ?>" />
                        <?= form_error("number_of_questions", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Number of attempts
                    </label>
                    <label>
                        <input type="text" class="input-xxlarge" name="number_of_attempts" value="<?= set_value("number_of_attempts", $info["number_of_attempts"]); ?>" />
                        <?= form_error("number_of_attempts", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Hide students
                    </label>
                    <label>
                        <select name="hide_students" class="input-xxlarge">
                            <option value="0"> - select option - </option>
                            <option value="1" <? if ($info["hide_students"] == 1) echo 'selected="selected"'; ?>> Active </option>
                            <option value="0" <? if ($info["hide_students"] == 0) echo 'selected="selected"'; ?>> InActive </option>
                        </select>
                        <?= form_error("hide_students", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Description
                    </label>
                    <label>

                        <textarea class="ckeditor" id="description" rows="15" cols="100" name="description">
<?= set_value("description", $info["description"]); ?>
</textarea>

                        <?= form_error("description", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Image
                    </label>
                    <label>
                        <input type="file" class="input-xxlarge" name="image" />
                        <span>
                            <?
                            if ($info["image"] != "") { ?>
                                <a href="<?= base_url(); ?>uploads/exam/<?= $info["image"]; ?>" class="blue gallery" title="<?= $info["title"]; ?>">
                                    show image
                                </a>
                                &nbsp;&nbsp;&nbsp;
                                <a class="cur" onclick="if(confirm('Are you sure you want to delete this image ?')){ window.location=' <?= base_url(); ?>back_office/exam/delete_image/image/<?= $info["image"]; ?>/<?= $info["id_exam"] ?> ' } ">
                                    <img src="<?= base_url(); ?>images/delete.png" /></a>
                            <? } else {
                                echo "<span class='blue'>No Image Available</span>";
                            } ?>
                        </span>
                        <?= form_error("image", "<span class='text-error'>", "</span>"); ?>
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