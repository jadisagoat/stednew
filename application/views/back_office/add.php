<?
$content_name = $this->uri->segment(2);
$cond1["name"] = str_replace("_", " ", $content_name);
$id_content = $this->fct->getonecell("content_type", "id_content", $cond1);
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
<div id="content">
    <? $this->load->view("back_office/includes/left_box"); ?>
    <div class="full-col">
        <div class="box">
            <h4 class="white"><?= $title; ?></h4>
            <div class="box-container">
                <form action="<?= base_url(); ?>back_office//submit" method="post" class="middle-forms" enctype="multipart/form-data">
                    <h3><?= $title; ?>:</h3>
                    <p>Please complete the form below. Mandatory fields marked <em>*</em>
                        <?
                        if (isset($id)) { ?>
                            <input type="hidden" name="id" value="<?= $id; ?>" />
                        <?
                        } else {
                            foreach ($attr as $val) {
                                $info[str_replace(" ", "_", $val["name"])] = "";
                            }
                        }
                        ?>
                        <? if ($this->session->userdata("success_message")) { ?>
                            <span class="form-confirm-inline">
                                <?= $this->session->userdata("success_message"); ?>
                            </span>
                        <? } ?>
                        <? if ($this->session->userdata("error_message")) { ?>
                            <span class="form-error-inline">
                                <?= $this->session->userdata("error_message"); ?>
                            </span>
                        <? } ?>
                    </p>
                    <fieldset>
                        <legend>Fieldset Title</legend>
                        <ol>
                            <?
                            $i = 0;
                            foreach ($attr as $val) {
                                $i++;
                                ($i % 2 != 0) ? $even = "even" : $even = "";
                            ?>
                                <li class="<?= $even; ?>">
                                    <label class="field-title">
                                        <?= $val["name"] ?>
                                        <?
                                        if ($val["validation"] != "trim") { ?>
                                            &nbsp;<em>*</em>:<? } ?> </label><label>
                                        <? if ($val["type"] == 3) { ?>
                                            <textarea id="wysiwyg" rows="7" cols="25" name="<?= str_replace(" ", "_", $val["name"]); ?>"><?= set_value(str_replace(" ", "_", $val["name"]), $info[str_replace(" ", "_", $val["name"])]); ?></textarea>
                                        <? } elseif ($val["type"] == 4) { ?>
                                            <input type="file" name="<?= str_replace(" ", "_", $val["name"]); ?>" />
                                            <span>
                                                <?
                                                if ($info[str_replace(" ", "_", $val["name"])] != "") { ?>
                                                    <a href="<?= base_url(); ?>uploads//<?= $info[str_replace(" ", "_", $val["name"])]; ?>" class="blue gallery" title="<?= $info["title"]; ?>">
                                                        show image
                                                    </a>
                                                    &nbsp;&nbsp;&nbsp;
                                                    <a class="cur" onclick="if(confirm('Are you sure you want to delete this image ?')){ window.location=' <?= base_url(); ?>back_office//delete_image/<?= str_replace(" ", "_", $val["name"]); ?>/<?= $info[str_replace(" ", "_", $val["name"])]; ?>/<?= $info["id_"] ?> ' } ">
                                                        <img src="<?= base_url(); ?>images/delete.png" /></a>
                                                <? } else {
                                                    echo "<span class='blue'>No Image Available</span>";
                                                } ?>
                                            </span>
                                        <? } elseif ($val["type"] == 1) { ?>
                                            <input class="txtbox-middle" id="calendar" name="<?= str_replace(" ", "_", $val["name"]); ?>" value="<?= set_value(str_replace(" ", "_", $val["name"]), $this->fct->date_out_formate($info[str_replace(" ", "_", $val["name"])])); ?>" />
                                        <? } elseif ($val["type"] == 5) { ?>
                                            <input type="file" name="<?= str_replace(" ", "_", $val["name"]); ?>" />
                                        <? } elseif ($val["type"] == 6) { ?>
                                            <select name="<?= str_replace(" ", "_", $val["name"]); ?>">
                                                <option value="0"> - select option - </option>
                                                <option value="1" <? if ($info[str_replace(" ", "_", $val["name"])] == 1) echo 'selected="selected"'; ?>> Active </option>
                                                <option value="0" <? if ($info[str_replace(" ", "_", $val["name"])] == 0) echo 'selected="selected"'; ?>> InActive </option>
                                            </select>
                                        <?
                                        } elseif ($val["type"] == 7) {
                                            $cond = array("id_content" => $val["foreign_key"]);
                                            $content001 = $this->fct->getonecell("content_type", "name", $cond);
                                            $items = $this->fct->getAll($content001, "sort_order");
                                        ?>
                                            <select name="<?= str_replace(" ", "_", $val["name"]); ?>">
                                                <option> - select <?= $content001; ?> - </option>
                                                <? foreach ($items as $valll) { ?>
                                                    <option value="<?= $valll["id_" . str_replace(" ", "_", $content001)]; ?>" <? if (isset($id)) {
                                                                                                                                if ($info["id_" . str_replace(" ", "_", $val["name"])] == $valll["id_" . str_replace(" ", "_", $content001)]) {
                                                                                                                                    echo 'selected="selected"';
                                                                                                                                }
                                                                                                                            } ?>><?= $valll["title"]; ?></option>
                                                <? } ?>
                                            </select>
                                        <? } else { ?>
                                            <input class="txtbox-middle" name="<?= str_replace(" ", "_", $val["name"]); ?>" value="<?= set_value(str_replace(" ", "_", $val["name"]), $info[str_replace(" ", "_", $val["name"])]); ?>" />
                                        <? } ?>
                                        <?= form_error(str_replace(" ", "_", $val["name"]), "<span class='form-error-inline'>", "</span>"); ?>
                                    </label><span class="clearFix">&nbsp;</span>
                                </li>
                            <? } ?>
                        </ol>
                    </fieldset>
                    <p class="align-right"><input type="image" src="<?= base_url(); ?>images/bt-send-form.gif" /></p>
                    <span class="clearFix">&nbsp;</span>
                </form>
            </div>
        </div>
    </div>
    <span class="clearFix">&nbsp;</span>
</div>
<? $this->session->unset_userdata("success_message"); ?>
<? $this->session->unset_userdata("error_message"); ?>