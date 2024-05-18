 <?php 
  include "includes/site/data.php"; 
  include "includes/site/utils.php"; 

  $slug =  $_GET["slug"];

 $social_links;
 $services;
 $content;

// URL of the Sanity HTTP API endpoint you want to query
$url = 'https://om0khjs6.api.sanity.io/v2024-04-15/data/query/production';

// Query parameters
$query = '{
  "settings": *[_type == "settings"][0]{
    socialLinks[link in [*].links] {
    url,
    "icon": icon.name
  }
},
  "services": *[_type == "services"][0..8]{
     title,
    "slug": slug.current,
   
  },
  "service": *[_type == "services" && slug.current == "' . $slug . '"]{
    overview
  }[0],
}
';

// Parameters for the GET request
$params = array(
    'query' => $query,
    // Add any other parameters here if needed
);

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($params));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the request and get the response
$response = curl_exec($ch);

// Check if the request was successful
if ($response === false) {
    // Handle the error
    
    echo '<div id="form-result" class="alert alert-danger" role="alert">' . curl_error($ch) . '</div>' ;
} else {
    // Output the response
  $result = json_decode($response, true)["result"];
   $social_links = $result["settings"]["socialLinks"];
  $services = $result["services"];
  $service = $result["service"];


    if (isset($service) && isset($service["overview"]) && !empty($service["overview"])) {

    // Prepare secondary request data (assuming JSON body)
    $postData = json_encode(array(
      "portableText" => $service["overview"],
    ));

    // Initialize secondary cURL session for POST request
    $secondaryCh = curl_init("https://blog.sonamaxmarketing.com/api/to-html");
    curl_setopt($secondaryCh, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($secondaryCh, CURLOPT_POST, true);
    curl_setopt($secondaryCh, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($secondaryCh, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); // Set content type

    // Execute secondary request and get response
    $secondaryResponse = curl_exec($secondaryCh);

    // Check if secondary request was successful (optional, handle response)
    if (curl_errno($secondaryCh)) {
       echo '<div id="form-result" class="alert alert-danger" role="alert">' . curl_error($secondaryCh) . '</div>' ;
    } else {
      // Process the response from the secondary request (if needed)
      $secondaryResult = json_decode($secondaryResponse, true);
      // ... handle secondary result ... (e.g., process "html" if returned)
	    $content = $secondaryResult["html"];
      
    }

    // Close secondary cURL session
    curl_close($secondaryCh);
  } else {
    echo "Service not found or overview is empty.";
  }
}

// Close cURL session
curl_close($ch);
 
 ?>

 <!DOCTYPE html>
 <html lang="en">

 <?php include "includes/site/head.php" ?>

 <body>

     <div class="page-wrapper">

         <!-- Preloader -->
         <div class="preloader"></div>

         <!-- Main Header-->
         <?php include "includes/site/header.php"; ?>
         <!--End Main Header -->

         <!-- Start main-content -->
         <section class="page-title" style="background-image: url(images/background/page-title-bg.png);">
             <div class="auto-container">
                 <div class="title-outer text-center">
                     <h1 class="title">Service Details</h1>
                     <ul class="page-breadcrumb">
                         <li><a href="/">Home</a></li>
                         <li>Services</li>
                     </ul>
                 </div>
             </div>
         </section>


         <!--Start Services Details-->
         <section class="services-details">
             <div class="container">
                 <div class="row">
                     <!--Start Services Details Sidebar-->
                     <div class="col-xl-4 col-lg-4">
                         <div class="service-sidebar">
                             <!--Start Services Details Sidebar Single-->
                             <div class="sidebar-widget service-sidebar-single">

                                 <div class="sidebar-service-list">

                                 <!-- service-section -->
        <?php if(isset($services)): ?>
                                    <ul>
                                       <?php foreach($services as $service): ?>
                                            <li class="<?= $service["slug"] == $slug ? 'current' : '' ?>">
                                                <a href="services/<?= $service["slug"] ?>"><i
                                                        class="fas fa-angle-right">
                                                    </i><span><?= $service["title"] ?></span></a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
        <!-- End service-section -->
                                 </div>

                                 <div class="service-details-help">
                                     <div class="help-shape-1"></div>
                                     <div class="help-shape-2"></div>
                                     <h2 class="help-title">Contact with <br> us for any <br> advice</h2>
                                     <div class="help-icon">
                                         <span class=" lnr-icon-phone-handset"></span>
                                     </div>
                                     <div class="help-contact">
                                         <p>Need help? Talk to an expert</p>
                                         <a href="tel:<?= $site["phone"] ?>"><?= $site["phone"] ?></a>
                                     </div>
                                 </div>

                                 <!--Start Services Details Sidebar Single-->
                                 <div class="sidebar-widget service-sidebar-single mt-4">
                                     <div class="service-sidebar-single-btn wow fadeInUp" data-wow-delay="0.5s"
                                         data-wow-duration="1200m">
                                         <a 
                                           href="assets/overview.pdf" 
                                           class="theme-btn btn-style-one d-grid"
                                           download="sonamax-marketing-group"
                                           >
                                           <span
                                                 class="btn-title"><span class="fas fa-file-pdf"></span> download pdf
                                                 file</span></a>
                                     </div>
                                 </div>
                             </div>
                             <!--End Services Details Sidebar-->
                         </div>
                     </div>

                     <!--Start Services Details Content-->
                     <div class="col-xl-8 col-lg-8">
                         <div class="services-details__content">
                             <img src="images/resource/service-details.jpg" alt="" />

                             <div>
                                 <h2 class="mt-4">Service Overview</h2>
                                 <div class="project-details__content-left richtext">
			                         <?=$content; ?>
					            </div>
                             </div>



                             <div class="content mt-40">
                                 <div class="text">
                                     <h2>Service Center</h2>
                                     <p>At Sonamax, our Service Center is the heart of our operations, dedicated to
                                         providing exceptional support and assistance to our clients. Our team of
                                         experts is committed to delivering timely solutions, resolving issues, and
                                         ensuring the smooth operation of our clients' digital assets. Whether you need
                                         technical assistance, guidance, or advice, our Service Center is here to help
                                         you every step of the way. We pride ourselves on our responsive and
                                         customer-centric approach, putting your needs first and striving to exceed your
                                         expectations at every opportunity</p>
                                     <blockquote class="blockquote-one">Customer service is not a department, it's
                                         everyone's job.</blockquote>
                                 </div>
                                 <div class="feature-list mt-4">
                                     <div class="row clearfix">
                                         <div class="col-lg-6 col-md-6 col-sm-12 column">
                                             <img class="mb-3" src="images/resource/service-d1.jpg" alt="images" />
                                             <p>Our dedicated team of experts is here to provide exceptional support and
                                                 assistance to our clients. We pride ourselves on delivering timely
                                                 solutions and resolving issues to ensure the smooth operation of your
                                                 digital assets. Whether you need technical assistance, guidance, or
                                                 advice, our Service Center is here to help you every step of the way.
                                             </p>
                                         </div>
                                         <div class="col-lg-6 col-md-6 col-sm-12 column">
                                             <img class="mb-3" src="images/resource/service-d2.jpg" alt="images" />
                                             <p>At Sonamax, customer satisfaction is our top priority. We believe in
                                                 going above and beyond to meet your needs and exceed your expectations.
                                                 With our responsive and customer-centric approach, you can trust that
                                                 your concerns will be addressed promptly and professionally. Your
                                                 success is our success, and we are committed to providing the highest
                                                 level of service and support.</p>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                            <div class="innerpage mt-25">
    <h3>Frequently Asked Questions</h3>
    <p>Explore common questions about our services and how we can help you succeed:</p>
    <ul class="accordion-box wow fadeInRight">
        <!-- Block 1 -->
        <li class="accordion block">
            <div class="acc-btn">What digital marketing services do you offer?
                <div class="icon fa fa-plus"></div>
            </div>
            <div class="acc-content">
                <div class="content">
                    <div class="text">We offer a comprehensive range of digital marketing services, including search engine optimization (SEO), pay-per-click (PPC) advertising, social media marketing, content marketing, email marketing, and more. Our team can tailor a strategy to meet your specific needs and goals.</div>
                </div>
            </div>
        </li>
        <!-- Block 2 -->
        <li class="accordion block active-block">
            <div class="acc-btn">How can digital marketing benefit my business?
                <div class="icon fa fa-plus"></div>
            </div>
            <div class="acc-content current">
                <div class="content">
                    <div class="text">Digital marketing offers numerous benefits for businesses, including increased brand visibility, targeted audience reach, higher website traffic, lead generation, improved conversion rates, and measurable ROI. By leveraging digital channels effectively, you can engage with your audience, build relationships, and drive business growth.</div>
                </div>
            </div>
        </li>
        <!-- Block 3 -->
        <li class="accordion block">
            <div class="acc-btn">How do you measure the success of digital marketing campaigns?
                <div class="icon fa fa-plus"></div>
            </div>
            <div class="acc-content">
                <div class="content">
                    <div class="text">We utilize various metrics and analytics tools to measure the success of digital marketing campaigns, including website traffic, conversion rates, click-through rates (CTR), engagement metrics, return on ad spend (ROAS), and more. By tracking key performance indicators (KPIs), we can evaluate campaign effectiveness and make data-driven decisions to optimize performance.</div>
                </div>
            </div>
        </li>
        <!-- Block 4 -->
        <li class="accordion block">
            <div class="acc-btn">How long does it take to see results from digital marketing efforts?
                <div class="icon fa fa-plus"></div>
            </div>
            <div class="acc-content">
                <div class="content">
                    <div class="text">The timeline for seeing results from digital marketing efforts can vary depending on factors such as the competitiveness of your industry, the effectiveness of your strategy, and the channels you're utilizing. While some tactics may deliver immediate results, such as PPC advertising, others, like SEO, may take longer to yield noticeable outcomes. We work diligently to implement strategies that deliver both short-term wins and long-term success.</div>
                </div>
            </div>
        </li>
    </ul>
</div>


                         </div>
                     </div>
                     <!--End Services Details Content-->
                 </div>
             </div>
         </section>
         <!--End Services Details-->

         <?php include "includes/site/footer.php"; ?>

     </div><!-- End Page Wrapper -->


     <!-- Scroll To Top -->
     <div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-angle-up"></span></div>


     <script src="js/jquery.js"></script>
     <script src="js/popper.min.js"></script>
     <script src="js/bootstrap.min.js"></script>
     <script src="js/jquery.fancybox.js"></script>
     <script src="js/jquery-ui.js"></script>
     <script src="js/wow.js"></script>
     <script src="js/appear.js"></script>
     <script src="js/select2.min.js"></script>
     <script src="js/script.js"></script>
 </body>

 </html>