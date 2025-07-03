@extends('adminlte::master')

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <script>
    // Tailwind CDN uyarısını bastır
    const originalWarn = console.warn;
    console.warn = function(...args) {
        const message = args.join(' ');
        if (message.includes('cdn.tailwindcss.com should not be used in production')) {
            return; // Bu uyarıyı gösterme
        }
        originalWarn.apply(console, args);
    };
</script>
<script src="https://cdn.tailwindcss.com"></script>
    <style>
        .login-page {
            min-height: 100vh;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        .login-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 600px;
        }
        
        .form-section {
            padding: 4rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .image-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }
        
        .image-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.2);
            z-index: 1;
        }
        
        .background-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
        }
        
        .image-content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            padding: 2rem;
        }
        
        .login-header {
            margin-bottom: 3rem;
        }
        
        .login-title {
            font-size: 2.5rem;
            font-weight: 300;
            color: #1a202c;
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }
        
        .login-subtitle {
            color: #64748b;
            font-size: 1rem;
            font-weight: 400;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        
        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s ease;
            background: #ffffff;
            color: #1a202c;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .form-input.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }
        
        .form-input::placeholder {
            color: #94a3b8;
        }
        
        .remember-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }
        
        .remember-checkbox {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .remember-checkbox input[type="checkbox"] {
            width: 1rem;
            height: 1rem;
            accent-color: #3b82f6;
            border-radius: 3px;
        }
        
        .remember-checkbox label {
            color: #64748b;
            font-size: 0.875rem;
            cursor: pointer;
        }
        
        .forgot-link {
            color: #3b82f6;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: color 0.2s ease;
        }
        
        .forgot-link:hover {
            color: #2563eb;
        }
        
        .login-button {
            width: 100%;
            padding: 0.875rem 1rem;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 1rem;
        }
        
        .login-button:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }
        
        .login-button:active {
            transform: translateY(0);
        }
        
        .login-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .invalid-feedback {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }
        
        .welcome-text {
            margin-bottom: 2rem;
        }
        
        .welcome-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .welcome-subtitle {
            font-size: 1.125rem;
            opacity: 0.9;
            line-height: 1.6;
        }
        
        .brand-logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(10px);
        }
        
        .brand-logo::before {
            content: '🏛️';
            font-size: 2rem;
        }
        
        @media (max-width: 768px) {
            .login-card {
                grid-template-columns: 1fr;
                min-height: auto;
            }
            
            .image-section {
                order: -1;
                min-height: 200px;
            }
            
            .form-section {
                padding: 2rem 1.5rem;
            }
            
            .login-title {
                font-size: 2rem;
            }
            
            .welcome-title {
                font-size: 1.5rem;
            }
        }
        
        @media (max-width: 640px) {
            .login-container {
                padding: 1rem;
            }
            
            .login-card {
                border-radius: 16px;
            }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@stop

@section('classes_body', 'login-page')

@section('body')
    <div class="login-container">
        <div class="login-card animate-fade-in">
            <!-- Form Section -->
            <div class="form-section">
                <div class="login-header">
                    <h1 class="login-title">Yönetim Paneli</h1>
                    <p class="login-subtitle">Hesabınıza giriş yaparak devam edin</p>
                </div>

                <form action="{{ route('login') }}" method="post">
                    @csrf

                    <!-- Email Input -->
                    <div class="form-group">
                        <label for="email" class="form-label">E-posta Adresi</label>
                        <input type="email" 
                               id="email"
                               name="email" 
                               class="form-input @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" 
                               placeholder="ornek@email.com" 
                               autofocus>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div class="form-group">
                        <label for="password" class="form-label">Şifre</label>
                        <input type="password" 
                               id="password"
                               name="password" 
                               class="form-input @error('password') is-invalid @enderror"
                               placeholder="••••••••">
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="remember-section">
                        <div class="remember-checkbox">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">Beni hatırla</label>
                        </div>
                        
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">
                                Şifremi unuttum
                            </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="login-button">
                        Giriş Yap
                    </button>
                </form>
            </div>

            <!-- Image Section -->
            <div class="image-section">
                <img src="https://www.cankaya.bel.tr/uploads/images/cankaya-slider_1749928131_vr83Vbun.jpg" 
                     alt="Çankaya Belediyesi" 
                     class="background-image">
                
                <div class="image-content">
                </div>
            </div>
        </div>
    </div>
@stop

@section('adminlte_js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form submit handling
            const form = document.querySelector('form');
            const submitButton = document.querySelector('.login-button');
            const originalText = submitButton.textContent;
            
            form.addEventListener('submit', function() {
                submitButton.textContent = 'Giriş yapılıyor...';
                submitButton.disabled = true;
                
                // Re-enable after 3 seconds if form doesn't submit
                setTimeout(() => {
                    if (submitButton.disabled) {
                        submitButton.textContent = originalText;
                        submitButton.disabled = false;
                    }
                }, 3000);
            });
            
            // Input focus effects
            const inputs = document.querySelectorAll('.form-input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
            });
        });
    </script>
@stop