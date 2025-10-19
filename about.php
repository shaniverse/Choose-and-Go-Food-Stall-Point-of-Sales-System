<?php
require_once 'core/functions.php';
require_once 'config/db.php';

// Ensure session is started
ensureSession();

$pageTitle = 'About Us - Choose and Go';
require_once 'views/header.php';
require_once 'views/navbar.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold mb-3" style="background: linear-gradient(45deg, #0d6efd, #0dcaf0); background-clip: text; -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    About Us
                </h1>
                <p class="lead text-muted">Meet the talented team behind Choose and Go</p>
            </div>

            <div class="row g-4 justify-content-center">
                <!-- Team Member 1 -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="flex-shrink-0">
                                    <div class="avatar-lg rounded-circle overflow-hidden" style="width: 100px; height: 100px; border: 3px solid var(--primary-color);">
                                        <img src="assets/images/2x2 without name tag.jpg" alt="Shan G. Silvestrece" class="w-100 h-100 object-fit-cover">
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-4">
                                    <h4 class="mb-1">Shan G. Silvestrece</h4>
                                    <p class="text-muted mb-0">Lead Developer</p>
                                </div>
                            </div>
                            <div class="mb-4">
                                <h5 class="mb-3">Roles & Responsibilities</h5>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-primary bg-opacity-10 text-primary p-2">
                                        <i class="bi bi-diagram-3 me-1"></i> Project Leader
                                    </span>
                                    <span class="badge bg-primary bg-opacity-10 text-primary p-2">
                                        <i class="bi bi-code-slash me-1"></i> Full-Stack Developer
                                    </span>
                                    <span class="badge bg-primary bg-opacity-10 text-primary p-2">
                                        <i class="bi bi-brush me-1"></i> UI/UX Designer
                                    </span>
                                    <span class="badge bg-primary bg-opacity-10 text-primary p-2">
                                        <i class="bi bi-database me-1"></i> Database Administrator
                                    </span>
                                    <span class="badge bg-primary bg-opacity-10 text-primary p-2">
                                        <i class="bi bi-shield-lock me-1"></i> Security Specialist
                                    </span>
                                    <span class="badge bg-primary bg-opacity-10 text-primary p-2">
                                        <i class="bi bi-phone me-1"></i> Mobile Developer
                                    </span>
                                </div>
                            </div>
                            <p class="mb-0">
                                Shan is responsible for the overall architecture and development of the Choose and Go system, 
                                ensuring a seamless experience for both customers and staff.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Team Member 2 -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="flex-shrink-0">
                                    <div class="avatar-lg rounded-circle overflow-hidden" style="width: 100px; height: 100px; border: 3px solid var(--primary-color);">
                                        <img src="assets/images/Ripalda image.jpg" alt="Euri Christian F. Ripalda" class="w-100 h-100 object-fit-cover">
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-4">
                                    <h4 class="mb-1">Euri Christian F. Ripalda</h4>
                                    <p class="text-muted mb-0">Business Owner</p>
                                </div>
                            </div>
                            <div class="mb-4">
                                <h5 class="mb-3">Roles & Responsibilities</h5>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-success bg-opacity-10 text-success p-2">
                                        <i class="bi bi-briefcase me-1"></i> Business Owner
                                    </span>
                                    <span class="badge bg-success bg-opacity-10 text-success p-2">
                                        <i class="bi bi-clipboard-data me-1"></i> Business Analyst
                                    </span>
                                </div>
                            </div>
                            <p class="mb-0">
                                Euri Christian oversees the business operations and ensures that the system meets all business 
                                requirements and delivers value to both the business and its customers.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="index.php" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Home
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/footer.php'; ?>
