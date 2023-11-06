<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import Modal from "../Components/Modal.vue";
import Banner from "@/Components/Banner.vue";
import ChurchIcon from "vue-material-design-icons/ChurchOutline.vue";
import PersonIcon from "vue-material-design-icons/AccountOutline.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import GroupIcon from "vue-material-design-icons/AccountGroupOutline.vue";
import DonnationIcon from "vue-material-design-icons/HandCoinOutline.vue";
import { ref, onMounted } from "vue";
import axios from "axios";

let showQsn = ref(false);
let showNv = ref(false);
let showCa = ref(false);
let showAdhesionModal = ref(false);

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const props = defineProps({
    edj: "",
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        remember: form.remember ? "on" : "",
    })).post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
onMounted(() => {});
</script>

<template>
    <Head title="Bienvenue" />
    <Banner />
    <div class="px-20 grid grid-cols-2 pt-10">
        <div class="col-span-2 text-3xl font-bold capitalize text-center">
            {{
                new Date().toLocaleDateString("fr-FR", {
                    weekday: "long",
                    year: "numeric",
                    month: "long",
                    day: "numeric",
                })
            }}
        </div>
        <div class="px-5 col-span-2 md:col-span-1">
            <div>
                <div
                    role="button"
                    class="flex items-center bg-white px-3 py-4 justify-between rounded-md mt-5 shadow-lg"
                    @click="showQsn = !showQsn"
                >
                    Qui sommes nous ?
                    <svg
                        class="ml-2 -mr-0.5 h-4 w-4 transition-all"
                        :class="showQsn ? 'rotate-180' : ''"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
                <transition
                    enter-active-class="duration-300 ease-out"
                    enter-from-class="transform opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="duration-100 ease-in"
                    leave-from-class="opacity-100"
                    leave-to-class="transform opacity-0"
                >
                    <div v-if="showQsn">
                        <div
                            class="flex flex-col items-center gap-3 mt-2 whitespace-pre-wrap"
                        >
                            <div class="w-full">
                                <div
                                    class="mt-2 py-3 px-3 rounded-lg flex gap-4 justify-between items-center"
                                >
                                    * Texte Qui sommes nous *
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>
                <div
                    role="button"
                    class="flex items-center bg-white px-3 py-4 justify-between rounded-md mt-5 shadow-lg"
                    @click="showNv = !showNv"
                >
                    Nos valeurs
                    <svg
                        class="ml-2 -mr-0.5 h-4 w-4 transition-all"
                        :class="showNv ? 'rotate-180' : ''"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
                <transition
                    enter-active-class="duration-300 ease-out"
                    enter-from-class="transform opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="duration-100 ease-in"
                    leave-from-class="opacity-100"
                    leave-to-class="transform opacity-0"
                >
                    <div v-if="showNv">
                        <div
                            class="flex flex-col items-center gap-3 mt-2 whitespace-pre-wrap"
                        >
                            <div class="w-full">
                                <div
                                    class="mt-2 py-3 px-3 rounded-lg flex gap-4 justify-between items-center"
                                >
                                    * Texte Nos valeurs *
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>
                <div
                    role="button"
                    class="flex items-center bg-white px-3 py-4 justify-between rounded-md mt-5 shadow-lg"
                    @click="showCa = !showCa"
                >
                    Charte d'adhésion
                    <svg
                        class="ml-2 -mr-0.5 h-4 w-4 transition-all"
                        :class="showCa ? 'rotate-180' : ''"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
                <transition
                    enter-active-class="duration-300 ease-out"
                    enter-from-class="transform opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="duration-100 ease-in"
                    leave-from-class="opacity-100"
                    leave-to-class="transform opacity-0"
                >
                    <div v-if="showCa">
                        <div
                            class="flex flex-col items-center gap-3 mt-2 whitespace-pre-wrap"
                        >
                            <div class="w-full">
                                <div
                                    class="mt-2 py-3 px-3 rounded-lg flex gap-4 justify-between items-center"
                                >
                                    * Texte Charte d'adhésion*
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
            <div class=" p-3 text-sm">
                Verset du jour
                <p class="text-md font-semibold">{{ edj.titre }}</p>
                <div class="text-end text-xs">
                    {{ edj.ref }}
                </div>
            </div>
        </div>
        <div
            class="flex flex-col align-items-center col-span-2 md:col-span-1 mt-5"
        >
            <label
                class="w-full text-center block font-medium text-sm text-gray-700"
                >Espace membre</label
            >
            <img class="img-fluid col-3" />

            <div
                class="w-full text-center block font-medium text-sm text-gray-700"
            >
                Connectez vous avec vos identifiants
            </div>
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Mot de passe" />
                    <TextInput
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                        required
                        autocomplete="current-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="block mt-4">
                    <label class="flex items-center">
                        <Checkbox
                            v-model:checked="form.remember"
                            name="remember"
                        />
                        <span class="ml-2 text-sm text-gray-600"
                            >Se souvenir de moi</span
                        >
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton
                        class="ml-4"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Se connecter
                    </PrimaryButton>
                </div>
            </form>
            <div class="mt-3 flex text-center justify-center gap-3">
                <div>Vous n'avez pas de compte ?</div>
                <div
                    @click="showAdhesionModal = true"
                    class="text-blue-700 underline cursor-pointer"
                >
                    Demander une adhésion
                </div>
            </div>
        </div>
        <div class="col-span-2">
            <h4>Nous contacter ?</h4>
            <ul class="w-1/2">
                <li class="flex justify-between list-inline-item">
                    <span>Téléphone :</span> <span>(+221) 33 *** ** **</span>
                </li>
                <li class="flex justify-between">
                    <span>Adresse mail :</span>
                    <span class="text-primary text-decoration-underline"
                        >support@leparoissien.com</span
                    >
                </li>
            </ul>
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
