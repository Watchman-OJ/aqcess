import { createRouter, createWebHistory } from 'vue-router'; 
import Index from './Pages/Events/Index.vue'; 
import Create from './Pages/Events/Create.vue';
import FormHandler from './Pages/Guests/FormHandler.vue';
import CustomizeForm from './Pages/Guests/CustomizeForm.vue'; 
import ReviewForm from './Pages/Guests/ReviewForm.vue';
import SelectForm from './Pages/Guests/SelectForm.vue'; 
import ViewForm from './Pages/Guests/ViewForm.vue'; 

const routes = [
    { path: '/events', name: 'events.index', component: Index },
    { path: '/events/create', name: 'events.create', component: Create },
    { path: '/events/:eventId/form-handler', name: 'FormHandler', component: FormHandler, props: true },
    { path: '/events/:eventId/customize-form', name: 'CustomizeForm', component: CustomizeForm },
    { path: '/events/:eventId/review-form', name: 'ReviewForm', component: ReviewForm, props: true },
    { path: '/events/:eventId/select-form', name: 'SelectForm', component: SelectForm, props: true },
    { path: '/events/:eventId/form/:formId', name: 'ViewForm', component: ViewForm, props: true }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
