import {auth, guest} from "./middlewares";
import {ROUTER_PATH, TYPE_USER} from "@/const";
import UserManage from "@/views/UserManage";
import ListUser from "@/views/UserManage/ListUser";
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
import CreateImportGood from "@/views/ImportGoodManage/CreateImportGood";
import DetailImportGood from "@/views/ImportGoodManage/DetailImportGood";
import DebtManage from "@/views/DebtManage";
import ListDebt from "@/views/DebtManage/ListDebt";
import CreateDebt from "@/views/DebtManage/CreateDebt";
import CreatePayment from "@/views/DebtManage/CreateDebt/CreatePayment.vue";
import ListCustomerDebt from "@/views/DebtManage/ListDebt/ListCustomerDebt.vue";
import Dashboard from "@/views/Dashboard";
import Overview from "@/views/Dashboard/Overview/Overview.vue";
import AddCategory from "@/views/CategoryManage/AddCategory/AddCategory.vue";
import EditCategory from "@/views/CategoryManage/EditCategory/EditCategory.vue";
import EditProductPrice from "@/views/ProductManage/EditProduct/EditProductPrice.vue";
import ExportGoodManage from "@/views/ExportGoodManage/ExportGoodManage.vue";
import ListExportGood from "@/views/ExportGoodManage/ListExportGood";
import CreateExportGood from "@/views/ExportGoodManage/CreateExport/CreateExportGood.vue";
import AddUser from "@/views/UserManage/AddUser/AddUser.vue";
import EditUser from "@/views/UserManage/EditUser/EditUser.vue";

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
        component: Dashboard,
        children: [
          {
            path: ROUTER_PATH.EMPTY,
            component: Overview,
          },
        ]
      },
      {
        path: ROUTER_PATH.DASHBOARD,
        component: Dashboard,
        children: [
          {
            path: ROUTER_PATH.EMPTY,
            component: Overview,
          },
        ]
      },
      {
        path: ROUTER_PATH.USER_MANAGER,
        component: UserManage,
        children: [
          {
            path: ROUTER_PATH.EMPTY,
            component: ListUser,
          },
          {
            path: ROUTER_PATH.ADD,
            component: AddUser,
          },
          {
            path: ROUTER_PATH.EDIT_ID,
            component: EditUser,
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
          {
            path: ROUTER_PATH.PRICE_MANAGE,
            component: EditProductPrice,
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
          {
            path: ROUTER_PATH.ADD,
            component: AddCategory,
          },
          {
            path: ROUTER_PATH.EDIT_ID,
            component: EditCategory,
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
            path: ROUTER_PATH.ADD + '/:id',
            component: CreateDebt,
            name: 'CreateDebt'
          },
          {
            path: ROUTER_PATH.PAYMENT + '/' + ROUTER_PATH.ADD + '/:id',
            component: CreatePayment,
            name: 'CreatePayment'
          },
          {
            path: ROUTER_PATH.LIST_CUSTOMER_DEBT_ID,
            component: ListCustomerDebt,
          },
        ]
      },
      {
        path: ROUTER_PATH.EXPORT_GOOD_MANAGE,
        component: ExportGoodManage,
        children: [
          {
            path: ROUTER_PATH.EMPTY,
            component: ListExportGood,
          },
          {
            path: ROUTER_PATH.ADD,
            component: CreateExportGood,
          },
        ]
      },
    ]
  },
]
