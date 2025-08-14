<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type User } from '@/types';

interface Props {
    status?: string;
    user?: {
        name: string;
        email: string;
        signature?: string;
    };
}

const props = defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: '/settings/profile',
    },
];

const page = usePage();
const authUser = page.props.auth.user as User;
const user = props.user || authUser;

const form = useForm({
    name: user.name,
    email: user.email,
    signature: '',
});

const signatureCanvas = ref<HTMLCanvasElement | null>(null);
const isDrawing = ref(false);
const signatureData = ref('');
const isSignatureSaved = ref(false);

// Signature drawing functions
const startDrawing = (e: MouseEvent | TouchEvent) => {
    isDrawing.value = true;
    draw(e);
};

const draw = (e: MouseEvent | TouchEvent) => {
    if (!isDrawing.value || !signatureCanvas.value) return;
    
    e.preventDefault();
    const canvas = signatureCanvas.value;
    const ctx = canvas.getContext('2d');
    if (!ctx) return;
    
    const rect = canvas.getBoundingClientRect();
    let clientX, clientY;
    
    if (e instanceof MouseEvent) {
        clientX = e.clientX;
        clientY = e.clientY;
    } else {
        clientX = e.touches[0].clientX;
        clientY = e.touches[0].clientY;
    }
    
    const x = clientX - rect.left;
    const y = clientY - rect.top;
    
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.strokeStyle = '#000';
    
    ctx.lineTo(x, y);
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(x, y);
};

const stopDrawing = () => {
    isDrawing.value = false;
    if (signatureCanvas.value) {
        const ctx = signatureCanvas.value.getContext('2d');
        if (ctx) {
            ctx.beginPath();
        }
    }
};

const clearSignature = () => {
    if (!signatureCanvas.value) return;
    const ctx = signatureCanvas.value.getContext('2d');
    if (ctx) {
        ctx.clearRect(0, 0, signatureCanvas.value.width, signatureCanvas.value.height);
        signatureData.value = '';
        isSignatureSaved.value = false;
    }
};

const saveSignature = () => {
    if (!signatureCanvas.value) return;
    signatureData.value = signatureCanvas.value.toDataURL('image/png');
    form.signature = signatureData.value;
    isSignatureSaved.value = true;
    
    // Reset to false after 3 seconds
    setTimeout(() => {
        isSignatureSaved.value = false;
    }, 3000);
};

const loadExistingSignature = () => {
    if (user.signature) {
        // Load existing signature if available
        signatureData.value = user.signature;
    }
};

// Load existing signature on mount
onMounted(() => {
    loadExistingSignature();
});

const submit = () => {
    // Only include signature in form if user actually drew and saved a new one
    if (signatureData.value) {
        form.signature = signatureData.value;
    } else {
        // If no new signature, don't send signature field at all
        form.signature = '';
    }
    
    form.patch(route('profile.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Profile settings" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Profile information" description="Update your name and email address" />

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input id="name" class="mt-1 block w-full" v-model="form.name" required autocomplete="name" placeholder="Full name" />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Email address</Label>
                        <Input
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autocomplete="username"
                            placeholder="Email address"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <!-- Signature Section -->
                    <div class="grid gap-2">
                        <Label>Digital Signature</Label>
                        
                        <!-- Existing Signature Display -->
                        <div v-if="form.signature || user.signature" class="mb-4 p-3 bg-gray-50 rounded-lg border">
                            <p class="text-sm font-medium text-gray-700 mb-2">Current Signature:</p>
                            <img 
                                :src="form.signature || `/storage/${user.signature}?t=${Date.now()}`" 
                                alt="Current Signature" 
                                class="h-16 w-auto border border-gray-300 rounded" 
                            />
                        </div>
                        
                        <div class="border border-gray-300 rounded-lg p-4 bg-white">
                            <canvas
                                ref="signatureCanvas"
                                width="400"
                                height="200"
                                class="border border-gray-300 rounded cursor-crosshair"
                                @mousedown="startDrawing"
                                @mousemove="draw"
                                @mouseup="stopDrawing"
                                @mouseleave="stopDrawing"
                                @touchstart="startDrawing"
                                @touchmove="draw"
                                @touchend="stopDrawing"
                            ></canvas>
                            
                            <div class="mt-3 flex gap-2">
                                <Button type="button" @click="clearSignature" variant="outline" size="sm">
                                    Clear
                                </Button>
                                <Button type="button" @click="saveSignature" variant="outline" size="sm" :disabled="isSignatureSaved">
                                    {{ isSignatureSaved ? 'Saved' : 'Save Signature' }}
                                </Button>
                            </div>
                            
                            <p class="text-sm text-gray-600 mt-2">
                                Draw your signature above. Click "Save Signature" to include it in your profile.
                            </p>
                        </div>
                        <InputError class="mt-2" :message="form.errors.signature" />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing">Save</Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Saved.</p>
                        </Transition>
                    </div>
                </form>

                <div class="border-t border-neutral-200 pt-6 dark:border-neutral-700">
                    <DeleteUser />
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
