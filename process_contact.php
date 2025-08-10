<?php
// Contact form processing script

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get form data
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $enquiry_type = $_POST['enquiry'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($enquiry_type) || empty($message)) {
        $response = array(
            'success' => false,
            'message' => 'Please fill in all required fields.'
        );
        echo json_encode($response);
        exit;
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = array(
            'success' => false,
            'message' => 'Please enter a valid email address.'
        );
        echo json_encode($response);
        exit;
    }
    
    // Email configuration
    $to = "info@preonicenergy.co.in"; // Your email address
    $subject = "New Enquiry from Preonic Website - " . $enquiry_type;
    
    // Create email content
    $email_content = "
    <html>
    <head>
        <title>New Enquiry from Preonic Website</title>
    </head>
    <body>
        <h2>New Enquiry Received</h2>
        <table style='border-collapse: collapse; width: 100%;'>
            <tr>
                <td style='padding: 10px; border: 1px solid #ddd; font-weight: bold;'>Name:</td>
                <td style='padding: 10px; border: 1px solid #ddd;'>" . htmlspecialchars($name) . "</td>
            </tr>
            <tr>
                <td style='padding: 10px; border: 1px solid #ddd; font-weight: bold;'>Email:</td>
                <td style='padding: 10px; border: 1px solid #ddd;'>" . htmlspecialchars($email) . "</td>
            </tr>
            <tr>
                <td style='padding: 10px; border: 1px solid #ddd; font-weight: bold;'>Enquiry Type:</td>
                <td style='padding: 10px; border: 1px solid #ddd;'>" . htmlspecialchars($enquiry_type) . "</td>
            </tr>
            <tr>
                <td style='padding: 10px; border: 1px solid #ddd; font-weight: bold;'>Message:</td>
                <td style='padding: 10px; border: 1px solid #ddd;'>" . nl2br(htmlspecialchars($message)) . "</td>
            </tr>
        </table>
        <br>
        <p><strong>Submitted on:</strong> " . date('Y-m-d H:i:s') . "</p>
    </body>
    </html>
    ";
    
    // Email headers
    $headers = array(
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: Preonic Website <noreply@preonicenergy.co.in>',
        'Reply-To: ' . $email,
        'X-Mailer: PHP/' . phpversion()
    );
    
    // Send email
    $mail_sent = mail($to, $subject, $email_content, implode("\r\n", $headers));
    
    if ($mail_sent) {
        // Send confirmation email to user
        $user_subject = "Thank you for your enquiry - Preonic Energy";
        $user_message = "
        <html>
        <head>
            <title>Thank you for your enquiry</title>
        </head>
        <body>
            <h2>Thank you for contacting Preonic Energy!</h2>
            <p>Dear " . htmlspecialchars($name) . ",</p>
            <p>We have received your enquiry regarding <strong>" . htmlspecialchars($enquiry_type) . "</strong>.</p>
            <p>Our team will review your message and get back to you within 24-48 hours.</p>
            <br>
            <p><strong>Your enquiry details:</strong></p>
            <p><strong>Enquiry Type:</strong> " . htmlspecialchars($enquiry_type) . "</p>
            <p><strong>Message:</strong> " . htmlspecialchars($message) . "</p>
            <br>
            <p>If you have any urgent questions, please feel free to call us at: <strong>044 – 4552 4239</strong></p>
            <br>
            <p>Best regards,<br>
            <strong>Preonic Energy System Private Limited</strong></p>
        </body>
        </html>
        ";
        
        $user_headers = array(
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: Preonic Energy <info@preonicenergy.co.in>',
            'X-Mailer: PHP/' . phpversion()
        );
        
        mail($email, $user_subject, $user_message, implode("\r\n", $user_headers));
        
        $response = array(
            'success' => true,
            'message' => 'Thank you! Your enquiry has been sent successfully. We will get back to you soon.'
        );
    } else {
        $response = array(
            'success' => false,
            'message' => 'Sorry, there was an error sending your enquiry. Please try again or contact us directly.'
        );
    }
    
    echo json_encode($response);
    exit;
}

// If accessed directly without POST request
header("Location: preoniccu.html");
exit;
?>
