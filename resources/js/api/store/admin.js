import api from "../http-request";
import apiConstants from "../apiConstant";

//[POST] method
export const login = async (data) =>
    api.post(apiConstants.AUTH.LOGIN, data, {
        headers: {
            "Content-Type": "multipart/form-data",
        },
    });

export const getListUserManagerFromApi = async (page) => {
    `${api.get(apiConstants.ADMIN.LIST_USER)}=${page}`;
}

export const addUserManager = async function() {
    const res = await request.get('/list-user')
    console.log(res)
}

export const updateProfileManager = async function() {
    const res = await request.get('/list-user')
    console.log(res)
}

export const getUserDetailFromApi = async function(id) {
    const res = await request.get(`/user-detail/${id}`)
    return res.data
}

export const logout = async (data) => api.post(apiConstants.AUTH.LOGOUT, data, {});
