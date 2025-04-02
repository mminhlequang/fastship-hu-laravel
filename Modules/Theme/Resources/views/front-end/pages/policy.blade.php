@extends('theme::front-end.master')

@section('content')
    <!-- Hero Section with Search -->
    <div class="relative py-12 mb-4 overflow-hidden">
        <!-- Background image using <img> -->
        <img
                src="{{ url('assets/images/banner_ask.svg') }}"
                alt="Background Image"
                class="absolute inset-0 w-full h-full object-cover"
        />
        <!-- Content inside -->
        <div class="relative z-10 py-8">
            <h1
                    class="text-3xl font-medium mb-6 text-white px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80"
            >
                Policy
            </h1>
        </div>
    </div>

    <!-- FAQ Section -->
    <section class="px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
        <!-- Menu categories -->
        <div class="border-b">
            <div class="flex flex-wrap justify-start overflow-x-auto no-scrollbar">
                <button
                        class="px-4 py-3 text-black border-b-2 font-medium border-black whitespace-nowrap hover:text-secondary"
                >
                    Background/White
                </button>
                <button
                        class="px-4 py-3 text-gray-500 whitespace-nowrap hover:text-secondary"
                >
                    Payment policy
                </button>
                <button class="px-4 py-3 text-gray-500 font-medium whitespace-nowrap">
                    Fredoka
                </button>
                <button
                        class="px-4 py-3 text-gray-500 whitespace-nowrap hover:text-secondary"
                >
                    Return policy
                </button>
            </div>
        </div>
        <p class="my-8">
            Preamble<br>
            YAZIO GmbH (hereinafter, “YAZIO” or the “Provider”), with registered office in Erfurt, operates the website
            www.yazio.com (hereinafter, the “Website”) as well as the YAZIO app, a digital calorie counter (hereinafter,
            the “App” or the “Product”) for Android and iOS.
            The following Data Protection Policy informs you about the types of personal data of YAZIO users that are
            processed, the purposes for which they are processed, and the scope of processing. The Data Protection
            Policy applies to all processing of personal data performed by YAZIO, both in connection with service
            provision and, in particular, on the Website and in the YAZIO App, which users can install on their mobile
            end device, as well as within external online presences, such as YAZIO’s social media profiles (hereinafter,
            collectively referred to as the “Online Offer”).
            1. Controller & Data Protection Officer
            All incoming and outgoing data—both in the communication with the Apps and in third-party provider
            communication—are transmitted in encrypted form. The encrypted connection when using the YAZIO Website can
            be seen, for instance, via the address bar of the browser being used, which begins with “https://”, and via
            the encryption symbol found there. Because of encryption, the transmitted data cannot be read by third
            parties.
            3. Collection, Processing, & Use of Personal Data
            Personal data
            “Personal data” for the purposes of the GDPR means any information relating to an identified or identifiable
            natural person; an identifiable natural person is one who can be identified, directly or indirectly, in
            particular by reference to an identifier such as a name, an identification number, location data, an online
            identifier or to one or more factors specific to the physical, physiological, genetic, mental, economic,
            cultural or social identity of that natural person;
            Personal data (e.g., e-mail address, nutritional data in the App) will be processed by the Provider only
            pursuant to the provisions of applicable data protection law. The following provisions inform you about the
            nature, scope and purpose of the collection, processing and use of personal data
            Collection of data when using the YAZIO Website
            When visiting the Website www.yazio.com, the web server, on the basis of YAZIO's legitimate interests under
            Article 6(1)(f) GDPR, automatically records log files, which cannot be attributed to any specific person, if
            this is necessary for the App’s functionality and is not outweighed by the interest in protecting the user's
            personal data. These data include, e.g., the browser type and version, the operating system used, referrer
            URL (the previously visited site), IP address of the requesting computer, access date and time of server
            request, and the file requested by the client (file name and URL). These data are collected only for the
            purpose of statistical analysis and for security reasons (e.g., to investigate acts of misuse or fraud) and
            are stored for seven days and then erased. If it should be necessary to retain the data for a longer period
            of time for evidentiary purposes, they are exempt from erasure until final resolution of the respective
            event.
            3. Collection, Processing, & Use of Personal Data
            “Personal data” for the purposes of the GDPR means any information relating to an identified or identifiable
            natural person; an identifiable natural person is one who can be identified, directly or indirectly, in
            particular by reference to an identifier such as a name, an identification number, location data, an online
            identifier or to one or more factors specific to the physical, physiological, genetic, mental, economic,
            cultural or social identity of that natural person;
            Personal data (e.g., e-mail address, nutritional data in the App) will be processed by the Provider only
            pursuant to the provisions of applicable data protection law. The following provisions inform you about the
            nature, scope and purpose of the collection, processing and use of personal data
            When visiting the Website www.yazio.com, the web server, on the basis of YAZIO's legitimate interests under
            Article 6(1)(f) GDPR, automatically records log files, which cannot be attributed to any specific person, if
            this is necessary for the App’s functionality and is not outweighed by the interest in protecting the user's
            personal data. These data include, e.g., the browser type and version, the operating system used, referrer
            URL (the previously visited site), IP address of the requesting computer, access date and time of server
            request, and the file requested by the client (file name and URL). These data are collected only for the
            purpose of statistical analysis and for security reasons (e.g., to investigate acts of misuse or fraud) and
            are stored for seven days and then erased. If it should be necessary to retain the data for a longer period
            of time for evidentiary purposes, they are exempt from erasure until final resolution of the respective
            event.
            Further processing is handled by the payment service provider Stripe. The service is offered by Stripe
            Payments Europe Ltd, Block 4, Harcourt Centre, Harcourt Road, Dublin 2, Ireland. Information provided by the
            user (name, e-mail address, credit card data, start and end date, and, if applicable, termination data of
            subscriptions, invoice amount) is transmitted by YAZIO to Stripe pursuant to Article 6(1)(b) GDPR as part of
            the order process. User data are shared solely for the purpose of payment processing with the payment
            service provider Stripe Payments Europe Ltd. and only to the extent they are necessary for this.

        </p>
    </section>
@endsection
