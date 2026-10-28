<script type="text/javascript" src="<?= base_url(); ?>js/jquery.tablednd_0_5.js"></script>
<script>
    function delete_row(id) {
        window.location = '<?= base_url(); ?>back_office/control/delete/' + id;
        return false;
    }

    function completed(id) {
        var sel = "#complete_" + id;
        $(sel).load('<?= base_url(); ?>back_office/home/complete_todo/' + id);
    }

    $(function() {
        $('#table-1').tableDnD({
            onDrop: function(table, row) {
                var ser = $.tableDnD.serialize();
                $('#result').load('<?= base_url(); ?>back_office/control/sorted?' + ser);
            }
        });
    });
</script>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span2">
            <? $this->load->view('back_office/includes/left_box'); ?>
        </div>
        <div class="span10 cont_h">
            <div class="span10-fluid">
                <ul class="breadcrumb">
                    <li><?= $title; ?></li>
                    <li class="pull-right">
                        <a href="<?= base_url(); ?>back_office/control/add" id="topLink">Create New Content Type</a>
                    </li>
                </ul>
            </div>
            <div class="hundred pull-left">
                <div id="result"></div>

                <form action="<?= base_url(); ?>back_office/control/delete_all" method="post" name="list_form">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <? if ($this->session->userdata('success_message')) { ?>
                                <tr>
                                    <td colspan="5" align="center" style="text-align:center">
                                        <div class="alert alert-success">
                                            <?= $this->session->userdata('success_message'); ?>
                                        </div>
                                    </td>
                                <tr>
                                <? } ?>
                                <? if ($this->session->userdata('error_message')) { ?>
                                <tr>
                                    <td colspan="5" align="center" style="text-align:center">
                                        <div class="alert alert-error">
                                            <?= $this->session->userdata('error_message'); ?>
                                        </div>
                                    </td>
                                <tr>
                                <? } ?>

                                <tr>
                                    <th>&nbsp;</th>
                                    <th>Content Type Name...</th>
                                    <!-- <th>url Name</th> -->
                                    <th>Menu</th>
                                    <!-- <th>Label Icon</th> -->
                                    <th>Action</th>
                                </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td class="col-chk"><input type="checkbox" id="checkAllAuto" /></td>
                                <td colspan="3">
                                    <div class="pull-right">
                                        <select class="form-select" name="check_option">
                                            <option value="option1">Bulk Options</option>
                                            <option value="delete_all">Delete All</option>
                                        </select>
                                        <a onclick="document.forms['list_form'].submit();" class="btn btn-primary btn_mrg"><span>perform action</span></a>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?
                            if (count($info) > 0) {
                                $i = 0;
                                foreach ($info as $val) {
                                    $i++;
                                    ($i % 2 == 0) ? $odd = '' : $odd = 'odd';
                            ?>
                                    <tr class="<?= $odd; ?>" id="<?= $val["id_content"]; ?>">
                                        <td class="col-chk"><input type="checkbox" name="cehcklist[]" value="<?= $val["id_content"]; ?>" /></td>
                                        <td class="" width=""><b>
                                                <a href="<?= base_url(); ?>back_office/control/manage/<?= $val["id_content"]; ?>" class="blue">
                                                    <?= $val["name"]; ?></a></b></td>
                                        <!-- <td class="" width="">
                                            <?= $val["url_name"]; ?>
                                        </td> -->
                                        <td class="" width="">
                                            <? if ($val["id_menu"] != 0) {
                                                $cond = array("id_menu" => $val["id_menu"]);
                                                echo $this->fct->getonecell("menu", "title", $cond);
                                            } else {
                                                echo "<small>Other</small>";
                                            }   ?>
                                        </td>
                                        <!-- <td class="" width="">
                                            <i class="menu-icon tf-icons bx <?= $val["icon"]; ?>"></i>

                                            
                                                <a class="cur" onclick="completed(< ?= $val["id_content"]; ?>);" style="color:#74d24c;">
                                                < ? if (!empty($val["icon"])) { ?>
                                                    <img src="< ?= base_url(); ?>uploads/content_type/32x32/< ?= $val["icon"]; ?>" /> < ? }
                                                     else { echo "No Icon ";
                                                } ?></a>
                                           
                                        </td> -->
                                        <td class="row-nav"><a href="<?= base_url(); ?>back_office/control/edit/<?= $val["id_content"]; ?>" class="table-edit-link cur cur">
                                                <i class="bx bx-edit"></i>
                                                <span class="hidden"> | </span>
                                                <a onclick="if(confirm('Are you sure you want delete this item ?')){ delete_row('<?= $val["id_content"]; ?>'); }" class="table-delete-link cur">
                                                    <i class="bx bx-trash"></i></a>

                                        </td>
                                    </tr>
                            <? }
                            } else {
                                // echo '<tr class="odd">  <td colspan="5" align="center" style="text-align:center">No records available . </td></tr>';
                            }  ?>
                        </tbody>
                    </table>
                </form>

            </div>

        </div>
    </div>
</div>
<? $this->session->unset_userdata('success_message'); ?>
<? $this->session->unset_userdata('error_message'); ?>