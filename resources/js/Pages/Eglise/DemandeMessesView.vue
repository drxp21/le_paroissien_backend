<script setup>
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useForm, router } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import PencilIcon from "vue-material-design-icons/PencilOutline.vue";
import PlusIcon from "vue-material-design-icons/Plus.vue";
import Modal from "@/Components/Modal.vue";
import { downloadCsv } from "@/toolbox";
import { ref, onMounted } from "vue";

let showCreateModal = ref(false);
let showUpdateModal = ref(false);
let filterStart = ref("2023-01-01");
let filterEnd = ref("2024-01-01");
let csvData = ref([]);
let heuresTmp = ref([]);
let demandesTmp = ref([]);
let createForm = useForm({
    auteur: "",
    intention: "",
    date: "",
    heure: "",
    type: "",
});
let updateForm = useForm({
    id: "",
    intention: "",
    auteur: "",
    date: "",
    heure: "",
    type: "",
});
let deleteForm = useForm({
    id: "",
});

const prepareUpdate = (demandemesse) => {
    updateForm.id = demandemesse.id;
    updateForm.intention = demandemesse.intention;
    updateForm.auteur =
        demandemesse.auteur == "Anonyme" ? "" : demandemesse.auteur;
    updateForm.date = demandemesse.date;
    updateForm.heure = demandemesse.heure;
    updateForm.type = demandemesse.type;
    showUpdateModal.value = true;
    getHeureMesse();
};
const createdemandemesse = () => {
    createForm.post(route("demandemesses.store"), {
        onSuccess: () => {
            router.visit(route("demandemesses.index"));
            showCreateModal.value = false;
            createForm.reset();
        },
    });
};
const updatedemandemesse = () => {
    updateForm.auteur = updateForm.auteur == "" ? "Anonyme" : updateForm.auteur;
    updateForm.put(route("demandemesses.update", updateForm), {
        onSuccess: () => {
            router.visit(route("demandemesses.index"));
            updateForm.reset();
            showUpdateModal.value = false;
        },
    });
};
const filterDateRange = () => {
    demandesTmp.value = props.demandemesses.filter((s) => {
        return (
            new Date(filterStart.value) <= new Date(s.date) &&
            new Date(s.date) <= new Date(filterEnd.value)
        );
    });
};
const props = defineProps({
    demandemesses: "",
    heures: "",
});
const createCsv = () => {
    for (let demande of demandesTmp.value) {
        csvData.value.push({
            date: new Date(demande.date).toLocaleDateString("fr-FR", {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
            }),
            intention: demande.intention,
        });
    }
    downloadCsv(
        csvData.value,
        "demandeMesses" + new Date().toLocaleDateString()
    );
};
const getHeureMesse = (type) => {
    let jour_id =
        type == "create"
            ? new Date(createForm.date).getDay()
            : new Date(updateForm.date).getDay();
    jour_id == 0 ? (jour_id = 7) : null;
    heuresTmp.value = props.heures.filter((s) => s.jour_id == jour_id);
};
onMounted(() => {
    filterDateRange();
});
</script>
<template>
    <AppLayout>
        <Head title="Demandes de messe" />
        <div class="px-20 pt-10">
            <PrimaryButton @click="showCreateModal = true">
                Renseigner une demande physique
            </PrimaryButton>
            <button
                v-if="demandemesses.length > 0"
                class="inline-flex items-center px-4 py-2 bg-teal-800 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-teal-700 focus:bg-teal-700 active:bg-teal-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150"
                @click="createCsv"
            >
                Télécharger en csv
            </button>
            <div class="mt-5 flex justify-center items-center gap-3">
                Filtrer de
                <TextInput
                    id="date"
                    v-model="filterStart"
                    type="date"
                    @change="filterDateRange"
                />
                à
                <TextInput
                    id="date"
                    v-model="filterEnd"
                    type="date"
                    @change="filterDateRange"
                />
                <PrimaryButton
                    @click="
                        () => {
                            (demandesTmp = demandemesses),
                                (filterStart = '2023-01-01'),
                                (filterEnd = '2024-01-01');
                        }
                    "
                >
                    Supprimer le filtre
                </PrimaryButton>
            </div>
            <ul class="mt-10">
                <li
                    class="bg-[#cecece] rounded-t-lg py-3 flex justify-evenly items-center text-sm font-medium text-start"
                >
                    <span class="flex-[3] pl-3">Auteur</span>
                    <span class="flex-[3] pl-3"> Intention</span>
                    <span class="flex-[2] pl-3"> Date</span>
                    <span class="flex-[2] pl-3">Heure </span>
                    <span class="flex-[2] pl-3">Type </span>
                    <span class="flex-[2] pl-3"> Mode de paiement</span>
                    <span class="flex-[3] pl-3">Action</span>
                </li>
                <li
                    v-if="demandesTmp.length > 0"
                    v-for="(demandemesse, index) in demandesTmp"
                    class="py-2.5 flex justify- items-center text-sm font-medium hover:bg-[#b7b7b7] text-start"
                    :class="index % 2 == 0 ? 'bg-white' : 'bg-[#cecece]'"
                >
                    <span class="flex-[3] pl-3">
                        {{ demandemesse.auteur }}
                    </span>
                    <span class="flex-[3] pl-3">
                        {{ demandemesse.intention }}
                    </span>
                    <span class="flex-[2] pl-3">
                        {{
                            new Date(demandemesse.date).toLocaleDateString(
                                "fr-FR",
                                {
                                    year: "numeric",
                                    month: "long",
                                    day: "numeric",
                                }
                            )
                        }}
                    </span>
                    <span class="flex-[2] pl-3"> {{ demandemesse.heure }}</span>
                    <span class="flex-[2] pl-3"> {{ demandemesse.type }} </span>
                    <span class="flex-[2] pl-3 capitalize">
                        {{ demandemesse.operateur ? demandemesse.operateur : "Espèces" }}
                    </span>
                    <div class="flex-[3] flex gap-3 text-white justify-start">
                        <button
                            :disabled="demandemesse.paroissien_id"
                            class="bg-blue-700 py-2 px-4 self-start rounded-lg disabled:bg-gray-500 disabled:cursor-not-allowed"
                            @click="prepareUpdate(demandemesse)"
                        >
                            Modifier
                        </button>
                    </div>
                </li>
                <li
                    v-else
                    class="bg-[#b7b7b7] rounded-b-lg py-3 flex justify-evenly items-center text-sm font-medium text-start"
                >
                    Aucune demande de messe
                </li>
            </ul>
        </div>
    </AppLayout>
    <Modal :show="showCreateModal" @close="showCreateModal = false">
        <form class="p-10" @submit.prevent="createdemandemesse">
            <span class="text-2xl font-medium"
                >Renseigner une demande physique</span
            >
            <div class="mt-4">
                <InputLabel
                    for="auteur"
                    value="Nom du demandeur (Laisser vide pour un don anonyme)"
                />
                <TextInput
                    id="auteur"
                    v-model="createForm.auteur"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="auteur"
                />
                <InputError class="mt-2" :message="createForm.errors.auteur" />
            </div>
            <div class="mt-4">
                <InputLabel for="intention" value="Intention de messe" />
                <textarea
                    v-model="createForm.intention"
                    class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full"
                    rows="3"
                ></textarea>
                <InputError
                    class="mt-2"
                    :message="createForm.errors.intention"
                />
            </div>
            <div class="mt-4">
                <InputLabel for="date" value="Date de la messe" />
                <TextInput
                    id="date"
                    v-model="createForm.date"
                    type="date"
                    @change="getHeureMesse('create')"
                    class="mt-1 block w-full"
                    required
                    autocomplete="date"
                />
                <InputError class="mt-2" :message="createForm.errors.date" />
            </div>
            <div class="mt-4">
                <InputLabel for="heure" value="Heure de la messe" />
                <select
                    class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                    v-model="createForm.heure"
                >
                    <option v-for="h in heuresTmp" :value="h.heureDebut">
                        {{ h.heureDebut }}
                    </option>
                </select>

                <InputError class="mt-2" :message="createForm.errors.heure" />
            </div>
            <div class="mt-4">
                <InputLabel for="type" value="Type de demande de messe" />
                <select
                    class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                    v-model="createForm.type"
                >
                    <option value="Simple">Simple</option>
                    <option value="Neuvaine">Neuvaine</option>
                    <option value="Trentaine">Trentaine</option>
                </select>
                <InputError class="mt-2" :message="createForm.errors.type" />
                <br />
                <span v-if="createForm.type == 'Simple'" class="text-slate-400">
                    4 000 cfa</span
                >
                <span
                    v-if="createForm.type == 'Neuvaine'"
                    class="text-slate-400"
                >
                    40 000 cfa</span
                >
                <span
                    v-if="createForm.type == 'Trentaine'"
                    class="text-slate-400"
                >
                    150 000 cfa</span
                >
            </div>

            <div class="mt-4">
                <PrimaryButton>
                    Valider
                    <div
                        v-if="createForm.processing"
                        style="border-top-color: transparent"
                        class="ml-2 w-3 h-3 border-2 border-white border-solid rounded-full animate-spin"
                    ></div>
                    <PlusIcon v-else :size="20" />
                </PrimaryButton>
            </div>
        </form>
    </Modal>
    <Modal :show="showUpdateModal" @close="showUpdateModal = false">
        <form class="p-10" @submit.prevent="updatedemandemesse">
            <span class="text-2xl font-medium"
                >Modifier une demande physique</span
            >
            <div class="mt-4">
                <InputLabel
                    for="auteur"
                    value="Nom du demandeur (Laisser vide pour un don anonyme)"
                />
                <TextInput
                    id="auteur"
                    v-model="updateForm.auteur"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="auteur"
                />
                <InputError class="mt-2" :message="createForm.errors.auteur" />
            </div>
            <div class="mt-4">
                <InputLabel for="intention" value="Intention de messe" />
                <textarea
                    v-model="updateForm.intention"
                    class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full"
                    rows="3"
                ></textarea>
                <InputError
                    class="mt-2"
                    :message="updateForm.errors.intention"
                />
            </div>
            <div class="mt-4">
                <InputLabel for="date" value="Date de la messe" />
                <TextInput
                    id="date"
                    v-model="updateForm.date"
                    type="date"
                    @change="getHeureMesse('update')"
                    class="mt-1 block w-full"
                    required
                    autocomplete="date"
                />
                <InputError class="mt-2" :message="updateForm.errors.date" />
            </div>
            <div class="mt-4">
                <InputLabel for="heure" value="Heure de la messe" />
                <select
                    class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                    v-model="updateForm.heure"
                >
                    <option v-for="h in heuresTmp" :value="h.heureDebut">
                        {{ h.heureDebut }}
                    </option>
                </select>

                <InputError class="mt-2" :message="updateForm.errors.heure" />
            </div>
            <div class="mt-4">
                <InputLabel for="type" value="Type de demande de messe" />
                <select
                    class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                    v-model="updateForm.type"
                >
                    <option value="Simple">Simple</option>
                    <option value="Neuvaine">Neuvaine</option>
                    <option value="Trentaine">Trentaine</option>
                </select>
                <InputError class="mt-2" :message="updateForm.errors.type" />
                <br />
                <span v-if="updateForm.type == 'Simple'" class="text-slate-400">
                    4000 cfa</span
                >
                <span
                    v-if="updateForm.type == 'Neuvaine'"
                    class="text-slate-400"
                >
                    40000 cfa</span
                >
                <span
                    v-if="updateForm.type == 'Trentaine'"
                    class="text-slate-400"
                >
                    150000 cfa</span
                >
            </div>

            <div class="mt-4">
                <PrimaryButton>
                    Modifier
                    <div
                        v-if="updateForm.processing"
                        style="border-top-color: transparent"
                        class="ml-2 w-3 h-3 border-2 border-white border-solid rounded-full animate-spin"
                    ></div>
                    <PencilIcon v-else :size="20" />
                </PrimaryButton>
            </div>
        </form>
    </Modal>
</template>
