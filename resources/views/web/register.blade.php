@extends('web.layouts.base')
@section('title', 'Título de la página')
@push('styles')
<style>
        :root {
            /* Asegurando que las variables existan si no vienen del CSS global */
            --primary-color: #2b3d51;
            --secondary-color: #ff007f;
            --secondary-color-pastel: #fff0f6;
        }

        .registration__card {
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid #fce4ec !important;
        }

        .form-control-custom {
            border-radius: 12px;
            padding: 12px 15px;
            border: 1.5px solid #eee;
            transition: all 0.3s ease;
        }

        .form-control-custom:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 4px rgba(255, 0, 127, 0.1);
            outline: none;
        }

        .benefit-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            border: 1px solid #f0f0f0;
            height: 100%;
            transition: transform 0.3s ease;
        }

        .benefit-card:hover {
            transform: translateY(-5px);
        }

        .btn-unirme {
            background: var(--secondary-color);
            color: white;
            padding: 15px;
            border-radius: 12px;
            border: none;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
        }

        .btn-unirme:hover {
            filter: brightness(1.1);
            box-shadow: 0 8px 20px rgba(255, 0, 127, 0.3);
        }

        .legal-link {
            color: var(--secondary-color);
            font-weight: 700;
            text-decoration: none;
        }

        .legal-link:hover {
            text-decoration: underline;
        }
    </style>
@endpush
    
