<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('fpdf');
        $this->load->library('fpdi');
    }

    public function delete_user_exam()
    {
        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        if ($id_user) {
            $id_user = (int) $id_user;
            $id_exam = (int) $_REQUEST['id_exam'];
            $id_course = (int) $_REQUEST['id_course'];

            $qry = "DELETE FROM user_exam WHERE `id_exam` =" . $id_exam . " and id_user = " . $id_user;
            $query = $this->db->query($qry);

            $qry = "DELETE FROM user_content WHERE `id_course` =" . $id_course . " and id_user = " . $id_user;
            $query = $this->db->query($qry);

            // $rows = $this->fct->search($qry);
            // echo "<pre>";
            // print_r($qry);
            redirect(base_url() . "coursedetails/" . $id_course);
            exit();
        }
        redirect(base_url());
    }

    public function save_rating()
    {
        $id_course = $_REQUEST['id_course'];
        $rating = $_REQUEST['ratingClick'];
        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        if ($id_user && $id_course) {
            $cond = array('id_course' => $id_course, 'id_user' => $id_user);
            $user_course_rating = $this->fct->getAll_cond('user_course_rating', "sort_order", $cond);
            $msg = '';
            if (count($user_course_rating)) {
                $_data["updated_date"] = date("Y-m-d h:i:s");
                $this->db->where("id_course", $id_course);
                $this->db->where("id_user", $id_user);
                $_data["rating"] = $rating;
                $this->db->update('user_course_rating', $_data);
                // echo  $this->db->last_query();
                // echo "<pre>";
                // exit();
                $msg = 'Update rating';
            } else {
                $_data["created_date"] = date("Y-m-d h:i:s");
                $_data["id_course"] = $id_course;
                $_data["id_user"] = $id_user;
                $_data["rating"] = $rating;
                $Id = $this->db->insert('user_course_rating', $_data);
                $msg = 'Insert new rating';
            }
        }

        // Prepare JSON response
        $response = array(
            'data' => $msg,
        );
        // Set the appropriate content type for JSON responses
        header('Content-Type: application/json');
        // Allow requests from a specific origin (replace '*' with the origin you want to allow)
        header('Access-Control-Allow-Origin: *');
        // Output JSON response
        echo json_encode($response);
    }

    public function user_content_data($iid = '')
    {
        $id_content = $_REQUEST['id'];
        $id_course = $_REQUEST['id_course'];
        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];

        if ($id_user && $id_content) {
            $cond = array('id_content' => $id_content, 'id_user' => $id_user);
            // $videoplayere=$this->fct->getAll_cond('user_content',"sort_order",$cond); 

            $videoplayere = $this->fct->count_cond('user_content', $cond);
            // echo "<br><br><br>";
            // echo "<br>". $this->db->last_query();
            // echo "<pre>";
            // print_r( $videoplayere);
            if ($videoplayere) {
                $_data["updated_date"] = date("Y-m-d h:i:s");
                $this->db->where("id_content", $id_content);
                $this->db->where("id_user", $id_user);
                $_data["id_course"] = $id_course;
                $this->db->update('user_content', $_data);
            } else {
                $_data["created_date"] = date("Y-m-d h:i:s");
                $_data["id_course"] = $id_course;
                $_data["id_user"] = $id_user;
                $_data["id_content"] = $id_content;
                $this->db->insert('user_content', $_data);
            }
            //   echo "<br><br><br>";
            //   echo "<br>". $this->db->last_query();
            // exit(); 
        }
        if ($id_course && $id_user) {
            $cond = array('id_course' => $id_course, 'id_user' => $id_user);
            $videoplayeress = $this->fct->count_cond('user_content', $cond);
            // =$this->fct->getAll_cond('user_content',"sort_order",$cond); 
            // numrows
            // echo "<br><br><br>";
            // echo  $this->db->last_query();
            // echo "<pre>";
            // print_r( $videoplayeress);
            // exit(); 

            echo $videoplayeress;
        } else
            echo 0;
    }

    public function testgenerat()
    {
        // Security: developer test (fixed user / course) - website admins only
        $login = $this->session->userdata('login');
        if (empty($login['idUser']) || $login['id_init_role'] != 1) {
            show_404();
            return;
        }
        $id_course = 12;
        $id_user = 1;

        //     $query = "select user.name ,user.signature
        // from user 
        // INNER JOIN 
        //         user_course ON user_course.id_user = user.id_user AND user_course.deleted = 0                                  
        //     INNER JOIN 
        //        course ON user_course.id_course = course.id_course AND user_course.deleted = 0 AND user_course.id_role = 2 
        // WHERE user.deleted = 0 and user_course.id_course = " . $id_course . " 
        // order by user.id_user desc limit 1";

        //     $user_exam = $this->fct->search($query);
        // $user_exam_lecture = ucwords($user_exam['0']['name']);
        // $imagePath = FCPATH . 'uploads/signature.png';

        $id_grade = 1;
        ///////////////////////////////////////
        $url = FCPATH . 'uploads/certificate_signature.pdf';
        // Create a new FPDI instance
        $pdf = new Fpdi();

        // Set the source file
        $pdf->setSourceFile($url);

        // Import the first page
        $templateId = $pdf->importPage(1);

        // Get the size of the imported page
        $size = $pdf->getTemplateSize($templateId);

        // Add a page with the same size as the template
        $pdf->AddPage($size['orientation'], array($size['width'], $size['height']));

        // Use the imported page as template and fit it to the entire page
        $pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height']);


        // $pdf_content = file_get_contents($templateId);
        // $templateId = str_replace('{variable2}', 'sssssssss', $pdf_content);


        $pdf->AddFont('GreatVibes-Regular', '', 'GreatVibes-Regular.php');
        $pdf->SetFont('GreatVibes-Regular', '', 25);
        // $pdf->Cell(0, 10, 'This text is in the FREESCPT font', 0, 1, 'C');

        $cond = array('id_user' => $id_user);
        $user = $this->fct->getonerow("user", $cond);

        $cond = array('id_course' => $id_course);
        $course = $this->fct->getonerow("course", $cond);



        $pdf->SetTextColor(199, 177, 134); //  color
        $pdf->SetXY(120, 95);
        $pdf->Cell(40, 10, ucwords($user['name']));

        // Set font and add text at specific positions
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTextColor(0, 0, 0); //  color
        // Add the second text
        /////////////////////////////////
        // The text you want to center
        $text = strtoupper(ucwords($course['title']));
        // Get the width of the page
        $pageWidth = $pdf->GetPageWidth();
        // Calculate the width of the text
        $textWidth = $pdf->GetStringWidth($text);
        // Calculate the X position to center the text
        $x = ($pageWidth - $textWidth) / 2;
        // Set the Y position where you want the text to appear
        $y = 115;
        // Set the position and add the text
        $pdf->SetXY($x + 5, $y);
        $pdf->Cell($textWidth, 30, $text, 0, 0, 'C');
        /////////////////////////////////

        // $pdf->SetXY(60, 115);
        // $pdf->Cell(240, 30, strtoupper(ucwords($course['title'])));
        $date = date('d-m-Y');
        // Add the third text
        $pdf->SetXY(60, 147);
        $dateincell = date('F j, Y');
        $pdf->Cell(10, 20, $dateincell);

        $pdf->AddFont('GreatVibes-Regular', '', 'GreatVibes-Regular.php');
        $pdf->SetFont('GreatVibes-Regular', '', 25);
        // Add the fourth text
        // $pdf->SetXY(185, 160);

        // $user_exam_lecture = 'Sted Training Center';
        // $pdf->Cell(10, 20, $user_exam_lecture);

        // Add the image watermark







        // $pageWidth = $pdf->GetPageWidth();
        //$imagePath = FCPATH . 'uploads/watrmark.jpeg'; // Change the path to your image file
        // Set position and add the image watermark
        //  $pdf->Image($imagePath, 195, 150, 50); // Adjust the x, y, width, and height as necessary
        //////////////////

        $certificate = 'certificate.pdf';
        // Output the PDF inline
        $pdf->Output('I', 'certificate_signate.pdf');
        ///////////////////////////////////////








        // // Output the PDF to a file
        // $pdf->Output('F', 'certificate_signature.pdf');

        // // Convert the PDF to PNG using ImageMagick
        // exec('convert -density 300 certificate_signature.pdf -quality 100 certificate_signature.png');

        // // Output the PNG to the browser
        // header('Content-Type: image/png');
        // readfile('certificate_signature.png');
    }

    public function download_certificate($downloadLink)
    {
        // Security: login required + strict file name (the value is used for files and a shell command)
        $login = $this->session->userdata('login');
        if (empty($login['idUser']) || !preg_match('/^[A-Za-z0-9][A-Za-z0-9_\-]*$/', $downloadLink)) {
            show_404();
            return;
        }
        // // Path to the PDF file / /$downloadLink; //
        // $pdfFile = 'https://stedtrainingcenter.com/uploads/exam_history/certificate_17_14_20-07-2024.pdf';
        // // Path to the PNG file
        // $pngFile = 'https://stedtrainingcenter.com/uploads/exam_historyuploads/Text_Signature1.png';

        // // Convert the PDF to PNG using ImageMagick
        // exec("convert -density 300 $pdfFile -quality 100 $pngFile");

        // // Serve the PNG file for download
        // header('Content-Type: image/png');
        // header('Content-Disposition: attachment; filename="certificate.png"');
        // readfile($pngFile);

        // URL of the PDF file
        $pdfUrl = base_url() . 'uploads/exam_history/' . $downloadLink . ".pdf";
        // Local path to save the downloaded PDF
        $localPdfPath = $downloadLink . "_" . $downloadLink;
        // Local path to save the converted PNG
        $time = time();
        $localPngPath = $downloadLink . '.png';

        // Function to download the PDF file
        function downloadFile($url, $path)
        {
            $file = file_get_contents($url);
            if ($file === FALSE) {
                die('Error downloading PDF file.');
            }
            file_put_contents($path, $file);
        }

        // Download the PDF file
        downloadFile($pdfUrl, $localPdfPath);

        // Convert the PDF to PNG using ImageMagick
        exec("convert -density 300 " . escapeshellarg($localPdfPath) . " -quality 100 " . escapeshellarg($localPngPath));

        // Check if the PNG file was created
        if (!file_exists($localPngPath)) {
            die('Error converting PDF to PNG.');
        }

        // Serve the PNG file for download
        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="certificate.png"');
        readfile($localPngPath);

        // Clean up temporary files
        unlink($localPdfPath);
        unlink($localPngPath);
        exit();
    }

    public function save_exam()
    {
        $userId = $this->session->userdata('login');
        $id_user = (int) $userId['idUser'];
        // already exist 
        // Security: ids are concatenated into SQL below - force integers
        $id_exam = (int) $_REQUEST['id_exam'];
        $id_course = (int) $_REQUEST['id_course'];
        $cond = array('id_exam' => $id_exam, 'id_user' => $id_user);
        //$countuser_exam = $this->fct->getAll_limit('user_exam', "number_attempts", "DESC", "1", $cond);

        $countuser_exam = $this->fct->getAll_cond('user_exam', "sort_order", $cond);
        $cc = count($countuser_exam);

        if ($cc) {
            // echo "already exist";
            // exit();
            /////////////////////////////////
            $query = "select exam_history.*
            from exam_history  WHERE id_course = " . $id_course . " and id_user = " . $id_user . " 
            and id_exam = " . $id_exam . " order by number_attempts desc limit 1";
            $exam_historys = $this->fct->search($query);

            if (count($exam_historys) == 0) {
                $qry = "DELETE FROM user_exam WHERE `id_course` =" . $id_course . " and id_user = " . $id_user;
                $query = $this->db->query($qry);

                // /////////////////////
                // $totalsquestions = $cc;
                // $total_answer_true = 0;
                // foreach ($countuser_exam as $rowww) {
                //     $idanswer = $rowww['id_answer'];
                //     $cond = array('id_question' => $rowww['id_question'], 'true' => 1);
                //     $answer_true = $this->fct->getonerow("answer", $cond);
                //     $id_answer_true = $answer_true['id_answer'];
                //     if ($id_answer_true == $idanswer) {
                //         $total_answer_true++;
                //     }
                // }
                // $score = ($total_answer_true * 100) / $totalsquestions;
                // $id_grade = 2;
                // if ($score < 70) $id_grade = 2;
                // $exam_date = date("Y-m-d h:i:s");
                // $number_attempts_exist = 1;

                // $cond = array('id_course' => $id_course, 'id_exam' => $id_exam);
                // $number_of_attempts = $this->fct->getonerow('exam', $cond);
                // $max_number_of_attempts = $number_of_attempts['number_of_attempts'];

                // $_data["created_date"] = date("Y-m-d h:i:s");
                // $_data["id_course"] = $id_course;
                // $_data["id_user"] = $id_user;
                // $_data["exam_date"] = $exam_date;
                // $_data["id_exam"] = $id_exam;
                // $_data["id_grade"] = $id_grade;
                // $_data["score"] = $score;
                // // $_data["certificate"] = $certificate;
                // if ($number_attempts_exist == 0) $number_attempts_exist = 1;
                // $_data["number_attempts"] = $number_attempts_exist;
                // $this->db->insert('exam_history', $_data);
                // // Get the last insert ID
                // $last_insert_id = $this->db->insert_id();
            }
            /////////////////////////////////

        } //else {
        if ($id_user && isset($_REQUEST['answer']) && $_REQUEST['answer']) {
            // Loop through the array and insert data into the database
            $idquestions = $_REQUEST['answer'];
            // Initialize an empty array to hold the value sets for the INSERT query
            $valueSets = array();

            $total_answer_true = 0;
            $totalsquestions = count($idquestions);
            foreach ($idquestions as $idquestion => $idanswer) {
                $idquestion = (int) $idquestion;
                $idanswer = (int) $idanswer;
                $cond = array('id_question' => $idquestion, 'true' => 1);
                $answer_true = $this->fct->getonerow("answer", $cond);
                $id_answer_true = $answer_true['id_answer'];
                if ($id_answer_true == $idanswer) {
                    $total_answer_true++;
                }
                // Build a value set for the current record
                $valueSets[] = "('" . date("Y-m-d h:i:s") . "', '$id_exam', '$idquestion', '$idanswer','$id_user','$id_course')";

                // echo "------------<pre>";
                // echo $id_answer_true;
                // echo "<br>";
                // echo $idanswer;
                // echo "<br>---------------------";
            }
            // echo $total_answer_true;
            // echo "*100/<br>---------------------";
            // echo $totalsquestions;
            // echo "<br>---------------------";

            // print_r($idquestions);
            $qry = "DELETE FROM user_exam WHERE `id_course` =" . $id_course . " and id_user = " . $id_user;
            $query = $this->db->query($qry);

            // Combine all value sets into a single comma-separated string
            $valuesString = implode(", ", $valueSets);
            // Construct the INSERT query with multiple value sets
            $sql = "INSERT INTO user_exam (created_date, id_exam, id_question, id_answer,id_user,`id_course`) VALUES $valuesString";
            // Execute the INSERT query
            $Id = $this->db->query($sql);
            // echo  $this->db->last_query();
            // echo "<pre>";
            // print_r( $Id);
            // exit();
            if ($Id == 1) {
                // echo "New records created successfully<br>";
                //////////////// exam_history number_attempts
                $score = ($total_answer_true * 100) / $totalsquestions;
                $certificate = '';
                if ($score < 70)
                    $id_grade = 2;
                else {

                    $query = "select user.name ,user.signature
                            from user 
                            INNER JOIN 
                                    user_course ON user_course.id_user = user.id_user AND user_course.deleted = 0                                  
                                INNER JOIN 
                                   course ON user_course.id_course = course.id_course AND user_course.deleted = 0 AND user_course.id_role = 2 
                            WHERE user.deleted = 0 and user_course.id_course = " . $id_course . " 
                            order by user.id_user desc limit 1";

                    $user_exam = $this->fct->search($query);
                    $user_exam_lecture = ucwords($user_exam['0']['name']);
                    // if ($user_exam['0']['signature']) $imagePath =  FCPATH . 'uploads/user/330x60/' . $user_exam['0']['signature'];
                    // else $imagePath = FCPATH . 'uploads/watrmark.jpeg';
                    $imagePath = FCPATH . 'uploads/signature.png';

                    // echo $user_exam['0']['signature'];
                    // echo "<br>";
                    // echo "<pre>sssssssss";
                    // print_r($imagePath);
                    // exit();



                    $id_grade = 1;
                    ///////////////////////////////////////
                    $url = FCPATH . 'uploads/certificate_signature.pdf';
                    // Create a new FPDI instance
                    $pdf = new Fpdi();

                    // Set the source file
                    $pdf->setSourceFile($url);

                    // Import the first page
                    $templateId = $pdf->importPage(1);

                    // Get the size of the imported page
                    $size = $pdf->getTemplateSize($templateId);

                    // Add a page with the same size as the template
                    $pdf->AddPage($size['orientation'], array($size['width'], $size['height']));

                    // Use the imported page as template and fit it to the entire page
                    $pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height']);


                    // $pdf_content = file_get_contents($templateId);
                    // $templateId = str_replace('{variable2}', 'sssssssss', $pdf_content);


                    $pdf->AddFont('GreatVibes-Regular', '', 'GreatVibes-Regular.php');
                    $pdf->SetFont('GreatVibes-Regular', '', 25);
                    // $pdf->Cell(0, 10, 'This text is in the FREESCPT font', 0, 1, 'C');

                    $cond = array('id_user' => $id_user);
                    $user = $this->fct->getonerow("user", $cond);

                    $cond = array('id_course' => $id_course);
                    $course = $this->fct->getonerow("course", $cond);



                    $pdf->SetTextColor(199, 177, 134); //  color
                    $pdf->SetXY(120, 95);
                    $pdf->Cell(40, 10, ucwords($user['name']));

                    // Set font and add text at specific positions
                    $pdf->SetFont('Arial', 'B', 16);
                    $pdf->SetTextColor(0, 0, 0); //  color
                    // Add the second text
                    /////////////////////////////////
                    // The text you want to center
                    $text = strtoupper(ucwords($course['title']));
                    // Get the width of the page
                    $pageWidth = $pdf->GetPageWidth();
                    // Calculate the width of the text
                    $textWidth = $pdf->GetStringWidth($text);
                    // Calculate the X position to center the text
                    $x = ($pageWidth - $textWidth) / 2;
                    // Set the Y position where you want the text to appear
                    $y = 115;
                    // Set the position and add the text
                    $pdf->SetXY($x + 5, $y);
                    $pdf->Cell($textWidth, 30, $text, 0, 0, 'C');
                    /////////////////////////////////

                    // $pdf->SetXY(60, 115);
                    // $pdf->Cell(240, 30, strtoupper(ucwords($course['title'])));
                    $date = date('d-m-Y');
                    // Add the third text
                    $pdf->SetXY(60, 147);
                    $dateincell = date('F j, Y');
                    $pdf->Cell(10, 20, $dateincell);

                    $pdf->AddFont('GreatVibes-Regular', '', 'GreatVibes-Regular.php');
                    $pdf->SetFont('GreatVibes-Regular', '', 25);
                    // Add the fourth text
                    // $pdf->SetXY(185, 160);

                    // $user_exam_lecture = 'Sted Training Center';
                    // $pdf->Cell(10, 20, $user_exam_lecture);

                    // Add the image watermark

                    // $pageWidth = $pdf->GetPageWidth();
                    //$imagePath = FCPATH . 'uploads/watrmark.jpeg'; // Change the path to your image file
                    // Set position and add the image watermark
                    // $pdf->Image($imagePath, 195, 150, 50); // Adjust the x, y, width, and height as necessary
                    //////////////////

                    $certificate = 'certificate_' . $id_course . '_' . $id_user . '_' . $date . '.pdf';
                    // Output the PDF inline
                    $pdf->Output('F', 'uploads/exam_history/' . $certificate);
                    /////////////////////////////////////  png //////////////////////////////////////////////////////////////////////////
                    // User and course IDs
                    // $userId = $this->session->userdata('login');
                    // $id_user = $userId['idUser'];
                    // $id_course = 17;

                    // Define the path to the template image
                    $templatePath = FCPATH . 'uploads/exam_history/template_image.png'; // Update with the path to your template image

                    // Load the template image
                    $image = imagecreatefrompng($templatePath);

                    // Check if the image was loaded successfully
                    if (!$image) {
                        die('Error loading template image.');
                    }

                    // Define colors
                    $textColor1 = imagecolorallocate($image, 199, 177, 134); // Custom text color
                    $textColor2 = imagecolorallocate($image, 0, 0, 0);       // Black text color

                    // Set the path to your font
                    $fontPath = FCPATH . 'uploads/exam_history/GreatVibes-Regular.ttf'; // Update to the path of your TTF font

                    // Retrieve user and course data
                    $cond = array('id_user' => $id_user);
                    $user = $this->fct->getonerow("user", $cond);

                    $cond = array('id_course' => $id_course);
                    $course = $this->fct->getonerow("course", $cond);

                    if (!$user || !$course) {
                        die('User or course data not found.');
                    }

                    $imageWidth = imagesx($image); // Get the width of the template image
                    // Add user name text using TTF font
                    $fontSize = 30; // Font size in points
                    $x = 650;
                    $y = 470;

                    // Calculate the bounding box for the course title text
                    $textBoundingBox = imagettfbbox($fontSize, 0, $fontPath, ucwords($user['name']));
                    $textWidth = $textBoundingBox[2] - $textBoundingBox[0];
                    // $textHeight = $textBoundingBox[1] - $textBoundingBox[7]; // Use the y-coordinates to get the height
                    $x = ($imageWidth - $textWidth) / 2;

                    // imagettftext($image, $fontSize, 0, $x, $y, $textColor1, $fontPath, ucwords($user['name']));
                    imagettftext($image, $fontSize, 0, $x, $y, $textColor1, $fontPath, ucwords($user['name']));
                    // imagestring($image, $fontSize, $x, $y, ucwords($user['name']), $textColor1);

                    $fontPath = FCPATH . 'uploads/exam_history/arial.ttf';
                    // Add course title text, centered
                    $text = strtoupper(ucwords($course['title']));
                    $fontSize = 20; // Font size in points

                    // Calculate the bounding box for the course title text
                    $textBoundingBox = imagettfbbox($fontSize, 0, $fontPath, $text);
                    $textWidth = $textBoundingBox[2] - $textBoundingBox[0];
                    $textHeight = $textBoundingBox[1] - $textBoundingBox[7]; // Use the y-coordinates to get the height
                    $x = ($imageWidth - $textWidth) / 2;
                    $y = 600;
                    imagettftext($image, $fontSize, 0, $x, $y, $textColor2, $fontPath, $text);
                    // imagestring($image, $fontSize, $x, $y, $text, $textColor2);

                    // Add date text
                    $dateincell = date('F j, Y');
                    $fontSize = 20; // Font size in points
                    // $x = 260;
                    $y = 715;

                    // Calculate the bounding box for the course title text
                    $textBoundingBox = imagettfbbox($fontSize, 0, $fontPath, $dateincell);
                    $textWidth = $textBoundingBox[2] - $textBoundingBox[0];
                    $textHeight = $textBoundingBox[1] - $textBoundingBox[7]; // Use the y-coordinates to get the height
                    //$x = 170 + $textWidth / 2;
                    $x = 167 + $textWidth / 2;

                    imagettftext($image, $fontSize, 0, $x, $y, $textColor2, $fontPath, $dateincell);
                    $user_name = $user['name'];
                    $certificate = 'certificate_' . '_' . $user_name . '_' . $id_course . '_' . $id_user . '_' . date('d-m-Y') . '.png';
                    // Save the image to a file
                    $pngPath = FCPATH . 'uploads/exam_history/' . $certificate;
                    if (!imagepng($image, $pngPath)) {
                        die('Error saving the image.');
                    }
                    // Free up memory
                    imagedestroy($image);
                    /////////////////////// png //////////////////////////////////////////////////////////////////////////////
                }
                $exam_date = date("Y-m-d h:i:s");

                // $cond = array('id_course' => $id_course, 'id_exam' => $id_exam, 'id_user' => $id_user);
                // $exam_historys = $this->fct->getAll_cond('exam_history', "sort_order", $cond);
                // $number_attempts_exist = count($exam_historys) + 1;

                $query = "select exam_history.*
                                from exam_history  WHERE id_course = " . $id_course . " and id_user = " . $id_user . " 
                                and id_exam = " . $id_exam . " order by id_exam_history desc limit 1";
                $exam_historys = $this->fct->search($query);
                if (isset($exam_historys['0']) && isset($exam_historys['0']['number_attempts']))
                    $number_attempts_exist = $exam_historys['0']['number_attempts'] + 1;
                else
                    $number_attempts_exist = 1;


                $cond = array('id_course' => $id_course, 'id_exam' => $id_exam);
                $number_of_attempts = $this->fct->getonerow('exam', $cond);
                $max_number_of_attempts = $number_of_attempts['number_of_attempts'];



                // commentss  if ($number_attempts_exist <= $max_number_of_attempts) {
                $_data["created_date"] = date("Y-m-d h:i:s");
                $_data["id_course"] = $id_course;
                $_data["id_user"] = $id_user;
                $_data["exam_date"] = $exam_date;
                $_data["id_exam"] = $id_exam;
                $_data["id_grade"] = $id_grade;
                $_data["score"] = $score;
                $_data["certificate"] = $certificate;
                if ($number_attempts_exist == 0)
                    $number_attempts_exist = 1;
                $_data["number_attempts"] = $number_attempts_exist;


                $this->db->insert('exam_history', $_data);
                // Get the last insert ID
                $last_insert_id = $this->db->insert_id();
                // }
                ///////////////////////////  results
                /////////exam_history        
            } else {
                echo "Error: " . $this->db->error(); // Handle error accordingly based on your database interaction method
            }
        } else
            echo "-1";
        //}




        //////////////////////////////////////////////// all question 
        $qry = "SELECT DISTINCT
            question.id_question,
            exam.number_of_attempts,
            user_exam.*,
            exam.id_exam,
            exam.title AS examtitle,
            question.title AS questiontitle,
            answer.title AS answertitle,
            answer.id_answer,
            answer.true,
            CASE 
                WHEN user_exam.id_answer = answer.id_answer AND answer.true = 1 THEN user_exam.status + 1
                ELSE user_exam.status
            END AS answer_true,
            CASE 
                WHEN user_exam.id_answer = answer.id_answer AND answer.true = 0 THEN exam.status + 1
                ELSE exam.status
            END AS answer_false
        FROM
            exam
        INNER JOIN
            question ON question.id_exam = exam.id_exam AND question.deleted = 0
        INNER JOIN
            answer ON answer.id_question = question.id_question AND answer.deleted = 0
        INNER JOIN
            user_course ON user_course.id_course = exam.id_course
        LEFT JOIN
            user_exam ON user_exam.id_question = question.id_question AND user_exam.id_user = user_course.id_user
        WHERE
            user_course.id_role = 3
            AND exam.deleted = 0
            AND user_course.deleted = 0
            AND question.deleted = 0
            AND answer.deleted = 0
            AND exam.id_course = $id_course
            AND user_course.id_user = $id_user
            AND user_exam.id_exam = $id_exam
        ORDER BY
            question.sort_order,
            answer.id_answer,
            answer.sort_order";

        $rows = $this->fct->search($qry);



        $all_questions = [];
        $total_answer_true = 0;
        $total_answer_false = 0;

        foreach ($rows as $row) {
            $question_id = $row['id_question'];

            // Initialize the question array if it doesn't exist yet
            if (!isset($all_questions[$question_id])) {

                $data['exam'] = [
                    'number_of_attempts' => $row['number_of_attempts'],
                    'id_exam' => $row['id_exam'],
                    'examtitle' => $row['examtitle'],
                    'id_user_exam' => $row['id_user_exam'],
                    'id_user' => $row['id_user'],
                ];

                $all_questions[$question_id] = [
                    'id_question' => $question_id,
                    'questiontitle' => $row['questiontitle'],
                    'answers' => [],
                ];
            }

            // Add answer details to the question's answers array
            $all_questions[$question_id]['answers'][] = [
                'id_answer' => $row['id_answer'],
                'answertitle' => $row['answertitle'],
                'true' => $row['true'],
                'answer_true' => $row['answer_true'],
                'answer_false' => $row['answer_false'],
            ];

            // Accumulate total counts of correct and incorrect answers
            $total_answer_true += $row['answer_true'];
            $total_answer_false += $row['answer_false'];
        }

        $data['examdetails'] = $all_questions;
        // echo "<pre>";
        // print_r( $all_questions);
        // exit();
        ////////////////////////////////////////////////
        $query = "select exam_history.*,user.image,user.name 
           from exam_history 
            JOIN user ON user.id_user = exam_history.id_user
            WHERE exam_history.id_course = " . $id_course . " and exam_history.id_user = " . $id_user . " 
            order by id_exam_history desc limit 1";

        $data['exam_history'] = $this->fct->search($query);
        $data['id_course'] = $id_course;

        //if ($data['exam_history'])
        $this->load->view('exam_result', $data);

        // echo  $this->db->last_query();
        // echo "<br>";
        // echo $number_attempts_exist;
        // echo "<br>";
        // echo $max_number_of_attempts;
        // echo "<br>";
        // exit();    
    }

    public function index()
    {
        ////////// Start Header  
        $cond = array('admin_id' => 2);
        $data['generalinfo'] = $this->fct->getonerow("admin", $cond);
        $data['menuactive'] = "home";
        $cond = array('active' => 1);
        $data['category'] = $this->fct->getAll_cond('category', "sort_order", $cond);

        $cond = array('top_header' => '1');
        $data['header_news'] = $this->fct->getAll_limit('news', "sort_order", "desc", "5", $cond);
        $cond = array('top_course' => '1', 'active' => 1);
        $data['header_course'] = $this->fct->getAll_limit('course', "sort_order", "desc", "5", $cond);
        $home_page = $this->fct->getAll('home_page', "sort_order");
        $data['home_page'] = [];
        foreach ($home_page as $row) {
            $data['home_page'][$row['id_home_page']] = $row;
        }
        $data['link_footer'] = $this->fct->getAll('link_footer', "sort_order");
        // Initialize an empty associative array for category -> course mapping
        $category_course = array();
        $cond = array('active' => 1);
        $result = $this->fct->getAll_cond('course', "sort_order", $cond);
        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_course[$idCategory][] = $row;
        }
        $data['category_course'] = $category_course;

        // Added for 3-level menu: group_category -> category -> course
        $cond = array('active' => 1);
        $data['group_category'] = $this->fct->getAll_cond('group_category', "sort_order", $cond);

        //$data['group_category'] = $this->fct->getAll("group_category", "sort_order");
        $group_category_category = array();
        foreach ($data['category'] as $row) {
            $idSuperCategory = (int) $row['id_group_category'];
            $group_category_category[$idSuperCategory][] = $row;
        }
        $data['group_category_category'] = $group_category_category;


        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;

        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        //////////  End Header
        $cond = array('id_user' => $id_user);
        $favoritecourse = $this->fct->getAll_cond('user_favorite', "sort_order", $cond);
        foreach ($favoritecourse as $row) {
            $data['favorit'][$row['id_course']] = $row['active'];
        }
        //////////////////


        $data['banners'] = $this->fct->getAll("banner", "sort_order");
        $cond = array('active' => 1);
        $data['course'] = $this->fct->getAll_cond("course", "sort_order", $cond);
        //////////////////////
        $data['icons'] = $this->fct->getAll("icons_table", "sort_order");
        $data['brands'] = $this->fct->getAll("brands", "sort_order");

        $cond = array('id_home_page' => '1');
        $data['subheadericons'] = $this->fct->getAll_cond('icons_table', "sort_order", $cond);
        $cond = array('id_home_page' => '2');
        $data['Qualityeducationicons'] = $this->fct->getAll_cond('icons_table', "sort_order", $cond);
        $cond = array('id_home_page' => '3');
        $data['popularcoursesicons'] = $this->fct->getAll_cond('icons_table', "sort_order", $cond);
        $cond = array('id_home_page' => '7');
        $data['learnoppotrunities'] = $this->fct->getAll_cond('icons_table', "sort_order", $cond);

        $qry = "select count(course.id_category) as coursenbr,category.id_category,category.title,category.image
        from category left join course
                on  category.id_category = course.id_category
                where category.deleted = 0 and IFNULL(course.deleted ,0)=0 
                and `course`.`active` =1  and `category`.`active`=1 
                group by category.id_category";
        $data['subcategory'] = $this->fct->search($qry); //and trending = 1 

        // test
        $qry = "SELECT 
            course.id_course,
            course.title AS coursetitle,
            category.title AS cattitle,
            course.schedule,
            course.price,
            course.old_price,
            course.top_course,
            course.summary,
            course.image,
            AVG(user_course_rating.rating) AS avg_course_rating
        FROM 
            course
       INNER JOIN 
            category ON course.id_category = category.id_category
        LEFT JOIN 
        user_course_rating ON course.id_course = user_course_rating.id_course
        WHERE 
            course.deleted = 0
            AND category.deleted = 0
            AND course.top_course = 1
            and `course`.`active` =1  and `category`.`active`=1
        GROUP BY 
            course.id_course";
        // $query = "select * from course where id_course= 1 ";//.$id_course;
        $data['topcourse'] = $this->fct->search($qry);


        $cond = array('deleted' => '0');
        $data['lecturer'] = $this->fct->getAll_cond('user', "sort_order", $cond);


        $data["content"] = "home";
        $this->load->view('template', $data);
    }


    public function enrollcoursedata($id_role = 3)
    {

        $id_course = (int) $_REQUEST['idcourse'];
        $userId = $this->session->userdata('login');
        // Security: only an admin may enroll with another role (lecturer/admin) - everyone else is a student
        if (empty($userId['id_init_role']) || $userId['id_init_role'] != 1)
            $id_role = 3;
        $_data["id_user"] = $userId['idUser'];
        $_data["id_course"] = $id_course;
        $_data["id_role"] = $id_role; // defaults students

        $cond = array('id_user' => $_data['id_user'], 'id_course' => $_data['id_course']);
        $user_course = $this->fct->getAll_cond('user_course', "sort_order", $cond);
        if (count($user_course)) {
            echo "0"; // alredy exist
        } else {

            $_data["created_date"] = date("Y-m-d h:i:s");
            $ccconn = '';
            if ($_data["id_user"] && $_data["id_course"])
                $ccconn = $this->db->insert('user_course', $_data);
            echo ($ccconn) ? 1 : 0;
        }

        //echo response;

    }



    public function updatedata()
    {
        $userId = $this->session->userdata('login');
        $id_user = (int) $userId['idUser'];
        // Security: login required
        if (!$id_user) {
            echo 0;
            exit();
        }
        $id_profile = 0;
        if (isset($_REQUEST['id_profile']) && $_REQUEST['id_profile'])
            $id_profile = (int) $_REQUEST['id_profile'];

        // permission admin
        $qrys = "SELECT 
                role.title as role,
                user.id_user,
                user.name,
                user.email,
                user.username,
                user.phone,
                id_countries
            FROM 
                user 
            INNER JOIN
                role ON role.id_role = user.id_role
            WHERE
                user.deleted = 0 and user.id_role='1' and user.id_user= $id_user
            GROUP BY 
                user.id_user
            ORDER BY 
                user.id_role";
        $permisssion_admin_user = $this->fct->search($qrys);

        //echo  $this->db->last_query();


        if ($id_user && $permisssion_admin_user['0']['role'] == "Admin")
            if ($id_profile)
                $id_user = $id_profile;
        // else 
        // if($id_user != $id_profile){

        // }
        // end permission

        // Define an array to store the data for this iteration
        $data = array();

        // Iterate over different data keys based on $row
        $fields = array('name', 'active', 'email', 'phone', 'username', 'occupation', 'bio', 'website', 'facebook', 'twitter', 'linkedin', 'age', 'id_gender', 'years_of_exp', 'image', 'id_countries');

        // Iterate over the fields and check if corresponding data is not NULL
        foreach ($fields as $field) {
            $field_data = isset($_REQUEST[$field]) ? $_REQUEST[$field] : NULL;

            if ($field_data !== NULL) {
                // Process the date fields using your custom function
                // if (strpos($field, 'date') !== false) {
                //     $field_data = substr($this->fct->date_in_formate($field_data), 1, -1);
                // }

                // Add the non-NULL data to the $data array
                $data[$field] = $field_data;
            }
        }

        // Security: only an admin can change the account "active" status
        if (empty($permisssion_admin_user) || $permisssion_admin_user['0']['role'] != "Admin")
            unset($data['active']);

        // Check if there is any data to update
        if (count($data) > 0) {
            $this->db->where('id_user', $id_user);
            $ccconn = $this->db->update('user', $data);

            // echo  $this->db->last_query();
            // //   echo "<pre>";
            // // // print_r($userId);
            // exit();

            // Check the result of the update operation
            echo ($ccconn) ? 1 : 0;
        } else {
            // No data to update for this iteration
            echo 0;
        }
    }


    public function favorite()
    {
        $userId = $this->session->userdata('login');
        // Define an array to store the data for this iteration
        $data = array();
        if ($userId['idUser']) {
            $_data['id_user'] = $userId['idUser'];
            $_data['id_course'] = $_REQUEST['idcourse'];
            $_data['active'] = $_REQUEST['active'];
            // Check if there is any data to update
            if (count($_data) > 0) {

                $cond = array('id_user' => $_data['id_user'], 'id_course' => $_data['id_course']);
                $favoritecourse = $this->fct->getAll_cond('user_favorite', "sort_order", $cond);


                if (count($favoritecourse)) {
                    $this->db->where('id_user', $userId['idUser']);
                    $this->db->where('id_course', $_data['id_course']);
                    $ccconn = $this->db->update('user_favorite', $_data);
                } else {
                    $_data["created_date"] = date("Y-m-d h:i:s");
                    $ccconn = $this->db->insert('user_favorite', $_data);
                    // echo $this->db->last_query();
                }


                // Check the result of the update operation
                echo ($ccconn) ? 1 : 0;
            } else {
                // No data to update for this iteration
                echo 0;
            }
        } else
            echo 0;
    }


    public function editprofile($id_profile = '')
    {
        ////////// Start Header  
        $cond = array('admin_id' => 2);
        $data['generalinfo'] = $this->fct->getonerow("admin", $cond);
        $data['menuactive'] = "home";
        $cond = array('active' => 1);
        $data['category'] = $this->fct->getAll_cond('category', "sort_order", $cond);

        $cond = array('top_header' => '1');
        $data['header_news'] = $this->fct->getAll_limit('news', "sort_order", "desc", "5", $cond);
        $cond = array('top_course' => '1', 'active' => 1);
        $data['header_course'] = $this->fct->getAll_limit('course', "sort_order", "desc", "5", $cond);
        $home_page = $this->fct->getAll('home_page', "sort_order");
        $data['home_page'] = [];
        foreach ($home_page as $row) {
            $data['home_page'][$row['id_home_page']] = $row;
        }
        $data['link_footer'] = $this->fct->getAll('link_footer', "sort_order");
        // Initialize an empty associative array
        $category_course = array();
        $cond = array('active' => 1);
        $result = $this->fct->getAll_cond('course', "sort_order", $cond);
        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_course[$idCategory][] = $row;
        }
        $data['category_course'] = $category_course;

        // Added for 3-level menu: group_category -> category -> course
        $cond = array('active' => 1);
        $data['group_category'] = $this->fct->getAll_cond('group_category', "sort_order", $cond);
        $group_category_category = array();
        foreach ($data['category'] as $row) {
            $idSuperCategory = (int) $row['id_group_category'];
            $group_category_category[$idSuperCategory][] = $row;
        }
        $data['group_category_category'] = $group_category_category;


        if (!$this->session->userdata('login')) {
            echo "<script>alert('You need to log in!');</script>";
            echo '<script>window.location.href="' . base_url() . '";</script>';
            exit; // Make sure to exit to prevent further execution
        }
        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;

        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        //////////  End Header

        ///////////////////////////////////////////////////
        // permission admin
        $qrys = "SELECT 
 role.title as role,
 user.id_user,
 user.name,
 user.email,
 user.username,
 user.phone
