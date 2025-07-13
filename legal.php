<?php
$page_title = "Legal";
$page_description = "Legal information, privacy policy, and terms of service for Nexi Hub - Build. Automate. Scale.";
include 'includes/header.php';
?>

<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Legal Information</h1>
            <p class="hero-subtitle">Build. Automate. Scale.</p>
            <p class="hero-description">
                Transparency and trust are fundamental to our relationship with our users. 
                Here you'll find all our legal documents and policies.
            </p>
        </div>
    </div>
</section>

<section class="content-section">
    <div class="container">
        <div class="products-grid">
            <div class="product-card">
                <div class="product-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12,17A2,2 0 0,0 14,15C14,13.89 13.1,13 12,13A2,2 0 0,0 10,15A2,2 0 0,0 12,17M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6A2,2 0 0,1 4,20V10C4,8.89 4.9,8 6,8H7V6A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,3A3,3 0 0,0 9,6V8H15V6A3,3 0 0,0 12,3Z"/>
                    </svg>
                </div>
                <h3 class="product-title">Privacy Policy</h3>
                <p class="product-description">
                    How we collect, use, and protect your personal information across all Nexi Hub platforms.
                </p>
                <a href="#privacy" class="product-link">
                    Read Privacy Policy →
                </a>
            </div>

            <div class="product-card">
                <div class="product-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                    </svg>
                </div>
                <h3 class="product-title">Terms of Service</h3>
                <p class="product-description">
                    The terms and conditions that govern your use of Nexi Hub and our platforms.
                </p>
                <a href="#terms" class="product-link">
                    Read Terms →
                </a>
            </div>

            <div class="product-card">
                <div class="product-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12,3A9,9 0 0,0 3,12A9,9 0 0,0 12,21A9,9 0 0,0 21,12C21,11.5 20.96,11 20.87,10.5C20.6,10 20,10 20,10H18V9A2,2 0 0,0 16,7H15V6A2,2 0 0,0 13,4H12M7,7.5A1.5,1.5 0 0,1 8.5,9A1.5,1.5 0 0,1 7,10.5A1.5,1.5 0 0,1 5.5,9A1.5,1.5 0 0,1 7,7.5M7.5,16C8.5,16 9.24,16.5 10,17H5C5.76,16.5 6.5,16 7.5,16Z"/>
                    </svg>
                </div>
                <h3 class="product-title">Cookie Policy</h3>
                <p class="product-description">
                    Information about how we use cookies and similar technologies on our websites.
                </p>
                <a href="#cookies" class="product-link">
                    Read Cookie Policy →
                </a>
            </div>
        </div>
    </div>
</section>

<section id="privacy" class="content-section alt">
    <div class="container">
        <h2 class="section-title">Privacy Policy</h2>
        <div class="content-text" style="max-width: 800px; margin: 0 auto;">
            <p style="text-align: center; color: var(--text-secondary); margin-bottom: 2rem;">
                Last updated: <?php echo date('F j, Y'); ?>
            </p>
            
            <h3>Information We Collect</h3>
            <p>
                We collect information you provide directly to us, such as when you create an account, 
                use our services, or contact us for support.
            </p>

            <h3>How We Use Your Information</h3>
            <p>
                We use the information we collect to provide, maintain, and improve our services, 
                process transactions, and communicate with you.
            </p>

            <h3>Information Sharing</h3>
            <p>
                We do not sell, trade, or rent your personal information to third parties. We may 
                share information in certain limited circumstances as outlined in this policy.
            </p>

            <h3>Data Security</h3>
            <p>
                We implement appropriate security measures to protect your personal information 
                against unauthorized access, alteration, disclosure, or destruction.
            </p>

            <h3>Contact Us</h3>
            <p>
                If you have questions about this Privacy Policy, please contact us at 
                <a href="mailto:legal@nexihub.uk" style="color: var(--primary-color);">legal@nexihub.uk</a>.
            </p>
        </div>
    </div>
</section>

<section id="terms" class="content-section">
    <div class="container">
        <h2 class="section-title">Terms of Service</h2>
        <div class="content-text" style="max-width: 800px; margin: 0 auto;">
            <p style="text-align: center; color: var(--text-secondary); margin-bottom: 2rem;">
                Last updated: <?php echo date('F j, Y'); ?>
            </p>
            
            <h3>Acceptance of Terms</h3>
            <p>
                By accessing and using Nexi Hub services, you accept and agree to be bound by 
                the terms and provision of this agreement.
            </p>

            <h3>Use License</h3>
            <p>
                Permission is granted to temporarily use Nexi Hub services for personal, 
                non-commercial transitory viewing only.
            </p>

            <h3>Disclaimer</h3>
            <p>
                The materials on Nexi Hub's platforms are provided on an 'as is' basis. 
                Nexi Hub makes no warranties, expressed or implied.
            </p>

            <h3>Limitations</h3>
            <p>
                In no event shall Nexi Hub or its suppliers be liable for any damages arising 
                out of the use or inability to use our services.
            </p>

            <h3>Contact Information</h3>
            <p>
                Questions about the Terms of Service should be sent to us at 
                <a href="mailto:legal@nexihub.uk" style="color: var(--primary-color);">legal@nexihub.uk</a>.
            </p>
        </div>
    </div>
</section>

<section id="cookies" class="content-section alt">
    <div class="container">
        <h2 class="section-title">Cookie Policy</h2>
        <div class="content-text" style="max-width: 800px; margin: 0 auto;">
            <p style="text-align: center; color: var(--text-secondary); margin-bottom: 2rem;">
                Last updated: <?php echo date('F j, Y'); ?>
            </p>
            
            <h3>What Are Cookies</h3>
            <p>
                Cookies are small text files that are used to store small pieces of information. 
                They are stored on your device when the website is loaded on your browser.
            </p>

            <h3>How We Use Cookies</h3>
            <p>
                We use cookies to store information including visitors' preferences, and the pages 
                on the website that the visitor accessed or visited.
            </p>

            <h3>Managing Cookies</h3>
            <p>
                You can control and/or delete cookies as you wish. You can delete all cookies that 
                are already on your computer and you can set most browsers to prevent them from being placed.
            </p>

            <h3>Questions</h3>
            <p>
                If you have any questions about our use of cookies, please contact us at 
                <a href="mailto:legal@nexihub.uk" style="color: var(--primary-color);">legal@nexihub.uk</a>.
            </p>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
