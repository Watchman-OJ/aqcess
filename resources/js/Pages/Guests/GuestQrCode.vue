<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4 rounded shadow-md bg-gray-100 dark:bg-gray-900">
            <h1 class="text-3xl font-bold mb-4">QR Code Generated</h1>
            <p>{{ guest.name }} has been added to the Guest List for the event.</p>
            <div class="my-4">
                <img :src="qrCodeData" id="guestQrCode" alt="QR Code" @error="handleImageError">
            </div>
            <div class="flex justify-between">
                <button @click="downloadQrCode" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">Download</button>
                <button @click="shareQrCode" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-700">Share with Guest</button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted } from 'vue';

export default {
    components: {
        AuthenticatedLayout
    },
    props: {
        guest: {
            type: Object,
            required: true
        },
        qrCode: {
            type: String,
            required: true
        }
    },
    setup(props) {
        const guest = ref(props.guest);
        const qrCodeData = ref(props.qrCode);

        onMounted(() => { 
            console.log('QR Code Data Length:', qrCodeData.value.length);
            console.log('QR Code Data:', qrCodeData.value); 
        }); 

        function downloadQrCode() {
            const qrCodeUrl = document.getElementById('guestQrCode').src;
            const link = document.createElement('a');
            link.href = qrCodeUrl;
            link.download = 'guestQrCode.png';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function shareQrCode() {
            const qrCodeUrl = document.getElementById('guestQrCode').src;

            if (navigator.share) {
                navigator.share({
                    title: `${guest.value.name}'s QR Code`,
                    text: 'Here is your QR Code for the event.',
                    url: qrCodeUrl,
                }).then(() => {
                    console.log('QR Code shared successfully');
                }).catch((error) => {
                    console.error('Error sharing QR Code:', error);
                });
            } else {
                alert('Sharing not supported on this device.');
            }
        }

        function handleImageError() {
            console.error('Failed to load QR Code image');
        }

        return { qrCodeData, downloadQrCode, shareQrCode, handleImageError };
    }
};
</script>

<style scoped>
/* Add any component-specific styling here */
</style>
