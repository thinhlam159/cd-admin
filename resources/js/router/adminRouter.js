import { auth, guest } from "./middlewares";
import { ROUTER_PATH, TYPE_SCREEN, TYPE_USER } from "@/const";
import Home from "@/views/Home";
import UserManage from "@/views/UserManage";
import ListUserManage from "@/views/ListUserManage";
import AddUserManage from "@/views/AddUserManage";
import EditUserManage from "@/views/EditUserManage";
import Login from "@/views/Login";
import AdminLayout from "@/components/Layouts/AdminLayout";

export default [
    // ROUTER_ADMIN
    {
        path: ROUTER_PATH.LOGIN,
        component: Login,
        meta: {
            middleware: [(context) => guest(context, TYPE_USER.ADMIN)],
        },
    },
    {
        path: ROUTER_PATH.ADMIN,
        component: AdminLayout,
        meta: {
            middleware: [(context) => guest(context, TYPE_USER.ADMIN)],
        },
        children: [
            {
                path: ROUTER_PATH.EMPTY,
                component: Home,
            },
            {
                path: ROUTER_PATH.USER_MANAGER,
                component: UserManage,
                children: [
                    {
                        path: ROUTER_PATH.EMPTY,
                        component: ListUserManage,
                    },
                    {
                        path: ROUTER_PATH.ADD,
                        component: AddUserManage,
                    },
                    {
                        path: ROUTER_PATH.EDIT_ID,
                        component: EditUserManage,
                    },
                ],
            },
        ]
    },
]
