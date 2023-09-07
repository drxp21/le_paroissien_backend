<script setup>
import { Link } from '@inertiajs/vue3';

import PrimaryButton from '@/Components/PrimaryButton.vue';
import AppLayout from '@/Layouts/AppLayout.vue';



</script>

<template>
    <AppLayout title="Vie d'église">
        <div class="bg-white h-full">
            <Link :href="route('annonces.index')">
                <PrimaryButton> Gestion des annonces </PrimaryButton>
            </Link>
            <Link :href="route('dispopretres.index')">
                <PrimaryButton> Gestion disponibilité </PrimaryButton>
            </Link>
            <Link :href="route('demandemesses.index')">
                <PrimaryButton> Gestion des demandes de messes </PrimaryButton>
            </Link>
            <Link :href="route('evenements.index')">
                <PrimaryButton> Gestion des évènements </PrimaryButton>
            </Link>
        </div>

    </AppLayout>
</template>
