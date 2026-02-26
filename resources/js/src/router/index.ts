import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router";
import { useAppStore } from "../stores/index";
import { useAuthStore } from "../stores/auth";
import appSetting from "../app-setting";

import HomeView from "../views/index.vue";

const routes: RouteRecordRaw[] = [
    // authentication (con prefijo administrador)
    {
        path: "/administrador/login",
        name: "boxed-signin",
        component: () =>
            import(
                /* webpackChunkName: "auth-boxed-signin" */ "../views/auth/login.vue"
            ),
        meta: { layout: "auth" },
    },
    {
        path: "/administrador/auth/boxed-signup",
        name: "boxed-signup",
        component: () =>
            import(
                /* webpackChunkName: "auth-boxed-signup" */ "../views/auth/boxed-signup.vue"
            ),
        meta: { layout: "auth" },
    },
    {
        path: "/administrador/auth/boxed-lockscreen",
        name: "boxed-lockscreen",
        component: () =>
            import(
                /* webpackChunkName: "auth-boxed-lockscreen" */ "../views/auth/boxed-lockscreen.vue"
            ),
        meta: { layout: "auth" },
    },
    {
        path: "/administrador/auth/boxed-password-reset",
        name: "boxed-password-reset",
        component: () =>
            import(
                /* webpackChunkName: "auth-boxed-password-reset" */ "../views/auth/boxed-password-reset.vue"
            ),
        meta: { layout: "auth" },
    },
    {
        path: "/administrador/auth/cover-login",
        name: "cover-login",
        component: () =>
            import(
                /* webpackChunkName: "auth-cover-login" */ "../views/auth/cover-login.vue"
            ),
        meta: { layout: "auth" },
    },
    {
        path: "/administrador/auth/cover-register",
        name: "cover-register",
        component: () =>
            import(
                /* webpackChunkName: "auth-cover-register" */ "../views/auth/cover-register.vue"
            ),
        meta: { layout: "auth" },
    },
    {
        path: "/administrador/auth/cover-lockscreen",
        name: "cover-lockscreen",
        component: () =>
            import(
                /* webpackChunkName: "auth-cover-lockscreen" */ "../views/auth/cover-lockscreen.vue"
            ),
        meta: { layout: "auth" },
    },
    {
        path: "/administrador/auth/cover-password-reset",
        name: "cover-password-reset",
        component: () =>
            import(
                /* webpackChunkName: "auth-cover-password-reset" */ "../views/auth/cover-password-reset.vue"
            ),
        meta: { layout: "auth" },
    },

    // Rutas del administrador (con prefijo /administrador)
    // dashboard - redirige a home
    {
        path: "/administrador",
        redirect: "/administrador/home",
    },
    {
        path: "/administrador/home",
        name: "home",
        component: HomeView,
        meta: {
            permission: "inicio.view",
            title: "Inicio",
        },
    },

    // Sistema de usuarios
    {
        path: "/administrador/usuarios",
        name: "usuarios",
        component: () => import("../views/sistema/usuarios/index.vue"),
        meta: {
            permission: "usuarios.index",
            title: "Usuarios",
        },
    },
    {
        path: "/administrador/usuarios/crear",
        name: "usuarios-crear",
        component: () => import("../views/sistema/usuarios/crear.vue"),
        meta: {
            permission: "usuarios.create",
            title: "Crear Usuario",
        },
    },
    {
        path: "/administrador/usuarios/:id/editar",
        name: "usuarios-editar",
        component: () => import("../views/sistema/usuarios/editar.vue"),
        meta: {
            permission: "usuarios.edit",
            title: "Editar Usuario",
        },
    },

    // Sistema de roles
    {
        path: "/administrador/roles",
        name: "roles",
        component: () => import("../views/sistema/roles/index.vue"),
        meta: {
            permission: "roles.index",
            title: "Roles",
        },
    },
    {
        path: "/administrador/roles/crear",
        name: "roles-crear",
        component: () => import("../views/sistema/roles/crear.vue"),
        meta: {
            permission: "roles.create",
            title: "Crear Rol",
        },
    },
    {
        path: "/administrador/roles/:id/editar",
        name: "roles-editar",
        component: () => import("../views/sistema/roles/editar.vue"),
        meta: {
            permission: "roles.edit",
            title: "Editar Rol",
        },
    },

    // Sistema de Carrusel
    {
        path: "/administrador/carrusel",
        name: "carrusel",
        component: () => import("../views/carrusel/index.vue"),
        meta: {
            permission: "carrusel.index",
            title: "Carrusel",
        },
    },
    {
        path: "/administrador/carrusel/crear",
        name: "carrusel-crear",
        component: () => import("../views/carrusel/crear.vue"),
        meta: {
            permission: "carrusel.create",
            title: "Crear Carrusel",
        },
    },
    {
        path: "/administrador/carrusel/:id/editar",
        name: "carrusel-editar",
        component: () => import("../views/carrusel/editar.vue"),
        meta: {
            permission: "carrusel.edit",
            title: "Editar Carrusel",
        },
    },

    // Sistema de Banners
    {
        path: "/administrador/banners",
        name: "banners.index",
        component: () => import("../views/banners/index.vue"),
        meta: {
            permission: "carrusel.index",
            title: "Banners",
        },
    },
    {
        path: "/administrador/banners/crear",
        name: "banners.crear",
        component: () => import("../views/banners/crear.vue"),
        meta: {
            permission: "carrusel.create",
            title: "Crear Banner",
        },
    },
    {
        path: "/administrador/banners/:id/editar",
        name: "banners.editar",
        component: () => import("../views/banners/editar.vue"),
        meta: {
            permission: "carrusel.edit",
            title: "Editar Banner",
        },
    },

    // Sistema de Folletos (Catálogo)
    {
        path: "/administrador/folletos",
        name: "folletos.index",
        component: () => import("../views/folletos/index.vue"),
        meta: {
            permission: "carrusel.index",
            title: "Folletos y Catálogos",
        },
    },
    {
        path: "/administrador/folletos/crear",
        name: "folletos.crear",
        component: () => import("../views/folletos/crear.vue"),
        meta: {
            permission: "carrusel.create",
            title: "Nuevo Folleto",
        },
    },
    {
        path: "/administrador/folletos/:id/editar",
        name: "folletos.editar",
        component: () => import("../views/folletos/editar.vue"),
        meta: {
            permission: "carrusel.edit",
            title: "Editar Folleto",
        },
    },
    {
        path: "/administrador/folletos/estadisticas",
        name: "folletos.estadisticas",
        component: () => import("../views/folletos/Estadisticas.vue"),
        meta: {
            permission: "carrusel.edit",
            title: "Estadísticas de Folletos",
        },
    },

    // Sistema de Empresa (Información general) - ¡ESTAS SON LAS QUE FUNCIONAN!
    {
        path: "/administrador/empresa",
        name: "empresa-datos",
        component: () => import("../views/empresa/index.vue"),
        meta: {
            permission: "carrusel.index", // ¡AGREGA EL PERMISO!
            title: "Datos de Empresa",
        },
    },
    {
        path: "/administrador/empresa/crear",
        name: "empresa-datos-crear",
        component: () => import("../views/empresa/crear.vue"),
        meta: {
            permission: "carrusel.create", // ¡AGREGA EL PERMISO!
            title: "Crear Dato",
        },
    },
    {
        path: "/administrador/empresa/:id/editar",
        name: "empresa-datos-editar",
        component: () => import("../views/empresa/editar.vue"),
        meta: {
            permission: "carrusel.edit", // ¡AGREGA EL PERMISO!
            title: "Editar Dato",
        },
        props: true,
    },

    // Sistema de Métodos de Pago - ¡CORREGIDO Y UNIFICADO!
    {
        path: "/administrador/metodos-pago",
        name: "metodos-pago.index",
        component: () => import("../views/metodos-pago/index.vue"),
        meta: {
            permission: "carrusel.index",
            title: "Métodos de Pago",
        },
    },
    {
        path: "/administrador/metodos-pago/crear",
        name: "metodos-pago.crear",
        component: () => import("../views/metodos-pago/crear.vue"),
        meta: {
            permission: "carrusel.create",
            title: "Crear Método de Pago",
        },
    },
    {
        path: "/administrador/metodos-pago/:id/editar",
        name: "metodos-pago.editar",
        component: () => import("../views/metodos-pago/editar.vue"),
        meta: {
            permission: "carrusel.edit",
            title: "Editar Método de Pago",
        },
    },

    {
        path: "/administrador/transportes",
        name: "transportes.index",
        component: () => import("../views/transportes/index.vue"),
        meta: {
            permission: "carrusel.index",
            title: "Gestión de Transportes",
        },
    },
    {
        path: "/administrador/transportes/crear",
        name: "transportes.crear",
        component: () => import("../views/transportes/FormView.vue"),
        meta: {
            permission: "carrusel.create",
            title: "Nueva Ruta de Transporte",
        },
    },
    {
        path: "/administrador/transportes/:id/editar",
        name: "transportes.editar",
        component: () => import("../views/transportes/FormView.vue"),
        props: true,
        meta: {
            permission: "carrusel.edit",
            title: "Editar Transporte",
        },
    },

    {
        path: "/administrador/pedidos",
        name: "pedidos.index",
        component: () => import("../views/pedidos/Index.vue"),
        meta: { title: "Lista de Pedidos", auth: true, admin: true },
    },
    {
        path: "/administrador/pedidos/:id",
        name: "pedidos.show",
        component: () => import("../views/pedidos/Show.vue"),
        props: true,
        meta: { title: "Detalle de Pedido", auth: true, admin: true },
    },

    // ==================== TESTIMONIOS ====================
    {
        path: "/administrador/testimonios",
        name: "testimonios.index",
        component: () => import("../views/testimonials/TestimonialList.vue"),
        meta: {
            title: "Lista de Testimonios",
            requiresAuth: true,
        },
    },
    {
        path: "/administrador/testimonios/crear",
        name: "testimonios.create",
        component: () => import("../views/testimonials/TestimonialForm.vue"),
        meta: {
            title: "Crear Testimonio",
            requiresAuth: true,
        },
    },
    {
        path: "/administrador/testimonios/:id/editar",
        name: "testimonios.edit",
        component: () => import("../views/testimonials/TestimonialForm.vue"),
        props: true,
        meta: {
            title: "Editar Testimonio",
            requiresAuth: true,
        },
    },

    // ==================== CONFIGURACIÓN TESTIMONIOS ====================
    {
        path: "/administrador/testimonios/configuracion",
        name: "testimonios.config",
        component: () => import("../views/testimonials/TestimonialConfig.vue"),
        meta: {
            title: "Configuración de Testimonios",
            requiresAuth: true,
        },
    },

    // ==================== VIDEOS DE ÉXITO ====================
    {
        path: "/administrador/videos-exito",
        name: "videos-exito.index",
        component: () => import("../views/videos-exito/VideoExitoList.vue"),
        meta: {
            title: "Videos de Éxito",
            requiresAuth: true,
        },
    },
    {
        path: "/administrador/videos-exito/crear",
        name: "videos-exito.create",
        component: () => import("../views/videos-exito/VideoExitoForm.vue"),
        meta: {
            title: "Agregar Video de Éxito",
            requiresAuth: true,
        },
    },
    {
        path: "/administrador/videos-exito/:id/editar",
        name: "videos-exito.edit",
        component: () => import("../views/videos-exito/VideoExitoForm.vue"),
        props: true,
        meta: {
            title: "Editar Video de Éxito",
            requiresAuth: true,
        },
    },

    // ==================== FORMULARIOS DE ÉXITO ====================
    {
        path: "/administrador/testimonios/exito-confirm",
        name: "testimonios.exito-confirm",
        component: () => import("../views/videos-exito/VideoExitoConfig.vue"),
        meta: {
            title: "Confirmación de Éxito",
            requiresAuth: true,
        },
    },

    // {
    //     path: '/administrador/pedidos/pagos-pendientes',
    //     name: 'pedidos.pagos-pendientes',
    //     component: () => import('../views/pedidos/PedidosPagosPendientes.vue'),
    //     meta: { title: 'Pagos Pendientes', auth: true, admin: true }
    // },
    // {
    //     path: '/administrador/pedidos/estadisticas',
    //     name: 'pedidos.estadisticas',
    //     component: () => import('../views/pedidos/PedidosEstadisticas.vue'),
    //     meta: { title: 'Estadísticas de Pedidos', auth: true, admin: true }
    // },

    // Sistema de Categorías
    {
        path: "/administrador/categorias",
        name: "categorias.index",
        component: () => import("../views/categorias/index.vue"),
        meta: {
            permission: "categorias.index",
            title: "Categorías",
        },
    },
    {
        path: "/administrador/categorias/crear",
        name: "categorias.crear",
        component: () => import("../views/categorias/crear.vue"),
        meta: {
            permission: "categorias.create",
            title: "Crear Categoría",
        },
    },
    {
        path: "/administrador/categorias/:id/editar",
        name: "categorias.editar",
        component: () => import("../views/categorias/editar.vue"),
        meta: {
            permission: "categorias.edit",
            title: "Editar Categoría",
        },
    },

    // Sistema de Marcas
    {
        path: "/administrador/marcas",
        name: "marcas.index",
        component: () => import("../views/marcas/Index.vue"),
        meta: {
            permission: "marcas.index",
            title: "Marcas",
        },
    },
    {
        path: "/administrador/marcas/crear",
        name: "marcas.crear",
        component: () => import("../views/marcas/Crear.vue"),
        meta: {
            permission: "marcas.create",
            title: "Crear Marca",
        },
    },
    {
        path: "/administrador/marcas/:id/editar",
        name: "marcas.editar",
        component: () => import("../views/marcas/Editar.vue"),
        meta: {
            permission: "marcas.edit",
            title: "Editar Marca",
        },
    },

    // Sistema de Colores
    {
        path: "/administrador/colores",
        name: "colores.index",
        component: () => import("../views/colores/ColoresIndex.vue"),
        meta: {
            permission: "colores.index",
            title: "Colores",
        },
    },

    // Sistema de Tallas
    {
        path: "/administrador/tallas",
        name: "tallas.index",
        component: () => import("../views/tallas/TallasIndex.vue"),
        meta: {
            permission: "tallas.index",
            title: "Tallas",
        },
    },

    // Sistema de Plazas
    {
        path: "/administrador/plazas",
        name: "plazas.index",
        component: () => import("../views/plazas/PlazasIndex.vue"),
        meta: {
            permission: "plazas.index",
            title: "Plazas",
        },
    },

    // Sección Nosotros (singleton)
    {
        path: "/administrador/nosotros",
        name: "nosotros.index",
        component: () => import("../views/nosotros/Index.vue"),
        meta: {
            permission: "view nosotros",
            title: "Nosotros",
        },
    },

    // Sistema de Medidas
    {
        path: "/administrador/medidas",
        name: "medidas.index",
        component: () => import("../views/medidas/MedidasIndex.vue"),
        meta: {
            permission: "medidas.index",
            title: "Medidas",
        },
    },

    // Sistema de Sabores
    {
        path: "/administrador/sabores",
        name: "sabores.index",
        component: () => import("../views/sabores/SaborIndex.vue"),
        meta: {
            permission: "sabores.index",
            title: "Sabores",
        },
    },

    // Sistema de Modelos
    {
        path: "/administrador/modelos",
        name: "modelos.index",
        component: () => import("../views/modelos/ModeloIndex.vue"),
        meta: {
            permission: "modelos.index",
            title: "Modelos",
        },
    },

    // Sistema de Tonos
    {
        path: "/administrador/tonos",
        name: "tonos.index",
        component: () => import("../views/tonos/TonoIndex.vue"),
        meta: {
            permission: "tonos.index",
            title: "Tonos",
        },
    },

    // Sistema de Artículos
    {
        path: "/administrador/articulos",
        name: "articulos.index",
        component: () => import("../views/articulos/articulosIndex.vue"),
        meta: {
            permission: "articulos.index",
            title: "Artículos",
        },
    },
    {
        path: "/administrador/articulos/create",
        name: "articulos.create",
        component: () => import("../views/articulos/articulosFormModal.vue"),
        meta: {
            permission: "articulos.create",
            title: "Crear Artículo",
        },
    },
    {
        path: "/administrador/articulos/:id",
        name: "articulos-show",
        component: () => import("../views/articulos/Show.vue"), // O el nombre correcto
        meta: {
            permission: "articulos.show",
            title: "Detalle del Artículo",
        },
    },
    {
        path: "/administrador/articulos/:id/edit",
        name: "articulos.edit",
        component: () => import("../views/articulos/articulosFormModal.vue"),
        meta: {
            permission: "articulos.edit",
            title: "Editar Artículo",
        },
    },

    // Dashboard Analytics
    {
        path: "/administrador/analytics",
        name: "analytics",
        component: () =>
            import(
                /* webpackChunkName: "analytics" */ "../views/analytics.vue"
            ),
    },
    {
        path: "/administrador/finance",
        name: "finance",
        component: () =>
            import(/* webpackChunkName: "finance" */ "../views/finance.vue"),
    },
    {
        path: "/administrador/crypto",
        name: "crypto",
        component: () =>
            import(/* webpackChunkName: "crypto" */ "../views/crypto.vue"),
    },

    // apps
    {
        path: "/administrador/apps/chat",
        name: "chat",
        component: () =>
            import(
                /* webpackChunkName: "apps-chat" */ "../views/apps/chat.vue"
            ),
    },
    {
        path: "/administrador/apps/mailbox",
        name: "mailbox",
        component: () =>
            import(
                /* webpackChunkName: "apps-mailbox" */ "../views/apps/mailbox.vue"
            ),
    },
    {
        path: "/administrador/apps/todolist",
        name: "todolist",
        component: () =>
            import(
                /* webpackChunkName: "apps-todolist" */ "../views/apps/todolist.vue"
            ),
    },
    {
        path: "/administrador/apps/notes",
        name: "notes",
        component: () =>
            import(
                /* webpackChunkName: "apps-notes" */ "../views/apps/notes.vue"
            ),
    },
    {
        path: "/administrador/apps/scrumboard",
        name: "scrumboard",
        component: () =>
            import(
                /* webpackChunkName: "apps-scrumboard" */ "../views/apps/scrumboard.vue"
            ),
    },
    {
        path: "/administrador/apps/contacts",
        name: "contacts",
        component: () =>
            import(
                /* webpackChunkName: "apps-contacts" */ "../views/apps/contacts.vue"
            ),
    },
    // invoice
    {
        path: "/administrador/apps/invoice/list",
        name: "invoice-list",
        component: () =>
            import(
                /* webpackChunkName: "apps-invoice-list" */ "../views/apps/invoice/list.vue"
            ),
    },
    {
        path: "/administrador/apps/invoice/preview",
        name: "invoice-preview",
        component: () =>
            import(
                /* webpackChunkName: "apps-invoice-preview" */ "../views/apps/invoice/preview.vue"
            ),
    },
    {
        path: "/administrador/apps/invoice/add",
        name: "invoice-add",
        component: () =>
            import(
                /* webpackChunkName: "apps-invoice-add" */ "../views/apps/invoice/add.vue"
            ),
    },
    {
        path: "/administrador/apps/invoice/edit",
        name: "invoice-edit",
        component: () =>
            import(
                /* webpackChunkName: "apps-invoice-edit" */ "../views/apps/invoice/edit.vue"
            ),
    },
    {
        path: "/administrador/apps/calendar",
        name: "calendar",
        component: () =>
            import(
                /* webpackChunkName: "apps-calendar" */ "../views/apps/calendar.vue"
            ),
    },

    // components
    {
        path: "/administrador/components/tabs",
        name: "tabs",
        component: () =>
            import(
                /* webpackChunkName: "components-tabs" */ "../views/components/tabs.vue"
            ),
    },
    {
        path: "/administrador/components/accordions",
        name: "accordions",
        component: () =>
            import(
                /* webpackChunkName: "components-accordions" */ "../views/components/accordions.vue"
            ),
    },
    {
        path: "/administrador/components/modals",
        name: "modals",
        component: () =>
            import(
                /* webpackChunkName: "components-modals" */ "../views/components/modals.vue"
            ),
    },
    {
        path: "/administrador/components/cards",
        name: "cards",
        component: () =>
            import(
                /* webpackChunkName: "components-cards" */ "../views/components/cards.vue"
            ),
    },
    {
        path: "/administrador/components/carousel",
        name: "carousel",
        component: () =>
            import(
                /* webpackChunkName: "components-carousel" */ "../views/components/carousel.vue"
            ),
    },
    {
        path: "/administrador/components/countdown",
        name: "countdown",
        component: () =>
            import(
                /* webpackChunkName: "components-countdown" */ "../views/components/countdown.vue"
            ),
    },
    {
        path: "/administrador/components/counter",
        name: "counter",
        component: () =>
            import(
                /* webpackChunkName: "components-counter" */ "../views/components/counter.vue"
            ),
    },
    {
        path: "/administrador/components/sweetalert",
        name: "sweetalert",
        component: () =>
            import(
                /* webpackChunkName: "components-sweetalert" */ "../views/components/sweetalert.vue"
            ),
    },
    {
        path: "/administrador/components/timeline",
        name: "timeline",
        component: () =>
            import(
                /* webpackChunkName: "components-timeline" */ "../views/components/timeline.vue"
            ),
    },
    {
        path: "/administrador/components/notifications",
        name: "notifications",
        component: () =>
            import(
                /* webpackChunkName: "components-notifications" */ "../views/components/notifications.vue"
            ),
    },
    {
        path: "/administrador/components/media-object",
        name: "media-object",
        component: () =>
            import(
                /* webpackChunkName: "components-media-object" */ "../views/components/media-object.vue"
            ),
    },
    {
        path: "/administrador/components/list-group",
        name: "list-group",
        component: () =>
            import(
                /* webpackChunkName: "components-list-group" */ "../views/components/list-group.vue"
            ),
    },
    {
        path: "/administrador/components/pricing-table",
        name: "pricing-table",
        component: () =>
            import(
                /* webpackChunkName: "components-pricing-table" */ "../views/components/pricing-table.vue"
            ),
    },
    {
        path: "/administrador/components/lightbox",
        name: "lightbox",
        component: () =>
            import(
                /* webpackChunkName: "components-lightbox" */ "../views/components/lightbox.vue"
            ),
    },

    //elements
    {
        path: "/administrador/elements/alerts",
        name: "alerts",
        component: () =>
            import(
                /* webpackChunkName: "elements-alerts" */ "../views/elements/alerts.vue"
            ),
    },
    {
        path: "/administrador/elements/avatar",
        name: "avatar",
        component: () =>
            import(
                /* webpackChunkName: "elements-avatar" */ "../views/elements/avatar.vue"
            ),
    },
    {
        path: "/administrador/elements/badges",
        name: "badges",
        component: () =>
            import(
                /* webpackChunkName: "elements-badges" */ "../views/elements/badges.vue"
            ),
    },
    {
        path: "/administrador/elements/breadcrumbs",
        name: "breadcrumbs",
        component: () =>
            import(
                /* webpackChunkName: "elements-breadcrumbs" */ "../views/elements/breadcrumbs.vue"
            ),
    },
    {
        path: "/administrador/elements/buttons",
        name: "buttons",
        component: () =>
            import(
                /* webpackChunkName: "elements-buttons" */ "../views/elements/buttons.vue"
            ),
    },
    {
        path: "/administrador/elements/buttons-group",
        name: "buttons-group",
        component: () =>
            import(
                /* webpackChunkName: "elements-buttons-group" */ "../views/elements/buttons-group.vue"
            ),
    },
    {
        path: "/administrador/elements/color-library",
        name: "color-library",
        component: () =>
            import(
                /* webpackChunkName: "elements-color-library" */ "../views/elements/color-library.vue"
            ),
    },
    {
        path: "/administrador/elements/dropdown",
        name: "dropdown",
        component: () =>
            import(
                /* webpackChunkName: "elements-dropdown" */ "../views/elements/dropdown.vue"
            ),
    },
    {
        path: "/administrador/elements/infobox",
        name: "infobox",
        component: () =>
            import(
                /* webpackChunkName: "elements-infobox" */ "../views/elements/infobox.vue"
            ),
    },
    {
        path: "/administrador/elements/jumbotron",
        name: "jumbotron",
        component: () =>
            import(
                /* webpackChunkName: "elements-jumbotron" */ "../views/elements/jumbotron.vue"
            ),
    },
    {
        path: "/administrador/elements/loader",
        name: "loader",
        component: () =>
            import(
                /* webpackChunkName: "elements-loader" */ "../views/elements/loader.vue"
            ),
    },
    {
        path: "/administrador/elements/pagination",
        name: "pagination",
        component: () =>
            import(
                /* webpackChunkName: "elements-pagination" */ "../views/elements/pagination.vue"
            ),
    },
    {
        path: "/administrador/elements/popovers",
        name: "popovers",
        component: () =>
            import(
                /* webpackChunkName: "elements-popovers" */ "../views/elements/popovers.vue"
            ),
    },
    {
        path: "/administrador/elements/progress-bar",
        name: "progress-bar",
        component: () =>
            import(
                /* webpackChunkName: "elements-progress-bar" */ "../views/elements/progress-bar.vue"
            ),
    },
    {
        path: "/administrador/elements/search",
        name: "search",
        component: () =>
            import(
                /* webpackChunkName: "elements-search" */ "../views/elements/search.vue"
            ),
    },
    {
        path: "/administrador/elements/tooltips",
        name: "tooltips",
        component: () =>
            import(
                /* webpackChunkName: "elements-tooltips" */ "../views/elements/tooltips.vue"
            ),
    },
    {
        path: "/administrador/elements/treeview",
        name: "treeview",
        component: () =>
            import(
                /* webpackChunkName: "elements-treeview" */ "../views/elements/treeview.vue"
            ),
    },
    {
        path: "/administrador/elements/typography",
        name: "typography",
        component: () =>
            import(
                /* webpackChunkName: "elements-typography" */ "../views/elements/typography.vue"
            ),
    },

    //charts
    {
        path: "/administrador/charts",
        name: "charts",
        component: () =>
            import(/* webpackChunkName: "charts" */ "../views/charts.vue"),
    },

    //widgets
    {
        path: "/administrador/widgets",
        name: "widgets",
        component: () =>
            import(/* webpackChunkName: "widgets" */ "../views/widgets.vue"),
    },

    //font-icons
    {
        path: "/administrador/font-icons",
        name: "font-icons",
        component: () =>
            import(
                /* webpackChunkName: "font-icons" */ "../views/font-icons.vue"
            ),
    },

    //dragndrop
    {
        path: "/administrador/dragndrop",
        name: "dragndrop",
        component: () =>
            import(
                /* webpackChunkName: "dragndrop" */ "../views/dragndrop.vue"
            ),
    },

    //tables
    {
        path: "/administrador/tables",
        name: "tables",
        component: () =>
            import(/* webpackChunkName: "tables" */ "../views/tables.vue"),
    },

    //datatables
    {
        path: "/administrador/datatables/basic",
        name: "datatables-basic",
        component: () =>
            import(
                /* webpackChunkName: "datatables-basic" */ "../views/datatables/basic.vue"
            ),
    },
    {
        path: "/administrador/datatables/advanced",
        name: "datatables-advanced",
        component: () =>
            import(
                /* webpackChunkName: "datatables-advanced" */ "../views/datatables/advanced.vue"
            ),
    },
    {
        path: "/administrador/datatables/skin",
        name: "skin",
        component: () =>
            import(
                /* webpackChunkName: "datatables-skin" */ "../views/datatables/skin.vue"
            ),
    },
    {
        path: "/administrador/datatables/order-sorting",
        name: "order-sorting",
        component: () =>
            import(
                /* webpackChunkName: "datatables-order-sorting" */ "../views/datatables/order-sorting.vue"
            ),
    },
    {
        path: "/administrador/datatables/columns-filter",
        name: "columns-filter",
        component: () =>
            import(
                /* webpackChunkName: "datatables-columns-filter" */ "../views/datatables/columns-filter.vue"
            ),
    },
    {
        path: "/administrador/datatables/multi-column",
        name: "multi-column",
        component: () =>
            import(
                /* webpackChunkName: "datatables-multi-column" */ "../views/datatables/multi-column.vue"
            ),
    },
    {
        path: "/administrador/datatables/multiple-tables",
        name: "multiple-tables",
        component: () =>
            import(
                /* webpackChunkName: "datatables-multiple-tables" */ "../views/datatables/multiple-tables.vue"
            ),
    },
    {
        path: "/administrador/datatables/alt-pagination",
        name: "alt-pagination",
        component: () =>
            import(
                /* webpackChunkName: "datatables-alt-pagination" */ "../views/datatables/alt-pagination.vue"
            ),
    },
    {
        path: "/administrador/datatables/checkbox",
        name: "checkbox",
        component: () =>
            import(
                /* webpackChunkName: "datatables-checkbox" */ "../views/datatables/checkbox.vue"
            ),
    },
    {
        path: "/administrador/datatables/range-search",
        name: "range-search",
        component: () =>
            import(
                /* webpackChunkName: "datatables-range-search" */ "../views/datatables/range-search.vue"
            ),
    },
    {
        path: "/administrador/datatables/export",
        name: "export",
        component: () =>
            import(
                /* webpackChunkName: "datatables-export" */ "../views/datatables/export.vue"
            ),
    },
    {
        path: "/administrador/datatables/sticky-header",
        name: "sticky-header",
        component: () =>
            import(
                /* webpackChunkName: "datatables-sticky-header" */ "../views/datatables/sticky-header.vue"
            ),
    },
    {
        path: "/administrador/datatables/clone-header",
        name: "clone-header",
        component: () =>
            import(
                /* webpackChunkName: "datatables-clone-header" */ "../views/datatables/clone-header.vue"
            ),
    },
    {
        path: "/administrador/datatables/column-chooser",
        name: "column-chooser",
        component: () =>
            import(
                /* webpackChunkName: "datatables-column-chooser" */ "../views/datatables/column-chooser.vue"
            ),
    },

    //forms
    {
        path: "/administrador/forms/basic",
        name: "basic",
        component: () =>
            import(
                /* webpackChunkName: "forms-basic" */ "../views/forms/basic.vue"
            ),
    },
    {
        path: "/administrador/forms/input-group",
        name: "input-group",
        component: () =>
            import(
                /* webpackChunkName: "forms-input-group" */ "../views/forms/input-group.vue"
            ),
    },
    {
        path: "/administrador/forms/layouts",
        name: "layouts",
        component: () =>
            import(
                /* webpackChunkName: "forms-layouts" */ "../views/forms/layouts.vue"
            ),
    },
    {
        path: "/administrador/forms/validation",
        name: "validation",
        component: () =>
            import(
                /* webpackChunkName: "forms-validation" */ "../views/forms/validation.vue"
            ),
    },
    {
        path: "/administrador/forms/input-mask",
        name: "input-mask",
        component: () =>
            import(
                /* webpackChunkName: "forms-input-mask" */ "../views/forms/input-mask.vue"
            ),
    },
    {
        path: "/administrador/forms/select2",
        name: "select2",
        component: () =>
            import(
                /* webpackChunkName: "forms-select2" */ "../views/forms/select2.vue"
            ),
    },
    {
        path: "/administrador/forms/touchspin",
        name: "touchspin",
        component: () =>
            import(
                /* webpackChunkName: "forms-touchspin" */ "../views/forms/touchspin.vue"
            ),
    },
    {
        path: "/administrador/forms/checkbox-radio",
        name: "checkbox-radio",
        component: () =>
            import(
                /* webpackChunkName: "forms-checkbox-radio" */ "../views/forms/checkbox-radio.vue"
            ),
    },
    {
        path: "/administrador/forms/switches",
        name: "switches",
        component: () =>
            import(
                /* webpackChunkName: "forms-switches" */ "../views/forms/switches.vue"
            ),
    },
    {
        path: "/administrador/forms/wizards",
        name: "wizards",
        component: () =>
            import(
                /* webpackChunkName: "forms-wizards" */ "../views/forms/wizards.vue"
            ),
    },
    {
        path: "/administrador/forms/file-upload",
        name: "file-upload",
        component: () =>
            import(
                /* webpackChunkName: "forms-file-upload" */ "../views/forms/file-upload.vue"
            ),
    },
    {
        path: "/administrador/forms/quill-editor",
        name: "quill-editor",
        component: () =>
            import(
                /* webpackChunkName: "forms-quill-editor" */ "../views/forms/quill-editor.vue"
            ),
    },
    {
        path: "/administrador/forms/markdown-editor",
        name: "markdown-editor",
        component: () =>
            import(
                /* webpackChunkName: "forms-markdown-editor" */ "../views/forms/markdown-editor.vue"
            ),
    },
    {
        path: "/administrador/forms/date-picker",
        name: "date-picker",
        component: () =>
            import(
                /* webpackChunkName: "forms-date-picker" */ "../views/forms/date-picker.vue"
            ),
    },
    {
        path: "/administrador/forms/clipboard",
        name: "clipboard",
        component: () =>
            import(
                /* webpackChunkName: "forms-clipboard" */ "../views/forms/clipboard.vue"
            ),
    },

    // users
    {
        path: "/administrador/users/profile",
        name: "profile",
        component: () =>
            import(
                /* webpackChunkName: "users-profile" */ "../views/users/profile.vue"
            ),
    },
    {
        path: "/administrador/users/user-account-settings",
        name: "user-account-settings",
        component: () =>
            import(
                /* webpackChunkName: "users-user-account-settings" */ "../views/users/user-account-settings.vue"
            ),
    },

    // pages
    {
        path: "/administrador/pages/knowledge-base",
        name: "knowledge-base",
        component: () =>
            import(
                /* webpackChunkName: "pages-knowledge-base" */ "../views/pages/knowledge-base.vue"
            ),
    },
    {
        path: "/pages/contact-us-boxed",
        name: "contact-us-boxed",
        component: () =>
            import(
                /* webpackChunkName: "pages-contact-us-boxed" */ "../views/pages/contact-us-boxed.vue"
            ),
        meta: { layout: "auth" },
    },
    {
        path: "/pages/contact-us-cover",
        name: "contact-us-cover",
        component: () =>
            import(
                /* webpackChunkName: "pages-contact-us-cover" */ "../views/pages/contact-us-cover.vue"
            ),
        meta: { layout: "auth" },
    },
    {
        path: "/administrador/pages/faq",
        name: "faq",
        component: () =>
            import(
                /* webpackChunkName: "pages-faq" */ "../views/pages/faq.vue"
            ),
    },
    {
        path: "/pages/coming-soon-boxed",
        name: "coming-soon-boxed",
        component: () =>
            import(
                /* webpackChunkName: "pages-coming-soon-boxed" */ "../views/pages/coming-soon-boxed.vue"
            ),
        meta: { layout: "auth" },
    },
    {
        path: "/pages/coming-soon-cover",
        name: "coming-soon-cover",
        component: () =>
            import(
                /* webpackChunkName: "pages-coming-soon-cover" */ "../views/pages/coming-soon-cover.vue"
            ),
        meta: { layout: "auth" },
    },
    {
        path: "/pages/error404",
        name: "error404",
        component: () =>
            import(
                /* webpackChunkName: "pages-error404" */ "../views/pages/error404.vue"
            ),
        meta: { layout: "auth" },
    },
    {
        path: "/pages/error500",
        name: "error500",
        component: () =>
            import(
                /* webpackChunkName: "pages-error500" */ "../views/pages/error500.vue"
            ),
        meta: { layout: "auth" },
    },
    {
        path: "/pages/error503",
        name: "error503",
        component: () =>
            import(
                /* webpackChunkName: "pages-error503" */ "../views/pages/error503.vue"
            ),
        meta: { layout: "auth" },
    },
    {
        path: "/pages/maintenence",
        name: "maintenence",
        component: () =>
            import(
                /* webpackChunkName: "pages-maintenence" */ "../views/pages/maintenence.vue"
            ),
        meta: { layout: "auth" },
    },

    // Redirección de la raíz
    {
        path: "/",
        redirect: () => {
            // Redirigir a login si no está autenticado, si no a home
            const authStore = useAuthStore();
            return authStore.isAuthenticated
                ? "/administrador/home"
                : "/administrador/login";
        },
    },
    // Redirección de login antiguo al nuevo
    {
        path: "/login",
        redirect: "/administrador/login",
    },
];

