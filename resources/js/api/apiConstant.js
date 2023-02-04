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
    LIST_PRODUCT_ATTRIBUTE_PRICES: 'admin/product-attribute-prices',
    CREATE_PRODUCT_ATTRIBUTE_VALUE: 'admin/product-attribute-value',
    DOWNLOAD_USER_EXCEL: 'admin/users/export',
    EXPORT_ORDER: 'admin/order/export-order',

    LIST_ORDER: 'admin/order/list-order',
    CREATE_ORDER: 'admin/order/create-order',
    DETAIL_ORDER: 'admin/order/detail-order',

    LIST_IMPORT_GOOD: 'admin/import-good/import-goods',
    CREATE_IMPORT_GOOD: 'admin/import-good/import-good',
    RESTORE_IMPORT_GOOD: 'admin/import-good/restore-import-good',
    DETAIL_IMPORT_GOOD: 'admin/import-good/detail-import-good',

    LIST_DEALER: 'admin/dealer/dealers',

    LIST_DEBT: 'admin/debt/list-debt',
    LIST_CUSTOMER_DEBT: 'admin/debt/list-customer-debt',
    CUSTOMER_CURRENT_DEBT: 'admin/debt/customer-current-debt',
    CREATE_CONTAINER_ORDER: 'admin/debt/create-container-order',
    CREATE_VAT_DEBT: 'admin/debt/create-vat-debt',
    CREATE_PAYMENT: 'admin/debt/create-payment',

    STATISTICAL_REVENUES: 'admin/statistical/revenues',
    STATISTICAL_PERIOD_REVENUES: 'admin/statistical/period-revenues',
    STATISTICAL_PRODUCT_SALE: 'admin/statistical/product-sale-statistical',
    STATISTICAL_COUNT_ORDER: 'admin/statistical/count-customer-order',
  }
};
