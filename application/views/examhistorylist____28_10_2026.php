<!-- INNER PAGE WRAPPER============================================= -->
<div class="inner-page-wrapper">
    <!-- BREADCRUMB============================================= -->
    <div id="breadcrumb" class="division">
        <div class="container">
            <div class="row">
                <!-- BREADCRUMB NAV -->
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>edit-profile">Home</a></li>
                            <!-- <?php if ($oncourse) { ?><li class="breadcrumb-item"><?php echo $oncourse['categtitle']; ?><?php } ?> -->


                            <?php
                            if (count($oncourse)) { ?>
                                <li class="breadcrumb-item active"><?php echo $oncourse['title']; ?>
                                <?php } else { ?>
                                <li class="breadcrumb-item active">Exams</li>
                            <?php }
                            if ($userenrollement == 2) { ?>
                                <li class="breadcrumb-item">Exam History</li>
                            <?php }
                            ?>
                            <input type="hidden" name='id_course' id='id_course' value="<?php echo $oncourse['id_course']; ?>"></li>

                        </ol>
                    </nav>
                </div>

            </div> <!-- End row -->
        </div> <!-- End container -->
    </div> <!-- END BREADCRUMB -->
    <!-- COURSE DETAILS============================================= -->
    <section id="course-details" class="wide-40 course-section division">
        <div class="container">
            <div class="row grid-container">
                <div id="gridContainer" class="dx-responsive-scroll"></div>
            </div>
        </div>
    </section>
