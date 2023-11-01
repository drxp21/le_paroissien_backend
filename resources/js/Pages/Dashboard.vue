<script setup>
import { Link,Head } from '@inertiajs/vue3';

import PrimaryButton from '@/Components/PrimaryButton.vue';
import AppLayout from '@/Layouts/AppLayout.vue';



</script>

<template>
    <AppLayout title="Vie d'église">
        <Head title="Tableau de bord" />
       <div class="grid grid-cols-2 justify-center items-center">
        <div class="bg-white h-full flex flex-col gap-4 p-20">
            <Link :href="route('annonces.index')">
                <PrimaryButton> Gestion des annonces </PrimaryButton>
            </Link>
            <Link :href="route('demandemesses.index')">
                <PrimaryButton> Gestion des demandes de messes </PrimaryButton>
            </Link>
            <Link :href="route('evenements.index')">
                <PrimaryButton> Gestion des évènements </PrimaryButton>
            </Link>
        </div>
        <div class="px-20">
            <img :src="`/images/church-clipart.jpg`" />
        </div>
       </div>

    </AppLayout>
</template>
