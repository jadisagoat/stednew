<div id="content">
    <div id="content-top">
        <h2>Dashboard</h2>
        <a href="<?= base_url(); ?>back_office/manage_db/create" id="topLink">Create New DataBase</a>
        <span class="clearFix">&nbsp;</span>
    </div><!-- end of div#content-top -->
    <div id="left-col">
        <div class="box">
            <h4 class="yellow">Side Menu</h4>
            <div class="box-container"><!-- use no-padding wherever you need element padding gone -->
                <ul class="list-links">
                    <li><a href="#">Manage Filters</a></li>
                    <li><a href="#">Setup a New Site</a>
                        <ul>
                            <li><a href="#">Configure Paths</a></li>
                            <li><a href="#">Define Database Name</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Manage Site Accounts</a></li>
                </ul>
            </div><!--end of div.box-container -->
        </div><!-- end of div.box --><!--end of div.box --><!-- end of div.box -->
    </div> <!-- end of div#left-col -->

    <div id="mid-col" class="full-col"><!-- end of div.box -->

        <div class="box">
            <h4 class="white">Data Bases Table</h4>
            <div class="box-container">
                <form action="<?= base_url(); ?>back_office/manage_db/delete_all" method="post" name="list_form">
                    <table class="table-long">
                        <thead>
                            <tr>
                                <td>&nbsp;</td>
                                <td>Database Name</td>
                                <td>Manage</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tfoot>

                            <tr>
                                <td class="col-chk"><input type="checkbox" id="checkAllAuto" /></td>
                                <td colspan="3">
                                    <div class="align-right">
                                        <select class="form-select" name="check_option">
                                            <option value="option1">Bulk Options</option>
                                            <option value="delete_all">Delete All</option>
                                        </select>
                                        <a class="button" onclick="document.forms['list_form'].submit();" style="cursor:pointer;"><span>perform action</span></a>
                                    </div>
                                </td>
                            </tr>

                        </tfoot>
                        <tbody>
                            <?
                            if (count($db) > 0) {
                                $i = 0;
                                foreach ($db as $val) {
                                    $i++; ?>
                                    <tr class="odd">
                                        <td class="col-chk"><input type="checkbox" name="cehcklist[]" value="<?= $val["db_name"]; ?>" /></td>
                                        <td class="col-first"><?= $val["db_name"]; ?></td>
                                        <td class="col-second"><a href="#">Manage '<?= $val["db_name"]; ?>' Data base</a></td>
                                        <td class="row-nav"><a href="#" class="table-edit-link">Edit</a> <span class="hidden"> | </span>
                                            <a href="<?= base_url(); ?>back_office/manage_db/delete/<?= $val["db_name"]; ?>" class="table-delete-link">Delete</a>
                                        </td>
                                    </tr>
                            <? }
                            } else {
                                echo '<tr class="odd"><td colspan="4" >No records available . </td></tr>';
                            } ?>
                        </tbody>
                    </table>
                </form>
            </div><!-- end of div.box-container -->
        </div> <!-- end of div.box -->

    </div><!-- end of div#mid-col --><!-- end of div#right-col -->

    <span class="clearFix">&nbsp;</span>
</div>