<?php
/* ==========================================================================
   USERS LIST - admin (modern design - backup: userlist____28_10_2026.php)
   Grid: DevExtreme #gridContainer (data: users/all_user) - styles in css/pages/manage.css
   ========================================================================== */
$this->load->view('include/_page_hero', array(
    'ph_title'   => 'Users',
    'ph_eyebrow' => 'Admin tools',
    'ph_text'    => 'Every registered account in one place. Search, filter and group the list, edit a profile or export it to Excel and PDF.',
    'ph_crumbs'  => array(
        array('label' => 'Home', 'url' => base_url()),
        array('label' => 'My profile', 'url' => base_url() . 'edit-profile'),
        array('label' => 'Users'),
    ),
    'ph_stats'   => array(
        array('icon' => 'fas fa-users', 'value' => '<span data-mx-mg-total>&hellip;</span>', 'label' => 'Registered users'),
        array('icon' => 'fas fa-file-export', 'value' => 'Excel &middot; PDF', 'label' => 'One-click export'),
        array('icon' => 'fas fa-shield-halved', 'value' => 'Admin', 'label' => 'Restricted access'),
    ),
));
?>
<div class="mx-inner mx-mg">
    <section id="course-details" class="mx-mg-section">
        <div class="container">

            <?php $this->load->view('include/_manage_nav', array('mg_active' => 'listusers')); ?>

            <div class="mx-mg-card" data-reveal>
                <div class="mx-mg-head">
                    <span class="mx-mg-ico" aria-hidden="true"><i class="fas fa-users"></i></span>
                    <div class="mx-mg-ttl">
                        <h2>All users</h2>
                        <p>Grouped by status. Use the pencil to edit a profile and the bin to remove an account.</p>
                    </div>
                    <span class="mx-mg-count"><b data-mx-mg-total>&hellip;</b> users</span>
                </div>
                <div class="mx-mg-grid grid-container">
                    <div id="gridContainer" class="dx-responsive-scroll"></div>
                </div>
                <div class="mx-mg-tips" aria-hidden="true">
                    <span><i class="fas fa-arrow-down-wide-short"></i> Click a column title to sort</span>
                    <span><i class="fas fa-filter"></i> Use the funnel to filter</span>
                    <span><i class="fas fa-layer-group"></i> Drag a column to the bar above to group</span>
                    <span><i class="fas fa-table-columns"></i> Show or hide columns with the column chooser</span>
                </div>
            </div>

        </div>
    </section>
