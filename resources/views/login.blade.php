<!doctype html>

<html
  lang="en"
  class="layout-wide customizer-hide"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Connexion</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />

    <link rel="stylesheet" href="../assets/vendor/fonts/iconify-icons.css" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- endbuild -->

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="{{ asset('assets/js/config.js') }}"></script>
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <div class="card px-sm-6 px-0 shadow-lg border-0" style="border-radius: 1rem; overflow: hidden;">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="index.html" class="app-brand-link gap-2">
                  <span class="app-brand-logo demo">
                    <div class="app-brand-text demo text-heading fw-bold"><img src="{{ asset('img/logo/logo.jpeg') }}" width="200" height="200" alt=""></div>
                  </span>
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-1">Bienvenue sur votre espace</h4>
              <p class="mb-6 text-body-secondary">Connectez-vous pour accéder au tableau de bord.</p>

              <form id="formAuthentication" class="mb-0" method="POST" action="{{ route('login.perform') }}">
                @csrf

                @if ($errors->any())
                  <div class="alert alert-danger mb-6" role="alert">
                    {{ $errors->first() }}
                  </div>
                @endif

                <div class="mb-6">
                  <label class="form-label" for="login">Login</label>
                  <input
                    type="text"
                    class="form-control @error('login') is-invalid @enderror"
                    id="login"
                    name="login"
                    value="{{ old('login') }}"
                    placeholder="Votre login"
                    autocomplete="username"
                    autofocus />
                  @error('login')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-6 form-password-toggle">
                  <label class="form-label" for="password">Mot de passe</label>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="password"
                      class="form-control @error('password') is-invalid @enderror"
                      name="password"
                      placeholder="Votre mot de passe"
                      autocomplete="current-password"
                      aria-describedby="password" />
                    <span class="input-group-text cursor-pointer" onclick="togglePassword()">
                      <i id="passwordIcon" class="icon-base bx bx-hide"></i>
                    </span>
                    @error('password')
                      <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="mb-8">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="form-check mb-0">
                      <input class="form-check-input" type="checkbox" id="remember-me" name="remember" {{ old('remember') ? 'checked' : '' }} />
                      <label class="form-check-label" for="remember-me">Restez connecté</label>
                    </div>
                  </div>
                </div>

                <div class="mb-6">
                  <button class="btn btn-primary d-grid w-100" type="submit">Se connecter</button>
                </div>
              </form>
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>


    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->

    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
      function togglePassword() {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('passwordIcon');
        if (!passwordInput || !icon) return;

        const isPassword = passwordInput.getAttribute('type') === 'password';
        passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

        icon.classList.toggle('bx-hide', !isPassword);
        icon.classList.toggle('bx-show', isPassword);
      }
    </script>

    <!-- Page JS -->

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
