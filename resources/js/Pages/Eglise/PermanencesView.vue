<script setup>
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import PencilIcon from "vue-material-design-icons/PencilOutline.vue";
import PlusIcon from "vue-material-design-icons/Plus.vue";
import Modal from "@/Components/Modal.vue";
import axios from "axios";
import { ref, onMounted } from "vue";

let processing = ref(false);
const props = defineProps({
    permanences_messe: "",
    permanences_confession: "",
    permanences_pretre: "",
    permanences_secretariat: "",
});
let page = usePage();
let showDelModal = ref(false);
let permanences_messeForms = ref([]);
let permanences_confessionForms = ref([]);
let permanences_pretreForms = ref([]);
let toBeDeleted = ref("");
let permanences_secretariatForms = ref([]);

const updatePermanences = async () => {
    processing.value = true;
    let promises = [];
    for (let form of permanences_messeForms.value) {
        promises.push(axios.put(route("permanencesmesse.update", form), form));
    }
    for (let form of permanences_confessionForms.value) {
        promises.push(
            axios.put(route("permanencesconfession.update", form), form)
        );
    }
    for (let form of permanences_pretreForms.value) {
        promises.push(
            axios.put(route("permanencespretres.update", form), form)
        );
    }
    for (let form of permanences_secretariatForms.value) {
        promises.push(
            axios.put(route("permanencessecretariat.update", form), form)
        );
    }
    Promise.all(promises).then(() => {
        window.location.reload();
    });
};
const deleteHeureMesse = (id) => {
    toBeDeleted.value = id;
    showDelModal.value = true;
};

