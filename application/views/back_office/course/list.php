<script>
    function delete_row(id) {
        window.location = "<?= base_url(); ?>back_office/course/delete/" + id;
        return false;
    }
</script>
<?php $base = base_url(); ?>
<script src="<?= $base ?>js/jspdf.umd.min.js"></script>
<script src="<?= $base ?>js/jspdf.plugin.autotable.min.js"></script>
<?php
$content_name = "course";
$id_content = "7";
$sego = $this->uri->segment(4);
$gallery_status = "0";
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
                            <a href="<?= base_url(); ?>back_office/course/add" id="topLink" title="Add  course">Add course</a>
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

        <form action="<?= base_url(); ?>back_office/course/delete_all" method="post" name="list_form">
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
                                    <a class="btn btn-primary btn_mrg" onclick="document.forms['list_form'].submit();" style="cursor:pointer;"><span>perform action</span></a>
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
    // Iterate through each sub-array   
    $columns_header .= " {
     allowHiding: false,
                        caption: \"#\",
                        dataField: \"PIDid_course\",
                        encodeHtml: false,
                        sortOrder: 'desc',";
    $columns_header .= " width:50,";
    $columns_header .= " 
                        render: function(dataField, type, row) {
                            return stripHtml(dataField);
                        },";
    $columns_header .= " },";
    $columns_header .= " {
  allowHiding: false,
                        caption: \"Title\",
                        dataField: \"title\",
                        encodeHtml: false,
                        sortOrder: \"desc\",";
    // $columns_header .= " width: 85,";
    $columns_header .= " },";
    $columns_header .= " {
                        caption: \"Active\",
                        dataField: \"active\",
                        encodeHtml: false,
                        sortOrder: \"desc\",";
    // $columns_header .= " width: 85,";
    $columns_header .= " },";
    $columns_header .= " {
        allowHiding: false,
                              caption: \"Minutes\",
                              dataField: \"hours\",
                              encodeHtml: false,
                              sortOrder: \"desc\",";
    // $columns_header .= " width: 85,";
    $columns_header .= " },";
    $columns_header .= " {
       visible: false,
                        caption: \"Top course\",
                        dataField: \"top_course\",
                        encodeHtml: false,
                        sortOrder: \"desc\",";
    // $columns_header .= " width: 85,";
    $columns_header .= " },";
    $columns_header .= " {
     groupIndex: 0,
                        caption: \"Category\",
                        dataField: \"category\",
                        encodeHtml: false,
                        sortOrder: \"desc\",";
    // $columns_header .= " width: 85,";
    $columns_header .= " },";
    $columns_header .= " {
    visible: false,
                        caption: \"Schedule\",
                        dataField: \"schedule\",
                        encodeHtml: false,
                        sortOrder: \"desc\",";
    // $columns_header .= " width: 85,";
    $columns_header .= " },";
    $columns_header .= " {
    visible: false,
                        caption: \"Hours\",
                        dataField: \"hours\",
                        encodeHtml: false,
                        sortOrder: \"desc\",";
    // $columns_header .= " width: 85,";
    $columns_header .= " },";
    $columns_header .= " {
    visible: false,
                        caption: \"Price\",
                        dataField: \"price\",
                        encodeHtml: false,
                        sortOrder: \"desc\",";
    // $columns_header .= " width: 85,";
    $columns_header .= " },";
    $columns_header .= " {
     visible: false,
                        caption: \"Old price\",
                        dataField: \"old_price\",
                        encodeHtml: false,
                        sortOrder: \"desc\",";
    // $columns_header .= " width: 85,";
    $columns_header .= " },";
    $DkeyExpr = "PIDid_course";
    $id_table = "id_course";
    $key_array = array();
    foreach ($info as $index => $val) {
        $i++;
        // $source_data .= " { ";

        // $source_data .= "},";
    }
    $columns_header .= '{
      visible: false,
         caption: "Actions",
        dataField: "Actions",
        encodeHtml: false,
        width: 140,
        sortOrder: "desc",
        calculateDisplayValue: function(rowData) {
                var row = "";
';
    if ($write_priv == 1) {
        $columns_header .= ' row += "<a href=\"https://www.stedtrainingcenter.com/back_office/course/edit/" + rowData.id_course + "\">Edit</a>";
                ';
    }

    if ($delete_priv == 1) {
        $columns_header .= 'row += " | ";
                row += "<a href=\"https://www.stedtrainingcenter.com/back_office/course/delete/" + rowData.id_course + "\">Delete</a>";
                 ';
    }
    $columns_header .= '

                 return row;
            } 
    },';

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
                    url: '<?php echo base_url(); ?>back_office/course/delete/' + id_user, // Adjust URL as necessary
                    method: "POST",
                    success: function(response) {
                        alert("aaaaa" + response)
                        // Check response to ensure the item was deleted successfully
                        if (response) {
                            alert("aaaaa" + response)
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
                    url: "<?php echo base_url(); ?>back_office/course/all_data",
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
        const columnChooserModes = [{
            key: 'dragAndDrop',
            name: 'Drag and drop',
        }, {
            key: 'select',
            name: 'Select',
        }];

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
                    visible: true,
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
                onExporting: function(e) {
                    const now = new Date();
                    const year = now.getFullYear();
                    const month = String(now.getMonth() + 1).padStart(2, '0');
                    const day = String(now.getDate()).padStart(2, '0');
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const fileName = `Courses_${year}-${month}-${day}_${hours}.${minutes}`;

                    const excludedCaptions = ["#", "Actions"];

                    if (e.format === "xlsx") {
                        const workbook = new ExcelJS.Workbook();
                        const worksheet = workbook.addWorksheet("Courses");

                        DevExpress.excelExporter.exportDataGrid({
                            component: e.component,
                            worksheet: worksheet,
                            autoFilterEnabled: true,
                            keepColumnWidths: true,
                            selectedRowsOnly: false,
                            topLeftCell: {
                                row: 2,
                                column: 2
                            },
                            customizeCell: function({
                                gridCell,
                                excelCell
                            }) {
                                if (!gridCell || !gridCell.column) return;

                                if (excludedCaptions.includes(gridCell.column.caption)) {
                                    excelCell.value = null;
                                    return;
                                }

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
                                            }
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
                                    excelCell.font = {
                                        italic: true
                                    };
                                }
                            },
                            customizeExportData: function(columns, rows) {
                                for (let i = columns.length - 1; i >= 0; i--) {
                                    if (excludedCaptions.includes(columns[i].caption)) {
                                        columns.splice(i, 1);
                                        rows.forEach(r => r.values.splice(i, 1));
                                    }
                                }
                            }
                        }).then(() => {
                            workbook.xlsx.writeBuffer().then((buffer) => {
                                saveAs(new Blob([buffer], {
                                    type: "application/octet-stream"
                                }), `${fileName}.xlsx`);
                            });
                        });

                        e.cancel = true;
                    }

                    if (e.format === "pdf") {
                        e.cancel = true; // Cancel the built-in export

                        const doc = new jsPDF();
                        const pageWidth = doc.internal.pageSize.getWidth();

                        // Define excluded captions
                        const excludedCaptions = ["#", "Actions"];

                        // Get visible columns and filter out the excluded ones
                        const visibleColumns = e.component.getVisibleColumns().filter(
                            col => !excludedCaptions.includes(col.caption)
                        );

                        // Extract header titles
                        const headers = visibleColumns.map(col => col.caption);

                        // Extract row data
                        const rowsData = e.component.getDataSource().items().map(dataItem => {
                            return visibleColumns.map(col => {
                                const field = col.dataField;
                                return dataItem[field];
                            });
                        });

                        // Add Header
                        const title = "Courses";
                        doc.setFontSize(16);
                        const titleWidth = doc.getTextWidth(title);
                        doc.text(title, (pageWidth - titleWidth) / 2, 20);

                        // Add Table
                        doc.autoTable({
                            head: [headers],
                            body: rowsData,
                            startY: 30,
                            styles: {
                                fontSize: 10
                            }
                        });

                        // Add Footer
                        const footer = "www.stedlb.com";
                        const footerWidth = doc.getTextWidth(footer);
                        doc.setFontSize(9);
                        doc.setTextColor("#cccccc");
                        doc.text(footer, pageWidth - footerWidth - 10, doc.internal.pageSize.getHeight() - 10);

                        // Save File
                        const now = new Date();
                        const year = now.getFullYear();
                        const month = String(now.getMonth() + 1).padStart(2, '0');
                        const day = String(now.getDate()).padStart(2, '0');
                        const hours = String(now.getHours()).padStart(2, '0');
                        const minutes = String(now.getMinutes()).padStart(2, '0');
                        const fileName = `exam_history_${year}-${month}-${day}_${hours}.${minutes}.pdf`;

                        doc.save(fileName);
                    }

                },
                customizeColumns: function(columns) {
                    columns[0].width = 50;
                    columns[2].width = 50;
                },
                columnChooser: {
                    height: "340px",
                    enabled: true,
                    mode: columnChooserModes[1].key,
                    position: {
                        my: 'right top',
                        at: 'right bottom',
                        of: '.dx-datagrid-column-chooser-button',
                    },
                    search: {
                        enabled: true,
                        editorOptions: {
                            placeholder: 'Search column'
                        },
                    },
                    selection: {
                        recursive: true,
                        selectByClick: true,
                        allowSelectAll: true,
                    },
                },
                summary: {
                    groupItems: [{
                            column: "OrderCount",
                            summaryType: "count",
                            name: "OrderCount",
                            displayFormat: "Course: {0}",
                        },
                        // {
                        //     column: "category",
                        //     summaryType: "sum"
                        // }, {
                        //     column: "Price",
                        //     summaryType: "max"
                        // }
                    ]
                },
                sortByGroupSummaryInfo: [{
                    summaryItem: "OrderCount",
                    sortOrder: "desc"
                }]
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
    <? $this->session->unset_userdata("error_message"); ?>