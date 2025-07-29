@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
    <div class="container my-5">
        <h1 class="mb-4">Privacy Policy for {{ setting('site_name' ?? 'Name Not Set') }}</h1>

        <p><strong>Last updated: July 18, 2025</strong></p>

        <p>Welcome to {{ setting('site_name' ?? 'Name Not Set') }}! We are an online job portal dedicated to connecting job seekers with employers. Your privacy is critically important to us. This Privacy Policy outlines the types of information we collect, how it's used, and the steps we take to protect your personal data.</p>

        ---

        <h3>1. Information We Collect</h3>
        <p>We collect various types of information to provide and improve our services to you:</p>
        <ul>
            <li><strong>Personal Identifiable Information (PII):</strong> This includes information you voluntarily provide when you register, create a profile, apply for a job, or post a job. This may include your name, email address, phone number, resume/CV, educational background, work experience, and any other details you choose to include in your profile or application. For employers, this may include company name, contact person details, and billing information.</li>
            <li><strong>Usage Data:</strong> We automatically collect information on how the service is accessed and used. This Usage Data may include information such as your computer's Internet Protocol (IP) address, browser type, browser version, the pages of our Service that you visit, the time and date of your visit, the time spent on those pages, unique device identifiers, and other diagnostic data.</li>
            <li><strong>Cookies and Tracking Technologies:</strong> We use cookies and similar tracking technologies to track the activity on our Service and hold certain information. Cookies are files with a small amount of data which may include an anonymous unique identifier. You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. However, if you do not accept cookies, you may not be able to use some portions of our Service.</li>
        </ul>

        ---

        <h3>2. How We Use Your Information</h3>
        <p>The information we collect is used for various purposes:</p>
        <ul>
            <li>To provide and maintain our Service, including facilitating job applications and job postings.</li>
            <li>To notify you about changes to our Service.</li>
            <li>To allow you to participate in interactive features of our Service when you choose to do so.</li>
            <li>To provide customer support.</li>
            <li>To gather analysis or valuable information so that we can improve our Service.</li>
            <li>To monitor the usage of our Service.</li>
            <li>To detect, prevent, and address technical issues.</li>
            <li>To provide you with news, special offers, and general information about other goods, services, and events which we offer that are similar to those that you have already purchased or enquired about unless you have opted not to receive such information.</li>
        </ul>

        ---

        <h3>3. Sharing Your Information</h3>
        <p>We may share your personal information in the following situations:</p>
        <ul>
            <li><strong>With Employers/Job Seekers:</strong> If you are a job seeker, your profile and application information will be shared with employers to whom you apply. If you are an employer, your job postings will be visible to job seekers.</li>
            <li><strong>With Service Providers:</strong> We may employ third-party companies and individuals to facilitate our Service, provide the Service on our behalf, perform Service-related services, or assist us in analyzing how our Service is used. These third parties have access to your Personal Data only to perform these tasks on our behalf and are obligated not to disclose or use it for any other purpose.</li>
            <li><strong>For Legal Reasons:</strong> We may disclose your Personal Data in the good faith belief that such action is necessary to:
                <ul>
                    <li>Comply with a legal obligation.</li>
                    <li>Protect and defend the rights or property of {{ setting('site_name') }}.</li>
                    <li>Prevent or investigate possible wrongdoing in connection with the Service.</li>
                    <li>Protect the personal safety of users of the Service or the public.</li>
                    <li>Protect against legal liability.</li>
                </ul>
            </li>
            <li><strong>Business Transfer:</strong> If {{ setting('site_name') }} is involved in a merger, acquisition, or asset sale, your Personal Data may be transferred. We will provide notice before your Personal Data is transferred and becomes subject to a different Privacy Policy.</li>
        </ul>

        ---

        <h3>4. Security of Data</h3>
        <p>The security of your data is important to us, but remember that no method of transmission over the Internet or method of electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your Personal Data, we cannot guarantee its absolute security.</p>

        ---

        <h3>5. Your Data Protection Rights</h3>
        <p>Depending on your location, you may have the following data protection rights:</p>
        <ul>
            <li>The right to access, update, or delete the information we have on you.</li>
            <li>The right of rectification.</li>
            <li>The right to object.</li>
            <li>The right of restriction.</li>
            <li>The right to data portability.</li>
            <li>The right to withdraw consent.</li>
        </ul>
        <p>To exercise any of these rights, please contact us at <strong>[Your Contact Email/Method, e.g., privacy@yourdomain.com]</strong>.</p>

        ---

        <h3>6. Links to Other Sites</h3>
        <p>Our Service may contain links to other sites that are not operated by us. If you click on a third-party link, you will be directed to that third party's site. We strongly advise you to review the Privacy Policy of every site you visit. We have no control over and assume no responsibility for the content, privacy policies, or practices of any third-party sites or services.</p>

        ---

        <h3>7. Children's Privacy</h3>
        <p>Our Service does not address anyone under the age of 18 ("Children"). We do not knowingly collect personally identifiable information from anyone under the age of 18. If you are a parent or guardian and you are aware that your Children have provided us with Personal Data, please contact us. If we become aware that we have collected Personal Data from children without verification of parental consent, we take steps to remove that information from our servers.</p>

        ---

        <h3>8. Changes to This Privacy Policy</h3>
        <p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page. We will let you know via email and/or a prominent notice on our Service, prior to the change becoming effective and update the "last updated" date at the top of this Privacy Policy. You are advised to review this Privacy Policy periodically for any changes. Changes to this Privacy Policy are effective when they are posted on this page.</p>

        ---

        <h3>9. Contact Us</h3>
        <p>If you have any questions about this Privacy Policy, please contact us:</p>
        <ul>
            <li>By email: <strong>[Your Support Email Address]</strong></li>
            <li>By visiting this page on our website: <strong>[Link to your Contact Us page]</strong></li>
        </ul>
    </div>
@endsection

@section('footer')
    <footer class="text-center text-muted py-4 mt-auto border-top small bg-light">
        © {{ date('Y') }} <strong> {{ setting('site_name' ?? 'Name Not Set') }}</strong>. All rights reserved.
        <span class="d-block d-md-inline mt-1 mt-md-0">| Built with ❤️ for job seekers and employers.</span>
    </footer>
@endsection