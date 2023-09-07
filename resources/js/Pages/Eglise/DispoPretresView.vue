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
    nomPretre: "",
    jour: "",
    heureDebut: "",
    heureFin: "",
});
let updateForm = useForm({
    id: "",
    nomPretre: "",
    jour: "",
    heureDebut: "",
    heureFin: "",
});
let deleteForm = useForm({
    id: "",
});

const prepareUpdate = (dispopretre) => {
    updateForm.id =dispopretre.id
    updateForm.nomPretre =dispopretre.nomPretre
    updateForm.jour =dispopretre.jour
    updateForm.heureDebut =dispopretre.heureDebut
    updateForm.heureFin =dispopretre.heureFin
    showUpdateModal.value = true;
};
const createdispopretre = () => {
    createForm.post(route("dispopretres.store"), {
        onSuccess: () => {
            createForm.reset();
            showCreateModal.value = false;
        },
    });
};
const updatedispopretre = () => {
    updateForm.put(route("dispopretres.update", updateForm), {
        onSuccess: () => {
            updateForm.reset();
            showUpdateModal.value = false;
        },
    });
};
const props = defineProps({
    dispopretres: "",
});
</script>
<template>
    <AppLayout>
        <Head title="Disponi" />
        <div class="px-20">
            <div>Gestion des disponibilité des pretres</div>
            <PrimaryButton @click="showCreateModal = true">
                Renseigner un disponibilité
            </PrimaryButton>
            <ul class="mt-10">
                <li
                    class="bg-[#cecece] rounded-t-lg py-3 flex justify-evenly items-center text-sm font-medium text-start"
                >
                    <span class="flex-[3] pl-3"> Nom du pretre </span>
                    <span class="flex-[2] pl-3"> Jour</span>
                    <span class="flex-[2] pl-3">De </span>
                    <span class="flex-[2] pl-3 uppercase">à </span>
                    <span class="flex-[3] ">Action</span>
                </li>
                <li
                    v-for="(dispopretre, index) in dispopretres"
                    class="py-2.5 flex justify- items-center text-sm font-medium hover:bg-[#b7b7b7] text-start"
                    :class="index % 2 == 0 ? 'bg-white' : 'bg-[#cecece]'"
                >
                    <span class="flex-[3] pl-3">
                        {{ dispopretre.nomPretre }}
                    </span>
                    <span class="flex-[2] pl-3"> {{ dispopretre.jour }} </span>
                    <span class="flex-[2] pl-3">
                        {{ dispopretre.heureDebut }} h</span
                    >
                    <span class="flex-[2]"> {{ dispopretre.heureFin }} h</span>
                    <div class="flex-[3] flex gap-3 text-white justify-start">
                        <button
                            class="bg-blue-700 py-2 px-4 self-start rounded-lg"
                            @click="prepareUpdate(dispopretre)"
                        >
                            Modifier
                        </button>
                        <button
                            @click="()=>(
                                deleteForm.id=dispopretre.id,
                                deleteForm.delete(
                                    route(
                                        'dispopretres.destroy',
                                        dispopretre.id
                                    )
                                ))
                            "
                            :disabled="deleteForm.processing"
                            class="bg-red-700 px-4 py-2 self-start rounded-lg flex gap-0.5 items-center transition-all "
                        >
                            Supprimer
                            <div
                            v-if="deleteForm.processing && deleteForm.id==dispopretre.id"
                        style="border-top-color: transparent"
                        class="ml-2 w-3 h-3 border-2 border-white border-solid rounded-full animate-spin"
                    ></div>
                        </button>
                    </div>
                </li>
            </ul>
        </div>
    </AppLayout>
    <Modal :show="showCreateModal" @close="showCreateModal = false">
        <form class="p-10" @submit.prevent="createdispopretre">
            <span class="text-2xl font-medium"
                >Renseigner un disponibilité</span
            >
            <div class="mt-4">
                <InputLabel for="nomPretre" value="Nom du pretre " />
                <TextInput
                    id="nomPretre"
                    v-model="createForm.nomPretre"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autocomplete="nomPretre"
                />
                <InputError
                    class="mt-2"
                    :message="createForm.errors.nomPretre"
                />
            </div>
            <div class="mt-4">
                <InputLabel for="jour" value="Jour de la semaine" />
                <select
                    class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                    v-model="createForm.jour"
                >
                    <option value="Lundi">Lundi</option>
                    <option value="Mardi">Mardi</option>
                    <option value="Mercredi">Mercredi</option>
                    <option value="Jeudi">Jeudi</option>
                    <option value="Vendredi">Vendredi</option>
                    <option value="Samedi">Samedi</option>
                    <option value="Dimanche">Dimanche</option>
                </select>
                <InputError class="mt-2" :message="createForm.errors.jour" />
            </div>
            <div class="mt-4 flex gap-5">
                <div>
                    <InputLabel for="heureDebut" value="Disponible de: " />
                    <select
                        class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                        v-model="createForm.heureDebut"
                    >
                        <option value="8">8 h</option>
                        <option value="9">9 h</option>
                        <option value="10">10 h</option>
                        <option value="11">11 h</option>
                        <option value="12">12 h</option>
                        <option value="13">13 h</option>
                        <option value="14">14 h</option>
                        <option value="15">15 h</option>
                        <option value="16">16 h</option>
                        <option value="17">17 h</option>
                    </select>
                    <InputError
                        class="mt-2"
                        :message="createForm.errors.heureDebut"
                    />
                </div>
                <div>
                    <InputLabel for="heureFin" value="à " />
                    <select
                        class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                        v-model="createForm.heureFin"
                    >
                        <option value="8">8 h</option>
                        <option value="9">9 h</option>
                        <option value="10">10 h</option>
                        <option value="11">11 h</option>
                        <option value="12">12 h</option>
                        <option value="13">13 h</option>
                        <option value="14">14 h</option>
                        <option value="15">15 h</option>
                        <option value="16">16 h</option>
                        <option value="17">17 h</option>
                    </select>
                    <InputError
                        class="mt-2"
                        :message="createForm.errors.heureFin"
                    />
                </div>
            </div>
            <div class="mt-4">
                <PrimaryButton>
                    Renseigner
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
        <form class="p-10" @submit.prevent="updatedispopretre">
            <span class="text-2xl font-medium"
                >Modifier un disponibilité</span
            >
            <div class="mt-4">
                <InputLabel for="nomPretre" value="Nom du pretre " />
                <TextInput
                    id="nomPretre"
                    v-model="updateForm.nomPretre"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autocomplete="nomPretre"
                />
                <InputError
                    class="mt-2"
                    :message="updateForm.errors.nomPretre"
                />
            </div>
            <div class="mt-4">
                <InputLabel for="jour" value="Jour de la semaine" />
                <select
                    class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                    v-model="updateForm.jour"
                >
                    <option value="Lundi">Lundi</option>
                    <option value="Mardi">Mardi</option>
                    <option value="Mercredi">Mercredi</option>
                    <option value="Jeudi">Jeudi</option>
                    <option value="Vendredi">Vendredi</option>
                    <option value="Samedi">Samedi</option>
                    <option value="Dimanche">Dimanche</option>
                </select>
                <InputError class="mt-2" :message="updateForm.errors.jour" />
            </div>
            <div class="mt-4 flex gap-5">
                <div>
                    <InputLabel for="heureDebut" value="Disponible de: " />
                    <select
                        class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                        v-model="updateForm.heureDebut"
                    >
                        <option value="8">8 h</option>
                        <option value="9">9 h</option>
                        <option value="10">10 h</option>
                        <option value="11">11 h</option>
                        <option value="12">12 h</option>
                        <option value="13">13 h</option>
                        <option value="14">14 h</option>
                        <option value="15">15 h</option>
                        <option value="16">16 h</option>
                        <option value="17">17 h</option>
                    </select>
                    <InputError
                        class="mt-2"
                        :message="updateForm.errors.heureDebut"
                    />
                </div>
                <div>
                    <InputLabel for="heureFin" value="à " />
                    <select
                        class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                        v-model="updateForm.heureFin"
                    >
                        <option value="8">8 h</option>
                        <option value="9">9 h</option>
                        <option value="10">10 h</option>
                        <option value="11">11 h</option>
                        <option value="12">12 h</option>
                        <option value="13">13 h</option>
                        <option value="14">14 h</option>
                        <option value="15">15 h</option>
                        <option value="16">16 h</option>
                        <option value="17">17 h</option>
                    </select>
                    <InputError
                        class="mt-2"
                        :message="updateForm.errors.heureFin"
                    />
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
</template>
