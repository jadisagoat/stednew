<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Downloader extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('fpdf');
        $this->load->library('fpdi');
    }



    public function index()
    {


        // User and course IDs
        $userId =   $this->session->userdata('login');
        $id_user = 1; //$userId['idUser'];
        $id_course = 25;

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
        $fontSize = 25; // Font size in points
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

        // Debug: Print the size of the text
        // echo "Text width: " . $textWidth . " pixels\n";
        // echo "Text height: " . $textHeight . " pixels\n";
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
        // imagestring($image, $fontSize, $x, $y, $dateincell, $textColor2);


        $name_of_certificate = 'certificate_' . $id_course . '_' . $id_user . '_' . date('d-m-Y') . '.png';
        // Save the image to a file
        $pngPath = FCPATH . 'uploads/exam_history/' . $name_of_certificate;
        if (!imagepng($image, $pngPath)) {
            die('Error saving the image.');
        }
        //echo "succes";

        // Free up memory
        imagedestroy($image);
        //echo "<br>";
        //echo  $pngPath;
        //echo "<br>";
        echo '<img src="' . base_url() . 'uploads/exam_history/' . $name_of_certificate . '" />';
        // Output the image for download
        // header('Content-Type: image/png');
        // header('Content-Disposition: attachment; filename="' . basename($pngPath) . '"');
        // readfile($pngPath);
        // exit();


    }
}
