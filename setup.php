<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHOOSE AND GO - System Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">                        <h2 class="card-title text-center mb-4">
                            <img src="assets/images/CHOOSE AND GO LOGO.jpg" alt="Logo" width="60" height="60" class="me-2">
                            CHOOSE AND GO Food Stall POS
                        </h2>
                        
                        <div id="setupStatus" class="alert alert-info">
                            Initializing system...
                        </div>

                        <div class="mb-4">
                            <h5>Test User Credentials:</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Role</th>
                                            <th>Employee ID</th>
                                            <th>Password</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Admin</td>
                                            <td>1</td>
                                            <td>test123</td>
                                        </tr>
                                        <tr>
                                            <td>Cashier</td>
                                            <td>2</td>
                                            <td>test123</td>
                                        </tr>
                                        <tr>
                                            <td>Kitchen Staff</td>
                                            <td>3</td>
                                            <td>test123</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="text-center">
                            <a href="index.php" class="btn btn-primary" style="display: none;" id="startButton">
                                Start Using the System
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to initialize the database
        async function initializeSystem() {
            try {
                const response = await fetch('config/config/init_db.php');
                const result = await response.text();
                
                const statusDiv = document.getElementById('setupStatus');
                const startButton = document.getElementById('startButton');
                
                if (result.includes('successfully')) {
                    statusDiv.className = 'alert alert-success';
                    statusDiv.innerHTML = 'System initialized successfully! You can now start using the system.';
                    startButton.style.display = 'inline-block';
                } else {
                    statusDiv.className = 'alert alert-danger';
                    statusDiv.innerHTML = 'Error initializing system: ' + result;
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('setupStatus').className = 'alert alert-danger';
                document.getElementById('setupStatus').innerHTML = 'Error initializing system. Please check the console for details.';
            }
        }

        // Initialize the system when the page loads
        window.addEventListener('load', initializeSystem);
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
