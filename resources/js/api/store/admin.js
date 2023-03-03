import api from "../http-request";
import apiConstants from "../apiConstant";

//[POST] method
export const login = async (data) =>
    api.post(apiConstants.AUTH.LOGIN, data, {
        headers: {
            "Content-Type": "multipart/form-data",
        },
    });

// user manage
export const getListUserManagerFromApi = async (page) => api.get(`${apiConstants.ADMIN.LIST_USER}?page=${page}`)
export const createUserFromApi = async (data) => await api.post(apiConstants.ADMIN.CREATE_USER, data)
export const updateUserProfileFormApi = async (userId, data) => api.put(`${apiConstants.ADMIN.UPDATE_USER}/${userId}`, data)
export const getUserDetailFromApi = async (userId) => api.get(`${apiConstants.ADMIN.USER_DETAIL}/${userId}`)
export const logout = async (data) => api.post(apiConstants.AUTH.LOGOUT, data, {})

// customer manage
export const getListCustomerFromApi = async (page, config) => api.get(`${apiConstants.ADMIN.LIST_CUSTOMER}?page=${page}`, config)
export const createCustomerFromApi = async (data) => await api.post(apiConstants.ADMIN.CREATE_CUSTOMER, data)
export const getCustomerDetailFromApi = async (customerId) => api.get(`${apiConstants.ADMIN.CUSTOMER_DETAIL}/${customerId}`)
export const updateCustomerFormApi = async (customerId, data) => api.put(`${apiConstants.ADMIN.UPDATE_CUSTOMER}/${customerId}`, data)

// product manage
export const getListProductFromApi = async (page, config) => api.get(`${apiConstants.ADMIN.LIST_PRODUCT}?page=${page}`, config)
export const createProductFromApi = async (data) => await api.post(apiConstants.ADMIN.CREATE_PRODUCT, data)
export const getProductDetailFromApi = async (productId) => api.get(`${apiConstants.ADMIN.PRODUCT_DETAIL}/${productId}`)
export const updateProductAttributePriceFromApi = async (data) => api.put(apiConstants.ADMIN.UPDATE_PRODUCT_ATTRIBUTE_PRICE, data)

// category manage
export const getListCategoryFromApi = async (page) => api.get(`${apiConstants.ADMIN.LIST_CATEGORY}?page=${page}`)
export const createCategoryFromApi = async (data) => api.post(apiConstants.ADMIN.CREATE_CATEGORY, data)
export const getCategoryDetailFromApi = async (categoryId) => api.get(`${apiConstants.ADMIN.CATEGORY_DETAIL}/${categoryId}`)
export const updateCategoryFromApi = async (categoryId, data) => api.put(`${apiConstants.ADMIN.UPDATE_CATEGORY}/${categoryId}`, data)

// product attribute
export const getListProductAttributeFromApi = async (page) => api.get(`${apiConstants.ADMIN.LIST_PRODUCT_ATTRIBUTE}?page=${page}`)

// measure unit
export const getListMeasureUnitFromApi = async (page) => api.get(`${apiConstants.ADMIN.LIST_MEASURE_UNIT}?page=${page}`)

// product attribute value
export const createProductAttributeValueFromApi = async (data) => await api.post(apiConstants.ADMIN.CREATE_PRODUCT_ATTRIBUTE_VALUE, data, {
    headers: {
        "Content-Type": "multipart/form-data",
    },
})

//order
export const getListOrderFromApi = async (page, config) => api.get(`${apiConstants.ADMIN.LIST_ORDER}?page=${page}`, config)
export const createOrderFromApi = async (data) => await api.post(apiConstants.ADMIN.CREATE_ORDER, data)
export const getOrderDetailFromApi = async (id) => await api.get(`${apiConstants.ADMIN.DETAIL_ORDER}/${id}`)

// product attribute price
export const getListProductAttributePriceFromApi = async () => api.get(`${apiConstants.ADMIN.LIST_PRODUCT_ATTRIBUTE_PRICES}`)

export const exportOrderFromApi = async (data) => await api.post(apiConstants.ADMIN.EXPORT_ORDER, data,{responseType: 'blob'})

export const getListImportGoodFromApi = async (params) => api.get(`${apiConstants.ADMIN.LIST_IMPORT_GOOD}`, {params})
export const createImportGoodFromApi = async (data) => api.post(`${apiConstants.ADMIN.CREATE_IMPORT_GOOD}`, data)
export const restoreImportGoodFromApi = async (id) => api.delete(`${apiConstants.ADMIN.RESTORE_IMPORT_GOOD}/${id}`)
export const getImportGoodDetailFromApi = async (id) => await api.get(`${apiConstants.ADMIN.DETAIL_IMPORT_GOOD}/${id}`)
export const createContainerOrderFromApi = async (data) => api.post(`${apiConstants.ADMIN.CREATE_CONTAINER_ORDER}`, data)
export const createVatFromApi = async (data) => api.post(`${apiConstants.ADMIN.CREATE_VAT_DEBT}`, data)
export const createPaymentFromApi = async (data) => api.post(`${apiConstants.ADMIN.CREATE_PAYMENT}`, data)

// dealer
export const getListDealerFromApi = async (page) => api.get(`${apiConstants.ADMIN.LIST_DEALER}?page=${page}`)

export const getListDebtFromApi = async (params) => api.get(`${apiConstants.ADMIN.LIST_DEBT}`, {params})
export const getListCustomerDebtFromApi = async (id, params) => api.get(`${apiConstants.ADMIN.LIST_CUSTOMER_DEBT}/${id}`, {params})
export const getCustomerCurrentDebtFromApi = async (id) => api.get(`${apiConstants.ADMIN.CUSTOMER_CURRENT_DEBT}/${id}`)
export const exportCustomerDebtHistoryFromApi = async (id) => await api.post(`${apiConstants.ADMIN.EXPORT_CUSTOMER_DEBT}/${id}`,{}, {responseType: 'blob'})

//statistical
export const getRevenuesFromApi = async (params) => api.get(apiConstants.ADMIN.STATISTICAL_REVENUES, {params})
export const getPeriodRevenuesFromApi = async (params) => api.get(apiConstants.ADMIN.STATISTICAL_PERIOD_REVENUES, {params})
export const getProductSaleStatisticalFromApi = async (params) => api.get(apiConstants.ADMIN.STATISTICAL_PRODUCT_SALE, {params})
export const getCountCustomerOrderFromApi = async (params) => api.get(apiConstants.ADMIN.STATISTICAL_COUNT_ORDER, {params})
