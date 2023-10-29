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
let previewImageUrl = ref(false);

let createForm = useForm({
    titre: "",
    date: "",
    description: "",
    couverture: "",
    frais: "",
});
let updateForm = useForm({
    id: "",
    titre: "",
    date: "",
    description: "",
    couverture: "",
    frais: "",
});
let deleteForm = useForm({
    id: "",
});

const previewFile = (event) => {
    let file = "";
    event.target ? (file = event.target.files[0]) : (file = event);
    console.log(file.name);
    const reader = new FileReader();

    reader.onload = (event) => {
        createForm.couverture = reader.result.split(",")[1];
        updateForm.couverture = reader.result.split(",")[1];
        previewImageUrl.value = event.target.result;
    };

    reader.readAsDataURL(file);
};
const prepareUpdate = (evenement) => {
    updateForm.id = evenement.id;
    updateForm.titre = evenement.titre;
    updateForm.date = evenement.date;
    updateForm.description = evenement.description;
    updateForm.frais = evenement.frais;
    updateForm.couverture = "";
    showUpdateModal.value = true;
};
const createevenement = () => {
    createForm.post(route("evenements.store"), {
        onSuccess: () => {
            createForm.reset();
            showCreateModal.value = false;
        },
    });
};
const updateevenement = () => {
    updateForm.put(route("evenements.update", updateForm), {
        onSuccess: () => {
            updateForm.reset();
            showUpdateModal.value = false;
        },
    });
};
const props = defineProps({
    evenements: "",
});
</script>
<template>
    <AppLayout>
        <Head title="évènements" class="capitalize" />
        <div class="px-20">
            <div>Gestion de évènements</div>
            <PrimaryButton @click="showCreateModal = true">
                Créer un évènement
            </PrimaryButton>
            <ul class="mt-10">
                <li
                    class="bg-[#cecece] rounded-t-lg py-3 flex justify-evenly items-center text-sm font-medium text-start"
                >
                    <span class="flex-[1.2]"> Couverture</span>
                    <span class="flex-[1.2]"> Intitulé </span>
                    <span class="flex-[1.2]"> Date </span>
                    <span class="flex-[1.2]"> Frais </span>
                    <span class="flex-[4] pl-3"> Déscription </span>
                    <span class="flex-[3] text-center">Action </span>
                </li>
                <li
                    v-if="evenements.length > 0"
                    v-for="(evenement, index) in evenements"
                    class="py-2.5 flex justify- items-center text-sm font-medium hover:bg-[#b7b7b7]"
                    :class="index % 2 == 0 ? 'bg-white' : 'bg-[#cecece]'"
                >
                    <span class="flex-[1.2]">
                        <img
                            class="h-10 rounded-xl object-cover"
                            :src="evenement.couverture_path"
                        />
                    </span>
                    <span class="flex-[1.2]"> {{ evenement.titre }}</span>
                    <span class="flex-[1.2]">
                        {{ evenement.date.split("T")[0] }}</span
                    >
                    <span class="flex-[1.2]"> {{ evenement.frais }}</span>
                    <span class="flex-[4] pl-3">
                        {{ evenement.description }}
                    </span>
                    <div class="flex-[3] flex gap-3 text-white justify-center">
                        <button
                            class="bg-blue-700 py-2 px-4 self-start rounded-lg"
                            @click="prepareUpdate(evenement)"
                        >
                            Modifier
                        </button>
                        <button
                            @click="
                                deleteForm.delete(
                                    route('evenements.destroy', evenement.id)
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
                    Aucun évènement pour le moment
                </li>
            </ul>
        </div>
    </AppLayout>
    <Modal :show="showCreateModal" @close="showCreateModal = false">
        <form class="p-10" @submit.prevent="createevenement">
            <span class="text-2xl font-medium"> Créer un évènement </span>
            <div class="mt-4">
                <InputLabel for="titre" value="Intitulé de l'évènement" />
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
                <InputLabel for="date" value="Date de l'évènement" />
                <TextInput
                    id="date"
                    v-model="createForm.date"
                    type="date"
                    class="mt-1 block w-full"
                    required
                    autocomplete="date"
                />
                <InputError class="mt-2" :message="createForm.errors.date" />
            </div>
            <div class="mt-4">
                <InputLabel
                    for="description"
                    value="Déscription de l'évènement"
                />
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
                <InputLabel for="frais" value="Frais d'inscription" />
                <TextInput
                    id="frais"
                    v-model="createForm.frais"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autocomplete="frais"
                />
                <InputError class="mt-2" :message="createForm.errors.frais" />
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
        <form class="p-10" @submit.prevent="updateevenement">
            <span class="text-2xl font-medium"> Modifier un évènement </span>
            <div class="mt-4">
                <InputLabel for="titre" value="Intitulé de l'évènement" />
                <TextInput
                    id="titre"
                    v-model="updateForm.titre"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autocomplete="titre"
                />
                <InputError class="mt-2" :message="updateForm.errors.titre" />
            </div>
            <div class="mt-4">
                <InputLabel for="date" value="Date de l'évènement" />
                <TextInput
                    id="date"
                    v-model="updateForm.date"
                    type="date"
                    class="mt-1 block w-full"
                    required
                    autocomplete="date"
                />
                <InputError class="mt-2" :message="updateForm.errors.date" />
            </div>
            <div class="mt-4">
                <InputLabel
                    for="description"
                    value="Déscription de l'évènement"
                />
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
                <InputLabel for="frais" value="Frais d'inscription" />
                <TextInput
                    id="frais"
                    v-model="updateForm.frais"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autocomplete="frais"
                />
                <InputError class="mt-2" :message="updateForm.errors.frais" />
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
                    :message="createForm.errors.couverture"
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
</template>
