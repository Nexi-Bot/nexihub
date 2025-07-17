<?php
require_once __DIR__ . '/config/config.php';

try {
    // Connect to database
    if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $db = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]);
    } else {
        $db = new PDO("sqlite:" . __DIR__ . "/database/nexihub.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    $newCodeOfConduct = '# NEXI BOT LTD - STAFF CODE OF CONDUCT

**Company Registration Number:** 16502958  
**ICO Registration Number:** ZB910034  
**Document Version:** 1.0  
**Effective Date:** 05/07/2025  
**Review Date:** 05/07/2026

---

## 1. INTRODUCTION

This Staff Code of Conduct ("Code") establishes the behavioral and professional standards expected of all employees, contractors, volunteers, and representatives of Nexi Bot LTD ("Company," "we," "us," or "our"). This Code applies to all staff members regardless of age, position, or employment status.

### 1.1 Purpose
This Code aims to:
• Establish clear professional and ethical standards
• Protect the Company\'s reputation and values
• Ensure compliance with legal and regulatory requirements
• Create a safe and respectful workplace for all employees
• Provide guidance for decision-making and conduct

### 1.2 Scope
This Code applies to:
• All voluntary employees and contributors (including those under 18 years of age)
• Shareholders and directors
• Contractors and consultants (when applicable)
• All work-related activities conducted online
• Digital interactions representing the Company

---

## 2. CORE VALUES AND PRINCIPLES

### 2.1 Company Values
All staff members are expected to uphold our core values:

**Integrity:** Acting honestly and transparently in all dealings  
**Respect:** Treating all individuals with dignity and fairness  
**Excellence:** Striving for high-quality work and continuous improvement  
**Innovation:** Embracing creativity and technological advancement  
**Responsibility:** Taking ownership of actions and commitments  
**Collaboration:** Working effectively as a team

### 2.2 Fundamental Principles
• Compliance with all applicable laws and regulations
• Commitment to ethical business practices
• Protection of confidential and personal information
• Promotion of a safe and inclusive workplace
• Maintenance of professional standards at all times
---

## 3. LEGAL AND REGULATORY COMPLIANCE

### 3.1 General Legal Compliance
All staff members must:
• Comply with all applicable UK laws and regulations
• Follow company policies and procedures
• Report any suspected legal violations immediately
• Cooperate fully with any legal investigations or audits
• Seek guidance when unsure about legal requirements

### 3.2 Data Protection Compliance
Given our ICO registration (ZB910034), all staff must:
• Comply with UK GDPR and Data Protection Act 2018
• Complete mandatory data protection training
• Handle personal data only as authorized and necessary
• Report data breaches immediately (within 1 hour of discovery)
• Maintain confidentiality of all personal and customer data
• Follow established data retention and deletion procedures

### 3.3 Specific Compliance Areas
**Employment Law:** Adherence to working time regulations, especially for under-18 employees  
**Health and Safety:** Following all workplace safety protocols  
**Anti-Discrimination:** Zero tolerance for discrimination or harassment  
**Financial Regulations:** Proper handling of company finances and billing  
**Intellectual Property:** Respecting copyrights, trademarks, and proprietary information

---

## 4. PROFESSIONAL CONDUCT STANDARDS

### 4.1 General Professional Behavior
All staff members are expected to:
• Maintain the highest standards of professional conduct
• Act in the Company\'s best interests at all times
• Treat colleagues, customers, and partners with respect and courtesy
• Communicate professionally and constructively
• Take responsibility for their actions and decisions
• Seek help and guidance when needed

### 4.2 Availability and Communication
• Maintain agreed availability schedules for voluntary contributions
• Communicate proactively about availability changes or limitations
• Respond to work-related communications within reasonable timeframes
• Honor commitments made to the team and customers

### 4.3 Professional Online Presence
• Maintain professional conduct during video calls and online meetings
• Ensure appropriate backgrounds and environments for customer-facing interactions
• Consider the Company\'s image in all digital interactions and communications

### 4.4 Communication Standards
• Use clear, professional, and respectful language
• Respond promptly to work-related communications
• Follow established channels for different types of communication
• Maintain confidentiality in all communications

---

## 5. SPECIAL PROVISIONS FOR EMPLOYEES UNDER 18

### 5.1 Additional Protections
Recognizing our voluntary employment structure with individuals under 18, we provide enhanced protections:
• Regular welfare checks through online meetings and communications
• Clear digital reporting procedures for any concerns
• Mentorship through experienced team members
• Flexible contribution schedules accommodating education requirements
• Enhanced safeguarding measures in all online interactions

### 5.2 Voluntary Work Arrangements
For voluntary contributors under 18:
• Contributions are entirely voluntary with no obligation to work specific hours
• Educational commitments always take priority
• Flexible participation based on availability and interest
• No pressure to maintain minimum contribution levels
• Right to withdraw participation at any time without consequence

### 5.3 Digital Communication Guidelines
• Professional communication standards apply regardless of age or voluntary status
• Support for developing professional digital communication skills
• Clear escalation procedures for any inappropriate online interactions
• Protection from adult-oriented or inappropriate digital content
• Safe online interaction protocols

### 5.4 Development and Support
• Regular virtual performance reviews and development planning
• Online skills training and professional development opportunities
• Educational support and complete flexibility for school commitments
• Digital mentorship programs with experienced team members
• Recognition that all contributions are voluntary and valued

---

## 6. CONFIDENTIALITY AND DATA SECURITY

### 6.1 Confidentiality Obligations
All staff members must:
• Maintain strict confidentiality of all company and customer information
• Not disclose confidential information to unauthorized parties
• Continue confidentiality obligations after employment ends
• Report any suspected breaches of confidentiality immediately

### 6.2 Customer Data Protection
• Handle customer data only as necessary for job functions
• Follow data minimization principles
• Implement appropriate security measures for data processing
• Never use customer data for personal purposes
• Report any unauthorized access or suspicious activity immediately

### 6.3 Company Information Security
• Protect company systems, passwords, and access credentials
• Use company equipment and software only for authorized purposes
• Follow established IT security policies and procedures
• Report security incidents or suspicious activity immediately
• Participate in regular security training and updates

### 6.4 Information Systems Usage

**Email Systems (webmail.nexibot.uk):**
• Use company email only for business purposes
• Maintain professional tone and content
• Follow data retention policies
• Report any security concerns immediately

**HR Systems (ODOO/Google Docs):**
• Access only information necessary for job functions
• Maintain accurate and up-to-date records
• Follow established procedures for data entry and updates
• Protect login credentials and access rights
---

## 7. WORKPLACE BEHAVIOR AND RELATIONSHIPS

### 7.1 Respect and Dignity
All staff members must:
• Treat others with respect, dignity, and courtesy
• Value diversity and promote inclusion
• Refrain from discriminatory behavior or language
• Create a positive and supportive work environment
• Address conflicts constructively and professionally

### 7.2 Harassment and Discrimination
Zero tolerance policy for:
• Any form of harassment, bullying, or intimidation
• Discrimination based on protected characteristics
• Creating hostile or uncomfortable work environments
• Retaliation against those who report concerns
• Inappropriate personal relationships that affect work

### 7.3 Age-Appropriate Workplace Culture
Given our mixed-age workforce:
• Maintain appropriate professional boundaries
• Ensure all workplace activities are suitable for all staff
• Provide additional support and guidance for younger employees
• Foster an inclusive environment that respects different life stages
• Implement safeguarding measures to protect vulnerable employees

### 7.4 Conflict Resolution
• Address workplace conflicts promptly and professionally
• Seek mediation or supervisory assistance when needed
• Focus on solutions rather than blame
• Maintain confidentiality during conflict resolution processes
• Follow established grievance and disciplinary procedures

---

## 8. CUSTOMER SERVICE STANDARDS

### 8.1 Customer Interaction Principles
• Provide professional, helpful, and courteous service
• Respond promptly to customer inquiries and concerns
• Maintain accurate and detailed customer records
• Protect customer confidentiality and privacy
• Escalate complex issues appropriately

### 8.2 Discord Community Standards
When interacting in Discord servers:
• Follow Discord\'s Terms of Service and Community Guidelines
• Maintain professional representation of the Company
• Provide accurate and helpful information
• Report inappropriate behavior or content
• Respect server rules and community standards

### 8.3 Support Quality Standards
• Provide accurate and complete information
• Follow established support procedures and scripts
• Document all customer interactions properly
• Seek assistance when uncertain about solutions
• Follow up to ensure customer satisfaction
---

## 9. FINANCIAL INTEGRITY AND BILLING

### 9.1 Financial Responsibilities
All staff involved in financial matters must:
• Handle company funds and resources responsibly
• Follow established procedures for expenses and reimbursements
• Maintain accurate financial records and documentation
• Report any financial irregularities immediately
• Avoid conflicts of interest in financial dealings

### 9.2 Billing and Payment Systems
For staff with access to billing systems:
• Handle customer billing information with utmost care
• Follow established procedures for subscription management
• Never access payment details beyond job requirements
• Report any billing system issues immediately
• Maintain audit trails for all billing actions

### 9.3 Stripe Integration Management
• Access Stripe systems only as authorized for job functions
• Follow PCI DSS compliance requirements
• Never store or transmit payment card information inappropriately
• Report any payment processing issues immediately
• Maintain confidentiality of all payment-related information

---

## 10. TECHNOLOGY AND SOCIAL MEDIA

### 10.1 Technology Usage
Company technology must be used:
• Primarily for business purposes
• In compliance with acceptable use policies
• With respect for security and privacy settings
• Without installing unauthorized software
• While maintaining appropriate backup and security measures

### 10.2 Social Media Guidelines
When using personal social media:
• Do not represent yourself as speaking for the Company
• Maintain professional standards that reflect positively on the Company
• Respect confidentiality obligations
• Avoid posting content that could damage the Company\'s reputation
• Follow Discord\'s community standards when participating in gaming communities

### 10.3 Intellectual Property Protection
• Respect all intellectual property rights
• Do not share proprietary code or trade secrets
• Follow open source licensing requirements
• Report any intellectual property concerns
• Seek permission before publishing work-related content


---

## 11. HEALTH, SAFETY, AND WELLBEING

### 11.1 Digital Workplace Safety
All staff must:
• Follow cybersecurity procedures and guidelines
• Report security incidents or suspicious online activity immediately
• Use secure internet connections for all work-related activities
• Maintain appropriate digital boundaries and online safety
• Participate in online safety training and awareness programs

### 11.2 Remote Work Environment
For all remote contributors:
• Maintain appropriate home workspace for online activities
• Ensure secure internet connections for all work activities
• Follow data security protocols when working from personal devices
• Create appropriate digital boundaries between work and personal activities
• Report any cybersecurity or online safety concerns

### 11.3 Digital Wellbeing and Mental Health
The Company supports contributor wellbeing through:
• Open digital communication about mental health and workload challenges
• Flexible voluntary contribution arrangements
• Access to online resources and support programs
• Regular virtual check-ins with team leaders
• Training on healthy online work practices and digital boundaries

### 11.4 Special Considerations for Young Voluntary Contributors
Additional wellbeing support for voluntary contributors under 18:
• Regular virtual welfare assessments
• Complete educational support and priority for school commitments
• Enhanced online supervision and digital mentorship
• Protection from inappropriate online workplace stresses
• Clear digital procedures for raising concerns safely

---

## 12. REPORTING AND WHISTLEBLOWING

### 12.1 Reporting Obligations
All staff members have a duty to report:
• Violations of this Code of Conduct
• Suspected illegal activities
• Data protection breaches
• Safety hazards or incidents
• Discrimination or harassment
• Financial irregularities

### 12.2 Reporting Channels
Staff may report concerns through:
• Direct supervisor or manager
• HR department or designated officer
• Anonymous reporting system (where available)
• External regulatory bodies (ICO, Companies House, etc.)
• Confidential whistleblowing hotline

### 12.3 Protection for Reporters
We guarantee:
• No retaliation against good faith reporters
• Confidentiality to the extent legally possible
• Fair and thorough investigation of all reports
• Appropriate follow-up and resolution
• Support for staff who raise legitimate concerns

### 12.4 Special Protections for Young Employees
Enhanced reporting protections for employees under 18:
• Additional confidential reporting channels
• Immediate response to safeguarding concerns
• Involvement of parents/guardians when appropriate
• External oversight when necessary
• Specialized support during investigation processes
---

## 13. DISCIPLINARY PROCEDURES

### 13.1 Progressive Discipline
Disciplinary actions may include:
• **Verbal Warning:** For minor first-time violations
• **Written Warning:** For repeated or more serious violations
• **Final Written Warning:** For serious violations or continued misconduct
• **Suspension:** Pending investigation of serious allegations
• **Termination:** For gross misconduct or repeated violations

### 13.2 Gross Misconduct
Immediate termination may result from:
• Serious criminal activity
• Major data protection breaches
• Serious harassment or discrimination
• Theft or fraud
• Breach of Contract or NDA
• Serious safety violations
• Breach of confidentiality causing significant harm

### 13.3 Special Procedures for Young Voluntary Contributors
Additional considerations for voluntary contributors under 18:
• Parental/guardian involvement in any disciplinary processes
• Enhanced support during any conduct discussions
• Consideration of age, development, and voluntary status
• Additional training and mentorship opportunities before any formal action
• Recognition that participation is entirely voluntary

---

## 14. TRAINING AND DEVELOPMENT

### 14.1 Mandatory Training
All staff must complete:
• All training assigned on Staff Portal
• Signature on NDA, Contract & Code of Conduct
• Bi-Yearly Refresher

### 14.2 Ongoing Development
The Company supports professional development through:
• Regular training updates and workshops
• Professional certification support
• Mentorship and coaching programs
• Conference and learning opportunity access
• Career development planning

### 14.3 Youth Development Programs
Special development support for voluntary contributors under 18:
• Enhanced digital mentorship programs
• Online skills development workshops
• Educational support and complete flexibility for school priorities
• Leadership development opportunities within the team
• Career guidance and transition planning

---

## 15. IMPLEMENTATION AND COMPLIANCE

### 15.1 Code Awareness
All contributors are expected to:
• Familiarize themselves with this Code of Conduct
• Understand their responsibilities and obligations
• Seek clarification when uncertain about requirements
• Apply these standards in all work-related activities
• Maintain awareness of updates and revisions to the Code

### 15.2 Regular Review and Updates
This Code is reviewed:
• Annually or as needed for regulatory changes
• Following significant incidents or issues
• Based on staff feedback and suggestions
• To incorporate best practices and improvements
• To ensure continued legal compliance

### 15.3 Management Responsibilities
Managers and supervisors must:
• Model exemplary conduct at all times
• Ensure team members understand and follow the Code
• Address violations promptly and fairly
• Provide support and guidance to staff
• Create an environment that encourages ethical behavior

---

## 16. CONTACT INFORMATION

### General Inquiries
**Code of Conduct Questions:** legal@nexibot.uk  
**Confidential Reporting:** ethics@nexibot.uk  
**Data Protection Concerns:** dpo@nexibot.uk  

### Emergency Contacts
**Health & Safety:** safety@nexibot.uk  
**Security Incidents:** security@nexibot.uk  

### External Reporting
**ICO (Information Commissioner\'s Office):** ico.org.uk | 0303 123 1113  
**Companies House:** gov.uk/government/organisations/companies-house  

### Company Registration Details
**ICO Registration Number:** ZB910034

---

## 17. ACKNOWLEDGMENT AND COMPLIANCE

All staff members are required to read, understand, and comply with this Staff Code of Conduct. Violation of this Code may result in disciplinary action up to and including removal from the voluntary contributor program or termination of employment.

All contributors are expected to uphold the highest standards of professional and ethical conduct in all work activities and digital interactions representing the Company.

### 17.1 Implementation
This Code of Conduct is effective immediately for all staff members and will be reviewed annually or as needed to ensure continued compliance with legal and regulatory requirements.

### 17.2 Questions and Clarifications
Staff members who have questions about any aspect of this Code should contact their supervisor, HR department, or use the confidential reporting channels outlined in Section 12.

---

**Document Control:**  
**Version:** 1.0  
**Review Date:** 05/07/2026  
**Distribution:** All staff, HR records, management team';

    // Update the Code of Conduct template
    $stmt = $db->prepare("UPDATE contract_templates SET content = ?, updated_at = datetime('now') WHERE name = 'Code of Conduct'");
    $result = $stmt->execute([$newCodeOfConduct]);
    
    if ($result) {
        echo "✅ Code of Conduct contract template updated successfully!\n";
        
        // Verify the update
        $stmt = $db->prepare("SELECT name, LENGTH(content) as content_length, updated_at FROM contract_templates WHERE name = 'Code of Conduct'");
        $stmt->execute();
        $template = $stmt->fetch();
        
        if ($template) {
            echo "📋 Updated template details:\n";
            echo "   Name: " . $template['name'] . "\n";
            echo "   Content Length: " . $template['content_length'] . " characters\n";
            echo "   Updated At: " . $template['updated_at'] . "\n";
        }
    } else {
        echo "❌ Failed to update Code of Conduct template\n";
    }

} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
