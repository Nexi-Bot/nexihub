<?php
$page_title = "Careers";
$page_description = "Join the Nexi Hub team - Build. Automate. Scale. Help us build the future of digital tools.";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_application'])) {
    // Prepare webhook data
    $position = $_POST['position'] ?? '';
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $country = $_POST['country'] ?? '';
    $dob = $_POST['date_of_birth'] ?? '';
    $discordUsername = $_POST['discord_username'] ?? '';
    $discordId = $_POST['discord_id'] ?? '';
    $experience = $_POST['experience'] ?? '';
    $motivation = $_POST['motivation'] ?? '';
    $availability = $_POST['availability'] ?? '';
    $additionalInfo = $_POST['additional_info'] ?? '';
    $contractAgreement = isset($_POST['contract_agreement']) ? 'Yes' : 'No';
    $emailAgreement = isset($_POST['email_agreement']) ? 'Yes' : 'No';
    
    // Create Discord embed
    $embed = [
        'title' => 'New Job Application - ' . $position,
        'color' => hexdec('e64f21'), // Orange color
        'timestamp' => date('c'),
        'fields' => [
            [
                'name' => 'Personal Information',
                'value' => "**Name:** {$firstName} {$lastName}\n**Email:** {$email}\n**Country:** {$country}\n**Date of Birth:** {$dob}",
                'inline' => false
            ],
            [
                'name' => 'Discord Information',
                'value' => "**Username:** {$discordUsername}\n**ID:** {$discordId}",
                'inline' => false
            ],
            [
                'name' => 'Experience & Skills',
                'value' => substr($experience, 0, 1000) . (strlen($experience) > 1000 ? '...' : ''),
                'inline' => false
            ],
            [
                'name' => 'Motivation',
                'value' => substr($motivation, 0, 1000) . (strlen($motivation) > 1000 ? '...' : ''),
                'inline' => false
            ],
            [
                'name' => 'Availability',
                'value' => $availability,
                'inline' => true
            ],
            [
                'name' => 'Agreements',
                'value' => "**Contract & NDA:** {$contractAgreement}\n**Nexi Hub Email:** {$emailAgreement}",
                'inline' => true
            ]
        ],
        'footer' => [
            'text' => 'Nexi Hub Careers • nexihub.uk'
        ]
    ];
    
    if (!empty($additionalInfo)) {
        $embed['fields'][] = [
            'name' => 'Additional Information',
            'value' => substr($additionalInfo, 0, 1000) . (strlen($additionalInfo) > 1000 ? '...' : ''),
            'inline' => false
        ];
    }
    
    $webhookData = [
        'content' => "**New Application Received**\nPosition: **{$position}**",
        'embeds' => [$embed]
    ];
    
    // Send webhook
    $webhookUrl = 'https://discord.com/api/webhooks/1393997390112886855/8vCEMFEskYWcN9S5tNCFbOI7q4XTX4nXvzgM0CDoq-eufAFXWwrhFpURSXG7B0lriN_L';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $webhookUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($webhookData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 204) {
        $submitSuccess = true;
    } else {
        $submitError = true;
    }
}

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="careers-hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Shape the Future with Us</h1>
            <p class="hero-subtitle">Build. Automate. Scale.</p>
            <p class="hero-description">
                Join a team of innovators, creators, and visionaries building the next generation of digital tools.
                At Nexi Hub, your work directly impacts millions of users worldwide.
            </p>
            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-number">100%</span>
                    <span class="stat-label">Remote-First</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">11</span>
                    <span class="stat-label">Open Positions</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">Global</span>
                    <span class="stat-label">Opportunities</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Success/Error Messages -->
<?php if (isset($submitSuccess)): ?>
<section class="message-section">
    <div class="container">
        <div class="success-message">
            <div class="message-icon success-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 6L9 17l-5-5"/>
                </svg>
            </div>
            <h2>Application Submitted Successfully!</h2>
            <p>Thank you for your interest in joining Nexi Hub. Our team will review your application and get back to you within 2-3 business days.</p>
            <a href="/careers" class="btn btn-primary">Submit Another Application</a>
        </div>
    </div>
</section>
<?php elseif (isset($submitError)): ?>
<section class="message-section">
    <div class="container">
        <div class="error-message">
            <div class="message-icon error-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </div>
            <h2>Submission Error</h2>
            <p>There was an issue submitting your application. Please try again or contact us directly.</p>
            <a href="/contact" class="btn btn-primary">Contact Support</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Why Join Us Section -->
