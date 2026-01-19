<template>
    <div :class="{ 'dark text-white-dark': store.semidark }">
        <nav class="sidebar fixed min-h-screen h-full top-0 bottom-0 w-[260px] shadow-[5px_0_25px_0_rgba(94,92,154,0.1)] z-50 transition-all duration-300">
            <div class="bg-white dark:bg-[#0e1726] h-full">
                <div class="flex justify-between items-center px-4 py-3">
                    <router-link to="/administrador/" class="main-logo flex items-center shrink-0">
                        <img class="w-8 ml-[5px] flex-none" src="/assets/images/logo.svg" alt="" />
                        <span class="text-2xl ltr:ml-1.5 rtl:mr-1.5 font-semibold align-middle lg:inline dark:text-white-light">ESTILO STORE</span>
                    </router-link>
                    <a
                        href="javascript:;"
                        class="collapse-icon w-8 h-8 rounded-full flex items-center hover:bg-gray-500/10 dark:hover:bg-dark-light/10 dark:text-white-light transition duration-300 rtl:rotate-180 hover:text-primary"
                        @click="store.toggleSidebar()"
                    >
                        <svg class="w-5 h-5 m-auto" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                opacity="0.5"
                                d="M16.9998 19L10.9998 12L16.9998 5"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </a>
                </div>
                <perfect-scrollbar
                    :options="{
                        swipeEasing: true,
                        wheelPropagation: false,
                    }"
                    class="h-[calc(100vh-80px)] relative"
                >
                    <ul class="relative font-semibold space-y-0.5 p-4 py-0">
                        <!-- ==================== PANEL PRINCIPAL ==================== -->
                        <h2 class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">
                            <svg class="w-4 h-5 flex-none mr-2" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <span>Panel Principal</span>
                        </h2>

                        <li class="menu nav-item">
                            <button
                                type="button"
                                class="nav-link group w-full"
                                :class="{ active: activeDropdown === 'dashboard' }"
                                @click="activeDropdown === 'dashboard' ? (activeDropdown = null) : (activeDropdown = 'dashboard')"
                            >
                                <div class="flex items-center">
                                    <svg
                                        class="group-hover:!text-primary shrink-0"
                                        width="20"
                                        height="20"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            opacity="0.5"
                                            d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                                            fill="currentColor"
                                        />
                                        <path
                                            d="M9 17.25C8.58579 17.25 8.25 17.5858 8.25 18C8.25 18.4142 8.58579 18.75 9 18.75H15C15.4142 18.75 15.75 18.4142 15.75 18C15.75 17.5858 15.4142 17.25 15 17.25H9Z"
                                            fill="currentColor"
                                        />
                                    </svg>

                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">
                                        Dashboard
                                    </span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'dashboard' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <vue-collapsible :isOpen="activeDropdown === 'dashboard'">
                                <ul class="sub-menu text-gray-500">
                                    <li>
                                        <router-link to="/administrador" @click="toggleMobileMenu">Ventas</router-link>
                                    </li>
                                    <li>
                                        <router-link to="/administrador/analytics" @click="toggleMobileMenu">Analíticas</router-link>
                                    </li>
                                    <li>
                                        <router-link to="/administrador/finance" @click="toggleMobileMenu">Finanzas</router-link>
                                    </li>
                                    <li>
                                        <router-link to="/administrador/crypto" @click="toggleMobileMenu">Crypto</router-link>
                                    </li>
                                </ul>
                            </vue-collapsible>
                        </li>

                        <!-- ==================== GESTIÓN DE ACCESOS ==================== -->
                        <h2 class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1 mt-6">
                            <svg class="w-4 h-5 flex-none mr-2" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="10" rx="2" ry="2"></rect>
                                <circle cx="12" cy="16" r="1"></circle>
                                <path d="m7 11 0-4a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            <span>Gestión de Accesos</span>
                        </h2>

                        <!-- Usuarios -->
                        <li class="menu nav-item">
                            <button
                                type="button"
                                class="nav-link group w-full"
                                :class="{ active: activeDropdown === 'usuarios' }"
                                @click="activeDropdown === 'usuarios' ? (activeDropdown = null) : (activeDropdown = 'usuarios')"
                            >
                                <div class="flex items-center">
                                    <svg class="group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                                        <path d="M20 17.5C20 19.9853 20 22 12 22C4 22 4 19.9853 4 17.5C4 15.0147 7.58172 13 12 13C16.4183 13 20 15.0147 20 17.5Z" stroke="currentColor" stroke-width="1.5" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">{{ 'Usuarios' }}</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'usuarios' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <vue-collapsible :isOpen="activeDropdown === 'usuarios'">
                                <ul class="sub-menu text-gray-500">
                                    <li>
                                        <router-link to="/administrador/usuarios" @click="toggleMobileMenu">{{ 'Lista de Usuarios' }}</router-link>
                                    </li>
                                    <li>
                                        <router-link to="/administrador/usuarios/crear" @click="toggleMobileMenu">{{ 'Crear Usuario' }}</router-link>
                                    </li>
                                </ul>
                            </vue-collapsible>
                        </li>

                        <!-- Roles -->
                        <li class="menu nav-item">
                            <button
                                type="button"
                                class="nav-link group w-full"
                                :class="{ active: activeDropdown === 'roles' }"
                                @click="activeDropdown === 'roles' ? (activeDropdown = null) : (activeDropdown = 'roles')"
                            >
                                <div class="flex items-center">
                                    <svg class="group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15.3929 4.05365L14.8912 4.61112L15.3929 4.05365ZM19.3517 7.61654L18.85 8.17402L19.3517 7.61654ZM21.654 10.1541L20.9689 10.4592V10.4592L21.654 10.1541ZM3.17157 20.8284L3.7019 20.2981H3.7019L3.17157 20.8284ZM20.8284 20.8284L20.2981 20.2981L20.2981 20.2981L20.8284 20.8284ZM14 21.25H10V22.75H14V21.25ZM2.75 14V10H1.25V14H2.75ZM21.25 13.5629V14H22.75V13.5629H21.25ZM14.8912 4.61112L18.85 8.17402L19.8534 7.05907L15.8947 3.49618L14.8912 4.61112ZM22.75 13.5629C22.75 11.8745 22.7651 10.8055 22.3391 9.84897L20.9689 10.4592C21.2349 11.0565 21.25 11.742 21.25 13.5629H22.75ZM18.85 8.17402C20.2034 9.3921 20.7029 9.86199 20.9689 10.4592L22.3391 9.84897C21.9131 8.89241 21.1084 8.18853 19.8534 7.05907L18.85 8.17402ZM10.0298 2.75C11.6116 2.75 12.2085 2.76158 12.7405 2.96573L13.2779 1.5653C12.4261 1.23842 11.498 1.25 10.0298 1.25V2.75ZM15.8947 3.49618C14.8087 2.51878 14.1297 1.89214 13.2779 1.5653L12.7405 2.96573C13.2727 3.16993 13.7215 3.55836 14.8912 4.61112L15.8947 3.49618ZM10 21.25C8.09318 21.25 6.73851 21.2484 5.71085 21.1102C4.70476 20.975 4.12511 20.7213 3.7019 20.2981L2.64124 21.3588C3.38961 22.1071 4.33855 22.4392 5.51098 22.5969C6.66182 22.7516 8.13558 22.75 10 22.75V21.25ZM1.25 14C1.25 15.8644 1.24841 17.3382 1.40313 18.489C1.56076 19.6614 1.89288 20.6104 2.64124 21.3588L3.7019 20.2981C3.27869 19.8749 3.02502 19.2952 2.88976 18.2892C2.75159 17.2615 2.75 15.9068 2.75 14H1.25ZM14 22.75C15.8644 22.75 17.3382 22.7516 18.489 22.5969C19.6614 22.4392 20.6104 22.1071 21.3588 21.3588L20.2981 20.2981C19.8749 20.7213 19.2952 20.975 18.2892 21.1102C17.2615 21.2484 15.9068 21.25 14 21.25V22.75ZM21.25 14C21.25 15.9068 21.2484 17.2615 21.1102 18.2892C20.975 19.2952 20.7213 19.8749 20.2981 20.2981L21.3588 21.3588C22.1071 20.6104 22.4392 19.6614 22.5969 18.489C22.7516 17.3382 22.75 15.8644 22.75 14H21.25ZM2.75 10C2.75 8.09318 2.75159 6.73851 2.88976 5.71085C3.02502 4.70476 3.27869 4.12511 3.7019 3.7019L2.64124 2.64124C1.89288 3.38961 1.56076 4.33855 1.40313 5.51098C1.24841 6.66182 1.25 8.13558 1.25 10H2.75ZM10.0298 1.25C8.15538 1.25 6.67442 1.24842 5.51887 1.40307C4.34232 1.56054 3.39019 1.8932 2.64124 2.64124L3.7019 3.7019C4.12453 3.27928 4.70596 3.02525 5.71785 2.88982C6.75075 2.75158 8.11311 2.75 10.0298 2.75V1.25Z" fill="currentColor" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">{{ 'Roles' }}</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'roles' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <vue-collapsible :isOpen="activeDropdown === 'roles'">
                                <ul class="sub-menu text-gray-500">
                                    <li>
                                        <router-link to="/administrador/roles" @click="toggleMobileMenu">{{ 'Lista de Roles' }}</router-link>
                                    </li>
                                    <li>
                                        <router-link to="/administrador/roles/crear" @click="toggleMobileMenu">{{ 'Crear Rol' }}</router-link>
                                    </li>
                                </ul>
                            </vue-collapsible>
                        </li>

                        <!-- ==================== INVENTARIO Y PRODUCTOS ==================== -->
                        <h2 class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1 mt-6">
                            <svg class="w-4 h-5 flex-none mr-2" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m7.5 4.27 9 5.15"></path>
                                <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path>
                                <path d="m3.3 7 8.7 5 8.7-5"></path>
                                <path d="M12 22V12"></path>
                            </svg>
                            <span>Inventario y Productos</span>
                        </h2>

                        <!-- Artículos -->
                        <li class="menu nav-item">
                            <router-link to="/administrador/articulos" class="nav-link group" @click="toggleMobileMenu">
                                <div class="flex items-center">
                                    <svg class="group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 4C3 2.89543 3.89543 2 5 2H19C20.1046 2 21 2.89543 21 4V20C21 21.1046 20.1046 22 19 22H5C3.89543 22 3 21.1046 3 20V4Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M7 6H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M7 10H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M7 14H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M7 18H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Artículos</span>
                                </div>
                            </router-link>
                        </li>

                        <!-- Categorías -->
                        <li class="menu nav-item">
                            <button
                                type="button"
                                class="nav-link group w-full"
                                :class="{ active: activeDropdown === 'categorias' }"
                                @click="activeDropdown === 'categorias' ? (activeDropdown = null) : (activeDropdown = 'categorias')"
                            >
                                <div class="flex items-center">
                                    <svg class="group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Categorías</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'categorias' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <vue-collapsible :isOpen="activeDropdown === 'categorias'">
                                <ul class="sub-menu text-gray-500">
                                    <li>
                                        <router-link to="/administrador/categorias" @click="toggleMobileMenu">Lista de Categorías</router-link>
                                    </li>
                                    <li>
                                        <router-link to="/administrador/categorias/crear" @click="toggleMobileMenu">Crear Categoría</router-link>
                                    </li>
                                </ul>
                            </vue-collapsible>
                        </li>

                        <!-- Marcas -->
                        <li class="menu nav-item">
                            <button
                                type="button"
                                class="nav-link group w-full"
                                :class="{ active: activeDropdown === 'marcas' }"
                                @click="activeDropdown === 'marcas' ? (activeDropdown = null) : (activeDropdown = 'marcas')"
                            >
                                <div class="flex items-center">
                                    <svg class="group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.79545 2.94318L12 2L15.2045 2.94318C16.4588 3.22706 17.2271 4.46264 16.9432 5.71698L15.7186 10.9038C15.4347 12.1581 14.1991 12.9264 12.9448 12.6425L12 12.4283L11.0552 12.6425C9.80086 12.9264 8.56528 12.1581 8.28139 10.9038L7.05682 5.71698C6.77294 4.46264 7.54122 3.22706 8.79545 2.94318Z" stroke="currentColor" stroke-width="1.5"/>
                                        <path d="M12 12V22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                        <path d="M9 19H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Marcas</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'marcas' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <vue-collapsible :isOpen="activeDropdown === 'marcas'">
                                <ul class="sub-menu text-gray-500">
                                    <li>
                                        <router-link to="/administrador/marcas" @click="toggleMobileMenu">Lista de Marcas</router-link>
                                    </li>
                                    <li>
                                        <router-link to="/administrador/marcas/crear" @click="toggleMobileMenu">Crear Marca</router-link>
                                    </li>
                                </ul>
                            </vue-collapsible>
                        </li>

                        <!-- ==================== ATRIBUTOS DE PRODUCTOS ==================== -->
                        <h2 class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1 mt-6">
                            <svg class="w-4 h-5 flex-none mr-2" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                            </svg>
                            <span>Atributos de Productos</span>
                        </h2>

                        <!-- Colores -->
                        <li class="menu nav-item">
                            <router-link to="/administrador/colores" class="nav-link group" @click="toggleMobileMenu">
                                <div class="flex items-center">
                                    <svg class="group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2Z" stroke="currentColor" stroke-width="1.5"/>
                                        <path d="M12 6V18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                        <path d="M6 12H18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Colores</span>
                                </div>
                            </router-link>
                        </li>

                        <!-- Tallas -->
                        <li class="menu nav-item">
                            <router-link to="/administrador/tallas" class="nav-link group" @click="toggleMobileMenu">
                                <div class="flex items-center">
                                    <svg class="group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7 8H17L15 12L17 16H7L9 12L7 8Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                                        <path d="M6 4H18V20H6V4Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                                        <path d="M9 1V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                        <path d="M15 1V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                        <path d="M9 17V23" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                        <path d="M15 17V23" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Tallas</span>
                                </div>
                            </router-link>
                        </li>

                        <!-- Plazas -->
                        <li class="menu nav-item">
                            <router-link to="/administrador/plazas" class="nav-link group" @click="toggleMobileMenu">
                                <div class="flex items-center">
                                    <svg class="group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 9H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M5 3H19C20.1046 3 21 3.89543 21 5V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V5C3 3.89543 3.89543 3 5 3Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                                        <path d="M3 9H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Plazas</span>
                                </div>
                            </router-link>
                        </li>

                        <!-- Medidas -->
                        <li class="menu nav-item">
                            <router-link to="/administrador/medidas" class="nav-link group" @click="toggleMobileMenu">
                                <div class="flex items-center">
                                    <svg class="group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 3H21V5H3V3Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                                        <path d="M3 11H21V13H3V11Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                                        <path d="M3 19H21V21H3V19Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                                        <path d="M8 8H10V10H8V8Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                                        <path d="M8 16H10V18H8V16Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Medidas</span>
                                </div>
                            </router-link>
                        </li>

                        <!-- Sabores -->
                        <li class="menu nav-item">
                            <router-link to="/administrador/sabores" class="nav-link group" @click="toggleMobileMenu">
                                <div class="flex items-center">
                                    <svg class="group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z" fill="currentColor"/>
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Sabores</span>
                                </div>
                            </router-link>
                        </li>

                        <!-- Modelos -->
                        <li class="menu nav-item">
                            <router-link to="/administrador/modelos" class="nav-link group" @click="toggleMobileMenu">
                                <div class="flex items-center">
                                    <svg class="group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z" fill="currentColor"/>
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Modelos</span>
                                </div>
                            </router-link>
                        </li>

                        <!-- Tonos -->
                        <li class="menu nav-item">
                            <router-link to="/administrador/tonos" class="nav-link group" @click="toggleMobileMenu">
                                <div class="flex items-center">
                                    <svg class="group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z" fill="currentColor"/>
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Tonos</span>
                                </div>
                            </router-link>
                        </li>

                        <!-- ==================== PÁGINAS Y CONTENIDO ==================== -->
                        <h2 class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1 mt-6">
                            <svg class="w-4 h-5 flex-none mr-2" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            <span>Páginas y Contenido</span>
                        </h2>

                        <!-- Carrusel -->
                        <li class="menu nav-item">
                            <button
                                type="button"
                                class="nav-link group w-full"
                                :class="{ active: activeDropdown === 'carrusel' }"
                                @click="activeDropdown === 'carrusel' ? (activeDropdown = null) : (activeDropdown = 'carrusel')"
                            >
                                <div class="flex items-center">
                                    <svg class="group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2 6a2 2 0 012-2h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" stroke="currentColor" stroke-width="1.5"/>
                                        <path d="M8 12l3 3 5-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Carrusel</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'carrusel' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <vue-collapsible :isOpen="activeDropdown === 'carrusel'">
                                <ul class="sub-menu text-gray-500">
                                    <li>
                                        <router-link to="/administrador/carrusel" @click="toggleMobileMenu">Lista de Carrusel</router-link>
                                    </li>
                                    <li>
                                        <router-link to="/administrador/carrusel/crear" @click="toggleMobileMenu">Crear Carrusel</router-link>
                                    </li>
                                </ul>
                            </vue-collapsible>
                        </li>

                        <!-- ==================== BANNERS ==================== -->
                        <h2 class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1 mt-6">
                            <svg class="w-4 h-5 flex-none mr-2" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="3" y1="9" x2="21" y2="9"></line>
                                <line x1="9" y1="21" x2="9" y2="9"></line>
                            </svg>
                            <span>Banners</span>
                        </h2>

                        <!-- Banners -->
                        <li class="menu nav-item">
                            <button
                                type="button"
                                class="nav-link group w-full"
                                :class="{ active: activeDropdown === 'banners' }"
                                @click="activeDropdown === 'banners' ? (activeDropdown = null) : (activeDropdown = 'banners')"
                            >
                                <div class="flex items-center">
                                    <svg class="group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 5C3 3.89543 3.89543 3 5 3H19C20.1046 3 21 3.89543 21 5V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V5Z" stroke="currentColor" stroke-width="1.5"/>
                                        <path d="M3 9H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                        <path d="M9 21V9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Banners</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'banners' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <vue-collapsible :isOpen="activeDropdown === 'banners'">
                                <ul class="sub-menu text-gray-500">
                                    <li>
                                        <router-link to="/administrador/banners" @click="toggleMobileMenu">Lista de Banners</router-link>
                                    </li>
                                    <li>
                                        <router-link to="/administrador/banners/crear" @click="toggleMobileMenu">Crear Banner</router-link>
                                    </li>
                                </ul>
                            </vue-collapsible>
                        </li>

                        <!-- Empresa (Sobre Nosotros) -->
                        <li class="menu nav-item">
                            <button
                                type="button"
                                class="nav-link group w-full"
                                :class="{ active: activeDropdown === 'empresa' }"
                                @click="activeDropdown === 'empresa' ? (activeDropdown = null) : (activeDropdown = 'empresa')"
                            >
                                <div class="flex items-center">
                                    <svg class="group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19 21V5C19 3.89543 18.1046 3 17 3H7C5.89543 3 5 3.89543 5 5V21" stroke="currentColor" stroke-width="1.5"/>
                                        <path d="M3 21H21" stroke="currentColor" stroke-width="1.5"/>
                                        <path d="M9 7H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                        <path d="M9 11H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                        <path d="M9 15H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Empresa</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'empresa' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <vue-collapsible :isOpen="activeDropdown === 'empresa'">
                                <ul class="sub-menu text-gray-500">
                                    <li>
                                        <router-link to="/administrador/empresa" @click="toggleMobileMenu">Datos empresa</router-link>
                                    </li>
                                    <li>
                                        <router-link to="/administrador/empresa/crear" @click="toggleMobileMenu">Crear datos</router-link>
                                    </li>
                                    <!-- <li>
                                        <router-link to="/administrador/empresa/:id/editar" @click="toggleMobileMenu">Contacto</router-link>
                                    </li> -->
                                </ul>
                            </vue-collapsible>
                        </li>

                        <li class="menu nav-item">
                            <button 
                            type="button" 
                            class="nav-link group w-full" 
                            :class="{ active: activeDropdown === 'folletos' }" 
                            @click="toggleDropdown('folletos')"
                            >
                            <div class="flex items-center">
                                <svg class="group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="1.5"/>
                                <polyline points="14 2 14 8 20 8" stroke="currentColor" stroke-width="1.5"/>
                                <line x1="16" y1="13" x2="8" y2="13" stroke="currentColor" stroke-width="1.5"/>
                                <line x1="16" y1="17" x2="8" y2="17" stroke="currentColor" stroke-width="1.5"/>
                                <polyline points="10 9 9 9 8 9" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M18 13v-2a4 4 0 0 0-4-4H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                                <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">
                                Folletos y Catálogos
                                </span>
                            </div>
                            <div :class="['rtl:rotate-180', { '!rotate-90': activeDropdown === 'folletos' }]">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            </button>
                            <ul v-show="activeDropdown === 'folletos'" class="sub-menu text-gray-500">
                            <li>
                                <router-link 
                                to="/administrador/folletos" 
                                @click="toggleMobileMenu"
                                class="flex items-center"
                                >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Lista de Folletos
                                </router-link>
                            </li>
                            <li>
                                <router-link 
                                to="/administrador/folletos/crear" 
                                @click="toggleMobileMenu"
                                class="flex items-center"
                                >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Nuevo Folleto
                                </router-link>
                            </li>
                            <li>
                                <router-link 
                                to="/administrador/folletos/estadisticas" 
                                @click="toggleMobileMenu"
                                class="flex items-center"
                                >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Estadísticas
                                </router-link>
                            </li>
                            </ul>
                        </li>

                        <!-- Métodos de Pago -->
                        <li class="menu nav-item">
                            <button
                                type="button"
                                class="nav-link group w-full"
                                :class="{ active: activeDropdown === 'metodos-pago' }"
                                @click="activeDropdown === 'metodos-pago' ? (activeDropdown = null) : (activeDropdown = 'metodos-pago')"
                            >
                                <div class="flex items-center">
                                    <svg class="group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 10H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                        <rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.5"/>
                                        <circle cx="7.5" cy="14.5" r="1.5" fill="currentColor"/>
                                        <circle cx="12" cy="14.5" r="1.5" fill="currentColor"/>
                                        <circle cx="16.5" cy="14.5" r="1.5" fill="currentColor"/>
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Métodos de Pago</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'metodos-pago' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <vue-collapsible :isOpen="activeDropdown === 'metodos-pago'">
                                <ul class="sub-menu text-gray-500">
                                    <li>
                                        <router-link to="/administrador/metodos-pago" @click="toggleMobileMenu">Lista de Métodos</router-link>
                                    </li>
                                    <li>
                                        <router-link to="/administrador/metodos-pago/crear" @click="toggleMobileMenu">Agregar Método</router-link>
                                    </li>
                                </ul>
                            </vue-collapsible>
                        </li>

                    </ul>
                </perfect-scrollbar>
            </div>
        </nav>
    </div>
</template>

<script lang="ts" setup>
    import { ref, onMounted, computed } from 'vue';

    import { useAppStore } from '../../stores/index';
    import { useAuthStore } from '../../stores/auth';
    import VueCollapsible from 'vue-height-collapsible/vue3';
    const store = useAppStore();
    const authStore = useAuthStore();
    const activeDropdown: any = ref('');
    const subActive: any = ref('');
    const canViewNosotros = computed(() => authStore.hasPermission('view nosotros'));

    onMounted(() => {
        const selector = document.querySelector('.sidebar ul a[href="' + window.location.pathname + '"]');
        if (selector) {
            selector.classList.add('active');
            const ul: any = selector.closest('ul.sub-menu');
            if (ul) {
                let ele: any = ul.closest('li.menu').querySelectorAll('.nav-link') || [];
                if (ele.length) {
                    ele = ele[0];
                    setTimeout(() => {
                        ele.click();
                    });
                }
            }
        }
    });

    const toggleMobileMenu = () => {
        if (window.innerWidth < 1024) {
            store.toggleSidebar();
        }
    };

    const toggleDropdown = (name: string) => {
        activeDropdown.value = activeDropdown.value === name ? null : name;
    };
</script>