FROM 
 user 
INNER JOIN
 role ON role.id_role = user.id_role
WHERE
 user.deleted = 0 and user.id_role='1' and user.id_user= $id_user
GROUP BY 
 user.id_user
ORDER BY 
 user.id_role";
        $permisssion_admin_user = $this->fct->search($qrys);

        if ($id_user && $permisssion_admin_user['0']['role'] == "Admin")
            if ($id_profile)
                $id_user = $id_profile;

        // if($id_profile == ''){
        //     $id_profile=$id_user;
        //   }
        ///////////////////////////////////////////////////


        $cond = array('id_user' => $id_user);
        $data['user'] = $this->fct->getonerow('user', $cond);

        $qrys = "select * from countries where deleted = 0 order by title asc";
        $data['countries'] = $this->fct->search($qrys);


        $data['id_profile'] = $id_profile;

        $data["content"] = "editprofile";
        $this->load->view('template', $data);
    }


    public function enrolled_courses()
    {
        ////////// Start Header  
        $cond = array('admin_id' => 2);
        $data['generalinfo'] = $this->fct->getonerow("admin", $cond);
        $data['menuactive'] = "home";
        $cond = array('active' => 1);
        $data['category'] = $this->fct->getAll_cond('category', "sort_order", $cond);

        $cond = array('top_header' => '1');
        $data['header_news'] = $this->fct->getAll_limit('news', "sort_order", "desc", "5", $cond);
        $cond = array('top_course' => '1', 'active' => 1);
        $data['header_course'] = $this->fct->getAll_limit('course', "sort_order", "desc", "5", $cond);
        $home_page = $this->fct->getAll('home_page', "sort_order");
        $data['home_page'] = [];
        foreach ($home_page as $row) {
            $data['home_page'][$row['id_home_page']] = $row;
        }
        $data['link_footer'] = $this->fct->getAll('link_footer', "sort_order");
        // Initialize an empty associative array
        $category_course = array();
        $cond = array('active' => 1);
        $result = $this->fct->getAll_cond('course', "sort_order", $cond);

        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_course[$idCategory][] = $row;
        }
        $data['category_course'] = $category_course;

        // Added for 3-level menu: group_category -> category -> course
        $cond = array('active' => 1);
        $data['group_category'] = $this->fct->getAll_cond('group_category', "sort_order", $cond);
        $group_category_category = array();
        foreach ($data['category'] as $row) {
            $idSuperCategory = (int) $row['id_group_category'];
            $group_category_category[$idSuperCategory][] = $row;
        }
        $data['group_category_category'] = $group_category_category;

        if (!$this->session->userdata('login')) {
            echo "<script>alert('You need to log in!');</script>";
            echo '<script>window.location.href="' . base_url() . '";</script>';
            exit; // Make sure to exit to prevent further execution
        }
        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;


        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        //////////  End Header
        $qry = "select course.id_course,course.image,course.title coursetitle,course.summary,category.title subtitle
            from user_course,course,category
            where course.id_course = user_course.id_course
            and course.id_category= category.id_category
            and course.deleted = 0
            and course.active = 1
            and user_course.deleted = 0
            and category.deleted = 0 
            and user_course.id_user = " . $id_user;
        $data['enrolledcourses'] = $this->fct->search($qry);

        $qrys = "SELECT 
            course.id_course,
            -- COUNT(DISTINCT user_content.id_content) AS completed_contents,
            -- COUNT(DISTINCT content.id_content) AS total_contents,
            ROUND(COUNT(DISTINCT user_content.id_content) / COUNT(DISTINCT content.id_content) * 100, 2) AS completion_percentage
            FROM 
                course 
            LEFT JOIN 
                content ON content.id_course = course.id_course and content.deleted=0 
            LEFT JOIN  
                user_content ON content.id_content = user_content.id_content AND user_content.id_user =" . $id_user . " 
                and user_content.deleted=0
                where course.deleted=0
            GROUP BY 
                course.id_course";

        $avgPercentageResults = $this->fct->search($qrys);
        $avgCoursePercentage = [];
        foreach ($avgPercentageResults as $row) {
            $avgCoursePercentage[$row['id_course']] = $row['completion_percentage'];
        }
        $data['avgcourse'] = $avgCoursePercentage;




        $cond = array('id_user' => $userId['idUser']);
        $data['user'] = $this->fct->getonerow('user', $cond);

        $data["content"] = "enrolled_courses";
        $this->load->view('template', $data);
    }



    public function wishlistdata()
    {
        ////////// Start Header  
        $cond = array('admin_id' => 2);
        $data['generalinfo'] = $this->fct->getonerow("admin", $cond);
        $data['menuactive'] = "home";
        $cond = array('active' => 1);
        $data['category'] = $this->fct->getAll_cond('category', "sort_order", $cond);

        $cond = array('top_header' => '1');
        $data['header_news'] = $this->fct->getAll_limit('news', "sort_order", "desc", "5", $cond);
        $cond = array('top_course' => '1', 'active' => 1);
        $data['header_course'] = $this->fct->getAll_limit('course', "sort_order", "desc", "5", $cond);
        $home_page = $this->fct->getAll('home_page', "sort_order");
        $data['home_page'] = [];
        foreach ($home_page as $row) {
            $data['home_page'][$row['id_home_page']] = $row;
        }
        $data['link_footer'] = $this->fct->getAll('link_footer', "sort_order");
        // Initialize an empty associative array
        $category_course = array();
        $cond = array('active' => 1);
        $result = $this->fct->getAll_cond('course', "sort_order", $cond);
        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_course[$idCategory][] = $row;
        }
        $data['category_course'] = $category_course;

        // Added for 3-level menu: group_category -> category -> course
        $cond = array('active' => 1);
        $data['group_category'] = $this->fct->getAll_cond('group_category', "sort_order", $cond);
        $group_category_category = array();
        foreach ($data['category'] as $row) {
            $idSuperCategory = (int) $row['id_group_category'];
            $group_category_category[$idSuperCategory][] = $row;
        }
        $data['group_category_category'] = $group_category_category;


        if (!$this->session->userdata('login')) {
            echo "<script>alert('You need to log in!');</script>";
            echo '<script>window.location.href="' . base_url() . '";</script>';
            exit; // Make sure to exit to prevent further execution
        }
        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;

        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        //////////  End Header


        $qry = "select course.id_course,course.image,course.title coursetitle,course.summary,category.title subtitle
    from user_favorite,course,category
    where course.id_course = user_favorite.id_course
    and course.id_category= category.id_category
    and course.deleted = 0
    and course.active = 1
    and user_favorite.deleted = 0
    and user_favorite.active = 1
    and user_favorite.id_user = " . $id_user;
        $data['wishcourses'] = $this->fct->search($qry);

        $cond = array('id_user' => $userId['idUser']);
        $data['user'] = $this->fct->getonerow('user', $cond);


        $data["content"] = "wishlist";
        $this->load->view('template', $data);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url() . "home");
    }

    public function Registration()
    {

        // $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        ////////////////////////////
        /*Data that we receive from ajax*/
        $email = $this->input->post('email');
        $phone = $this->input->post('phone');

        $username = $this->input->post('username_r');
        $password = $this->input->post('password_r');
        $name = $this->input->post('name');

        if (!isset($email) || $email == '' || $email == 'undefined') {
            /*If Username that we recieved is invalid, go here, and return 2 as output*/
            echo 2;
            exit();
        }
        if (!isset($phone) || $phone == '' || $phone == 'undefined') {
            /*If Password that we recieved is invalid, go here, and return 3 as output*/
            echo 3;
            exit();
        }
        if (!isset($password) || $password == '' || $password == 'undefined') {
            /*If Password that we recieved is invalid, go here, and return 3 as output*/
            echo 4;
            exit();
        }
        if (!isset($username) || $username == '' || $username == 'undefined') {
            /*If Password that we recieved is invalid, go here, and return 3 as output*/
            echo 5;
            exit();
        }
        if (!isset($name) || $name == '' || $name == 'undefined') {
            /*If Password that we recieved is invalid, go here, and return 3 as output*/
            echo 6;
            exit();
        }

        $this->db->select('*');
        $this->db->from('employee');
        $this->db->where('email', $email);
        $query = $this->db->get();
        $result = $query->result();
        //  echo $this->db->last_query();
        //  exit;
        if (count($result) == 0) {
            echo 100;
            exit();
        }

        //////////////// if User Exist 
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('email', $email);
        $query = $this->db->get();
        $result = $query->result();
        // echo $this->db->last_query();
        //  exit;
        // echo $this->db->last_query();
        if (count($result) > 0) {

            if ($result[0]->active == 0) {
                //////////////////////////////////////////
                function generateRandomCode($length = 10)
                {
                    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $charactersLength = strlen($characters);
                    $randomCode = '';
                    for ($i = 0; $i < $length; $i++) {
                        $randomCode .= $characters[random_int(0, $charactersLength - 1)]; // Security: unpredictable activation / reset codes
                    }
                    return $randomCode;
                }

                $name = $result[0]->name;
                $_data["updated_date"] = date("Y-m-d h:i:s");
                $_data["hashsendcode"] = generateRandomCode(12);
                $this->db->where("id_user", $result[0]->id_user);
                $this->db->update('user', $_data);
                // echo $this->db->last_query();
                // The email address to send the reset link to
                // $email = "aymanserhan82@gmail.com";

                // The base URL of your website
                $baseUrl = base_url();

                // Construct the email message
                $msg = '<html><body>';
                $msg .= '<p>Hi ' . $name . ',</p>';
                $msg .= '<p>We are sending you this email because you requested to activate your account. Please click on the link below to complete the activation process:</p>';
                $msg .= '<p><a href="' . $baseUrl . 'activateyourpassword/' . $_data["hashsendcode"] . '">Active Account</a></p>';
                $msg .= '<p>If you did not request to activate an account, please ignore this email.</p>';
                $msg .= '<p>Thanks,<br>STED LTD</p>';
                $msg .= '</body></html>';


                //////////////////////////////

                $toEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
                $subject = htmlspecialchars("Account Activation Required!");
                // $message = htmlspecialchars($msg);

                // Additional headers
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: noreply@stedtrainingcenter.com' . "\r\n";
                //echo $msg;
                // exit();
                if (filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
                    if (mail($toEmail, $subject, $msg, $headers)) {
                        //echo 'Email sent successfully.';
                        echo 1;
                        exit();
                    } else {
                        // echo 'Email sending failed.';
                        echo 2;
                        exit();
                    }
                } else {
                    //echo 'Invalid email address.';
                    echo 2;
                    exit();
                }

                echo 7;
                exit();
                ///////////////////////////////////////
            } else {
                // Security: an existing active account is only opened with its own password
                if ((string) $result[0]->password !== (string) $password) {
                    echo 4;
                    exit();
                }
                // /*If everything is fine, then go here, and return 1 as output and set session*/
                $data = array(
                    'idUser' => $result[0]->id_user,
                    'username' => $result[0]->username,
                    'email' => $email,
                    'fullname' => $result[0]->name,
                    'image' => $result[0]->image,
                    'id_role' => 3,
                    'id_init_role' => $result[0]->id_role
                );
                $this->session->set_userdata('login', $data);
                echo 1;
                exit();
                ////////////////////////
            }

            exit();
        } else {
            function generateRandomCode($length = 10)
            {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomCode = '';
                for ($i = 0; $i < $length; $i++) {
                    $randomCode .= $characters[random_int(0, $charactersLength - 1)]; // Security: unpredictable activation / reset codes
                }
                return $randomCode;
            }

            $name = $name;
            //$_data["updated_date"] = date("Y-m-d h:i:s");
            $_data["hashsendcode"] = generateRandomCode(12);
            // $this->db->where("id_user", $_data);
            //$this->db->update('user', $_data["id_user"]);
            //echo $this->db->last_query();




            $_data["name"] = $name;
            $_data["email"] = $email;
            $_data["phone"] = $phone;
            $_data["username"] = $username;
            $_data["password"] = $password;
            $_data["id_role"] = 3;
            $_data["active"] = 0;
            $_data["created_date"] = date("Y-m-d h:i:s");
            $this->db->insert('user', $_data);
            $_data["id_user"] = $this->db->insert_id();
            // echo $this->db->last_query();
            ////////// 
            // $data = array(
            //     'idUser' => $_data["id_user"],
            //     'username' => $_data["username"],
            //     'email' => $_data["email"],
            //     'fullname' => $_data["name"],
            //     'image' => '',
            //     'id_role' => 3,
            //     'id_init_role' => 0
            // );
            // $this->session->set_userdata('login', $data);

            //////////////////////////////////////////

            // The email address to send the reset link to
            // $email = "aymanserhan82@gmail.com";

            // The base URL of your website
            $baseUrl = base_url();

            // Construct the email message
            $msg = '<html><body>';
            $msg .= '<p>Hi ' . $name . ',</p>';
            $msg .= '<p>We are sending you this email because you requested to activate your account. Please click on the link below to complete the activation process:</p>';
            $msg .= '<p><a href="' . $baseUrl . 'activateyourpassword/' . $_data["hashsendcode"] . '">Active Account</a></p>';
            $msg .= '<p>If you did not request to activate an account, please ignore this email.</p>';
            $msg .= '<p>Thanks,<br>STED LTD</p>';
            $msg .= '</body></html>';


            //////////////////////////////

            $toEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
            $subject = htmlspecialchars("Account Activation Required!");
            // $message = htmlspecialchars($msg);

            // Additional headers
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: noreply@stedtrainingcenter.com' . "\r\n";
            //echo $msg;
            //exit();
            if (filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
                if (mail($toEmail, $subject, $msg, $headers)) {
                    //echo 'Email sent successfully.';
                    echo 1;
                    exit();
                } else {
                    //echo 'Email sending failed.';
                    echo 2;
                    exit();
                }
            } else {
                //echo 'Invalid email address.';
                echo 2;
                exit();
            }
            echo 7;
            exit();
        }
        ////////////////////////
    }

    public function getLogin()
    {
        // $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];$this->session->set_userdata('login', $data);
        /*Data that we receive from ajax*/
        $username = $this->input->post('UserName');
        $Password = $this->input->post('Password');
        if (!isset($username) || $username == '' || $username == 'undefined') {
            /*If Username that we recieved is invalid, go here, and return 2 as output*/
            echo 2;
            exit();
        }
        if (!isset($Password) || $Password == '' || $Password == 'undefined') {
            /*If Password that we recieved is invalid, go here, and return 3 as output*/
            echo 3;
            exit();
        }

        $this->form_validation->set_rules('UserName', 'UserName', 'required');
        $this->form_validation->set_rules('Password', 'Password', 'required');
        if ($this->form_validation->run() == FALSE) {
            /*If Both Username &  Password that we recieved is invalid, go here, and return 4 as output*/
            echo 4;
            exit();
        } else {
            /*validate($username, $Password)*/
            $this->db->select('*');
            $this->db->from('user');
            $this->db->where('deleted', '0');
            $this->db->where('password', $Password);
            // Security: group (username OR email) - otherwise "... AND password=x AND username=u OR email=u" let anyone log in with an email only
            $this->db->where("(username = " . $this->db->escape($username) . " OR email = " . $this->db->escape($username) . ")", NULL, FALSE);
            $query = $this->db->get();
            $result = $query->result();

            //echo $this->db->last_query();
            if (count($result) == 1) {
                if ($result[0]->active > 0) {
                    /*If everything is fine, then go here, and return 1 as output and set session*/
                    $_datao["updated_date"] = date("Y-m-d h:i:s");
                    $this->db->where("id_user", $result[0]->id_user);
                    $this->db->update('user', $_datao);


                    $data = array(
                        'idUser' => $result[0]->id_user,
                        'username' => $result[0]->username,
                        'fullname' => $result[0]->name,
                        'email' => $result[0]->email,
                        'image' => $result[0]->image,
                        'id_init_role' => $result[0]->id_role
                    );

                    $this->session->set_userdata('login', $data);
                    echo 1;
                } else {
                    echo 7;
                }
            } else {
                /*If Both Username &  Password that we recieved is invalid, go here, and return 5 as output*/
                echo 5;
            }
        }
    }

    public function activateyourpassword($activateyourpassword)
    {

        if ($activateyourpassword == '') {


            echo 'error ';

            $this->session->sess_destroy();
            redirect(base_url() . "home");
            exit();
        } else {
            $this->db->select('*');
            $this->db->from('user');
            $this->db->where('deleted', '0');
            $this->db->where('hashsendcode', $activateyourpassword);
            $query = $this->db->get();
            $result = $query->result_array();

            // echo $this->db->last_query();
            // echo "<br>";
            // echo "<pre>";
            // echo count($result);
            // echo "<br>";
            // print_r($result);
            // exit();


            if (count($result)) {
                $id_user = $result['0']['id_user'];
                $email = $result['0']['email'];
                $name = $result['0']['name'];
                $image = $result['0']['image'];
                $id_role = $result['0']['id_role'];
                $username = $result['0']['username'];


                $_data["updated_date"] = date("Y-m-d h:i:s");
                $_data["active"] = 1;
                $_data["hashsendcode"] = '';
                $this->db->where("id_user", $id_user);
                $this->db->update('user', $_data);
                // echo $this->db->last_query();
                // echo "<pre>";
                // print_r($data_session);

                $data_session = array(
                    'idUser' => $id_user,
                    'username' => $username,
                    'fullname' => $name,
                    'email' => $email,
                    'image' => $image,
                    'id_init_role' => $id_role
                );
                $this->session->set_userdata('login', $data_session);

                $this->session->sess_destroy();
                redirect(base_url() . "home");
                echo 1;
                exit();
            } else {


                $this->session->sess_destroy();
                redirect(base_url() . "home");
                echo 2;
                exit();
            }
        }
    }




    public function updatepassword()
    {


        $resetpassword = $this->input->post('resetpassword');
        $this->form_validation->set_rules('resetpassword', 'resetpassword', 'required');
        if ($this->form_validation->run() == FALSE) {
            /*If Both Username &  Password that we recieved is invalid, go here, and return 4 as output*/
            echo 4;
            exit();
        } else {
            $data_session = $this->session->userdata('resetpassword');

            $id_user = $data_session['idUser'];
            $username = $data_session['username'];
            $name = $data_session['fullname'];
            $email = $data_session['email'];
            $image = $data_session['image'];
            $id_role = $data_session['id_init_role'];

            $_data["updated_date"] = date("Y-m-d h:i:s");
            $_data["password"] = $resetpassword;
            $_data["hashsendcode"] = '';
            $this->db->where("id_user", $id_user);
            $this->db->update('user', $_data);
            // echo $this->db->last_query();
            // echo "<pre>";
            // print_r($data_session);

            $data_session = array(
                'idUser' => $id_user,
                'username' => $username,
                'fullname' => $name,
                'email' => $email,
                'image' => $image,
                'id_init_role' => $id_role
            );
            $this->session->set_userdata('login', $data_session);

            echo 1;
            exit();
        }
    }



    public function resetpassword($hashsendcode)
    {
        ////////// Start Header  
        $cond = array('admin_id' => 2);
        $data['generalinfo'] = $this->fct->getonerow("admin", $cond);
        $data['menuactive'] = "home";
        $cond = array('active' => 1);
        $data['category'] = $this->fct->getAll_cond('category', "sort_order", $cond);

        $cond = array('top_header' => '1');
        $data['header_news'] = $this->fct->getAll_limit('news', "sort_order", "desc", "5", $cond);
        $cond = array('top_course' => '1', 'active' => 1);
        $data['header_course'] = $this->fct->getAll_limit('course', "sort_order", "desc", "5", $cond);
        $home_page = $this->fct->getAll('home_page', "sort_order");
        $data['home_page'] = [];
        foreach ($home_page as $row) {
            $data['home_page'][$row['id_home_page']] = $row;
        }
        $data['link_footer'] = $this->fct->getAll('link_footer', "sort_order");
        // Initialize an empty associative array
        $category_course = array();
        $cond = array('active' => 1);
        $result = $this->fct->getAll_cond('course', "sort_order", $cond);
        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_course[$idCategory][] = $row;
        }
        $data['category_course'] = $category_course;

        // Added for 3-level menu: group_category -> category -> course
        $cond = array('active' => 1);
        $data['group_category'] = $this->fct->getAll_cond('group_category', "sort_order", $cond);
        $group_category_category = array();
        foreach ($data['category'] as $row) {
            $idSuperCategory = (int) $row['id_group_category'];
            $group_category_category[$idSuperCategory][] = $row;
        }
        $data['group_category_category'] = $group_category_category;

        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;

        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        //////////  End Header


        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('deleted', '0');
        $this->db->where('hashsendcode', $hashsendcode);
        $query = $this->db->get();
        $result = $query->result_array();

        // echo $this->db->last_query();
        // echo "<br>";
        // echo "<pre>";
        // echo count($result);
        // echo "<br>";
        // print_r($result);
        // exit();


        if (count($result)) {
            $id_user = $result['0']['id_user'];
            $email = $result['0']['email'];
            $name = $result['0']['name'];
            $image = $result['0']['image'];
            $id_role = $result['0']['id_role'];
            $username = $result['0']['username'];

            $data_session = array(
                'idUser' => $id_user,
                'username' => $username,
                'fullname' => $name,
                'email' => $email,
                'image' => $image,
                'id_init_role' => $id_role
            );
            $this->session->set_userdata('resetpassword', $data_session);

            // ///// remove token from reset password 
            // $_data["updated_date"] = date("Y-m-d h:i:s");
            // $_data["hashsendcode"] = '';
            // $this->db->where("id_user", $id_user);
            // $this->db->update('user', $_data);

            $data["content"] = "resetpassword";
            $this->load->view('template', $data);
        } else {
            $this->session->sess_destroy();
            redirect(base_url() . "home");
        }
    }

    public function updateLogin()
    {
        $username = $this->input->post('UserName');
        if (!isset($username) || $username == '' || $username == 'undefined') {
            /*If Username that we recieved is invalid, go here, and return 2 as output*/
            echo 2;
            exit();
        }

        // $this->form_validation->set_rules('UserName', 'UserName', 'required');
        // if ($this->form_validation->run() == FALSE) {
        //     /*If Both Username &  Password that we recieved is invalid, go here, and return 4 as output*/
        //     echo 2;
        //     exit();
        // } else {
        /*validate($username, $Password)*/
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('deleted', '0');
        // Security: group (username OR email) so deleted accounts are really excluded
        $this->db->where("(username = " . $this->db->escape($username) . " OR email = " . $this->db->escape($username) . ")", NULL, FALSE);
        $query = $this->db->get();
        $result = $query->result_array();
        $id_user = $result['0']['id_user'];
        $email = $result['0']['email'];
        $name = ucwords(strtolower($result['0']['name']));
        // echo $this->db->last_query();
        // echo "<pre>";
        // print_r($result);
        // exit();
        function generateRandomCode($length = 10)
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomCode = '';
            for ($i = 0; $i < $length; $i++) {
                $randomCode .= $characters[random_int(0, $charactersLength - 1)]; // Security: unpredictable activation / reset codes
            }
            return $randomCode;
        }


        if (count($result) == 1 && $id_user) {
            // send email + hash insert in my database 

            $_data["updated_date"] = date("Y-m-d h:i:s");
            $_data["hashsendcode"] = generateRandomCode(12);
            $this->db->where("id_user", $id_user);
            $this->db->update('user', $_data);

            // The email address to send the reset link to
            // $email = "aymanserhan82@gmail.com";

            // The base URL of your website
            $baseUrl = base_url();

            // Construct the email message
            $msg = '<html><body>';
            $msg .= '<p>Hi ' . $name . ',</p>';
            $msg .= '<p>We\'re sending you this email because you requested a password reset. Click on the link below to create a new password:</p>';
            $msg .= '<p><a href="' . $baseUrl . '/resetpassword/' . $_data["hashsendcode"] . '">Reset Password</a></p>';
            $msg .= '<p>If you did not request a password reset, please ignore this email.</p>';
            $msg .= '<p>Thanks,<br>STED LTD</p>';
            $msg .= '</body></html>';


            //////////////////////////////

            $toEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
            $subject = htmlspecialchars("Password Reset Request");
            // $message = htmlspecialchars($msg);

            // Additional headers
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: noreply@stedtrainingcenter.com' . "\r\n";

            if (filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
                if (mail($toEmail, $subject, $msg, $headers)) {
                    //echo 'Email sent successfully.';
                } else {
                    echo 'Email sending failed.';
                }
            } else {
                echo 'Invalid email address.';
            }
            //echo $toEmail;

            ////////////////////////////////
            // $this->session->set_userdata("success_message", "Information was updated successfully");
            echo 1;
        } else {
            /*If Both Username &  Password that we recieved is invalid, go here, and return 5 as output*/
            echo 3;
        }
        ////////// }
    }



    public function faqs()
    {
        ////////// Start Header  
        $cond = array('admin_id' => 2);
        $data['generalinfo'] = $this->fct->getonerow("admin", $cond);
        $data['menuactive'] = "home";
        $cond = array('active' => 1);
        $data['category'] = $this->fct->getAll_cond('category', "sort_order", $cond);

        $cond = array('top_header' => '1');
        $data['header_news'] = $this->fct->getAll_limit('news', "sort_order", "desc", "5", $cond);
        $cond = array('top_course' => '1', 'active' => 1);
        $data['header_course'] = $this->fct->getAll_limit('course', "sort_order", "desc", "5", $cond);
        $home_page = $this->fct->getAll('home_page', "sort_order");
        $data['home_page'] = [];
        foreach ($home_page as $row) {
            $data['home_page'][$row['id_home_page']] = $row;
        }
        $data['link_footer'] = $this->fct->getAll('link_footer', "sort_order");
        // Initialize an empty associative array
        $category_course = array();
        $cond = array('active' => 1);
        $result = $this->fct->getAll_cond('course', "sort_order", $cond);
        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_course[$idCategory][] = $row;
        }
        $data['category_course'] = $category_course;

        // Added for 3-level menu: group_category -> category -> course
        $cond = array('active' => 1);
        $data['group_category'] = $this->fct->getAll_cond('group_category', "sort_order", $cond);
        $group_category_category = array();
        foreach ($data['category'] as $row) {
            $idSuperCategory = (int) $row['id_group_category'];
            $group_category_category[$idSuperCategory][] = $row;
        }
        $data['group_category_category'] = $group_category_category;

        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;

        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        //////////  End Header

        $data['faqs'] = $this->fct->getAll("faqs", "sort_order");
        $data["content"] = "faq";
        $this->load->view('template', $data);
    }


    public function aboutus()
    {
        ////////// Start Header  
        $cond = array('admin_id' => 2);
        $data['generalinfo'] = $this->fct->getonerow("admin", $cond);
        $data['menuactive'] = "home";
        $cond = array('active' => 1);
        $data['category'] = $this->fct->getAll_cond('category', "sort_order", $cond);

        $cond = array('top_header' => '1');
        $data['header_news'] = $this->fct->getAll_limit('news', "sort_order", "desc", "5", $cond);
        $cond = array('top_course' => '1', 'active' => 1);
        $data['header_course'] = $this->fct->getAll_limit('course', "sort_order", "desc", "5", $cond);
        $home_page = $this->fct->getAll('home_page', "sort_order");
        $data['home_page'] = [];
        foreach ($home_page as $row) {
            $data['home_page'][$row['id_home_page']] = $row;
        }
        $data['link_footer'] = $this->fct->getAll('link_footer', "sort_order");
        // Initialize an empty associative array
        $category_course = array();
        $cond = array('active' => 1);
        $result = $this->fct->getAll_cond('course', "sort_order", $cond);
        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_course[$idCategory][] = $row;
        }
        $data['category_course'] = $category_course;

        // Added for 3-level menu: group_category -> category -> course
        $cond = array('active' => 1);
        $data['group_category'] = $this->fct->getAll_cond('group_category', "sort_order", $cond);
        $group_category_category = array();
        foreach ($data['category'] as $row) {
            $idSuperCategory = (int) $row['id_group_category'];
            $group_category_category[$idSuperCategory][] = $row;
        }
        $data['group_category_category'] = $group_category_category;

        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;

        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        //////////  End Header

        $data['aboutus'] = $this->fct->getAll("about", "sort_order");

        $data["content"] = "aboutus";
        $this->load->view('template', $data);
    }



    public function category($id_category = 0)
    {
        $id_category = (int) $id_category; // Security: numeric id from the URL (used in SQL)
        ////////// Start Header  
        $cond = array('admin_id' => 2);
        $data['generalinfo'] = $this->fct->getonerow("admin", $cond);
        $data['menuactive'] = "home";
        $cond = array('active' => 1);
        $data['category'] = $this->fct->getAll_cond('category', "sort_order", $cond);

        $cond = array('top_header' => '1');
        $data['header_news'] = $this->fct->getAll_limit('news', "sort_order", "desc", "5", $cond);
        $cond = array('top_course' => '1', 'active' => 1);
        $data['header_course'] = $this->fct->getAll_limit('course', "sort_order", "desc", "5", $cond);
        $home_page = $this->fct->getAll('home_page', "sort_order");
        $data['home_page'] = [];
        foreach ($home_page as $row) {
            $data['home_page'][$row['id_home_page']] = $row;
        }
        $data['link_footer'] = $this->fct->getAll('link_footer', "sort_order");
        // Initialize an empty associative array
        $category_course = array();
        $cond = array('active' => 1);
        $result = $this->fct->getAll_cond('course', "sort_order", $cond);
        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_course[$idCategory][] = $row;
        }
        $data['category_course'] = $category_course;

        // Added for 3-level menu: group_category -> category -> course
        $cond = array('active' => 1);
        $data['group_category'] = $this->fct->getAll_cond('group_category', "sort_order", $cond);
        $group_category_category = array();
        foreach ($data['category'] as $row) {
            $idSuperCategory = (int) $row['id_group_category'];
            $group_category_category[$idSuperCategory][] = $row;
        }
        $data['group_category_category'] = $group_category_category;

        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;

        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        //////////  End Header


        $qry = "select   count(course.id_course)  coursenbr
        FROM category 
        left join course
        on category.id_category = course.id_category
        where category.deleted = 0 and ifnull(category.deleted,0)=0
        and   ifnull(course.deleted,0)=0 and ifnull(course.active,0)=1
        and category.id_category =  " . $id_category;
        $data['countcousre'] = $this->fct->search($qry);


        $qry = "select   count(category.id_category)  subcatenbr
        FROM category 
        where category.deleted = 0 
        and category.id_category = " . $id_category;
        $data['countsubcategory'] = $this->fct->search($qry);


        $qry = "select  category.id_category,category.title,count(course.id_course) coursesnbr,category.image
        from  category  
        left join course on course.id_category=category.id_category
        where IFNULL(course.deleted,0) =0 and IFNULL(course.active,0)=1
        and category.deleted =0
        and category.deleted=0 and category.id_category =" . $id_category;
        $qry = $qry . "  group by category.id_category,category.title,category.image";

        $data['coursesnbrdetails'] = $this->fct->search($qry);


        $cond = array('id_category' => $id_category);
        $data['categoryinfo'] = $this->fct->getonerow("category", $cond);

        $cond = array('id_category' => $id_category, 'active' => 1);
        $data['course'] = $this->fct->getAll_cond('course', "sort_order", $cond);

        $data["content"] = "category";
        $this->load->view('template', $data);
    }

    public function categorydetails($id_category = 0)
    {
        $id_category = (int) $id_category; // Security: numeric id from the URL (used in SQL)
        ////////// Start Header  
        $cond = array('admin_id' => 2);
        $data['generalinfo'] = $this->fct->getonerow("admin", $cond);
        $data['menuactive'] = "home";
        $cond = array('active' => 1);
        $data['category'] = $this->fct->getAll_cond('category', "sort_order", $cond);

        $cond = array('top_header' => '1');
        $data['header_news'] = $this->fct->getAll_limit('news', "sort_order", "desc", "5", $cond);
        $cond = array('top_course' => '1', 'active' => 1);
        $data['header_course'] = $this->fct->getAll_limit('course', "sort_order", "desc", "5", $cond);
        $home_page = $this->fct->getAll('home_page', "sort_order");
        $data['home_page'] = [];
        foreach ($home_page as $row) {
            $data['home_page'][$row['id_home_page']] = $row;
        }
        $data['link_footer'] = $this->fct->getAll('link_footer', "sort_order");
        // Initialize an empty associative array
        $category_course = array();
        $cond = array('active' => 1);
        $result = $this->fct->getAll_cond('course', "sort_order", $cond);
        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_course[$idCategory][] = $row;
        }
        $data['category_course'] = $category_course;

        // Added for 3-level menu: group_category -> category -> course
        $cond = array('active' => 1);
        $data['group_category'] = $this->fct->getAll_cond('group_category', "sort_order", $cond);
        $group_category_category = array();
        foreach ($data['category'] as $row) {
            $idSuperCategory = (int) $row['id_group_category'];
            $group_category_category[$idSuperCategory][] = $row;
        }
        $data['group_category_category'] = $group_category_category;

        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;

        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        //////////  End Header


        $qry = "SELECT 
    course.*,
    AVG(user_course_rating.rating) AS avg_course_rating
