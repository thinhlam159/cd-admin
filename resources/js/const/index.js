export const ROUTER_PATH = {
    //ROUTER_COMMON
    HOME: "/",
    EMPTY: "",
    ADD: "add",
    EDIT: "edit",
    RENTAL_PLAN: "rental-plan",
    RENTAL_PLAN_ID: "rental-plan/:id",
    EDIT_ID: "edit/:id",
    EMPTY_ID: ":id",

    // ROUTER_ADMIN
    ADMIN: "/admin",
    LOGIN: "/admin/login",
    SPACE: {

    },
    USER_MANAGER: "user-manage",
    PRODUCT_MANAGE: "product-manage",
    ROLE_MANAGE: "role-manage",
    CUSTOMER_MANAGE: "customer-manage",
    DASHBOARD: "dashboard",


    CONFIGURATION: "configuration",
    RESERVATIONS: "reservations",
    LIST_CUSTOMER: "list-customer",
    RESERVATION_CALENDAR: "reservation-calendar",
    RESERVATION_REGISTER: "reservation-register",
    CUSTOMER_VIEW_ID: "customer-view/:id",
    CUSTOMER_VIEW: "customer-view",
    SYSTEM_CONFIG: "system-config",
    NEWS: "news",
    CUSTOMER_NEWS: "customer-news",
    FAQ: "faq",
    STATIC_PAGE: "static-page",
    HTML_PAGE: "html-page",
    RENTAL_SPACE_COMPILATION: "rental-space-compilation",
    SLIDE: "slide",
    META: "meta",
    RENTAL_SPACE_USE_PURPOSE: "rental-space-use-purpose",
    RENTAL_SPACE_EQUIPMENT_INFORMATION: "rental-space-equipment-information",
    AREA: "area",
    AREA_GROUP: "area-group",
    EMAIL_TEMPLATE: "email-template",
    RENTAL_SPACE_POINTS: "rental-space-points",
    ORGANIZATION: "organization",
    REQUEST_MANAGEMENT: "request-management",
    DOCUMENTATION: "documentation/explorer/browse",
    FOOTER_LINKS: "footer_links",
    HOLIDAY_INFO: "holiday-info",
    PAYOUT: "payout",
    RENTAL_SPACE_COUPONS: "rental-space-coupons",
    CAMPAIGN: "campaign",
    TRACKING_LINKS: "tracking-links",
    XML_EXPORT: "xml-export",
    CSV_EXPORT: "csv-export",
    FIRST_CONTRACTOR: "first-contractor",
    EXTERNAL_RESERVATION: "external-reservation",
    STATIC_PAGE_ADD: "static-page-add",
    EMAIL_TEMPLATE_ADD: "email-template/add",
    HOLIDAY_IMPORT:"holiday-info-import",

    // ROUTER_CUSTOMER
    REGISTER_CUSTOMER: "/register",
    LOGIN_CUSTOMER: "/login",
    OVERVIEW_USER: "overview-user",
    SPACE_DETAILS_CLIENT: "space-details-client",
    SPACE_TOUR: "/space/tour",
};

export const MODULE_STORE = {
    AUTH: {
        NAME: "AUTH",
        GETTERS: {},
        ACTIONS: {},
        MUTATIONS: {},
    },

    COMMON: {
        NAME: "COMMON",
        GETTERS: {
            GET_IS_LOADING: "GET_IS_LOADING",
        },
        ACTIONS: {},
        MUTATIONS: {},
    },
};

export const TYPE_SCREEN = {
    SPACE_ADDING: "space_adding",
    SPACE_EDITING: "space_editing",
};

export const TYPE_USER = {
    USER: "user",
    ADMIN: "admin",
};

export const PAGE_DEFAULT = 1;

export const FORM_USER_MANAGE = {
    ORGANIZATION: "organization",
    ADMIN: "admin",
    MALE: "male",
    FEMALE: "female",
    INQUIRY: "inquiry",
    USER: "user",
    RESERVATION: "reservation",
    RESERVATION_COMPENSATION_CHARGE: "reservation_compensation_charge",
    CUSTOMER: "customer",
    RENTAL_SPACE: "rental_space",
    ALL: "all",
    SUPERVISOR: "supervisor",
    BILLING: "billing",
};
export const STATUS_CODE = {
    BadRequest: 400,
    Unauthorized: 401,
    Forbidden: 403,
    TooManyRequests: 429,
    ValidationFailed: 422,
    InternalServerError: 500,
};