onMounted(() => {
    for (const [i, p] of props.permanences_messe.entries()) {
        permanences_messeForms.value.push(
            useForm({
                id: p?.id ?? i,
                jour_id: p?.jour_id ?? i,
                heureDebut: p?.heureDebut ?? 0,
                institution_id: page.props.auth.user.institution.id,
            })
        );
    }
    for (let permanence of props.permanences_confession) {
        permanences_confessionForms.value.push(
            useForm({
                id: permanence.id,
                jour_id: permanence.jour_id,
                heureDebut: permanence.heureDebut,
                institution_id: permanence.institution_id,
            })
        );
    }

    for (let permanence of props.permanences_pretre) {
        permanences_pretreForms.value.push(
            useForm({
                id: permanence.id,
                jour_id: permanence.jour_id,
                heureDebut: permanence.heureDebut,
                institution_id: permanence.institution_id,
            })
        );
    }
    for (let permanence of props.permanences_secretariat) {
        permanences_secretariatForms.value.push(
            useForm({
                id: permanence.id,
                jour_id: permanence.jour_id,
                heureDebut: permanence.heureDebut,
                heureFin: permanence.heureFin,
                institution_id: permanence.institution_id,
            })
        );
    }
});
</script>
<template>
    <AppLayout>
        <Head title="Permanences" />
        <div class="px-20 mt-10">
            <PrimaryButton @click="updatePermanences" :disabled="processing">
                Mettre à jour &nbsp;
                <div
                    style="border-top-color: transparent"
                    v-if="processing"
                    class="w-4 h-4 border-2 border-white-400 border-solid rounded-full animate-spin"
                ></div>
            </PrimaryButton>
        </div>
        <div class="flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8 px-20">
                <div class="py-2 inline-block min-w-full">
                    <div class="overflow-auto px-10">
                        <table class="min-w-full">
                            <thead class="bg-white border-b">
                                <tr>
                                    <th
                                        scope="col"
                                        class="text-sm font-medium text-gray-900 px-1 py-4 text-left"
                                    >
                                        #
                                    </th>
                                    <th
                                        scope="col"
                                        class="text-sm font-medium text-gray-900 px-1 py-4 text-left"
                                    >
                                        Lundi
                                    </th>
                                    <th
                                        scope="col"
                                        class="text-sm font-medium text-gray-900 px-1 py-4 text-left"
                                    >
                                        Mardi
                                    </th>
                                    <th
                                        scope="col"
                                        class="text-sm font-medium text-gray-900 px-1 py-4 text-left"
                                    >
                                        Mercredi
                                    </th>
                                    <th
                                        scope="col"
                                        class="text-sm font-medium text-gray-900 px-1 py-4 text-left"
                                    >
                                        Jeudi
                                    </th>
                                    <th
                                        scope="col"
                                        class="text-sm font-medium text-gray-900 px-1 py-4 text-left"
                                    >
                                        Vendredi
                                    </th>
                                    <th
                                        scope="col"
                                        class="text-sm font-medium text-gray-900 px-1 py-4 text-left"
                                    >
                                        Samedi
                                    </th>
                                    <th
                                        scope="col"
                                        class="text-sm font-medium text-gray-900 px-1 py-4 text-left"
                                    >
                                        Dimanche
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-gray-100 border-b">
                                    <td
                                        class="px-2 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                    >
                                        Heures de Messe(s)
                                    </td>

                                    <td
                                        v-for="i in 7"
                                        class="text-sm text-gray-900 font-light px-1 py-4 whitespace-nowrap"
                                    >
                                        <div class="flex">
                                            <div
                                                v-for="(
                                                    p, index
                                                ) in permanences_messeForms"
                                            >
                                                <input
                                                    v-if="
                                                        p.jour_id == i &&
                                                        p.heureDebut != 0
                                                    "
                                                    type="time"
                                                    @contextmenu.prevent="
                                                        deleteHeureMesse(p.id)
                                                    "
                                                    class="p-0 text-xs rounded-md"
                                                    v-model="
                                                        permanences_messeForms[
                                                            index
                                                        ].heureDebut
                                                    "
                                                />
                                            </div>
                                            <button
                                                class="bg-blue-500 text-white m-0.5 p-1 h-full rounded-md"
                                                title="Ajouter une messe pour ce jour"
                                                @click="
                                                    router.visit(
                                                        route(
                                                            'permanenceMesseAdd'
                                                        ),
                                                        {
                                                            method: 'post',
                                                            data: {
                                                                jour_id: i,
                                                                institution_id:
                                                                    page.props
                                                                        .auth
                                                                        .user
                                                                        .institution
                                                                        .id,
                                                            },
                                                        },
                                                        {
                                                            only: [
                                                                'permanences_messe',
                                                            ],
                                                        }
                                                    )
                                                "
                                            >
                                                <PlusIcon :size="12" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="bg-white border-b">
                                    <td
                                        class="px-2 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                    >
                                        Permanences Confession
                                    </td>
                                    <td
                                        v-for="permanence in permanences_confessionForms"
                                        class="text-sm text-gray-900 font-light px-1 py-4 whitespace-nowrap"
                                    >
                                        <input
                                            type="time"
                                            class="p-0 text-xs rounded-md"
                                            v-model="permanence.heureDebut"
                                        />
                                    </td>
                                </tr>
                                <tr class="bg-gray-100 border-b">
                                    <td
                                        class="px-2 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                    >
                                        Permanences Pretre
                                    </td>
                                    <td
                                        v-for="permanence in permanences_pretreForms"
                                        class="text-sm text-gray-900 font-light px-1 py-4 whitespace-nowrap"
                                    >
                                        <input
                                            type="time"
                                            class="p-0 text-xs rounded-md"
                                            v-model="permanence.heureDebut"
                                        />
                                    </td>
                                </tr>
                                <tr class="bg-white border-b">
                                    <td
                                        class="px-2 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                    >
                                        Permanences Secretariat
                                    </td>
                                    <td
                                        v-for="permanence in permanences_secretariatForms"
                                        class="text-sm text-gray-900 font-light px-1 py-4 whitespace-nowrap"
                                    >
                                        <div class="flex flex-col">
                                            De
                                            <input
                                                type="time"
                                                class="p-0 text-xs rounded-md"
                                                v-model="permanence.heureDebut"
                                            />
                                            à
                                            <input
                                                type="time"
                                                class="p-0 text-xs rounded-md"
                                                v-model="permanence.heureFin"
                                            />
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <Modal :show="showDelModal" @close="showDelModal = false">
                <form
                    class="p-10"
                    @submit.prevent="
                        () => (
                            router.visit(
                                route('permanenceMesseDestroy'),
                                {
                                    method: 'delete',
                                    data: {
                                        id: toBeDeleted,
                                    },
                                },
                                {
                                    only: ['permanences_messe'],
                                }
                            ),
                            (showDelModal = false)
                        )
                    "
                >
                    <span class="text-2xl font-medium"
                        >Supprimer cette heure de messe pour ce jour
                    </span>

                    <div class="mt-4">
                        <button
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            Confirmer
                        </button>
                    </div>
                </form>
            </Modal>
        </div>
    </AppLayout>
</template>