FROM 
    course
LEFT JOIN 
    user_course_rating ON course.id_course = user_course_rating.id_course
WHERE 
    course.deleted = 0 AND course.active = 1 AND id_category=$id_category
GROUP BY 
    course.id_course";
        $data['course'] = $this->fct->search($qry);

        $qry = "select category.title cattitle,category.title subcattitle
        FROM  category
                WHERE 
                category.deleted = 0 
                and category.id_category = " . $id_category;
        $data['subcategoryinfo'] = $this->fct->search($qry);

        $data["content"] = "category-details";
        $this->load->view('template', $data);
    }

    public function coursedetails($id_course = 0)
    {
        $id_course = (int) $id_course; // Security: numeric id from the URL (used in SQL)
        ////////// Start Header  
        $cond = array('admin_id' => 2);
        $data['generalinfo'] = $this->fct->getonerow("admin", $cond);
        $data['menuactive'] = "home";
        $cond = array('active' => 1);
        $data['category'] = $this->fct->getAll_cond('category', "sort_order", $cond);

        $cond = array('top_header' => '1');
        $data['header_news'] = $this->fct->getAll_limit('news', "sort_order", "desc", "5", $cond);
        $cond = array('top_course' => '1', 'active' => 1);
        $data['header_course'] = $this->fct->getAll_limit('course', "sort_order", "desc", "5", $cond);
        $home_page = $this->fct->getAll('home_page', "sort_order");
        $data['home_page'] = [];
        foreach ($home_page as $row) {
            $data['home_page'][$row['id_home_page']] = $row;
        }
        $data['link_footer'] = $this->fct->getAll('link_footer', "sort_order");
        // Initialize an empty associative array
        $category_course = array();
        $cond = array('active' => 1);
        $result = $this->fct->getAll_cond('course', "sort_order", $cond);
        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_course[$idCategory][] = $row;
        }
        $data['category_course'] = $category_course;

        // Added for 3-level menu: group_category -> category -> course
        $cond = array('active' => 1);
        $data['group_category'] = $this->fct->getAll_cond('group_category', "sort_order", $cond);
        $group_category_category = array();
        foreach ($data['category'] as $row) {
            $idSuperCategory = (int) $row['id_group_category'];
            $group_category_category[$idSuperCategory][] = $row;
        }
        $data['group_category_category'] = $group_category_category;

        // if(!$this->session->userdata('login')) {
        //     echo "<script>alert('You need to log in!');</script>";
        //     echo '<script>window.location.href="' . base_url() . '";</script>';
        //     exit; 
        // }
        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;
        if ($id_user == '')
            $id_user = 0;

        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        //////////  End Header

        $data['id_exam'] = '';
        $cond = array('id_course' => $id_course);
        $exam = $this->fct->getonerow("exam", $cond);
        if (isset($exam['id_exam']) && $exam['id_exam'])
            $data['id_exam'] = $exam['id_exam'];

        $conda = array('id_course' => $id_course, 'id_user' => $id_user);
        $usercourse = $this->fct->getAll_cond('user_course', "sort_order", $conda);
        $data['userenrollement'] = 0;
        if (count($usercourse)) {
            foreach ($usercourse as $row) {
                if ($row['id_role']) {
                    $data['userenrollement'] = $row['id_role'];
                    if ($row['id_role'] == 2)
                        break;
                } else {
                    $data['userenrollement'] = '0';
                }
            }
        }

        $cond = array('id_course' => $id_course);
        $data['contents'] = $this->fct->getAll_cond('content', "sort_order", $cond);

        $cond = array('id_course' => $id_course, 'id_user' => $id_user);
        $data['my_user_course_rating'] = $this->fct->getonerow('user_course_rating', $cond);


        $qry = " SELECT 
    course.id_course,
    course.title AS coursetitle,
    course.updated_date,
    course.image,
    course.price,
    course.old_price as oldprice,
    course.summary,
    course.description,
    course.hours,
    course.price,
    course.schedule,
    category.title AS categtitle,
    course.requirements,
    AVG(user_course_rating.rating) AS avg_course_rating
