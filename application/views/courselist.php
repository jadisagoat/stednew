<?php
/* ==========================================================================
   COURSES LIST - admin (modern design - backup: courselist____28_10_2026.php)
   Grid: DevExtreme #gridContainer (data: course/all_courses, detail rows: course/content) - styles in css/pages/manage.css
   ========================================================================== */
$this->load->view('include/_page_hero', array(
    'ph_title'   => 'Courses',
    'ph_eyebrow' => 'Admin tools',
    'ph_text'    => 'The full course catalogue with category, lecturer and average rating. Open a row to see the course content.',
    'ph_crumbs'  => array(
        array('label' => 'Home', 'url' => base_url()),
        array('label' => 'My profile', 'url' => base_url() . 'edit-profile'),
        array('label' => 'Courses'),
    ),
    'ph_stats'   => array(
        array('icon' => 'fas fa-book-open', 'value' => '<span data-mx-mg-total>&hellip;</span>', 'label' => 'Courses'),
        array('icon' => 'fas fa-list-ul', 'value' => 'Content', 'label' => 'Expand a row to see it'),
        array('icon' => 'fas fa-file-export', 'value' => 'Excel &middot; PDF', 'label' => 'One-click export'),
    ),
));
?>
<div class="mx-inner mx-mg">
    <section id="course-details" class="mx-mg-section">
        <div class="container">

            <?php $this->load->view('include/_manage_nav', array('mg_active' => 'listcourses')); ?>

            <div class="mx-mg-card" data-reveal>
                <div class="mx-mg-head">
                    <span class="mx-mg-ico" aria-hidden="true"><i class="fas fa-book-open"></i></span>
                    <div class="mx-mg-ttl">
                        <h2>Course catalogue</h2>
                        <p>Use the arrow on the left of a row to list its content, the pencil to open the course.</p>
                    </div>
                    <span class="mx-mg-count"><b data-mx-mg-total>&hellip;</b> courses</span>
                </div>
                <div class="mx-mg-grid grid-container">
                    <div id="gridContainer" class="dx-responsive-scroll"></div>
                </div>
                <div class="mx-mg-tips" aria-hidden="true">
                    <span><i class="fas fa-chevron-right"></i> Expand a row to see the course content</span>
                    <span><i class="fas fa-arrow-down-wide-short"></i> Click a column title to sort</span>
                    <span><i class="fas fa-filter"></i> Use the funnel to filter</span>
                    <span><i class="fas fa-layer-group"></i> Drag a column to the bar above to group</span>
                </div>
            </div>

        </div>
    </section>
