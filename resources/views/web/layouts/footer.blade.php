<style>
    .footer__section {
        padding: 60px 0 30px;
    }

    .footer__divider {
        position: relative;
    }

    /* Línea vertical para pantallas grandes */
    @media (min-width: 992px) {
        .footer__divider:not(:last-child)::after {
            content: "";
            position: absolute;
            right: 0;
            top: 10%;
            height: 80%;
            width: 1px;
            background: linear-gradient(to bottom, transparent, rgba(255, 255, 255, 0.2), transparent);
        }
    }

    .footer__link:hover {
        color: var(--primary-color) !important;
        /* O el color de tu marca */
        padding-left: 5px;
        transition: all 0.3s ease;
    }

    .social__shear--list__icon:hover {
        transform: translateY(-3px);
        transition: transform 0.3s ease;
    }
</style>

<footer class="footer__section bg__black">
    <div class="container-fluid px-5">
        <div class="row">

            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0 footer__divider text-center">
                <div class="footer__social">
                    <ul class="social__shear d-flex justify-content-center gap-3">
                        <li class="social__shear--list">
                            <a class="social__shear--list__icon text-ofwhite" target="_blank"
                                href="https://www.facebook.com/Estilostore03">
                                <i class="fab fa-facebook-f fa-lg"></i>
                                <span class="visually-hidden">Facebook</span>
                            </a>
                        </li>
                        <li class="social__shear--list">
                            <a class="social__shear--list__icon text-ofwhite" target="_blank"
                                href="https://www.instagram.com/estilo_store_ec?igsh=MWJtcHNkN3J4d21vZA==">
                                <i class="fab fa-instagram fa-lg"></i>
                                <span class="visually-hidden">Instagram</span>
                            </a>
                        </li>
                        <li class="social__shear--list">
                            <a class="social__shear--list__icon text-ofwhite" target="_blank"
                                href="https://www.tiktok.com/@estilo_store_ec">
                                <i class="fab fa-tiktok fa-lg"></i>
                                <span class="visually-hidden">TikTok</span>
                            </a>
                        </li>
                        <li class="social__shear--list">
                            <a class="social__shear--list__icon text-ofwhite" target="_blank"
                                href="https://t.me/estiloec">
                                <i class="fab fa-telegram fa-lg"></i>
                                <span class="visually-hidden">Telegram</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0 footer__divider px-lg-5">
                <ul class="footer__widget--menu text-center text-lg-start p-0 list-unstyled">
                    <li class="mb-2"><a class="footer__link text-ofwhite text-decoration-none" href="#">¿Quiénes
                            somos?</a></li>
                    <li class="mb-2"><a class="footer__link text-ofwhite text-decoration-none" href="#">Términos y
                            Condiciones</a></li>
                    <li class="mb-2"><a class="footer__link text-ofwhite text-decoration-none" href="#">Aviso de
                            Privacidad</a></li>
                </ul>
            </div>

            <div
                class="col-lg-3 col-md-6 mb-4 mb-lg-0 footer__divider px-lg-5 d-flex align-items-center justify-content-center text-center">
                <p class="footer__widget--desc text-ofwhite fst-italic">
                    <strong>Estilo Store</strong></br>
                    "Somos una empresa ecuatoriana enfocada en la mujer, brindando posibilidades de crecimiento y
                    productos innovadores."
                </p>
            </div>

            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0 text-center text-lg-start px-lg-5">
                <div class="footer__contact--info text-ofwhite">
                    <p class="mb-2 small"><i class="fas fa-map-marker-alt me-2"></i> Quevedo, Ecuador</p>
                    <p class="mb-2 small"><i class="fas fa-phone-alt me-2"></i> +593 99 027 3691</p>
                    <p class="mb-0 small"><i class="fas fa-envelope me-2"></i> info@estilostore.com</p>
                </div>
            </div>

        </div>

        <hr class="mt-5 mb-4" style="border-top: 1px solid rgba(255,255,255,0.1);">
        <div class="footer__bottom text-center">
            <p class="copyright__content text-ofwhite small m-0">
                Copyright © <script>
                    document.write(new Date().getFullYear())
                </script>
                <a class="copyright__content--link fw-bold text-decoration-none" href="/">Estilo Store</a>.
                Todos los Derechos Reservados.
            </p>
        </div>
    </div>
</footer>