</div>
<script>
    DevExpress.config({
        rtlEnabled: true
    });
    DevExpress.localization.locale('ar');
    window.jsPDF = window.jspdf.jsPDF;

    $(function() {
        const store = new DevExpress.data.CustomStore({
            key: 'id_exam',
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
                // args.id_course = '< ?php echo $id_course;?>';
                args.idarticle = $('#id_course').val();
                // args.active = $('#active').val();
                // args.searchfrom = $('#searchfrom').val();
                // args.searchto = $('#searchto').val();

                $.ajax({
                    url: '<?php echo base_url(); ?>listexamhistory',
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
            rtlEnabled: false,
            dataSource: store,
            paging: {
                pageSize: 10,
            },
            pager: {
                showPageSizeSelector: true,
                allowedPageSizes: [10, 25, 50, 100],
                displayMode: 'compact'
            },
            groupPanel: {
                visible: true,
                emptyPanelText: 'search By ',
            },
            grouping: {
                autoExpandAll: true,
            },
            showColumnLines: true,
            rowAlternationEnabled: true,
            showRowLines: true,
            showBorders: true,
            // filterPanel: {
            //     visible: true
            // },
            headerFilter: {
                visible: true
            },
            searchPanel: {
                visible: true,
                placeholder: 'Search',
            },
            columnFixing: {
                enabled: true,
            },
            allowColumnReordering: true,
            allowColumnResizing: true,
            columnResizingMode: 'widget',
            // columnAutoWidth: true,
            columns: [{
                    dataField: 'id_exam',
                    caption: 'Id',
                    dataType: 'number',
                    alignment: 'left',
                    width: '50px'
                }, {
                    dataField: 'course',
                    caption: 'Course',
                    dataType: 'string',
                    alignment: 'left',
                }, {
                    dataField: 'exam',
                    caption: 'Exam',
                    dataType: 'string',
                    alignment: 'left',
                },
                {
                    dataField: 'grade',
                    caption: 'Grade',
                    dataType: 'string',
                    alignment: 'left',
                },
                {
                    dataField: 'score',
                    caption: 'Score',
                    dataType: 'string',
                    alignment: 'left',
                    // encodeHtml: false,
                    // calculateDisplayValue: function(rowData) {

                    //     var row =
                    //         "<a href='articledetails.php?id=" + rowData.id_exam +
                    //         "' ><span>" + rowData.articlename + "</span></a>";
                    //     return row;

                    // }
                },
                {
                    dataField: 'number_attempts',
                    caption: 'Number of attempts',
                    dataType: 'string',
                    alignment: 'left',
                },
                {
                    dataField: 'created_date',
                    caption: 'Date',
                    dataType: 'string',
                    alignment: 'left',
                },

            ],
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
                width: 100,
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

                // var gridInstance = $("#gridContainer").dxDataGrid("instance");
                // gridInstance.columnOption("image", "visible", false);
                // gridInstance.columnOption("Actions", "visible", false);

                if (e.format === "pdf") {
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
                        columnWidths: [10, 40, 40, 15, 23, 23, 12, 15],
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
                        customizeCell: function(options) {
                            const {
                                gridCell,
                                pdfCell
                            } = options;
                            if (gridCell.rowType === 'data') {
                                console.log(gridCell.column.dataField);

                                if (gridCell.column.dataField === 'Actions') {
                                    // Access the `completion_percentage` from the current row data
                                    const completionPercentage = gridCell.data.completion_percentage;

                                    // Create a text representation including the completion percentage
                                    const actionText = `${completionPercentage}%`;
                                    // Assign the text to the PDF cell
                                    pdfCell.text = actionText;
                                }
                            }

                        }
                    }).then(() => {
                        // Add header
                        const header = "Exam";
                        const pageWidth = doc.internal.pageSize.getWidth();
                        const headerWidth = doc.getTextDimensions(header).w;

                        doc.setFontSize(15);
                        doc.text(header, (pageWidth - headerWidth) / 2, 20);

                        // Add footer
                        const footer = "www.stedlb.com";
                        const footerWidth = doc.getTextDimensions(footer).w;

                        doc.setFontSize(9);
                        doc.setTextColor("#cccccc");
                        doc.text(footer, pageWidth - footerWidth - 10, lastPoint.y + 5);

                        // Save the PDF
                        doc.save("examcourse.pdf");
                    });
                } else {
                    const workbook = new ExcelJS.Workbook();
                    const worksheet = workbook.addWorksheet('examcourse');
                    e.component.columnOption('edit', 'visible', false);
                    DevExpress.excelExporter.exportDataGrid({
                        component: e.component,
                        worksheet,
                        autoFilterEnabled: true,
                        encodeHtml: false,
                        customizeCell: function(options) {
                            const {
                                gridCell,
                                excelCell
                            } = options;

                            if (gridCell.rowType === 'data') {
                                if (gridCell.column.dataField === 'Actions' && typeof excelCell.value === 'string') {
                                    // Replace HTML tags and any &nbsp; with a space
                                    excelCell.value = excelCell.value.replace(/<[^>]+>/g, '').replace(/&nbsp;/g, ' ');

                                    // Optionally, you can also trim the string to remove any leading or trailing spaces
                                    excelCell.value = excelCell.value.trim();
                                }
                            }
                        }
                    }).then(() => {

                        // gridInstance.columnOption("image", "visible", true);
                        // gridInstance.columnOption("Actions", "visible", true);
                        e.component.columnOption('edit', 'visible', true);
                        workbook.xlsx.writeBuffer().then((buffer) => {
                            saveAs(new Blob([buffer], {
                                type: 'application/octet-stream'
                            }), 'examcourse.xlsx');
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

        });
    });

    function functiondelete(id_exam, id_role) {

        if (confirm("Are you sure you want to delete this item?")) {
            // Example AJAX request to your server-side deletion logic
            $.ajax({
                url: '<?php echo base_url(); ?>deleteexam/' + id_exam, // Adjust URL as necessary
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
        }
    }





    // function functiondelete(id_exam) {
    //     alert(id_exam)
    // //     $.ajax({
    // //     url: '<?php echo base_url(); ?>deleteexam/'+id_exam,
    // //     method: 'POST',
    // //     dataType: 'json',
    // //     success(result) {

    // //      $.ajax({
    // //         url: '<?php echo base_url(); ?>listexam',
    // //         method: 'POST',
    // //         dataType: 'json',
    // //         success(result) {
    // //             // myGrid.resolve(result.data, {
    // //             //     totalCount: result.totalCount,
    // //             //     summary: result.summary,
    // //             //     groupCount: result.groupCount,
    // //             // });
    // //         },
    // //         error() {
    // //             // myGrid.reject('Data Loading Error');
    // //         },
    // //         timeout: 5000,
    // //       });

    // //     },
    // //     error() {

    // //     },
    // //     timeout: 5000,
    // // });
    // }
</script>
<style>
    .dx-overlay.dx-widget.dx-visibility-change-handler.dx-toast {
        /* color:#ff4500 */
    }

    .link {
        color: #ff4500 !important;
    }

    .link:hover {
        color: inherit !important;
    }

    .grid-container {
        width: 100%;
        /* Adjust the width as needed */
        max-width: 100%;
        /* Adjust the max-width as needed */
        overflow-x: auto;
        /* Enable horizontal scrolling when necessary */
    }

    .dx-responsive-scroll {
        width: 100%;
        /* Ensure the grid takes full width */
        min-width: 800px;
        /* Adjust the minimum width as needed */
    }


    .dx-popup-bottom.dx-toolbar {
        padding: 10px 0;
    }

    .dx-list-select-all-checkbox {
        height: 22px;
    }

    .takeexam {
        font-size: small;
        color: #fff !important;
        margin-top: 10px;
        display: block;
        text-align: center;
        padding: 4px 5px;
        line-height: 15px;
        font-weight: bold;
        border-radius: 4px;
        background: #00ADB1;
        cursor: pointer;
    }

    .takeexam:hover {
        color: #ff4500 !important;
    }

    .dx-datagrid-scrollable-simulated {
        background: #00ADB1;
        border-top-right-radius: 8px;
        border-top-left-radius: 8px;
    }

    .dx-datagrid-headers.dx-datagrid-nowrap {
        border-top: 0;
        border-left: 0;
        border-right: 0;
    }

    .dx-datagrid-text-content {
        color: #fff
    }

    .dx-header-filter.dx-header-filter-empty,
    .dx-sort.dx-sort-down,
    .dx-sort.dx-sort-up {
        color: #fff !important
    }
</style>