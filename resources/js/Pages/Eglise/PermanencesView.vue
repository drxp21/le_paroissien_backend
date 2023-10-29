<script setup>
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useForm } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import PencilIcon from "vue-material-design-icons/PencilOutline.vue";
import PlusIcon from "vue-material-design-icons/Plus.vue";
import Modal from "@/Components/Modal.vue";
import axios from "axios";
import { ref } from "vue";

let processing = ref(false);
const props = defineProps({
    permanences_messe: "",
    permanences_confession: "",
    permanences_pretre: "",
    permanences_secretariat: "",
});
let permanences_messeForms = ref([]);
let permanences_confessionForms = ref([]);
let permanences_pretreForms = ref([]);
let permanences_secretariatForms = ref([]);
for (let permanence of props.permanences_messe) {
    permanences_messeForms.value.push(
        useForm({
            id: permanence.id,
            jour_id: permanence.jour_id,
            heureDebut: permanence.heureDebut,
            heureFin: permanence.heureFin,
            institution_id: permanence.institution_id,
        })
    );
}
for (let permanence of props.permanences_confession) {
    permanences_confessionForms.value.push(
        useForm({
            id: permanence.id,
            jour_id: permanence.jour_id,
            heureDebut: permanence.heureDebut,
            heureFin: permanence.heureFin,
            institution_id: permanence.institution_id,
        })
    );
}
for (let permanence of props.permanences_pretre) {
    permanences_pretreForms.value.push(
        useForm({
            id: permanence.id,
            jour_id: permanence.jour_id,
            heureDebut: permanence.heureDebut,
            heureFin: permanence.heureFin,
            institution_id: permanence.institution_id,
        })
    );
}
for (let permanence of props.permanences_secretariat) {
    permanences_secretariatForms.value.push(
        useForm({
            id: permanence.id,
            jour_id: permanence.jour_id,
            heureDebut: permanence.heureDebut,
            heureFin: permanence.heureFin,
            institution_id: permanence.institution_id,
        })
    );
}

const updatePermanences = async () => {
    processing.value = true;
    let promises = [];
    for (let form of permanences_messeForms.value) {
        promises.push(axios.put(route("permanencesmesse.update", form), form));
    }
    for (let form of permanences_confessionForms.value) {
        promises.push(
            axios.put(route("permanencesconfession.update", form), form)
        );
    }
    for (let form of permanences_pretreForms.value) {
        promises.push(
            axios.put(route("permanencespretres.update", form), form)
        );
    }
    for (let form of permanences_secretariatForms.value) {
        promises.push(
            axios.put(route("permanencessecretariat.update", form), form)
        );
    }
    Promise.all(promises).then(() => {
        window.location.reload();
    });
};
console.log(permanences_messeForms.value);
</script>
<template>
    <AppLayout>
        <Head title="Permanences" />
        <div class="px-20 mt-10">
            <PrimaryButton @click="updatePermanences" :disabled="processing">
                Mettre à jour &nbsp;
                <div
                    style="border-top-color: transparent"
                    v-if="processing"
                    class="w-4 h-4 border-2 border-white-400 border-solid rounded-full animate-spin"
                ></div>
            </PrimaryButton>
        </div>
        <div class="flex flex-col ">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 inline-block min-w-full ">
                    <div class="overflow-auto px-10">
                        <table class="min-w-full">
                            <thead class="bg-white border-b">
                                <tr>
                                    <th
                                        scope="col"
                                        class="text-sm font-medium text-gray-900 px-1 py-4 text-left"
                                    >
                                        #
                                    </th>
                                    <th
                                        scope="col"
                                        class="text-sm font-medium text-gray-900 px-1 py-4 text-left"
                                    >
                                        Lundi
                                    </th>
                                    <th
                                        scope="col"
                                        class="text-sm font-medium text-gray-900 px-1 py-4 text-left"
                                    >
                                        Mardi
                                    </th>
                                    <th
                                        scope="col"
                                        class="text-sm font-medium text-gray-900 px-1 py-4 text-left"
                                    >
                                        Mercredi
                                    </th>
                                    <th
                                        scope="col"
                                        class="text-sm font-medium text-gray-900 px-1 py-4 text-left"
                                    >
                                        Jeudi
                                    </th>
                                    <th
                                        scope="col"
                                        class="text-sm font-medium text-gray-900 px-1 py-4 text-left"
                                    >
                                        Vendredi
                                    </th>
                                    <th
                                        scope="col"
                                        class="text-sm font-medium text-gray-900 px-1 py-4 text-left"
                                    >
                                        Samedi
                                    </th>
                                    <th
                                        scope="col"
                                        class="text-sm font-medium text-gray-900 px-1 py-4 text-left"
                                    >
                                        Dimanche
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-gray-100 border-b">
                                    <td
                                        class="px-2 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                    >
                                        Heures de Messe
                                    </td>

                                    <td
                                        v-for="permanence in permanences_messeForms"
                                        class="text-sm text-gray-900 font-light px-1 py-4 whitespace-nowrap"
                                    >
                                        De
                                        <input
                                            type="time"
                                            class="p-0 text-xs rounded-md"
                                            v-model="permanence.heureDebut"
                                        />
                                        à
                                        <input
                                            type="time"
                                            class="p-0 text-xs rounded-md"
                                            v-model="permanence.heureFin"
                                        />
                                    </td>
                                </tr>
                                <tr class="bg-white border-b">
                                    <td
                                        class="px-2 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                    >
                                        Permanences Confession
                                    </td>
                                    <td
                                        v-for="permanence in permanences_confessionForms"
                                        class="text-sm text-gray-900 font-light px-1 py-4 whitespace-nowrap"
                                    >
                                        De
                                        <input
                                            type="time"
                                            class="p-0 text-xs rounded-md"
                                            v-model="permanence.heureDebut"
                                        />
                                        à
                                        <input
                                            type="time"
                                            class="p-0 text-xs rounded-md"
                                            v-model="permanence.heureFin"
                                        />
                                    </td>
                                </tr>
                                <tr class="bg-gray-100 border-b">
                                    <td
                                        class="px-2 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                    >
                                        Permanences Pretre
                                    </td>
                                    <td
                                        v-for="permanence in permanences_pretreForms"
                                        class="text-sm text-gray-900 font-light px-1 py-4 whitespace-nowrap"
                                    >
                                        De
                                        <input
                                            type="time"
                                            class="p-0 text-xs rounded-md"
                                            v-model="permanence.heureDebut"
                                        />
                                        à
                                        <input
                                            type="time"
                                            class="p-0 text-xs rounded-md"
                                            v-model="permanence.heureFin"
                                        />
                                    </td>
                                </tr>
                                <tr class="bg-white border-b">
                                    <td
                                        class="px-2 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                    >
                                        Permanences Secretariat
                                    </td>
                                    <td
                                        v-for="permanence in permanences_secretariatForms"
                                        class="text-sm text-gray-900 font-light px-1 py-4 whitespace-nowrap"
                                    >
                                        De
                                        <input
                                            type="time"
                                            class="p-0 text-xs rounded-md"
                                            v-model="permanence.heureDebut"
                                        />
                                        à
                                        <input
                                            type="time"
                                            class="p-0 text-xs rounded-md"
                                            v-model="permanence.heureFin"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
