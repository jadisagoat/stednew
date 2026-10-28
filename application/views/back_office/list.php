<script type="text/javascript" src="<?= base_url(); ?>js/jquery.tablednd_0_5.js"></script>
<script>
    function delete_row(id) {
        window.location = "<?= base_url(); ?>back_office//delete/" + id;
        return false;
    }


    $(function() {
        $("#table-1").tableDnD({
            onDrop: function(table, row) {
                var ser = $.tableDnD.serialize();
                $("#result").load("<?= base_url(); ?>back_office//sorted?" + ser);
            }
        });
    });
</script>
<?
$content_name = $this->uri->segment(2);
$cond1["name"] = str_replace("_", " ", $content_name);
$id_content = $this->fct->getonecell("content_type", "id_content", $cond1);
$gallery_status = $this->fct->getonecell("content_type", "gallery", $cond1);
if ($this->session->userdata("level") == 0) {
    $cond = array("id_role" => $this->session->userdata("id_role"), "id_content" => $id_content);
    $read_priv = $this->fct->getonecell("priviledge", "read_p", $cond);
    $write_priv = $this->fct->getonecell("priviledge", "write_p", $cond);
    $delete_priv = $this->fct->getonecell("priviledge", "delete_p", $cond);
} else {
    $read_priv = 1;
    $write_priv = 1;
    $delete_priv = 1;
}
if ($read_priv != 1) {
    redirect(base_url() . "back_office/home/dashboard");
}
?>
<div id="content">
    <div id="content-top">
        <h2><?= $title; ?></h2>
        <? if ($write_priv == 1) { ?>
            <a href="<?= base_url(); ?>back_office//add" id="topLink">Add </a>
        <? } ?>
        <span class="clearFix">&nbsp;</span>
    </div>
    <? $this->load->view("back_office/includes/left_box"); ?>
    <div class="full-col">
        <div id="result"></div>
        <div class="box">
            <h4 class="white"><?= $title; ?></h4>
            <div class="box-container">

                <form action="<?= base_url(); ?>back_office//delete_all" method="post" name="list_form">
                    <table class="table-long" id="table-1">
                        <thead>
                            <? if ($this->session->userdata("success_message")) { ?>
                                <tr>
                                    <td colspan="2" align="center" style="text-align:center">
                                        <span class="form-confirm-inline">
                                            <?= $this->session->userdata("success_message"); ?>
                                        </span>
                                    </td>
                                <tr>
                                <? } ?>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>Action</td>
                                </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td class="col-chk"><input type="checkbox" id="checkAllAuto" /></td>
                                <td colspan="1">
                                    <div class="align-right">
                                        <? if ($delete_priv == 1) { ?>
                                            <select class="form-select" name="check_option">
                                                <option value="option1">Bulk Options</option>
                                                <option value="delete_all">Delete All</option>
                                            </select>
                                            <a class="button" onclick="document.forms['list_form'].submit();" style="cursor:pointer;"><span>perform action</span></a>
                                    </div> <? } ?>
                                </td>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?
                            if (count($info) > 0) {
                                $i = 0;
                                foreach ($info as $val) {
                                    $i++;
                                    ($i % 2 == 0) ? $odd = "" : $odd = "odd";
                            ?>
                                    <tr class="<?= $odd; ?>" id="<?= $val["id_"]; ?>">
                                        <td class="col-chk"><input type="checkbox" name="cehcklist[]" value="<?= $val["id_"]; ?>" /></td>
                                        <td class="row-nav"><? if ($write_priv == 1) { ?>
                                                <a href="<?= base_url(); ?>back_office//edit/<?= $val["id_"]; ?>" class="table-edit-link cur cur">Edit</a> <span class="hidden"> | </span>
                                            <? } ?>
                                            <? if ($delete_priv == 1) { ?>
                                                <a onclick="if(confirm('Are you sure you want delete this item ?')){ delete_row('<?= $val["id_"]; ?>'); }" class="table-delete-link cur">Delete</a>
                                            <? } ?>
                                        </td>
                                    </tr>
                                <? }
                            } else { ?>
                                <tr class='odd'>
                                    <td colspan="2" style='text-align:center;'>No records available . </td>
                                </tr>
                            <?  } ?>
                        </tbody>
                    </table>
                </form>

            </div>
        </div>
    </div>
    <span class="clearFix">&nbsp;</span>
</div>
<? $this->session->unset_userdata("success_message"); ?>
<? $this->session->unset_userdata("error_message"); ?>