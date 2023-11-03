<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import PencilIcon from "vue-material-design-icons/PencilOutline.vue";
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import PlusIcon from "vue-material-design-icons/Plus.vue";
import { Chart, registerables } from "chart.js";
import { reactive, ref, onMounted } from "vue";

const props = defineProps({
    collecte: "",
});

const updateForm = useForm({
    id: "",
    titre: "",
    description: "",
    minimum: "0",
    date_debut: "",
    date_cloture: "",
    objectif: "",
    toutlemonde: "",
    couverture: "",
});
const createDonForm = useForm({
    montant: "",
    auteur: "",
    collecte_id: props.collecte.id,
});
let years = ref([]);
let previewImageUrl = ref(false);
let showCreateModal = ref(false);
let showUpdateModal = ref(false);
let showDelModal = ref(false);
const addDots = (nStr) => {
    nStr += "";
    let x = nStr.split(".");
    let x1 = x[0];
    let x2 = x.length > 1 ? "." + x[1] : "";
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, "$1" + "." + "$2"); // changed comma to dot here
    }
    return x1 + x2;
};

const delForm = useForm({
    id: props.collecte?.id,
});
const previewFile = (event) => {
    let file = "";
    event.target ? (file = event.target.files[0]) : (file = event);
    const reader = new FileReader();

    reader.onload = (event) => {
        updateForm.couverture = reader.result.split(",")[1];
        previewImageUrl.value = event.target.result;
    };

    reader.readAsDataURL(file);
};

const prepareUpdate = () => {
    updateForm.id = props.collecte.id;
    updateForm.titre = props.collecte.titre;
    updateForm.minimum = props.collecte.minimum;
    updateForm.description = props.collecte.description;
    updateForm.date_debut = props.collecte.date_debut;
    updateForm.date_cloture = props.collecte.date_cloture;
    updateForm.objectif = props.collecte.objectif;
    updateForm.toutlemonde = props.collecte.toutlemonde == 0 ? false : true;
    updateForm.couverture = null;
    showUpdateModal.value = true;
};

