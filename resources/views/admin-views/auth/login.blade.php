<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="stylesheet" href="assets/css/pages/auth.css">
    <style>
        /* Additional custom styles without changing existing classes */
        #auth {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        #auth-left {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 0 20px 20px 0;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .auth-logo img {
            transition: transform 0.3s ease;
        }
        
        .auth-logo img:hover {
            transform: scale(1.05);
        }
        
        .auth-title {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }
        
        .form-control {
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
            border-radius: 12px;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }
        
        .form-control-icon {
            transition: color 0.3s ease;
        }
        
        .form-control:focus + .form-control-icon {
            color: #667eea;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        #auth-right img {
            border-radius: 20px 0 0 20px;
            box-shadow: 20px 0 40px rgba(0, 0, 0, 0.2);
            transition: transform 0.5s ease;
        }
        
        #auth-right:hover img {
            transform: scale(1.02);
        }
        
        .auth-subtitle {
            color: #64748b;
            font-weight: 500;
        }
        
        .text-gray-600 {
            color: #64748b !important;
        }
        
        a {
            color: #667eea;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        a:hover {
            color: #764ba2;
        }
        
        /* Floating animation for form elements */
        @keyframes floatIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        #auth-left > * {
            animation: floatIn 0.6s ease-out;
        }
        
        #auth-left > *:nth-child(1) { animation-delay: 0.1s; }
        #auth-left > *:nth-child(2) { animation-delay: 0.2s; }
        #auth-left > *:nth-child(3) { animation-delay: 0.3s; }
        #auth-left > *:nth-child(4) { animation-delay: 0.4s; }
    </style>
</head>

<body>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo">
                        <a href="index.html"><img src="{{ asset('admin/assets/images/logo/logo.png') }}" class="w-25 h-25" alt="Logo"></a>
                    </div>
                    <h1 class="auth-title">Welcome Back</h1>
                    <p class="auth-subtitle mb-5">Sign in to access your admin dashboard</p>

                    <form action="{{ route('admin.loginCheck') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" name="email" placeholder="Email or Username" required>
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" class="form-control form-control-xl" name="password" placeholder="Password" required>
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check form-check-lg d-flex align-items-center">
                                <input class="form-check-input me-2" type="checkbox" id="flexCheckDefault">
                                <label class="form-check-label text-gray-600" for="flexCheckDefault">
                                    Remember me
                                </label>
                            </div>
                            <a href="auth-forgot-password.html" class="text-sm text-gray-600">Forgot password?</a>
                        </div>
                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-3 w-100 py-3">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                        </button>
                    </form>
                    <div class="text-center mt-5 text-lg fs-4">
                        <p class="text-gray-600">New to our platform? 
                            <a href="auth-register.html" class="font-bold">Create an account</a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">
                    <img src="{{ asset('admin/assets/images/login.jpg') }}" style="width:100%; height: 100%; object-fit:cover;" alt="Admin Dashboard">
                </div>
            </div>
        </div>
    </div>
</body>

</html>