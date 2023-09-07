<script setup>
import { Head, Link } from "@inertiajs/vue3";
import Modal from "../Components/Modal.vue";
import ChurchIcon from "vue-material-design-icons/ChurchOutline.vue";
import PersonIcon from "vue-material-design-icons/AccountOutline.vue";
import GroupIcon from "vue-material-design-icons/AccountGroupOutline.vue";
import DonnationIcon from "vue-material-design-icons/HandCoinOutline.vue";
import { ref } from "vue";

let showAdhesionModal = ref(false);
</script>

<template>
    <Head title="Bienvenue" />

    <div class="px-20 grid grid-cols-2 pt-10">
        <div class="col-span-2 capitalize text-center">
            {{
                new Date().toLocaleDateString("fr-FR", {
                    weekday: "long",
                    year: "numeric",
                    month: "long",
                    day: "numeric",
                })
            }}
        </div>
        <div>
            <div
                class="text-blue-700 underline cursor-pointer"
                @click="showAdhesionModal = true"
            >
                Demander une adhésion
            </div>
        </div>

        <Modal :show="showAdhesionModal" @close="showAdhesionModal = false">
            <div class="px-10 py-5">
                <div class="text-lg">Demander une adhésion</div>
                <div class="flex gap-3 pt-5">
                    <Link
                        :href="route('register')"
                        class="font-medium rounded-md shadow-sm w-full border-2 border-blue-500 flex flex-col items-center text-sm p-4 text-center justify-between gap-4 cursor-pointer"
                    >
                        En qualité de Paroisse
                        <ChurchIcon :size="35" fillColor="#3b82f6" />
                    </Link>
                    <div
                        class="font-medium rounded-md shadow-sm w-full border-2 border-blue-500 flex flex-col items-center text-sm p-4 text-center justify-between gap-4 cursor-pointer"
                    >
                        En qualité de Paroissien
                        <PersonIcon :size="35" fillColor="#3b82f6" />
                    </div>
                    <div
                        class="font-medium rounded-md shadow-sm w-full border-2 border-blue-500 flex flex-col items-center text-sm p-4 text-center justify-between gap-4 cursor-pointer"
                    >
                        En qualité d'institution sociale
                        <DonnationIcon :size="35" fillColor="#3b82f6" />
                    </div>
                    <div
                        class="font-medium rounded-md shadow-sm w-full border-2 border-blue-500 flex flex-col items-center text-sm p-4 text-center justify-between gap-4 cursor-pointer"
                    >
                        En qualité de groupe de prière
                        <GroupIcon :size="35" fillColor="#3b82f6" />
                    </div>
                </div>
            </div>
        </Modal>
    </div>
</template>

<style></style>
