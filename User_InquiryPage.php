<?php
session_start();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['username']);
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ and Inquiry | Mirror Your World</title>

    <link rel="stylesheet" href="Style/Required.css" />
    <link rel="stylesheet" href="Style/User_InquiryPageCSS.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

    <!-- Required -->

    <div class="navbar">
        <a href="User_Homepage.php">About</a>
        <a href="User_InquiryPage.php">FAQ</a>
        <a href="User_Showcase.php">Showcase</a>
    </div>

    <div class="logo">
        <img src="Assets/icon_Logo.png" alt="Logo" style="width: 30px">
    </div>

    <div class="profile-container" style="position: fixed; top: 10px; right: 20px; z-index: 1000; border-radius: 20px;">
        <button class="btn dropdown-trigger" data-target="dropdown1" style=" border-radius: 20px; padding: 0; background-color: transparent; border: none; cursor: pointer;" onclick="toggleDropdown()">
            <img src="Assets/icon_Profile.png" class="iconProfile" alt="Profile Icon" width="40px" height="40px" style="width: 25px; height: 25px; object-fit: cover; cursor: pointer; transition: filter 0.3s ease;" onmouseover="this.style.filter='invert(1)';" onmouseout="this.style.filter='invert(0)';" />
        </button>

        <br />
        <ul id="dropdown1" class="dropdown-content" style="transition: 0.3s; display: none; position: absolute; top: 60px; right: 0; background-color: white; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15); width: 200px; padding: 0; margin: 0;">
            <?php if ($isLoggedIn): ?>
                <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s;">
                    <a href="User_AccountPage.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">View Profile</a>
                </li>
                <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s;">
                    <a href="User_ActiveBookings.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">My Appointments</a>
                </li>
                <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s;">
                    <a href="User_LogoutProcess.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Logout</a>
                </li>
            <?php else: ?>
                <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s;">
                    <a href="User_LoginPage.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Login</a>
                </li>
                <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s;">
                    <a href="User_SignupPage.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Sign Up</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>


    <div class="BGhome-container">

        <div class="text-container">
            <h1>Are we the <br>perfect fit <br> for you?</h1>
            <p>Frequently asked questions and <br /> inquiry page.</p>

            <h2> Still have questions? <br> Don't hesitate to email us!</h2>
            <button onclick="sendEmail()" class="btn-email">Email Us</button>
        </div>

        <img src="Assets/bg_HomePage.png" alt="Full-Screen Image" class="BGhome">
    </div>

    <br>

    <!-- Required -->

    <a href="#" title="Go back to top" class="uparrow2">
        <img src="Assets/icon_uparrow.png" class="uparrow3">
        <span class="tooltip">Go back to top</span>
    </a>

    <!-- Page 2 -->

    <div class="PageTwo-container">

        <center>
            <div class="logoHeader-container">
                <img src="Assets/icon_Logo.png" class="logoHeader">
            </div>

            <div class="navbar-2">
                <table>
                    <tr>
                        <td> <a href="#section-faq" class="navbar-link"> FAQS </a> </td>
                        <td> <a href="#section-services" class="navbar-link"> Services Offered </a> </td>
                        <td> <a href="#section-materials" class="navbar-link"> Available Materials </a> </td>
                        <td id="section-faq"> <a href="#section-policies" class="navbar-link"> Policies </a> </td>
                    </tr>
                </table>
            </div>

            <div class="faq-container">
                <div class="faq-frame" id="faq-frame-1">
                    <p>How do I book an appointment?</p>
                </div>
                <div class="faq-frame" id="faq-frame-2">
                    <p>Do you accept custom designs?</p>
                </div>
                <div class="faq-frame" id="faq-frame-3">
                    <p>Do you offer warranties?</p>
                </div>
                <div class="faq-frame" id="faq-frame-4">
                    <p>What services do you provide?</p>
                </div>
                <div class="faq-frame" id="faq-frame-5">
                    <p>Which areas do the company serve?</p>
                </div>
                <div class="faq-frame" id="faq-frame-6">
                    <p>Do you offer consultations?</p>
                </div>
                <div class="faq-frame" id="faq-frame-7">
                    <p>What is your cancellation policy?</p>
                </div>
                <div class="faq-frame" id="faq-frame-8">
                    <p>What are the modes and terms of payment?</p>
                </div>
                <div class="faq-frame" id="faq-frame-9">
                    <p>What should I do to prepare for the installation?</p>
                </div>
                <div class="faq-frame" id="faq-frame-10">
                    <p>How long do the installations take?</p>
                </div>
                <div class="faq-frame" id="faq-frame-11">
                    <p>What is the process after booking an appointment?</p>
                </div>
                <div class="faq-frame" id="faq-frame-12">
                    <p>How and When can I leave a review?</p>
                </div>
            </div>

            <div class="faq-modal" id="faq-modal" style="display: none;">
                <div class="faq-modal-content">
                    <span class="faq-close" id="faq-close">&times;</span>
                    <h2 id="faq-modal-title">Title</h2>
                    <p id="faq-modal-description">Description</p>
                </div>
            </div>

            <div class="txtServices-container" id="section-services">
                <h2 class="txtServices"> Services Offered </h2>
            </div>

            <div class="services-container">
                <div class="frames-container">
                    <!-- Frame 1 -->
                    <div class="frame" id="frame1">
                        <img src="Assets/icon_Aluminum.png" alt="Icon 1" class="frame-icon">
                        <h3 class="frame-title">Aluminum Installation</h3>
                        <p class="frame-description">Enhance your space with durable, lightweight, and modern aluminum solutions. From sturdy frames to sleek partitions, our aluminum installations offer strength, versatility, and a contemporary aesthetic.</p>
                        <button class="learn-more-btn" onclick="openModal(1)">Learn More</button>
                    </div>

                    <!-- Frame 2 -->
                    <div class="frame" id="frame2">
                        <img src="Assets/icon_Glass.png" alt="Icon 2" class="frame-icon">
                        <h3 class="frame-title">Glass Installation</h3>
                        <p class="frame-description">Transform any space with our premium glass installations. Whether for mirrors, panels, or partitions, our high-quality glasswork delivers elegance, clarity, and a refined modern touch.</p>
                        <button class="learn-more-btn" onclick="openModal(2)">Learn More</button>
                    </div>

                    <!-- Frame 3 -->
                    <div class="frame" id="frame3">
                        <img src="Assets/icon_GlassandAluminum.png" alt="Icon 3" class="frame-icon">
                        <h3 class="frame-title">Aluminum and Glass <br> Installation</h3>
                        <p class="frame-description">Experience the perfect balance of durability and sophistication with our aluminum and glass installations. Designed for strength and style, they bring a seamless, polished look to any project.</p>
                        <button class="learn-more-btn" onclick="openModal(3)">Learn More</button>
                    </div>
                </div>

                <!-- Modal -->
                <div id="modal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn" onclick="closeModal()">&times;</span>
                        <div class="modal-body">
                            <div id="modal-left" class="modal-left">
                                <img src="" alt="Image" id="modal-image">
                            </div>
                            <div id="modal-right" class="modal-right">
                                <h3 id="modal-title">Title</h3>
                                <p id="modal-description">Detailed description of the content will go here.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <center>
                    <h2 class="materials-title" id="section-materials"> Available Materials </h2>

                    <div class="material-scroll-container">
                        <div class="material-frame" onclick="openMaterialModal('Sample Title', 'Sample Subtitle', 'Sample Description', 'Available', 'Detail 1', 'Detail 2', 'Detail 3', 'Detail 4')">
                            <img src="Assets/placeholder2.jpg">
                            <div class="material-frame-title">Tempered Glass</div>
                            <div class="material-frame-subtitle">Strong and heat-resistant safety glass.</div>
                            <div class="material-frame-description">Available</div>
                        </div>

                        <div class="material-frame" onclick="openMaterialModal('Sample Title', 'Sample Subtitle', 'Sample Description', 'Available', 'Detail 1', 'Detail 2', 'Detail 3', 'Detail 4')">
                            <img src="Assets/placeholder1.jpg">
                            <div class="material-frame-title">Frosted Glass</div>
                            <div class="material-frame-subtitle">Opaque glass for privacy and aesthetics.</div>
                            <div class="material-frame-description">Available</div>
                        </div>

                        <div class="material-frame" onclick="openMaterialModal('Sample Title', 'Sample Subtitle', 'Sample Description', 'Available', 'Detail 1', 'Detail 2', 'Detail 3', 'Detail 4')">
                            <img src="Assets/placeholder3.jpg">
                            <div class="material-frame-title"> Laminated Glass</div>
                            <div class="material-frame-subtitle">Safety glass with soundproofing features. </div>
                            <div class="material-frame-description">Available</div>
                        </div>

                        <div class="material-frame" onclick="openMaterialModal('Sample Title', 'Sample Subtitle', 'Sample Description', 'Available', 'Detail 1', 'Detail 2', 'Detail 3', 'Detail 4')">
                            <img src="Assets/placeholder4.jpg">
                            <div class="material-frame-title"> Clear Acrylic Sheet</div>
                            <div class="material-frame-subtitle">Transparent and lightweight glass alternative.</div>
                            <div class="material-frame-description">Available</div>
                        </div>

                        <div class="material-frame" onclick="openMaterialModal('Sample Title', 'Sample Subtitle', 'Sample Description', 'Available', 'Detail 1', 'Detail 2', 'Detail 3', 'Detail 4')">
                            <img src="Assets/placeholder5.jpg">
                            <div class="material-frame-title"> Frosted Acrylic Sheet</div>
                            <div class="material-frame-subtitle">Semi-transparent acrylic for privacy and elegance.</div>
                            <div class="material-frame-description">Available</div>
                        </div>

                        <div class="material-frame" onclick="openMaterialModal('Sample Title', 'Sample Subtitle', 'Sample Description', 'Available', 'Detail 1', 'Detail 2', 'Detail 3', 'Detail 4')">
                            <img src="Assets/placeholder6.jpg">
                            <div class="material-frame-title"> Colored Acrylic Sheet</div>
                            <div class="material-frame-subtitle">Vibrant, durable acrylic for decorative use.</div>
                            <div class="material-frame-description">Available</div>
                        </div>
                    </div>

                    <div class="material-modal-overlay" id="material-modal">
                        <div class="material-modal">
                            <button class="material-modal-close" onclick="closeMaterialModal()">&times;</button>
                            <div class="material-modal-left">
                                <img src="Assets/placeholder2.jpg">
                                <div id="material-modal-status" class="material-modal-status">Available</div>
                            </div>
                            <div class="material-modal-right">
                                <div id="material-modal-title" class="material-modal-right-title">Title Here</div>
                                <div id="material-modal-detail1" class="material-modal-details">Detail 1: Information</div>
                                <div id="material-modal-detail2" class="material-modal-details">Detail 2: Information</div>
                                <div id="material-modal-detail3" class="material-modal-details">Detail 3: Information</div>
                                <div id="material-modal-detail4" class="material-modal-details">Detail 4: Information</div>
                            </div>
                        </div>
                    </div>

                    <div class="policies-container">
                        <h2 class="txtPolicies" id="section-policies"> Policies </h2>
                        <div class="policy-section">
                            <h2 class="policy-section-header">1. Booking Policy</h2>
                            <div class="policy-item"><span>Advance Booking:</span> Appointments should be booked at least <strong> 1 day </strong> in advance to ensure availability.</div>
                            <div class="policy-item"><span>Required Information:</span> Customers must provide accurate details during booking, including location, service type, and contact information.</div>
                        </div>
                        <div class="policy-section">
                            <h2 class="policy-section-header">2. Cancellation and Rescheduling Policy</h2>
                            <div class="policy-item"><span>Cancellation Deadline:</span> Cancellations must be made within 12 hours after booking the appointment. Failure to do so may result in a cancellation fee.</div>
                            <div class="policy-item"><span>Rescheduling Policy:</span> Rescheduling is allowed with at least 1 day notice, subject to availability.</div>
                        </div>
                        <div class="policy-section">
                            <h2 class="policy-section-header">3. Payment Policy</h2>
                            <div class="policy-item"><span>Deposit:</span> A percentage of the estimated price deposit is required to secure your appointment, payable via bank, cash, or online wallets.</div>
                            <div class="policy-item"><span>Full Payment:</span> The remaining balance is due upon project completion or as per the agreement.</div>
                            <div class="policy-item"><span>Accepted Payment Methods:</span> We accept cash, bank transfers, and online payment methods like Gcash, PayMaya, GoTyme, and SeaBank.</div>
                        </div>
                        <div class="policy-section">
                            <h2 class="policy-section-header">4. Warranty Policy</h2>
                            <div class="policy-item"><span>Workmanship Warranty:</span> We provide a 6-month on all installations for defects in materials and workmanship.</div>
                            <div class="policy-item"><span>Exclusions:</span> The warranty does not cover damages caused by misuse, accidents, or natural wear and tear.</div>
                        </div>
                        <div class="policy-section">
                            <h2 class="policy-section-header">5. Service Area Policy</h2>
                            <div class="policy-item"><span>Covered Areas:</span> We primarily serve Pasig area only. If you’re outside this area, additional charges may apply for travel.</div>
                            <div class="policy-item"><span>Additional Fees:</span> Projects outside the service area may incur extra travel or transportation fees.</div>
                        </div>
                        <div class="policy-section">
                            <h2 class="policy-section-header">6. Project Preparation Policy</h2>
                            <div class="policy-item"><span>Customer Responsibilities:</span> Customers must ensure the installation area is clear, accessible, and free of obstacles before the scheduled appointment.</div>
                            <div class="policy-item"><span>Site Access:</span> If our team is unable to access the site, a rebooking fee may apply.</div>
                        </div>
                        <div class="policy-section">
                            <h2 class="policy-section-header">7. Privacy Policy</h2>
                            <div class="policy-item"><span>Data Usage:</span> We collect and store customer information solely for booking and service purposes. Your data will not be shared with third parties without consent.</div>
                            <div class="policy-item"><span>Security Measures:</span> We use secure platforms to protect your personal and payment information.</div>
                        </div>
                    </div>
            </div>
        </center>

    </div>
    </div>

    <div class="Footer-container">
        <div class="text-container">
            <h1>Book a <br> consultation <br> now.</h1>
            <p>Still got questions? <button onclick="sendEmail()" class="btn-email2">Email Us</button> </p>
            <button class="btnConsult" onclick="showConsultAlert()">Consult</button>
        </div>

        <img src="Assets/bg_Footer.png" alt="Full-Screen Image" class="BGhome">
    </div>

