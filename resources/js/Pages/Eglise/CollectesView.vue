<script setup>
import { Head, Link,useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import PencilIcon from "vue-material-design-icons/PencilOutline.vue";
import PlusIcon from "vue-material-design-icons/Plus.vue";
import Modal from "@/Components/Modal.vue";
import { reactive, ref } from "vue";


const props = defineProps({
    collectes: "",
});
let showCreateModal = ref(false);
let previewImageUrl = ref(false);
const createForm = useForm({
    titre: "",
    description: "",
    minimum: "0",
    date_debut: "",
    date_cloture: "",
    objectif: "",
    toutlemonde:"",
    couverture: "",
});
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
const createcollecte = () => {
    createForm.post(route("collectes.store"), {
        onSuccess: () => {
            createForm.reset();
            previewImageUrl.value = false;
            showCreateModal.value = false;
        },
    });
};
</script>
<template>
    <AppLayout>
        <Head title="Collectes" />
        <div>Liste des collectes de fonds</div>
        <PrimaryButton @click="showCreateModal = true">
            Créer une collecte de fonds
        </PrimaryButton>

        <div class="pt-10 px-20 grid grid-cols-4 gap-5">
            <Link :href="route('collectes.show',collecte)"
                v-for="(collecte, index) in collectes"
                :key="index"
                class="border shadow-sm rounded-lg flex items-end p-3"
                style="min-height: 144px"
            >
                <span
                    class="self-end whitespace-nowrap overflow-hidden overflow-ellipsis font-semibold text-slate-900"
                    >{{ collecte.titre }}</span
                >
            </Link>
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
