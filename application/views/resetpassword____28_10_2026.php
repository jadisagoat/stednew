<!-- INNER PAGE WRAPPER
============================================= -->
<div class="inner-page-wrapper">
    <!-- BREADCRUMB
    ============================================= -->
    <div id="breadcrumb" class="division">
        <div class="container">
            <div class="row">

                <!-- BREADCRUMB NAV -->
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Reset Password</li>
                        </ol>
                    </nav>
                </div>

            </div> <!-- End row -->
        </div> <!-- End container -->
    </div> <!-- END BREADCRUMB -->

    <!-- CATEGORIES-2
    ============================================= -->
    <section id="about-2" class="about-section division">
        <div class="container pt-60 pb-60">
            <?php
            $resetpassword = $this->session->userdata('resetpassword');
            $info["idUser"] = $resetpassword['idUser'];
            $info["fullname"] = $resetpassword['fullname'];
            $info["email"] = $resetpassword['email'];
            ?>
            <div class="row d-flex">
                <div class="col-md-5 col-lg-6 contact-top">
                    <h3>Hi <?php echo htmlspecialchars($info["fullname"]); ?>,<br>
                        <span style='color: #BEBDBD;font-size: .7em;font-weight: normal;'> Please enter new password:</span>
                    </h3>

                    <div>
                        <span>New password <span id="resetpassword-error" class="text-error"></span></span>
                        <div class="password-container">
                            <input type="password" class="input-xxlarge" id="resetpassword" autocomplete="new-password" />
                            <span class="toggle-password" onclick="togglePasswordVisibility('resetpassword')">&#128065;</span>


                        </div>
                    </div>
                    <div>
                        <span>Confirm password</span>
                        <div class="password-container">
                            <input type="password" class="input-xxlarge" id="confirmresetpassword" value="" />
                            <span class="toggle-password" onclick="togglePasswordVisibility('confirmresetpassword')">&#128065;</span>

                            <span id="confirmresetpassword-error" class="text-error"></span>
                        </div>

                        <span id="confirmresetpassword-error" class="text-error"></span>
                    </div>
                    <div class="loader_button">
                        <input type="button" value="Reset" id="updatepassword" onClick="updatepassword()" class="contactbutt desgin-button" disabled />
                        <div class="loader_loader"></div>
                    </div>

                    <br>
                    <div class="allinfocontact" id="allinfocontact"></div>
                    <script>
                        function togglePasswordVisibility(fieldId) {
                            var passwordField = document.getElementById(fieldId);
                            if (passwordField.type === 'password') {
                                passwordField.type = 'text';
                            } else {
                                passwordField.type = 'password';
                            }
                        }



                        // Function to validate confirm password field
                        function validateConfirmPassword1() {
                            var password = $('#resetpassword').val();
                            if (password == '') {
                                $("#updatepassword").prop("disabled", true); // Optionally disable the button

                                // If passwords don't match, show an error
                                $('#resetpassword').css('border', '1px solid red !important');
                                $('#resetpassword-error').text('Passwords Required!');
                                return false;
                            }
                        }

                        function validateConfirmPassword() {
                            var password = $('#resetpassword').val();
                            var confirmPassword = $('#confirmresetpassword').val();
                            $("#updatepassword").prop("disabled", true); // Optionally disable the button

                            if (password == '') {
                                // If passwords don't match, show an error
                                $('#resetpassword').css('border', '1px solid red !important');
                                $('#resetpassword-error').text('*');
                                return false;
                            } else
                            if (confirmPassword !== password) {
                                // If passwords don't match, show an error
                                $('#confirmresetpassword').css('border', '1px solid red !important');
                                $('#confirmresetpassword-error').text('Passwords do not match.');
                                return false;
                            } else {

                                $("#updatepassword").prop("disabled", false); // Optionally disable the button

                                // If passwords match, clear the error
                                $('#resetpassword').css('border', '1px solid green');
                                $('#resetpassword-error').text('');


                                $('#confirmresetpassword').css('border', '1px solid green');
                                $('#confirmresetpassword-error').text('');
                                return true;
                            }
                        }

                        $(document).ready(function() {

                            // Event handler for confirm password field change
                            $('#confirmresetpassword').on('change', function() {
                                validateConfirmPassword();
                                console.log('validateConfirmPassword');
                            });
                            // Event handler for confirm password field change
                            $('#resetpassword').on('change', function() {
                                validateConfirmPassword1();
                                console.log('resetpassword');
                            })
                        });

                        function updatepassword() {

                            $(".loader_loader").show(); // Show the loader
                            $("#updatepassword").prop("disabled", true); // Optionally disable the button



                            var password = $('#resetpassword').val();
                            var confirmPassword = $('#confirmresetpassword').val();
                            var boolean = 0;

                            // Clear previous errors
                            $("#resetpassword-error").text('');
                            $("#confirmresetpassword-error").text('');
                            $("#resetpassword").css({
                                'border': '1px solid #bebdbd',
                                'color': '#132c57'
                            });

                            // Validate password
                            if (password == '') {
                                $("#resetpassword").css({
                                    'border': '1px solid #ff0000',
                                    'color': '#ff0000'
                                });
                                $("#resetpassword-error").text('Password is required.');
                                boolean = 1;
                            } else
                            if (confirmPassword == '') {
                                $("#confirmresetpassword-error").text('Please confirm your password.');
                                boolean = 1;
                            } else if (password !== confirmPassword) {
                                $("#confirmresetpassword-error").text('Passwords do not match.');
                                boolean = 1;
                            }

                            if (boolean == 0) {
                                $.ajax({
                                    type: 'POST',
                                    url: "<?php echo base_url() ?>home/updatepassword",
                                    data: {
                                        'resetpassword': password,
                                    },
                                    // dataType: "json",
                                    success: function(response) {
                                        if (response === '1') {
                                            $('.allinfocontact').html('Password updated successfully.');

                                            setTimeout(function() {
                                                /*Redirect to login page after 1 sec*/
                                                <?php
                                                // if ($this->session->userdata('redirect_url')) {
                                                //     $linkpage = $this->session->userdata('redirect_url')['redirect_url'];
                                                // } else
                                                $linkpage = base_url();
                                                ?>
                                                window.location.href = '<?php echo $linkpage; ?>';
                                            }, 1000)

                                        } else {
                                            $('.allinfocontact').html('Password update failed.');
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        console.error("AJAX Error: ", status, error);
                                        $('.allinfocontact').html('An error occurred.');
                                    }
                                });
                            } else {

                                $('.allinfocontact').html('Password update failed.');
                                return "error";
                            }
                        }

                        document.getElementById('confirmresetpassword').addEventListener('input', validateConfirmPassword);
                    </script>

                </div>
            </div>
        </div>
    </section>

</div> <!-- END INNER PAGE WRAPPER -->
<style>
    .text-error {
        color: red !important
    }

    .contact-top div input[type="text"],
    .contact-top div input[type="password"],
    .contact-top div textarea {
        width: 100%;
        padding: 0.7em;
        margin: 0.4em 0 0;
        background: none;
        outline: none;
        border: 1px solid #BEBDBD !important;
        -webkit-appearance: none;
    }

    .password-container {
        position: relative;
    }

    .password-container input {
        padding-right: 30px;
        /* Add space for the icon */
    }

    .password-container .toggle-password {
        position: absolute;
        right: 10px;
        top: 34px;
        transform: translateY(-50%);
        cursor: pointer;
    }

    .desgin-button {
        background: #00ADB1;
        border-radius: 5px;
        color: #fff;
        font-size: 1.1em;
        border: none;
        margin-left: 20px;
        width: 200px !important;
        min-width: 100px;
        outline: none;
        font-weight: 600;
        padding: 0.6em 1em;
        transition: 0.5s all;
        -webkit-transition: 0.5s all;
    }

    .loader_button {
        position: relative;
        width: 200px !important;
    }

    .loader_loader {
        padding: 0 !important;
        display: none;
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #ff4500;
        width: 20px;
        height: 20px;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
        position: absolute;
        top: 13%;
        left: calc(50% - 16px) !important;
        transform: translate(-50%, -50%);
    }

    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>