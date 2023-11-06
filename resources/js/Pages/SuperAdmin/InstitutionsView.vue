<script setup>
import AppLayout from "../../Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    institutions: "",
});
</script>

<template>
    <AppLayout>
        <Head title="Institutions" />

        <div>
            <div v-if="institutions.length > 0" class="px-10">
                <table class="text-xs font-light table-auto">
                    <thead class="border-b font-medium">
                        <tr>
                            <th scope="col" class="px-2 py-4 capitalize">
                                Statut
                            </th>
                            <th scope="col" class="px-2 py-4 capitalize">
                                Dénomination
                            </th>
                            <th scope="col" class="px-2 py-4 capitalize">
                                Archidiocèse/Diocèse
                            </th>
                            <th scope="col" class="px-2 py-4 capitalize">
                                Doyenné
                            </th>
                            <th scope="col" class="px-2 py-4 capitalize">
                                Adresse
                            </th>
                            <th scope="col" class="px-2 py-4 capitalize">
                                Téléphone Fixe
                            </th>
                            <th scope="col" class="px-2 py-4 capitalize">
                                Téléphone Mobile
                            </th>
                            <th scope="col" class="px-2 py-4 capitalize">
                                Prénom et nom du responsable
                            </th>
                            <th scope="col" class="px-2 py-4 capitalize">
                                Titre du responsable
                            </th>
                            <th scope="col" class="px-2 py-4 capitalize">
                                Email du responsable
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            class="py-3 font-medium text-start"
                            v-for="instit in institutions"
                            :key="instit.id"
                        >
                            <td class="px-2 py-4 capitalize text-center">
                                {{ instit.statut }}
                            </td>
                            <td class="px-2 py-4 capitalize text-center">
                                {{ instit.denomination }}
                            </td>
                            <td class="px-2 py-4 capitalize">
                                {{ instit.diocese ?? "---" }}
                            </td>
                            <td class="px-2 py-4 capitalize">
                                {{ instit.doyenne ?? "---" }}
                            </td>
                            <td class="px-2 py-4 capitalize text-center">
                                {{ instit.adresse }}
                            </td>
                            <td class="px-2 py-4 capitalize text-center">
                                {{ instit.telephonefixe ?? '---' }}
                            </td>
                            <td class="px-2 py-4 capitalize text-center">
                                {{ instit.telephonemobile ?? '---' }}
                            </td>
                            <td class="px-2 py-4 capitalize text-center">
                                {{ instit.prenomresp }}
                                {{ instit.nomresp }}
                            </td>
                            <td class="px-2 py-4 capitalize text-center">
                                {{ instit.titreresp }}
                            </td>
                            <td class="px-2 py-4 capitalize text-center">
                                {{ instit.emailresp ?? '---'}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-else class="p-5 text-slate-600 text-lg font-xl">
                Aucune institution pour le moment
            </div>
        </div>
    </AppLayout>
</template>