FROM 
    course
JOIN 
    category ON category.id_category = course.id_category
LEFT JOIN 
    user_course_rating ON course.id_course = user_course_rating.id_course
WHERE   
    course.deleted = 0 
    AND category.deleted = 0 
    AND course.active = 1
    AND course.id_course =" . $id_course;
        $data['coursedetail'] = $this->fct->searchonerow($qry);

        if (empty($data['coursedetail']) || !isset($data['coursedetail']['id_course']) || (isset($data['coursedetail']['active']) && $data['coursedetail']['active'] != 1)) {
            redirect(base_url());
            exit;
        }

        $qry = "SELECT 
                    course.*,
                    AVG(user_course_rating.rating) AS avg_course_rating
                    FROM  
                        course left join 
                        user_course_rating ON course.id_course = user_course_rating.id_course
                    WHERE 
                    course.id_category IN (
                            SELECT 
                            category.id_category
                            FROM 
                            course, category
                            WHERE 
                            course.id_category = category.id_category
                            AND course.deleted = 0
                            AND category.deleted = 0
                            AND course.id_course = $id_course ) and course.deleted = 0 and course.active = 1 and course.id_course!= $id_course 
                    group by course.id_course";
        $data['similarcourses'] = $this->fct->search($qry);
        // $data['video_course']=$this->fct->getAll_cond('video',"sort_order",$cond);
        ////////////////////////////////////////////////////////// complete user content  by course
        $data['id_content_complete_by_course'][] = [];
        if ($id_user) {
            $qrys = "SELECT 
                        course.id_course,
                        user_content.id_content
                        FROM 
                            course 
                        LEFT JOIN 
                            content ON content.id_course = course.id_course AND content.deleted = 0 
                        INNER JOIN 
                            user_content ON user_content.id_content = content.id_content 
                                AND user_content.id_user = " . $id_user . " AND user_content.deleted = 0
                        WHERE 
                        course.deleted = 0   AND course.id_course= $id_course ";
            $data['user_content_complete'] = $this->fct->search($qrys);
            foreach ($data['user_content_complete'] as $row) {
                // Extract the id_course value from the current row
                $data['id_content_complete_by_course'][] = $row['id_content'];
            }
        }
        //////////////////////////////////////////////////////// End get exam

        // $qry = "SELECT DISTINCT
        //                 exam.number_of_attempts,
        //                 question.id_question,
        //                 exam.id_exam,
        //                 exam.title AS examtitle,
        //                 question.title AS questiontitle,
        //                 answer.title AS answertitle,
        //                 answer.id_answer
        //             FROM
        //                 exam
        //                 INNER JOIN
        //                 question ON question.id_exam = exam.id_exam AND question.deleted = 0
        //                 INNER JOIN
        //                 answer ON answer.id_question = question.id_question AND answer.deleted = 0
        //                 INNER JOIN
        //                 user_course ON user_course.id_course = exam.id_course
        //             WHERE
        //                 user_course.id_role = 3
        //                 AND exam.deleted = 0
        //                 AND user_course.deleted = 0
        //                 AND question.deleted = 0
        //                 AND answer.deleted = 0
        //                 AND exam.id_course = " . $id_course . " 
        //                 AND user_course.id_user = " . $id_user . "
        //                 AND NOW() BETWEEN exam.start_date AND exam.close_date
        //                 ORDER BY
        //                 question.sort_order,
        //                 answer.id_answer,
        //                 answer.sort_order";
        // $rows = $this->fct->search($qry);


        // -- Prepare the main query

        // -- Set the number of questions as a variable
        // -- Prepare the main query

        // $qry1 = "
        //     SET @question_limit = ( SELECT number_of_questions FROM exam WHERE id_course =" . $id_course . " AND deleted = 0 LIMIT 1 );
        //     SET @sql_query = CONCAT(
        //         'SELECT DISTINCT e.number_of_attempts, q.id_question, e.id_exam, e.title AS examtitle, q.title AS questiontitle, a.title AS answertitle, a.id_answer 
        //         FROM (
        //             SELECT DISTINCT question.id_question, question.id_exam, question.title 
        //             FROM question 
        //             WHERE question.id_exam IN (
        //                 SELECT id_exam FROM exam WHERE id_course = " . $id_course . " AND deleted = 0
        //             ) AND question.deleted = 0 
        //             ORDER BY RAND() 
        //             LIMIT ', @question_limit, '
        //         ) q 
        //         INNER JOIN exam e ON q.id_exam = e.id_exam 
        //         INNER JOIN answer a ON a.id_question = q.id_question AND a.deleted = 0 
        //         INNER JOIN user_course uc ON uc.id_course = e.id_course 
        //         WHERE uc.id_role = 3 AND e.deleted = 0 AND uc.deleted = 0 AND e.id_course = " . $id_course . " AND uc.id_user = " . $id_user . " AND NOW() BETWEEN e.start_date AND e.close_date 
        //         ORDER BY q.id_question, a.id_answer, a.sort_order'
        //     );
        //     PREPARE stmt FROM @sql_query;
        //     EXECUTE stmt;
        //     DEALLOCATE PREPARE stmt;
        // ";

        // $query = $this->db->query($qry1);
        // echo "<pre>";
        // print_r($query->result_array());
        // exit();
        // $rows = $this->fct->search($qry1);

        // Step 1: Set the question limit


        $qry_1 = " 
            SELECT number_of_questions 
            FROM exam 
            WHERE id_course = " . $id_course . " 
            AND deleted = 0 
            LIMIT 1 
        ";
        $rows_check = $this->fct->search($qry_1);



        if (count($rows_check) && $rows_check['0']['number_of_questions']) {


            $qry1 = "
            SET @question_limit = (
                SELECT number_of_questions 
                FROM exam 
                WHERE id_course = " . $id_course . " 
                AND deleted = 0 
                LIMIT 1
            );
        ";
            $this->db->query($qry1);

            // Step 2: Construct the dynamic SQL query
            $qry2 = "
            SET @sql_query = CONCAT(
                'SELECT DISTINCT q.id_question,e.number_of_attempts, e.time,  e.id_exam, e.title AS examtitle, q.title AS questiontitle, a.title AS answertitle, a.id_answer 
                FROM (
                    SELECT DISTINCT question.id_question, question.id_exam, question.title 
                    FROM question 
                    WHERE question.id_exam IN (
                        SELECT id_exam 
                        FROM exam 
                        WHERE id_course = " . $id_course . " 
                        AND deleted = 0
                    ) 
                    AND question.deleted = 0 
                    ORDER BY RAND() 
                    LIMIT ', @question_limit, '
                ) q 
                INNER JOIN exam e ON q.id_exam = e.id_exam 
                INNER JOIN answer a ON a.id_question = q.id_question 
                AND a.deleted = 0 
                INNER JOIN user_course uc ON uc.id_course = e.id_course 
                WHERE uc.id_role = 3 
                AND e.deleted = 0 
                AND uc.deleted = 0 
                AND e.id_course = " . $id_course . " 
                AND uc.id_user = " . $id_user . " 
                AND NOW() BETWEEN e.start_date AND e.close_date 
                ORDER BY a.id_answer, a.sort_order'
            );
        ";

            // Output the results
            $this->db->query($qry2);
            $query = $this->db->query("SELECT @sql_query AS result");
            $results = $query->result_array();

            $result_query = $results[0]['result'];
            $query = $this->db->query($result_query);
            $rows = $query->result_array();
        } else {
            $rows = array();
        }


        // Organize the fetched data into the format of $questions array
        $questions = array();
        $all_answers = array();

        // echo "<pre>";
        // print_r( $qry);
        // exit();

        foreach ($rows as $row) {
            $data['exam'] = [
                'id_exam' => $row['id_exam'],
                'time' => $row['time'],
                'number_of_attempts' => $row['number_of_attempts'],
                'examtitle' => $row['examtitle'],
            ];
            // $data['exam'] = [
            //     'id_exam' => $row['id_exam'],
            //     'time' => $row['time'],
            //     'number_of_attempts' => $row['number_of_attempts'],
            //     'examtitle' => $row['examtitle'],
            //     'id_user_exam' => $row['id_user_exam'],
            //     'id_user' => $row['id_user'],
            // ];

            $questionExists = false;

            // Check if the question exists in $questions array
            foreach ($questions as &$question) {
                if ($question['question'] === $row['questiontitle']) {
                    // If the question exists, add the answer title and ID answer to its answers array
                    $question['answers'][] = array(
                        'id_answer' => $row['id_answer'],
                        'answertitle' => $row['answertitle']
                    );

                    // Also, add the answer to the $all_answers array for this question
                    $all_answers[$row['id_question']][] = array(
                        'id_answer' => $row['id_answer'],
                        'answertitle' => $row['answertitle']
                    );

                    $questionExists = true;
                    break;
                }
            }

            // If the question doesn't exist in $questions array, add it
            if (!$questionExists) {
                $questions[] = array(
                    'number_of_attempts' => $row['number_of_attempts'],
                    'id_question' => $row['id_question'], // Optionally, include question ID if needed
                    'id_exam' => $row['id_exam'], // Optionally, include exam ID if needed
                    'examtitle' => $row['examtitle'], // Optionally, include exam title if needed
                    'question' => $row['questiontitle'],

                    'answers' => array(
                        array(
                            'id_answer' => $row['id_answer'],
                            'answertitle' => $row['answertitle']
                        )
                    )
                );

                // Also, add the answer to the $all_answers array for this question
                $all_answers[$row['id_question']][] = array(
                    'id_answer' => $row['id_answer'],
                    'answertitle' => $row['answertitle']
                );
            }
        }

        $data['questions'] = $questions;


        // Now $questions array contains the data in the desired format

        ///////////////////////////////////////// lecture
        $cond_course = ' and id_course = ' . $id_course;
        $usercourse = $this->fct->getAll_cond('user_course', "sort_order", $conda);

        $qrys_lecture = "SELECT user.* FROM 
 user_course 
