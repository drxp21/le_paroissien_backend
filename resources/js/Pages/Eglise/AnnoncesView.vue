<script setup>
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import PencilIcon from "vue-material-design-icons/PencilOutline.vue";
import PlusIcon from "vue-material-design-icons/Plus.vue";
import Modal from "@/Components/Modal.vue";
import { ref } from "vue";

let showCreateModal = ref(false);
let showUpdateModal = ref(false);

let createForm = useForm({
    contenu: "",
    dateFin:''
});
let updateForm = useForm({
    id: "",
    dateFin:"",
    contenu: "",
});
let deleteForm = useForm({
    id: "",
});

const prepareUpdate = (annonce) => {
    updateForm.id = annonce.id;
    updateForm.contenu = annonce.contenu;
    updateForm.dateFin = annonce.dateFin;
    showUpdateModal.value = true;
};
const createannonce = () => {
    createForm.post(route("annonces.store"), {
        onSuccess: () => {
            createForm.reset();
            showCreateModal.value = false;
        },
    });
};
const updateannonce = () => {
    updateForm.put(route("annonces.update", updateForm), {
        onSuccess: () => {
            updateForm.reset();
            showUpdateModal.value = false;
        },
    });
};
const props = defineProps({
    annonces: "",
});
</script>
<template>
    <AppLayout>
        <Head title="Annonces" />
        <div class="px-20">
            <div>Gestion de annonces</div>
            <PrimaryButton @click="showCreateModal = true">
                Créer une annonce
            </PrimaryButton>
            <ul class="mt-10">
                <li
                    class="bg-[#cecece] rounded-t-lg py-3 flex justify-evenly items-center text-sm font-medium text-start"
                >
                    <span class="flex-[5] pl-3"> Contenu </span>
                    <span class="flex-[3]">Date de fin </span>
                    <span class="flex-[1]"> Crée le </span>
                    <span class="flex-[1]"> Actif </span>
                    <span class="flex-[3] text-center">Action </span>
                </li>
                <li
                    v-if="annonces.length > 0"
                    v-for="(annonce, index) in annonces"
                    class="py-2.5 flex justify- items-center text-sm font-medium hover:bg-[#b7b7b7]"
                    :class="index % 2 == 0 ? 'bg-white' : 'bg-[#cecece]'"
                >
                    <span class="flex-[5] pl-3"> {{ annonce.contenu }} </span>
                    <span class="flex-[3]">
                        {{ annonce.created_at.split("T")[0] }}</span
                    >
                    <span class="flex-[1]">
                        {{ annonce.created_at.split("T")[0] }}</span
                    >
                    <span class="flex-[1] text-center">
                        {{ annonce.actif ? '✅':'❌' }}</span
                    >
                    <div class="flex-[3] flex gap-3 text-white justify-center">
                        <button
                            class="bg-blue-700 py-2 px-4 self-start rounded-lg"
                            @click="prepareUpdate(annonce)"
                        >
                            Modifier
                        </button>
                        <button
                            @click="
                                deleteForm.delete(
                                    route('annonces.destroy', annonce.id)
                                )
                            "
                            :disabled="deleteForm.processing"
                            class="bg-red-700 px-4 py-2 self-start rounded-lg disabled:bg-slate-300"
                        >
                            Supprimer
                        </button>
                    </div>
                </li>
                <li
                    v-else
                    class="bg-[#b7b7b7] rounded-b-lg py-3 flex justify-evenly items-center text-sm font-medium text-start"
                >
                    Aucune inscription pour le moment
                </li>
            </ul>
        </div>
    </AppLayout>
    <Modal :show="showCreateModal" @close="showCreateModal = false">
        <form class="p-10" @submit.prevent="createannonce">
            <span class="text-2xl font-medium"> Créer une annonce </span>
            <div class="mt-4">
                <InputLabel for="contenu" value="Contenu de l'annonce" />
                <textarea
                    v-model="createForm.contenu"
                    class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full"
                    rows="3"
                ></textarea>
                <InputError class="mt-2" :message="createForm.errors.contenu" />
            </div>
            <div class="mt-4">
                <InputLabel for="dateFin" value="Date de fin de l'annonce" />
                <TextInput
                    id="dateFin"
                    v-model="createForm.dateFin"
                    type="date"
                    class="mt-1 block w-full"
                    required
                    autocomplete="dateFin"
                />
                <InputError class="mt-2" :message="createForm.errors.dateFin" />
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
        <form class="p-10" @submit.prevent="updateannonce">
            <span class="text-2xl font-medium"> Modifier une annonce </span>
            <div class="mt-4">
                <InputLabel for="contenu" value="Contenu de l'annonce" />
                <textarea
                    v-model="updateForm.contenu"
                    class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full"
                    rows="3"
                ></textarea>
                <InputError class="mt-2" :message="updateForm.errors.contenu" />
            </div>
            <div class="mt-4">
                <InputLabel for="dateFin" value="Date de fin de l'annonce" />
                <TextInput
                    id="dateFin"
                    v-model="updateForm.dateFin"
                    type="date"
                    class="mt-1 block w-full"
                    required
                    autocomplete="dateFin"
                />
                <InputError class="mt-2" :message="updateForm.errors.dateFin" />
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
