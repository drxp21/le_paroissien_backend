<script setup>
import InputLabel from "../Components/InputLabel.vue";
import InputError from "../Components/InputError.vue";
import TextInput from "../Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import MazPhoneNumberInput from "maz-ui/components/MazPhoneNumberInput";

import { Head, Link, useForm } from "@inertiajs/vue3";
import Banner from "@/Components/Banner.vue";

let createForm = useForm({
    statut: "",
    denomination: "",
    diocese: "",
    doyenne: "",
    adresse: "",
    telephonefixe: "",
    telephonemobile: "",
    slogan: "",
    emailinstitution: "",
    nomresp: "",
    prenomresp: "",
    titreresp: "",
    emailresp: "",
    nomdemandeur: "",
    prenomdemandeur: "",
    fonctiondemandeur: "",
    telephonefixedemandeur: "",
    telephonemobiledemandeur: "",
    emaildemandeur: "",
});
</script>
<template>
    <Head title="Demande d'adhésion" />

    <form
        class="pb-20"
        @submit.prevent="createForm.post(route('institutions.store'))"
    >
        <div class="w-full px-20 pt-10 grid grid-cols-2 gap-5">
            <div class="my-1 col-span-2 font-bold text-lg">
                Informations sur l'institution
            </div>
            <div class="w-full">
                <div class="mt-4">
                    <InputLabel for="statut" value="Statut*" />
                    <select
                        required
                        autofocus
                        class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                        v-model="createForm.statut"
                    >
                        <option value="cathedrale">Cathédrale</option>
                        <option value="paroisse">Paroisse</option>
                        <option value="quasiparoisse">Quasi-Paroisse</option>
                        <option value="chapelle">Chapelle</option>
                    </select>
                    <InputError
                        class="mt-2"
                        :message="createForm.errors.statut"
                    />
                </div>
                <div
                    class="mt-4"

                >
                    <InputLabel for="diocese" value="Archidiocèse/Diocèse*" />
                    <select
                        class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                        v-model="createForm.diocese"
                        required
                    >
                        <option value="Dakar">Dakar</option>
                        <option value="Kaolack">Kaolack</option>
                        <option value="Kolda">Kolda</option>
                        <option value="Saint-Louis">Saint-Louis</option>
                        <option value="Tambacounda">Tambacounda</option>
                        <option value="Thiès">Thiès</option>
                        <option value="Zinguinchor">Zinguinchor</option>
                    </select>

                    <InputError
                        class="mt-2"
                        :message="createForm.errors.diocese"
                    />
                </div>
                <div
                    class="mt-4"

                >
                    <InputLabel for="doyenne" value="Doyenné*" />
                    <TextInput
                        id="doyenne"
                        v-model="createForm.doyenne"
                        type="text"
                        required
                        class="mt-1 block w-full"
                        autocomplete="doyenne"
                    />
                    <InputError
                        class="mt-2"
                        :message="createForm.errors.doyenne"
                    />
                </div>

                <div class="mt-4">
                    <InputLabel for="denomination" value="Dénomination*" />
                    <TextInput
                        id="denomination"
                        v-model="createForm.denomination"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autocomplete="denomination"
                    />
                    <InputError
                        class="mt-2"
                        :message="createForm.errors.denomination"
                    />
                </div>
                <div class="mt-4">
                    <InputLabel for="slogan" value="Slogan" />
                    <TextInput
                        id="slogan"
                        v-model="createForm.slogan"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="slogan"
                    />
                    <InputError
                        class="mt-2"
                        :message="createForm.errors.slogan"
                    />
                </div>

                <div class="mt-4">
                    <InputLabel for="adresse" value="Adresse Physique*" />
                    <TextInput
                        id="adresse"
                        v-model="createForm.adresse"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autocomplete="adresse"
                    />
                    <InputError
                        class="mt-2"
                        :message="createForm.errors.adresse"
                    />
                </div>
                <div class="mt-4">
                    <InputLabel for="telephonefixe" value="Téléphone Fixe" />

                    <MazPhoneNumberInput
                        defaultCountryCode="SN"
                        v-model="createForm.telephonefixe"
                        :translations="{
                            countrySelector: {
                                placeholder: 'Code pays',
                                error: 'Choisir le pays',
                            },
                            phoneInput: {
                                placeholder: 'Numéro de téléphone',
                                example: 'Exemple:',
                            },
                        }"
                    />

                    <InputError
                        class="mt-2"
                        :message="createForm.errors.telephonefixe"
                    />
                    <div class="mt-4">
                        <InputLabel
                            for="emailinstitution"
                            value="Adresse Mail Institution"
                        />
                        <TextInput
                            id="emailinstitution"
                            v-model="createForm.emailinstitution"
                            type="email"
                            class="mt-1 block w-full"
                            autocomplete="emailinstitution"
                        />
                        <InputError
                            class="mt-2"
                            :message="createForm.errors.emailinstitution"
                        />
                    </div>
                </div>
            </div>
            <div class="w-full">
                <div>
                    <InputLabel for="nomresp" value="Nom du responsable*" />
                    <TextInput
                        id="nomresp"
                        v-model="createForm.nomresp"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autocomplete="nomresp"
                    />
                    <InputError
                        class="mt-2"
                        :message="createForm.errors.nomresp"
                    />
                </div>
                <div class="mt-4">
                    <InputLabel
                        for="prenomresp"
                        value="Prénom du responsable*"
                    />
                    <TextInput
                        id="prenomresp"
                        v-model="createForm.prenomresp"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autocomplete="prenomresp"
                    />
                    <InputError
                        class="mt-2"
                        :message="createForm.errors.prenomresp"
                    />
                </div>
                <div class="mt-4">
                    <InputLabel for="titreresp" value="Titre du responsable*" />

                    <select
                        class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                        v-model="createForm.titreresp"
                        required
                    >
                        <option value="curee">Curée</option>
                        <option value="directeur">Directeur</option>
                    </select>
                </div>
                <div class="mt-4">
                    <InputLabel
                        for="telephonemobile"
                        value="Téléphone mobile*"
                    />
                    <MazPhoneNumberInput
                        defaultCountryCode="SN"
                        v-model="createForm.telephonemobile"
                        required
                        :translations="{
                            countrySelector: {
                                placeholder: 'Code pays',
                                error: 'Choisir le pays',
                            },
                            phoneInput: {
                                placeholder: 'Numéro de téléphone',
                                example: 'Exemple:',
                            },
                        }"
                    />

                    <InputError
                        class="mt-2"
                        :message="createForm.errors.telephonemobile"
                    />
                </div>
                <div class="mt-4">
                    <InputLabel
                        for="emailresp"
                        value="Adresse Mail du Responsable"
                    />
                    <TextInput
                        id="emailresp"
                        v-model="createForm.emailresp"
                        type="email"
                        class="mt-1 block w-full"
                        autocomplete="emailresp"
                    />
                    <InputError
                        class="mt-2"
                        :message="createForm.errors.emailresp"
                    />
                </div>
            </div>
        </div>
        <div class="w-full px-20 pt-10 grid grid-cols-2 gap-5">
            <div class="my-1 col-span-2 font-bold text-lg">
                Informations sur la personne ayant fait la demande
            </div>
            <div class="w-full">
                <div class="mt-4">
                    <InputLabel for="nomdemandeur" value="Nom*" />
                    <TextInput
                        id="nomdemandeur"
                        v-model="createForm.nomdemandeur"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autocomplete="nomdemandeur"
                    />
                    <InputError
                        class="mt-2"
                        :message="createForm.errors.nomdemandeur"
                    />
                </div>
                <div class="mt-4">
                    <InputLabel for="prenomdemandeur" value="Prénom(s)*" />
                    <TextInput
                        id="prenomdemandeur"
                        v-model="createForm.prenomdemandeur"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autocomplete="prenomdemandeur"
                    />
                    <InputError
                        class="mt-2"
                        :message="createForm.errors.prenomdemandeur"
                    />
                </div>

                <div class="mt-4">
                    <InputLabel for="fonctiondemandeur" value="Fonction*" />
                    <TextInput
                        id="fonctiondemandeur"
                        v-model="createForm.fonctiondemandeur"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autocomplete="fonctiondemandeur"
                    />
                    <InputError
                        class="mt-2"
                        :message="createForm.errors.fonctiondemandeur"
                    />
                </div>
                <div class="mt-4 flex gap-3 items-center">
                    <input type="checkbox" required />
                    <InputLabel
                        value="Je certifie l'exactitude des informations renseignées ci-dessus"
                    />
                </div>
            </div>
            <div class="w-full">
                <div class="mt-4">
                    <InputLabel
                        for="telephonefixedemandeur"
                        value="Téléphone Fixe"
                    />
                    <MazPhoneNumberInput
                        defaultCountryCode="SN"
                        v-model="createForm.telephonefixedemandeur"
                        :translations="{
                            countrySelector: {
                                placeholder: 'Code pays',
                                error: 'Choisir le pays',
                            },
                            phoneInput: {
                                placeholder: 'Numéro de téléphone',
                                example: 'Exemple:',
                            },
                        }"
                    />

                    <InputError
                        class="mt-2"
                        :message="createForm.errors.telephonefixedemandeur"
                    />
                </div>
                <div class="mt-4">
                    <InputLabel
                        for="telephonemobiledemandeur*"
                        value="Téléphone Mobile*"
                    />
                    <MazPhoneNumberInput
                        defaultCountryCode="SN"
                        v-model="createForm.telephonemobiledemandeur"
                        required
                        :translations="{
                            countrySelector: {
                                placeholder: 'Code pays',
                                error: 'Choisir le pays',
                            },
                            phoneInput: {
                                placeholder: 'Numéro de téléphone',
                                example: 'Exemple:',
                            },
                        }"
                    />

                    <InputError
                        class="mt-2"
                        :message="createForm.errors.telephonemobiledemandeur"
                    />
                </div>
                <div class="mt-4">
                    <InputLabel for="emaildemandeur" value="Adresse Mail*" />
                    <TextInput
                        id="emaildemandeur"
                        v-model="createForm.emaildemandeur"
                        type="email"
                        class="mt-1 block w-full"
                        required
                        autocomplete="emaildemandeur"
                    />
                    <InputError
                        class="mt-2"
                        :message="createForm.errors.emaildemandeur"
                    />
                </div>
            </div>

            <PrimaryButton
                type="submit"
                :disabled="createForm.processing"
                class="disabled:cursor-not-allowed"
            >
            envoyer la demande
            </PrimaryButton>
        </div>
    </form>
    <Banner />
</template>
