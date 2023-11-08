<script setup>
import { ref } from "vue";
import AppLayout from "../../Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/Modal.vue";
import { Head } from "@inertiajs/vue3";

let showCreateModal = ref(false);
let showDelModal = ref(false);

const props = defineProps({
    admins: "",
});
const deleteForm = useForm({
    id: "",
    name: "",
});
const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    terms: false,
});
const submit = () => {
    form.post(route("create_admin"), {
        onSuccess: () => {
            form.reset("password", "password_confirmation");
            showCreateModal.value = false;
        },
    });
};
</script>

<template>
    <AppLayout>
        <Head title="Utilisateurs" />

        <div class="px-20 mt-10">
            <PrimaryButton @click="showCreateModal = true">
                Ajouter un super-admin
            </PrimaryButton>

            <ul class="mt-10">
                <li
                    class="bg-[#cecece] rounded-t-lg py-3 flex justify-evenly items-center text-sm font-medium text-start"
                >
                    <span class="flex-[3] pl-3">Nom</span>
                    <span class="flex-[3] pl-3"> Email</span>
                    <span class="flex-[3] pl-3">Ajouté le </span>
                    <span class="flex-[3] pl-3">Action</span>
                </li>
                <li
                    v-if="admins.length > 0"
                    v-for="(user, index) in admins"
                    class="py-2.5 flex justify- items-center text-sm font-medium hover:bg-[#b7b7b7] text-start"
                    :class="index % 2 == 0 ? 'bg-white' : 'bg-[#cecece]'"
                >
                    <span class="flex-[3] pl-3">
                        {{ user.name }}
                    </span>
                    <span class="flex-[3] pl-3">
                        {{ user.email }}
                    </span>
                    <span class="flex-[3] pl-3">
                        {{
                            new Date(user.created_at).toLocaleDateString(
                                "fr-FR",
                                {
                                    year: "numeric",
                                    month: "long",
                                    day: "numeric",
                                }
                            )
                        }}
                    </span>
                    <div class="flex-[3] flex gap-3 text-white justify-start">
                        <button
                            @click="
                                () => (
                                    (showDelModal = true),
                                    (deleteForm.id = user.id),
                                    (deleteForm.name = user.name)
                                )
                            "
                            :disabled="deleteForm.processing"
                            class="bg-red-700 px-4 py-2 self-start rounded-lg flex gap-0.5 items-center transition-all"
                        >
                            Supprimer
                        </button>
                    </div>
                </li>
            </ul>
        </div>
        <Modal :show="showCreateModal" @close="showCreateModal = false">
            <form @submit.prevent="submit" class="p-10">
                <div>
                    <InputLabel for="name" value="Nom" />
                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        autocomplete="name"
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="email" value="Email" />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full"
                        required
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Mot de passe" />
                    <TextInput
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                        required
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4">
                    <InputLabel
                        for="password_confirmation"
                        value="Confirmation"
                    />
                    <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        required
                        autocomplete="new-password"
                    />
                    <InputError
                        class="mt-2"
                        :message="form.errors.password_confirmation"
                    />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton
                        class="ml-4"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Valider
                    </PrimaryButton>
                </div>
            </form>
        </Modal>
        <Modal :show="showDelModal" @close="showDelModal = false">
            <form
                class="p-10"
                @submit.prevent="
                    () => (
                        deleteForm.delete(route('delete_admin', deleteForm.id)),
                        (showDelModal = false)
                    )
                "
            >
                <span class="text-2xl font-medium"
                    >Supprimer le super-admin
                </span>
                <div class="mt-4">
                    Vous allez supprimer super-admin {{ deleteForm.name }} !
                </div>

                <div class="mt-4">
                    <button
                        type="submit"
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        Confirmer
                        <div
                            v-if="deleteForm.processing"
                            style="border-top-color: transparent"
                            class="ml-2 w-3 h-3 border-2 border-white border-solid rounded-full animate-spin bg-red-600"
                        ></div>
                    </button>
                </div>
            </form>
        </Modal>
    </AppLayout>
</template>
