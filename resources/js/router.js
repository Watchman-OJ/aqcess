import { createRouter, createWebHistory } from 'vue-router'; 
import Index from './Pages/Events/Index.vue'; 
import Create from './Pages/Events/Create.vue';
import CustomizeForm from './Pages/Guests/CustomizeForm.vue'; 
import NewForm from './Pages/Guests/NewForm.vue';

const routes = [
    { path: '/events', name: 'events.index', component: Index },
    { path: '/events/create', name: 'events.create', component: Create },
    { path: '/events/:eventId/customize-form', name: 'CustomizeForm', component: CustomizeForm },
    { path: '/events/:eventId/new-form', name: 'NewForm', component: NewForm, props: true }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;