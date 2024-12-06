import { createRouter, createWebHistory } from 'vue-router'; 
import Index from './Pages/Events/Index.vue'; 
import Create from './Pages/Events/Create.vue';
import GuestList from './Pages/Guests/GuestList.vue'; 
import AddGuestMethod from './Pages/Guests/AddGuestMethod.vue';
import CustomizeForm from './Pages/Guests/CustomizeForm.vue';
import GuestFormFields from './Pages/Guests/GuestFormFields.vue';
import ReviewDetails from './Pages/Guests/ReviewDetails.vue';
import GuestQrCode from './Pages/Guests/GuestQrCode.vue'; 
import InputEmails from './Pages/Guests/InputEmails.vue';

const routes = [
    { path: '/events', name: 'events.index', component: Index },
    { path: '/events/create', name: 'events.create', component: Create },
    { path: '/events/:event/guest-list', name: 'events.guestList', component: GuestList, props: true }, 
    { path: '/events/:event/add-guest', name: 'addGuest', component: AddGuestMethod, props: true }, 
    { path: '/events/:event/customize-form', name: 'customizeForm', component: CustomizeForm, props: true }, 
    { path: '/events/:event/guest-form-fields', name: 'guestFormFields', component: GuestFormFields, props: true }, 
    { path: '/events/:event/review-details', name: 'reviewDetails', component: ReviewDetails, props: true }, 
    { path: '/events/:event/guest/guest-qrcode', name: 'guestQrCode', component: GuestQrCode, props: true }, 
    { path: '/events/:event/input-emails', name: 'inputEmails', component: InputEmails, props: true },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;