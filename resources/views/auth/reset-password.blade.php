<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="LaraBids - Online Auction Platform">
    <title>Reset Password - LaraBids</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-dark: #000000;
            --primary-gold: #d4af37;
            --gold-light: #f1d592;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            padding: 2rem 0;
        }

        .auth-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .auth-card {
            background: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 20px;
            padding: 4rem 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        .logo-text {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #d4af37 0%, #f1d592 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .icon-circle {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #d4af37 0%, #f1d592 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
        }

        .icon-circle i {
            font-size: 3rem;
            color: #000000;
        }

        .auth-subtitle {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 1rem;
            font-size: 1.3rem;
            font-weight: 600;
        }

        .auth-description {
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 2.5rem;
            font-size: 0.95rem;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 50px;
            padding: 0.9rem 1.5rem;
            color: #ffffff;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--primary-gold);
            box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.15);
            color: #ffffff;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-label {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .btn-auth {
            background: linear-gradient(135deg, #d4af37 0%, #f1d592 100%);
            border: none;
            border-radius: 50px;
            padding: 0.9rem 2rem;
            color: #000000;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.4);
            color: #000000;
        }

        .auth-link {
            color: var(--primary-gold);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .auth-link:hover {
            color: var(--gold-light);
            text-decoration: underline;
        }

        .divider {
            height: 1px;
            background: rgba(212, 175, 55, 0.2);
            margin: 2rem 0;
        }

        .invalid-feedback {
            color: #ff6b6b;
            font-size: 0.85rem;
            display: block;
            margin-top: 0.5rem;
        }

        .error-message {
            color: #ff6b6b;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: none;
        }

        @media (max-width: 767px) {
            .auth-card {
                padding: 3rem 2rem;
            }
            .logo-text {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="text-center">
                <h1 class="logo-text">LaraBids</h1>
                
                <div class="icon-circle">
                    <i class="fas fa-lock"></i>
                </div>
                
                <h4 class="auth-subtitle">Reset Your Password</h4>
                <p class="auth-description">
                    Enter your email and new password below.
                </p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" id="resetPasswordForm">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="text" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $request->email) }}" 
                           placeholder="Enter your email">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="error-message" id="emailError"></div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           placeholder="Create a new password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="error-message" id="passwordError"></div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" 
                           class="form-control" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           placeholder="Confirm your new password">
                    <div class="error-message" id="confirmPasswordError"></div>
                </div>

                <button type="submit" class="btn btn-auth">
                    <i class="fas fa-check-circle me-2"></i> Reset Password
                </button>
            </form>

            <div class="divider"></div>

            <div class="text-center">
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="auth-link">
                        <i class="fas fa-arrow-left me-1"></i> Back to Login
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Client-side Validation -->
    <script>
        document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
            let isValid = true;
            
            // Clear previous errors
            document.querySelectorAll('.error-message').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));
            
            // Email validation
            const email = document.getElementById('email').value.trim();
            const emailError = document.getElementById('emailError');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!email) {
                emailError.textContent = 'Email address is required';
                emailError.style.display = 'block';
                document.getElementById('email').classList.add('is-invalid');
                isValid = false;
            } else if (!emailRegex.test(email)) {
                emailError.textContent = 'Please enter a valid email address';
                emailError.style.display = 'block';
                document.getElementById('email').classList.add('is-invalid');
                isValid = false;
            }
            
            // Password validation
            const password = document.getElementById('password').value;
            const passwordError = document.getElementById('passwordError');
            
            if (!password) {
                passwordError.textContent = 'Password is required';
                passwordError.style.display = 'block';
                document.getElementById('password').classList.add('is-invalid');
                isValid = false;
            } else if (password.length < 8) {
                passwordError.textContent = 'Password must be at least 8 characters';
                passwordError.style.display = 'block';
                document.getElementById('password').classList.add('is-invalid');
                isValid = false;
            }
            
            // Confirm Password validation
            const confirmPassword = document.getElementById('password_confirmation').value;
            const confirmPasswordError = document.getElementById('confirmPasswordError');
            
            if (!confirmPassword) {
                confirmPasswordError.textContent = 'Please confirm your password';
                confirmPasswordError.style.display = 'block';
                document.getElementById('password_confirmation').classList.add('is-invalid');
                isValid = false;
            } else if (password !== confirmPassword) {
                confirmPasswordError.textContent = 'Passwords do not match';
                confirmPasswordError.style.display = 'block';
                document.getElementById('password_confirmation').classList.add('is-invalid');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
