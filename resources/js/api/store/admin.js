import axios from "axios"

const request = axios.create({
    baseURL: '/api/admin',
})

export const getListUserManagerFromApi = async function() {
    const res = await request.get('/list-user')
    return res.data
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

export const logout = async function() {
    const res = await request.get('/list-user')
    console.log(res)
}
