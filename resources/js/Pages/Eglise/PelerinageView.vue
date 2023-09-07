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
    pelerinage: "",
    inscriptions: [],
});

const createForm = useForm({
    edition: "",
    theme: "",
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
    theme: "",
    dateLimCar: "",
    dateLimMarche: "",
    fraisCar: "",
    fraisMarche: "",
    couverture: "",
    description: "",
});
let years = ref([]);
let previewImageUrl = ref(false);
let showCreateModal = ref(false);
let showUpdateModal = ref(false);
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

const previewFile = (event) => {
    let file = "";
    event.target ? (file = event.target.files[0]) : (file = event);
    console.log(file.name);
    const reader = new FileReader();

    reader.onload = (event) => {
        createForm.couverture = file;
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
    updateForm.dateLimCar = props.pelerinage.dateLimCar;
    updateForm.dateLimMarche = props.pelerinage.dateLimMarche;
    updateForm.fraisCar = props.pelerinage.fraisCar;
    updateForm.fraisMarche = props.pelerinage.fraisMarche;
    updateForm.couverture = props.pelerinage.couverture;
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
    console.log(props.inscriptions);
});
</script>
<template>
    <AppLayout>
        <Head title="Pélerinage" />
        <PrimaryButton v-if="!pelerinage" @click="showCreateModal = true">
            Créer l'inscription du pélerinage
        </PrimaryButton>

        <div v-else class="px-20 py-10">
            <button
                class="float-right px-3 py-2 bg-teal-700 text-white rounded-md shadow-sm text-sm"
                @click="prepareUpdate"
            >
                Modifier les informations
            </button>
            <div class="flex flex-col gap-3">
                <span class="text-xl font-semibold"
                    >Pélérinage Marial de Popoguine édition
                    {{ pelerinage.edition }}</span
                >
                <span
                    >Thème:
                    <span class="font-semibold">{{
                        pelerinage.theme
                    }}</span></span
                >
            </div>
            <div class="grid grid-cols-2">
                <canvas id="pelerinageChart"></canvas>
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
                    <li
                        class="bg-[#cecece] rounded-t-lg py-3 flex justify-evenly items-center text-sm font-medium text-start"
                    >
                        <span class="flex-[1.2] pl-3">
                            Moyen de déplacement
                        </span>
                        <span class="flex-[1.2] pl-3"> Opérateur </span>
                        <span class="flex-[1.2] pl-3"> Bénéficiaire </span>
                        <span class="flex-[1.2] pl-3"> Moyen </span>
                        <span class="flex-[1.2] pl-3"> Montant </span>
                        <span class="flex-[1.2] pl-3"> Inscrit le: </span>
                    </li>
                    <li
                        v-for="(inscription, index) in inscriptions"
                        class="py-2.5 flex justify- items-center text-sm font-medium hover:bg-[#b7b7b7]"
                        :class="index % 2 == 0 ? 'bg-white' : 'bg-[#cecece]'"
                    >
                        <span class="flex-[1.2] pl-3">
                            {{ inscription.moyen }}</span
                        >
                        <span class="flex-[1.2] pl-3">
                            {{ inscription.operateur }}
                        </span>
                        <span class="flex-[1.2] pl-3">
                            {{ inscription.numeroBeneficiare }}</span
                        >
                        <span class="flex-[1.2] pl-3">
                            {{ ucfirst(inscription.moyen) }}</span
                        >
                        <span class="flex-[1.2] pl-3 text-green-500">
                            {{ inscription.montant }} cfa  </span
                        >
                        <span class="flex-[1.2] pl-3">
                            {{ inscription.created_at.split("T")[0] }}</span
                        >
                    </li>
                </ul>
            </div>
        </div>
        <Modal :show="showCreateModal" @close="showCreateModal = false">
            <form class="p-10" @submit.prevent="createpelerinage">
                <span class="text-2xl font-medium"
                    >Créer l'évènement du pélerinage</span
                >
                <div class="mt-4">
                    <InputLabel for="edition" value="Année" />
                    <select
                        required
                        class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                        v-model="createForm.edition"
                    >
                        <option v-for="year in years" :key="year" :value="year">
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
                        value="Date limite d'inscript(Marche)"
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
                    <InputLabel for="couverture" value="Image de couverture" />
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
        <Modal :show="showUpdateModal" @close="showUpdateModal = false">
            <form class="p-10" @submit.prevent="updatepelerinage">
                <span class="text-2xl font-medium"
                    >Modifier les informations</span
                >
                <div class="mt-4">
                    <InputLabel for="edition" value="Année" />
                    <select
                        required
                        class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                        v-model="updateForm.edition"
                    >
                        <option v-for="year in years" :key="year" :value="year">
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
                        value="Date limite d'inscript(Marche)"
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
                    <InputLabel for="couverture" value="Image de couverture" />
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
