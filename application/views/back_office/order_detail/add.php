<?php
$content_name = "order_detail";
$id_content = "28";
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
                    <li><a href="<?= base_url(); ?>back_office/order_detail">order detail</a> <span class="divider">/</span></li>
                    <li class="active"><?= $title; ?></li>
                </ul>
            </div>
            <div class="hundred pull-left">
                <form action="<?= base_url(); ?>back_office/order_detail/submit" method="post" class="middle-forms" enctype="multipart/form-data">
                    <p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
                        <?
                        if (isset($id)) { ?>
                            <input type="hidden" name="id" value="<?= $id; ?>" />
                        <?
                        } else {
                            $info["orders"] = "";
                            $info["course"] = "";
                            $info["quantity"] = "";
                            $info["price"] = "";
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

                    <label class="field-title">Orders&nbsp;<em>*</em>:
                    </label>
                    <label>

                        <? $items = $this->fct->getAll("orders", "sort_order"); ?>
                        <select name="orders" class="input-xxlarge">
                            <option value=""> - select orders - </option>
                            <? foreach ($items as $valll) { ?>
                                <option value="<?= $valll["id_orders"]; ?>" <? if (isset($id)) {
                                                                                if ($info["id_orders"] == $valll["id_orders"]) {
                                                                                    echo 'selected="selected"';
                                                                                }
                                                                            } ?>><?= $valll["title"]; ?></option>
                            <? } ?>
                        </select>
                        <?= form_error("orders", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Course&nbsp;<em>*</em>:
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
                    <label class="field-title">Quantity
                    </label>
                    <label>
                        <input type="text" class="input-xxlarge" name="quantity" value="<?= set_value("quantity", $info["quantity"]); ?>" />
                        <?= form_error("quantity", "<span class='text-error'>", "</span>"); ?>
                    </label>
                    <label class="field-title">Price
                    </label>
                    <label>
                        <input type="text" class="input-xxlarge" name="price" value="<?= set_value("price", $info["price"]); ?>" />
                        <?= form_error("price", "<span class='text-error'>", "</span>"); ?>
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