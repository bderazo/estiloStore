<div class="header__info-section" 
     style="background-image: url('{{ $bannerHeader ? asset('storage/' . $bannerHeader->imagen_ruta) : asset('web/assets/img/banner/banner-bg2.png') }}'); 
            background-size: cover; 
            background-position: center; 
            height: 10em; 
            position: relative;">
    
    <div class="container-fluid h-100">
        <div class="row h-100 align-items-center">
            
            <div class="col-6 d-flex align-items-center justify-content-start border-end border-white border-opacity-25">
                <div class="header__info--inner ps-4">
                    <p class="text-white mb-2" style="font-size: 1.3rem; font-weight: 500;">¿Cómo pagar?</p>
                    <a class="btn custom-round-btn" href="#">Conocer más</a>
                </div>
            </div>

            <div class="col-6 d-flex align-items-center justify-content-start">
                <div class="header__info--inner ps-4">
                    <p class="text-white mb-2" style="font-size: 1.3rem; font-weight: 500;">Únete a nuestra familia</p>
                    <a class="btn custom-round-btn" href="{{ route('register')}}">Registrar</a>
                </div>
            </div>

        </div>
    </div>
</div>