<section class="why-join-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Why Join Nexi Hub?</h2>
            <p class="section-subtitle">
                We're building something extraordinary, and we want you to be part of it
            </p>
        </div>
        
        <div class="benefits-grid">
            <div class="benefit-card">
                <div class="benefit-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                </div>
                <h3>Remote-First Culture</h3>
                <p>Work from anywhere in the world. We believe talent isn't limited by geography.</p>
            </div>
            
            <div class="benefit-card">
                <div class="benefit-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
                <h3>Voluntary Opportunity</h3>
                <p>All positions are voluntary roles that offer valuable experience, skill development, and networking opportunities.</p>
            </div>
            
            <div class="benefit-card">
                <div class="benefit-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 0 0 6.001 6M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 0 0 6.001 6M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16l3-9m-3 9l-3-9"/>
                    </svg>
                </div>
                <h3>Growth Opportunities</h3>
                <p>Continuous learning, skill development, and clear career progression paths.</p>
            </div>
            
            <div class="benefit-card">
                <div class="benefit-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                    </svg>
                </div>
                <h3>Cutting-Edge Projects</h3>
                <p>Work on innovative platforms that impact millions of users worldwide.</p>
            </div>
            
            <div class="benefit-card">
                <div class="benefit-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <h3>Amazing Team</h3>
                <p>Collaborate with passionate, talented people who love what they do.</p>
            </div>
            
            <div class="benefit-card">
                <div class="benefit-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 3v18h18"/>
                        <path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"/>
                    </svg>
                </div>
                <h3>Impact & Scale</h3>
                <p>Your work directly contributes to products used by millions globally.</p>
            </div>
        </div>
    </div>
</section>

<!-- Open Positions Section -->
<section class="positions-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Open Positions</h2>
            <p class="section-subtitle">
                Find your perfect role and help us build the future of digital tools
            </p>
            <div class="volunteer-notice">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
                <p><strong>Please Note:</strong> All positions at Nexi Hub are <strong>voluntary roles</strong>. While unpaid, these opportunities offer valuable experience, skill development, professional networking, and the chance to contribute to innovative projects that impact users worldwide.</p>
            </div>
        </div>
        
        <!-- Regional Leadership -->
        <div class="position-category">
            <h3 class="category-title">Regional Leadership</h3>
            <div class="positions-grid">
                <div class="position-card" data-position="Regional Director - North America">
                    <div class="position-header">
                        <h4 class="position-title">Regional Director - North America</h4>
                        <span class="position-type">Leadership</span>
                    </div>
                    <div class="position-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        Remote (US, Canada, Mexico)
                    </div>
                    <p class="position-summary">Lead market expansion and operations across North America. Drive growth strategies and establish key partnerships.</p>
                    <button class="apply-btn">Apply Now</button>
                </div>
                
                <div class="position-card" data-position="Regional Director - Europe, Middle East & Africa">
                    <div class="position-header">
                        <h4 class="position-title">Regional Director - EMEA</h4>
                        <span class="position-type">Leadership</span>
                    </div>
                    <div class="position-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        Remote (Europe, Middle East, Africa)
                    </div>
                    <p class="position-summary">Oversee presence across European countries, Middle Eastern nations, and African continent.</p>
                    <button class="apply-btn">Apply Now</button>
                </div>
                
                <div class="position-card" data-position="Regional Director - Asia-Pacific">
                    <div class="position-header">
                        <h4 class="position-title">Regional Director - Asia-Pacific</h4>
                        <span class="position-type">Leadership</span>
                    </div>
                    <div class="position-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        Remote (APAC Region)
                    </div>
                    <p class="position-summary">Manage operations across East Asia, Southeast Asia, Australia, New Zealand, and Pacific Islands.</p>
                    <button class="apply-btn">Apply Now</button>
                </div>
                
                <div class="position-card" data-position="Regional Director - Latin America">
                    <div class="position-header">
                        <h4 class="position-title">Regional Director - Latin America</h4>
                        <span class="position-type">Leadership</span>
                    </div>
                    <div class="position-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        Remote (South America, Central America, Caribbean)
                    </div>
                    <p class="position-summary">Lead expansion across Latin American markets with focus on localization and emerging strategies.</p>
                    <button class="apply-btn">Apply Now</button>
                </div>
            </div>
        </div>
        
        <!-- Corporate Functions -->
        <div class="position-category">
            <h3 class="category-title">Corporate Functions</h3>
            <div class="positions-grid">
                <div class="position-card" data-position="Head of Human Resources">
                    <div class="position-header">
                        <h4 class="position-title">Head of Human Resources</h4>
                        <span class="position-type">Management</span>
                    </div>
                    <div class="position-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        Remote
                    </div>
                    <p class="position-summary">Strategic HR leadership focused on talent management and exceptional employee experience.</p>
                    <button class="apply-btn">Apply Now</button>
                </div>
                
                <div class="position-card" data-position="Talent Acquisition Manager">
                    <div class="position-header">
                        <h4 class="position-title">Talent Acquisition Manager</h4>
                        <span class="position-type">Management</span>
                    </div>
                    <div class="position-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        Remote
                    </div>
                    <p class="position-summary">Lead recruitment efforts to attract top talent worldwide and build diverse teams.</p>
                    <button class="apply-btn">Apply Now</button>
                </div>
                
                <div class="position-card" data-position="Learning & Development Manager">
                    <div class="position-header">
                        <h4 class="position-title">Learning & Development Manager</h4>
                        <span class="position-type">Management</span>
                    </div>
                    <div class="position-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        Remote
                    </div>
                    <p class="position-summary">Design comprehensive learning programs that enhance skills and foster career growth.</p>
                    <button class="apply-btn">Apply Now</button>
                </div>
                
                <div class="position-card" data-position="Internal Communications Manager">
                    <div class="position-header">
                        <h4 class="position-title">Internal Communications Manager</h4>
                        <span class="position-type">Management</span>
                    </div>
                    <div class="position-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        Remote
                    </div>
                    <p class="position-summary">Ensure clear communication across teams and build engagement strategies for global workforce.</p>
                    <button class="apply-btn">Apply Now</button>
                </div>
                
                <div class="position-card" data-position="Corporate Strategy Manager">
                    <div class="position-header">
                        <h4 class="position-title">Corporate Strategy Manager</h4>
                        <span class="position-type">Strategy</span>
                    </div>
                    <div class="position-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        Remote
                    </div>
                    <p class="position-summary">Drive strategic initiatives, market analysis, and long-term planning for competitive positioning.</p>
                    <button class="apply-btn">Apply Now</button>
                </div>
                
                <div class="position-card" data-position="Compliance Manager">
                    <div class="position-header">
                        <h4 class="position-title">Compliance Manager</h4>
                        <span class="position-type">Legal</span>
                    </div>
                    <div class="position-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        Remote
                    </div>
                    <p class="position-summary">Ensure adherence to global regulations and support ethical business practices.</p>
                    <button class="apply-btn">Apply Now</button>
                </div>
                
                <div class="position-card" data-position="Business Intelligence Manager">
                    <div class="position-header">
                        <h4 class="position-title">Business Intelligence Manager</h4>
                        <span class="position-type">Analytics</span>
                    </div>
                    <div class="position-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        Remote
                    </div>
                    <p class="position-summary">Lead data-driven decision making through advanced analytics and insights across all platforms.</p>
                    <button class="apply-btn">Apply Now</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Application Modal -->
