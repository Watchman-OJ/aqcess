import { createRouter, createWebHistory } from 'vue-router';
import Index from './Pages/Events/Index.vue';
import Create from './Pages/Events/Create.vue';
import FormHandler from './Pages/Guests/FormHandler.vue';
import CustomizeForm from './Pages/Guests/CustomizeForm.vue';
import ReviewForm from './Pages/Guests/ReviewForm.vue';
import SelectForm from './Pages/Guests/SelectForm.vue';
import ViewForm from './Pages/Guests/ViewForm.vue';
import Login from './Pages/Auth/Login.vue'; 
import AuthenticatedLayout from './Layouts/AuthenticatedLayout.vue'; 

const routes = [
    { path: '/', redirect: '/events' }, 
    { path: '/login', name: 'Login', component: Login, meta: { guest: true } },
    { path: '/authenticated', name: 'AuthenticatedLayout', component: AuthenticatedLayout, meta: { requiresAuth: true }, children: [
        { path: 'events', name: 'events.index', component: Index },
        { path: 'events/create', name: 'events.create', component: Create },
        { path: 'events/:eventId/form-handler', name: 'FormHandler', component: FormHandler, props: true },
        { path: 'events/:eventId/customize-form', name: 'CustomizeForm', component: CustomizeForm },
        { path: 'events/:eventId/review-form', name: 'ReviewForm', component: ReviewForm, props: true },
        { path: 'events/:eventId/select-form', name: 'SelectForm', component: SelectForm, props: true },
        { path: 'events/:eventId/form/:formId', name: 'ViewForm', component: ViewForm, props: true }
    ]}
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const isLoggedIn = !!localStorage.getItem('authToken'); 

    if (to.matched.some(record => record.meta.requiresAuth) && !isLoggedIn) {
        next({ name: 'Login' });
    } else if (to.matched.some(record => record.meta.guest) && isLoggedIn) {
        next({ name: 'AuthenticatedLayout' });
    } else {
        next();
    }
});

export default router;

