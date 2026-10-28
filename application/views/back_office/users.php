<script type="text/javascript" src="<?= base_url(); ?>js/jquery.tablednd_0_5.js"></script>
<script>
    function delete_row(db_name) {
        window.location = '<?= base_url(); ?>back_office/users/delete/' + db_name;
        return false;
    }


    $(function() {

        $('#table-1').tableDnD({
            onDrop: function(table, row) {
                var ser = $.tableDnD.serialize();
                $('#result').load('<?= base_url(); ?>back_office/users/sorted?' + ser);
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
                        <a href="<?= base_url(); ?>back_office/users/add">Create New User</a>
                    </li>
                </ul>
            </div>
            <div class="hundred pull-left">
                <div id="result"></div>

                <form action="<?= base_url(); ?>back_office/users/delete_all" method="post" name="list_form">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <? if ($this->session->userdata('success_message')) { ?>
                                <tr>
                                    <td colspan="6" align="center" style="text-align:center">
                                        <div class="alert alert-success">
                                            <?= $this->session->userdata('success_message'); ?>
                                        </div>
                                    </td>
                                <tr>
                                <? } ?>
                                <? if ($this->session->userdata('error_message')) { ?>
                                    <div class="alert alert-error">
                                        <?= $this->session->userdata('error_message'); ?>
                                    </div>
                                <? } ?>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>Full Name</td>
                                    <td>Picture</td>
                                    <td>Email</td>
                                    <td>Phone</td>
                                    <td>Created Date</td>
                                    <td>Action</td>
                                </tr>
                        </thead>
                        <tfoot>

                            <tr>
                                <td class="col-chk"><input type="checkbox" id="checkAllAuto" /></td>
                                <td colspan="6">
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
                                    <tr class="<?= $odd; ?>" id="<?= $val["id_user"]; ?>">
                                        <td class="col-chk"><input type="checkbox" name="cehcklist[]" value="<?= $val["id_user"]; ?>" /></td>
                                        <td class="" width=""><?= $val["name"]; ?></td>
                                        <td class="" width=""><? if ($val["image"] != "") { ?>
                                                <a href="<?= base_url(); ?>uploads/user/<?= $val["image"]; ?>" class="blue gallery" title="<?= $val["title"]; ?>">
                                                    show image</a>&nbsp;&nbsp;&nbsp;<a class="cur" onclick="if(confirm('Are you sure you want to delete this image ?')){ window.location=' <?= base_url(); ?>back_office/user/delete_image/image/<?= $val["image"]; ?>/<?= $val["id_user"]; ?>' } ">
                                                    <img src="<?= base_url(); ?>images/delete.png" /></a>
                                            <? } else {
                                                                    echo "<small class='blue'>No Image Available</small>";
                                                                } ?>
                                        </td>
                                        < <td class="" width=""><?= $val["email"]; ?></td>
                                            <td class="" width=""><?= $val["phone"]; ?></td>
                                            <td class="" width=""><?= $val["created_date"]; ?></td>
                                            <td class="">
                                                <a href="<?= base_url(); ?>back_office/users/priviledge/<?= $val["id_user"]; ?>" class="table-privel-link cur cur">
                                                    <i class="icon-filter"></i> Privilege</a>
                                                <span class="hidden"> | </span>
                                                <a href="<?= base_url(); ?>back_office/users/edit/<?= $val["id_user"]; ?>" class="table-edit-link cur cur">
                                                    <i class="bx bx-edit"></i>
                                                    <span class="hidden"> | </span>
                                                    <a onclick="if(confirm('Are you sure you want delete this user ?')){ delete_row('<?= $val["id_user"]; ?>'); }" class="table-delete-link cur">
                                                        <i class="bx bx-trash"></i></a>
                                            </td>
                                    </tr>
                            <? }
                            } else {
                                echo '<tr class="odd"><td colspan="6" style="text-align:center;">No records available . </td></tr>';
                            } ?>
                        </tbody>
                    </table>
                </form>

            </div><!-- end of div.box -->

        </div>

    </div>
    <? $this->session->unset_userdata('success_message'); ?>
    <? $this->session->unset_userdata('error_message'); ?>