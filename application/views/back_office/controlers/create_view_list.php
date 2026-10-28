<?php
$list_view = '<script>
    function delete_row(id) {
        window.location = "<?= base_url(); ?>back_office/' . $table_name . '/delete/" + id;
        return false;
    }
</script>';


$content_name = $table_name;
$cond1["name"] = str_replace("_", " ", $content_name);
$id_content = $this->fct->getonecell("content_type", "id_content", $cond1);
$gallery_status = $this->fct->getonecell("content_type", "gallery", $cond1);
$list_view .= ' 
<?php
$content_name="' . $content_name . '";
$id_content="' . $id_content . '";
$sego =$this->uri->segment(4);
$gallery_status="' . $gallery_status . '";
if($this->session->userdata("level") == 0){
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

<body class="dx-viewport">
    <div class="demo-container">
        <div class="long-title">
            <div class="span10-fluid">
                <ul class="breadcrumb">
                    <li class="active">
                        <?php echo $title; ?>
                    </li>
                    <? if ($write_priv == 1) { ?>
                        <li class="pull-right">
                            <a href="<?= base_url(); ?>back_office/' . $table_name . '/add" id="topLink" title="Add  ' . $table_name . '">Add ' . str_replace("_", " ", $content_name) . '</a>
                        </li><? } ?>
                </ul>
            </div>
        </div>
        <div id="result"></div>
        <? if ($this->session->userdata("success_message")) { ?>
            <div class="alert alert-success">
                <?= $this->session->userdata("success_message"); ?>
            </div>
        <? } ?>

        <form action="<?= base_url(); ?>back_office/' . $table_name . '/delete_all" method="post" name="list_form">
            <div id="gridContainer"></div>

            <div class="tfoot">
                <table style="width: 100%;">
                    <tr>
                        <td class="col-chk"><input type="checkbox" id="checkAllAuto" /></td>
                        <td colspan="3">
                            <div class="pull-right">
                                <? if ($delete_priv == 1) { ?>
                                    <select class="form-select" name="check_option">
                                        <option value="option1">Bulk Options</option>
                                        <option value="delete_all">Delete All</option>
                                    </select>
                                    <a class="btn btn-primary btn_mrg" onclick="document.forms[\'list_form\'].submit();" style="cursor:pointer;"><span>perform action</span></a>
                            </div> <? } ?>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
    <?php

    // $source_data = "[";
    $columns_header = "[";

    $i = 0;
    ////////////////////////////////////////
    $all_keys = array();
    // Iterate through each sub-array
    foreach ($info as $sub_array) {
        // Merge the keys of the current sub-array into the $all_keys array
        $all_keys = array_merge($all_keys, array_keys($sub_array));
    }
    // Remove duplicate keys
    $unique_keys = array_unique($all_keys);

    $array_notdisplay = array("deleted", "created_date", "updated_date", "deleted_date", "sort_order", "status");
    // Iterate through each sub-array   ';


$id_table = "id_" . $table_name;
$list_view .= '
    $columns_header .= " {
                        caption: \"#\",
                        dataField: \"PID' . $id_table . '\",
                        encodeHtml: false,
                        sortOrder: \'desc\',";
    $columns_header .= " width:50,";            
    $columns_header .= " 
                        render: function(dataField, type, row) {
                            return stripHtml(dataField);
                        },";
                    $columns_header .= " },";';


// $list_view .= '
//     $columns_header .= " {
//                         caption: \"#\",
//                         dataField: \"' . $id_table . '\",
//                         encodeHtml: false,
//                         sortOrder: \'desc\',";
//     $columns_header .= " width:50,";            
//     $columns_header .= " },";';

foreach ($attr as $v) {

    // echo "<pre>";
    // print_r($v);
    // exit();

    $exception = 'encodeHtml: false,';
    if ($v["type"] == "4") {
        $exception = 'encodeHtml: false,';
    }
    $v_nsme = str_replace(" ", "_", $v["name"]);
    $list_view .= '
    $columns_header .= " {
                        caption: \"' . ucfirst($v["name"]) . '\",
                        dataField: \"' .  $v_nsme . '\",
                        ' . $exception . '
                        sortOrder: \"desc\",";
                        // $columns_header .= " width: 85,";
                        $columns_header .= " },";';
}
$list_view .= '
$DkeyExpr="PID' . $id_table . '";';
$list_view .= '
$id_table="' . $id_table . '";';

$list_view .= ' $key_array = array();
foreach ($info as $index => $val) {
    $i++;
    // $source_data .= " { ";
    ';

$vvv = ' <input type=checkbox name=cehcklist[] value=$val[' . $id_table . '] />';
//$list_view .= ' $source_data .= " PID' . $id_table . ' : \"' . $vvv . '\",";';
//$list_view .= ' $source_data .= " ' . $id_table . ' : \"$val[' . $id_table . ']\",";';

foreach ($attr as $v) {
    // $v_nsme = str_replace(" ", "_", $v["name"]);
    // $vvvvvvv = '\"$val[' . $v_nsme . ']\"';


    // if ($v["type"] == 4) {

    //     $image_code = '
    //     if ($val["image"] != "") {
    //         $image =  \'<a onclick=popup("' . base_url() . '/uploads/stedtest/\' . $val["image"] . \'")    class="blue gallery" title="\' . $val["title"] . \'">show image</a>\';
    //     } else {
    //         $image =  \'<small class="blue">No Image Available</small>\';
    //     }

    //     $source_data .= "' .  $v["name"] . ': \'$image\',";
    //     ';

    //     $list_view .=  $image_code;
    // } else {
    //     $list_view .= ' $source_data .= "' .  $v["name"] . ': ' . $vvvvvvv . ',"; ';
    // }

    // echo "<pre>";
    // print_r($val);
    // echo "<br>";
    // echo $v["type"];
    // exit();
}

// $list_view .= '   

//     $source_data .= "},";
// }

// $columns_header .= " {
//     caption: \'Actions\',
//     dataField: \'Actions\',
//     encodeHtml: false,
//     width:140,
//     sortOrder: \'desc\',"; ';


$list_view .= '  
    // $source_data .= "},";
}';


$list_view .= '
    $columns_header .= \'{
        caption: "Actions",
        dataField: "Actions",
        encodeHtml: false,
        width: 140,
        sortOrder: "desc",
        calculateDisplayValue: function(rowData) {
                var row = "";
\';
    if ($write_priv == 1) {
        $columns_header .= \' row += "<a href=\"' . base_url() . 'back_office/' . $table_name . '/edit/" + rowData.' . $id_table . ' + "\">Edit</a>";
                \';
    }
  
 if ($delete_priv == 1) {
        $columns_header .= \'row += " | ";
                row += "<a href=\"' . base_url() . 'back_office/' . $table_name . '/delete/" + rowData.' . $id_table . ' + "\">Delete</a>";
                 \';
    }
    $columns_header .=\'

                 return row;
            } 
    },\';
