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
    intention: "",
    date: "",
    heure: "",
    type: "",
});
let updateForm = useForm({
    id: "",
    intention: "",
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
    updateForm.date = demandemesse.date;
    updateForm.heure = demandemesse.heure;
    updateForm.type = demandemesse.type;
    showUpdateModal.value = true;
};
const createdemandemesse = () => {
    createForm.post(route("demandemesses.store"), {
        onSuccess: () => {
            createForm.reset();
            showCreateModal.value = false;
        },
    });
};
const updatedemandemesse = () => {
    updateForm.put(route("demandemesses.update", updateForm), {
        onSuccess: () => {
            updateForm.reset();
            showUpdateModal.value = false;
        },
    });
};
const props = defineProps({
    demandemesses: "",
});
</script>
<template>
    <AppLayout>
        <Head title="Disponi" />
        <div class="px-20">
            <div>Gestion des demandes de messes</div>
            <PrimaryButton @click="showCreateModal = true">
                Renseigner une demande physique
            </PrimaryButton>
            <ul class="mt-10">
                <li
                    class="bg-[#cecece] rounded-t-lg py-3 flex justify-evenly items-center text-sm font-medium text-start"
                >
                    <span class="flex-[3] pl-3"> Intention</span>
                    <span class="flex-[2] pl-3"> Date</span>
                    <span class="flex-[2] pl-3">Heure </span>
                    <span class="flex-[2] pl-3">Type </span>
                    <span class="flex-[3] pl-3">Action</span>
                </li>
                <li
                    v-for="(demandemesse, index) in demandemesses"
                    class="py-2.5 flex justify- items-center text-sm font-medium hover:bg-[#b7b7b7] text-start"
                    :class="index % 2 == 0 ? 'bg-white' : 'bg-[#cecece]'"
                >
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
                    <div class="flex-[3] flex gap-3 text-white justify-start">
                        <button
                            class="bg-blue-700 py-2 px-4 self-start rounded-lg"
                            @click="prepareUpdate(demandemesse)"
                        >
                            Modifier
                        </button>
                        <button
                            @click="
                                () => (
                                    (deleteForm.id = demandemesse.id),
                                    deleteForm.delete(
                                        route(
                                            'demandemesses.destroy',
                                            demandemesse.id
                                        )
                                    )
                                )
                            "
                            :disabled="deleteForm.processing"
                            class="bg-red-700 px-4 py-2 self-start rounded-lg flex gap-0.5 items-center transition-all"
                        >
                            Supprimer
                            <div
                                v-if="
                                    deleteForm.processing &&
                                    deleteForm.id == demandemesse.id
                                "
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
        <form class="p-10" @submit.prevent="createdemandemesse">
            <span class="text-2xl font-medium"
                >Renseigner une demande physique</span
            >
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
                    class="mt-1 block w-full"
                    required
                    autocomplete="date"
                />
                <InputError class="mt-2" :message="createForm.errors.date" />
            </div>
            <div class="mt-4">
                <InputLabel for="heure" value="Heure de la messe" />
                <TextInput
                    id="heure"
                    v-model="createForm.heure"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autocomplete="heure"
                />
                <InputError class="mt-2" :message="createForm.errors.heure" />
            </div>
            <div class="mt-4">
                <InputLabel for="type" value="Type de demande de messe" />
                <select
                    class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                    v-model="createForm.type"
                >
                    <option value="Simple ">Simple</option>
                    <option value="Neuvaine">Neuvaine</option>
                    <option value="Trentaine">Trentaine</option>
                </select>
                <InputError class="mt-2" :message="createForm.errors.type" />
                <br />
                <span v-if="createForm.type == 'Simple'" class="text-slate-400">
                    4000 cfa</span
                >
                <span
                    v-if="createForm.type == 'Neuvaine'"
                    class="text-slate-400"
                >
                    9000 cfa</span
                >
                <span
                    v-if="createForm.type == 'Trentaine'"
                    class="text-slate-400"
                >
                    150000 cfa</span
                >
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
        <form class="p-10" @submit.prevent="updatedemandemesse">
            <span class="text-2xl font-medium"
                >Renseigner une demande physique</span
            >
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
                    class="mt-1 block w-full"
                    required
                    autocomplete="date"
                />
                <InputError class="mt-2" :message="updateForm.errors.date" />
            </div>
            <div class="mt-4">
                <InputLabel for="heure" value="Heure de la messe" />
                <TextInput
                    id="heure"
                    v-model="updateForm.heure"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autocomplete="heure"
                />
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
                    9000 cfa</span
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
