<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import PencilIcon from "vue-material-design-icons/PencilOutline.vue";
import PlusIcon from "vue-material-design-icons/Plus.vue";
import Modal from "@/Components/Modal.vue";
import { downloadCsv } from "@/toolbox";

import { computed, ref } from "vue";

const props = defineProps({
    collectes: "",
});
let csvData = ref([]);

let showCreateModal = ref(false);
let previewImageUrl = ref(false);
const createForm = useForm({
    titre: "",
    description: "",
    minimum: "0",
    date_debut: "",
    date_cloture: "",
    objectif: "",
    toutlemonde: "",
    couverture: "",
});
const previewFile = (event) => {
    let file = "";
    event.target ? (file = event.target.files[0]) : (file = event);
    console.log(file.name);
    const reader = new FileReader();

    reader.onload = (event) => {
        createForm.couverture = reader.result.split(",")[1];
        previewImageUrl.value = event.target.result;
    };

    reader.readAsDataURL(file);
};
const createcollecte = () => {
    createForm.post(route("collectes.store"), {
        onSuccess: () => {
            createForm.reset();
            previewImageUrl.value = false;
            showCreateModal.value = false;
        },
    });
};
const pourcentage = (total, reunis) => {
    return (reunis * 100) / total;
};
const echeance = (date) => {
    const currentDate = new Date();
    let targetDate = new Date(date);
    // Calculate the difference in milliseconds
    const timeDifference = targetDate.getTime() - currentDate.getTime();

    // Convert milliseconds to days
    const daysRemaining = Math.ceil(timeDifference / (1000 * 3600 * 24));

    return daysRemaining;
};
const createCsv = () => {
    for (let demande of props.demandemesses) {
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
</script>
<template>
    <AppLayout>
        <Head title="Collecte de fonds" />
        <div class="px-10 mt-10">
            <PrimaryButton @click="showCreateModal = true">
                Créer une collecte de fonds
            </PrimaryButton>
            <!-- <button
            v-if="collectes.length > 0"
            class="ml-2 inline-flex items-center px-4 py-2 bg-teal-800 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-teal-700 focus:bg-teal-700 active:bg-teal-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150"
            @click="createCsv"
            >
            Télécharger en csv
        </button> -->
    </div>
        <div class="overflow-auto px-10">
            <table class="text-xs font-light table-auto">
                <thead class="border-b font-medium">
                    <tr>
                        <th scope="col" class="px-6 py-4">Numéro</th>
                        <th scope="col" class="px-6 py-4">Début</th>
                        <th scope="col" class="px-6 py-4">Intitulé</th>
                        <th scope="col" class="px-6 py-4">Details</th>
                        <th scope="col" class="px-6 py-4">Montant cible</th>
                        <th scope="col" class="px-6 py-4">Date de clôture</th>
                        <th scope="col" class="px-6 py-4">Participants</th>
                        <th scope="col" class="px-6 py-4">Minimum de participation</th>
                        <th scope="col" class="px-6 py-4 capitalize">
                            échéance
                        </th>
                        <th scope="col" class="px-6 py-4">Montant collécté</th>
                        <th scope="col" class="px-6 py-4">
                            Taux de réalisation
                        </th>
                        <th scope="col" class="px-6 py-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-if="collectes.length > 0"
                        v-for="(collecte, index) in collectes"
                        class="py-3 font-medium text-start hover:bg-[#b7b7b7]"
                        :class="index % 2 == 0 ? 'bg-white' : 'bg-[#cecece]'"
                    >
                        <td class="px-6 py-4">{{ collecte.id }}</td>
                        <td class="px-6 py-4">{{ collecte.date_debut }}</td>
                        <td class="px-6 py-4">{{ collecte.titre }}</td>
                        <td class="px-6 py-4">
                          {{ collecte.description }}
                        </td>
                        <td class="px-6 py-4">{{ collecte.objectif }} cfa</td>
                        <td class="px-6 py-4">{{ collecte.date_cloture }}</td>
                        <td class="px-6 py-4">
                            {{
                                collecte.toutlemonde
                                    ? "Tous le monde"
                                    : "Mes paroissiens"
                            }}
                        </td>
                        <td class="px-6 py-4">{{ collecte.minimum }} cfa</td>
                        <td class="px-6 py-4">
                            -{{ echeance(collecte.date_cloture) }} jours
                        </td>
                        <td class="px-6 py-4">{{ collecte.reunis }} cfa</td>
                        <td class="px-6 py-4">
                            {{
                                pourcentage(collecte.objectif, collecte.reunis)
                            }}
                            %
                        </td>
                        <td class="px-6 py-4 text-white">
                            <Link
                                :href="route('collectes.show', collecte.id)"
                                class="bg-blue-700 py-2 px-4 self-start rounded-lg"
                            >
                                Détails
                            </Link>
                        </td>
                    </tr>
                    <tr v-else>
                        <td colspan="12">Aucune collecte pour le moment</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
    <Modal :show="showCreateModal" @close="showCreateModal = false">
        <form class="p-10" @submit.prevent="createcollecte">
            <span class="text-2xl font-medium"
                >Créer une nouvelle collecte de fonds</span
            >
            <div class="mt-4">
                <InputLabel for="titre" value="Intitulé" />
                <TextInput
                    id="titre"
                    v-model="createForm.titre"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autocomplete="titre"
                />
                <InputError class="mt-2" :message="createForm.errors.titre" />
            </div>
            <div class="mt-4">
                <InputLabel for="description" value="Détails" />
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
                    for="minimum"
                    value="Montant minimum de participation"
                />
                <TextInput
                    id="minimum"
                    v-model="createForm.minimum"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autocomplete="minimum"
                />
                <InputError class="mt-2" :message="createForm.errors.minimum" />
            </div>
            <div class="mt-4">
                <InputLabel for="objectif" value="Montant visé" />
                <TextInput
                    id="objectif"
                    v-model="createForm.objectif"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autocomplete="objectif"
                />
                <InputError
                    class="mt-2"
                    :message="createForm.errors.objectif"
                />
            </div>
            <div class="mt-4">
                <InputLabel
                    for="date_debut"
                    value="Date de début de la collecte"
                />
                <TextInput
                    id="date_debut"
                    v-model="createForm.date_debut"
                    type="date"
                    class="mt-1 block w-full"
                    required
                    autocomplete="date_debut"
                />
                <InputError
                    class="mt-2"
                    :message="createForm.errors.date_debut"
                />
            </div>
            <div class="mt-4">
                <InputLabel
                    for="date_cloture"
                    value="Date de clôture de la collecte"
                />
                <TextInput
                    id="date_cloture"
                    v-model="createForm.date_cloture"
                    type="date"
                    class="mt-1 block w-full"
                    required
                    autocomplete="date_cloture"
                />
                <InputError
                    class="mt-2"
                    :message="createForm.errors.date_cloture"
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
                <InputLabel for="toutlemonde" value="Qui peut participer ?" />
                <select
                    required
                    class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                    v-model="createForm.toutlemonde"
                >
                    <option :value="true">Tout le monde</option>
                    <option :value="false">Mes paroissiens</option>
                    <InputError
                        class="mt-2"
                        :message="createForm.errors.toutlemonde"
                    />
                </select>
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
</template>
