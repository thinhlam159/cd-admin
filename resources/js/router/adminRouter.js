import { auth, guest } from "./middlewares";
import { ROUTER_PATH, TYPE_SCREEN, TYPE_USER } from "@/const";
import Home from "@/views/Home";
import UserManage from "@/views/UserManage";
import ListUserManage from "@/views/ListUserManage";
import AddUserManage from "@/views/AddUserManage";
import EditUserManage from "@/views/EditUserManage";
import Login from "@/views/Login";
import AdminLayout from "@/components/Layouts/AdminLayout";
import CustomerManage from "@/views/CustomerManage";
import ListCustomer from "@/views/CustomerManage/ListCustomer";
import AddCustomer from "@/views/CustomerManage/AddCustomer";
import EditCustomer from "@/views/CustomerManage/EditCustomer";
import ProductManage from "@/views/ProductManage";
import ListProduct from "@/views/ProductManage/ListProduct";
import AddProduct from "@/views/ProductManage/AddProduct";
import CategoryManage from "@/views/CategoryManage";
import ListCategory from "@/views/CategoryManage/ListCategory/ListCategory";
import AddProductAttributeValue from "@/views/ProductManage/AddProductAttributeValue";
import OrderManage from "@/views/OrderManage";
import ListOrder from "@/views/OrderManage/ListOrder";
import CreateOrder from "@/views/OrderManage/CreateOrder";
import DetailOrder from "@/views/OrderManage/DetailOrder";
import ImportGoodManage from "@/views/ImportGoodManage";
import ListImportGood from "@/views/ImportGoodManage/ListImportGood";
import CreateImportGood from "@/views/ImportGoodManage/CreateOrder";
import DetailImportGood from "@/views/ImportGoodManage/DetailOrder";
import DebtManage from "@/views/DebtManage";
import ListDebt from "@/views/DebtManage/ListDebt";
import CreateDebt from "@/views/DebtManage/CreateDebt";

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
            middleware: [(context) => auth(context, TYPE_USER.ADMIN)],
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
            {
                path: ROUTER_PATH.CUSTOMER_MANAGE,
                component: CustomerManage,
                children: [
                    {
                        path: ROUTER_PATH.EMPTY,
                        component: ListCustomer,
                    },
                    {
                        path: ROUTER_PATH.ADD,
                        component: AddCustomer,
                    },
                    {
                        path: ROUTER_PATH.EDIT_ID,
                        component: EditCustomer,
                    },
                ],
            },
            {
                path: ROUTER_PATH.PRODUCT_MANAGE,
                component: ProductManage,
                children: [
                    {
                        path: ROUTER_PATH.EMPTY,
                        component: ListProduct,
                    },
                    {
                        path: ROUTER_PATH.ADD,
                        component: AddProduct,
                    },
                    {
                        path: ROUTER_PATH.EDIT_ID,
                        // component: EditProduct,
                    },
                    {
                        path: ROUTER_PATH.ADD_PRODUCT_ATTRIBUTE_VALUE_ID,
                        component: AddProductAttributeValue,
                    },
                ],
            },
            {
                path: ROUTER_PATH.CATEGORY_MANAGE,
                component: CategoryManage,
                children: [
                    {
                        path: ROUTER_PATH.EMPTY,
                        component: ListCategory,
                    },
                ]
            },
            {
                path: ROUTER_PATH.ORDER_MANAGE,
                component: OrderManage,
                children: [
                    {
                        path: ROUTER_PATH.EMPTY,
                        component: ListOrder,
                    },
                    {
                        path: ROUTER_PATH.ADD,
                        component: CreateOrder,
                    },
                    {
                      path: ROUTER_PATH.DETAIL_ID,
                      component: DetailOrder,
                    },
                ]
            },
          {
            path: ROUTER_PATH.IMPORT_GOOD_MANAGE,
            component: ImportGoodManage,
            children: [
              {
                path: ROUTER_PATH.EMPTY,
                component: ListImportGood,
              },
              {
                path: ROUTER_PATH.ADD,
                component: CreateImportGood,
              },
              {
                path: ROUTER_PATH.DETAIL_ID,
                component: DetailImportGood,
              },
            ]
          },
          {
            path: ROUTER_PATH.DEBT_MANAGE,
            component: DebtManage,
            children: [
              {
                path: ROUTER_PATH.EMPTY,
                component: ListDebt,
              },
              {
                path: ROUTER_PATH.ADD,
                component: CreateDebt,
              },
              // {
              //   path: ROUTER_PATH.DETAIL_ID,
              //   component: DetailImportGood,
              // },
            ]
          },
        ]
    },
]
