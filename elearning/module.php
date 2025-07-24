<?php
require_once '../config/config.php';

// Check if user is logged in via contract user session
if (!isset($_SESSION['contract_user_id'])) {
    header('Location: /elearning/');
    exit;
}

// Get staff information
$stmt = $pdo->prepare("SELECT sp.* FROM staff_profiles sp 
                       JOIN contract_users cu ON sp.id = cu.staff_id 
                       WHERE cu.id = ?");
$stmt->execute([$_SESSION['contract_user_id']]);
$staff = $stmt->fetch();

if (!$staff) {
    header('Location: /elearning/');
    exit;
}

$module_id = intval($_GET['id'] ?? 1);

// Validate module ID
if ($module_id < 1 || $module_id > 7) {
    header('Location: /elearning/');
    exit;
}

try {
    // Check if user can access this module
    $can_access = true;
    if ($module_id > 1) {
        $stmt = $pdo->prepare("SELECT completed FROM elearning_module_progress WHERE staff_id = ? AND module_id = ?");
        $stmt->execute([$staff['id'], $module_id - 1]);
        $prev_completed = $stmt->fetchColumn();
        $can_access = ($prev_completed == 1);
    }
    
    if (!$can_access) {
        header('Location: /elearning/');
        exit;
    }
    
    // Check if current module is completed
    $stmt = $pdo->prepare("SELECT * FROM elearning_module_progress WHERE staff_id = ? AND module_id = ?");
    $stmt->execute([$staff['id'], $module_id]);
    $module_progress = $stmt->fetch();
    $is_completed = ($module_progress && $module_progress['completed'] == 1);
    
} catch (Exception $e) {
    die("Error loading progress: " . $e->getMessage());
}

// Define modules content
$modules = [
    1 => [
        'title' => 'Welcome to Nexi Hub',
        'duration' => '30 minutes',
        'quiz_questions' => 5,
        'content' => '
            <h2>🚀 Welcome to the Nexi Hub Family!</h2>
            
            <p>Congratulations on joining Nexi Hub! We\'re absolutely thrilled to have you as part of our innovative and dynamic team. This comprehensive training module will introduce you to our company, our mission, our values, and what makes us unique in the competitive digital solutions industry.</p>
            
            <h3>Our Story: From Vision to Reality</h3>
            <p>Nexi Hub was founded in 2019 with a simple yet powerful vision: to revolutionize how businesses connect, communicate, and grow in the digital age. What started as a small team of passionate technologists working out of a shared workspace in Manchester has grown into a dynamic company serving clients across the globe.</p>
            
            <p>Our founders, driven by their experience in both traditional business consulting and cutting-edge technology development, recognized a critical gap in the market. While many companies offered either web development OR business intelligence OR automation solutions, very few provided an integrated approach that could transform businesses holistically.</p>
            
            <div class="mission-box">
                <h4>Our Mission Statement</h4>
                <p><strong>"To empower businesses with cutting-edge digital solutions that drive sustainable growth, operational efficiency, and long-term success in an increasingly connected world."</strong></p>
            </div>
            
            <h3>What Makes Nexi Hub Different</h3>
            <p>Unlike traditional agencies that focus on single solutions, Nexi Hub operates as a comprehensive digital transformation partner. We don\'t just build websites or create bots - we architect complete digital ecosystems that grow with your business.</p>
            
            <h3>Our Core Service Divisions</h3>
            
            <h4>🌐 Nexi Web Division</h4>
            <p>Our web development division specializes in creating responsive, high-performance websites and web applications that don\'t just look great - they deliver measurable business results. We work with everything from small business websites to enterprise-level e-commerce platforms and complex web applications.</p>
            
            <p><strong>Key Services Include:</strong></p>
            <ul>
                <li>Custom website design and development</li>
                <li>E-commerce platform development (Shopify, WooCommerce, custom solutions)</li>
                <li>Progressive Web App (PWA) development</li>
                <li>Website optimization and performance tuning</li>
                <li>Content Management System (CMS) development</li>
                <li>Search Engine Optimization (SEO) integration</li>
            </ul>
            
            <h4>🤖 Nexi Bot Division</h4>
            <p>Our automation and chatbot division focuses on streamlining business processes and improving customer engagement through intelligent automation. We create custom bots and automation workflows that save time, reduce costs, and improve customer satisfaction.</p>
            
            <p><strong>Key Services Include:</strong></p>
            <ul>
                <li>Custom chatbot development for websites and messaging platforms</li>
                <li>Business process automation</li>
                <li>Customer service automation</li>
                <li>Lead generation and qualification bots</li>
                <li>Integration with existing business systems</li>
                <li>AI-powered conversation flows</li>
            </ul>
            
            <h4>📊 Nexi Pulse Division</h4>
            <p>Our analytics and business intelligence division helps companies make data-driven decisions by providing comprehensive insights into their operations, customers, and market performance. We transform raw data into actionable intelligence.</p>
            
            <p><strong>Key Services Include:</strong></p>
            <ul>
                <li>Custom dashboard development</li>
                <li>Data visualization and reporting</li>
                <li>Business intelligence strategy</li>
                <li>Performance tracking and KPI monitoring</li>
                <li>Market analysis and competitive intelligence</li>
                <li>Predictive analytics and forecasting</li>
            </ul>
            
            <h3>Our Integrated Approach</h3>
            <p>What sets Nexi Hub apart is our integrated approach. Rather than treating web development, automation, and analytics as separate services, we view them as interconnected components of a complete digital ecosystem. For example:</p>
            
            <ul>
                <li>A website we build includes analytics tracking from day one</li>
                <li>Chatbots we develop feed data directly into business intelligence dashboards</li>
                <li>Automation workflows are designed to improve metrics we\'re already tracking</li>
            </ul>
            
            <h3>Your Role in Our Success</h3>
            <p>Every team member at Nexi Hub plays a crucial role in our success, regardless of their department or level. Whether you\'re in development, design, marketing, customer success, or operations, your contributions directly impact our ability to deliver exceptional value to our clients and push the boundaries of what\'s possible in digital transformation.</p>
            
            <p>As a member of the Nexi Hub team, you\'re not just an employee - you\'re a partner in our mission to transform how businesses operate in the digital age. Your ideas, creativity, and expertise will help shape the future of our company and the success of our clients.</p>
            
            <h3>Our Client-Centric Philosophy</h3>
            <p>At Nexi Hub, we believe that technology should serve business goals, not the other way around. Every solution we create is designed with our clients\' specific objectives in mind. We take the time to understand their industry, their challenges, and their vision for growth before proposing any technical solutions.</p>
            
            <div class="key-points">
                <h4>🎯 Key Takeaways from This Module:</h4>
                <ul>
                    <li>Nexi Hub is a comprehensive digital transformation company, not just a web development agency</li>
                    <li>We offer integrated solutions across three core divisions: Web, Bot, and Pulse</li>
                    <li>Our approach focuses on creating complete digital ecosystems rather than standalone solutions</li>
                    <li>Every team member contributes to our collective success and client satisfaction</li>
                    <li>We prioritize business outcomes over technical complexity</li>
                    <li>Innovation and excellence are fundamental to everything we do</li>
                </ul>
            </div>
            
            <h3>What\'s Next?</h3>
            <p>In the following modules, you\'ll learn more about our company values and culture, communication guidelines, security protocols, and the specific tools and processes we use to deliver exceptional results for our clients. Each module builds upon the previous one, so make sure to complete them in order and take the knowledge checks seriously.</p>
            
            <p>Welcome aboard, and let\'s build something amazing together! 🚀</p>
        '
    ],
    2 => [
        'title' => 'Company Values & Culture',
        'duration' => '25 minutes',
        'quiz_questions' => 5,
        'content' => '
            <h2>💎 Nexi Hub Values & Culture</h2>
            <p>At Nexi Hub, our values aren\'t just words on a wall - they\'re the foundation of how we operate, how we treat each other, and how we serve our clients. Understanding and embodying these values is essential to your success here and to our collective mission of transforming businesses through innovative digital solutions.</p>
            
            <h3>🎯 Excellence: The Nexi Standard</h3>
            <p>Excellence at Nexi Hub means consistently delivering work that exceeds expectations. It\'s not about perfection - it\'s about continuous improvement, attention to detail, and taking pride in your craft.</p>
            
            <p><strong>What Excellence Looks Like:</strong></p>
            <ul>
                <li><strong>Code Quality:</strong> Writing clean, maintainable, and well-documented code that follows industry best practices</li>
                <li><strong>Client Deliverables:</strong> Delivering projects on time, within scope, and with thorough testing</li>
                <li><strong>Communication:</strong> Being clear, concise, and proactive in all interactions</li>
                <li><strong>Problem-Solving:</strong> Going beyond quick fixes to find root causes and lasting solutions</li>
                <li><strong>Continuous Learning:</strong> Staying current with industry trends and constantly improving your skills</li>
            </ul>
            
            <p><strong>Excellence in Action:</strong> When a client requests a feature, we don\'t just build what they asked for - we think about how it fits into their broader goals, suggest improvements, and ensure it\'s scalable for future growth.</p>
            
            <h3>🚀 Innovation: Pushing Boundaries</h3>
            <p>Innovation is in our DNA. We\'re not content with doing things the way they\'ve always been done. We constantly explore new technologies, methodologies, and approaches to solve problems more effectively.</p>
            
            <p><strong>Innovation Principles:</strong></p>
            <ul>
                <li><strong>Embracing New Technologies:</strong> We\'re early adopters of promising technologies that can benefit our clients</li>
                <li><strong>Creative Problem-Solving:</strong> We approach challenges from multiple angles and aren\'t afraid to try unconventional solutions</li>
                <li><strong>Process Improvement:</strong> We regularly review and refine our workflows to increase efficiency and quality</li>
                <li><strong>Client Innovation:</strong> We help clients innovate by introducing them to new possibilities they hadn\'t considered</li>
                <li><strong>Knowledge Sharing:</strong> We share discoveries and learnings across the team to multiply our innovative capacity</li>
            </ul>
            
            <p><strong>Innovation Examples:</strong> Our integration of AI chatbots with business intelligence dashboards, our automated deployment pipelines, and our custom analytics solutions that provide insights competitors can\'t match.</p>
            
            <h3>🤝 Collaboration: Stronger Together</h3>
            <p>No one at Nexi Hub works in isolation. Our collaborative culture means that every project benefits from diverse perspectives, skills, and experiences. We believe that the best solutions emerge when different minds work together.</p>
            
            <p><strong>Collaboration Practices:</strong></p>
            <ul>
                <li><strong>Cross-Functional Teams:</strong> Projects include members from development, design, and strategy</li>
                <li><strong>Code Reviews:</strong> All code is reviewed by peers to ensure quality and knowledge sharing</li>
                <li><strong>Regular Check-ins:</strong> Weekly team meetings and daily standups keep everyone aligned</li>
                <li><strong>Mentorship:</strong> Senior team members actively mentor junior colleagues</li>
                <li><strong>Open Communication:</strong> Questions are welcomed, and knowledge sharing is encouraged</li>
                <li><strong>Conflict Resolution:</strong> Disagreements are addressed constructively and professionally</li>
            </ul>
            
            <p><strong>Collaboration in Practice:</strong> When working on a complex e-commerce integration, our developers work closely with our UX designers to ensure the technical implementation supports the best user experience, while our analysts track performance metrics to optimize the solution.</p>
            
            <h3>� Growth: Evolving Every Day</h3>
            <p>Growth at Nexi Hub happens on multiple levels - personal growth, professional development, and company expansion. We\'re committed to creating an environment where everyone can reach their full potential.</p>
            
            <p><strong>Personal Growth Opportunities:</strong></p>
            <ul>
                <li><strong>Skills Development:</strong> Regular training sessions, conference attendance, and certification programs</li>
                <li><strong>Project Variety:</strong> Exposure to different industries, technologies, and challenges</li>
                <li><strong>Leadership Opportunities:</strong> Chances to lead projects, mentor others, and drive initiatives</li>
                <li><strong>Career Pathways:</strong> Clear advancement opportunities with regular performance reviews</li>
                <li><strong>Innovation Time:</strong> Dedicated time for personal projects and skill exploration</li>
            </ul>
            
            <p><strong>Professional Development Support:</strong></p>
            <ul>
                <li>Learning stipend for courses, books, and conferences</li>
                <li>Time allocation for professional development activities</li>
                <li>Internal knowledge-sharing sessions and tech talks</li>
                <li>Cross-training opportunities in different areas of the business</li>
            </ul>
            
            <h3>🌟 Additional Core Values</h3>
            
            <h4>🔒 Integrity</h4>
            <p>We do the right thing, even when no one is watching. This means honest communication with clients about timelines and challenges, protecting client data, and taking responsibility for our mistakes.</p>
            
            <h4>⚡ Agility</h4>
            <p>The digital landscape changes rapidly, and we adapt quickly. We embrace change, pivot when necessary, and always look for ways to deliver value faster and more efficiently.</p>
            
            <h4>🎨 Creativity</h4>
            <p>Every challenge is an opportunity for creative thinking. We encourage experimentation, celebrate unique approaches, and understand that breakthrough solutions often come from thinking outside the box.</p>
            
            <h3>Our Culture in Daily Practice</h3>
            
            <h4>🏢 Work Environment</h4>
            <p>Our office is designed to foster collaboration and creativity. Open spaces encourage interaction, while quiet zones provide focus time. We have dedicated areas for brainstorming, team meetings, and casual conversations.</p>
            
            <h4>⏰ Work-Life Balance</h4>
            <p>We believe that well-rested, fulfilled team members do their best work. We offer flexible schedules, remote work options, and encourage taking time off to recharge. We respect boundaries and don\'t expect after-hours availability unless there\'s a genuine emergency.</p>
            
            <h4>🎉 Celebrating Success</h4>
            <p>We celebrate both big wins and small victories. Monthly team meetings highlight achievements, successful project launches are celebrated company-wide, and individual contributions are recognized publicly.</p>
            
            <h4>� Open Communication</h4>
            <p>Our culture encourages open, honest communication at all levels. Regular feedback sessions, anonymous suggestion boxes, and leadership accessibility ensure that every voice is heard.</p>
            
            <h3>Living Our Values</h3>
            <p>As a Nexi Hub team member, you\'re expected to embody these values in your daily work. This means:</p>
            
            <ul>
                <li>Delivering high-quality work that reflects our commitment to excellence</li>
                <li>Proactively suggesting improvements and innovative solutions</li>
                <li>Collaborating effectively with colleagues across all departments</li>
                <li>Taking initiative in your professional development and growth</li>
                <li>Acting with integrity in all client and colleague interactions</li>
                <li>Adapting positively to change and new challenges</li>
                <li>Contributing to our positive, supportive culture</li>
            </ul>
            
            <div class="key-points">
                <h4>🎯 Key Takeaways from This Module:</h4>
                <ul>
                    <li>Excellence means continuous improvement and exceeding expectations, not perfection</li>
                    <li>Innovation is encouraged and supported at all levels of the organization</li>
                    <li>Collaboration makes our solutions stronger and our team more effective</li>
                    <li>Growth opportunities are available and actively supported by the company</li>
                    <li>Our values guide decision-making and daily interactions</li>
                    <li>Work-life balance and open communication are cultural priorities</li>
                    <li>Every team member is responsible for upholding and promoting our values</li>
                </ul>
            </div>
        '
    ],
    3 => [
        'title' => 'Communication Guidelines',
        'duration' => '22 minutes',
        'quiz_questions' => 5,
        'content' => '
            <h2>💬 Communication Excellence at Nexi Hub</h2>
            <p>Effective communication is the backbone of everything we do at Nexi Hub. Whether you\'re collaborating with team members, updating clients, or documenting your work, clear and professional communication ensures our projects run smoothly and our relationships remain strong.</p>
            
            <h3>📱 Internal Communication Channels</h3>
            <p>We use different communication tools for different purposes. Understanding when and how to use each tool is crucial for effective collaboration.</p>
            
            <h4>🔹 Discord - Daily Team Communication</h4>
            <p><strong>Primary Use:</strong> Real-time team discussions, quick questions, informal updates, and collaborative problem-solving.</p>
            
            <p><strong>Best Practices:</strong></p>
            <ul>
                <li><strong>Channel Organization:</strong> Use appropriate channels (#general, #development, #design, #projects)</li>
                <li><strong>Response Time:</strong> Respond within 2-4 hours during business hours</li>
                <li><strong>Thread Usage:</strong> Use threads for detailed discussions to keep channels organized</li>
                <li><strong>Status Updates:</strong> Use status indicators to show availability</li>
                <li><strong>Screen Sharing:</strong> Use for quick code reviews or troubleshooting sessions</li>
                <li><strong>File Sharing:</strong> Share screenshots, mockups, and quick files</li>
            </ul>
            
            <p><strong>Discord Etiquette:</strong></p>
            <ul>
                <li>Keep messages concise but informative</li>
                <li>Use @mentions sparingly and only when necessary</li>
                <li>Avoid sending multiple single-word messages; compose your thoughts first</li>
                <li>Use appropriate emojis and reactions to acknowledge messages</li>
                <li>Respect others\' focus time and availability status</li>
            </ul>
            
            <h4>📧 Email - Formal Communications</h4>
            <p><strong>Primary Use:</strong> Official communications, client correspondence, project documentation, and formal announcements.</p>
            
            <p><strong>When to Use Email:</strong></p>
            <ul>
                <li>Client communications and updates</li>
                <li>Project proposals and contracts</li>
                <li>Performance reviews and HR matters</li>
                <li>External vendor communications</li>
                <li>Important announcements that need documentation</li>
                <li>Sharing detailed project reports or documentation</li>
            </ul>
            
            <p><strong>Email Best Practices:</strong></p>
            <ul>
                <li><strong>Subject Lines:</strong> Clear, specific, and descriptive</li>
                <li><strong>Structure:</strong> Use proper greeting, body, and closing</li>
                <li><strong>Tone:</strong> Professional but friendly</li>
                <li><strong>Length:</strong> Concise yet comprehensive</li>
                <li><strong>Attachments:</strong> Clearly labeled and virus-scanned</li>
                <li><strong>CC/BCC:</strong> Use appropriately and sparingly</li>
            </ul>
            
            <h4>📞 Video Calls - Face-to-Face Discussions</h4>
            <p><strong>Primary Use:</strong> Client meetings, team standup, brainstorming sessions, and complex problem-solving discussions.</p>
            
            <p><strong>Video Call Guidelines:</strong></p>
            <ul>
                <li><strong>Preparation:</strong> Have agenda and materials ready</li>
                <li><strong>Technology:</strong> Test audio/video before important calls</li>
                <li><strong>Environment:</strong> Professional background and good lighting</li>
                <li><strong>Participation:</strong> Mute when not speaking, engage actively</li>
                <li><strong>Follow-up:</strong> Send summary and action items after calls</li>
            </ul>
            
            <h3>👥 Client Communication Standards</h3>
            <p>Our clients trust us with their most important business initiatives. Professional, clear communication helps build and maintain that trust.</p>
            
            <h4>⏰ Response Time Expectations</h4>
            <ul>
                <li><strong>Urgent Issues:</strong> Within 1 hour during business hours</li>
                <li><strong>General Inquiries:</strong> Within 4 hours during business hours</li>
                <li><strong>Project Updates:</strong> Within 24 hours</li>
                <li><strong>Proposals/Quotes:</strong> Within 2-3 business days</li>
            </ul>
            
            <h4>📝 Client Communication Templates</h4>
            
            <p><strong>Project Update Email Structure:</strong></p>
            <div class="code-block">
                <strong>Subject:</strong> [Project Name] - Weekly Update [Date]<br><br>
                <strong>Hi [Client Name],</strong><br><br>
                <strong>Progress This Week:</strong><br>
                • [Completed tasks]<br>
                • [Milestones achieved]<br><br>
                <strong>Upcoming This Week:</strong><br>
                • [Planned tasks]<br>
                • [Expected deliverables]<br><br>
                <strong>Issues/Blockers:</strong><br>
                • [Any challenges and how we\'re addressing them]<br><br>
                <strong>Questions for You:</strong><br>
                • [Any client input needed]<br><br>
                <strong>Best regards,</strong><br>
                [Your name and title]
            </div>
            
            <h4>🔄 Status Communication Protocol</h4>
            <p><strong>Green Status:</strong> Project on track, no issues</p>
            <p><strong>Yellow Status:</strong> Minor issues, working on solutions</p>
            <p><strong>Red Status:</strong> Significant problems, immediate discussion needed</p>
            
            <h3>📋 Documentation Standards</h3>
            <p>Good documentation ensures knowledge is preserved and accessible to the entire team.</p>
            
            <h4>💻 Code Documentation</h4>
            <ul>
                <li><strong>Comments:</strong> Explain complex logic and business rules</li>
                <li><strong>README Files:</strong> Setup instructions and project overview</li>
                <li><strong>API Documentation:</strong> Clear endpoint descriptions and examples</li>
                <li><strong>Commit Messages:</strong> Descriptive and follows team conventions</li>
            </ul>
            
            <h4>📊 Project Documentation</h4>
            <ul>
                <li><strong>Project Requirements:</strong> Detailed scope and specifications</li>
                <li><strong>Meeting Notes:</strong> Key decisions and action items</li>
                <li><strong>Change Logs:</strong> Track modifications and their reasoning</li>
                <li><strong>Testing Procedures:</strong> How to validate functionality</li>
            </ul>
            
            <h3>🚨 Crisis Communication</h3>
            <p>When things go wrong, clear communication becomes even more critical.</p>
            
            <h4>⚠️ Incident Reporting Process</h4>
            <ol>
                <li><strong>Immediate Notification:</strong> Alert team lead and affected stakeholders</li>
                <li><strong>Status Assessment:</strong> Determine severity and impact</li>
                <li><strong>Action Plan:</strong> Outline steps to resolve the issue</li>
                <li><strong>Regular Updates:</strong> Keep stakeholders informed of progress</li>
                <li><strong>Resolution Summary:</strong> Document what happened and lessons learned</li>
            </ol>
            
            <h4>📞 Escalation Protocol</h4>
            <ul>
                <li><strong>Level 1:</strong> Team member → Project lead</li>
                <li><strong>Level 2:</strong> Project lead → Department manager</li>
                <li><strong>Level 3:</strong> Department manager → Senior leadership</li>
            </ul>
            
            <h3>🌐 Cross-Cultural Communication</h3>
            <p>We work with clients and team members from diverse backgrounds. Cultural sensitivity in communication is important.</p>
            
            <h4>🤝 Best Practices for Global Communication</h4>
            <ul>
                <li><strong>Clear Language:</strong> Avoid idioms and complex phrases</li>
                <li><strong>Time Zone Awareness:</strong> Schedule meetings thoughtfully</li>
                <li><strong>Cultural Holidays:</strong> Be aware of different holiday schedules</li>
                <li><strong>Communication Styles:</strong> Adapt to different directness preferences</li>
                <li><strong>Follow-up:</strong> Confirm understanding in writing</li>
            </ul>
            
            <h3>📱 Digital Communication Security</h3>
            <p>Protecting sensitive information in our communications is essential.</p>
            
            <h4>🔒 Security Guidelines</h4>
            <ul>
                <li><strong>Confidential Information:</strong> Use secure channels for sensitive data</li>
                <li><strong>Password Protection:</strong> Never share passwords via unsecured channels</li>
                <li><strong>File Sharing:</strong> Use approved platforms with encryption</li>
                <li><strong>Public Spaces:</strong> Be aware of who might overhear conversations</li>
                <li><strong>Screen Sharing:</strong> Close sensitive windows before sharing</li>
            </ul>
            
            <h3>🎯 Communication Success Metrics</h3>
            <p>How we measure effective communication:</p>
            
            <ul>
                <li><strong>Response Times:</strong> Meeting our stated response time commitments</li>
                <li><strong>Client Satisfaction:</strong> Positive feedback on communication quality</li>
                <li><strong>Project Clarity:</strong> Fewer misunderstandings and rework requests</li>
                <li><strong>Team Alignment:</strong> Reduced conflicts and improved collaboration</li>
                <li><strong>Knowledge Retention:</strong> Well-documented processes and decisions</li>
            </ul>
            
            <div class="key-points">
                <h4>🎯 Key Takeaways from This Module:</h4>
                <ul>
                    <li>Use Discord for daily team communication and quick collaboration</li>
                    <li>Use email for formal communications and client correspondence</li>
                    <li>Respond to client communications within established timeframes</li>
                    <li>Document important decisions and processes clearly</li>
                    <li>Follow escalation protocols during crisis situations</li>
                    <li>Be culturally sensitive in all communications</li>
                    <li>Protect sensitive information in digital communications</li>
                    <li>Communication quality directly impacts project success and client satisfaction</li>
                </ul>
            </div>
        '
    ],
    4 => [
        'title' => 'Data Protection & Security',
        'duration' => '30 minutes',
        'quiz_questions' => 6,
        'content' => '
            <h2>🔒 Security & Data Protection at Nexi Hub</h2>
            <p>Security isn\'t just an IT concern - it\'s everyone\'s responsibility. As a member of the Nexi Hub team, you handle sensitive client data, proprietary information, and valuable intellectual property daily. Understanding and following our security protocols protects our clients, our company, and your own career.</p>
            
            <h3>🛡️ Understanding Data Classification</h3>
            <p>Not all data is equal. We classify information based on sensitivity and implement appropriate protection measures for each level.</p>
            
            <h4>🔴 Highly Confidential</h4>
            <ul>
                <li><strong>Examples:</strong> Client financial data, payment information, personal identifiable information (PII), source code</li>
                <li><strong>Protection:</strong> Encryption required, access logs maintained, limited access on need-to-know basis</li>
                <li><strong>Sharing:</strong> Secure channels only, with explicit client permission</li>
            </ul>
            
            <h4>🟡 Confidential</h4>
            <ul>
                <li><strong>Examples:</strong> Client business strategies, internal project details, employee information</li>
                <li><strong>Protection:</strong> Password-protected, shared only with authorized team members</li>
                <li><strong>Sharing:</strong> Within project teams, with signed NDAs for external parties</li>
            </ul>
            
            <h4>🟢 Internal Use</h4>
            <ul>
                <li><strong>Examples:</strong> Company policies, general project status, team announcements</li>
                <li><strong>Protection:</strong> Standard password protection, company network access required</li>
                <li><strong>Sharing:</strong> Within Nexi Hub team members only</li>
            </ul>
            
            <h4>⚪ Public</h4>
            <ul>
                <li><strong>Examples:</strong> Marketing materials, published case studies, public website content</li>
                <li><strong>Protection:</strong> Standard web security practices</li>
                <li><strong>Sharing:</strong> Can be shared publicly</li>
            </ul>
            
            <h3>🔐 Password Security Standards</h3>
            <p>Passwords are your first line of defense. Weak passwords are one of the most common security vulnerabilities.</p>
            
            <h4>✅ Password Requirements</h4>
            <ul>
                <li><strong>Length:</strong> Minimum 14 characters (16+ recommended)</li>
                <li><strong>Complexity:</strong> Include uppercase, lowercase, numbers, and special characters</li>
                <li><strong>Uniqueness:</strong> Use different passwords for each account</li>
                <li><strong>Expiration:</strong> Change passwords every 90 days for critical systems</li>
                <li><strong>History:</strong> Don\'t reuse your last 12 passwords</li>
            </ul>
            
            <h4>🚫 Password Don\'ts</h4>
            <ul>
                <li>Don\'t use personal information (names, birthdays, addresses)</li>
                <li>Don\'t use common words or phrases</li>
                <li>Don\'t write passwords on sticky notes or unsecured documents</li>
                <li>Don\'t share passwords with anyone, including team members</li>
                <li>Don\'t use the same password for work and personal accounts</li>
            </ul>
            
            <h4>🔧 Password Management Tools</h4>
            <p><strong>Approved Password Managers:</strong></p>
            <ul>
                <li>1Password (company preferred)</li>
                <li>Bitwarden</li>
                <li>LastPass</li>
            </ul>
            
            <p><strong>Benefits of Password Managers:</strong></p>
            <ul>
                <li>Generate strong, unique passwords automatically</li>
                <li>Store passwords securely with encryption</li>
                <li>Auto-fill credentials to prevent keyloggers</li>
                <li>Share credentials securely with team members when necessary</li>
                <li>Alert you to compromised passwords</li>
            </ul>
            
            <h3>🔐 Two-Factor Authentication (2FA)</h3>
            <p>2FA adds an essential second layer of security beyond passwords.</p>
            
            <h4>📱 Required 2FA Implementation</h4>
            <ul>
                <li><strong>Company Email:</strong> Required for all email accounts</li>
                <li><strong>Cloud Services:</strong> AWS, Google Cloud, Microsoft 365</li>
                <li><strong>Development Tools:</strong> GitHub, GitLab, deployment systems</li>
                <li><strong>Communication Tools:</strong> Discord, Slack, project management tools</li>
                <li><strong>Financial Systems:</strong> Any system processing payments or financial data</li>
            </ul>
            
            <h4>🛠️ 2FA Methods (in order of preference)</h4>
            <ol>
                <li><strong>Hardware Security Keys:</strong> YubiKey, Google Titan (most secure)</li>
                <li><strong>Authenticator Apps:</strong> Google Authenticator, Authy, Microsoft Authenticator</li>
                <li><strong>SMS/Phone:</strong> Only when other methods unavailable (least secure)</li>
            </ol>
            
            <h3>💻 Device Security</h3>
            <p>Your devices are gateways to sensitive data and systems. Securing them properly is crucial.</p>
            
            <h4>🖥️ Computer Security Requirements</h4>
            <ul>
                <li><strong>Operating System:</strong> Keep OS updated with latest security patches</li>
                <li><strong>Antivirus:</strong> Install and maintain updated antivirus software</li>
                <li><strong>Firewall:</strong> Enable and properly configure firewall</li>
                <li><strong>Screen Lock:</strong> Automatic screen lock after 10 minutes of inactivity</li>
                <li><strong>Disk Encryption:</strong> Full disk encryption enabled (FileVault, BitLocker)</li>
                <li><strong>Software Updates:</strong> Keep all software updated, especially browsers and development tools</li>
            </ul>
            
            <h4>📱 Mobile Device Security</h4>
            <ul>
                <li><strong>Screen Lock:</strong> PIN, pattern, biometric, or password required</li>
                <li><strong>App Sources:</strong> Only install apps from official app stores</li>
                <li><strong>Work Apps:</strong> Use separate profiles for work-related apps</li>
                <li><strong>Public WiFi:</strong> Avoid accessing work systems on public WiFi</li>
                <li><strong>Remote Wipe:</strong> Enable remote wipe capability for lost/stolen devices</li>
            </ul>
            
            <h3>🌐 Network Security</h3>
            <p>Protecting data in transit is as important as protecting it at rest.</p>
            
            <h4>🔒 VPN Usage</h4>
            <ul>
                <li><strong>Required When:</strong> Working from public locations, accessing sensitive systems remotely</li>
                <li><strong>Approved VPNs:</strong> Company-provided VPN solutions</li>
                <li><strong>Configuration:</strong> Use company-approved VPN settings</li>
                <li><strong>Always On:</strong> VPN should be active when working outside the office</li>
            </ul>
            
            <h4>🌐 Safe Browsing Practices</h4>
            <ul>
                <li><strong>HTTPS Only:</strong> Ensure websites use HTTPS, especially for sensitive data</li>
                <li><strong>Download Sources:</strong> Only download software from official sources</li>
                <li><strong>Suspicious Links:</strong> Don\'t click links in suspicious emails</li>
                <li><strong>Browser Updates:</strong> Keep browsers updated with latest security patches</li>
                <li><strong>Extensions:</strong> Only install necessary, trusted browser extensions</li>
            </ul>
            
            <h3>📧 Email Security</h3>
            <p>Email is a common attack vector. Recognizing and preventing email-based threats protects our entire organization.</p>
            
            <h4>🎣 Phishing Recognition</h4>
            <p><strong>Red Flags to Watch For:</strong></p>
            <ul>
                <li>Urgent language demanding immediate action</li>
                <li>Requests for passwords or sensitive information</li>
                <li>Unexpected attachments or links</li>
                <li>Sender addresses that don\'t match the claimed organization</li>
                <li>Poor grammar or spelling in professional communications</li>
                <li>Requests to verify account information</li>
            </ul>
            
            <h4>📎 Email Attachment Security</h4>
            <ul>
                <li><strong>Scan First:</strong> All attachments are automatically scanned, but remain vigilant</li>
                <li><strong>Expected Files:</strong> Only open attachments you\'re expecting</li>
                <li><strong>File Types:</strong> Be extra cautious with .exe, .zip, .doc, .pdf files</li>
                <li><strong>Source Verification:</strong> Verify sender before opening suspicious attachments</li>
            </ul>
            
            <h3>☁️ Cloud Security</h3>
            <p>We use various cloud services for development, storage, and collaboration. Each requires proper security configuration.</p>
            
            <h4>🗄️ Data Storage Best Practices</h4>
            <ul>
                <li><strong>Approved Platforms:</strong> Use only company-approved cloud storage (Google Drive, AWS S3)</li>
                <li><strong>Access Controls:</strong> Share files with minimum necessary permissions</li>
                <li><strong>Data Classification:</strong> Store data according to its classification level</li>
                <li><strong>Backup Verification:</strong> Regularly verify that backups are working</li>
                <li><strong>Retention Policies:</strong> Follow data retention and deletion policies</li>
            </ul>
            
            <h4>🔧 Development Environment Security</h4>
            <ul>
                <li><strong>API Keys:</strong> Never commit API keys or secrets to version control</li>
                <li><strong>Environment Variables:</strong> Use environment variables for sensitive configuration</li>
                <li><strong>Access Reviews:</strong> Regularly review who has access to production systems</li>
                <li><strong>Code Reviews:</strong> Include security considerations in all code reviews</li>
                <li><strong>Dependency Management:</strong> Keep dependencies updated and scan for vulnerabilities</li>
            </ul>
            
            <h3>🚨 Incident Response</h3>
            <p>Despite our best efforts, security incidents can occur. Quick, proper response minimizes damage.</p>
            
            <h4>⚠️ What Constitutes a Security Incident</h4>
            <ul>
                <li>Suspected malware infection</li>
                <li>Lost or stolen device containing work data</li>
                <li>Suspected phishing email</li>
                <li>Unauthorized access to systems or data</li>
                <li>Data breach or potential data exposure</li>
                <li>Suspicious system behavior</li>
            </ul>
            
            <h4>📞 Incident Reporting Process</h4>
            <ol>
                <li><strong>Immediate Action:</strong> Disconnect affected systems if safe to do so</li>
                <li><strong>Report Immediately:</strong> Contact IT security team and your manager</li>
                <li><strong>Document:</strong> Record what happened, when, and what you observed</li>
                <li><strong>Cooperate:</strong> Provide full cooperation with incident response team</li>
                <li><strong>Learn:</strong> Participate in post-incident review and training</li>
            </ol>
            
            <h3>⚖️ Legal and Compliance Requirements</h3>
            <p>We must comply with various regulations depending on our clients and the data we handle.</p>
            
            <h4>📋 Key Regulations</h4>
            <ul>
                <li><strong>GDPR:</strong> European data protection requirements</li>
                <li><strong>CCPA:</strong> California consumer privacy rights</li>
                <li><strong>HIPAA:</strong> Healthcare data protection (when applicable)</li>
                <li><strong>PCI DSS:</strong> Payment card industry standards</li>
                <li><strong>SOX:</strong> Financial reporting requirements (for public company clients)</li>
            </ul>
            
            <h4>🏢 Client-Specific Requirements</h4>
            <ul>
                <li>Some clients may have additional security requirements</li>
                <li>Security questionnaires must be completed accurately</li>
                <li>Compliance audits may require your participation</li>
                <li>Data processing agreements must be followed precisely</li>
            </ul>
            
            <h3>🎓 Security Training and Awareness</h3>
            <p>Security threats evolve constantly. Ongoing education is essential.</p>
            
            <h4>📚 Required Training</h4>
            <ul>
                <li><strong>Annual Security Training:</strong> Complete annually with updated threat information</li>
                <li><strong>Phishing Simulation:</strong> Monthly simulated phishing tests</li>
                <li><strong>Role-Specific Training:</strong> Additional training based on your access level</li>
                <li><strong>New Threat Briefings:</strong> Updates when new threats emerge</li>
            </ul>
            
            <h4>🔍 Staying Informed</h4>
            <ul>
                <li>Follow company security bulletins</li>
                <li>Report suspicious activities immediately</li>
                <li>Participate in security discussions and ask questions</li>
                <li>Keep security awareness current through industry resources</li>
            </ul>
            
            <div class="key-points">
                <h4>🎯 Key Takeaways from This Module:</h4>
                <ul>
                    <li>Security is everyone\'s responsibility, not just IT\'s</li>
                    <li>Use strong, unique passwords with 2FA on all critical systems</li>
                    <li>Classify data appropriately and protect it according to its sensitivity</li>
                    <li>Keep all devices and software updated with security patches</li>
                    <li>Be vigilant against phishing and social engineering attacks</li>
                    <li>Use VPN when working outside the office network</li>
                    <li>Report security incidents immediately to minimize damage</li>
                    <li>Follow compliance requirements for client data protection</li>
                    <li>Participate actively in ongoing security training and awareness</li>
                </ul>
            </div>
        '
    ],
    5 => [
        'title' => 'Working with Clients',
        'duration' => '28 minutes',
        'quiz_questions' => 6,
        'content' => '
            <h2>🤝 Excellence in Client Relations</h2>
            <p>Our clients are the lifeblood of Nexi Hub. They trust us with their most important business initiatives, and how we interact with them directly impacts not only project success but also our company\'s reputation and growth. Every interaction you have with a client reflects on the entire Nexi Hub brand.</p>
            
            <h3>🎯 Understanding Client Relationships</h3>
            <p>Client relationships at Nexi Hub go beyond simple vendor-customer transactions. We position ourselves as strategic partners who understand our clients\' businesses and contribute to their long-term success.</p>
            
            <h4>🏆 The Nexi Hub Client Experience</h4>
            <ul>
                <li><strong>Consultative Approach:</strong> We don\'t just execute requests; we provide strategic guidance</li>
                <li><strong>Proactive Communication:</strong> We anticipate needs and communicate before problems arise</li>
                <li><strong>Transparent Processes:</strong> Clients always know project status and what to expect next</li>
                <li><strong>Continuous Value:</strong> Every interaction should provide value beyond the immediate task</li>
                <li><strong>Long-term Thinking:</strong> We make decisions that benefit the client\'s long-term success</li>
            </ul>
            
            <h3>💼 Professional Communication Standards</h3>
            <p>Professional communication builds trust and credibility. Every email, call, and meeting should reflect our commitment to excellence.</p>
            
            <h4>📧 Email Communication Best Practices</h4>
            <ul>
                <li><strong>Response Time:</strong> Acknowledge emails within 4 hours during business hours</li>
                <li><strong>Subject Lines:</strong> Clear, specific, and action-oriented</li>
                <li><strong>Structure:</strong> Professional greeting, clear body, appropriate closing</li>
                <li><strong>Tone:</strong> Professional but warm, confident but not arrogant</li>
                <li><strong>Proofreading:</strong> Always proofread before sending</li>
                <li><strong>Attachments:</strong> Clearly labeled and relevant to the conversation</li>
            </ul>
            
            <p><strong>Email Template Example:</strong></p>
            <div class="code-block">
                <strong>Subject:</strong> [Project Name] Update - Next Steps Required<br><br>
                <strong>Hi [Client Name],</strong><br><br>
                I hope this email finds you well. I wanted to update you on our progress with [specific deliverable] and get your input on the next phase.<br><br>
                <strong>What we\'ve completed:</strong><br>
                • [Specific accomplishment with brief detail]<br>
                • [Another accomplishment]<br><br>
                <strong>What we need from you:</strong><br>
                • [Specific request with deadline]<br>
                • [Another request if applicable]<br><br>
                <strong>Next steps:</strong><br>
                • [What we\'ll do next]<br>
                • [Timeline for completion]<br><br>
                Please let me know if you have any questions or concerns. I\'m happy to schedule a call to discuss any aspects in more detail.<br><br>
                <strong>Best regards,</strong><br>
                [Your name]<br>
                [Your title]<br>
                [Contact information]
            </div>
            
            <h4>📞 Phone and Video Call Etiquette</h4>
            <ul>
                <li><strong>Preparation:</strong> Review agenda and materials beforehand</li>
                <li><strong>Punctuality:</strong> Join calls 2-3 minutes early</li>
                <li><strong>Environment:</strong> Professional background, good lighting, minimal distractions</li>
                <li><strong>Speaking:</strong> Clear, measured pace; avoid filler words</li>
                <li><strong>Listening:</strong> Active listening with appropriate acknowledgments</li>
                <li><strong>Follow-up:</strong> Send summary and action items within 24 hours</li>
            </ul>
            
            <h3>⏰ Setting and Managing Expectations</h3>
            <p>Clear expectations prevent misunderstandings and build trust. Clients appreciate transparency about what they can expect and when.</p>
            
            <h4>📋 Project Expectation Setting</h4>
            <ul>
                <li><strong>Scope Definition:</strong> Clearly define what is and isn\'t included</li>
                <li><strong>Timeline Communication:</strong> Provide realistic timelines with buffer for revisions</li>
                <li><strong>Milestone Mapping:</strong> Break projects into clear phases with deliverables</li>
                <li><strong>Communication Schedule:</strong> Establish regular check-in cadence</li>
                <li><strong>Change Process:</strong> Explain how scope changes will be handled</li>
            </ul>
            
            <h4>📈 Progress Reporting</h4>
            <ul>
                <li><strong>Regular Updates:</strong> Weekly progress emails for active projects</li>
                <li><strong>Status Indicators:</strong> Use green/yellow/red status system</li>
                <li><strong>Issue Transparency:</strong> Communicate challenges early with proposed solutions</li>
                <li><strong>Celebration of Wins:</strong> Highlight completed milestones and achievements</li>
                <li><strong>Next Steps:</strong> Always communicate what happens next</li>
            </ul>
            
            <h3>⚡ Prompt and Reliable Response</h3>
            <p>Responsiveness demonstrates respect for our clients\' time and priorities. Quick responses can often prevent small issues from becoming larger problems.</p>
            
            <h4>⏰ Response Time Standards</h4>
            <ul>
                <li><strong>Emergency Issues:</strong> Within 1 hour during business hours</li>
                <li><strong>Urgent Questions:</strong> Within 2 hours during business hours</li>
                <li><strong>General Inquiries:</strong> Within 4 hours during business hours</li>
                <li><strong>Project Updates:</strong> Within 24 hours</li>
                <li><strong>Proposals/Estimates:</strong> Within 2-3 business days</li>
            </ul>
            
            <h4>🔄 When You Can\'t Respond Immediately</h4>
            <ul>
                <li><strong>Acknowledgment:</strong> Send quick acknowledgment that you received the message</li>
                <li><strong>Timeline:</strong> Provide realistic timeline for full response</li>
                <li><strong>Interim Updates:</strong> If research is needed, provide interim updates</li>
                <li><strong>Alternative Contact:</strong> Provide backup contact for urgent matters</li>
            </ul>
            
            <h3>🚀 Delivering on Promises</h3>
            <p>Trust is built through consistent delivery on commitments. Under-promise and over-deliver whenever possible.</p>
            
            <h4>✅ Commitment Management</h4>
            <ul>
                <li><strong>Realistic Promises:</strong> Only commit to what you can reliably deliver</li>
                <li><strong>Buffer Time:</strong> Build in contingency time for unexpected issues</li>
                <li><strong>Early Warning:</strong> Communicate potential delays as soon as they\'re identified</li>
                <li><strong>Alternative Solutions:</strong> Offer alternatives when original plans aren\'t feasible</li>
                <li><strong>Documentation:</strong> Keep written records of all commitments made</li>
            </ul>
            
            <h4>📊 Quality Assurance</h4>
            <ul>
                <li><strong>Internal Review:</strong> All deliverables reviewed internally before client presentation</li>
                <li><strong>Testing:</strong> Thorough testing of all functionality before delivery</li>
                <li><strong>Documentation:</strong> Clear documentation accompanying all deliverables</li>
                <li><strong>Training:</strong> Provide necessary training for client team members</li>
                <li><strong>Support:</strong> Ensure ongoing support processes are in place</li>
            </ul>
            
            <h3>📢 Keeping Clients Informed</h3>
            <p>Regular communication prevents surprises and demonstrates our commitment to transparency.</p>
            
            <h4>📅 Communication Cadence</h4>
            <ul>
                <li><strong>Daily:</strong> Internal team standups (client not included but informed of outcomes)</li>
                <li><strong>Weekly:</strong> Progress updates to client via email or brief call</li>
                <li><strong>Bi-weekly:</strong> Detailed project review meetings</li>
                <li><strong>Monthly:</strong> Strategic review and planning sessions</li>
                <li><strong>As-needed:</strong> Issue escalation or urgent updates</li>
            </ul>
            
            <h4>📈 Progress Communication Template</h4>
            <div class="code-block">
                <strong>Project Status: [Green/Yellow/Red]</strong><br><br>
                <strong>Completed This Period:</strong><br>
                • [Specific accomplishment with impact]<br>
                • [Another accomplishment]<br><br>
                <strong>In Progress:</strong><br>
                • [Current work with expected completion]<br>
                • [Another current task]<br><br>
                <strong>Coming Up:</strong><br>
                • [Next milestone with date]<br>
                • [Upcoming deliverable]<br><br>
                <strong>Blockers/Issues:</strong><br>
                • [Any obstacles and proposed solutions]<br><br>
                <strong>Client Action Items:</strong><br>
                • [What we need from client with deadlines]<br><br>
                <strong>Questions/Discussion Points:</strong><br>
                • [Items for next meeting or immediate clarification]
            </div>
            
            <h3>🤔 Handling Difficult Situations</h3>
            <p>Not every client interaction will be smooth. How we handle challenges determines whether relationships strengthen or deteriorate.</p>
            
            <h4>😟 When Clients Are Unhappy</h4>
            <ul>
                <li><strong>Listen Actively:</strong> Let them express their concerns fully before responding</li>
                <li><strong>Acknowledge:</strong> Validate their feelings even if you disagree with the facts</li>
                <li><strong>Take Responsibility:</strong> Own any mistakes on our part without making excuses</li>
                <li><strong>Propose Solutions:</strong> Come with multiple options to address their concerns</li>
                <li><strong>Follow Through:</strong> Implement agreed-upon solutions completely and promptly</li>
                <li><strong>Follow Up:</strong> Check back to ensure the client is satisfied with the resolution</li>
            </ul>
            
            <h4>⚠️ Scope Creep Management</h4>
            <ul>
                <li><strong>Document Everything:</strong> Keep detailed records of all requests and agreements</li>
                <li><strong>Clarify Impact:</strong> Explain how changes affect timeline and budget</li>
                <li><strong>Offer Options:</strong> Provide alternatives that work within original scope</li>
                <li><strong>Formal Approval:</strong> Get written approval for scope changes</li>
                <li><strong>Communication:</strong> Keep all stakeholders informed of changes</li>
            </ul>
            
            <h4>🔄 Change Request Process</h4>
            <ol>
                <li><strong>Document Request:</strong> Write down exactly what the client is asking for</li>
                <li><strong>Assess Impact:</strong> Determine effects on timeline, budget, and other deliverables</li>
                <li><strong>Propose Solutions:</strong> Offer 2-3 options with different trade-offs</li>
                <li><strong>Get Approval:</strong> Obtain written approval before proceeding</li>
                <li><strong>Update Plans:</strong> Modify project plans and communicate changes to team</li>
                <li><strong>Track Changes:</strong> Maintain change log for future reference</li>
            </ol>
            
            <h3>🎯 Client Success Metrics</h3>
            <p>We measure our success by our clients\' success. Understanding and tracking the right metrics helps us continuously improve.</p>
            
            <h4>📊 Key Performance Indicators</h4>
            <ul>
                <li><strong>Client Satisfaction Scores:</strong> Regular survey feedback</li>
                <li><strong>Project Delivery:</strong> On-time, on-budget completion rates</li>
                <li><strong>Response Times:</strong> Average time to respond to client communications</li>
                <li><strong>Issue Resolution:</strong> Time to resolve client concerns</li>
                <li><strong>Repeat Business:</strong> Percentage of clients who return for additional projects</li>
                <li><strong>Referrals:</strong> Number of new clients from existing client referrals</li>
            </ul>
            
            <h4>🔄 Continuous Improvement</h4>
            <ul>
                <li><strong>Post-Project Reviews:</strong> Conduct lessons learned sessions after each project</li>
                <li><strong>Client Feedback:</strong> Regular solicitation of client feedback and suggestions</li>
                <li><strong>Process Updates:</strong> Update procedures based on learnings and feedback</li>
                <li><strong>Team Training:</strong> Ongoing training based on identified improvement areas</li>
                <li><strong>Best Practice Sharing:</strong> Share successful approaches across the team</li>
            </ul>
            
            <h3>🌟 Building Long-term Partnerships</h3>
            <p>Our goal isn\'t just project completion - it\'s building lasting partnerships that provide ongoing value to our clients.</p>
            
            <h4>🤝 Partnership Mindset</h4>
            <ul>
                <li><strong>Strategic Thinking:</strong> Consider client\'s long-term business goals in all recommendations</li>
                <li><strong>Proactive Suggestions:</strong> Identify opportunities for improvement even outside current scope</li>
                <li><strong>Industry Expertise:</strong> Share relevant industry knowledge and trends</li>
                <li><strong>Network Connections:</strong> Make valuable introductions when appropriate</li>
                <li><strong>Thought Leadership:</strong> Share insights through content and presentations</li>
            </ul>
            
            <h4>📈 Value-Added Services</h4>
            <ul>
                <li><strong>Performance Monitoring:</strong> Regular check-ins on delivered solutions</li>
                <li><strong>Optimization Suggestions:</strong> Ongoing recommendations for improvement</li>
                <li><strong>Training and Support:</strong> Helping client teams become more self-sufficient</li>
                <li><strong>Strategic Planning:</strong> Contributing to client\'s technology roadmap</li>
                <li><strong>Industry Updates:</strong> Keeping clients informed of relevant developments</li>
            </ul>
            
            <div class="key-points">
                <h4>🎯 Key Takeaways from This Module:</h4>
                <ul>
                    <li>Every client interaction reflects on the entire Nexi Hub brand</li>
                    <li>Professional communication builds trust and credibility</li>
                    <li>Set clear expectations and consistently deliver on promises</li>
                    <li>Respond promptly to all client communications</li>
                    <li>Keep clients informed with regular, transparent updates</li>
                    <li>Handle difficult situations with empathy and solution-focused thinking</li>
                    <li>Manage scope changes through formal processes</li>
                    <li>Focus on building long-term partnerships, not just completing projects</li>
                    <li>Continuously seek ways to add value beyond the immediate scope</li>
                </ul>
            </div>
        '
    ],
    6 => [
        'title' => 'Tools & Technologies',
        'duration' => '25 minutes',
        'quiz_questions' => 6,
        'content' => '
            <h2>🛠️ Nexi Hub Technology Stack</h2>
            <p>At Nexi Hub, we use cutting-edge tools and technologies to deliver exceptional results for our clients. Understanding our technology stack and how to use these tools effectively is essential for your success and our collective productivity. This module covers the core tools you\'ll use daily and the best practices for each.</p>
            
            <h3>💻 Development Environment</h3>
            <p>Our development environment is designed for efficiency, collaboration, and consistency across all projects and team members.</p>
            
            <h4>🔧 Primary Development Tools</h4>
            
            <p><strong>Visual Studio Code (VS Code)</strong></p>
            <ul>
                <li><strong>Why We Use It:</strong> Lightweight, extensible, excellent for web development</li>
                <li><strong>Required Extensions:</strong>
                    <ul>
                        <li>Prettier - Code formatter</li>
                        <li>ESLint - JavaScript linting</li>
                        <li>Live Server - Local development server</li>
                        <li>GitLens - Enhanced Git capabilities</li>
                        <li>Thunder Client - API testing</li>
                        <li>Auto Rename Tag - HTML/XML tag management</li>
                    </ul>
                </li>
                <li><strong>Configuration:</strong> Use company settings sync for consistent configuration</li>
                <li><strong>Shortcuts:</strong> Learn key shortcuts for productivity (Ctrl+P for file search, Ctrl+Shift+P for command palette)</li>
            </ul>
            
            <p><strong>Git & GitHub</strong></p>
            <ul>
                <li><strong>Version Control:</strong> All code must be version controlled in Git</li>
                <li><strong>Branching Strategy:</strong> Use feature branches with pull requests for all changes</li>
                <li><strong>Commit Guidelines:</strong> Write clear, descriptive commit messages</li>
                <li><strong>Code Reviews:</strong> All code must be reviewed before merging to main branch</li>
                <li><strong>Repository Structure:</strong> Follow company standards for repo organization</li>
            </ul>
            
            <p><strong>Docker</strong></p>
            <ul>
                <li><strong>Containerization:</strong> Use Docker for consistent development environments</li>
                <li><strong>Docker Compose:</strong> Multi-service applications use docker-compose.yml</li>
                <li><strong>Image Management:</strong> Use official base images when possible</li>
                <li><strong>Local Development:</strong> All projects should run via Docker for consistency</li>
            </ul>
            
            <h3>🌐 Web Development Stack</h3>
            <p>Our web development division uses modern, scalable technologies that provide excellent performance and maintainability.</p>
            
            <h4>🎨 Frontend Technologies</h4>
            <ul>
                <li><strong>HTML5:</strong> Semantic markup with accessibility considerations</li>
                <li><strong>CSS3:</strong> Modern CSS with Flexbox, Grid, and CSS Variables</li>
                <li><strong>Sass/SCSS:</strong> CSS preprocessing for better organization</li>
                <li><strong>JavaScript (ES6+):</strong> Modern JavaScript features and best practices</li>
                <li><strong>React:</strong> Component-based UI development for complex applications</li>
                <li><strong>Next.js:</strong> React framework for production applications</li>
                <li><strong>TypeScript:</strong> Type-safe JavaScript for larger projects</li>
                <li><strong>Tailwind CSS:</strong> Utility-first CSS framework for rapid development</li>
            </ul>
            
            <h4>⚙️ Backend Technologies</h4>
            <ul>
                <li><strong>Node.js:</strong> JavaScript runtime for backend development</li>
                <li><strong>PHP:</strong> Server-side scripting for web applications</li>
                <li><strong>Python:</strong> Data processing and API development</li>
                <li><strong>Express.js:</strong> Node.js web framework</li>
                <li><strong>Laravel:</strong> PHP framework for web applications</li>
                <li><strong>FastAPI:</strong> Python framework for API development</li>
            </ul>
            
            <h4>🗄️ Database Technologies</h4>
            <ul>
                <li><strong>MySQL:</strong> Primary relational database</li>
                <li><strong>PostgreSQL:</strong> Advanced relational database for complex projects</li>
                <li><strong>MongoDB:</strong> NoSQL database for flexible data structures</li>
                <li><strong>Redis:</strong> In-memory data store for caching and sessions</li>
            </ul>
            
            <h3>🤖 Bot Development Tools</h3>
            <p>Our bot division creates intelligent automation solutions using specialized tools and platforms.</p>
            
            <h4>🔧 Chatbot Development</h4>
            <ul>
                <li><strong>Dialogflow:</strong> Google\'s natural language processing platform</li>
                <li><strong>Microsoft Bot Framework:</strong> Enterprise-grade bot development</li>
                <li><strong>Rasa:</strong> Open-source conversational AI platform</li>
                <li><strong>OpenAI API:</strong> GPT integration for advanced conversational capabilities</li>
                <li><strong>Webhook Integration:</strong> Custom backend services for bot logic</li>
            </ul>
            
            <h4>⚡ Automation Tools</h4>
            <ul>
                <li><strong>Zapier:</strong> No-code automation between apps</li>
                <li><strong>Make (Integromat):</strong> Advanced automation scenarios</li>
                <li><strong>n8n:</strong> Open-source workflow automation</li>
                <li><strong>Python Scripts:</strong> Custom automation solutions</li>
                <li><strong>APIs:</strong> Direct integration with third-party services</li>
            </ul>
            
            <h3>📊 Analytics & Business Intelligence</h3>
            <p>Our Pulse division uses powerful analytics tools to transform data into actionable insights.</p>
            
            <h4>📈 Analytics Platforms</h4>
            <ul>
                <li><strong>Google Analytics 4:</strong> Web analytics and user behavior tracking</li>
                <li><strong>Google Tag Manager:</strong> Tag management and event tracking</li>
                <li><strong>Mixpanel:</strong> Product analytics and user journey tracking</li>
                <li><strong>Hotjar:</strong> User experience analytics and heatmaps</li>
                <li><strong>Adobe Analytics:</strong> Enterprise-level web analytics</li>
            </ul>
            
            <h4>📊 Data Visualization</h4>
            <ul>
                <li><strong>Tableau:</strong> Professional data visualization and dashboards</li>
                <li><strong>Power BI:</strong> Microsoft business intelligence platform</li>
                <li><strong>Google Data Studio:</strong> Free dashboard and reporting tool</li>
                <li><strong>D3.js:</strong> Custom data visualizations in web applications</li>
                <li><strong>Chart.js:</strong> Simple charts for web applications</li>
            </ul>
            
            <h4>🔍 Data Processing</h4>
            <ul>
                <li><strong>Python:</strong> Data analysis with pandas, numpy, scipy</li>
                <li><strong>R:</strong> Statistical analysis and data science</li>
                <li><strong>SQL:</strong> Database queries and data extraction</li>
                <li><strong>Apache Spark:</strong> Big data processing</li>
                <li><strong>ETL Tools:</strong> Data pipeline creation and management</li>
            </ul>
            
            <h3>💬 Communication & Collaboration</h3>
            <p>Effective communication tools keep our team connected and projects moving forward.</p>
            
            <h4>💭 Team Communication</h4>
            <ul>
                <li><strong>Discord:</strong>
                    <ul>
                        <li>Primary team communication platform</li>
                        <li>Organized channels for different topics</li>
                        <li>Voice channels for pair programming and meetings</li>
                        <li>Screen sharing for code reviews and troubleshooting</li>
                        <li>Bot integrations for automated notifications</li>
                    </ul>
                </li>
                <li><strong>Email:</strong>
                    <ul>
                        <li>Formal communications and client correspondence</li>
                        <li>Google Workspace for email and calendar</li>
                        <li>Shared calendars for team coordination</li>
                        <li>Meeting scheduling and invitations</li>
                    </ul>
                </li>
            </ul>
            
            <h4>📹 Video Conferencing</h4>
            <ul>
                <li><strong>Zoom:</strong>
                    <ul>
                        <li>Client meetings and presentations</li>
                        <li>Team meetings and standups</li>
                        <li>Screen sharing and recording capabilities</li>
                        <li>Breakout rooms for collaborative sessions</li>
                    </ul>
                </li>
                <li><strong>Google Meet:</strong>
                    <ul>
                        <li>Quick internal meetings</li>
                        <li>Integration with Google Calendar</li>
                        <li>Easy access via web browser</li>
                    </ul>
                </li>
            </ul>
            
            <h3>☁️ Cloud Infrastructure</h3>
            <p>We leverage cloud platforms for scalable, reliable hosting and services.</p>
            
            <h4>🌐 Hosting Platforms</h4>
            <ul>
                <li><strong>Amazon Web Services (AWS):</strong>
                    <ul>
                        <li>EC2 for virtual servers</li>
                        <li>S3 for file storage</li>
                        <li>RDS for managed databases</li>
                        <li>CloudFront for content delivery</li>
                        <li>Lambda for serverless functions</li>
                    </ul>
                </li>
                <li><strong>Google Cloud Platform:</strong>
                    <ul>
                        <li>Compute Engine for virtual machines</li>
                        <li>Cloud Storage for file hosting</li>
                        <li>Cloud SQL for managed databases</li>
                        <li>Cloud Functions for serverless computing</li>
                    </ul>
                </li>
                <li><strong>Vercel:</strong>
                    <ul>
                        <li>Frontend deployment and hosting</li>
                        <li>Next.js optimization</li>
                        <li>Edge functions and CDN</li>
                        <li>Git integration for automatic deployments</li>
                    </ul>
                </li>
            </ul>
            
            <h4>🔄 DevOps Tools</h4>
            <ul>
                <li><strong>GitHub Actions:</strong> CI/CD pipelines and automation</li>
                <li><strong>Docker Hub:</strong> Container image registry</li>
                <li><strong>Nginx:</strong> Web server and reverse proxy</li>
                <li><strong>PM2:</strong> Node.js process management</li>
                <li><strong>Cloudflare:</strong> DNS, CDN, and security services</li>
            </ul>
            
            <h3>📋 Project Management</h3>
            <p>Organized project management keeps our work on track and clients informed.</p>
            
            <h4>📊 Project Tracking</h4>
            <ul>
                <li><strong>Notion:</strong>
                    <ul>
                        <li>Project documentation and wikis</li>
                        <li>Team databases and templates</li>
                        <li>Knowledge base and procedures</li>
                        <li>Meeting notes and action items</li>
                    </ul>
                </li>
                <li><strong>Trello:</strong>
                    <ul>
                        <li>Kanban boards for task management</li>
                        <li>Client-facing project boards</li>
                        <li>Simple workflow visualization</li>
                        <li>Team collaboration on tasks</li>
                    </ul>
                </li>
                <li><strong>GitHub Projects:</strong>
                    <ul>
                        <li>Technical task management</li>
                        <li>Issue tracking and bug reports</li>
                        <li>Sprint planning and roadmaps</li>
                        <li>Integration with code repositories</li>
                    </ul>
                </li>
            </ul>
            
            <h3>🎨 Design Tools</h3>
            <p>Our design workflow ensures consistent, professional visual output.</p>
            
            <h4>🖌️ Design Software</h4>
            <ul>
                <li><strong>Figma:</strong>
                    <ul>
                        <li>UI/UX design and prototyping</li>
                        <li>Collaborative design reviews</li>
                        <li>Design system management</li>
                        <li>Developer handoff and specs</li>
                    </ul>
                </li>
                <li><strong>Adobe Creative Suite:</strong>
                    <ul>
                        <li>Photoshop for image editing</li>
                        <li>Illustrator for vector graphics</li>
                        <li>After Effects for animations</li>
                        <li>Premiere Pro for video editing</li>
                    </ul>
                </li>
                <li><strong>Canva:</strong>
                    <ul>
                        <li>Quick marketing materials</li>
                        <li>Social media graphics</li>
                        <li>Presentation templates</li>
                        <li>Simple design tasks</li>
                    </ul>
                </li>
            </ul>
            
            <h3>🔒 Security Tools</h3>
            <p>Security tools protect our work and client data throughout the development process.</p>
            
            <h4>🛡️ Security Software</h4>
            <ul>
                <li><strong>1Password:</strong> Team password management and secure sharing</li>
                <li><strong>VPN:</strong> Secure remote access to company resources</li>
                <li><strong>SSL Certificates:</strong> Let\'s Encrypt and commercial certificates</li>
                <li><strong>Code Scanning:</strong> GitHub security alerts and dependency scanning</li>
                <li><strong>Penetration Testing:</strong> Third-party security audits for critical projects</li>
            </ul>
            
            <h3>📚 Learning & Development</h3>
            <p>Continuous learning keeps our skills sharp and our solutions innovative.</p>
            
            <h4>🎓 Educational Platforms</h4>
            <ul>
                <li><strong>Udemy:</strong> Technical courses and certifications</li>
                <li><strong>Pluralsight:</strong> Technology training and skill assessments</li>
                <li><strong>LinkedIn Learning:</strong> Professional development courses</li>
                <li><strong>YouTube:</strong> Tutorial videos and conference talks</li>
                <li><strong>Documentation:</strong> Official docs for all technologies we use</li>
            </ul>
            
            <h3>⚡ Best Practices for Tool Usage</h3>
            <p>Getting the most out of our tools requires following established best practices.</p>
            
            <h4>🎯 General Guidelines</h4>
            <ul>
                <li><strong>Stay Updated:</strong> Keep all tools and software updated to latest stable versions</li>
                <li><strong>Learn Shortcuts:</strong> Invest time learning keyboard shortcuts for frequently used tools</li>
                <li><strong>Customize Wisely:</strong> Customize tools to improve productivity, but maintain team consistency</li>
                <li><strong>Documentation:</strong> Document custom configurations and tool setups</li>
                <li><strong>Backup:</strong> Regular backups of tool configurations and important data</li>
                <li><strong>Security:</strong> Follow security best practices for all tool access</li>
            </ul>
            
            <h4>🔧 Tool Integration</h4>
            <ul>
                <li><strong>Workflow Automation:</strong> Connect tools to reduce manual work</li>
                <li><strong>Single Sign-On:</strong> Use SSO where available to reduce password fatigue</li>
                <li><strong>Data Consistency:</strong> Ensure data flows correctly between connected tools</li>
                <li><strong>Notification Management:</strong> Configure notifications to avoid overwhelm while staying informed</li>
            </ul>
            
            <div class="key-points">
                <h4>🎯 Key Takeaways from This Module:</h4>
                <ul>
                    <li>VS Code, Git, and Docker are essential tools for all development work</li>
                    <li>Different technology stacks serve different client needs and project types</li>
                    <li>Communication tools like Discord and Zoom keep teams connected and productive</li>
                    <li>Cloud platforms provide scalable infrastructure for client projects</li>
                    <li>Project management tools ensure organized, transparent workflow</li>
                    <li>Design tools enable professional, consistent visual output</li>
                    <li>Security tools protect sensitive data and maintain client trust</li>
                    <li>Continuous learning through educational platforms keeps skills current</li>
                    <li>Following best practices maximizes tool effectiveness and team productivity</li>
                </ul>
            </div>
        '
    ],
    7 => [
        'title' => 'Final Assessment',
        'duration' => '15 minutes',
        'quiz_questions' => 10,
        'content' => '
            <h2>🎓 Final Assessment - Nexi Hub Comprehensive Review</h2>
            <p>Congratulations on completing the Nexi Hub E-Learning program! You\'ve covered essential knowledge about our company, values, processes, and tools. This final assessment will test your understanding of all the modules and ensure you\'re ready to excel as a Nexi Hub team member.</p>
            
            <h3>📋 Assessment Overview</h3>
            <div class="assessment-info">
                <h4>📊 Assessment Details:</h4>
                <ul>
                    <li><strong>Questions:</strong> 10 comprehensive multiple-choice questions</li>
                    <li><strong>Passing Score:</strong> 80% (8 out of 10 correct answers)</li>
                    <li><strong>Time Limit:</strong> No time limit - take your time to think carefully</li>
                    <li><strong>Attempts:</strong> You can retake the assessment if needed</li>
                    <li><strong>Content Coverage:</strong> Questions from all previous modules</li>
                </ul>
            </div>
            
            <h3>📚 What This Assessment Covers</h3>
            <p>The final assessment draws from all six previous modules to ensure comprehensive understanding:</p>
            
            <h4>🏢 Module 1: Introduction to Nexi Hub</h4>
            <ul>
                <li>Company mission and three core divisions</li>
                <li>Integrated approach to digital transformation</li>
                <li>Client-centric philosophy and business focus</li>
            </ul>
            
            <h4>💎 Module 2: Company Values & Culture</h4>
            <ul>
                <li>Four core values: Excellence, Innovation, Collaboration, Growth</li>
                <li>How values translate into daily work practices</li>
                <li>Cultural priorities and work environment</li>
            </ul>
            
            <h4>💬 Module 3: Communication Guidelines</h4>
            <ul>
                <li>Appropriate use of different communication channels</li>
                <li>Client communication standards and response times</li>
                <li>Professional communication best practices</li>
            </ul>
            
            <h4>🔒 Module 4: Data Protection & Security</h4>
            <ul>
                <li>Data classification levels and protection requirements</li>
                <li>Password security and two-factor authentication</li>
                <li>Security incident reporting and response</li>
            </ul>
            
            <h4>🤝 Module 5: Working with Clients</h4>
            <ul>
                <li>Professional communication standards</li>
                <li>Expectation setting and promise delivery</li>
                <li>Building long-term client partnerships</li>
            </ul>
            
            <h4>🛠️ Module 6: Tools & Technologies</h4>
            <ul>
                <li>Primary development tools and their purposes</li>
                <li>Communication and collaboration platforms</li>
                <li>Cloud infrastructure and security tools</li>
            </ul>
            
            <h3>💡 Study Tips for Success</h3>
            <p>To prepare for this assessment, we recommend:</p>
            
            <ul>
                <li><strong>Review Key Takeaways:</strong> Look back at the key takeaways section at the end of each module</li>
                <li><strong>Understand Applications:</strong> Focus on how concepts apply in real work situations</li>
                <li><strong>Remember Examples:</strong> Recall specific examples and scenarios discussed in each module</li>
                <li><strong>Think Holistically:</strong> Consider how different modules connect and support each other</li>
                <li><strong>Practice Application:</strong> Think about how you would apply these concepts in your role</li>
            </ul>
            
            <h3>🎯 Assessment Success Strategies</h3>
            
            <h4>📖 Before Starting</h4>
            <ul>
                <li>Ensure you have a quiet, distraction-free environment</li>
                <li>Review the key takeaways from each module</li>
                <li>Have confidence in the knowledge you\'ve gained</li>
                <li>Remember there\'s no time pressure - think carefully about each question</li>
            </ul>
            
            <h4>📝 During the Assessment</h4>
            <ul>
                <li>Read each question carefully and completely</li>
                <li>Consider all answer options before selecting</li>
                <li>Think about real-world applications of the concepts</li>
                <li>If unsure, eliminate obviously incorrect answers first</li>
                <li>Trust your understanding from the training modules</li>
            </ul>
            
            <h4>🔄 If You Need to Retake</h4>
            <ul>
                <li>Review the modules where you missed questions</li>
                <li>Focus on understanding concepts, not memorizing facts</li>
                <li>Consider how different topics connect to each other</li>
                <li>Don\'t hesitate to ask questions or seek clarification</li>
            </ul>
            
            <h3>🌟 After Completing the Assessment</h3>
            <p>Once you successfully pass this final assessment, you will have:</p>
            
            <ul>
                <li><strong>Comprehensive Understanding:</strong> Solid foundation in Nexi Hub\'s mission, values, and operations</li>
                <li><strong>Practical Knowledge:</strong> Understanding of tools, processes, and best practices for success</li>
                <li><strong>Professional Standards:</strong> Clear guidelines for client interaction and communication</li>
                <li><strong>Security Awareness:</strong> Knowledge to protect sensitive data and maintain client trust</li>
                <li><strong>Cultural Integration:</strong> Understanding of how to contribute to our positive company culture</li>
            </ul>
            
            <h3>📜 Certification and Next Steps</h3>
            <p>Upon successfully completing this assessment with a score of 80% or higher:</p>
            
            <ul>
                <li><strong>Certificate Generation:</strong> You\'ll be able to download your completion certificate</li>
                <li><strong>Progress Tracking:</strong> Your completion will be recorded in your learning profile</li>
                <li><strong>Team Integration:</strong> You\'ll be fully prepared to contribute effectively to Nexi Hub projects</li>
                <li><strong>Ongoing Learning:</strong> Continue to build on this foundation through daily work and advanced training</li>
            </ul>
            
            <h3>🚀 Ready to Excel at Nexi Hub</h3>
            <p>The knowledge you\'ve gained through this E-Learning program provides the foundation for success at Nexi Hub. Remember that learning is a continuous process, and we encourage you to:</p>
            
            <ul>
                <li>Apply these concepts in your daily work</li>
                <li>Ask questions when you need clarification</li>
                <li>Share your ideas and contribute to our culture of innovation</li>
                <li>Seek opportunities to grow and develop your skills further</li>
                <li>Help mentor new team members as they complete this same training</li>
            </ul>
            
            <div class="assessment-encouragement">
                <h3>💪 You\'ve Got This!</h3>
                <p>You\'ve put in the effort to learn about Nexi Hub comprehensively. Trust in your preparation, take your time with each question, and demonstrate the knowledge you\'ve gained. We\'re confident you\'ll succeed and make valuable contributions to our team!</p>
            </div>
            
            <div class="key-points">
                <h4>🎯 Final Assessment Key Points:</h4>
                <ul>
                    <li>10 questions covering all six previous modules</li>
                    <li>80% passing score required (8 out of 10 correct)</li>
                    <li>No time limit - take your time to think carefully</li>
                    <li>You can retake if needed - focus on understanding concepts</li>
                    <li>Successful completion earns your Nexi Hub training certificate</li>
                    <li>This assessment demonstrates readiness to contribute effectively to our team</li>
                </ul>
            </div>
        '
    ]
];

$current_module = $modules[$module_id];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($current_module['title']); ?> - E-Learning Portal</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e64f21 0%, #ff6b35 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #e64f21 0%, #ff6b35 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0 0 10px 0;
            font-size: 2rem;
        }

        .header .subtitle {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .content {
            padding: 30px;
        }

        .module-nav {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-btn {
            background: linear-gradient(135deg, #e64f21 0%, #ff6b35 100%);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: transform 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }

        .nav-btn.disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .module-content {
            line-height: 1.6;
            color: #333;
        }

        .module-content h2 {
            color: #e64f21;
            margin-bottom: 20px;
            font-size: 2rem;
        }

        .module-content h3 {
            color: #444;
            margin: 25px 0 15px 0;
            font-size: 1.5rem;
        }

        .module-content h4 {
            color: #555;
            margin: 20px 0 10px 0;
            font-size: 1.2rem;
        }

        .module-content p {
            margin-bottom: 15px;
            font-size: 16px;
        }

        .module-content ul, .module-content ol {
            margin: 15px 0 15px 30px;
        }

        .module-content li {
            margin-bottom: 8px;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 25px 0;
        }

        .value-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #e64f21;
        }

        .value-card h3 {
            color: #e64f21;
            margin-bottom: 10px;
        }

        .security-checklist {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }

        .tools-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 25px 0;
        }

        .tool-card {
            background: white;
            border: 2px solid #eee;
            border-radius: 10px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .tool-card:hover {
            border-color: #e64f21;
            box-shadow: 0 5px 15px rgba(230, 79, 33, 0.1);
        }

        .assessment-info {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            padding: 20px;
            border-radius: 10px;
            margin: 25px 0;
            border-left: 4px solid #2196f3;
        }

        .mission-box {
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
            padding: 20px;
            border-radius: 10px;
            margin: 25px 0;
            border-left: 4px solid #e64f21;
            text-align: center;
        }

        .mission-box h4 {
            color: #e64f21;
            margin-bottom: 15px;
        }

        .key-points {
            background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
            padding: 20px;
            border-radius: 10px;
            margin: 25px 0;
            border-left: 4px solid #9c27b0;
        }

        .key-points h4 {
            color: #9c27b0;
            margin-bottom: 15px;
        }

        .quiz-section {
            background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
            padding: 30px;
            border-radius: 15px;
            margin: 30px 0;
            text-align: center;
            border: 2px solid #4caf50;
        }

        .quiz-section h3 {
            color: #2e7d32;
            margin-bottom: 15px;
            font-size: 1.8rem;
        }

        .quiz-section p {
            color: #388e3c;
            font-size: 1.1rem;
            margin-bottom: 25px;
        }

        .complete-btn {
            background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        }

        .complete-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
        }

        .complete-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .completed-section {
            background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
            padding: 30px;
            border-radius: 15px;
            margin: 30px 0;
            text-align: center;
            border: 2px solid #4caf50;
        }

        .completion-message {
            color: #2e7d32;
        }

        .completion-message i {
            font-size: 3rem;
            margin-bottom: 15px;
            display: block;
        }

        .completion-message h3 {
            margin-bottom: 10px;
            font-size: 1.8rem;
        }

        .quiz-info {
            background: #fff3cd;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
        }

        .quiz-info i {
            color: #856404;
            margin-right: 10px;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 10px;
            }

            .content {
                padding: 20px;
            }

            .module-nav {
                flex-direction: column;
                gap: 15px;
            }

            .values-grid {
                grid-template-columns: 1fr;
            }

            .tools-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?php echo htmlspecialchars($current_module['title']); ?></h1>
            <div class="subtitle">Module <?php echo $module_id; ?> of 7 • <?php echo $current_module['duration']; ?></div>
        </div>

        <div class="content">
            <div class="module-nav">
                <div>
                    <a href="/elearning/" class="nav-btn">
                        <i class="fas fa-arrow-left"></i>
                        Back to Dashboard
                    </a>
                </div>
                <div>
                    <?php if ($module_id > 1): ?>
                        <a href="/elearning/module.php?id=<?php echo $module_id - 1; ?>" class="nav-btn">
                            <i class="fas fa-chevron-left"></i>
                            Previous
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($module_id < 7 && $is_completed): ?>
                        <a href="/elearning/module.php?id=<?php echo $module_id + 1; ?>" class="nav-btn">
                            Next
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="module-content">
                <?php echo $current_module['content']; ?>
            </div>
            
            <?php if (!$is_completed): ?>
            <div class="quiz-section">
                <h3><i class="fas fa-question-circle"></i> Knowledge Check</h3>
                <p>Complete this quick quiz to finish the module and progress to the next one.</p>
                
                <div id="quiz-container">
                    <div class="quiz-info">
                        <p><i class="fas fa-info-circle"></i> This module includes <?php echo $current_module['quiz_questions']; ?> quiz questions to test your understanding.</p>
                        <p>Answer all questions correctly to complete the module.</p>
                    </div>
                    <div id="quiz-questions" style="display:none;">
                        <!-- Quiz questions will be loaded here -->
                    </div>
                </div>
                
                <div class="quiz-actions">
                    <button id="start-quiz-btn" class="complete-btn" onclick="startQuiz()">
                        <i class="fas fa-play"></i>
                        Start Quiz
                    </button>
                    <button id="complete-module-btn" class="complete-btn" onclick="completeModule(<?php echo $module_id; ?>)" style="display:none;">
                        <i class="fas fa-check"></i>
                        Complete Module
                    </button>
                </div>
            </div>
            <?php else: ?>
            <div class="completed-section">
                <div class="completion-message">
                    <i class="fas fa-check-circle"></i>
                    <h3>Module Completed!</h3>
                    <p>You completed this module on <?php echo date('F j, Y', strtotime($module_progress['completed_at'])); ?></p>
                    <p>Quiz Score: <strong><?php echo $module_progress['quiz_score']; ?>%</strong></p>
                </div>
                
                <div class="navigation-actions">
                    <?php if ($module_id < 7): ?>
                    <a href="/elearning/module.php?id=<?php echo $module_id + 1; ?>" class="complete-btn">
                        <i class="fas fa-arrow-right"></i>
                        Next Module
                    </a>
                    <?php else: ?>
                    <a href="/elearning/" class="complete-btn">
                        <i class="fas fa-trophy"></i>
                        View Certificate
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        let quizQuestions = [];
        let currentQuestionIndex = 0;
        let userAnswers = [];
        let quizScore = 0;

        // Define quiz questions for each module
        const moduleQuizzes = {
            1: [
                {
                    question: "What year was Nexi Hub founded?",
                    options: ["2018", "2019", "2020", "2021"],
                    correct: 1
                },
                {
                    question: "How many core service divisions does Nexi Hub have?",
                    options: ["2", "3", "4", "5"],
                    correct: 1
                },
                {
                    question: "Which division focuses on business intelligence?",
                    options: ["Nexi Web", "Nexi Bot", "Nexi Pulse", "Nexi Analytics"],
                    correct: 2
                },
                {
                    question: "What makes Nexi Hub different from traditional agencies?",
                    options: ["Lower prices", "Faster delivery", "Integrated approach", "More developers"],
                    correct: 2
                },
                {
                    question: "What is Nexi Hub's primary philosophy?",
                    options: ["Technology first", "Client-centric solutions", "Profit maximization", "Fast delivery"],
                    correct: 1
                }
            ],
            2: [
                {
                    question: "Which of the following is NOT one of Nexi Hub's four core values?",
                    options: ["Excellence", "Innovation", "Competition", "Collaboration"],
                    correct: 2
                },
                {
                    question: "What does 'Excellence' mean at Nexi Hub?",
                    options: ["Being perfect", "Continuous improvement and exceeding expectations", "Working faster than competitors", "Having the most certifications"],
                    correct: 1
                },
                {
                    question: "How does Nexi Hub encourage Innovation?",
                    options: ["By avoiding new technologies", "Through early adoption and creative problem-solving", "By copying competitors", "Only in special projects"],
                    correct: 1
                },
                {
                    question: "What is emphasized about Collaboration at Nexi Hub?",
                    options: ["Working alone is preferred", "Cross-functional teams and knowledge sharing", "Only senior members collaborate", "Collaboration slows down projects"],
                    correct: 1
                },
                {
                    question: "How does Nexi Hub support Growth for team members?",
                    options: ["No growth opportunities", "Learning stipend and career pathways", "Growth is individual responsibility", "Only technical training"],
                    correct: 1
                }
            ],
            3: [
                {
                    question: "What is the primary tool for daily team communication at Nexi Hub?",
                    options: ["Email", "Discord", "Phone calls", "Text messages"],
                    correct: 1
                },
                {
                    question: "What is the expected response time for urgent client issues?",
                    options: ["Within 1 hour", "Within 4 hours", "Within 24 hours", "Within 1 week"],
                    correct: 0
                },
                {
                    question: "When should email be used instead of Discord?",
                    options: ["Never", "For all communications", "For formal communications and client correspondence", "Only for internal messages"],
                    correct: 2
                },
                {
                    question: "What should you do when you receive a client message but can't respond immediately?",
                    options: ["Ignore it until later", "Send a quick acknowledgment with timeline for full response", "Ask someone else to respond", "Wait until you have the complete answer"],
                    correct: 1
                },
                {
                    question: "In crisis communication, what is the first step?",
                    options: ["Write a detailed report", "Alert team lead and affected stakeholders", "Fix the problem first", "Schedule a meeting"],
                    correct: 1
                }
            ],
            4: [
                {
                    question: "What is the minimum recommended length for passwords at Nexi Hub?",
                    options: ["8 characters", "12 characters", "14 characters", "20 characters"],
                    correct: 2
                },
                {
                    question: "Which 2FA method is considered most secure?",
                    options: ["SMS/Phone", "Email codes", "Authenticator apps", "Hardware security keys"],
                    correct: 3
                },
                {
                    question: "What should you do if you suspect a security incident?",
                    options: ["Try to fix it yourself first", "Report immediately to IT security team", "Wait to see if it gets worse", "Ask colleagues what they think"],
                    correct: 1
                },
                {
                    question: "Which data classification requires encryption and access logs?",
                    options: ["Public", "Internal Use", "Confidential", "Highly Confidential"],
                    correct: 3
                },
                {
                    question: "When is VPN usage required?",
                    options: ["Never", "Only for sensitive projects", "When working from public locations", "Only on weekends"],
                    correct: 2
                },
                {
                    question: "What constitutes a phishing email red flag?",
                    options: ["Professional grammar", "Expected attachments", "Urgent language demanding immediate action", "Clear sender identification"],
                    correct: 2
                }
            ],
            5: [
                {
                    question: "What is the expected response time for general client inquiries?",
                    options: ["Within 1 hour", "Within 4 hours", "Within 24 hours", "Within 1 week"],
                    correct: 1
                },
                {
                    question: "How should you handle scope creep?",
                    options: ["Just do the extra work", "Refuse any changes", "Document the request and assess impact with formal approval", "Charge extra without discussion"],
                    correct: 2
                },
                {
                    question: "What does a 'Red Status' project mean?",
                    options: ["Project completed", "Minor issues", "Significant problems requiring immediate discussion", "Project cancelled"],
                    correct: 2
                },
                {
                    question: "When a client is unhappy, what should you do first?",
                    options: ["Defend your work", "Listen actively and let them express concerns", "Offer a discount", "Escalate immediately"],
                    correct: 1
                },
                {
                    question: "What mindset should guide long-term client relationships?",
                    options: ["Transaction-focused", "Strategic partnership", "Cost minimization", "Quick delivery"],
                    correct: 1
                },
                {
                    question: "How often should you provide progress updates to clients?",
                    options: ["Only when asked", "Weekly for active projects", "Monthly only", "At project completion"],
                    correct: 1
                }
            ],
            6: [
                {
                    question: "What is the primary code editor used at Nexi Hub?",
                    options: ["Sublime Text", "Atom", "Visual Studio Code", "Notepad++"],
                    correct: 2
                },
                {
                    question: "Which tool is used for version control?",
                    options: ["SVN", "Git & GitHub", "Mercurial", "CVS"],
                    correct: 1
                },
                {
                    question: "What is Docker used for at Nexi Hub?",
                    options: ["Email management", "Consistent development environments", "Video conferencing", "File storage"],
                    correct: 1
                },
                {
                    question: "Which platform is preferred for team password management?",
                    options: ["LastPass", "1Password", "Bitwarden", "Chrome passwords"],
                    correct: 1
                },
                {
                    question: "What is the primary communication platform for daily team discussions?",
                    options: ["Email", "Discord", "Slack", "Teams"],
                    correct: 1
                },
                {
                    question: "Which cloud platform is commonly used for hosting at Nexi Hub?",
                    options: ["DigitalOcean only", "AWS and Google Cloud", "Microsoft Azure only", "Local servers only"],
                    correct: 1
                }
            ],
            7: [
                {
                    question: "How many core divisions does Nexi Hub have and what are they?",
                    options: ["2: Web and Bot", "3: Web, Bot, and Pulse", "4: Web, Bot, Pulse, and Mobile", "5: Web, Bot, Pulse, Mobile, and Cloud"],
                    correct: 1
                },
                {
                    question: "Which value emphasizes continuous improvement and exceeding expectations?",
                    options: ["Innovation", "Excellence", "Collaboration", "Growth"],
                    correct: 1
                },
                {
                    question: "What is the expected response time for urgent client issues during business hours?",
                    options: ["Within 4 hours", "Within 2 hours", "Within 1 hour", "Within 30 minutes"],
                    correct: 2
                },
                {
                    question: "What is the minimum recommended password length?",
                    options: ["12 characters", "14 characters", "16 characters", "18 characters"],
                    correct: 1
                },
                {
                    question: "Which 2FA method provides the highest security?",
                    options: ["SMS", "Email codes", "Authenticator apps", "Hardware security keys"],
                    correct: 3
                },
                {
                    question: "When should you escalate a client issue to your manager?",
                    options: ["Immediately for any issue", "Never escalate", "When you can't resolve it or client requests escalation", "Only on Fridays"],
                    correct: 2
                },
                {
                    question: "What does 'Highly Confidential' data classification require?",
                    options: ["Standard password protection", "Encryption and limited access on need-to-know basis", "Public sharing allowed", "No special requirements"],
                    correct: 1
                },
                {
                    question: "Which tool is used for containerization and consistent development environments?",
                    options: ["Git", "Docker", "Discord", "Figma"],
                    correct: 1
                },
                {
                    question: "How should scope changes be handled?",
                    options: ["Just implement them", "Refuse all changes", "Document, assess impact, and get written approval", "Charge double"],
                    correct: 2
                },
                {
                    question: "What is Nexi Hub's approach to client relationships?",
                    options: ["Simple vendor-customer transactions", "Strategic partnerships for long-term success", "Minimum viable service", "Focus only on technical delivery"],
                    correct: 1
                }
            ]
        };

        function startQuiz() {
            const moduleId = <?php echo $module_id; ?>;
            quizQuestions = moduleQuizzes[moduleId] || [];
            
            if (quizQuestions.length === 0) {
                alert('Quiz questions not available for this module.');
                return;
            }

            document.getElementById('start-quiz-btn').style.display = 'none';
            document.querySelector('.quiz-info').style.display = 'none';
            document.getElementById('quiz-questions').style.display = 'block';
            
            currentQuestionIndex = 0;
            userAnswers = [];
            showQuestion();
        }

        function showQuestion() {
            if (currentQuestionIndex >= quizQuestions.length) {
                showQuizResults();
                return;
            }

            const question = quizQuestions[currentQuestionIndex];
            const questionsContainer = document.getElementById('quiz-questions');
            
            questionsContainer.innerHTML = `
                <div class="quiz-question">
                    <h4>Question ${currentQuestionIndex + 1} of ${quizQuestions.length}</h4>
                    <p><strong>${question.question}</strong></p>
                    <div class="quiz-options">
                        ${question.options.map((option, index) => `
                            <label class="quiz-option">
                                <input type="radio" name="quiz-answer" value="${index}">
                                <span>${option}</span>
                            </label>
                        `).join('')}
                    </div>
                    <div class="quiz-navigation">
                        <button onclick="submitAnswer()" class="complete-btn">
                            ${currentQuestionIndex === quizQuestions.length - 1 ? 'Finish Quiz' : 'Next Question'}
                        </button>
                    </div>
                </div>
            `;
        }

        function submitAnswer() {
            const selectedAnswer = document.querySelector('input[name="quiz-answer"]:checked');
            if (!selectedAnswer) {
                alert('Please select an answer before continuing.');
                return;
            }

            const answerIndex = parseInt(selectedAnswer.value);
            userAnswers.push(answerIndex);
            
            currentQuestionIndex++;
            showQuestion();
        }

        function showQuizResults() {
            // Calculate score
            quizScore = 0;
            for (let i = 0; i < quizQuestions.length; i++) {
                if (userAnswers[i] === quizQuestions[i].correct) {
                    quizScore++;
                }
            }

            const percentage = Math.round((quizScore / quizQuestions.length) * 100);
            
            document.getElementById('quiz-questions').innerHTML = `
                <div class="quiz-results">
                    <h4>Quiz Complete!</h4>
                    <p>You scored <strong>${quizScore}/${quizQuestions.length}</strong> (${percentage}%)</p>
                    ${percentage >= 80 ? 
                        '<p style="color: green;"><i class="fas fa-check"></i> Congratulations! You passed the quiz.</p>' : 
                        '<p style="color: red;"><i class="fas fa-times"></i> You need 80% to pass. Please review the content and try again.</p>'
                    }
                </div>
            `;

            if (percentage >= 80) {
                document.getElementById('complete-module-btn').style.display = 'inline-flex';
            } else {
                setTimeout(() => {
                    document.getElementById('start-quiz-btn').style.display = 'inline-flex';
                    document.getElementById('start-quiz-btn').innerHTML = '<i class="fas fa-redo"></i> Retake Quiz';
                    document.getElementById('quiz-questions').style.display = 'none';
                    document.querySelector('.quiz-info').style.display = 'block';
                }, 3000);
            }
        }

        function completeModule(moduleId) {
            console.log('Starting module completion for module:', moduleId);
            
            // Show loading state
            const btn = document.getElementById('complete-module-btn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            btn.disabled = true;
            
            const finalScore = Math.round((quizScore / quizQuestions.length) * 100);
            
            fetch('complete-module.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    module_id: moduleId,
                    quiz_score: finalScore
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    alert('🎉 Module completed successfully!\n\nQuiz Score: ' + finalScore + '%\n\nGreat job! You can now proceed to the next module.');
                    location.reload();
                } else {
                    alert('Error completing module: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error. Please try again. Error: ' + error.message);
            })
            .finally(() => {
                // Restore button state
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }
    </script>
</body>
</html>
