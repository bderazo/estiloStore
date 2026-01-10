    <!-- Inicio minicart offCanvas -->
    <div class="offCanvas__minicart">
        <div class="minicart__header ">
            <div class="minicart__header--top d-flex justify-content-between align-items-center">
                <h2 class="minicart__title h3">Carrito de compras</h2>
                <button class="minicart__close--btn" aria-label="botón para cerrar el minicart" data-offcanvas>
                    <svg class="minicart__close--icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M368 368L144 144M368 144L144 368" />
                    </svg>
                </button>
            </div>
            <p class="minicart__header--desc">Los productos de ropa y moda son limitados</p>
        </div>
        <div class="minicart__product">
            <div class="minicart__product--items d-flex">
                <div class="minicart__thumb">
                    <a href="product-details.html"><img src="{{asset('web/assets/img/product/product1.png')}}" alt="prduct-img"></a>
                </div>
                <div class="minicart__text">
                    <h3 class="minicart__subtitle h4"><a href="product-details.html">Vestido de algodón holgado</a></h3>
                    <span class="color__variant"><b>Color:</b> Beige</span>
                    <div class="minicart__price">
                        <span class="current__price">$125.00</span>
                        <span class="old__price">$140.00</span>
                    </div>
                    <div class="minicart__text--footer d-flex align-items-center">
                        <div class="quantity__box minicart__quantity">
                            <button type="button" class="quantity__value decrease" aria-label="valor de cantidad" value="Disminuir valor">-</button>
                            <label>
                                <input type="number" class="quantity__number" value="1" data-counter />
                            </label>
                            <button type="button" class="quantity__value increase" value="Aumentar valor">+</button>
                        </div>
                        <button class="minicart__product--remove">Eliminar</button>
                    </div>
                </div>
            </div>
            <div class="minicart__product--items d-flex">
                <div class="minicart__thumb">
                    <a href="product-details.html"><img src="{{asset('web/assets/img/product/product2.png')}}" alt="prduct-img"></a>
                </div>
                <div class="minicart__text">
                    <h3 class="minicart__subtitle h4"><a href="product-details.html">Chaqueta de mezclilla recta</a></h3>
                    <span class="color__variant"><b>Color:</b> Verde</span>
                    <div class="minicart__price">
                        <span class="current__price">$115.00</span>
                        <span class="old__price">$130.00</span>
                    </div>
                    <div class="minicart__text--footer d-flex align-items-center">
                        <div class="quantity__box minicart__quantity">
                            <button type="button" class="quantity__value decrease" aria-label="valor de cantidad" value="Disminuir valor">-</button>
                            <label>
                                <input type="number" class="quantity__number" value="1" data-counter />
                            </label>
                            <button type="button" class="quantity__value increase" aria-label="valor de cantidad" value="Aumentar valor">+</button>
                        </div>
                        <button class="minicart__product--remove">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="minicart__amount">
            <div class="minicart__amount_list d-flex justify-content-between">
                <span>Subtotal:</span>
                <span><b>$240.00</b></span>
            </div>
            <div class="minicart__amount_list d-flex justify-content-between">
                <span>Total:</span>
                <span><b>$240.00</b></span>
            </div>
        </div>
        <div class="minicart__conditions text-center">
            <input class="minicart__conditions--input" id="accept" type="checkbox">
            <label class="minicart__conditions--label" for="accept">Acepto la <a class="minicart__conditions--link" href="privacy-policy.html">Política de privacidad</a></label>
        </div>
        <div class="minicart__button d-flex justify-content-center">
            <a class="primary__btn minicart__button--link" href="cart.html">Ver carrito</a>
            <a class="primary__btn minicart__button--link" href="checkout.html">Proceder al pago</a>
        </div>
    </div>
    <!-- Fin minicart offCanvas -->
