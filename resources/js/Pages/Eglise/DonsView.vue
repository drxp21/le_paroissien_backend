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
    type: "",
    montant: "",
    intention: "",
});
let updateForm = useForm({
    id: "",
    type: "",
    montant: "",
    intention: "",
});
let deleteForm = useForm({
    id: "",
});

const prepareUpdate = (don) => {
    updateForm.id = don.id;
    updateForm.intention = don.intention;
    updateForm.type = don.type;
    updateForm.montant = don.montant;
    showUpdateModal.value = true;
};
const createdon = () => {
    createForm.post(route("dons.store"), {
        onSuccess: () => {
            createForm.reset();
            showCreateModal.value = false;
        },
    });
};
const updatedon = () => {
    updateForm.put(route("dons.update", updateForm), {
        onSuccess: () => {
            updateForm.reset();
            showUpdateModal.value = false;
        },
    });
};
const donType = (type) => {
    const typeMap = {
        pretres: "Don aux prêtres",
        paroisse: "Soutenir une Paroisse",
        caritas: "Caritas",
        denier: "Denier de culte",
        dime: "Dîme",
        dieu: "Offrande à Dieu",
        autre: "Autres",
    };

    return typeMap[type];
};
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
const props = defineProps({
    dons: "",
});
</script>
<template>
    <AppLayout>
        <Head title="Dons" />
        <div class="px-20">
            <div class="mt-10">
            <PrimaryButton @click="showCreateModal = true">
                Renseigner un don physique
            </PrimaryButton>
        </div>
            <ul class="mt-10">
                <li
                    class="bg-[#cecece] rounded-t-lg py-3 flex justify-evenly items-center text-sm font-medium text-start"
                >
                    <span class="flex-[3] pl-3">Auteur </span>
                    <span class="flex-[3] pl-3">Type de don </span>
                    <span class="flex-[2] pl-3">Montant</span>
                    <span class="flex-[2] pl-3">Intention </span>
                    <span class="flex-[2] pl-3">Operateur</span>
                </li>
                <li
                    v-if="dons.length>0"
                    v-for="(don, index) in dons"
                    class="py-2.5 flex justify- items-center text-sm font-medium hover:bg-[#b7b7b7] text-start"
                    :class="index % 2 == 0 ? 'bg-white' : 'bg-[#cecece]'"
                >
                <span class="flex-[3] pl-3">
                        {{ don.auteur }}
                    </span>
                    <span class="flex-[3] pl-3">
                        {{ donType(don.type) }}
                    </span>
                    <span class="flex-[2] pl-3 text-green-500"> {{ addDots(don.montant) }} cfa </span>
                    <span class="flex-[2] pl-3"> {{ don.intention }}</span>
                    <span class="flex-[2] pl-3 capitalize">
                        {{ don.operateur ? don.operateur : "---" }}
                    </span>

                </li>
                <li
                        v-else
                        class="bg-[#b7b7b7] rounded-b-lg py-3 flex justify-evenly items-center text-sm font-medium text-start"
                    >
                        Aucun don pour le moment
                    </li>
            </ul>
        </div>
    </AppLayout>
    <Modal :show="showCreateModal" @close="showCreateModal = false">
        <form class="p-10" @submit.prevent="createdon">
            <span class="text-2xl font-medium">Renseigner un don physique</span>
            <div class="mt-4">
                <InputLabel for="type" value="Type de don" />
                <select
                    class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                    v-model="createForm.type"
                >
                    <option value="pretres">Don aux prêtres</option>
                    <option value="paroisse">Soutenir une Paroisse</option>
                    <option value="caritas">Caritas</option>
                    <option value="denier">Denier de culte</option>
                    <option value="dime">Dîme</option>
                    <option value="dieu">Offrande à Dieu</option>
                    <option value="autre">Autres</option>
                </select>
                <InputError class="mt-2" :message="createForm.errors.type" />
            </div>
            <div class="mt-4">
                <InputLabel for="intention" value="Intention" />
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
                <InputLabel for="montant" value="Montant" />
                <TextInput
                    id="montant"
                    v-model="createForm.montant"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autocomplete="montant"
                />
                <InputError class="mt-2" :message="createForm.errors.montant" />
            </div>
            <div class="mt-4">
                <InputLabel for="auteur" value="Nom du donneur (Laisser vite pour un don anonyme)" />
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
        <form class="p-10" @submit.prevent="updatedon">
            <span class="text-2xl font-medium">Renseigner un don physique</span>
            <div class="mt-4">
                <InputLabel for="type" value="Type de don" />
                <select
                    class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                    v-model="updateForm.type"
                >
                    <option value="pretres">Don aux prêtres</option>
                    <option value="paroisse">Soutenir une Paroisse</option>
                    <option value="caritas">Caritas</option>
                    <option value="denier">Denier de culte</option>
                    <option value="dime">Dîme</option>
                    <option value="dieu">Offrande à Dieu</option>
                    <option value="autre">Autres</option>
                </select>
                <InputError class="mt-2" :message="updateForm.errors.type" />
            </div>
            <div class="mt-4">
                <InputLabel for="intention" value="Intention" />
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
                <InputLabel for="montant" value="Montant" />
                <TextInput
                    id="montant"
                    v-model="updateForm.montant"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autocomplete="montant"
                />
                <InputError class="mt-2" :message="updateForm.errors.montant" />
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