const router = createRouter({
    history: createWebHistory(),
    linkExactActiveClass: "active",
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        } else {
            return { left: 0, top: 0 };
        }
    },
});

router.beforeEach(async (to, from, next) => {
    const store = useAppStore();
    const authStore = useAuthStore();

    // Prevenir navegación durante el logout (excepto a login)
    if (authStore.isLoggingOut && to.path !== "/administrador/login") {
        return; // Bloquear navegación
    }

    // Configurar layout
    if (to?.meta?.layout == "auth") {
        store.setMainLayout("auth");
    } else {
        store.setMainLayout("app");
    }

    // Rutas públicas (no requieren autenticación)
    const publicRoutes = [
        "/administrador/login",
        "/administrador/auth/boxed-signup",
        "/administrador/auth/boxed-lockscreen",
        "/administrador/auth/cover-password-reset",
        "/administrador/auth/cover-signup",
    ];

    // Si es una ruta pública
    if (publicRoutes.includes(to.path)) {
        // Si está autenticado y trata de acceder a login, redirigir al administrador
        if (to.path === "/administrador/login" && authStore.isAuthenticated) {
            next("/administrador/home");
            return;
        }
        next();
        return;
    }

    // Verificar autenticación para rutas protegidas
    if (!authStore.isAuthenticated) {
        // Intentar recuperar la sesión del localStorage
        const hasValidSession = authStore.initAuth();

        if (hasValidSession) {
            // Verificar que el token siga siendo válido
            const isValidUser = await authStore.getUser();
            if (!isValidUser) {
                next("/administrador/login");
                return;
            }
        } else {
            next("/administrador/login");
            return;
        }
    }

    // Verificar permisos si la ruta los requiere
    if (to.meta?.permission && typeof to.meta.permission === "string") {
        if (!authStore.hasPermission(to.meta.permission)) {
            // Redirigir a página de acceso denegado o administrador
            next("/administrador/home");
            return;
        }
    }

    next();
});

router.afterEach((to, from, next) => {
    appSetting.changeAnimation();
});

export default router;