</div>
<script>
    // const employees = new DevExpress.data.CustomStore({
    //     key: 'id_user',
    //     load(loadOptions) {
    //         const deferred = $.Deferred();
    //         const args = {};
    //         [
    //             'skip',
    //             'take',
    //             'requireTotalCount',
    //             'requireGroupCount',
    //             'sort',
    //             'filter',
    //             'totalSummary',
    //             'group',
    //             'groupSummary',
    //         ].forEach((i) => {
    //             if (i in loadOptions && isNotEmpty(loadOptions[i])) {
    //                 args[i] = JSON.stringify(loadOptions[i]);
    //             }
    //         });
    //         args.idarticle = $('#id_course').val();

    //         $.ajax({
    //             url: '<?php echo base_url(); ?>users/all_user',
    //             method: 'POST',
    //             dataType: 'json',
    //             data: args,
    //             success(result) {
    //                 // console.log("Aaaaaaaaaaa" + result);
    //                 deferred.resolve(result.data, {
    //                     totalCount: result.totalCount,
    //                     summary: result.summary,
    //                     groupCount: result.groupCount,
    //                 });
    //             },
    //             error() {
    //                 deferred.reject('Data Loading Error');
    //             },
    //             timeout: 5000,
    //         });

    //         return deferred.promise();
    //     },
    // });

    let course = '';
    $.ajax({
        url: '<?php echo base_url(); ?>users/course',
        method: 'POST',
        dataType: 'json',
        // data: args,
        success(result) {
            course = result.data;
            console.log("aaaa" + course);
        },
        error() {
            // Handle error if needed
        },
        // timeout: 5000,
    });

    // $(function() {
    //     $('#gridContainer').dxDataGrid({
    //         dataSource: employees,
    //         keyExpr: 'id_user',
    //         showBorders: true,
    //         searchPanel: {
    //             visible: true,
    //             width: 240,
    //             placeholder: "Pesquisar..."
    //         },
    //         headerFilter: {
    //             visible: true
    //         },
    //         columns: [{
    //                 dataField: 'image',
    //                 caption: 'Image',
    //                 dataType: 'string',
    //                 alignment: 'center',
    //                 cellTemplate: function(container, options) {
    //                     if (options.value) {
    //                         $('<img class="image-feedback">').attr('src', options.value).appendTo(container);
    //                     } else {
    //                         $('<img class="image-feedback">').attr('src', '<?php echo base_url(); ?>images/nopic.jpg').appendTo(container);
    //                     }
    //                 },
    //                 width: '70px',
    //             },
    //             {
    //                 dataField: 'name',
    //                 caption: 'Name',
    //                 dataType: 'string',
    //                 alignment: 'left',
    //             },
    //             {
    //                 dataField: 'title',
    //                 caption: 'role',
    //                 dataType: 'string',
    //                 alignment: 'left',
    //             },
    //             {
    //                 dataField: 'phone',
    //                 caption: 'Phone',
    //                 dataType: 'string',
    //                 alignment: 'left',
    //             },
    //             {
    //                 dataField: 'email',
    //                 caption: 'Email',
    //                 dataType: 'string',
    //                 alignment: 'left',
    //             },
    //             {
    //                 dataField: 'username',
    //                 caption: 'Username',
    //                 dataType: 'string',
    //                 alignment: 'left',
    //             },
    //             {
    //                 dataField: 'Actions',
    //                 width: 100,
    //                 caption: '',
    //                 dataType: 'string',

    //                 alignment: 'left',
    //                 encodeHtml: false,
    //                 width: '100',
    //                 sortOrder: null,
    //                 allowSorting: false, // Disable sorting for this column
    //                 allowFiltering: false, // Disable filtering for this column,
    //                 calculateDisplayValue: function(rowData) {
    //                     var row = "";
    //                     row += "<div id=" + rowData.id_user + " style='cursor: pointer;text-align:center'>";
    //                     row += "<a href='<?php echo base_url(); ?>edit-profile/" + rowData.id_user + "' class='link'><i class='fa fa-edit' style='font-size: small;' title='Edit'></i></i></a>";
    //                     row += " | <a style='font-size: small;' title='delete'class='dx-link dx-link-delete dx-icon-trash dx-link-icon link' onclick='functiondeleteuser(" + rowData.id_user + ")' >";
    //                     row += "</a>";
    //                     return row;
    //                 }
    //             }
    //         ],
    //     });
    // });
    DevExpress.config({
        rtlEnabled: true
    });
    DevExpress.localization.locale('ar');
    window.jsPDF = window.jspdf.jsPDF;

    $(function() {
        const store = new DevExpress.data.CustomStore({
            key: 'id_user',
            load(loadOptions) {
                const deferred = $.Deferred();
                const args = {};

                [
                    'skip',
                    'take',
                    'requireTotalCount',
                    'requireGroupCount',
                    'sort',
                    'filter',
                    'totalSummary',
                    'group',
                    'groupSummary',
                ].forEach((i) => {
                    if (i in loadOptions && isNotEmpty(loadOptions[i])) {
                        args[i] = JSON.stringify(loadOptions[i]);
                    }
                });
                args.idarticle = $('#id_course').val();

                $.ajax({
                    url: '<?php echo base_url(); ?>users/all_user',
                    method: 'POST',
                    dataType: 'json',
                    data: args,
                    success(result) {
                        deferred.resolve(result.data, {
                            totalCount: result.totalCount,
                            summary: result.summary,
                            groupCount: result.groupCount,
                        });
                    },
                    error() {
                        deferred.reject('Data Loading Error');
                    },
                    timeout: 5000,
                });

                return deferred.promise();
            },
        });
        var myGrid = $('#gridContainer').dxDataGrid({
            keyExpr: 'id_user',
            rtlEnabled: false,
            dataSource: store,
            columns: [{
                    dataField: 'image',
                    caption: 'Image',
                    dataType: 'string',
                    alignment: 'center',
                    cellTemplate: function(container, options) {
                        if (options.value) {
                            $('<img class="image-feedback">').attr('src', options.value).appendTo(container);
                        } else {
                            $('<img class="image-feedback">').attr('src', '<?php echo base_url(); ?>images/nopic.jpg').appendTo(container);
                        }
                    },
                    width: '70px',
                },
                {
                    dataField: 'name',
                    caption: 'Name',
                    dataType: 'string',
                    alignment: 'left',
                },
                {
                    dataField: 'title',
                    caption: 'role',
                    dataType: 'string',
                    alignment: 'left',
                },
                {
                    dataField: 'phone',
                    caption: 'Phone',
                    dataType: 'string',
                    alignment: 'left',
                },
                {
                    dataField: 'email',
                    caption: 'Email',
                    dataType: 'string',
                    alignment: 'left',
                },
                {
                    dataField: 'username',
                    caption: 'Username',
                    dataType: 'string',
                    alignment: 'left',
                },
                {
                    dataField: 'status',
                    caption: 'status',
                    dataType: 'string',
                    alignment: 'left',
                    groupIndex: 0, // grouping data

                },
                {
                    dataField: 'Actions',
                    width: 100,
                    caption: '',
                    dataType: 'string',

                    alignment: 'left',
                    encodeHtml: false,
                    width: '100',
                    sortOrder: null,
                    allowSorting: false, // Disable sorting for this column
                    allowFiltering: false, // Disable filtering for this column,
                    calculateDisplayValue: function(rowData) {
                        var row = "";
                        row += "<div id=" + rowData.id_user + " style='cursor: pointer;text-align:center'>";
                        row += "<a href='<?php echo base_url(); ?>edit-profile/" + rowData.id_user + "' class='link'><i class='fa fa-edit' style='font-size: small;' title='Edit'></i></i></a>";
                        row += " | <a style='font-size: small;' title='delete'class='dx-link dx-link-delete dx-icon-trash dx-link-icon link' onclick='functiondeleteuser(" + rowData.id_user + ")' >";
                        row += "</a>";
                        return row;
                    }
                }
            ],
            summary: {
                groupItems: [{
                        column: 'id',
                        summaryType: 'count',
                        displayFormat: '{0}',
                    },

                    {
                        column: 'rating',
                        summaryType: 'avg',
                        dataType: 'number',
                        displayFormat: function(value) {
                            try {
                                const numValue = parseFloat(value);
                                if (!isNaN(numValue)) {
                                    return numValue.toFixed(2);
                                } else {
                                    console.error('Invalid value:', value);
                                    return value;
                                }
                            } catch (error) {
                                // Handle any exceptions that may occur
                                console.error('Error:', error);
                                return value;
                            }
                        },
                        showInGroupFooter: false,
                        alignByColumn: true,
                    },

                ]
            },
            showBorders: true,
            selection: {
                mode: 'single',
            },
            wordWrapEnabled: true,
            allowColumnReordering: true,
            allowColumnResizing: true,
            columnAutoWidth: true,
            rowAlternationEnabled: true,
            hoverStateEnabled: true,
            columnHidingEnabled: true,
            columnChooser: {
                height: "340px",
                enabled: true,
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
            filterRow: {
                visible: false,
            },
            headerFilter: {
                visible: true,
            },
            searchPanel: {
                visible: true,
                width: 240,
                placeholder: "search..."
            },
            groupPanel: {
                visible: true,
            },
            grouping: {
                autoExpandAll: false,
            },
            export: {
                enabled: true,
                formats: ['xlsx', 'pdf'],
                allowExportSelectedData: false,
            },
            onExporting(e) {


                var gridInstance = $("#gridContainer").dxDataGrid("instance");
                gridInstance.columnOption("image", "visible", false);
                gridInstance.columnOption("Actions", "visible", false);

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
                        columnWidths: [30, 20, 30, 45, 35, 15],
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

                        gridInstance.columnOption("image", "visible", true);
                        gridInstance.columnOption("Actions", "visible", true);
                        // header
                        const header = "userlist";
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

                        doc.save("userlist.pdf");
                    });
                } /// end pdf 
                else {
                    const workbook = new ExcelJS.Workbook();
                    const worksheet = workbook.addWorksheet('userlist');
                    e.component.columnOption('edit', 'visible', false);
                    DevExpress.excelExporter.exportDataGrid({
                        component: e.component,
                        worksheet,
                        autoFilterEnabled: true,
                        encodeHtml: false,
                        customizeCell: function(options) {
                            const {
                                gridCell
                            } = options;
                            const {
                                excelCell
                            } = options;
                            if (gridCell.rowType === 'data') {
                                if (gridCell.column.dataField === 'description') {
                                    excelCell.value = excelCell.value.replace(/<[^>]+>/g, '').replace(/&nbsp;/g, ' ');
                                }
                            }

                        }
                    }).then(() => {
                        e.component.columnOption('edit', 'visible', true);
                        gridInstance.columnOption("image", "visible", true);
                        gridInstance.columnOption("Actions", "visible", true);

                        workbook.xlsx.writeBuffer().then((buffer) => {
                            saveAs(new Blob([buffer], {
                                type: 'application/octet-stream'
                            }), 'userlist.xlsx');
                        });
                    });
                }

            },
            paging: {
                pageSize: 10,
            },
            pager: {
                showPageSizeSelector: true,
                allowedPageSizes: [5, 10, 25, 50, 100],
                displayMode: 'compact',
                visible: true,
                showInfo: true,
                showNavigationButtons: true,
            },

            showColumnLines: true,
            showRowLines: true,


        });
    });

    function functiondeleteuser(id_user) {
        mxConfirm("This item will be permanently deleted. This action cannot be undone.", { danger: true, title: "Delete this item?", confirmText: "Yes, delete" }).then(function (ok) {
            if (!ok) return;
            // Example AJAX request to your server-side deletion logic
            $.ajax({
                url: '<?php echo base_url(); ?>users/deleteuser/' + id_user, // Adjust URL as necessary
                method: 'POST',
                success: function(response) {
                    // Check response to ensure the item was deleted successfully
                    if (response) {
                        DevExpress.ui.notify('Exam deleted successfully.', 'success', 3000);

                        // Refresh the grid to reflect deletion
                        $('#gridContainer').dxDataGrid('instance').refresh();
                    } else {
                        // Handle failure (item not found, server error, etc.)
                        DevExpress.ui.notify('Failed to delete exam.', 'error', 3000);
                    }
                },
                error: function() {
                    // Handle AJAX error
                    DevExpress.ui.notify('Error contacting server.', 'error', 3000);
                }
            });
        });
    }

    // function userAddEdit(id_user) {
    //         $.ajax({
    //             url: '<?php echo base_url(); ?>users/userAddEdit/' + id_user, // Adjust URL as necessary
    //             method: 'POST',
    //             success: function(response) {
    //                 // Check response to ensure the item was deleted successfully
    //                 if (response) {
    //                     $("#userAddEdit").html(response);
    //                     //  DevExpress.ui.notify('user updated successfully.', 'success', 3000);

    //                     // Refresh the grid to reflect deletion
    //                    // $('#gridContainer').dxDataGrid('instance').refresh();
    //                 } else {
    //                     // Handle failure (item not found, server error, etc.)
    //                     DevExpress.ui.notify('Failed to updated user.', 'error', 3000);
    //                 }
    //             },
    //             error: function() {
    //                 // Handle AJAX error
    //                 DevExpress.ui.notify('Error contacting server.', 'error', 3000);
    //             }
    //         });
    // }
</script>
<script>
    // Modern UI: show the record count on the page (read-only - the grid itself is set up above; styles: css/pages/manage.css)
    $(function() {
        var node = document.getElementById('gridContainer'),
            grid = node && window.DevExpress && DevExpress.ui.dxDataGrid.getInstance(node);
        if (!grid) return;
        grid.on('contentReady', function(e) {
            var n = e.component.totalCount();
            if (n >= 0) $('[data-mx-mg-total]').text(n.toLocaleString('en-US'));
            $(node).closest('.mx-mg-card').addClass('is-loaded');
        });
    });
</script>
<!-- ------------------------- -->

<!-- Modal -->
<!-- <div class="modal fade" id="myModalmyModal" tabindex="-1" role="dialog" aria-labelledby="myModalmyModal" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="examModalLabel">
                    Add/Edit
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="userAddEdit">
                <div class="tab-content" id="myTabContent">
                    <form action="post" action="" id="exam-form">
                        ssssssssss
                    </form>
                    <div class="footerpopup">
                        <span class="tab-number-exam"></span>
                        <div class="group">
                            <input type="submit" class="button" value="Save" onclick="submitformuser()">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
<!-- userAddEdit------------------------ -->