@section('content')


    <main class="main__content_wrapper">

        <section class="breadcrumb__section breadcrumb__bg" style="background: var(--primary-color); padding: 40px 0;">
            <div class="container text-center">
                <h2 class="text-white fw-bold">Registro de Nuevas Emprendedoras</h2>
            </div>
        </section>

        <section class="registration__area section--padding">
            <div class="container">
                <div class="row align-items-center">

                    <div class="col-lg-6 pe-lg-5 mb-5 mb-lg-0">
                        <div class="info__content">
                            <span class="text-secondary fw-bold text-uppercase" style="letter-spacing: 2px;">Únete a Ser Dama</span>
                            <h2 class="mb-3 fw-bold" style="color: var(--primary-color); font-size: 2.8rem;">Comienza tu propio negocio hoy mismo</h2>
                            <p class="text-muted mb-4" style="font-size: 1.3rem;">
                                Conviértete en una líder de ventas. Te brindamos todas las herramientas digitales y el apoyo necesario para que alcances tus metas financieras.
                            </p>

                            <div class="row g-3 mb-4">
                                <div class="col-sm-6">
                                    <div class="benefit-card d-flex align-items-center">
                                        <div class="icon me-3 text-secondary"><i class="fas fa-book-open fa-2x"></i>
                                        </div>
                                        <span class="fw-bold">Catálogo Digital Actualizado</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="benefit-card d-flex align-items-center">
                                        <div class="icon me-3 text-secondary"><i class="fas fa-mobile-alt fa-2x"></i>
                                        </div>
                                        <span class="fw-bold">Sistema de Pedidos Online</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="benefit-card d-flex align-items-center">
                                        <div class="icon me-3 text-secondary"><i class="fas fa-lightbulb fa-2x"></i>
                                        </div>
                                        <span class="fw-bold">Guías de Apoyo</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="benefit-card d-flex align-items-center">
                                        <div class="icon me-3 text-secondary"><i class="fas fa-trophy fa-2x"></i></div>
                                        <span class="fw-bold">Reto Semanal</span>
                                    </div>
                                </div>
                            </div>

                            <div class="notificaciones p-3 rounded"
                                style="background: #f8f9fa; border-left: 5px solid var(--secondary-color);">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2"><i class="fas fa-info-circle text-secondary me-2"></i> Todos los
                                        campos son obligatorios.</li>
                                    <li><i class="fas fa-user-check text-secondary me-2"></i> Debe ser mayor de edad
                                        para comenzar a vender.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card registration__card p-4 p-md-5 bg-white">
                            <form action="{{ route('register.store') }}" method="POST">
                                @csrf
                                @if ($errors->any())
                                    <div class="alert alert-danger p-2 mb-4"
                                        style="font-size: 1.35rem; border-radius: 10px;">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="fw-bold mb-1">Nombres</label>
                                        <input type="text" name="nombres" class="form-control-custom w-100"
                                            placeholder="Sus nombres" value="{{ old('nombres') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="fw-bold mb-1">Apellidos</label>
                                        <input type="text" name="apellidos" class="form-control-custom w-100"
                                            placeholder="Sus apellidos" value="{{ old('apellidos') }}" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="fw-bold mb-1">Tipo Documento</label>
                                        <select name="tipo_documento" class="form-control-custom w-100" required>
                                            <option value="CEDULA"
                                                {{ old('tipo_documento') == 'CEDULA' ? 'selected' : '' }}>Cédula
                                            </option>
                                            <option value="PASAPORTE"
                                                {{ old('tipo_documento') == 'PASAPORTE' ? 'selected' : '' }}>Pasaporte
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="fw-bold mb-1">Nro. Documento</label>
                                        <input type="text" name="numero_documento" class="form-control-custom w-100"
                                            placeholder="Ej: 1712345678" value="{{ old('numero_documento') }}" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="fw-bold mb-1">WhatsApp</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"
                                                style="border-radius: 12px 0 0 12px; border: 1.5px solid #eee; border-right: none;">+593</span>
                                            <input type="number" name="whatsapp"
                                                class="form-control-custom flex-grow-1"
                                                style="border-radius: 0 12px 12px 0;" placeholder="999999999"
                                                value="{{ old('whatsapp') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="fw-bold mb-1">Fecha Nacimiento</label>
                                        <input type="date" name="fecha_nacimiento" class="form-control-custom w-100"
                                            value="{{ old('fecha_nacimiento') }}" required>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="fw-bold mb-1">Email</label>
                                        <input type="email" name="email" class="form-control-custom w-100"
                                            placeholder="correo@ejemplo.com" value="{{ old('email') }}" required>
                                    </div>

                                    <div class="col-md-7 mb-3">
                                        <label class="fw-bold mb-1">Ciudad / Provincia</label>
                                        <input type="text" name="ciudad_provincia"
                                            class="form-control-custom w-100" placeholder="Ej: Quito, Pichincha"
                                            value="{{ old('ciudad_provincia') }}" required>
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <label class="fw-bold mb-1">Código Referido</label>
                                        <input type="text" name="referido_por_codigo"
                                            class="form-control-custom w-100" placeholder="Opcional"
                                            value="{{ old('referido_por_codigo') }}">
                                    </div>
                                    
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold mb-1">Contraseña</label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="password"
                                            class="form-control-custom w-100" placeholder="••••••••" required>
                                    </div>
                                    <small class="text-muted" style="font-size: 1.2rem;">Mínimo 8 caracteres</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold mb-1">Confirmar Contraseña</label>
                                    <div class="input-group">
                                        <input type="password" name="password_confirmation"
                                            id="password_confirmation" class="form-control-custom w-100"
                                            placeholder="••••••••" required>
                                    </div>
                                </div>

                                    <div class="col-12 mt-3">
                                        <button type="submit" class="btn-unirme w-100">
                                            Unirme ahora
                                        </button>
                                        <p class="text-center mt-3 fw-bold text-secondary" style="font-size: 0.9rem;">
                                            Sin inversión inicial alta, empieza a ganar desde la primera venta.
                                        </p>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="terms1"
                                                name="acepto_terminos" required>
                                            <label class="form-check-label text-muted" for="terms1"
                                                style="font-size: 1.2rem;">
                                                Si, acepto <a
                                                    href="{{ $legalService->getDocumentUrl('terminos_ser_dama') }}"
                                                    target="_blank" class="legal-link">Ser Dama</a> (Descargar otros
                                                términos y condiciones)
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="terms2"
                                                name="acepto_privacidad" required>
                                            <label class="form-check-label text-muted" for="terms2"
                                                style="font-size: 1.2rem;">
                                                Si, acepto <a
                                                    href="{{ $legalService->getDocumentUrl('aviso_privacidad') }}"
                                                    target="_blank" class="legal-link">Aviso de Privacidad</a>
                                                (Descargar términos y condiciones)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>



                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>
@endsection