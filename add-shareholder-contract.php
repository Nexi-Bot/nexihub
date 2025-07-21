<?php
require_once 'config/config.php';

try {
    // Insert Shareholder Agreement
    $shareholder_content = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexi Employee Share Participation Agreement</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #007bff; padding-bottom: 20px; }
        .logo { font-size: 28px; font-weight: bold; color: #007bff; margin-bottom: 10px; }
        .subtitle { font-size: 18px; color: #666; }
        .section { margin: 25px 0; }
        .section h3 { color: #007bff; border-bottom: 2px solid #eee; padding-bottom: 5px; }
        .important { background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 15px 0; }
        .minor-provision { background: #d4edda; padding: 15px; border-left: 4px solid #28a745; margin: 15px 0; }
        .warning { background: #f8d7da; padding: 15px; border-left: 4px solid #dc3545; margin: 15px 0; }
        .signature-section { margin-top: 40px; border-top: 2px solid #007bff; padding-top: 20px; }
        .signature-box { border: 1px solid #ccc; padding: 15px; margin: 10px 0; background: #f9f9f9; }
        ul li { margin: 8px 0; }
        .legal-notice { font-size: 12px; color: #666; margin-top: 30px; padding: 15px; background: #f8f9fa; border: 1px solid #dee2e6; }
        .financial-table { border-collapse: collapse; width: 100%; margin: 15px 0; }
        .financial-table th, .financial-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .financial-table th { background: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">NEXI</div>
        <div class="subtitle">Employee Share Participation Agreement</div>
        <p><strong>Equity Participation & Ownership Framework</strong></p>
    </div>

    <div class="important">
        <strong>INVESTMENT NOTICE:</strong> This agreement involves financial investments and equity participation. You should seek independent financial and legal advice before signing. If you are under 18, additional legal requirements apply and parental/guardian consent may be required.
    </div>

    <div class="minor-provision">
        <h4>Young Person Protections (Under 18)</h4>
        <p>Special safeguards for young employees:</p>
        <ul>
            <li>Parent/guardian must review and approve all equity arrangements</li>
            <li>Independent legal advice is strongly recommended</li>
            <li>Financial education and support will be provided</li>
            <li>Simplified vesting schedules and exit provisions</li>
            <li>Enhanced disclosure and reporting requirements</li>
            <li>Protection under consumer credit and investment regulations</li>
        </ul>
    </div>

    <div class="section">
        <h3>1. Share Participation Overview</h3>
        <p><strong>Grant Details:</strong></p>
        <ul>
            <li><strong>Company:</strong> Nexi Limited (Company Number: [TO BE INSERTED])</li>
            <li><strong>Share Class:</strong> Ordinary Shares</li>
            <li><strong>Number of Shares:</strong> _____ shares (to be specified in individual grant)</li>
            <li><strong>Exercise Price:</strong> £_____ per share (fair market value at grant date)</li>
            <li><strong>Grant Date:</strong> _____________</li>
            <li><strong>Vesting Commencement:</strong> Employment start date</li>
        </ul>
    </div>

    <div class="section">
        <h3>2. Vesting Schedule & Conditions</h3>
        <p><strong>Standard Vesting:</strong></p>
        <ul>
            <li><strong>4-year vesting period</strong> with 1-year cliff</li>
            <li><strong>25% vests</strong> after 12 months of continuous service</li>
            <li><strong>Remaining 75% vests monthly</strong> over following 36 months</li>
            <li><strong>Accelerated vesting</strong> may apply in certain circumstances</li>
        </ul>
        
        <table class="financial-table">
            <thead>
                <tr>
                    <th>Time Period</th>
                    <th>Cumulative Vested %</th>
                    <th>Monthly Vesting Rate</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>0-12 months</td>
                    <td>0%</td>
                    <td>0%</td>
                </tr>
                <tr>
                    <td>12 months (cliff)</td>
                    <td>25%</td>
                    <td>25%</td>
                </tr>
                <tr>
                    <td>13-48 months</td>
                    <td>25-100%</td>
                    <td>2.08% per month</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>3. Exercise Rights & Procedures</h3>
        <p><strong>Exercise Conditions:</strong></p>
        <ul>
            <li>Shares must be vested before exercise</li>
            <li>Exercise price must be paid in full upon exercise</li>
            <li>Company may require cashless exercise arrangements</li>
            <li>Exercise must comply with securities law restrictions</li>
            <li>Company has right of first refusal on share transfers</li>
        </ul>
        
        <p><strong>Exercise Process:</strong></p>
        <ul>
            <li>Submit written exercise notice to company</li>
            <li>Pay exercise price (cash, cashless exercise, or approved method)</li>
            <li>Complete required tax withholdings</li>
            <li>Receive share certificates or book entries</li>
        </ul>
    </div>

    <div class="section">
        <h3>4. Termination Provisions</h3>
        
        <div class="warning">
            <strong>Important:</strong> Unvested shares are typically forfeited upon termination.
        </div>
        
        <p><strong>Voluntary Termination:</strong></p>
        <ul>
            <li>Unvested shares are forfeited</li>
            <li>Vested but unexercised options expire in 90 days</li>
            <li>Exercised shares subject to company repurchase rights</li>
        </ul>
        
        <p><strong>Involuntary Termination (Without Cause):</strong></p>
        <ul>
            <li>Unvested shares are forfeited (unless board approves acceleration)</li>
            <li>Vested options expire in 90 days</li>
            <li>May qualify for partial acceleration in certain cases</li>
        </ul>
        
        <p><strong>Termination for Cause:</strong></p>
        <ul>
            <li>All unvested shares immediately forfeited</li>
            <li>All vested but unexercised options immediately expire</li>
            <li>Company may repurchase exercised shares at fair value</li>
        </ul>
        
        <p><strong>Death/Disability:</strong></p>
        <ul>
            <li>Partial or full acceleration may apply</li>
            <li>Extended exercise periods for beneficiaries/estate</li>
            <li>Special provisions for transfer to family members</li>
        </ul>
    </div>

    <div class="section">
        <h3>5. Valuation & Pricing</h3>
        <p><strong>Fair Market Value Determination:</strong></p>
        <ul>
            <li>Annual independent valuation by qualified appraiser</li>
            <li>Based on company financial performance and market conditions</li>
            <li>Consider recent arm\'s length transactions</li>
            <li>Reviewed and approved by board of directors</li>
        </ul>
        
        <p><strong>Repurchase Rights:</strong></p>
        <ul>
            <li>Company has right of first refusal on all share transfers</li>
            <li>Repurchase price based on most recent fair market valuation</li>
            <li>Payment may be made in installments over reasonable period</li>
        </ul>
    </div>

    <div class="section">
        <h3>6. Transfer Restrictions</h3>
        <p><strong>Prohibited Transfers:</strong></p>
        <ul>
            <li>No transfers without company approval</li>
            <li>No transfers to competitors or restricted parties</li>
            <li>All transfers subject to securities law compliance</li>
            <li>Company maintains register of all shareholders</li>
        </ul>
        
        <p><strong>Permitted Transfers:</strong></p>
        <ul>
            <li>Transfers to immediate family members (with restrictions)</li>
            <li>Transfers to trusts for employee\'s benefit</li>
            <li>Transfers pursuant to court orders (divorce, etc.)</li>
            <li>Transfers in connection with company-approved transactions</li>
        </ul>
    </div>

    <div class="section">
        <h3>7. Tax Obligations & Withholding</h3>
        
        <div class="important">
            <strong>Tax Advice Required:</strong> Share participation has significant tax implications. Consult a tax advisor.
        </div>
        
        <p><strong>Tax Events:</strong></p>
        <ul>
            <li><strong>Grant:</strong> Generally no immediate tax impact</li>
            <li><strong>Vesting:</strong> May create taxable income depending on structure</li>
            <li><strong>Exercise:</strong> Taxable income equal to spread (if any)</li>
            <li><strong>Sale:</strong> Capital gains tax on appreciation</li>
        </ul>
        
        <p><strong>Withholding Requirements:</strong></p>
        <ul>
            <li>Employee responsible for all tax obligations</li>
            <li>Company may withhold shares or require cash payment</li>
            <li>Estimated tax payments may be required</li>
            <li>Section 83(b) elections available in certain circumstances</li>
        </ul>
    </div>

    <div class="section">
        <h3>8. Corporate Events</h3>
        <p><strong>Change in Control:</strong></p>
        <ul>
            <li>Potential full or partial vesting acceleration</li>
            <li>Cash-out rights at transaction price</li>
            <li>Conversion to acquirer securities</li>
            <li>Board discretion on treatment of unvested shares</li>
        </ul>
        
        <p><strong>Reorganizations:</strong></p>
        <ul>
            <li>Proportional adjustment to share numbers and prices</li>
            <li>Preservation of economic value</li>
            <li>Substitution of equivalent rights in new entity</li>
        </ul>
    </div>

    <div class="section">
        <h3>9. Information Rights</h3>
        <p><strong>As a shareholder, you are entitled to:</strong></p>
        <ul>
            <li>Annual financial statements</li>
            <li>Notice of shareholder meetings</li>
            <li>Voting rights (if applicable to share class)</li>
            <li>Access to corporate records as required by law</li>
            <li>Information about material corporate events</li>
        </ul>
    </div>

    <div class="section">
        <h3>10. Drag-Along & Tag-Along Rights</h3>
        <p><strong>Drag-Along Rights:</strong></p>
        <ul>
            <li>Major shareholders may require sale of all shares in certain transactions</li>
            <li>Protects minority shareholders by ensuring equal treatment</li>
            <li>Applies to sales of majority control</li>
        </ul>
        
        <p><strong>Tag-Along Rights:</strong></p>
        <ul>
            <li>Right to participate in sales by major shareholders</li>
            <li>Ensures minority shareholders can exit on same terms</li>
            <li>Prevents dilution of minority positions</li>
        </ul>
    </div>

    <div class="section">
        <h3>11. Dispute Resolution</h3>
        <p><strong>Resolution Process:</strong></p>
        <ul>
            <li>Good faith negotiation required first</li>
            <li>Mediation through appointed neutral mediator</li>
            <li>Binding arbitration if mediation fails</li>
            <li>Courts of England and Wales for enforcement</li>
        </ul>
    </div>

    <div class="section">
        <h3>12. Compliance & Legal Requirements</h3>
        <p><strong>Securities Law Compliance:</strong></p>
        <ul>
            <li>All issuances comply with applicable securities laws</li>
            <li>Restrictions on public trading and disclosure</li>
            <li>Insider trading restrictions apply</li>
            <li>Anti-money laundering verification required</li>
        </ul>
        
        <p><strong>Corporate Governance:</strong></p>
        <ul>
            <li>Compliance with Companies Act 2006</li>
            <li>Proper share register maintenance</li>
            <li>Filing of required returns with Companies House</li>
            <li>Board approval for equity grants and transfers</li>
        </ul>
    </div>

    <div class="signature-section">
        <h3>Agreement & Acceptance</h3>
        
        <div class="important">
            <p>By signing below, I acknowledge that I have read and understood this agreement, have been advised to seek independent legal and financial advice, and agree to be bound by all terms and conditions.</p>
        </div>
        
        <div class="signature-box">
            <p><strong>Employee/Participant:</strong></p>
            <p>Name: _________________________________</p>
            <p>Signature: ____________________________</p>
            <p>Date: _________________________________</p>
            <p>Age: __________________________________</p>
            <p>National Insurance Number: _____________</p>
        </div>

        <div class="signature-box" style="background: #e8f5e8;">
            <p><strong>Parent/Guardian Consent (Required if participant is under 18):</strong></p>
            <p>I consent to my child\'s participation in the Nexi equity program and acknowledge the financial and legal implications.</p>
            <p>Name: _________________________________</p>
            <p>Signature: ____________________________</p>
            <p>Date: _________________________________</p>
            <p>Relationship: __________________________</p>
        </div>

        <div class="signature-box">
            <p><strong>Company Representative:</strong></p>
            <p>Name: _________________________________</p>
            <p>Title: ________________________________</p>
            <p>Signature: ____________________________</p>
            <p>Date: _________________________________</p>
        </div>
        
        <div class="signature-box">
            <p><strong>Board Resolution:</strong></p>
            <p>This grant has been approved by the Board of Directors on _______________</p>
            <p>Secretary Signature: ___________________</p>
        </div>
    </div>

    <div class="legal-notice">
        <p><strong>Legal Framework:</strong> This agreement is governed by the laws of England and Wales and complies with the Companies Act 2006, employment law, securities regulations, and tax law. The agreement is subject to the company\'s Articles of Association and any applicable shareholder agreements.</p>
        
        <p><strong>Professional Advice:</strong> Both parties acknowledge the importance of seeking independent legal, tax, and financial advice before entering into this agreement. The company does not provide investment or tax advice.</p>
        
        <p><strong>Document Version:</strong> EQUITY-UK-2024-v1.0 | <strong>Effective Date:</strong> Upon signature and board approval</p>
    </div>
</body>
</html>';

    $stmt = $pdo->prepare('INSERT INTO contract_templates (name, type, content, is_assignable, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())');
    $result = $stmt->execute([
        'Employee Share Participation Agreement',
        'shareholder',
        $shareholder_content,
        1
    ]);
    
    if ($result) {
        echo "Successfully inserted Shareholder Agreement contract.\n";
    } else {
        echo "Failed to insert Shareholder Agreement contract.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