INNER JOIN 
 user ON user_course.id_user = user.id_user AND user.deleted = 0  AND user_course.deleted = 0
WHERE
 user_course.id_role = 2 AND user.deleted = 0  AND user_course.deleted = 0 
 " . $cond_course . " ";
        $user_lecture = $this->fct->search($qrys_lecture);
        //  $data['alllecture'][] =[];
        if (count($user_lecture)) {
            foreach ($user_lecture as $row) {
                if ($row['name']) {
                    $data['alllecture'][] = $row['name'];
                }
            }
        }
        //////////////////////////////////////// lecture
        //////////////////////////////
        $qryssss = "SELECT 
course.id_course,
ROUND(COUNT(DISTINCT user_content.id_content) / COUNT(DISTINCT content.id_content) * 100) AS completion_percentage
FROM 
    course 
LEFT JOIN 
    content ON content.id_course = course.id_course and content.deleted=0 
LEFT JOIN  
    user_content ON content.id_content = user_content.id_content AND user_content.id_user =" . $id_user . " 
    and user_content.deleted=0
    where course.deleted=0 and course.id_course=$id_course
GROUP BY 
    course.id_course";
        $avgPercentageResults = $this->fct->search($qryssss);
        $avgCoursePercentage = [];
        foreach ($avgPercentageResults as $row) {
            $avgCoursePercentage[$row['id_course']] = $row['completion_percentage'];
        }
        $data['avgcourse'] = $avgCoursePercentage;
        //////////////////////////////
        // $cond=array('id_course'=>$id_course);
        // $data['user_course_rating'] =$this->fct->getAll_cond('user_course_rating',"sort_order",$cond); 
        //////////////////////////////
        // $query = "select user_course_rating.*,user.* from user_course_rating 
        //             JOIN user ON user.id_user = user_course_rating.id_user
        //             WHERE  user_course_rating.active=1 and user_course_rating.id_course = ". $id_course ;
        // $data['user_course_rating']=$this->fct->search($query);



        $query = "select user_course_rating.*,user.image,user.name from user_course_rating 
            JOIN user ON user.id_user = user_course_rating.id_user
            WHERE user_course_rating.id_course = " . $id_course . " and user_course_rating.id_user = " . $id_user;
        $data['count'] = $this->fct->search_count_cond($query);

        $data['my_user_course_rating'] = $this->fct->search($query);
        $data["id_course"] = $id_course;
        /////////////////////////////////////////////////////////////////////////////// start exam details if exist


        $qry = "SELECT DISTINCT
            question.id_question,
            user_exam.*,
            exam.id_exam,
            exam.time,
            exam.number_of_attempts,
            exam.title AS examtitle,
            question.title AS questiontitle,
            answer.title AS answertitle,
            answer.id_answer,
            answer.true,
            CASE 
                WHEN user_exam.id_answer = answer.id_answer AND answer.true = 1 THEN user_exam.status + 1
                ELSE user_exam.status
            END AS answer_true,
            CASE 
                WHEN user_exam.id_answer = answer.id_answer AND answer.true = 0 THEN exam.status + 1
                ELSE exam.status
            END AS answer_false
        FROM
            exam
        INNER JOIN
            question ON question.id_exam = exam.id_exam AND question.deleted = 0
        INNER JOIN
            answer ON answer.id_question = question.id_question AND answer.deleted = 0
        INNER JOIN
            user_course ON user_course.id_course = exam.id_course
        INNER JOIN
            user_exam ON user_exam.id_question = question.id_question AND user_exam.id_user = user_course.id_user
        WHERE
            user_course.id_role = 3
            AND exam.deleted = 0
            AND user_course.deleted = 0
            AND question.deleted = 0
            AND answer.deleted = 0
            AND exam.id_course = $id_course
            AND user_course.id_user = $id_user
            -- AND user_exam.id_exam = $ id_exam
        ORDER BY
            question.sort_order,
            answer.id_answer,
            answer.sort_order";

        $rows = $this->fct->search($qry);

        $all_questions = [];
        $total_answer_true = 0;
        $total_answer_false = 0;
        $id_exam = '';

        foreach ($rows as $row) {
            $question_id = $row['id_question'];
            $id_exam = $row['id_exam'];
            // Initialize the question array if it doesn't exist yet
            if (!isset($all_questions[$question_id])) {
                // $data['exam'] = [
                //     'id_exam' => $row['id_exam'],
                //     'time' => $row['time'],
                //     'number_of_attempts' => $row['number_of_attempts'],
                //     'examtitle' => $row['examtitle'],
                //     'id_user_exam' => $row['id_user_exam'],
                //     'id_user' => $row['id_user'],
                // ];


                // Merge the values from the second assignment
                $data['exam'] = array_merge($data['exam'], [
                    'id_user_exam' => $row['id_user_exam'],
                    'id_user' => $row['id_user'],
                ]);

                $all_questions[$question_id] = [
                    'id_question' => $question_id,
                    'questiontitle' => $row['questiontitle'],
                    'answers' => [],
                ];
            }

            // Add answer details to the question's answers array
            $all_questions[$question_id]['answers'][] = [
                'id_answer' => $row['id_answer'],
                'answertitle' => $row['answertitle'],
                'true' => $row['true'],
                'answer_true' => $row['answer_true'],
                'answer_false' => $row['answer_false'],
            ];

            // Accumulate total counts of correct and incorrect answers
            $total_answer_true += $row['answer_true'];
            $total_answer_false += $row['answer_false'];
        }

        $data['examdetails'] = $all_questions;

        $query = "select exam_history.*,user.image,user.name 
           from exam_history 
            JOIN user ON user.id_user = exam_history.id_user
            WHERE exam_history.id_course = " . $id_course . " and exam_history.id_user = " . $id_user . " 
            order by id_exam_history desc limit 1";
        // echo "<pre>";
        // print_r($data);
        // exit();

        $data['exam_history'] = $this->fct->search($query);
        $data['id_exam'] = $id_exam;
        ///////////////////////////////////////////////////////////////////////////////

        $data['curentpage'] = 'course-details';
        $data["content"] = "course-details";
        $this->load->view('template', $data);
    }



    public function contactus()
    {
        ////////// Start Header  
        $cond = array('admin_id' => 2);
        $data['generalinfo'] = $this->fct->getonerow("admin", $cond);
        $data['menuactive'] = "home";
        $cond = array('active' => 1);
        $data['category'] = $this->fct->getAll_cond('category', "sort_order", $cond);

        $cond = array('top_header' => '1');
        $data['header_news'] = $this->fct->getAll_limit('news', "sort_order", "desc", "5", $cond);
        $cond = array('top_course' => '1', 'active' => 1);
        $data['header_course'] = $this->fct->getAll_limit('course', "sort_order", "desc", "5", $cond);
        $home_page = $this->fct->getAll('home_page', "sort_order");
        $data['home_page'] = [];
        foreach ($home_page as $row) {
            $data['home_page'][$row['id_home_page']] = $row;
        }
        $data['link_footer'] = $this->fct->getAll('link_footer', "sort_order");
        // Initialize an empty associative array
        $category_course = array();
        $cond = array('active' => 1);
        $result = $this->fct->getAll_cond('course', "sort_order", $cond);
        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_course[$idCategory][] = $row;
        }
        $data['category_course'] = $category_course;

        // Added for 3-level menu: group_category -> category -> course
        $cond = array('active' => 1);
        $data['group_category'] = $this->fct->getAll_cond('group_category', "sort_order", $cond);
        $group_category_category = array();
        foreach ($data['category'] as $row) {
            $idSuperCategory = (int) $row['id_group_category'];
            $group_category_category[$idSuperCategory][] = $row;
        }
        $data['group_category_category'] = $group_category_category;

        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;

        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        //////////  End Header


        $data['tab'] = 0;
        $cond = array('id_contact_us' => 1);
        $data['info'] = $this->fct->getonerow("contact_us", $cond);

        $data["content"] = "contactus";
        $this->load->view('template', $data);
    }



    public function contactinfo()
    {
        $this->form_validation->set_rules("name", "name", "trim|required");
        $this->form_validation->set_rules("subject", "subject", "trim|required");
        $this->form_validation->set_rules("email", "email", "trim|email|required");
        $this->form_validation->set_rules("comments", "comments", "trim|required");

        if ($this->form_validation->run() == FALSE) {
            echo "Error ";
        } else {
            $_data["name"] = $this->input->post("name");
            $_data["subject"] = $this->input->post("subject");
            $_data["email"] = $this->input->post("email");
            $_data["comments"] = $this->input->post("comments");

            $_data["created_date"] = date("Y-m-d h:i:s");
            $this->db->insert('contactform', $_data);

            ////////////////////////
            //$baseUrl = base_url();

            // Construct the email message
            $msg = "<div style='color:darkblue'><b>The following user has submitted the contact form</b></div><br>";
            $msg .= "<table border='3' cellspacing='0' cellpadding='5'>
            <tr><td>Subject </td><td>" . htmlspecialchars($_data["subject"]) . "</td></tr>
            <tr><td>Message </td><td>" . htmlspecialchars($_data["comments"]) . "</td></tr>
            <tr><td>Name </td><td>" . htmlspecialchars($_data["name"]) . "</td></tr>
            <tr><td>Email</td><td>" . filter_var($_data["email"], FILTER_SANITIZE_EMAIL) . "</td></tr>
            <tr><td>Date </td><td>" . htmlspecialchars($_data["created_date"]) . "</td></tr>
            </table>";

            // Fetch the admin email
            $condition = array('admin_id' => 2);
            $admin_data = $this->fct->getonecell("admin", "email", $condition);
            $email = $admin_data;

            $toEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
            $subject = htmlspecialchars("Contact Us");

            // Additional headers
            $fromEmail = filter_var($_data["email"], FILTER_SANITIZE_EMAIL);
            $fromName = str_replace(array("
", "
"), '', htmlspecialchars($_data["name"])); // Security: no header injection
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: $fromName <$fromEmail>" . "\r\n";

            if (filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
                if (mail($toEmail, $subject, $msg, $headers)) {
                    $this->session->set_userdata("success_message", "Information was inserted successfully");
                    echo "<div class='titledata'>Thank You for Contacting Us!</div>";
                } else {
                    $this->session->set_userdata("success_message", "Email sending failed.");
                    echo "<div class='titledata'>Email sending failed.</div>";
                }
            } else {
                $this->session->set_userdata("error_message", "Invalid email address.");
                echo "<div class='titledata'>Invalid email address.</div>";
            }

            //////////////////////////////////////////////
        }
    }



    public function newsletter()
    {
        $data = trim($_REQUEST['EMAIL']);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $website = $data;

        $cond = array('email' => $data);
        $newsletter = $this->fct->getAll_cond('newsletter', "sort_order", $cond);
        if (count($newsletter)) {
            echo "Already exist ";
        } else {
            // check if URL address syntax is valid
            if (!filter_var($website, FILTER_VALIDATE_EMAIL)) {
                echo "Error ";
            } else {
                $_data["email"] = $website;
                $_data["created_date"] = date("Y-m-d h:i:s");
                $this->db->insert('newsletter', $_data);

                $this->session->set_userdata("success_message", "Information was inserted successfully");
                echo "<div class='titledata' >Thanks for your contact</div>";
            }
        }
    }

    public function readreact()
    {
        // Security: users list (names + emails) is for admins only
        $login = $this->session->userdata('login');
        if (empty($login['idUser']) || $login['id_init_role'] != 1) {
            show_404();
            return;
        }
        // Set default values for page, pageSize, sortBy, and sortOrder
        $page = isset($_REQUEST['page']) && $_REQUEST['page'] ? max(1, intval($_REQUEST['page'])) : 1;
        $pageSize = isset($_REQUEST['pageSize']) ? max(1, min(500, intval($_REQUEST['pageSize']))) : 10;
        $sortBy = isset($_REQUEST['sortBy']) && $_REQUEST['sortBy'] ? $_REQUEST['sortBy'] : 'name';
        $sortOrder = isset($_REQUEST['sortOrder']) ? $_REQUEST['sortOrder'] : 'asc';
        $filter = isset($_REQUEST['filter']) && $_REQUEST['filter'] ? $_REQUEST['filter'] : '';
        // Security: whitelist ORDER BY values (they cannot be escaped)
        $sortBy = in_array($sortBy, array('name', 'email', 'id_user', 'username'), true) ? $sortBy : 'name';
        $sortOrder = (strtolower($sortOrder) === 'desc') ? 'desc' : 'asc';

        // Prepare the WHERE clause based on the filter parameter
        $cond = "";
        if (!empty($filter)) {
            // Assuming 'name' is the column name for filtering
            $cond = " AND name LIKE '%" . $this->db->escape_str($filter) . "%'";
        }

        // Prepare the ORDER BY clause based on the sortBy and sortOrder parameters
        $orderClause = "ORDER BY $sortBy $sortOrder";

        // Calculate the OFFSET for pagination
        $offset = ($page - 1) * $pageSize;

        // Construct the SQL query
        $sql = "SELECT * FROM user WHERE 1 $cond $orderClause LIMIT $pageSize OFFSET $offset";

        // Execute the query and fetch the results
        $users = $this->fct->search($sql);


        /////////////////////////////
        // $cond = ' '; // Default language ID
        // if (isset($_REQUEST['filter']) && $_REQUEST['filter']) {
        //     $cond = " and name like  '%".$_REQUEST['filter']."%'";
        // }

        // $wehereclause =" SELECT * FROM user WHERE 1 ". $cond ;
        // $users=$this->fct->search($wehereclause);

        // $users = array_merge($users, $users);
        // Prepare data for JSON response
        $data = [];
        foreach ($users as $user) {
            $data[] = array(
                'id_user' => (int) $user['id_user'],
                'name' => $user['name'],
                'email' => strip_tags($user['email'])
            );
        }


        // Construct the SQL query
        $sql = "SELECT * FROM user WHERE 1 ";
        // Execute the query and fetch the results
        $users = $this->fct->search($sql);


        // Prepare JSON response
        $response = array(
            'data' => $data,
            'total' => count($users) // Total count of customers
        );

        // Set the appropriate content type for JSON responses
        header('Content-Type: application/json');

        // Allow requests from a specific origin (replace '*' with the origin you want to allow)
        header('Access-Control-Allow-Origin: *');

        // Output JSON response
        echo json_encode($response);
    }

    public function news($id_news = 0)
    {
        $id_news = (int) $id_news; // Security: numeric id from the URL (used in SQL)
        ////////// Start Header  
        $cond = array('admin_id' => 2);
        $data['generalinfo'] = $this->fct->getonerow("admin", $cond);
        $data['menuactive'] = "home";
        $cond = array('active' => 1);
        $data['category'] = $this->fct->getAll_cond('category', "sort_order", $cond);

        $cond = array('top_header' => '1');
        $data['header_news'] = $this->fct->getAll_limit('news', "sort_order", "desc", "5", $cond);
        $cond = array('top_course' => '1', 'active' => 1);
        $data['header_course'] = $this->fct->getAll_limit('course', "sort_order", "desc", "5", $cond);
        $home_page = $this->fct->getAll('home_page', "sort_order");
        $data['home_page'] = [];
        foreach ($home_page as $row) {
            $data['home_page'][$row['id_home_page']] = $row;
        }
        $data['link_footer'] = $this->fct->getAll('link_footer', "sort_order");
        // Initialize an empty associative array
        $category_course = array();
        $cond = array('active' => 1);
        $result = $this->fct->getAll_cond('course', "sort_order", $cond);
        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_course[$idCategory][] = $row;
        }
        $data['category_course'] = $category_course;

        // Added for 3-level menu: group_category -> category -> course
        $cond = array('active' => 1);
        $data['group_category'] = $this->fct->getAll_cond('group_category', "sort_order", $cond);
        $group_category_category = array();
        foreach ($data['category'] as $row) {
            $idSuperCategory = (int) $row['id_group_category'];
            $group_category_category[$idSuperCategory][] = $row;
        }
        $data['group_category_category'] = $group_category_category;

        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;

        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        //////////  End Header

        $cond = array('id_news' => $id_news);
        $data['news'] = $this->fct->getonerow("news", $cond);



        $data["content"] = "news";
        $this->load->view('template', $data);
    }

    public function chatbot()
    {
        ////////// Start Header  
        $cond = array('admin_id' => 2);
        $data['generalinfo'] = $this->fct->getonerow("admin", $cond);
        $data['menuactive'] = "home";
        $cond = array('active' => 1);
        $data['category'] = $this->fct->getAll_cond('category', "sort_order", $cond);

        $cond = array('top_header' => '1');
        $data['header_news'] = $this->fct->getAll_limit('news', "sort_order", "desc", "5", $cond);
        $cond = array('top_course' => '1', 'active' => 1);
        $data['header_course'] = $this->fct->getAll_limit('course', "sort_order", "desc", "5", $cond);
        $home_page = $this->fct->getAll('home_page', "sort_order");
        $data['home_page'] = [];
        foreach ($home_page as $row) {
            $data['home_page'][$row['id_home_page']] = $row;
        }
        $data['link_footer'] = $this->fct->getAll('link_footer', "sort_order");
        // Initialize an empty associative array
        $category_course = array();
        $cond = array('active' => 1);
        $result = $this->fct->getAll_cond('course', "sort_order", $cond);
        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_course[$idCategory][] = $row;
        }
        $data['category_course'] = $category_course;

        // Added for 3-level menu: group_category -> category -> course
        $cond = array('active' => 1);
        $data['group_category'] = $this->fct->getAll_cond('group_category', "sort_order", $cond);
        $group_category_category = array();
        foreach ($data['category'] as $row) {
            $idSuperCategory = (int) $row['id_group_category'];
            $group_category_category[$idSuperCategory][] = $row;
        }
        $data['group_category_category'] = $group_category_category;

        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;

        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        //////////  End Header


        $data["content"] = "chatbot";
        $this->load->view('template', $data);
    }


    public function videodata($id_course = 0)
    {
        $id_course = (int) $id_course; // Security: numeric id from the URL (used in SQL)
        ////////// Start Header  
        $cond = array('admin_id' => 2);
        $data['generalinfo'] = $this->fct->getonerow("admin", $cond);
        $data['menuactive'] = "home";
        $cond = array('active' => 1);
        $data['category'] = $this->fct->getAll_cond('category', "sort_order", $cond);

        $cond = array('top_header' => '1');
        $data['header_news'] = $this->fct->getAll_limit('news', "sort_order", "desc", "5", $cond);
        $cond = array('top_course' => '1', 'active' => 1);
        $data['header_course'] = $this->fct->getAll_limit('course', "sort_order", "desc", "5", $cond);
        $home_page = $this->fct->getAll('home_page', "sort_order");
        $data['home_page'] = [];
        foreach ($home_page as $row) {
            $data['home_page'][$row['id_home_page']] = $row;
        }
        $data['link_footer'] = $this->fct->getAll('link_footer', "sort_order");
        // Initialize an empty associative array
        $category_course = array();
        $cond = array('active' => 1);
        $result = $this->fct->getAll_cond('course', "sort_order", $cond);
        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_course[$idCategory][] = $row;
        }
        $data['category_course'] = $category_course;

        // Added for 3-level menu: group_category -> category -> course
        $cond = array('active' => 1);
        $data['group_category'] = $this->fct->getAll_cond('group_category', "sort_order", $cond);
        $group_category_category = array();
        foreach ($data['category'] as $row) {
            $idSuperCategory = (int) $row['id_group_category'];
            $group_category_category[$idSuperCategory][] = $row;
        }
        $data['group_category_category'] = $group_category_category;

        // if(!$this->session->userdata('login')) {
        //     echo "<script>alert('You need to log in!');</script>";
        //     echo '<script>window.location.href="' . base_url() . '";</script>';
        //     exit; 
        // }
        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;

        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        //////////  End Header

        $qry = " SELECT 
