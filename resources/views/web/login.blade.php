<!doctype html>
<html lang="zxx">

<head>
    <!-- Recursos de cabecera -->
    @include('web.index.partials.head-recursos')
</head>

<body>
    <!-- Bloque: Preloader -->
    @include('web.layouts.preloader')

    <!-- Bloque: Encabezado -->
    @include('web.layouts.header')

    <main class="main__content_wrapper">

        <section class="login__section section--padding"
            style="background: #fdfdfd; min-height: 80vh; display: flex; align-items: center;">
            <div class="container">
                <div class="row align-items-center">

                    <div class="col-lg-6 pe-lg-5 mb-5 mb-lg-0">
                        <div class="login__content">
                            <h2 class="fw-bold mb-3" style="color: var(--primary-color); font-size: 2.5rem;">¡Bienvenida
                                de nuevo, Emprendedora!</h2>
                            <p class="text-muted mb-4" style="fs-5">
                                Tu oficina virtual te espera. Accede para gestionar tus pedidos, revisar tus puntos
                                acumulados y descargar el catálogo más reciente.
                            </p>

                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="p-3 border-radius-10 d-flex align-items-center"
                                        style="background: var(--secondary-color-pastel); border: 1px solid #fce4ec;">
                                        <div class="icon-box me-3 text-secondary"><i
                                                class="fas fa-chart-line fa-lg"></i></div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">Revisa tus Ganancias</h6>
                                            <small class="text-muted">Mira cuánto has generado este mes.</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="p-3 border-radius-10 d-flex align-items-center"
                                        style="background: white; border: 1px solid #eee;">
                                        <div class="icon-box me-3 text-secondary"><i class="fas fa-gift fa-lg"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">Canjea tus Puntos</h6>
                                            <small class="text-muted">Tienes premios esperando por ti.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5 offset-lg-1">
                        <div class="card border-0 shadow-lg p-4 p-md-5" style="border-radius: 25px;">
                            <div class="text-center mb-4">
                                <h3 class="fw-bold">Iniciar Sesión</h3>
                                <p class="text-muted">Ingresa tus credenciales para continuar</p>
                            </div>

                            <form action="{{ route('login.post') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Correo Electrónico</label>
                                    <input type="email" name="email" class="form-control fs-4"
                                        style="border-radius: 12px; padding: 12px;" placeholder="correo@ejemplo.com"
                                        required value="{{ old('email') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Contraseña</label>
                                    <input type="password" name="password" class="form-control fs-3"
                                        style="border-radius: 12px; padding: 12px;" placeholder="••••••••" required>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                        <label class="form-check-label small" for="remember">Recordarme</label>
                                    </div>
                                    <a href="#" class="small text-secondary fw-bold">¿Olvidaste tu contraseña?</a>
                                </div>

                                <button type="submit" class="btn w-100 p-3 fw-bold text-white"
                                    style="background: var(--secondary-color); border-radius: 12px; text-transform: uppercase;">
                                    Entrar a mi cuenta
                                </button>

                                <div class="text-center mt-4">
                                    <p class="text-muted small">¿Aún no eres parte de la red?
                                        <a href="{{ route('register') }}" class="text-secondary fw-bold">Regístrate
                                            aquí</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>

    <!-- Bloque: Pie de página -->
    @include('web.layouts.footer')

    <!-- Bloque: Modal de vista rápida -->
    @include('web.index.partials.modal-quickview')

    <!-- Bloque: Botón subir -->
    @include('web.index.partials.boton-scroll-top')

    <!-- Bloque: Scripts principales -->
    @include('web.index.partials.scripts')
</body>

</html>