';



// $list_view .= ' 
//     $columns_header .= "  calculateDisplayValue: function(rowData) { var row = \'\';";
//         $columns_header .= " row += \'<div id=\"rowData.' . $id_table . '\" style=\"cursor: pointer; text-align: center;\">\'; ";
//         $columns_header .= " row += \'<a href=\"" . base_url() . "back_office/' . $table_name . '/edit/\' + rowData . ' . $id_table . ' + \'\" class=\"link\"><i class=\"bx bx-edit\" style=\"font-size: small;\" title=\"Edit\"></i></a>\';";
//         $columns_header .= " row += \' | <a style=\"font-size: small;\" title=\"delete\" class=\"bx bx-trash me-1\" onclick=\"functiondeleteuser(\' + rowData .' . $id_table . ' + \')\">\';";
//         $columns_header .= " row += \'</a>\';";
//         $columns_header .= " row += \'</div>\';";
// $list_view .= '  $columns_header .= "  return row;  }";';

$list_view .= ' 
// $columns_header .= " }";

// $source_data .= " ]";
$columns_header .= " ]";


// echo "<pre>";
// print_r($source_data);
// exit();
 ?>
<script>
// Function to strip HTML tags
function stripHtml(html) {
    var tmp = document.createElement("DIV");
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || "";
}

    function functiondeleteuser(id_user) {
        if (confirm("Are you sure you want to delete this item?")) {
            // Example AJAX request to your server-side deletion logic
 
            $.ajax({
                url: \'<?php echo base_url(); ?>back_office/' . $table_name . '/delete/\' + id_user, // Adjust URL as necessary
                method: "POST",
                success: function(response) {
                    alert("aaaaa"+response)
                    // Check response to ensure the item was deleted successfully
                    if (response) {alert("aaaaa"+response)
                        DevExpress.ui.notify("Exam deleted successfully.", "success", 3000);

                        // Refresh the grid to reflect deletion
                        $("#gridContainer").dxDataGrid("instance").refresh();
                        
                    } else {
                        // Handle failure (item not found, server error, etc.)
                        DevExpress.ui.notify("Failed to delete exam.", "error", 3000);
                    }
                },
                error: function() {
                    // Handle AJAX error
                    DevExpress.ui.notify("Error contacting server.", "error", 3000);
                }
            });
        }
    }

    const employees = new DevExpress.data.CustomStore({
        key: "<?php echo $id_table; ?>",
        load(loadOptions) {
            const deferred = $.Deferred();
            const args = {};
            [
                "skip",
                "take",
                "requireTotalCount",
                "requireGroupCount",
                "sort",
                "filter",
                "totalSummary",
                "group",
                "groupSummary",
            ].forEach((i) => {
                if (i in loadOptions && isNotEmpty(loadOptions[i])) {
                    args[i] = JSON.stringify(loadOptions[i]);
                }
            });
            // args.idarticle = $("#id_course").val();

            $.ajax({
                url: "<?php echo base_url(); ?>back_office/' . $table_name . '/all_data",
                method: "POST",
                dataType: "json",
                data: args,
                success(result) {
                    // console.log("Aaaaaaaaaaa" + result);
                    deferred.resolve(result.data, {
                        totalCount: result.totalCount,
                        summary: result.summary,
                        groupCount: result.groupCount,
                    });
                },
                error() {
                    deferred.reject("Data Loading Error");
                },
                timeout: 5000,
            });

            return deferred.promise();
        },
    });

    // This code is used for backwards compatibility with the older jsPDF variable name
    window.jsPDF = window.jspdf.jsPDF;
    $(() => {
        $("#gridContainer").dxDataGrid({
            dataSource: employees,
            columns: <?php echo $columns_header; ?>,
            showBorders: true,
            rtlEnabled: false,
            selection: {
                mode: "single",
            },
            wordWrapEnabled: true,
            allowColumnReordering: true,
            allowColumnResizing: true,
            columnAutoWidth: true,
            rowAlternationEnabled: true,
            hoverStateEnabled: true,
            columnHidingEnabled: true,
            columnChooser: {
                enabled: true
            },
            filterRow: {
                visible: false,
            },
            headerFilter: {
                visible: true,
            },
            searchPanel: {
                visible: true,
                width: 200,
                placeholder: "Search"
            },
            groupPanel: {
                visible: true,
            },
            grouping: {
                autoExpandAll: false,
            },

            scrolling: {
                rowRenderingMode: "virtual",
            },
            paging: {
                pageSize: 10,
            },
            pager: {
                visible: true,
                allowedPageSizes: [5, 10, "all"],
                showPageSizeSelector: true,
                showInfo: true,
                showNavigationButtons: true,
            },

            export: {
                enabled: true,
                formats: ["xlsx", "pdf"],
            },
            onExporting(e) {

                if (e.format == "pdf") {
                    const doc = new jsPDF();
                    const lastPoint = {
                        x: 0,
                        y: 0
                    };

                    DevExpress.pdfExporter.exportDataGrid({
                        jsPDFDocument: doc,
                        component: e.component,
                        topLeft: {
                            x: 1,
                            y: 15
                        },
                        columnWidths: [30, 20, 30, 15, 22, 22, 20, 20],
                        customDrawCell({
                            rect
                        }) {
                            if (lastPoint.x < rect.x + rect.w) {
                                lastPoint.x = rect.x + rect.w;
                            }
                            if (lastPoint.y < rect.y + rect.h) {
                                lastPoint.y = rect.y + rect.h;
                            }
                        },
                    }).then(() => {
                        // header
                        const header = "Country Area, Population, and GDP Structure";
                        const pageWidth = doc.internal.pageSize.getWidth();
                        const headerWidth = doc.getTextDimensions(header).w;

                        doc.setFontSize(15);
                        doc.text(header, (pageWidth - headerWidth) / 2, 20);

                        // footer
                        const footer = "www.stedlb.com";
                        const footerWidth = doc.getTextDimensions(footer).w;

                        doc.setFontSize(9);
                        doc.setTextColor("#cccccc");
                        doc.text(footer, (lastPoint.x - footerWidth), lastPoint.y + 5);

                        doc.save("Companies.pdf");
                    });
                } /// end pdf 
                else {

                    const workbook = new ExcelJS.Workbook();
                    const worksheet = workbook.addWorksheet("Companies");

                    worksheet.columns = [{
                        width: 5
                    }, {
                        width: 30
                    }, {
                        width: 25
                    }, {
                        width: 15
                    }, {
                        width: 25
                    }, {
                        width: 40
                    }, ];

                    DevExpress.excelExporter.exportDataGrid({
                        component: e.component,
                        worksheet,
                        keepColumnWidths: false,
                        topLeftCell: {
                            row: 2,
                            column: 2
                        },
                        customizeCell(options) {
                            const {
                                gridCell
                            } = options;
                            const {
                                excelCell
                            } = options;

                            if (gridCell.rowType === "data") {
                                if (gridCell.column.dataField === "Picture") {
                                    excelCell.value = undefined;

                                    const image = workbook.addImage({
                                        base64: gridCell.value,
                                        extension: "png",
                                    });

                                    worksheet.getRow(excelCell.row).height = 90;
                                    worksheet.addImage(image, {
                                        tl: {
                                            col: excelCell.col - 1,
                                            row: excelCell.row - 1
                                        },
                                        br: {
                                            col: excelCell.col,
                                            row: excelCell.row
                                        },
                                    });
                                }

                                if (gridCell.column.dataField === "Phone") {
                                    excelCell.value = parseInt(gridCell.value, 10);
                                    excelCell.numFmt = "[<=9999999]###-####;(###) ###-####";
                                }
                                if (gridCell.column.dataField === "Website") {
                                    excelCell.value = {
                                        text: gridCell.value,
                                        hyperlink: gridCell.value
                                    };
                                    excelCell.font = {
                                        color: {
                                            argb: "FF0000FF"
                                        },
                                        underline: true
                                    };
                                    excelCell.alignment = {
                                        horizontal: "left"
                                    };
                                }
                            }
                            if (gridCell.rowType === "group") {
                                excelCell.fill = {
                                    type: "pattern",
                                    pattern: "solid",
                                    fgColor: {
                                        argb: "BEDFE6"
                                    }
                                };
                            }
                            if (gridCell.rowType === "totalFooter" && excelCell.value) {
                                excelCell.font.italic = true;
                            }
                        },
                    }).then(() => {
                        workbook.xlsx.writeBuffer().then((buffer) => {
                            saveAs(new Blob([buffer], {
                                type: "application/octet-stream"
                            }), "Companies.xlsx");
                        });
                    });
                } /// export to excel
            },
        });
    });
</script>

<style>
.bx {
     font-size: 1.15rem !important;
    color: inherit !important;
 }
    
    .dx-texteditor-input-container input[type="text"] {
        margin-bottom: 0 !important;
    }

   .dx-datagrid .dx-link {
        text-decoration: none !important;
    }

    .dx-header-filter-indicator {
        text-transform: capitalize !important;
    }

    .dx-datagrid .dx-header-row>td {
        background-color: #eee;

        font-weight: bold;
    }

    #gridContainer {
        weight: 100%;
    }
</style>
</form>
</div>
</div>
</div>
</div>
<? $this->session->unset_userdata("success_message"); ?>
<? $this->session->unset_userdata("error_message"); ?>';