course.title AS coursetitle,
course.description,
course.hours,
course.price,
course.schedule,
category.title AS categtitle,
course.summary,
course.requirements,
client.title AS clienttitle,
client.email AS clientemail,
AVG(user_course_rating.rating) AS avg_course_rating
FROM 
course
JOIN 
category ON category.id_category = course.id_category
LEFT JOIN 
shopping_cart ON shopping_cart.id_course = course.id_course
LEFT JOIN 
client ON shopping_cart.id_client = client.id_client
LEFT JOIN 
user_course_rating ON course.id_course = user_course_rating.id_course
WHERE   
course.deleted = 0 
AND category.deleted = 0 
AND course.active = 1
AND IFNULL(client.deleted, 0) = 0
AND course.id_course =" . $id_course;


        $data['coursedetail'] = $this->fct->search($qry);

        if (empty($data['coursedetail']) || count($data['coursedetail']) == 0) {
            redirect(base_url());
            exit;
        }

        $query = "select content.file,content.title,content.description,content.link,content.hours,content.link
    from course,content
    where course.id_course = content.id_course
     and course.deleted = 0
     and content.deleted = 0
      and course.id_course =" . $id_course;
        $data['coursecontent'] = $this->fct->search($query);


        $qry = "select sum(content.hours) sumhrs, count(*) contentnbr 
    from course,content
        where course.id_course = content.id_course
         and course.deleted = 0
         and content.deleted = 0
          and course.id_course=" . $id_course;
        $qry = $qry . "  group by course.id_course";
        $data['coursesummary'] = $this->fct->search($qry);


        $cond = array('id_course' => $id_course);

        $data['video_course'] = $this->fct->getAll_cond('video', "sort_order", $cond);


        $data["content"] = "video";
        $this->load->view('template', $data);
    }

    public function teacherprofile($id_user_teacher)
    {
        $id_user_teacher = (int) $id_user_teacher; // Security: numeric id from the URL (used in SQL)
        ////////// Start Header  
        $cond = array('admin_id' => 2);
        $data['generalinfo'] = $this->fct->getonerow("admin", $cond);
        $data['menuactive'] = "home";
        $cond = array('active' => 1);
        $data['category'] = $this->fct->getAll_cond('category', "sort_order", $cond);

        $cond = array('top_header' => '1');
        $data['header_news'] = $this->fct->getAll_limit('news', "sort_order", "desc", "5", $cond);
        $cond = array('top_course' => '1', 'active' => 1);
        $data['header_course'] = $this->fct->getAll_limit('course', "sort_order", "desc", "5", $cond);
        $home_page = $this->fct->getAll('home_page', "sort_order");
        $data['home_page'] = [];
        foreach ($home_page as $row) {
            $data['home_page'][$row['id_home_page']] = $row;
        }
        $data['link_footer'] = $this->fct->getAll('link_footer', "sort_order");
        // Initialize an empty associative array
        $category_course = array();
        $cond = array('active' => 1);
        $result = $this->fct->getAll_cond('course', "sort_order", $cond);
        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_course[$idCategory][] = $row;
        }
        $data['category_course'] = $category_course;

        // Added for 3-level menu: group_category -> category -> course
        $cond = array('active' => 1);
        $data['group_category'] = $this->fct->getAll_cond('group_category', "sort_order", $cond);
        $group_category_category = array();
        foreach ($data['category'] as $row) {
            $idSuperCategory = (int) $row['id_group_category'];
            $group_category_category[$idSuperCategory][] = $row;
        }
        $data['group_category_category'] = $group_category_category;

        // if(!$this->session->userdata('login')) {
        //     echo "<script>alert('You need to log in!');</script>";
        //     echo '<script>window.location.href="' . base_url() . '";</script>';
        //     exit; 
        // }
        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;

        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        //////////  End Header


        $cond = array('id_user' => $id_user_teacher);
        $data['lecturerinfo'] = $this->fct->getonerow("user", $cond);


        $query = "select course.id_course,course.title,course.id_category,course.image,course.summary
        FROM user_course,course
        WHERE   user_course.id_course = course.id_course and course.deleted = 0 and course.active = 1
        and user_course.id_user =" . $id_user_teacher;
        $data['courses'] = $this->fct->search($query);


        $data["content"] = "teacher-profile";
        $this->load->view('template', $data);
    }


    public function my_courses()
    {
        ////////// Start Header  
        $cond = array('admin_id' => 2);
        $data['generalinfo'] = $this->fct->getonerow("admin", $cond);
        $data['menuactive'] = "home";
        $cond = array('active' => 1);
        $data['category'] = $this->fct->getAll_cond('category', "sort_order", $cond);

        $cond = array('top_header' => '1');
        $data['header_news'] = $this->fct->getAll_limit('news', "sort_order", "desc", "5", $cond);
        $cond = array('top_course' => '1', 'active' => 1);
        $data['header_course'] = $this->fct->getAll_limit('course', "sort_order", "desc", "5", $cond);
        $home_page = $this->fct->getAll('home_page', "sort_order");
        $data['home_page'] = [];
        foreach ($home_page as $row) {
            $data['home_page'][$row['id_home_page']] = $row;
        }
        $data['link_footer'] = $this->fct->getAll('link_footer', "sort_order");
        // Initialize an empty associative array
        $category_course = array();
        $cond = array('active' => 1);
        $result = $this->fct->getAll_cond('course', "sort_order", $cond);
        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_course[$idCategory][] = $row;
        }
        $data['category_course'] = $category_course;

        // Added for 3-level menu: group_category -> category -> course
        $cond = array('active' => 1);
        $data['group_category'] = $this->fct->getAll_cond('group_category', "sort_order", $cond);
        $group_category_category = array();
        foreach ($data['category'] as $row) {
            $idSuperCategory = (int) $row['id_group_category'];
            $group_category_category[$idSuperCategory][] = $row;
        }
        $data['group_category_category'] = $group_category_category;

        if (!$this->session->userdata('login')) {
            echo "<script>alert('You need to log in!');</script>";
            echo '<script>window.location.href="' . base_url() . '";</script>';
            exit; // Make sure to exit to prevent further execution
        }
        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;


        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        //////////  End Header


        $qry = "select course.id_course,course.image,course.title coursetitle,course.summary
            from user_course,course,role,category
            where course.id_course = user_course.id_course
            and course.id_category= category.id_category
            and user_course.id_role=role.id_role
            and course.deleted = 0
            and user_course.deleted = 0
            and role.deleted = 0
            and role.id_role = 2
            and user_course.id_user = " . $id_user;
        $data['mycourses'] = $this->fct->search($qry);

        $cond = array('id_user' => $userId['idUser']);
        $data['user'] = $this->fct->getonerow('user', $cond);

        $data["content"] = "mycourses";
        $this->load->view('template', $data);
    }




    public function save_review()
    {
        // echo "<pre>";
        // print_r($_REQUEST);
        // exit();
        $feedback = $_REQUEST['reviews'];
        $id_course = (int) $_REQUEST['id_course']; // Security: concatenated into SQL below
        $userId = $this->session->userdata('login');
        $id_user = (int) $userId['idUser'];
        if ($id_user && $id_course) {
            $cond = array('id_course' => $id_course, 'id_user' => $id_user);
            $user_course_rating = $this->fct->getAll_cond('user_course_rating', "sort_order", $cond);
            $msg = '';


            // email  student send feedback
            $cond = array('id_user' => $id_user);
            $user = $this->fct->getonerow("user", $cond);

            // to lecture this course //lecture 2
            $cond_course = ' and course.id_course = ' . $id_course;
            $qrys_lecture = "SELECT user.*,course.* FROM 
user 
INNER JOIN 
user_course ON user_course.id_user = user.id_user AND user.deleted = 0  AND user_course.deleted = 0
INNER JOIN 
course ON user_course.id_course = course.id_course AND course.deleted = 0 
WHERE
(user_course.id_role = 2 or user_course.id_role = 1 )  " . $cond_course . " ";
            $all_user_lecture = $this->fct->search($qrys_lecture);
            // $id_user=$userId['idUser'];
            if (count($user_course_rating)) {

                if ($user_course_rating['0']['id_user_course_rating']) {
                    $_data["updated_date"] = date("Y-m-d h:i:s");
                    $this->db->where("id_course", $id_course);
                    $this->db->where("id_user", $id_user);
                    $_data["feedback"] = $feedback;
                    $this->db->update('user_course_rating', $_data);
                    // echo  $this->db->last_query();
                }

                // if($user_course_rating['0']["active"]==0)
                //   $msg= "waiting to activate from Admin...";
                // else if($user_course_rating['0']["active"]==1)
                //    $msg= "Data already exist...";
            } else {
                $_data["created_date"] = date("Y-m-d h:i:s");
                $_data["id_course"] = $id_course;
                $_data["id_user"] = $id_user;
                $_data["active"] = 1;
                $_data["feedback"] = $feedback;
                $Id = $this->db->insert('user_course_rating', $_data);
                //  $msg= "waiting to activate from Admin...";	
            }

            foreach ($all_user_lecture as $user_lecture) {
                $from = $user['email'];
                $to = $user_lecture['email'];
                /////////////////////////////////////
                // $from = "ayman_serhan@hotmail.com"; 
                // $to = "aymanserhan82@gmail.com"; 

                // Security: names go into mail headers - strip line breaks (header injection)
                $from_user = str_replace(array("
", "
"), '', $user['name']);
                $to_user = str_replace(array("
", "
"), '', $user_lecture['name']);
                /////////////////////////////////////
                $subject = 'Feedback';

                $body1 = "Dear " . $to_user . ",";
                $body2 = "Please see below the feedback on course <span style='color:red'>" . $user_lecture['title'] . "</span> provided by the student  <span style='color:red'>" . $from_user . "</span>:<br><br>";

                $message = '
    <html>
    <head>
        <title>Feedback</title>
    </head>
    <body>
        <p>' . $body1 . '</p>
        <p>' . $body2 . '</p>
        ' . nl2br(htmlspecialchars($feedback, ENT_QUOTES, 'UTF-8')) . '
    </body>
    </html>
    ';

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                // Using the $from variable in the headers
                $headers .= 'From: ' . $from_user . ' <' . $from . '>' . "\r\n";
                $headers .= 'Reply-To: ' . $to_user . ' <' . $to . '>' . "\r\n";

                if (mail($to, $subject, $message, $headers)) {
                    $msgmsg = "<div class='msg-feedback'>Email sent successfully.</div>";
                } else {
                    $msgmsg = "<div class='msg-feedback'>Failed to send email.</div>";
                }
                /////////////////////////////////////////////////
            }

            echo $msgmsg;
        }
        //else $msg= "login first...";


        // // Prepare JSON response
        // $response = array(
        //     'data' => $msg,      
        //  );
        // // Set the appropriate content type for JSON responses
        // header('Content-Type: application/json');   
        // // Allow requests from a specific origin (replace '*' with the origin you want to allow)
        // header('Access-Control-Allow-Origin: *');
        // // Output JSON response
        // echo json_encode($response);

        $query = "select user_course_rating.*,user.image,user.name from user_course_rating 
JOIN user ON user.id_user = user_course_rating.id_user
WHERE user_course_rating.id_course = " . $id_course . " and user_course_rating.id_user = " . $id_user;
        $data['my_user_course_rating'] = $this->fct->search($query);

        $this->load->view('feedback', $data);

        // echo "<pre>";
        // print_r($user_course_rating);
        // exit();

    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
