<?php
$page_title = "Contact";
$page_description = "Get in touch with the Nexi Hub team - Build. Automate. Scale. We'd love to hear from you.";

// Handle form submission
$message = '';
$messageType = '';

if ($_POST && isset($_POST['name'], $_POST['email'], $_POST['department'], $_POST['message'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $department = $_POST['department'];
    $subject = trim($_POST['subject']);
    $userMessage = trim($_POST['message']);
    $phone = trim($_POST['phone']);
    $company = trim($_POST['company']);
    
    // Define department emails
    $departmentEmails = [
        'general' => 'info@nexihub.uk',
        'business' => 'business@nexihub.uk',
        'careers' => 'careers@nexihub.uk',
        'support' => 'support@nexihub.uk',
        'press' => 'press@nexihub.uk',
        'security' => 'security@nexihub.uk'
    ];
    
    $departmentNames = [
        'general' => 'General Inquiries',
        'business' => 'Business & Partnerships',
        'careers' => 'Careers',
        'support' => 'Technical Support',
        'press' => 'Press & Media',
        'security' => 'Security'
    ];
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && isset($departmentEmails[$department])) {
        $to = $departmentEmails[$department];
        $emailSubject = $subject ? $subject : "Contact Form - " . $departmentNames[$department];
        
        $emailBody = "New contact form submission\n\n";
        $emailBody .= "Department: " . $departmentNames[$department] . "\n";
        $emailBody .= "Name: " . $name . "\n";
        $emailBody .= "Email: " . $email . "\n";
        if ($phone) $emailBody .= "Phone: " . $phone . "\n";
        if ($company) $emailBody .= "Company: " . $company . "\n";
        $emailBody .= "Subject: " . $emailSubject . "\n\n";
        $emailBody .= "Message:\n" . $userMessage . "\n\n";
        $emailBody .= "---\nSent from nexihub.uk contact form\n";
        
        $headers = "From: noreply@nexihub.uk\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        if (mail($to, $emailSubject, $emailBody, $headers)) {
            $message = "Thank you for contacting us! We'll get back to you within 24 hours.";
            $messageType = "success";
        } else {
            $message = "Sorry, there was an error sending your message. Please try again or email us directly.";
            $messageType = "error";
        }
    } else {
        $message = "Please check that all required fields are filled correctly.";
        $messageType = "error";
    }
}

include 'includes/header.php';
?>

<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Get in Touch</h1>
            <p class="hero-subtitle">Build. Automate. Scale.</p>
            <p class="hero-description">
                Whether you have questions about our platforms, want to explore partnership opportunities, 
                or just want to say hello, we're here to help.
            </p>
        </div>
    </div>
</section>

