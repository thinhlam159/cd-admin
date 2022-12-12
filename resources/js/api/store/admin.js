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
export const getListCustomerFromApi = async (page) => api.get(`${apiConstants.ADMIN.LIST_CUSTOMER}?page=${page}`)
export const createCustomerFromApi = async (data) => await api.post(apiConstants.ADMIN.CREATE_CUSTOMER, data)
export const getCustomerDetailFromApi = async (customerId) => api.get(`${apiConstants.ADMIN.CUSTOMER_DETAIL}/${customerId}`)
export const updateCustomerFormApi = async (userId, data) => api.put(`${apiConstants.ADMIN.UPDATE_CUSTOMER}/${userId}`, data)

// product manage
export const getListProductFromApi = async (page, category) => api.get(`${apiConstants.ADMIN.LIST_PRODUCT}?page=${page}`, category)
export const createProductFromApi = async (data) => await api.post(apiConstants.ADMIN.CREATE_PRODUCT, data, {
    headers: {
        "Content-Type": "multipart/form-data",
    },
})
export const getProductDetailFromApi = async (productId) => api.get(`${apiConstants.ADMIN.PRODUCT_DETAIL}/${productId}`)

// category manage
export const getListCategoryFromApi = async (page) => api.get(`${apiConstants.ADMIN.LIST_CATEGORY}?page=${page}`)

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
export const getListOrderFromApi = async (page) => api.get(`${apiConstants.ADMIN.LIST_ORDER}?page=${page}`)
export const createOrderFromApi = async (data) => await api.post(apiConstants.ADMIN.CREATE_ORDER, data)
export const getOrderDetailFromApi = async (id) => await api.get(`${apiConstants.ADMIN.DETAIL_ORDER}/${id}`)

// product attribute price
export const getListProductAttributePriceFromApi = async () => api.get(`${apiConstants.ADMIN.LIST_PRODUCT_ATTRIBUTE_PRICES}`)

export const exportOrderFromApi = async (data) => await api.post(apiConstants.ADMIN.EXPORT_ORDER, data,{responseType: 'blob'})

export const getListImportGoodFromApi = async (param) => api.get(`${apiConstants.ADMIN.LIST_IMPORT_GOOD}`)
export const createImportGoodFromApi = async (data) => api.post(`${apiConstants.ADMIN.CREATE_IMPORT_GOOD}`, data)
export const deleteImportGoodFromApi = async () => api.delete(`${apiConstants.ADMIN.RESTORE_IMPORT_GOOD}`)

// dealer
export const getListDealerFromApi = async (page) => api.get(`${apiConstants.ADMIN.LIST_DEALER}?page=${page}`)
