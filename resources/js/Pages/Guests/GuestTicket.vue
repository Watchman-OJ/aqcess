<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4 rounded shadow-md bg-gray-200 dark:bg-gray-900">
            <div class="ticket">
                <div class="ticket-header flex items-center justify-center">
                    <!-- <img :src="appLogo" alt="App Logo" class="logo"> -->
                    <h2 class="title"> {{ event.title }} </h2>
                </div>

                <div v-if="localQrCode" class="qr-code-container my-4">
                    <img :src="localQrCode" alt="QR Code" class="qr-code">
                </div>

                <h1 class="text-3xl font-bold mb-4">{{ guest.form_data.name }}</h1>

                <div class="ticket-body">
                    <h3 class="mb-1 text-2xl">Location</h3>
                    <p class="mb-4"> {{ event.location }} </p>
                    <h3 class="mb-1 text-2xl">Date</h3>
                    <p class="mb-4"> {{ event.startDate }} </p>
                    <h3 class="mb-1 text-2xl">Time</h3>
                    <p> {{ event.startTime }} </p>
                </div>
            </div>

            <div class="flex justify-between mt-4 p-4">
                <button @click="shareQrCode" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-700">Share with Guest</button>
                <button @click="downloadQrCode" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">Download</button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    components: {
        AuthenticatedLayout
    },
    props: {
        event: {
            type: Object,
            required: true
        },
        guest: {
            type: Object,
            required: true
        },
        // appLogo: {
        //     type: String,
        //     required: true
        // },
        qrCode: {
            type: String,
            default: null
        }
    },
    data() {
        return {
            localQrCode: this.qrCode,
        };
    },
    mounted() {
        console.log("Qr Code received:", this.qrCode);
    },
    setup(props) {
        function downloadQrCode() {
            const qrCodeUrl = document.getElementById('qrCode').src;
            const link = document.createElement('a');
            link.href = qrCodeUrl;
            link.download = 'guestQrCode.png';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function shareQrCode() {
            const qrCodeUrl = document.getElementById('qrCode').src;

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

        return { downloadQrCode, shareQrCode, handleImageError };
    }

    
};
</script>

<style scoped>
.ticket {
    color: #424242;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #fff;
    padding: 16px;
    max-width: 400px;
    margin: 0 auto;
}

.ticket-header {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 0px;
}

.logo {
    width: 50px;
    height: 50px;
}

.title {
    font-size: 30px;
    font-weight: bold;
}

.ticket-body {
    margin-top: 10px;
}

.ticket-body p {
    color: black;
    font-size: 17px;
    font-weight: bold;
}

.qr-code-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 0;
}

.qr-code {
    width: 200px;
    height: 200px;
}
</style>
