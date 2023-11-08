<script setup>
import AppLayout from "../../Layouts/AppLayout.vue";
import Banner from "@/Components/Banner.vue";
import { ref, onMounted } from "vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/Modal.vue";
import { useForm, Head } from "@inertiajs/vue3";

let visibles = ref([]);
let showConfModal = ref(false);
let showRejModal = ref(false);
let modalItem = ref();
const props = defineProps({
    demandeurs: "",
});
const confForm = useForm({
    id: 1,
    verified: 0,
    statut: "",
    denomination: "",
    diocese: "",
    doyenne: "",
    adresse: "",
    telephonefixe: " ",
    telephonemobile: "",
    emailinstitution: "",
    emaildemandeur: "",
    slogan: "",
    nomresp: "",
    prenomresp: "",
    titreresp: "",
    emailresp: "",
    raison:""
});
onMounted(() => {
    for (let i in props.demandeurs) {
        visibles.value.push(false);
    }
});
</script>
<template>
    <AppLayout>
        <Head title="Tableau de bord" />
        <div class="px-20 mt-10">
            <div>Demandes d'adhésion</div>
            <div
                v-if="demandeurs.length > 0"
                v-for="(demandeur, index) in demandeurs"
                :key="demandeur"
                class="w-full overflow-auto"
            >
                <div
                    role="button"
                    class="flex items-center bg-gray-300 px-3 py-2 justify-between rounded-md mt-5"
                    @click="visibles[index] = !visibles[index]"
                >
                    {{ demandeur.denomination }}
                    <svg
                        class="ml-2 -mr-0.5 h-4 w-4 transition-all"
                        :class="visibles[index] ? 'rotate-180' : ''"
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
                    <div v-if="visibles[index]">
                        <div
                            class="flex items-center gap-3 mt-2 whitespace-pre-wrap"
                        >
                            <table class="text-xs font-light table-auto">
                                <thead class="border-b font-medium">
                                    <tr>
                                        <th
                                            scope="col"
                                            class="px-2 py-4 capitalize"
                                        >
                                            Statut
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-2 py-4 capitalize"
                                        >
                                            Dénomination
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-2 py-4 capitalize"
                                            v-if="demandeur.diocese"
                                        >
                                            Archidiocèse/Diocèse
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-2 py-4 capitalize"
                                            v-if="demandeur.doyenne"
                                        >
                                            Doyenné
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-2 py-4 capitalize"
                                        >
                                            Adresse
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-2 py-4 capitalize"
                                        >
                                            Téléphone Fixe
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-2 py-4 capitalize"
                                        >
                                            Prénom et nom du responsable
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-2 py-4 capitalize"
                                        >
                                            Titre du responsable
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-2 py-4 capitalize"
                                        >
                                            Email du responsable
                                        </th>

                                        <th
                                            scope="col"
                                            class="px-2 py-4 capitalize"
                                        >
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="py-3 font-medium text-start">
                                        <td
                                            class="px-2 py-4 capitalize text-center"
                                        >
                                            {{ demandeur.statut }}
                                        </td>
                                        <td
                                            class="px-2 py-4 capitalize text-center"
                                        >
                                            {{ demandeur.denomination }}
                                        </td>
                                        <td
                                            v-if="demandeur.diocese"
                                            class="px-2 py-4 capitalize"
                                        >
                                            {{ demandeur.diocese }}
                                        </td>
                                        <td
                                            v-if="demandeur.doyenne"
                                            class="px-2 py-4 capitalize"
                                        >
                                            {{ demandeur.doyenne }}
                                        </td>
                                        <td
                                            class="px-2 py-4 capitalize text-center"
                                        >
                                            {{ demandeur.adresse }}
                                        </td>
                                        <td
                                            class="px-2 py-4 capitalize text-center"
                                        >
                                            {{ demandeur.telephonefixe ?? '-----' }}
                                        </td>
                                        <td
                                            class="px-2 py-4 capitalize text-center"
                                        >
                                            {{ demandeur.prenomresp }}
                                            {{ demandeur.nomresp }}
                                        </td>
                                        <td
                                            class="px-2 py-4 capitalize text-center"
                                        >
                                            {{ demandeur.titreresp }}
                                        </td>
                                        <td
                                            class="px-2 py-4 capitalize text-center"
                                        >
                                            {{ demandeur.emailresp }}
                                        </td>
                                        <td
                                            class="px-2 py-4 capitalize text-center flex gap-2"
                                        >
                                            <button
                                                class="bg-blue-700 py-2 px-4 self-start rounded-lg text-white flex-shrink-0"
                                                @click="
                                                    () => (
                                                        (showConfModal = true),
                                                        (modalItem = demandeur)
                                                    )
                                                "
                                            >
                                                Valider
                                            </button>
                                            <button
                                                class="bg-red-700 py-2 px-4 self-start rounded-lg text-white flex-shrink-0"
                                                @click="
                                                    () => (
                                                        (showRejModal = true),
                                                        (modalItem = demandeur)
                                                    )
                                                "
                                            >
                                                Rejeter
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </transition>
            </div>
            <div v-else class="p-5 text-slate-600 text-lg font-xl">
                Aucune demande d'adhésion actuellement
            </div>
        </div>
    </AppLayout>
    <Modal :show="showConfModal" @close="showConfModal = false">
        <form
            class="p-10"
            @submit.prevent="
                () => (
                    Object.assign(confForm, modalItem),
                    confForm.post(route('valider')),
                    (showConfModal = false)
                )
            "
        >
            <span class="text-2xl font-medium"
                >Valider la demande d'adhésion</span
            >
            <div class="mt-4">
                Vous allez valider la demande d'adhésion de
                <span class="text-blue-500">{{ modalItem.denomination }}</span>
            </div>

            <div class="mt-4">
                <button
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Confirmer
                    <div
                        v-if="confForm.processing"
                        style="border-top-color: transparent"
                        class="ml-2 w-3 h-3 border-2 border-white border-solid rounded-full animate-spin bg-blue-600"
                    ></div>
                </button>
            </div>
        </form>
    </Modal>
    <Modal :show="showRejModal" @close="showRejModal = false">
        <form
            class="p-10"
            @submit.prevent="
                () => (
                    Object.assign(confForm, modalItem),
                    confForm.post(route('rejeter')),
                    (showRejModal = false)
                )
            "
        >
            <span class="text-2xl font-medium"
                >Rejeter la demande d'adhésion</span
            >
            <div class="mt-4">
                Vous allez rejeter la demande d'adhésion de
                <span class="text-red-500">{{ modalItem.denomination }}</span>
            </div>

            <div class="mt-4" v-if="showRejModal">
                <InputLabel for="raison" value="Raison du rejet" />
                <textarea
                    v-model="confForm.raison"
                    class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full"
                    rows="3"
                ></textarea>
            </div>

            <div class="mt-4">
                <button
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Rejeter
                    <div
                        v-if="confForm.processing"
                        style="border-top-color: transparent"
                        class="ml-2 w-3 h-3 border-2 border-white border-solid rounded-full animate-spin bg-red-600"
                    ></div>
                </button>
            </div>
        </form>
    </Modal>
</template>
