<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import PencilIcon from "vue-material-design-icons/PencilOutline.vue";
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import MazRadioButtons from "maz-ui/components/MazRadioButtons";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import PlusIcon from "vue-material-design-icons/Plus.vue";
import { Chart, registerables } from "chart.js";
import { reactive, ref, onMounted } from "vue";

const props = defineProps({
    pelerinage: "",
    inscriptions: "",
});

const createForm = useForm({
    edition: "",
    theme: "",
    dateDebut: "",
    dateFin: "",
    dateLimCar: "",
    dateLimMarche: "",
    fraisCar: "",
    fraisMarche: "",
    couverture: "",
    description: "",
});
const updateForm = useForm({
    id: "",
    edition: "",
    dateDebut: "",
    dateFin: "",
    theme: "",
    dateLimCar: "",
    dateLimMarche: "",
    fraisCar: "",
    fraisMarche: "",
    couverture: "",
    description: "",
});
const createInscriptionForm = useForm({
    montant: "",
    nomBeneficiare: "",
    numeroBeneficiare: "",
    moyen: "bus",
    pour: "",
    institution_id: "",
    pelerinage_id: props.pelerinage?.id,
});

let years = ref([]);
let previewImageUrl = ref(false);
let showCreateModal = ref(false);
let showUpdateModal = ref(false);
let showDelModal = ref(false);
let showCreateInscripModal = ref(false);
let reunisMarche = ref(0);
let reunisCar = ref(0);

let nbrMarche = ref(0);
let nbrCar = ref(0);
let total = ref(0);
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
const ucfirst = (str) => {
    if (typeof str !== "string" || str.length === 0) {
        return str;
    }
    return str.charAt(0).toUpperCase() + str.slice(1);
};
const delFrom = useForm({
    id: props.pelerinage?.id,
});
const previewFile = (event) => {
    let file = "";
    event.target ? (file = event.target.files[0]) : (file = event);
    const reader = new FileReader();

    reader.onload = (event) => {
        createForm.couverture = reader.result.split(",")[1];
        updateForm.couverture = reader.result.split(",")[1];
        previewImageUrl.value = event.target.result;
    };

    reader.readAsDataURL(file);
};

const createpelerinage = () => {
    createForm.post(route("pelerinage.store"), {
        onSuccess: () => {
            createForm.reset();
            previewImageUrl.value = false;
            showCreateModal.value = false;
        },
    });
};
const prepareUpdate = () => {
    updateForm.id = props.pelerinage.id;
    updateForm.edition = props.pelerinage.edition;
    updateForm.theme = props.pelerinage.theme;
    updateForm.dateDebut = props.pelerinage.dateDebut;
    updateForm.dateFin = props.pelerinage.dateFin;
    updateForm.dateLimCar = props.pelerinage.dateLimCar;
    updateForm.dateLimMarche = props.pelerinage.dateLimMarche;
    updateForm.fraisCar = props.pelerinage.fraisCar;
    updateForm.fraisMarche = props.pelerinage.fraisMarche;
    updateForm.couverture = null;
    updateForm.description = props.pelerinage.description;
    showUpdateModal.value = true;
};

