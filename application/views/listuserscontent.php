<?php
/* ==========================================================================
   ENROLLMENTS (users per course) (modern design - backup: listuserscontent____28_10_2026.php)
   Grid: DevExtreme #gridContainer (data: userscontent) - styles in css/pages/manage.css
   ========================================================================== */
$this->load->view('include/_page_hero', array(
    'ph_title'   => 'Enrollments',
    'ph_eyebrow' => 'Learner progress',
    'ph_text'    => 'Who is enrolled in which course, how far they have got, and the rating and feedback they left.',
    'ph_crumbs'  => array(
        array('label' => 'Home', 'url' => base_url()),
        array('label' => 'My profile', 'url' => base_url() . 'edit-profile'),
        array('label' => 'Enrollments'),
    ),
    'ph_stats'   => array(
        array('icon' => 'fas fa-user-graduate', 'value' => '<span data-mx-mg-total>&hellip;</span>', 'label' => 'Enrollments'),
        array('icon' => 'fas fa-chart-line', 'value' => 'Progress', 'label' => 'Completion in %'),
        array('icon' => 'fas fa-star', 'value' => 'Ratings', 'label' => 'Learner feedback'),
    ),
));
?>
<div class="mx-inner mx-mg">
    <section id="course-details" class="mx-mg-section">
        <div class="container">

            <?php $this->load->view('include/_manage_nav', array('mg_active' => 'listuserscontent')); ?>

            <div class="mx-mg-card" data-reveal>
                <div class="mx-mg-head">
                    <span class="mx-mg-ico" aria-hidden="true"><i class="fas fa-user-graduate"></i></span>
                    <div class="mx-mg-ttl">
                        <h2>Course enrollments</h2>
                        <p>Drag the &ldquo;Course&rdquo; column to the bar above to see every course with its learners.</p>
                    </div>
                    <span class="mx-mg-count"><b data-mx-mg-total>&hellip;</b> records</span>
                </div>
                <div class="mx-mg-grid grid-container">
                    <div id="gridContainer" class="dx-responsive-scroll"></div>
                </div>
                <div class="mx-mg-tips" aria-hidden="true">
                    <span><i class="fas fa-arrow-down-wide-short"></i> Click a column title to sort</span>
                    <span><i class="fas fa-filter"></i> Use the funnel to filter</span>
                    <span><i class="fas fa-layer-group"></i> Drag a column to the bar above to group</span>
                    <span><i class="fas fa-file-export"></i> Export to Excel or PDF</span>
                </div>
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
                // args.id_course = '< ?php echo $id_course;?>';
                args.idarticle = $('#id_course').val();

                $.ajax({
                    url: '<?php echo base_url(); ?>userscontent',
                    method: 'POST',
                    // dataType: 'json',
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
            columns: [
                // {
                //     dataField: 'id_user',
                //     caption: 'Id',
                //     dataType: 'number',
                //     alignment: 'left',
                //     width: '50px'
                // }, 
                {
                    dataField: 'coursetitle',
                    caption: 'Course',
                    dataType: 'string',
                    alignment: 'left',
                },
                {
                    dataField: 'name',
                    caption: 'name',
                    dataType: 'string',
                    alignment: 'left',
                },
                {
                    dataField: 'email',
                    caption: 'email',
                    dataType: 'string',
                    alignment: 'left',
                },



                {
                    dataField: 'feedback',
                    caption: 'feedback',
                    dataType: 'string',
                    alignment: 'left',
                },

                {
                    dataField: 'rating',
                    caption: 'course rating',
                    dataType: 'string',
                    alignment: 'left',
                },

                {
                    dataField: 'avg_course_rating',
                    caption: 'course avg',
                    dataType: 'string',
                    alignment: 'left',
                },
                {
                    dataField: 'completion_percentage',
                    caption: 'completion(%)',
                    dataType: 'string',
                    alignment: 'left',
                },
                // {
                //     dataField: 'Actions',
                //     width: 100,
                //     caption: '',
                //     dataType: 'string',
                //     alignment: 'left',
                //     encodeHtml: false,
                //     width: '100',
                //     sortOrder: null,
                //     allowSorting: false, // Disable sorting for this column
                //     allowFiltering: false, // Disable filtering for this column,
                //     calculateDisplayValue: function(rowData) {
                //         // hide_students

                //         console.log("ayman", rowData);
                //         var row = "";
                //         if (rowData.id_role == 2) {
                //             row += "<div id=" + rowData.id_exam + " style='cursor: pointer;text-align:center'>";
                //             row += "<a href='<?php echo base_url(); ?>editexam/" + rowData.id_exam + "' class='link'><i class='fa fa-edit' style='font-size: small;' title='Edit'></i></i></a>";

                //             row += "  |  <a style='font-size: small;' title='delete'class='dx-link dx-link-delete dx-icon-trash dx-link-icon link' onclick='functiondelete(" + rowData.id_exam + "," + rowData.id_role + ")' >";
                //             row += " </a>";
                //             row += " </div>";
                //         } else
                //         if (rowData.id_user_exam && rowData.score)
                //             row += "<a class='takeexam'  href='<?php echo base_url(); ?>coursedetails/" + rowData.id_course + "#examModal'   >Results Exam(" + rowData.score + ")</a>";
                //         else
                //         if (rowData.completion_percentage == '100.00')

                //             row += "<a class='takeexam'  href='<?php echo base_url(); ?>coursedetails/" + rowData.id_course + "#examModal'   >Take Exam</a>";
                //         else
                //             row += "<a class='takeexam'  href='<?php echo base_url(); ?>coursedetails/" + rowData.id_course + "#courseModal'   >" + parseInt(rowData.completion_percentage) + "%</a>";


                //         return row;

                //     }
                // }
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
                        const header = "Exam";
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

                        doc.save("listuserscontent.pdf");
                    });
                } /// end pdf 
                else {
                    const workbook = new ExcelJS.Workbook();
                    const worksheet = workbook.addWorksheet('listuserscontent');
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
                        workbook.xlsx.writeBuffer().then((buffer) => {
                            saveAs(new Blob([buffer], {
                                type: 'application/octet-stream'
                            }), 'listuserscontent.xlsx');
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

        mxConfirm("This item will be permanently deleted. This action cannot be undone.", { danger: true, title: "Delete this item?", confirmText: "Yes, delete" }).then(function (ok) {
            if (!ok) return;
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
        });
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