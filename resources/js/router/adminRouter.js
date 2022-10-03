import { auth, guest } from "./middlewares";
import { ROUTER_PATH, TYPE_SCREEN, TYPE_USER } from "@/const";

const Login = () => import("@/views/Login");
const AdminLayout = () => import("@/components/Layouts/AdminLayout");

const About = { template: '<div>About</div>' }

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
    },
]
