<?php
/* ==========================================================================
   EXAMS LIST (modern design - backup: examlist____28_10_2026.php)
   Routes: examcourse[/id_course]  ·  grid data: POST listexam (DevExtreme dxDataGrid)
   $oncourse        = course row (empty when all courses are listed)
   $userenrollement = 2 when the user is an instructor of at least one listed course
   ========================================================================== */
$mx_course   = (is_array($oncourse) && count($oncourse)) ? $oncourse : array();
$mx_teacher  = ($userenrollement == 2);
$mx_role     = isset($login['id_init_role']) ? $login['id_init_role'] : '';
$mx_crumbs   = array(
	array('label' => 'Home', 'url' => base_url()),
	array('label' => 'Dashboard', 'url' => base_url() . 'edit-profile'),
);
if ($mx_course) {
	$mx_crumbs[] = array('label' => 'Exams', 'url' => base_url() . 'examcourse');
	$mx_crumbs[] = array('label' => $mx_course['title']);
} else {
	$mx_crumbs[] = array('label' => 'Exams');
}

$this->load->view('include/_page_hero', array(
	'ph_title'   => $mx_course ? $mx_course['title'] : 'Exams',
	'ph_eyebrow' => $mx_course ? 'Course exams' : 'Exam center',
	'ph_text'    => $mx_teacher
		? 'Create, schedule and manage the exams of your courses. Search, group and export the list to Excel or PDF.'
		: 'All the exams of your courses in one place. Follow your progress and take an exam as soon as the course is complete.',
	'ph_crumbs'  => $mx_crumbs,
));
?>

<div class="mx-inner mx-exam mx-exam-list">
	<section class="mx-exam-main">
		<div class="container">
			<input type="hidden" name='id_course' id='id_course' value="<?php echo isset($oncourse['id_course']) ? $oncourse['id_course'] : ''; ?>">

			<div class="mx-exam-board" data-reveal>
				<div class="mx-exam-board-head">
					<span class="mx-exam-board-ico"><i class="fas fa-file-signature" aria-hidden="true"></i></span>
					<div class="mx-exam-board-ttl">
						<h2><?php echo $mx_teacher ? 'Manage exams' : 'My exams'; ?></h2>
						<p><?php echo $mx_course ? 'Exams of ' . htmlspecialchars($mx_course['title'], ENT_QUOTES, 'UTF-8') : 'Exams of all your courses'; ?></p>
					</div>
					<div class="mx-exam-board-actions">
						<?php if ($mx_role == 3) { ?>
							<a class="mx-exam-btn mx-exam-btn-ghost" href="<?php echo base_url(); ?>courseexamhistory"><i class="fas fa-clock-rotate-left" aria-hidden="true"></i>Exam history</a>
						<?php } ?>
						<?php if ($mx_role == 1) { ?>
							<a class="mx-exam-btn mx-exam-btn-ghost" href="<?php echo base_url(); ?>totalexamshistory"><i class="fas fa-chart-column" aria-hidden="true"></i>All results</a>
						<?php } ?>
						<?php if ($mx_teacher) { ?>
							<a class="mx-btn mx-btn-primary mx-exam-new" href="<?php echo base_url(); ?>editexam"><i class="fas fa-plus" aria-hidden="true"></i>New exam</a>
						<?php } ?>
					</div>
				</div>

				<ul class="mx-exam-tips" aria-label="Tips">
					<li><i class="fas fa-magnifying-glass" aria-hidden="true"></i>Search any column</li>
					<li><i class="fas fa-layer-group" aria-hidden="true"></i>Drag a column header to group</li>
					<li><i class="fas fa-file-export" aria-hidden="true"></i>Export to Excel or PDF</li>
					<li class="mx-exam-tip-swipe"><i class="fas fa-hand-pointer" aria-hidden="true"></i>Swipe the table sideways to see every column</li>
				</ul>

				<div class="mx-exam-grid">
					<div id="gridContainer" class="mx-exam-dx"></div>
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
                    url: '<?php echo base_url(); ?>listexam',
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
                    dataField: 'id_exam',
                    caption: 'Id',
                    dataType: 'number',
                    alignment: 'left',
                    width: '50px'
                }, {
                    dataField: 'title',
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
                    dataField: 'number_of_questions',
                    caption: 'Questions Nbr',
                    dataType: 'string',
                    alignment: 'left',
                },
                {
                    dataField: 'start_date',
                    caption: 'Start Date',
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
                    dataField: 'close_date',
                    caption: 'Close Date',
                    dataType: 'string',
                    alignment: 'left',
                },
                {
                    dataField: 'time',
                    caption: 'Time',
                    dataType: 'string',
                    alignment: 'left',
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
                        // hide_students

                        // console.log("ayman", rowData);
                        var row = "";
                        if (rowData.id_role == 2) {
                            row += "<div id=" + rowData.id_exam + " class='mx-exam-acts'>";
                            row += "<a href='<?php echo base_url(); ?>editexam/" + rowData.id_exam + "' class='link mx-exam-act' title='Edit'><i class='fa fa-edit' aria-hidden='true'></i></a>";

                            row += "<a title='Delete' class='dx-link dx-link-delete dx-icon-trash dx-link-icon link mx-exam-act mx-exam-act-del' onclick='functiondelete(" + rowData.id_exam + "," + rowData.id_role + ")' >";
                            row += "</a>";
                            row += "</div>";
                        } else
                        if (rowData.id_user_exam && rowData.score)
                            row += "<a class='takeexam is-result'  href='<?php echo base_url(); ?>coursedetails/" + rowData.id_course + "#examModal'   >Result (" + rowData.score + ")</a>";
                        else
                        if (rowData.completion_percentage == '100.00')

                            row += "<a class='takeexam is-take'  href='<?php echo base_url(); ?>coursedetails/" + rowData.id_course + "#examModal'   >Take exam</a>";
                        else
                            row += "<a class='takeexam is-progress' title='Complete the course to unlock the exam' href='<?php echo base_url(); ?>coursedetails/" + rowData.id_course + "#courseModal'   ><span class='mx-exam-prog'><i style='width:" + parseInt(rowData.completion_percentage) + "%'></i></span>" + parseInt(rowData.completion_percentage) + "%</a>";


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