<?php if ($message): ?>
<section class="content-section">
    <div class="container">
        <div class="alert alert-<?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="content-section">
    <div class="container">
        <div class="contact-grid">
            <div class="contact-form-section">
                <h2>Send us a message</h2>
                <form class="contact-form" method="POST" action="/contact">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="company">Company/Organization</label>
                            <input type="text" id="company" name="company" value="<?php echo isset($_POST['company']) ? htmlspecialchars($_POST['company']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="department">Department *</label>
                        <select id="department" name="department" required>
                            <option value="">Select a department...</option>
                            <option value="general" <?php echo (isset($_POST['department']) && $_POST['department'] == 'general') ? 'selected' : ''; ?>>General Inquiries</option>
                            <option value="business" <?php echo (isset($_POST['department']) && $_POST['department'] == 'business') ? 'selected' : ''; ?>>Business & Partnerships</option>
                            <option value="careers" <?php echo (isset($_POST['department']) && $_POST['department'] == 'careers') ? 'selected' : ''; ?>>Careers</option>
                            <option value="support" <?php echo (isset($_POST['department']) && $_POST['department'] == 'support') ? 'selected' : ''; ?>>Technical Support</option>
                            <option value="press" <?php echo (isset($_POST['department']) && $_POST['department'] == 'press') ? 'selected' : ''; ?>>Press & Media</option>
                            <option value="security" <?php echo (isset($_POST['department']) && $_POST['department'] == 'security') ? 'selected' : ''; ?>>Security</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" rows="6" required placeholder="Tell us how we can help..."><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
            
            <div class="contact-info-section">
                <h2>Contact Information</h2>
                
                <div class="contact-departments">
                    <div class="department-card">
                        <div class="department-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20,8L12,13L4,8V6L12,11L20,6M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4Z"/>
                            </svg>
                        </div>
                        <h3>General Inquiries</h3>
                        <p>For general questions about Nexi Hub or our platforms.</p>
                        <a href="mailto:info@nexihub.uk">info@nexihub.uk</a>
                    </div>

                    <div class="department-card">
                        <div class="department-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12,5.5A3.5,3.5 0 0,1 15.5,9A3.5,3.5 0 0,1 12,12.5A3.5,3.5 0 0,1 8.5,9A3.5,3.5 0 0,1 12,5.5M5,8C5.56,8 6.08,8.15 6.53,8.42C6.38,9.85 6.8,11.27 7.66,12.38C7.16,13.34 6.16,14 5,14A3,3 0 0,1 2,11A3,3 0 0,1 5,8M19,8A3,3 0 0,1 22,11A3,3 0 0,1 19,14C17.84,14 16.84,13.34 16.34,12.38C17.2,11.27 17.62,9.85 17.47,8.42C17.92,8.15 18.44,8 19,8M5.5,18.25C5.5,16.18 8.41,14.5 12,14.5C15.59,14.5 18.5,16.18 18.5,18.25V20H5.5V18.25M0,20V18.5C0,17.11 1.89,15.94 4.45,15.6C3.86,16.28 3.5,17.22 3.5,18.25V20H0M24,20H20.5V18.25C20.5,17.22 20.14,16.28 19.55,15.6C22.11,15.94 24,17.11 24,18.5V20Z"/>
                            </svg>
                        </div>
                        <h3>Business & Partnerships</h3>
                        <p>Interested in partnering with us or have a business proposal?</p>
                        <a href="mailto:business@nexihub.uk">business@nexihub.uk</a>
                    </div>

                    <div class="department-card">
                        <div class="department-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z"/>
                            </svg>
                        </div>
                        <h3>Technical Support</h3>
                        <p>Need help with one of our platforms? Our support team is here to help.</p>
                        <a href="mailto:support@nexihub.uk">support@nexihub.uk</a>
                    </div>
                </div>
                
                <div class="response-info">
                    <h3>Response Times</h3>
                    <ul>
                        <li><strong>General & Business:</strong> Within 24 hours</li>
                        <li><strong>Technical Support:</strong> Within 12 hours</li>
                        <li><strong>Security Issues:</strong> Within 4 hours</li>
                        <li><strong>Press Inquiries:</strong> Within 48 hours</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <span class="stat-number">&lt;24h</span>
                <span class="stat-label">Response Time</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">24/7</span>
                <span class="stat-label">Support Available</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">100%</span>
                <span class="stat-label">Response Rate</span>
            </div>
        </div>
    </div>
</section>

<section class="content-section">
    <div class="container">
        <h2 class="section-title">Quick Links</h2>
        <div class="content-grid">
            <div class="content-text">
                <h3>Our Platforms</h3>
                <p>Need help with a specific platform? Visit their dedicated support pages:</p>
                <ul style="color: var(--text-secondary); line-height: 1.8; margin-top: 1rem;">
                    <li><a href="https://nexiweb.uk" target="_blank" style="color: var(--primary-color);">Nexi Web Support</a></li>
                    <li><a href="https://nexibot.uk" target="_blank" style="color: var(--primary-color);">Nexi Bot Support</a></li>
                    <li><a href="https://nexipulse.uk" target="_blank" style="color: var(--primary-color);">Nexi Pulse Support</a></li>
                </ul>
            </div>
            <div class="content-text">
                <h3>Other Resources</h3>
                <p>Looking for something specific? These links might help:</p>
                <ul style="color: var(--text-secondary); line-height: 1.8; margin-top: 1rem;">
                    <li><a href="/about" style="color: var(--primary-color);">About Nexi Hub</a></li>
                    <li><a href="/careers" style="color: var(--primary-color);">Career Opportunities</a></li>
                    <li><a href="/legal" style="color: var(--primary-color);">Legal Information</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