</body>

<script>
    // Function to toggle the visibility of the dropdown content
    function toggleDropdown() {
        var dropdown = document.getElementById('dropdown1');
        // Toggle the display of the dropdown menu
        if (dropdown.style.display === 'none' || dropdown.style.display === '') {
            dropdown.style.display = 'block';
        } else {
            dropdown.style.display = 'none';
        }
    }

    // Close the dropdown if the user clicks anywhere outside the dropdown
    window.onclick = function(event) {
        // Check if the click is outside the dropdown or icon
        if (!event.target.matches('.dropdown-trigger') && !event.target.matches('.dropdown-trigger img')) {
            var dropdowns = document.querySelectorAll('.dropdown-content');
            dropdowns.forEach(function(dropdown) {
                dropdown.style.display = 'none'; // Close the dropdown
            });
        }
    };

    // Get elements
    const faqFrames = document.querySelectorAll('.faq-frame');
    const faqModal = document.getElementById('faq-modal');
    const faqClose = document.getElementById('faq-close');
    const faqModalTitle = document.getElementById('faq-modal-title');
    const faqModalDescription = document.getElementById('faq-modal-description');

    // Data for modal content
    const faqData = {
        "faq-frame-1": {
            title: "How do I book an Appointment / Consultation?",
            description: "You can book an appointment or consultation using the 'Consult' button included in the every page you visit from the website. We recommend to visit our FAQ and Inquiry Page first before booking."
        },
        "faq-frame-2": {
            title: "Do you accept custom designs?",
            description: "Absolutely! We specialize in custom acrylic and glass installations tailored to your unique style and requirements. Whether you have a specific design in mind or need help bringing your vision to life, we’re here to make it happen."
        },
        "faq-frame-3": {
            title: "Do you offer warranties?",
            description: "Yes, we stand by the quality of our work. We offer warranties on our installations, though the coverage and duration vary depending on the type of project. Feel free to contact us for specific details on warranty terms."
        },
        "faq-frame-4": {
            title: "What services do you provide?",
            description: "We offer a range of professional acrylic and glass installation services, including custom partitions, mirrors, glass doors, display cases, signage, and more. Our team ensures precision and high-quality craftsmanship for every project."
        },
        "faq-frame-5": {
            title: "Which areas do the company serve?",
            description: "We are proudly based in Pasig and serve clients in the surrounding areas. If you’re unsure whether we can accommodate your location, feel free to reach out—we’ll do our best to assist you!"
        },
        "faq-frame-6": {
            title: "Do you offer consultations?",
            description: "Yes! We provide consultations to better understand your needs and recommend the best solutions for your space. Whether you need advice on materials, design, or installation, we’re happy to guide you through the process."
        },
        "faq-frame-7": {
            title: "What is your cancellation policy?",
            description: "We understand that plans can change. If you need to cancel or reschedule, please notify us as soon as possible. Cancellations made within a certain timeframe may be eligible for a refund or rescheduling, while late cancellations may be subject to a fee. Contact us for full details on our policy. For Booking cancellations, you are able to cancel a booking within 12 hours."
        },
        "faq-frame-8": {
            title: "What are the modes and terms of payment?",
            description: "For your convenience, we accept various payment methods, including bank transfers, online payments, and cash. Depending on the project, a deposit may be required before we begin, with the remaining balance due upon completion. Specific payment terms will be discussed during your consultation."
        },
        "faq-frame-9": {
            title: "What should I do to prepare for the installation?",
            description: "To ensure a smooth and efficient installation process, please make sure the designated area is clear and accessible. If any special preparations are needed, we will provide you with instructions ahead of time. Our team will also confirm all details before the installation date."
        },
        "faq-frame-10": {
            title: "How long do the installations take?",
            description: "The duration of an installation depends on the complexity and scope of the project. Small installations may take just a few hours, while larger or more intricate designs may require additional time. We will provide you with an estimated timeline after assessing your project."
        },
        "faq-frame-11": {
            title: "What is the process after booking an appointment?",
            description: "Once your appointment is booked, our team will confirm the details and, if necessary, schedule a consultation. We’ll then take measurements, finalize the design, and plan the installation process to ensure everything runs smoothly. You’ll be kept informed every step of the way!"
        },
        "faq-frame-12": {
            title: "How and when can I leave a review?",
            description: "You can leave a review once your installation is complete, or if you'd like, you can share your thoughts on how we handled your booking process and our customer service along the way. It’s entirely up to you! We value all feedback to help us improve and serve you better."
        },
    };

    // Add click event to each FAQ frame
    faqFrames.forEach(frame => {
        frame.addEventListener('click', function() {
            const frameId = this.id;
            const {
                title,
                description
            } = faqData[frameId];
            faqModalTitle.textContent = title;
            faqModalDescription.textContent = description;
            faqModal.style.display = 'flex';
        });
    });

    // Close modal
    faqClose.addEventListener('click', () => {
        faqModal.style.display = 'none';
    });

    // Close modal when clicking outside the modal content
    window.addEventListener('click', (event) => {
        if (event.target === faqModal) {
            faqModal.style.display = 'none';
        }
    });


    function openMaterialModal(title, subtitle, description, status, detail1, detail2, detail3, detail4) {
        document.getElementById('material-modal-title').textContent = title;
        document.getElementById('material-modal-status').textContent = status;
        document.getElementById('material-modal-detail1').textContent = `Product Details: ${detail1}`;
        document.getElementById('material-modal-detail2').textContent = `Application: ${detail2}`;
        document.getElementById('material-modal-detail3').textContent = `Benefits: ${detail3}`;
        document.getElementById('material-modal-detail4').textContent = `Base Price: ${detail4}`;
        document.getElementById('material-modal').style.display = 'flex';
    }

    function closeMaterialModal() {
        document.getElementById('material-modal').style.display = 'none';
    }


    //  Frame and Modal

    function openModal(frameId) {
        // Select the modal and its content areas
        const modal = document.getElementById('modal');
        const modalImage = document.getElementById('modal-image');
        const modalTitle = document.getElementById('modal-title');
        const modalDescription = document.getElementById('modal-description');

        if (frameId === 2) {
            modalImage.src = 'Assets/pic-expertise.jpg';
            modalTitle.innerHTML = 'We do Glass Installations!';
            modalDescription.innerHTML = 'Transform your space with our premium glass solutions. Whether youre looking for sleek glass panels, mirrors, or custom designs, we deliver clarity, elegance, and precision. Perfect for any style, our glass pieces are crafted to enhance your environment with a touch of modern sophistication.';
        } else if (frameId === 1) {
            modalImage.src = 'Assets/pic-passion.jpg';
            modalTitle.innerHTML = 'We do Aluminum Installations!';
            modalDescription.innerHTML = 'Add a touch of industrial strength and sleek design with our high-quality aluminum products. From frames to custom pieces, aluminum offers durability, lightweight flexibility, and a clean, contemporary look. Whether for structural or aesthetic purposes, our aluminum creations are built to last and impress.';
        } else if (frameId === 3) {
            modalImage.src = 'Assets/pic-story.jpg';
            modalTitle.innerHTML = 'We do Glass and Aluminum Installations';
            modalDescription.innerHTML = 'Experience the perfect fusion of elegance and strength with our glass and aluminum combinations. The smooth, reflective beauty of glass paired with the sturdy, modern feel of aluminum creates stunning designs that elevate any project. Custom-made to your specifications, these pieces are as versatile as they are beautiful.';
        }

        // Display the modal
        modal.style.display = 'block';
    }

    function closeModal() {
        // Close the modal by setting its display to none
        const modal = document.getElementById('modal');
        modal.style.display = 'none';
    }

    // Close modal if the user clicks outside the modal content
    window.onclick = function(event) {
        const modal = document.getElementById('modal');
        if (event.target === modal) {
            closeModal();
        }
    }

    function showConsultAlert() {
        Swal.fire({
            title: 'Are we the perfect fit for you?',
            text: 'Please visit the FAQs page before proceeding!',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Proceed to Form',
            cancelButtonText: 'Visit FAQs',
            reverseButtons: true,
            customClass: {
                popup: 'custom-swal-popup',
                title: 'custom-swal-title',
                content: 'custom-swal-text',
                confirmButton: 'custom-swal-confirm',
                cancelButton: 'custom-swal-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to the form page
                window.location.href = 'User_FormsPage.php';
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Redirect to the FAQs page
                window.location.href = 'User_InquiryPage.php';
            }
        });
    }

    function showConsultAlert() {
        // Check if the user is logged in
        <?php if ($isLoggedIn): ?>
            Swal.fire({
                title: 'Are we the perfect fit for you?',
                text: 'Please visit the FAQs page before proceeding!',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Proceed to Form',
                cancelButtonText: 'Visit FAQs',
                reverseButtons: true,
                customClass: {
                    popup: 'custom-swal-popup',
                    title: 'custom-swal-title',
                    content: 'custom-swal-text',
                    confirmButton: 'custom-swal-confirm',
                    cancelButton: 'custom-swal-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'User_FormsPage.php';
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    window.location.href = 'User_InquiryPage.php';
                }
            });
        <?php else: ?>
            Swal.fire({
                title: 'Please Log In or Sign Up first',
                text: 'You need to be logged in to proceed with the consultation.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Log In',
                cancelButtonText: 'Sign Up',
                reverseButtons: true,
                customClass: {
                    popup: 'custom-swal-popup',
                    title: 'custom-swal-title',
                    content: 'custom-swal-text',
                    confirmButton: 'custom-swal-confirm',
                    cancelButton: 'custom-swal-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'User_LoginPage.php';
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    window.location.href = 'User_SignupPage.php'; // Redirect to signup page
                }
            });
        <?php endif; ?>
    }

    function sendEmail() {
        window.open("https://mail.google.com/mail/?view=cm&fs=1&to=hellodeesy@gmail.com", "_blank");
    }
</script>

</html>