const updatepelerinage = () => {
    updateForm.put(route("pelerinage.update", updateForm), {
        onSuccess: () => {
            updateForm.reset();
            previewImageUrl.value = false;
            showUpdateModal.value = false;
        },
    });
};
const createInscription = () => {
    createInscriptionForm.pour = createInscriptionForm.numeroBeneficiare;
    createInscriptionForm.moyen == "bus"
        ? (createInscriptionForm.montant = props.pelerinage.fraisCar)
        : (createInscriptionForm.montant = props.pelerinage.fraisMarche);
    createInscriptionForm.post(route("pelerinagePhysique"), {
        onSuccess: () => {
            showCreateInscripModal.value = false;
        },
    });
};
onMounted(() => {
    props.inscriptions.map((s) => {
        total.value += parseFloat(s.montant);
        console.log(s);
        if (s.moyen == "marche") {
            reunisMarche.value += parseFloat(s.montant);
            nbrMarche.value += 1;
        }
        if (s.moyen == "bus") {
            reunisCar.value += parseFloat(s.montant);
            nbrCar.value += 1;
        }
    });
    let today = new Date().getFullYear();
    for (let i = 0; i <= 5; i++) {
        years.value.push(today + i);
    }

    Chart.register(...registerables);
    if (props.pelerinage) {
        let pelerinageChart = new Chart("pelerinageChart", {
            type: "doughnut",
            data: {
                labels: ["Bus", "Marche"],
                datasets: [
                    {
                        type: "doughnut",
                        backgroundColor: ["blue", "yellow"],
                        data: [nbrCar.value, nbrMarche.value],
                    },
                ],
            },
            options: {
                animation: {
                    duration: 1500,
                    animateScale: true,
                    easing: "easeInOutQuint",
                },
                rotation: -90,
                circumference: 180,
                responsive: false,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: "bottom",
                    },
                    title: {
                        text: "Marcheurs et bus",
                        display: true,
                    },
                },
            },
        });
    }
});
</script>
<template>
    <AppLayout>
        <Head title="Pélerinage" />

        <div
            class="px-10 mt-10"
            v-if="!pelerinage"
            @click="showCreateModal = true"
        >
            <PrimaryButton> Créer l'évènement du pélerinage </PrimaryButton>
        </div>
        <div v-else class="px-20 py-10">
            <div class="flex justify-between gap-10 mt-10">
                <div class="flex flex-col gap-3 float-right">
                    <span class="text-xl font-semibold"
                        >Pélérinage Marial de Poponguine édition
                        {{ pelerinage.edition }}</span
                    >
                    du
                    {{
                        new Date(pelerinage.dateDebut).toLocaleDateString(
                            "fr-FR",
                            {
                                weekday: "long",
                                year: "numeric",
                                month: "long",
                                day: "numeric",
                            }
                        )
                    }}
                    au
                    {{
                        new Date(pelerinage.dateFin).toLocaleDateString(
                            "fr-FR",
                            {
                                weekday: "long",
                                year: "numeric",
                                month: "long",
                                day: "numeric",
                            }
                        )
                    }}
                    <span class="mt-4 text-lg"
                        >Thème:
                        <span class="font-semibold">{{
                            pelerinage.theme
                        }}</span></span
                    >
                    <span class="mt-1 text-lg">Déscription: </span>
                    <p>
                        {{ pelerinage.description }}
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
                                Supprimer l'évènement du pelerinage
                            </button> -->
                    </div>
                    <img
                        class="h-44 rounded-xl shadow-lg object-cover"
                        :src="pelerinage.couverture_path"
                    />
                </div>
            </div>
            <div class="grid grid-cols-2 mt-5">
                <canvas
                    v-if="inscriptions.length > 0"
                    id="pelerinageChart"
                ></canvas>
                <div class="text-end flex flex-col gap-3">
                    <span
                        >Montant récolté :
                        <span class="text-lg font-semibold">
                            {{ addDots(total) }} cfa</span
                        >
                    </span>
                    <span>
                        Marcheurs :
                        <span class="text-lg font-semibold">
                            {{ addDots(nbrMarche) }} |
                            {{ addDots(reunisMarche) }} cfa</span
                        >
                    </span>
                    <span>
                        Bus :
                        <span class="text-lg font-semibold">
                            {{ addDots(nbrCar) }} |
                            {{ addDots(reunisCar) }} cfa</span
                        >
                    </span>
                </div>
            </div>
            <div class="w-full">
                <ul class="mt-10">
                    <div
                        class="text-lg font-semibold mb-4 flex justify-between"
                    >
                        Inscriptions enregistrées
                        <PrimaryButton @click="showCreateInscripModal = true">
                            Renseigner une inscription physique
                        </PrimaryButton>
                    </div>
                    <li
                        class="bg-[#cecece] rounded-t-lg py-3 flex justify-evenly items-center text-sm font-medium text-start"
                    >
                        <span class="flex-[1.2] pl-3"> Auteur </span>
                        <span class="flex-[1.2] pl-3"> Opérateur </span>
                        <span class="flex-[1.2] pl-3"> Nom énéficiaire </span>
                        <span class="flex-[1.2] pl-3"> Numero Bénéficiaire </span>
                        <span class="flex-[1.2] pl-3">
                            Moyen de déplacement
                        </span>
                        <span class="flex-[1.2] pl-3"> Montant </span>
                        <span class="flex-[1.2] pl-3"> Inscrit le: </span>
                    </li>
                    <li
                        v-if="inscriptions.length > 0"
                        v-for="(inscription, index) in inscriptions"
                        class="py-2.5 flex justify- items-center text-sm font-medium hover:bg-[#b7b7b7]"
                        :class="index % 2 == 0 ? 'bg-white' : 'bg-[#cecece]'"
                    >
                        <span class="flex-[1.2] pl-3">
                            {{
                                inscription.paroissien_name ??
                                "Inscripton physique"
                            }}</span
                        >
                        <span class="flex-[1.2] pl-3">
                            {{ inscription.operateur ?? "---" }}
                        </span>
                        <span class="flex-[1.2] pl-3">
                            {{ inscription.nomBeneficiare }}</span
                        >
                        <span class="flex-[1.2] pl-3">
                            {{ inscription.numeroBeneficiare }}</span
                        >
                        <span class="flex-[1.2] pl-3">
                            {{ ucfirst(inscription.moyen) }}</span
                        >
                        <span class="flex-[1.2] pl-3 text-green-500">
                            {{ inscription.montant }} cfa
                        </span>
                        <span class="flex-[1.2] pl-3">
                            {{ inscription.created_at.split("T")[0] }}</span
                        >
                    </li>
                    <li
                        v-else
                        class="bg-[#b7b7b7] rounded-b-lg py-3 flex justify-evenly items-center text-sm font-medium text-start"
                    >
                        Aucune inscription pour le moment
                    </li>
                </ul>
            </div>
        </div>

        <Modal :show="showDelModal" @close="showDelModal = false">
            <form
                class="p-10"
                @submit.prevent="
                    () => (
                        delFrom.delete(
                            route('pelerinage.destroy', pelerinage.id)
                        ),
                        (showDelModal = false)
                    )
                "
            >
                <span class="text-2xl font-medium"
                    >Supprimer l'évènement du pélerinage</span
                >
                <div class="mt-4">
                    Vous allez supprimer l'évènement du pélerinage et toutes les
                    données qui y sont liées !
                </div>

                <div class="mt-4">
                    <button
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        Confirmer
                        <div
                            v-if="createForm.processing"
                            style="border-top-color: transparent"
                            class="ml-2 w-3 h-3 border-2 border-white border-solid rounded-full animate-spin bg-red-600"
                        ></div>
                    </button>
                </div>
            </form>
        </Modal>
        <Modal
            :show="showCreateModal"
            @close="showCreateModal = false"
            max-width="3xl"
        >
            <form class="p-10" @submit.prevent="createpelerinage">
                <div class="text-2xl font-medium">
                    Créer l'évènement du pélerinage
                </div>
                <div class="flex gap-5 w-full">
                    <div class="w-full">
                        <div class="mt-4">
                            <InputLabel for="edition" value="Année" />
                            <select
                                required
                                class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                                v-model="createForm.edition"
                            >
                                <option
                                    v-for="year in years"
                                    :key="year"
                                    :value="year"
                                >
                                    {{ year }}
                                </option>
                                <InputError
                                    class="mt-2"
                                    :message="createForm.errors.edition"
                                />
                            </select>
                        </div>
                        <div class="mt-4">
                            <InputLabel for="theme" value="Thème" />
                            <textarea
                                v-model="createForm.theme"
                                class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full"
                                rows="3"
                            ></textarea>
                            <InputError
                                class="mt-2"
                                :message="createForm.errors.theme"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="description" value="Déscription" />
                            <textarea
                                v-model="createForm.description"
                                class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full"
                                rows="3"
                            ></textarea>
                            <InputError
                                class="mt-2"
                                :message="createForm.errors.description"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel
                                for="dateDebut"
                                value="Date de début du pélerinage"
                            />
                            <TextInput
                                id="dateDebut"
                                v-model="createForm.dateDebut"
                                type="date"
                                class="mt-1 block w-full"
                                required
                                autocomplete="dateDebut"
                            />
                            <InputError
                                class="mt-2"
                                :message="createForm.errors.dateDebut"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel
                                for="dateFin"
                                value="Date de fin du pélerinage"
                            />
                            <TextInput
                                id="dateFin"
                                v-model="createForm.dateFin"
                                type="date"
                                class="mt-1 block w-full"
                                required
                                autocomplete="dateFin"
                            />
                            <InputError
                                class="mt-2"
                                :message="createForm.errors.dateFin"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="mt-4">
                            <InputLabel
                                for="fraisCar"
                                value="Frais d'inscription (Bus)"
                            />
                            <TextInput
                                id="fraisCar"
                                v-model="createForm.fraisCar"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autocomplete="fraisCar"
                            />
                            <InputError
                                class="mt-2"
                                :message="createForm.errors.fraisCar"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel
                                for="dateLimCar"
                                value="Date limite d'inscript(Bus)"
                            />
                            <TextInput
                                id="dateLimCar"
                                v-model="createForm.dateLimCar"
                                type="date"
                                class="mt-1 block w-full"
                                required
                                autocomplete="dateLimCar"
                            />
                            <InputError
                                class="mt-2"
                                :message="createForm.errors.dateLimCar"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel
                                for="fraisMarche"
                                value="Frais d'inscription (Marche)"
                            />
                            <TextInput
                                id="fraisMarche"
                                v-model="createForm.fraisMarche"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autocomplete="fraisMarche"
                            />
                            <InputError
                                class="mt-2"
                                :message="createForm.errors.fraisMarche"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel
                                for="dateLimMarche"
                                value="Date limite d'inscription(Marche)"
                            />
                            <TextInput
                                id="dateLimMarche"
                                v-model="createForm.dateLimMarche"
                                type="date"
                                class="mt-1 block w-full"
                                required
                                autocomplete="dateLimMarche"
                            />
                            <InputError
                                class="mt-2"
                                :message="createForm.errors.dateLimMarche"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel
                                for="couverture"
                                value="Image de couverture"
                            />
                            <div class="mt-2">
                                <label
                                    class="border font-medium text-sm flex items-center px-3 py-2.5 rounded-lg mt-0.5 shadow-lg"
                                    for="editCove"
                                    role="button"
                                >
                                    Choisir une image de couverture
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
                                :message="createForm.errors.couverture"
                            />
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <PrimaryButton>
                        Créer
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
        <Modal
            :show="showUpdateModal"
            @close="showUpdateModal = false"
            max-width="3xl"
        >
            <form class="p-10" @submit.prevent="updatepelerinage">
                <div class="text-2xl font-medium">
                    Modifier l'évènement du pélerinage
                </div>
                <div class="flex gap-5 w-full">
                    <div class="w-full">
                        <div class="mt-4">
                            <InputLabel for="edition" value="Année" />
                            <select
                                required
                                class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                                v-model="updateForm.edition"
                            >
                                <option
                                    v-for="year in years"
                                    :key="year"
                                    :value="year"
                                >
                                    {{ year }}
                                </option>
                                <InputError
                                    class="mt-2"
                                    :message="updateForm.errors.edition"
                                />
                            </select>
                        </div>
                        <div class="mt-4">
                            <InputLabel for="theme" value="Thème" />
                            <textarea
                                v-model="updateForm.theme"
                                class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full"
                                rows="3"
                            ></textarea>
                            <InputError
                                class="mt-2"
                                :message="updateForm.errors.theme"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="description" value="Déscription" />
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
                                for="dateDebut"
                                value="Date de début du pélerinage"
                            />
                            <TextInput
                                id="dateDebut"
                                v-model="updateForm.dateDebut"
                                type="date"
                                class="mt-1 block w-full"
                                required
                                autocomplete="dateDebut"
                            />
                            <InputError
                                class="mt-2"
                                :message="updateForm.errors.dateDebut"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel
                                for="dateFin"
                                value="Date de fin du pélerinage"
                            />
                            <TextInput
                                id="dateFin"
                                v-model="updateForm.dateFin"
                                type="date"
                                class="mt-1 block w-full"
                                required
                                autocomplete="dateFin"
                            />
                            <InputError
                                class="mt-2"
                                :message="updateForm.errors.dateFin"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="mt-4">
                            <InputLabel
                                for="fraisCar"
                                value="Frais d'inscription (Bus)"
                            />
                            <TextInput
                                id="fraisCar"
                                v-model="updateForm.fraisCar"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autocomplete="fraisCar"
                            />
                            <InputError
                                class="mt-2"
                                :message="updateForm.errors.fraisCar"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel
                                for="dateLimCar"
                                value="Date limite d'inscript(Bus)"
                            />
                            <TextInput
                                id="dateLimCar"
                                v-model="updateForm.dateLimCar"
                                type="date"
                                class="mt-1 block w-full"
                                required
                                autocomplete="dateLimCar"
                            />
                            <InputError
                                class="mt-2"
                                :message="updateForm.errors.dateLimCar"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel
                                for="fraisMarche"
                                value="Frais d'inscription (Marche)"
                            />
                            <TextInput
                                id="fraisMarche"
                                v-model="updateForm.fraisMarche"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autocomplete="fraisMarche"
                            />
                            <InputError
                                class="mt-2"
                                :message="updateForm.errors.fraisMarche"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel
                                for="dateLimMarche"
                                value="Date limite d'inscription(Marche)"
                            />
                            <TextInput
                                id="dateLimMarche"
                                v-model="updateForm.dateLimMarche"
                                type="date"
                                class="mt-1 block w-full"
                                required
                                autocomplete="dateLimMarche"
                            />
                            <InputError
                                class="mt-2"
                                :message="updateForm.errors.dateLimMarche"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel
                                for="couverture"
                                value="Image de couverture"
                            />
                            <div class="mt-2">
                                <label
                                    class="border font-medium text-sm flex items-center px-3 py-2.5 rounded-lg mt-0.5 shadow-lg"
                                    for="editCove"
                                    role="button"
                                >
                                    Choisir une image de couverture
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
                    </div>
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
        <Modal
            :show="showCreateInscripModal"
            @close="showCreateInscripModal = false"
        >
            <form class="p-10" @submit.prevent="createInscription">
                <span class="text-2xl font-medium"
                    >Renseigner une inscription physique</span
                >
                <div class="mt-4">
                    <InputLabel value="Nom du bénéficiaire" />
                    <TextInput
                        id="nom"
                        v-model="createInscriptionForm.nomBeneficiare"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autocomplete="nom"
                    />
                    <InputError
                        class="mt-2"
                        :message="createInscriptionForm.errors.nomBeneficiare"
                    />
                </div>
                <div class="mt-4">
                    <InputLabel value="Numéro du bénéficiaire (Ex: +221 7x xxx xx xx)" />
                    <TextInput
                        id="numero"
                        v-model="createInscriptionForm.numeroBeneficiare"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autocomplete="numero"
                    />
                    <InputError
                        class="mt-2"
                        :message="
                            createInscriptionForm.errors.numeroBeneficiare
                        "
                    />
                </div>
                <div class="flex flex-col items-start justify-center">
                    <label class="leading-7 pl-1 text-sm">
                        Choisir le moyen de déplacement
                    </label>
                    <MazRadioButtons
                        required
                        v-model="createInscriptionForm.moyen"
                        :options="[
                            { value: 'marche', label: 'Marche' },
                            { value: 'bus', label: 'Bus' },
                        ]"
                    />

                    <div class="mt-6 grid grid-cols-2 gap-x-3">
                        <span class="text-slate-800 text-xs col-span-2"
                            >Frais d'inscription :
                        </span>
                        <div
                            class="font-semibold"
                            v-show="createInscriptionForm.moyen == 'bus'"
                        >
                            <span
                                class="text-sm text-black"
                                v-if="pelerinage != 'await'"
                            >
                                Bus : {{ pelerinage?.fraisCar }} CFA</span
                            >
                            <div
                                v-else
                                style="border-top-color: transparent"
                                class="w-4 h-4 m-1 border-4 border-black border-solid rounded-full animate-spin"
                            ></div>
                        </div>
                        <div
                            class="font-semibold"
                            v-show="createInscriptionForm.moyen == 'marche'"
                        >
                            <span
                                class="text-sm text-black"
                                v-if="pelerinage != 'await'"
                                >Marche : {{ pelerinage.fraisMarche }}CFA</span
                            >
                            <div
                                v-else
                                style="border-top-color: transparent"
                                class="w-4 h-4 m-1 border-4 border-black border-solid rounded-full animate-spin"
                            ></div>
                        </div>
                    </div>
                </div>
                <PrimaryButton class="mt-4">Renseigner</PrimaryButton>
            </form>
        </Modal>
    </AppLayout>
</template>
