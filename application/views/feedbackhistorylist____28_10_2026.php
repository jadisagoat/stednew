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



                            <!-- <?php
                                    if (count($oncourse)) { ?>
                             <li class="breadcrumb-item active"><?php echo $oncourse['title']; ?>
                            <?php } else { ?>
                              <li class="breadcrumb-item active">Feedbacks</li>  
                           <?php }
                                    if ($userenrollement == 2) { ?>
                            <li class="breadcrumb-item">Feedback History</li>
                         <?php }
                            ?> 
                             <input type="hidden" name='id_course' id='id_course' value="<?php echo $oncourse['id_course']; ?>"></li> -->

                            <li class="breadcrumb-item active">Feedbacks</li>
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
    <br><br><br>
    <div class="container">
        <div class="dx-viewport">
            <div class="demo-container">
                <div id="chart"></div>
            </div>
        </div>
    </div>
    <br><br><br><br>

    <style>
        #chart {
            height: 440px;
        }

        .dx-header-filter-indicator {
            color: #fff !important;
            font-weight: bold;
        }
    </style>

</div>

<script>
    // Make an AJAX request to fetch the population data from the server
    $.ajax({
        url: '<?php echo base_url(); ?>course/raitingCourse', // Specify the URL of your server endpoint
        method: 'GET', // Use the GET method
        success: function(data) { // Handle the success response
            // Assuming the response data is in the format of populationData
            data.forEach(function(item) {
                item.val = parseFloat(item.val); // Convert 'val' to a floating-point number
            });
            console.log(data);
            const populationData = data;

            // Initialize the DevExpress chart with the retrieved data
            $('#chart').dxChart({
                dataSource: populationData,
                legend: {
                    visible: false,
                },
                series: {
                    type: 'bar',
                },
                valueAxis: {
                    tick: {
                        visible: true,
                    },
                    label: {
                        visible: true,
                    },
                    tickInterval: '0.5',
                },
                title: 'Raiting course',
            });
        },
        error: function(xhr, status, error) { // Handle any errors
            console.error('Error fetching population data:', error);
        }
    });


    //     const populationData = [{
    //   arg: "Testing, Adjusting, and Balancing (TAB) for both air and hydronic systems",
    //   val: 3032019978,
    // }, {
    //   arg: 1970,
    //   val: 3683676306,
    // }, {
    //   arg: 1980,
    //   val: 4434021975,
    // }, {
    //   arg: 1990,
    //   val: 5281340078,
    // }, {
    //   arg: 2000,
    //   val: 6115108363,
    // }, {
    //   arg: 2010,
    //   val: 6922947261,
    // }, {
    //   arg: 2020,
    //   val: 7795000000,
    // }];

    // const populationData = [{"arg":"Quality Management System in Project Environments","val":1},
    //  {"arg":"Electrical Power Distribution System","val":2},
    //  {"arg":"Testing, Adjusting, and Balancing (TAB) for both air and hydronic systems","val":"5"}];
    // $(() => {
    //   $('#chart').dxChart({
    //     dataSource: populationData,
    //     legend: {
    //       visible: false,
    //     },
    //     series: {
    //       type: 'bar',
    //     },
    //     argumentAxis: {
    //       tickInterval: 10,
    //       label: {
    //         format: {
    //           type: 'decimal',
    //         },
    //       },
    //     },
    //     title: 'World Population by Decade',
    //   });
    // });
    ////////////////////////////

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
                // args.active = $('#active').val();
                // args.searchfrom = $('#searchfrom').val();
                // args.searchto = $('#searchto').val();

                $.ajax({
                    url: '<?php echo base_url(); ?>listfeebacks',
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
            columns: [{
                    dataField: 'title',
                    caption: 'course',
                    dataType: 'title',
                    alignment: 'left',
                    width: '170px',
                    groupIndex: 0, // grouping data
                },
                {
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
                    caption: 'name',
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
                    dataField: 'occupation',
                    caption: 'Profession',
                    dataType: 'string',
                    alignment: 'left',
                },
                // {
                //     dataField: 'nationality',
                //     caption: 'Nationality',
                //     dataType: 'string',
                //     alignment: 'left',
                // },
                {
                    dataField: 'feedback',
                    caption: 'Feedback',
                    dataType: 'string',
                    alignment: 'left',
                },
                {
                    dataField: 'lecturername',
                    caption: 'Lecturer',
                    dataType: 'string',
                    alignment: 'left',
                },
                {
                    dataField: 'rating',
                    caption: 'Rating',
                    dataType: 'string',
                    alignment: 'left',
                },
            ],
            summary: {
                groupItems: [{
                        column: 'id',
                        summaryType: 'count',
                        displayFormat: '{0} Feedback',
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
                        const header = "feedback";
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

                        doc.save("feedbackhistory.pdf");
                    });
                } /// end pdf 
                else {
                    const workbook = new ExcelJS.Workbook();
                    const worksheet = workbook.addWorksheet('feedbackhistory');
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
                            }), 'feedbackhistory.xlsx');
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

    // function functiondelete(id_usercourserating) {

    //     if(confirm("Are you sure you want to delete this item?")){ 
    //     $.ajax({
    //         url: '<?php echo base_url(); ?>deletefeedback/' + id_exam,  
    //         method: 'POST',
    //         success: function(response) {

    //             if (response) {
    //                 DevExpress.ui.notify('Feedback deleted successfully.', 'success', 3000);
    //                 $('#gridContainer').dxDataGrid('instance').refresh();
    //             } else {
    //                 DevExpress.ui.notify('Failed to delete feedback.', 'error', 3000);
    //             }
    //         },
    //         error: function() {

    //             DevExpress.ui.notify('Error contacting server.', 'error', 3000);
    //         }
    //     });
    //   }
    // }





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

    /* .dx-datagrid-text-content {
    color:#fff
}
.dx-header-filter.dx-header-filter-empty,.dx-sort.dx-sort-down,.dx-sort.dx-sort-up {
    color:#fff !important
} */
    .image-feedback {
        width: 45px;
        margin: 0 auto;
        height: 45px;
        border: 1px solid #eee;
        border-radius: 50%;
    }
</style>