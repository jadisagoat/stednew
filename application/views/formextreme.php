<body class="dx-viewport">
    <div class="demo-container">
        <div class="left-side">
            <div class="logo">
                <img src="images/logo-devav.png" alt="DEVAV" />
                <img src="images/logo-tasks.png" alt="Tasks" />
            </div>
        </div>
        <div class="right-side">
            <div id="selectStatus"></div>
        </div>
        <div id="gridContainer"></div>
    </div>
</body>
<script>
    var people = [{
            "FirstName": "Joe",
            "LastName": "Blow"
        },
        {
            "FirstName": "Bob",
            "LastName": "Smith"
        },
        {
            "FirstName": "Mary",
            "LastName": "Martin"
        },
    ];

    $(function() {
        $("#gridContainer").dxDataGrid({
            dataSource: people,
            showBorders: true,

            editing: {
                mode: "form",
                allowAdding: true,
                allowUpdating: true,
                allowDeleting: true,
                form: {
                    colCount: 2,
                    showValidationSummary: true,
                    items: [{
                            dataField: "FirstName",
                            label: {
                                text: "First Name"
                            },
                            validationRules: [{
                                type: "required",
                                message: "First Name is required."
                            }],
                        },
                        {
                            dataField: "LastName",
                            label: {
                                text: "Last Name"
                            },
                            validationRules: [{
                                type: "required",
                                message: "Last Name is required."
                            }],
                        },
                        {
                            // This is my Save and Add button.  
                            editorType: "dxButton",
                            editorOptions: {
                                text: "Save and Add",
                                onClick: function() {
                                    var grid = $("#gridContainer").dxDataGrid("instance");

                                    grid.saveEditData();
                                    grid.addRow();
                                }
                            },
                        }
                    ],
                }
            },
            onEditorPrepared: function(e) {
                if (e.parentType == 'dataRow' && e.dataField == 'FirstName') {
                    setTimeout(function() {
                        e.editorElement.find("input").focus();
                    }, 100);
                }
            }
        });
    });
</script>
<style>
    .right-side {
        position: absolute;
        right: 1px;
        top: 6px;
    }

    .logo {
        line-height: 48px;
    }

    .logo img {
        vertical-align: middle;
        margin: 0 5px;
    }

    .dx-row.dx-data-row .employee {
        color: #bf4e6a;
        font-weight: bold;
    }

    #gridContainer {
        margin: 20px 0;
        height: 400px;
    }
</style>