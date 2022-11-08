export default {
  AUTH: {
    LOGIN: "/auth/login",
    LOGOUT: "/auth/logout",
    USER_MANAGER_CREATE: "/admin/customer/manage",
    USER_MANAGER: "/auth/user-management?page",
  },
  ADMIN: {
      LIST_USER: '/admin/list-user',
      USER_DETAIL: '/admin/user-detail',
      UPDATE_USER: '/admin/update-user',
      CREATE_USER: '/admin/create-user',

      LIST_CUSTOMER: 'admin/list-customer',
      CREATE_CUSTOMER: 'admin/create-customer',
      CUSTOMER_DETAIL: 'admin/customer-detail',
      UPDATE_CUSTOMER: 'admin/update-customer',

      LIST_PRODUCT: 'admin/list-product',
      CREATE_PRODUCT: 'admin/create-product',
      PRODUCT_DETAIL: 'admin/product-detail',
      UPDATE_PRODUCT: 'admin/update-product',

      LIST_CATEGORY: 'admin/list-category',
      CREATE_CATEGORY: 'admin/create-category',
      CATEGORY_DETAIL: 'admin/category-detail',
      UPDATE_CATEGORY: 'admin/update-category',

      LIST_PRODUCT_ATTRIBUTE: 'admin/product-attributes',
      LIST_MEASURE_UNIT: 'admin/measure-unit',
      CREATE_PRODUCT_ATTRIBUTE_VALUE: 'admin/product-attribute-value'
  }

  // ADMIN: "/admin/customer/user-manage",
  // CREATE_SPACE_GENERAL: "/admin/rental-spaces/create/general",
  // CREATE_SPACE: "/admin/rental-spaces/create",
  // UPLOAD_FILE: "/admin/rental-spaces/upload-image-storage",
  // GET_LIST_ALL_SPACES: "/admin/rental-spaces/manage",
  // GET_INFO_BANK_COMPANY: "/admin/organization",
  // GET_LIST_CUSTOMER: "/admin/customer/customer-manages",
  // GET_CUSTOMER_DETAIL: "/admin/customer/customer-manage",
  // CHANGE_STATUS_PUBLIC: "/admin/rental-spaces",
  // SETTING: "/admin/configuration/system-config/manage",
  // DETAIL_SPACE: "/admin/rental-spaces/detail",
  // LOGIN_CUSTOMER: "/customer/auth/login",
  // CREATE_CUSTOMER: "/customer/auth/customer-manage",
  // TOUR_MANAGER: "admin/rental-spaces/tour/manage",
  // TOUR_DETAIL: "admin/rental-spaces/tour/view",
};