const updatecollecte = () => {
    updateForm.put(route("collectes.update", updateForm), {
        onSuccess: () => {
            updateForm.reset();
            previewImageUrl.value = false;
            showUpdateModal.value = false;
        },
    });
};
const createDon = () => {
    createDonForm.post(route("collectePhysique"), {
        onSuccess: () => {
            showCreateModal.value = false;
        },
    });
};
onMounted(() => {
    Chart.register(...registerables);
    if (props.collecte) {
        new Chart("collecteChart", {
            type: "doughnut",
            data: {
                labels: ["Collectés", "Restant"],
                datasets: [
                    {
                        data: [
                            props.collecte.reunis,
                            props.collecte.objectif - props.collecte.reunis,
                        ],
                        backgroundColor: ["#507db8", "#CCCCCC"],
                    },
                ],
            },
            options: {
                plugins: {
                    title: {
                        position: "bottom",
                        display: true,
                        text: "Evolution des fonds collectés",
                        color: "#507db8",
                        font: {
                            size: 18,
                            weight: "bold",
                        },
                        padding: {
                            top: 10,
                        },
                    },
                },
                rotation: -90,
                circumference: 180,
                animation: {
                    duration: 1500,
                    animateScale: true,
                    easing: "easeInOutQuint",
                },
                responsive: true,
                maintainAspectRatio: false,
            },
        });
    }
});
</script>
<template>
    <AppLayout>
        <Head title="Collecte de fonds" />

        <div class="px-20 py-10">
            <div class="flex justify-between gap-10 mt-10">
                <div class="flex flex-col gap-3 float-right">
                    <span class="text-xl font-semibold"
                        >{{ collecte.titre }} {{ collecte.edition }}</span
                    >
                    <span class="mt-1 text-lg">Déscription: </span>
                    <p>
                        {{ collecte.description }}
                    </p>
                </div>
                <div class="flex">
                    <div class="m-4 flex flex-col gap-4 self-end">
                        <button
                            class="px-3 py-2 bg-teal-700 text-white rounded-md shadow-sm text-sm self-end"
                            @click="prepareUpdate"
                        >
                            Modifier les informations
                        </button>
                        <!-- <button
                            class="px-3 py-2 bg-red-700 text-white rounded-md shadow-sm text-sm"
                            @click="showDelModal = true"
                        >
                            Supprimer la collecte
                        </button> -->
                    </div>
                    <img
                        class="w-44 rounded-xl shadow-lg"
                        :src="collecte.couverture_path"
                    />
                </div>
            </div>
            <div class="grid grid-cols-2 mt-10">
                <canvas
                    v-if="collecte.participations.length > 0"
                    id="collecteChart"
                    class="col-span-1"
                ></canvas>
                <div class="text-end flex flex-col gap-3 col-span-1">
                    <span
                        >Montant récolté :
                        <span class="text-lg font-semibold">
                            {{ addDots(collecte.reunis) }} cfa</span
                        >
                    </span>
                    <span
                        >Montant restant :
                        <span class="text-lg font-semibold">
                            {{ addDots(collecte.objectif) }} cfa</span
                        >
                    </span>
                </div>
            </div>
            <div class="w-full">
                <ul class="mt-10">
                    <div
                        class="text-lg font-semibold mb-4 flex justify-between"
                    >
                        Dons enregistrés
                        <PrimaryButton @click="showCreateModal = true">
                            Renseigner un don physique
                        </PrimaryButton>
                    </div>
                    <li
                        class="bg-[#cecece] rounded-t-lg py-3 flex justify-evenly items-center text-sm font-medium text-start"
                    >
                        <span class="flex-[1.2] pl-3"> Auteur </span>
                        <span class="flex-[1.2] pl-3"> Montant </span>
                        <span class="flex-[1.2] pl-3"> Intention </span>
                        <span class="flex-[1.2] pl-3"> Donné le: </span>
                    </li>
                    <li
                        v-if="collecte.participations.length > 0"
                        v-for="(inscription, index) in collecte.participations"
                        class="py-2.5 flex justify- items-center text-sm font-medium hover:bg-[#b7b7b7]"
                        :class="index % 2 == 0 ? 'bg-white' : 'bg-[#cecece]'"
                    >
                    <span class="flex-[1.2] pl-3">
                            {{ inscription.auteur }}
                        </span>
                        <span class="flex-[1.2] pl-3 text-green-700">
                            {{ addDots(inscription.montant) }} cfa
                        </span>
                        <span class="flex-[1.2] pl-3">
                            {{ inscription.intention ?? '----' }}</span
                        >
                        <span class="flex-[1.2] pl-3">
                            {{ inscription.created_at.split("T")[0] }}</span
                        >
                    </li>
                    <li
                        v-else
                        class="bg-[#b7b7b7] rounded-b-lg py-3 flex justify-evenly items-center text-sm font-medium text-start"
                    >
                        Aucune participation pour le moment
                    </li>
                </ul>
            </div>
        </div>

        <Modal :show="showDelModal" @close="showDelModal = false">
            <form
                class="p-10"
                @submit.prevent="
                    () => (
                        delForm.delete(route('collectes.destroy', collecte.id)),
                        (showDelModal = false)
                    )
                "
            >
                <span class="text-2xl font-medium">Supprimer la collecte </span>
                <div class="mt-4">
                    Vous allez supprimer la collecte et toutes les données qui y
                    sont liées !
                </div>

                <div class="mt-4">
                    <button
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        Confirmer
                        <div
                            v-if="delForm.processing"
                            style="border-top-color: transparent"
                            class="ml-2 w-3 h-3 border-2 border-white border-solid rounded-full animate-spin bg-red-600"
                        ></div>
                    </button>
                </div>
            </form>
        </Modal>
        <Modal :show="showCreateModal" @close="showCreateModal = false">
            <form class="p-10" @submit.prevent="createDon">
                <span class="text-2xl font-medium"
                    >Renseigner un don physique</span
                >
                <div class="mt-4">
                    <InputLabel
                        for="auteur"
                        value="Auteur (Laisser vide pour un don anonyme )"
                    />
                    <TextInput
                        id="auteur"
                        v-model="createDonForm.auteur"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="auteur"
                    />
                    <InputError
                        class="mt-2"
                        :message="createDonForm.errors.auteur"
                    />
                </div>
                <div class="mt-4">
                    <InputLabel for="montant" value="Montant" />
                    <TextInput
                        id="montant"
                        v-model="createDonForm.montant"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="montant"
                    />
                    <InputError
                        class="mt-2"
                        :message="createDonForm.errors.montant"
                    />
                </div>
                <PrimaryButton class="mt-4">Renseigner</PrimaryButton>
            </form>
        </Modal>
        <Modal :show="showUpdateModal" @close="showUpdateModal = false">
            <form class="p-10" @submit.prevent="updatecollecte">
                <span class="text-2xl font-medium"
                    >Modifier la collecte de fonds</span
                >
                <div class="mt-4">
                    <InputLabel for="titre" value="Intitulé" />
                    <TextInput
                        id="titre"
                        v-model="updateForm.titre"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autocomplete="titre"
                    />
                    <InputError
                        class="mt-2"
                        :message="updateForm.errors.titre"
                    />
                </div>
                <div class="mt-4">
                    <InputLabel for="description" value="Détails" />
                    <textarea
                        v-model="updateForm.description"
                        class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full"
                        rows="3"
                    ></textarea>
                    <InputError
                        class="mt-2"
                        :message="updateForm.errors.description"
                    />
                </div>
                <div class="mt-4">
                    <InputLabel
                        for="minimum"
                        value="Montant minimum de participation"
                    />
                    <TextInput
                        id="minimum"
                        v-model="updateForm.minimum"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autocomplete="minimum"
                    />
                    <InputError
                        class="mt-2"
                        :message="updateForm.errors.minimum"
                    />
                </div>
                <div class="mt-4">
                    <InputLabel for="objectif" value="Montant visé" />
                    <TextInput
                        id="objectif"
                        v-model="updateForm.objectif"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autocomplete="objectif"
                    />
                    <InputError
                        class="mt-2"
                        :message="updateForm.errors.objectif"
                    />
                </div>
                <div class="mt-4">
                    <InputLabel
                        for="date_debut"
                        value="Date de début de la collecte"
                    />
                    <TextInput
                        id="date_debut"
                        v-model="updateForm.date_debut"
                        type="date"
                        class="mt-1 block w-full"
                        required
                        autocomplete="date_debut"
                    />
                    <InputError
                        class="mt-2"
                        :message="updateForm.errors.date_debut"
                    />
                </div>
                <div class="mt-4">
                    <InputLabel
                        for="date_cloture"
                        value="Date de clôture de la collecte"
                    />
                    <TextInput
                        id="date_cloture"
                        v-model="updateForm.date_cloture"
                        type="date"
                        class="mt-1 block w-full"
                        required
                        autocomplete="date_cloture"
                    />
                    <InputError
                        class="mt-2"
                        :message="updateForm.errors.date_cloture"
                    />
                </div>
                <div class="mt-4">
                    <InputLabel for="couverture" value="Image de couverture" />
                    <div class="mt-2">
                        <label
                            class="border font-medium text-sm flex items-center px-3 py-2.5 rounded-lg mt-0.5 shadow-lg"
                            for="editCove"
                            role="button"
                        >
                            Changer l'image de couverture
                        </label>
                    </div>
                    <div class="flex items-center mt-2">
                        <input
                            type="file"
                            id="editCove"
                            class="hidden"
                            @change="($event) => previewFile($event)"
                            accept="image/*"
                            name="couverture"
                        />
                        <img
                            :src="previewImageUrl"
                            v-if="previewImageUrl"
                            class="rounded-md my-4"
                            width="200"
                        />
                    </div>
                    <InputError
                        class="mt-2"
                        :message="updateForm.errors.couverture"
                    />
                </div>

                <div class="mt-4">
                    <InputLabel
                        for="toutlemonde"
                        value="Qui peut participer ?"
                    />
                    <select
                        required
                        class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                        v-model="updateForm.toutlemonde"
                    >
                        <option :value="true">Tout le monde</option>
                        <option :value="false">Mes paroissiens</option>
                        <InputError
                            class="mt-2"
                            :message="updateForm.errors.toutlemonde"
                        />
                    </select>
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
    </AppLayout>
</template>
p
