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
  DETAIL: "detail",
  DETAIL_ID: "detail/:id",

  PAYMENT: 'payment',

  //Debt
  LIST_CUSTOMER_DEBT: 'list-customer-debt',
  LIST_CUSTOMER_DEBT_ID: 'list-customer-debt/:id',

  // ROUTER_ADMIN
  ADMIN: "/admin",
  LOGIN: "/admin/login",
  SPACE: {},
  USER_MANAGER: "user-manage",
  PRODUCT_MANAGE: "product-manage",
  ROLE_MANAGE: "role-manage",
  CUSTOMER_MANAGE: "customer-manage",
  DASHBOARD: "dashboard",
  CATEGORY_MANAGE: "category-manage",
  ADD_PRODUCT_ATTRIBUTE_VALUE: "add-attribute-value",
  ADD_PRODUCT_ATTRIBUTE_VALUE_ID: "add-attribute-value/:id",
  ORDER_MANAGE: "order-manage",
  IMPORT_GOOD_MANAGE: "import-good-manage",
  DEBT_MANAGE: "debt-manage",

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
  HOLIDAY_IMPORT: "holiday-info-import",

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
    GETTERS: {
      GET_USER_NAME: 'GET_USER_NAME',
    },
    ACTIONS: {},
    MUTATIONS: {
      SET_USER_NAME: 'SET_USER_NAME',
    },
  },

  COMMON: {
    NAME: "COMMON",
    GETTERS: {
      GET_IS_LOADING: "GET_IS_LOADING",
    },
    ACTIONS: {},
    MUTATIONS: {},
  },

  ORDER: {
    NAME: "ORDER",
    GETTERS: {
      GET_ORDER_DATA: "GET_ORDER_DATA"
    },
    ACTIONS: {},
    MUTATIONS: {
      ADD_ORDER_DATA: 'ADD_ORDER_DATA',
      REMOVE_ORDER_DATA_ITEM: 'REMOVE_ORDER_DATA_ITEM',
      UPDATE_ORDER_DATA_ITEM: 'UPDATE_ORDER_DATA_ITEM',
      SORT_ORDER_DATA_ITEM: 'SORT_ORDER_DATA_ITEM',
    },
  },
  PRODUCT: {
    NAME: "PRODUCT",
    GETTERS: {},
    ACTIONS: {},
    MUTATIONS: {},
  },
  IMPORT_GOOD: {
    NAME: "IMPORT_GOOD",
    GETTERS: {
      GET_IMPORT_GOOD_DATA: "GET_IMPORT_GOOD_DATA"
    },
    ACTIONS: {},
    MUTATIONS: {
      ADD_IMPORT_GOOD_DATA: 'ADD_IMPORT_GOOD_DATA',
      REMOVE_IMPORT_GOOD_DATA_ITEM: 'REMOVE_IMPORT_GOOD_DATA_ITEM',
      UPDATE_IMPORT_GOOD_DATA_ITEM: 'UPDATE_IMPORT_GOOD_DATA_ITEM',
    },
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