</div>
<script>
    // DevExpress.config({
    //     rtlEnabled: true
    // });
    DevExpress.localization.locale('ar');
    window.jsPDF = window.jspdf.jsPDF;


    const employees = new DevExpress.data.CustomStore({
        key: 'id_course',
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
                url: '<?php echo base_url(); ?>course/all_courses',
                method: 'POST',
                dataType: 'json',
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
                    deferred.reject('Data Loading Error');
                },
                timeout: 5000,
            });

            return deferred.promise();
        },
    });

    let course = '';
    $.ajax({
        url: '<?php echo base_url(); ?>course/content',
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

    $(function() {
        $('#gridContainer').dxDataGrid({
            dataSource: employees,
            keyExpr: 'id_course',
            columns: [
                // {
                //     dataField: 'id_course',
                //     caption: 'Id',
                //     dataType: 'number',
                //     alignment: 'left',
                //     width: '170px',
                //     // groupIndex: 0, // grouping data
                // },
                {
                    dataField: 'image',
                    caption: 'Image',
                    dataType: 'string',
                    alignment: 'center',
                    cellTemplate: function(container, options) {
                        // alert(options.value)
                        if (options.value) {
                            $('<img class="image-feedback">').attr('src', options.value).appendTo(container);
                        } else {
                            $('<img class="image-feedback">').attr('src', '<?php echo base_url(); ?>images/nopic.jpg').appendTo(container);
                        }
                    },
                    width: '70px',
                },
                {
                    dataField: 'coursetitle',
                    caption: 'Name',
                    dataType: 'string',
                    alignment: 'left',
                    // width: '350px',
                },
                {
                    dataField: 'cattitle',
                    caption: 'Category',
                    dataType: 'string',
                    alignment: 'left',
                    width: '100px',
                },
                // {
                //     dataField: 'subcattitle',
                //     caption: 'Sub-Category',
                //     dataType: 'string',
                //     alignment: 'left',
                //     // width: '200px',
                // },
                {
                    dataField: 'lecturername',
                    caption: 'Lecturer',
                    dataType: 'string',
                    alignment: 'left',
                    width: '150px',
                },
                {
                    dataField: 'average_rating',
                    caption: 'Rating',
                    dataType: 'string',
                    alignment: 'left',
                    width: '70px',
                    // encodeHtml: false,
                    // calculateDisplayValue: function(rowData) {

                    //     var row =
                    //         "<a href='articledetails.php?id=" + rowData.id_course +
                    //         "' ><span>" + rowData.articlename + "</span></a>";
                    //     return row;

                    // }
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
                    allowSorting: false,
                    allowFiltering: false,
                    calculateDisplayValue: function(rowData) {

                        var row = "";
                        row += "<div id=" + rowData.id_course + " style='cursor: pointer;text-align:center'>";
                        // row += "<a onclick='userAddEdit( "+rowData.id_course+")' data-toggle='modal' data-target='#myModalmyModal' class='link'><i class='fa fa-edit' style='font-size: small;' title='Edit'></i></i></a>";
                        row += "<a href='<?php echo base_url(); ?>coursedetails/" + rowData.id_course + "' class='link'><i class='fa fa-edit' style='font-size: small;' title='Edit'></i></i></a>";

                        // row += " | <a style='font-size: small;' title='delete'class='dx-link dx-link-delete dx-icon-trash dx-link-icon link' onclick='functiondeleteuser(" + rowData.id_course + ")' >";
                        // row += "</a>";
                        // row +="  |  <a style='font-size: small;' title='active' class='pr dx-link dx-icon-check dx-link-icon link' onclick='functionactive" +rowData.id_course + "," + rowData.active + ")' >";                               
                        // row +=" </a>" ;
                        // row +=" </div>";
                        // else
                        // if(rowData.completion_percentage=='100.00')
                        //     row += "<a class='takeexam'  href='<?php echo base_url(); ?>coursedetails/"+rowData.id_course+"#examModal'   >Take Exam</a>";
                        //     else
                        //     row += "<a class='takeexam'  href='<?php echo base_url(); ?>coursedetails/"+rowData.id_course+"#examModal'   >"+parseInt(rowData.completion_percentage)+"%</a>";


                        return row;

                    }
                }
            ],
            masterDetail: {
                enabled: true,
                template(container, options) {
                    const currentEmployeeData = options.data;

                    console.log(course.data)
                    $('<div>')
                        .addClass('master-detail-caption')
                        .text(`${currentEmployeeData.coursetitle}  Content:`)
                        .appendTo(container);

                    $('<div>')
                        .dxDataGrid({
                            columnAutoWidth: true,
                            showBorders: true,
                            columns: [{
                                    dataField: 'contenttitle',
                                    caption: 'Content',

                                }
                                // {
                                //     dataField: 'role',
                                //     dataType: 'string',
                                // },
                                // {
                                //     dataField: 'id_course',
                                //     dataType: 'string',
                                // },
                                // 'Priority',
                                // {
                                //     caption: 'Completed',
                                //     dataType: 'boolean',
                                //     calculateCellValue(rowData) {
                                //         return rowData.Status === 'Completed';
                                //     },
                                // }
                            ],
                            dataSource: new DevExpress.data.DataSource({
                                store: new DevExpress.data.ArrayStore({
                                    key: 'id_course',
                                    data: course,
                                }),
                                filter: ['id_course', '=', options.key],
                            }),
                        }).appendTo(container);
                },
            },
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
                width: 300,
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
                        gridInstance.columnOption("image", "visible", true);
                        gridInstance.columnOption("Actions", "visible", true);
                        // header
                        const header = "Course";
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

                        doc.save("listcourses.pdf");
                    });
                } /// end pdf 
                else {
                    const workbook = new ExcelJS.Workbook();
                    const worksheet = workbook.addWorksheet('listcourses');
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
                        gridInstance.columnOption("image", "visible", true);
                        gridInstance.columnOption("Actions", "visible", true);
                        e.component.columnOption('edit', 'visible', true);
                        workbook.xlsx.writeBuffer().then((buffer) => {
                            saveAs(new Blob([buffer], {
                                type: 'application/octet-stream'
                            }), 'listcourses.xlsx');
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

    function functiondeleteuser(id_course) {
        mxConfirm("This item will be permanently deleted. This action cannot be undone.", { danger: true, title: "Delete this item?", confirmText: "Yes, delete" }).then(function (ok) {
            if (!ok) return;
            // Example AJAX request to your server-side deletion logic
            $.ajax({
                url: '<?php echo base_url(); ?>users/deleteuser/' + id_course, // Adjust URL as necessary
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

    // function userAddEdit(id_course) {
    //         $.ajax({
    //             url: '<?php echo base_url(); ?>users/userAddEdit/' + id_course, // Adjust URL as necessary
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