<div id="applicationModal" class="modal">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Apply for Position</h2>
            <button class="modal-close" type="button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="applicationForm" class="application-form" method="POST" action="">
            <input type="hidden" name="position" id="positionInput">
            
            <div class="form-progress">
                <h4>Application Progress</h4>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 0%"></div>
                </div>
            </div>
            
            <div class="form-section">
                <h3>Personal Information</h3>
                <p class="form-subtitle">Please provide your basic information for our records.</p>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name" class="required">First Name</label>
                        <input type="text" id="first_name" name="first_name" placeholder="Enter your first name" required>
                        <div class="form-helper">This will be used for official communications</div>
                    </div>
                    <div class="form-group">
                        <label for="last_name" class="required">Last Name</label>
                        <input type="text" id="last_name" name="last_name" placeholder="Enter your last name" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email" class="required">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="your.email@example.com" required>
                        <div class="form-helper">We'll use this to contact you about your application</div>
                    </div>
                    <div class="form-group">
                        <label for="country" class="required">Country</label>
                        <select id="country" name="country" required>
                            <option value="">Select your country</option>
                            <option value="US">United States</option>
                            <option value="CA">Canada</option>
                            <option value="MX">Mexico</option>
                            <option value="GB">United Kingdom</option>
                            <option value="DE">Germany</option>
                            <option value="FR">France</option>
                            <option value="ES">Spain</option>
                            <option value="IT">Italy</option>
                            <option value="NL">Netherlands</option>
                            <option value="AU">Australia</option>
                            <option value="NZ">New Zealand</option>
                            <option value="JP">Japan</option>
                            <option value="KR">South Korea</option>
                            <option value="SG">Singapore</option>
                            <option value="BR">Brazil</option>
                            <option value="AR">Argentina</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="date_of_birth" class="required">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" required>
                    <div class="form-helper">Required for legal compliance and age verification</div>
                </div>
            </div>
            
            <div class="form-section">
                <h3>Discord Information</h3>
                <p class="form-subtitle">Discord is our primary communication platform for team collaboration.</p>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="discord_username" class="required">Discord Username</label>
                        <input type="text" id="discord_username" name="discord_username" placeholder="username#1234" required>
                        <div class="form-helper">Include the full username with discriminator</div>
                    </div>
                    <div class="form-group">
                        <label for="discord_id" class="required">Discord User ID</label>
                        <input type="text" id="discord_id" name="discord_id" placeholder="123456789012345678" required>
                        <div class="form-helper">Enable Developer Mode in Discord settings to copy your ID</div>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h3>Application Details</h3>
                <p class="form-subtitle">Tell us about yourself and why you're interested in joining our team.</p>
                
                <div class="form-group">
                    <label for="experience" class="required">Relevant Experience & Skills</label>
                    <textarea id="experience" name="experience" rows="5" placeholder="Tell us about your relevant experience, skills, and qualifications for this role. Include any projects, achievements, or certifications that showcase your abilities..." required></textarea>
                    <div class="form-helper">Be specific about technologies, tools, and methodologies you've worked with</div>
                </div>
                
                <div class="form-group">
                    <label for="motivation" class="required">Why do you want to join Nexi Hub?</label>
                    <textarea id="motivation" name="motivation" rows="4" placeholder="What motivates you to apply for this position? How do you align with our mission to 'Build. Automate. Scale.'? What excites you about our platform ecosystem?" required></textarea>
                    <div class="form-helper">Help us understand your passion and how you'll contribute to our vision</div>
                </div>
                
                <div class="form-group">
                    <label for="availability" class="required">Availability</label>
                    <select id="availability" name="availability" required>
                        <option value="">Select your availability</option>
                        <option value="Immediately">Available immediately</option>
                        <option value="2 weeks">Available in 2 weeks</option>
                        <option value="1 month">Available in 1 month</option>
                        <option value="2-3 months">Available in 2-3 months</option>
                        <option value="Other">Other (please specify in additional info)</option>
                    </select>
                    <div class="form-helper">When would you be able to start contributing to our team?</div>
                </div>
                
                <div class="form-group">
                    <label for="additional_info">Additional Information</label>
                    <textarea id="additional_info" name="additional_info" rows="3" placeholder="Any additional information you'd like to share about yourself, your interests, or anything else that might be relevant to your application..."></textarea>
                    <div class="form-helper">Optional: Share anything else that showcases who you are</div>
                </div>
            </div>
            
            <div class="form-section">
                <h3>Legal Agreements</h3>
                <p class="form-subtitle">Please review and agree to the following terms to complete your application.</p>
                
                <div class="checkbox-group">
                    <input type="checkbox" id="contract_agreement" name="contract_agreement" required>
                    <label for="contract_agreement">
                        I agree to sign a <strong>contract and Non-Disclosure Agreement (NDA)</strong> if offered this position. I understand that this role involves access to confidential information and proprietary systems.
                    </label>
                </div>
                
                <div class="checkbox-group">
                    <input type="checkbox" id="email_agreement" name="email_agreement" required>
                    <label for="email_agreement">
                        I agree to <strong>receive and use a Nexi Hub email address</strong> for official communications. I understand this will be my primary email for work-related activities.
                    </label>
                </div>
            </div>
            
            <div class="form-submit-section">
                <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
                    By submitting this application, you acknowledge that you have read and agree to our 
                    <a href="/legal#privacy" style="color: var(--primary-color);">Privacy Policy</a> and 
                    <a href="/legal#terms" style="color: var(--primary-color);">Terms of Service</a>.
                </p>
                <button type="button" class="btn btn-secondary cancel-btn" style="margin-right: 1rem;">Cancel</button>
                <button type="submit" name="submit_application" class="submit-btn">
                    Submit Application
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Modal functionality
const modal = document.getElementById('applicationModal');
const modalOverlay = document.querySelector('.modal-overlay');
const applyBtns = document.querySelectorAll('.apply-btn');
const closeBtn = document.querySelector('.modal-close');
const cancelBtn = document.querySelector('.cancel-btn');
const modalTitle = document.getElementById('modalTitle');
const positionInput = document.getElementById('positionInput');

function openModal(position) {
    modalTitle.textContent = `Apply for ${position}`;
    positionInput.value = position;
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

applyBtns.forEach(btn => {
    btn.addEventListener('click', function() {
        const positionCard = this.closest('.position-card');
        const position = positionCard.dataset.position;
        openModal(position);
    });
});

closeBtn.addEventListener('click', closeModal);
cancelBtn.addEventListener('click', closeModal);
modalOverlay.addEventListener('click', closeModal);

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && modal.style.display === 'flex') {
        closeModal();
    }
});
</script>

<?php include 'includes/footer.php'; ?>
