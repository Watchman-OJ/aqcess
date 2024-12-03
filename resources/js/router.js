import { createRouter, createWebHistory } from 'vue-router'; 
import Index from './Pages/Events/Index.vue'; 
import Create from './Pages/Events/Create.vue'; 

const routes = [
    { path: '/events', name: 'events.index', component: Index },
    { path: '/events/create', name: 'events.create', component: Create },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;