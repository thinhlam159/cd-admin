import api from "../http-request";
import apiConstants from "../apiConstant";

//[POST] method
export const login = async (data) =>
    api.post(apiConstants.AUTH.LOGIN, data, {
        headers: {
            "Content-Type": "multipart/form-data",
        },
    });

export const getListUserManagerFromApi = async (page) => api.get(`${apiConstants.ADMIN.LIST_USER}?page=${page}`);

export const addUserManager = async function() {
    const res = await request.get('/list-user')
    console.log(res)
}

export const updateUserProfileFormApi = async (userId, data) => api.put(`${apiConstants.ADMIN.UPDATE_USER}/${userId}`, data)

export const getUserDetailFromApi = async (userId) => api.get(`${apiConstants.ADMIN.USER_DETAIL}/${userId}`);

export const logout = async (data) => api.post(apiConstants.AUTH.LOGOUT, data, {});
