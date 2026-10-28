<?php
/* ==========================================================================
   FEEDBACKS - admin / lecturer (modern design - backup: feedbackhistorylist____28_10_2026.php)
   Grid: DevExtreme #gridContainer (data: listfeebacks) + rating chart #chart (data: course/raitingCourse)
   Styles in css/pages/manage.css
   ========================================================================== */
$this->load->view('include/_page_hero', array(
    'ph_title'   => 'Feedbacks',
    'ph_eyebrow' => 'Instructor tools',
    'ph_text'    => 'What learners say about each course: their written feedback, the lecturer and the rating they gave, plus the average rating per course.',
    'ph_crumbs'  => array(
        array('label' => 'Home', 'url' => base_url()),
        array('label' => 'My profile', 'url' => base_url() . 'edit-profile'),
        array('label' => 'Feedbacks'),
    ),
    'ph_stats'   => array(
        array('icon' => 'far fa-comment-dots', 'value' => '<span data-mx-mg-total>&hellip;</span>', 'label' => 'Feedbacks'),
        array('icon' => 'fas fa-star', 'value' => '1 &ndash; 5', 'label' => 'Rating per course'),
        array('icon' => 'fas fa-file-export', 'value' => 'Excel &middot; PDF', 'label' => 'One-click export'),
    ),
));
?>
<div class="mx-inner mx-mg">
    <section id="course-details" class="mx-mg-section">
        <div class="container">

            <?php $this->load->view('include/_manage_nav', array('mg_active' => 'feedbackhistory')); ?>

            <div class="mx-mg-card" data-reveal>
                <div class="mx-mg-head">
                    <span class="mx-mg-ico" aria-hidden="true"><i class="far fa-comment-dots"></i></span>
                    <div class="mx-mg-ttl">
                        <h2>Learner feedback</h2>
                        <p>Grouped by course. Open a course to read every comment and rating.</p>
                    </div>
                    <span class="mx-mg-count"><b data-mx-mg-total>&hellip;</b> feedbacks</span>
                </div>
                <div class="mx-mg-grid grid-container">
                    <div id="gridContainer" class="dx-responsive-scroll"></div>
                </div>
                <div class="mx-mg-tips" aria-hidden="true">
                    <span><i class="fas fa-chevron-right"></i> Open a course group to read its feedback</span>
                    <span><i class="fas fa-arrow-down-wide-short"></i> Click a column title to sort</span>
                    <span><i class="fas fa-filter"></i> Use the funnel to filter</span>
                    <span><i class="fas fa-file-export"></i> Export to Excel or PDF</span>
                </div>
            </div>

            <div class="mx-mg-card mx-mg-chart" data-reveal>
                <div class="mx-mg-head">
                    <span class="mx-mg-ico is-warm" aria-hidden="true"><i class="fas fa-chart-column"></i></span>
                    <div class="mx-mg-ttl">
                        <h2>Course ratings</h2>
                        <p>Average learner rating for each course.</p>
                    </div>
                </div>
                <div class="dx-viewport">
                    <div class="demo-container">
                        <div id="chart"></div>
                    </div>
                </div>
            </div>

        </div>
    </section>
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