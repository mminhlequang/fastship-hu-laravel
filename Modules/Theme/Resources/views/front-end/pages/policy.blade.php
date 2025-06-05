@extends('theme::front-end.master')
@section('title')
    <title>{{ __('Fast ship Hu Policy') }}</title>
    <meta name="description"
          content="{{ __('Fast ship Hu Policy') }}"/>
    <meta name="keywords" content="{{ __('Fast ship Hu Policy') }}"/>
@endsection
@section('content')
    <!-- Hero Section with Search -->
    <div class="relative py-12 mb-4 overflow-hidden">
        <!-- Background image using <img> -->
        <img src="{{ url('assets/images/banner_ask.svg') }}" alt="Background Image"
             class="absolute inset-0 w-full h-full object-cover"/>
        <!-- Content inside -->
        <div class="relative z-10 py-8">
            <h1 class="text-3xl font-medium mb-6 text-white responsive-px">
                Policy
            </h1>
        </div>
    </div>

    <!-- FAQ Section -->
    <section class="responsive-px">
        <!-- Menu categories -->
        <div class="tab-buttons border-b">
            <div class="flex flex-wrap justify-start overflow-x-auto no-scrollbar">
                <button class="tab-btn px-4 py-3 text-black border-b-2 font-medium border-black" data-tab="terms-of-service">
                    Terms of Service
                </button>
                <button class="tab-btn px-4 py-3 text-gray-500 hover:text-secondary" data-tab="privacy-policy">
                    Privacy Policy
                </button>
                <button class="tab-btn px-4 py-3 text-gray-500 hover:text-secondary" data-tab="payment-policy">
                    Payment policy
                </button>
                <button class="tab-btn px-4 py-3 text-gray-500 hover:text-secondary" data-tab="refund-cancellation">
                    Refund & Cancellation
                </button>
                <button class="tab-btn px-4 py-3 text-gray-500 hover:text-secondary" data-tab="cookies-policy">
                    Cookies Policy
                </button>
            </div>
        </div>
    </section>
    <section class="responsive-px">
        <!-- Tab Content -->
        <div class="tab-content my-6">
            <div class="tab-panel" id="terms-of-service">
                <header>
                    <h1>Terms of Service</h1>
                </header>
                <section id="preamble">
                    <h2>Preamble</h2>
                    <p>
                        Welcome to FastShipHU (the “Platform”). These Terms of Service (“Terms”) govern your use of our website, mobile application, and any associated services (the “Service”) that facilitate food delivery in Hungary (and potentially other European countries). We connect customers, restaurants (Merchants), and delivery personnel (Drivers) through our Platform. By accessing or using the Service, you agree to be bound by these Terms.
                    </p>
                </section>

                <section id="definitions">
                    <h2>1. Definitions</h2>
                    <ul>
                        <li><strong>“Platform”</strong> means the website, mobile application, and any related software provided by FastShipHU.</li>
                        <li><strong>“Customer”</strong> means any user who places an order for food or grocery items through the Platform.</li>
                        <li><strong>“Merchant”</strong> means a restaurant, grocery store, or any business that prepares and/or sells food and partners with us to accept orders via the Platform.</li>
                        <li><strong>“Driver”</strong> means an independent contractor who accepts delivery requests through the Platform to pick up orders from Merchants and deliver them to Customers.</li>
                        <li><strong>“Order”</strong> means a request by a Customer for the purchase and delivery of food or other items from a Merchant.</li>
                        <li><strong>“We,” “Us,” or “Our”</strong> refer to FastShipHU, the owner and operator of the Platform.</li>
                    </ul>
                </section>

                <section id="eligibility">
                    <h2>2. Eligibility</h2>
                    <p>
                        To use the Service, you must be at least 18 years old or the age of majority in your jurisdiction. You represent and warrant that you meet all eligibility requirements and that any information you provide to register for an account is accurate and complete.
                    </p>
                </section>

                <section id="accounts">
                    <h2>3. Accounts</h2>
                    <p>
                        <strong>3.1 Account Creation:</strong> To place Orders as a Customer, list items as a Merchant, or accept deliveries as a Driver, you must register for an account and provide true, accurate, and complete information. You are responsible for safeguarding your account credentials.
                    </p>
                    <p>
                        <strong>3.2 Account Responsibilities:</strong> You agree to (a) keep your password secure, (b) notify us immediately if you suspect any unauthorized use, and (c) accept responsibility for all activities that occur under your account.
                    </p>
                    <p>
                        <strong>3.3 Suspension & Termination:</strong> We may suspend or terminate your account if you violate these Terms, engage in fraudulent activity, or violate any applicable laws. Upon termination, any outstanding Orders or transactions may be canceled.
                    </p>
                </section>

                <section id="use-of-service">
                    <h2>4. Use of the Service</h2>
                    <p>
                        <strong>4.1 Order Placement (Customers):</strong> You may browse participating Merchants, select items, and place Orders through the Platform. All Orders are subject to Merchant acceptance and availability.
                    </p>
                    <p>
                        <strong>4.2 Merchant Obligations:</strong> Merchants agree to prepare and have Orders ready at the estimated pickup time. Merchants are solely responsible for the quality, safety, and legality of the food or items they provide. Merchants must comply with all local health and safety regulations.
                    </p>
                    <p>
                        <strong>4.3 Driver Obligations:</strong> Drivers accept delivery requests at their own discretion. Drivers must follow local traffic laws and exercise reasonable care while making deliveries. Drivers are responsible for delivering Orders in a timely manner and in good condition.
                    </p>
                    <p>
                        <strong>4.4 Platform Etiquette:</strong> All users must treat others with respect, refrain from harassment, and comply with local laws. Abusive or discriminatory behavior may result in account suspension or termination.
                    </p>
                </section>

                <section id="fees-and-payments">
                    <h2>5. Fees and Payments</h2>
                    <p>
                        <strong>5.1 Service Fees:</strong> We charge a service fee on each Order, which may include delivery fees, platform fees, and any applicable taxes. The exact amount will be displayed before you confirm your Order.
                    </p>
                    <p>
                        <strong>5.2 Payment Processing:</strong> All payments are processed through our third‐party payment processor. By placing an Order, you authorize us to charge your chosen payment method for the full Order amount (including fees).
                    </p>
                    <p>
                        <strong>5.3 Refunds:</strong> Refunds are governed by our <a href="refund.html">Refund &amp; Cancellation Policy</a>. In certain cases (e.g., Merchant unable to fulfill your Order), we may issue a refund or credit at our discretion.
                    </p>
                </section>

                <section id="user-content">
                    <h2>6. User‐Generated Content</h2>
                    <p>
                        You may leave ratings, reviews, comments, or other content (“User Content”) on the Platform. By submitting User Content, you grant us a perpetual, royalty‐free, worldwide, transferable license to use, display, reproduce, modify, and distribute such content. You are responsible for ensuring that your User Content does not infringe any third‐party rights or violate any laws.
                    </p>
                </section>

                <section id="intellectual-property">
                    <h2>7. Intellectual Property</h2>
                    <p>
                        All content on this Platform, including text, graphics, logos, images, and software, is the property of FastShipHU or its licensors and is protected by applicable intellectual property laws. You may not copy, modify, distribute, sell, or lease any part of our Platform without our prior written consent.
                    </p>
                </section>

                <section id="disclaimers">
                    <h2>8. Disclaimers</h2>
                    <p>
                        <strong>8.1 No Warranty:</strong> The Service is provided “as is” and “as available.” We expressly disclaim all warranties, whether express or implied, including merchantability, fitness for a particular purpose, and non‐infringement.
                    </p>
                    <p>
                        <strong>8.2 Third‐Party Services:</strong> The Platform may contain links to third‐party websites or services. We are not responsible for the content, accuracy, or practices of any third party.
                    </p>
                </section>

                <section id="limitation-of-liability">
                    <h2>9. Limitation of Liability</h2>
                    <p>
                        To the maximum extent permitted by law, FastShipHU and its affiliates, officers, employees, and agents will not be liable for any indirect, incidental, special, consequential, or exemplary damages arising out of or in connection with your use of the Service. Our total liability for any claim arising out of or relating to these Terms or the Service will not exceed the total amount you paid to us in the last six (6) months.
                    </p>
                </section>

                <section id="indemnification">
                    <h2>10. Indemnification</h2>
                    <p>
                        You agree to indemnify, defend, and hold harmless FastShipHU, its officers, directors, employees, agents, and affiliates from and against any claims, damages, losses, liabilities, costs, or expenses (including reasonable attorneys’ fees) arising out of or related to (a) your use of the Service; (b) your violation of these Terms; (c) your User Content; or (d) your infringement of any third‐party rights.
                    </p>
                </section>

                <section id="termination">
                    <h2>11. Termination</h2>
                    <p>
                        We may suspend or terminate your access to the Service at any time, with or without cause or notice. Upon termination, all licenses and permissions granted to you under these Terms will immediately cease.
                    </p>
                </section>

                <section id="governing-law">
                    <h2>12. Governing Law &amp; Dispute Resolution</h2>
                    <p>
                        These Terms and any disputes arising out of or related to these Terms or the Service will be governed by the laws of Hungary (or, if applicable, the laws of the European Union), without regard to conflict of law principles. Any legal action or proceeding arising under these Terms shall be brought exclusively in the courts located in Budapest, Hungary.
                    </p>
                </section>

                <section id="changes-to-terms">
                    <h2>13. Changes to Terms</h2>
                    <p>
                        We may modify these Terms at any time. If we make material changes, we will notify you via the Platform or by email. Your continued use of the Service after the changes become effective constitutes your acceptance of the revised Terms.
                    </p>
                </section>

                <footer>
                    <h3>Contact Us</h3>
                    <p>
                        If you have any questions about these Terms, please contact us at:<br />
                        Email: <a href="mailto:support@fastshiphu.com">support@fastshiphu.com</a><br />
                        Phone: +36 30 785 9588<br />
                        Address: 1089 Budapest, Orczy tér 4, Hungary
                    </p>
                </footer>
            </div>
            <div class="tab-panel hidden" id="privacy-policy">
                <header>
                    <h1>Privacy Policy</h1>
                    
                </header>

                <section id="introduction">
                    <h2>1. Introduction</h2>
                    <p>
                        FastShipHU (“we,” “our,” or “us”) is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your personal data when you use our website and mobile application (collectively, the “Platform”) for food delivery services in Hungary and other European countries. Please read this policy before using our Platform or submitting any personal information.
                    </p>
                </section>

                <section id="data-we-collect">
                    <h2>2. Data We Collect</h2>
                    <h3>2.1 Information You Provide Directly</h3>
                    <ul>
                        <li><strong>Account Information:</strong> Name, email address, phone number, password, address (delivery and billing).</li>
                        <li><strong>Payment Information:</strong> Credit/debit card details or other payment method information (processed securely via third‐party payment processors).</li>
                        <li><strong>Profile Details:</strong> Profile picture (if you choose to upload), dietary preferences, and any optional information you choose to share.</li>
                    </ul>

                    <h3>2.2 Information Collected Automatically</h3>
                    <ul>
                        <li><strong>Log Data:</strong> IP address, device information (e.g., device model, operating system), browser type, pages visited, and timestamps.</li>
                        <li><strong>Location Data:</strong> GPS or precise location data if you grant permission to do so (used to match you with nearby Drivers and estimate delivery time).</li>
                        <li><strong>Cookies &amp; Tracking Technologies:</strong> Cookies, web beacons, and similar technologies to track your activity on the Platform. See our <a href="cookies.html">Cookies Policy</a> for details.</li>
                    </ul>

                    <h3>2.3 Information from Third Parties</h3>
                    <p>
                        We may obtain information from third‐party services, such as identity verification services, marketing or analytics providers, or publicly available sources to enhance or verify information you provide.
                    </p>
                </section>

                <section id="how-we-use-data">
                    <h2>3. How We Use Your Data</h2>
                    <ul>
                        <li><strong>To Provide the Service:</strong> Process Orders, send order confirmations, match Drivers and Customers, and facilitate payments.</li>
                        <li><strong>To Communicate:</strong> Respond to your inquiries, send you updates (e.g., order status), promotional offers, and marketing communications (which you can opt out of at any time).</li>
                        <li><strong>To Improve the Platform:</strong> Analyze usage patterns, monitor performance, and improve user experience.</li>
                        <li><strong>To Personalize Your Experience:</strong> Tailor recommendations, promotions, and features based on your preferences and order history.</li>
                        <li><strong>For Legal and Security Purposes:</strong> Comply with legal obligations, resolve disputes, prevent fraud, and maintain the security of the Platform.</li>
                    </ul>
                </section>

                <section id="sharing-of-data">
                    <h2>4. Sharing Your Data</h2>
                    <p>We may share your information as follows:</p>
                    <ul>
                        <li><strong>With Merchants:</strong> Your name, delivery address, and phone number are shared with the Merchant fulfilling your order so that they can prepare and package your items properly.</li>
                        <li><strong>With Drivers:</strong> Your name, delivery address, and phone number are shared with the Driver responsible for delivering your order.</li>
                        <li><strong>With Service Providers:</strong> Third‐party vendors that perform services on our behalf (e.g., payment processors, analytics providers, customer support, email delivery).</li>
                        <li><strong>With Legal Authorities:</strong> To comply with applicable laws, regulations, or legal processes, or to protect our rights, or in response to a valid subpoena, court order, or other legal request.</li>
                        <li><strong>Business Transfers:</strong> In the event of a merger, acquisition, reorganization, or sale of assets, your information may be transferred to the acquiring entity. We will notify you before your information becomes subject to a different privacy policy.</li>
                    </ul>
                </section>

                <section id="your-rights">
                    <h2>5. Your Rights</h2>
                    <p>
                        Depending on your jurisdiction (including the EU’s General Data Protection Regulation), you may have the right to:
                    </p>
                    <ul>
                        <li>Access and receive a copy of your personal data.</li>
                        <li>Rectify or update inaccurate or incomplete data.</li>
                        <li>Erase or delete certain personal data (under certain conditions).</li>
                        <li>Restrict or object to processing of your data.</li>
                        <li>Receive your data in a structured, commonly used, machine‐readable format (data portability).</li>
                        <li>Lodge a complaint with a supervisory authority if you believe your rights have been violated.</li>
                    </ul>
                    <p>
                        To exercise any of these rights, please contact us at
                        <a href="mailto:privacy@fastshiphu.com">privacy@fastshiphu.com</a>.
                    </p>
                </section>

                <section id="data-security">
                    <h2>6. Data Security</h2>
                    <p>
                        We implement reasonable technical and organizational measures to safeguard your personal data. However, no system is completely secure. We cannot guarantee that unauthorized third parties will never be able to defeat those measures.
                    </p>
                </section>

                <section id="data-retention">
                    <h2>7. Data Retention</h2>
                    <p>
                        We retain your personal data for as long as necessary to provide the Service, comply with our legal obligations, or resolve disputes. Once data is no longer required, we will securely delete or anonymize it.
                    </p>
                </section>

                <section id="international-transfer">
                    <h2>8. International Data Transfers</h2>
                    <p>
                        Since we operate in Hungary and may use service providers located in other countries (including outside the European Economic Area), your data may be transferred to and processed in countries with different data protection laws. Whenever we transfer personal data outside the EEA, we ensure adequate safeguards are in place (e.g., standard contractual clauses).
                    </p>
                </section>

                <section id="children">
                    <h2>9. Children’s Privacy</h2>
                    <p>
                        Our Platform is not intended for individuals under 18 years of age. We do not knowingly collect or solicit personal data from children. If we become aware that we have collected personal data from a child under 18, we will promptly delete that data.
                    </p>
                </section>

                <section id="changes-to-policy">
                    <h2>10. Changes to This Policy</h2>
                    <p>
                        We may update this Privacy Policy from time to time. If we make material changes, we will notify you via the Platform or by email before the changes become effective. Your continued use of the Service after the changes constitute your acceptance of the updated policy.
                    </p>
                </section>

                <footer>
                    <h3>Contact Us</h3>
                    <p>
                        If you have any questions or concerns about this Privacy Policy, please contact us at:<br />
                        Email: <a href="mailto:privacy@fastshiphu.com">privacy@fastshiphu.com</a><br />
                        Phone: +36 30 785 9588<br />
                        Address: 1089 Budapest, Orczy tér 4, Hungary
                    </p>
                </footer>

            </div>
            <div class="tab-panel hidden" id="payment-policy">
                <header>
                    <h1>Payment Policy</h1>
                    
                </header>

                <section id="introduction">
                    <h2>1. Introduction</h2>
                    <p>
                        This Payment Policy outlines the terms under which payments are processed for orders placed through the FastShipHU Platform. By placing an order, you agree to these terms.
                    </p>
                </section>

                <section id="accepted-payment-methods">
                    <h2>2. Accepted Payment Methods</h2>
                    <ul>
                        <li><strong>Credit/Debit Cards:</strong> We accept most major credit and debit cards (Visa, MasterCard, American Express).</li>
                        <li><strong>Mobile Wallets:</strong> Google Pay, Apple Pay (where supported).</li>
                        <li><strong>Digital Payment Platforms:</strong> PayPal (where available).</li>
                        <li><strong>Cash on Delivery (COD):</strong> In certain cities, Cash on Delivery may be offered. COD availability is indicated during checkout. Drivers will carry limited change; exact payment is appreciated.</li>
                    </ul>
                </section>

                <section id="pricing-and-taxes">
                    <h2>3. Pricing and Taxes</h2>
                    <p>
                        <strong>3.1 Menu Prices:</strong> The prices displayed on the Platform reflect what the Merchant charges. Prices may vary slightly from in‐restaurant pricing due to delivery fees or promotional offers.
                    </p>
                    <p>
                        <strong>3.2 Service Fees:</strong> We charge a service fee on each order to cover operational costs. This fee varies by location and is displayed before you confirm your order.
                    </p>
                    <p>
                        <strong>3.3 Taxes:</strong> Applicable VAT or other taxes will be clearly indicated at checkout. Taxes are calculated based on the Merchant’s location and your delivery address.
                    </p>
                </section>

                <section id="payment-processing">
                    <h2>4. Payment Processing</h2>
                    <p>
                        <strong>4.1 Authorization:</strong> When you place an order, we obtain pre‐authorization on your selected payment method for the total estimated order amount (including fees and taxes). Your card issuer may temporarily hold this amount until the order completes.
                    </p>
                    <p>
                        <strong>4.2 Final Charges:</strong> The final charge will be processed after the Merchant confirms and the Driver completes delivery. If there are modifications (e.g., item unavailable), you will be charged the actual total amount.
                    </p>
                    <p>
                        <strong>4.3 Failed Transactions:</strong> If your payment fails (e.g., insufficient funds, expired card), you will be prompted to provide an alternate payment method. We reserve the right to cancel orders if valid payment is not provided.
                    </p>
                </section>

                <section id="refunds-and-disputes">
                    <h2>5. Refunds and Disputes</h2>
                    <p>
                        If you believe you were charged incorrectly or experience an issue with your order, please refer to our <a href="refund.html">Refund &amp; Cancellation Policy</a> for guidance on how to request a refund. For disputes regarding credit/debit card charges, you may also contact your bank or card issuer.
                    </p>
                </section>

                <section id="billing-notices">
                    <h2>6. Billing Notices</h2>
                    <p>
                        After each successful order, you will receive an email receipt showing the breakdown of charges (items, service fees, taxes, discounts). Please review the receipt, and contact us at
                        <a href="mailto:billing@fastshiphu.com">billing@fastshiphu.com</a> within 30 days if you have any questions.
                    </p>
                </section>

                <section id="modifications">
                    <h2>7. Changes to Payment Policy</h2>
                    <p>
                        We may update this Payment Policy occasionally. If we make changes that materially affect your rights or obligations, we will notify you via the Platform or email before they take effect. Continued use of the Service after changes means you accept the updated policy.
                    </p>
                </section>

                <footer>
                    <h3>Contact Us</h3>
                    <p>
                        For questions regarding payments, please contact our billing team at:<br />
                        Email: <a href="mailto:billing@fastshiphu.com">billing@fastshiphu.com</a><br />
                        Phone: +36 30 785 9588<br />
                        Address: 1089 Budapest, Orczy tér 4, Hungary
                    </p>
                </footer>
            </div>
            <div class="tab-panel hidden" id="refund-cancellation">
                <header>
                    <h1>Refund &amp; Cancellation Policy</h1>
                    
                </header>

                <section id="overview">
                    <h2>1. Overview</h2>
                    <p>
                        This Refund &amp; Cancellation Policy explains how cancellations and refunds are handled when you place an order through the FastShipHU Platform. Please read this policy carefully before placing an order.
                    </p>
                </section>

                <section id="cancellation-by-customer">
                    <h2>2. Cancellation by Customer</h2>
                    <p>
                        <strong>2.1 Before Merchant Confirmation:</strong> You may cancel your order via the Platform free of charge at any time before the Merchant confirms the order (i.e., before the Merchant begins preparing your food). A full refund will be issued automatically to your original payment method.
                    </p>
                    <p>
                        <strong>2.2 After Merchant Confirmation (But Before Preparation):</strong> If the Merchant has confirmed but not yet started preparing your order, you may request cancellation. Depending on the Merchant’s policy, a cancellation fee up to 50% of the order value may apply. Any remaining amount will be refunded to your original payment method.
                    </p>
                    <p>
                        <strong>2.3 After Preparation Has Begun:</strong> If preparation has already begun, cancellations are typically not accepted. In exceptional circumstances (e.g., Merchant error), contact our Customer Support immediately at
                        <a href="mailto:support@fastshiphu.com">support@fastshiphu.com</a>. Refunds at this stage are at our sole discretion.
                    </p>
                </section>

                <section id="cancellation-by-merchant">
                    <h2>3. Cancellation by Merchant</h2>
                    <p>
                        If a Merchant cannot fulfill your Order (e.g., due to stock issues, kitchen closure, or technical problems), they may cancel it. In this case, you will receive a full refund to your original payment method within 3–5 business days. We will also notify you via email or push notification of the cancellation.
                    </p>
                </section>

                <section id="cancellation-by-driver">
                    <h2>4. Cancellation by Driver</h2>
                    <p>
                        Drivers may occasionally cancel after accepting a delivery (e.g., vehicle breakdown, urgent personal matter). If this occurs, we will attempt to assign another Driver. If no replacement Driver is available, your order may be delayed or canceled. In the event of cancellation, you will receive a full refund.
                    </p>
                </section>

                <section id="refund-process">
                    <h2>5. Refund Process</h2>
                    <p>
                        <strong>5.1 Automatic Refunds:</strong> Refunds for canceled orders are processed automatically. The refunded amount will appear on your original payment method within 3–5 business days, depending on your bank or payment provider.
                    </p>
                    <p>
                        <strong>5.2 Dispute Resolution:</strong> If you believe you should have received a refund but did not, contact our Customer Support at
                        <a href="mailto:support@fastshiphu.com">support@fastshiphu.com</a> with your order ID. We will review your case and respond within 48 hours.
                    </p>
                </section>

                <section id="partial-refunds">
                    <h2>6. Partial Refunds</h2>
                    <p>
                        In rare cases (e.g., missing items, incorrect items delivered, or food quality issues), you may be eligible for a partial refund or credit. Contact Customer Support within 24 hours of delivery, providing:
                    </p>
                    <ul>
                        <li>Your order ID</li>
                        <li>Photos or evidence of the issue</li>
                        <li>A brief description of the problem</li>
                    </ul>
                    <p>
                        After investigation, we will determine the appropriate compensation (partial refund, credit, or store credit) at our discretion.
                    </p>
                </section>

                <section id="no-show-policy">
                    <h2>7. No‐Show Policy</h2>
                    <p>
                        If you are not available at the delivery location after multiple Driver attempts (e.g., no answer, incorrect address), the Driver may mark the order as “no‐show.” In this case:
                    </p>
                    <ul>
                        <li>No refund will be issued. You will be responsible for covering the full order amount plus delivery fees.</li>
                        <li>Repeated no‐shows may result in account suspension or termination.</li>
                    </ul>
                </section>

                <section id="third-party-cancellations">
                    <h2>8. Cancellations Due to External Factors</h2>
                    <p>
                        Orders may be canceled or delayed due to external factors beyond our control (e.g., severe weather, traffic disruptions, public transportation strikes). In such cases, we will notify you as soon as possible. If an order is canceled for these reasons, you’ll receive a full refund.
                    </p>
                </section>

                <section id="contact-support">
                    <h2>9. Contact Customer Support</h2>
                    <p>
                        For any questions about cancellations or refunds, please reach out to us:
                    </p>
                    <ul>
                        <li>Email: <a href="mailto:support@fastshiphu.com">support@fastshiphu.com</a></li>
                        <li>Phone: +36 30 785 9588</li>
                        <li>Address: 1089 Budapest, Orczy tér 4, Hungary</li>
                    </ul>
                </section>

                <footer>
                    <p>
                        These policies may be updated occasionally. Please check this page regularly. Continued use of the Platform implies acceptance of any changes.
                    </p>
                </footer>
            </div>
            <div class="tab-panel hidden" id="cookies-policy">
                <header>
                    <h1>Cookies Policy</h1>
                    
                </header>

                <section id="purpose">
                    <h2>1. Purpose of This Cookies Policy</h2>
                    <p>
                        FastShipHU (“we,” “our,” or “us”) uses cookies and similar tracking technologies to improve your experience on our Platform. This Cookies Policy explains what cookies are, how we use them, and how you can manage your preferences.
                    </p>
                </section>

                <section id="what-are-cookies">
                    <h2>2. What Are Cookies?</h2>
                    <p>
                        Cookies are small text files placed on your device (computer, smartphone, or tablet) when you visit a website. They store a small amount of data to remember your actions and preferences over time.
                    </p>
                </section>

                <section id="how-we-use">
                    <h2>3. How We Use Cookies</h2>
                    <p>We use cookies for several purposes:</p>
                    <ul>
                        <li><strong>Essential Cookies:</strong> Required for the basic functionality of the Platform (e.g., maintaining your login session, shopping cart contents, security features).</li>
                        <li><strong>Performance &amp; Analytics Cookies:</strong> Collect anonymous information about how visitors use our Platform (e.g., which pages are visited most often, error messages). This helps us improve performance and user experience.</li>
                        <li><strong>Functional Cookies:</strong> Enable enhanced functionality and personalization (e.g., remember your language preference, saved addresses).</li>
                        <li><strong>Advertising &amp; Marketing Cookies:</strong> Track your browsing activity across websites to show you relevant advertisements and promotional offers. These may be placed by third‐party ad networks with our permission.</li>
                    </ul>
                </section>

                <section id="types-of-cookies">
                    <h2>4. Types of Cookies We Use</h2>
                    <table border="1" cellpadding="8" cellspacing="0">
                        <tr>
                            <th>Category</th>
                            <th>Name / Provider</th>
                            <th>Purpose</th>
                            <th>Duration</th>
                        </tr>
                        <tr>
                            <td>Essential Cookies</td>
                            <td>fastshiphu_session</td>
                            <td>Maintains user session and login state.</td>
                            <td>Session (deleted when browser closes)</td>
                        </tr>
                        <tr>
                            <td>Performance &amp; Analytics</td>
                            <td>_ga (Google Analytics)</td>
                            <td>Collects anonymous usage statistics to improve our Platform.</td>
                            <td>2 years</td>
                        </tr>
                        <tr>
                            <td>Functional Cookies</td>
                            <td>language_preference</td>
                            <td>Remembers user’s chosen language.</td>
                            <td>1 year</td>
                        </tr>
                        <tr>
                            <td>Advertising &amp; Marketing</td>
                            <td>fr (Facebook Pixel)</td>
                            <td>Measures ad performance and tracks conversions.</td>
                            <td>3 months</td>
                        </tr>
                    </table>
                </section>

                <section id="third-party">
                    <h2>5. Third‐Party Cookies</h2>
                    <p>
                        We may allow trusted third parties (e.g., analytics providers, advertising networks) to place cookies on our Platform. These third parties use cookies to collect information about your online activities over time and across different websites to provide targeted advertising or analytics services.
                    </p>
                    <p>
                        We do not control these third‐party cookies and recommend you review their privacy policies for more information on their practices.
                    </p>
                </section>

                <section id="managing-cookies">
                    <h2>6. Managing and Disabling Cookies</h2>
                    <p>
                        You can control or delete cookies at any time by adjusting your browser settings. Common actions include:
                    </p>
                    <ul>
                        <li>Viewing the cookies stored on your device and deleting individual cookies.</li>
                        <li>Blocking all cookies from a specific website.</li>
                        <li>Blocking all cookies from all websites (this may impact essential functionality).</li>
                    </ul>
                    <p>
                        For instructions on how to manage cookies in your browser, please visit:
                    </p>
                    <ul>
                        <li><a href="https://support.google.com/chrome/answer/95647">Google Chrome</a></li>
                        <li><a href="https://support.mozilla.org/en-US/kb/enable-and-disable-cookies-website-preferences">Mozilla Firefox</a></li>
                        <li><a href="https://support.apple.com/guide/safari/manage-cookies-and-website-data-sfri11471/mac">Safari</a></li>
                        <li><a href="https://support.microsoft.com/en-us/edge/view-and-delete-browser-history-in-microsoft-edge-14c1b21a-856d-0ae5-8db3-6f7005f0381b">Microsoft Edge</a></li>
                    </ul>
                    <p>
                        You can also manage ad cookies by visiting the
                        <a href="https://www.fastshiphu.com/opt-out">Ad Preferences / Opt‐out</a> page on our Platform.
                    </p>
                </section>

                <section id="consent">
                    <h2>7. Your Consent</h2>
                    <p>
                        By using our Platform, you consent to our placement and use of cookies as described in this policy. If you do not agree, please adjust your cookie settings or discontinue use of the Platform.
                    </p>
                </section>

                <section id="updates">
                    <h2>8. Updates to This Policy</h2>
                    <p>
                        We may update this Cookies Policy from time to time. The “Last Updated” date at the top indicates when this policy was last revised. Continued use of our Platform after changes will constitute acceptance of those changes.
                    </p>
                </section>

                <footer>
                    <h3>Contact Us</h3>
                    <p>
                        If you have any questions about our Cookies Policy, please contact us at:<br />
                        Email: <a href="mailto:privacy@fastshiphu.com">privacy@fastshiphu.com</a><br />
                        Phone: +36 30 785 9588<br />
                        Address: 1089 Budapest, Orczy tér 4, Hungary
                    </p>
                </footer>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabPanels = document.querySelectorAll('.tab-panel');

        function activateTab(tabId) {
            tabButtons.forEach(btn => {
                btn.classList.remove('text-black', 'border-black', 'border-b-2');
                btn.classList.add('text-gray-500');
            });

            const activeBtn = document.querySelector(`.tab-btn[data-tab="${tabId}"]`);
            if (activeBtn) {
                activeBtn.classList.add('text-black', 'border-black', 'border-b-2');
                activeBtn.classList.remove('text-gray-500');
            }

            tabPanels.forEach(panel => {
                panel.classList.add('hidden');
            });

            const activePanel = document.getElementById(tabId);
            if (activePanel) {
                activePanel.classList.remove('hidden');
            }

            history.replaceState(null, '', `#${tabId}`);
        }

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const tabId = button.getAttribute('data-tab');
                activateTab(tabId);
            });
        });

        window.addEventListener('DOMContentLoaded', () => {
            const hash = window.location.hash.substring(1);
            const validTab = document.getElementById(hash);
            if (validTab) {
                activateTab(hash);
            } else {
                activateTab('terms-of-service');
            }
        });

        window.addEventListener('hashchange', () => {
            const hash = window.location.hash.substring(1);
            if (document.getElementById(hash)) {
                activateTab(hash);
            }
        });

    </script>
@endsection
