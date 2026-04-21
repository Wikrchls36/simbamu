<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMBAMU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #1aa4f6 0%, #1579be 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif; 
            padding: 15px; 
        }

        .header-box {
            padding: 0;
            margin-bottom: 2.5rem;
            text-align: center;
        }

        .login-title {
            color: white;
            font-weight: 700;
            font-size: 4rem; 
            margin-bottom: 0.2rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .login-subtitle {
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0;
            letter-spacing: 0.5px;
            opacity: 0.9; 
        }

        .card-login {
            width: 100%;
            max-width: 420px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            border: none;
            padding: 2rem; 
        }

        .logo-mdmc {
            height: 70px; 
            width: 100%; 
            object-fit: contain; 
            margin-bottom: 2rem;
        }

        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ced4da;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.08); 
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.08); 
            outline: none;
        }

        .input-group {
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.08); 
            border-radius: 8px;
            border: 1px solid #ced4da;
        }

        .input-group .form-control {
            box-shadow: none; 
            border: none; 
        }

        .input-group:focus-within {
            border-color: #0d6efd; 
        }

        .input-group-text {
            background-color: transparent;
            border: none;
            cursor: pointer;
            border-radius: 0 8px 8px 0;
            color: #555; 
        }

        .form-control-password {
            border-radius: 8px 0 0 8px;
        }

        .btn-login {
            background-color: #0d6efd;
            border: none;
            font-weight: bold;
            padding: 12px;
            border-radius: 8px;
            font-size: 1.1rem;
            width: 160px; 
            margin: 0 auto;
            display: block; 
            transition: transform 0.2s;
        }

        .btn-login:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .login-title { font-size: 2.8rem; }
            .login-subtitle { font-size: 0.9rem; }
            .header-box { margin-bottom: 2rem; }
            .btn-login { width: 100%; }
        }

        
    </style>
</head>
<body>

    <div class="header-box">
        <h1 class="login-title">SIMBAMU</h1>
        <p class="login-subtitle">SISTEM INFORMASI MANAJEMEN TANGGAP BENCANA MUHAMMADIYAH</p>
    </div>

    <div class="card card-login bg-white">
        <div class="card-body text-center p-0">
            <img src="{{ asset('images/logo-mdmc.png') }}" alt="Logo MDMC" class="logo-mdmc">

            <form action="{{ url('/login') }}" method="POST" class="text-start">
                
                @csrf
                
                @error('email')
                    <div class="alert alert-danger py-2" style="font-size: 0.85rem;">
                        {{ $message }}
                    </div>
                @enderror
                
                <div class="mb-3">

                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>

                <div class="mb-4 input-group">
                    <input type="password" name="password" id="password" class="form-control form-control-password" placeholder="Password" required>
                    <span class="input-group-text" id="togglePassword">
                        <i class="fa-solid fa-eye" id="eyeIcon"></i> 
                    </span>
                </div>

                <button type="submit" class="btn btn-primary btn-login">Login</button>
            </form>
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>