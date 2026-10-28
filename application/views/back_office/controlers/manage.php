<script type="text/javascript" src="<?= base_url(); ?>js/jquery.tablednd_0_5.js"></script>
<script>
    function delete_row(id) {
        window.location = '<?= base_url(); ?>back_office/control/delete_attr/' + id;
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
                $('#result').load('<?= base_url(); ?>back_office/control/sorted_attr?' + ser);
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
                    <li><?= $title1; ?><span class="divider">/</span></li>
                    <li><?= $con_type["name"]; ?></li>
                    <li class="pull-right">
                        <a href="<?= base_url(); ?>back_office/control/add_attr/<?= $con_type["id_content"]; ?>" id="topLink">Add Attribute</a>
                    </li>
                </ul>
            </div>
            <div class="hundred pull-left">
                <div id="result"></div>
                <form action="<?= base_url(); ?>back_office/control/delete_all_attr" method="post" name="list_form">
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
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>Content Attribute Name</td>
                                    <td>Type</td>
                                    <td>Display</td>
                                    <td>Action</td>
                                </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td class="col-chk"><input type="checkbox" id="checkAllAuto" /></td>
                                <td colspan="4">
                                    <div class="pull-right">
                                        <select class="form-select" name="check_option">
                                            <option value="option1">Bulk Options</option>
                                            <option value="delete_all">Delete All</option>
                                        </select>
                                        <a class="btn btn-primary btn_mrg" onclick="document.forms['list_form'].submit();"><span>perform action</span></a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" align="center" style="text-align:center">
                                    <a class="btn btn-warning" href="<?= base_url(); ?>back_office/install/settings/<?= $this->uri->segment(4); ?>">
                                        <span>Install Conetnt Type</span>
                                    </a>
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
                                    <tr class="<?= $odd; ?>" id="<?= $val["id_attr"]; ?>">
                                        <td class="col-chk"><input type="checkbox" name="cehcklist[]" value="<?= $val["id_attr"]; ?>" /></td>
                                        <td class="" width=""><b>
                                                <?= $val["name"]; ?></b></td>
                                        <td class="" width="">
                                            <?
                                            $this->db->where('id_type', $val["type"]);
                                            $query = $this->db->get('attr_type');
                                            $res = $query->row_array();
                                            echo $res["name"];
                                            ?></td>
                                        <td class="" width="" style="color:#74d24c; font-weight:bold;">
                                            <? if ($val["display"] == 1)
                                                echo "yes";
                                            else
                                                echo "No";
                                            ?>
                                        </td>
                                        <td class="row-nav">
                                            <!--<a href="<? // base_url();
                                                            ?>back_office/control/edit_attr/<? // $val["id_content"]; 
                                                                                            ?>/<? //$val["id_attr"]; 
                                                                                                ?>" class="table-edit-link cur cur" >Edit</a>
<span class="hidden"> | </span>-->
                                            <a onclick="if(confirm('Are you sure you want delete this item ?')){ delete_row('<?= $val["id_attr"]; ?>'); }" class="table-delete-link cur">
                                                <i class="bx bx-trash"></i></a>

                                        </td>
                                    </tr>
                            <? }
                            } else {
                                // echo '<tr class="odd"><td colspan="5" style="text-align:center;">No records available . </td></tr>';
                            } ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <span class="clearFix">&nbsp;</span>
    </div>

    <? $this->session->unset_userdata('success_message'); ?>
    <? $this->session->unset_userdata('error_message'); ?>