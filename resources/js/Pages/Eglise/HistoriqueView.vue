<script setup>
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import MazRadioButtons from "maz-ui/components/MazRadioButtons";

import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref } from "vue";

let showTitles = ref("t");
const props = defineProps({
    collectes: [],
    dons: [],
    pelerinages: [],
    demandes: [],
});
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
</script>
<template>
    <AppLayout>
        <Head title="Historique" />
        <div class="mt-10 flex justify-center">
            <MazRadioButtons
                v-model="showTitles"
                color="primary"
                :options="[
                    { value: 't', label: 'Tous' },
                    { value: 'c', label: 'Collectes de fonds' },
                    { value: 'd', label: 'Dons' },
                    { value: 'i', label: 'Inscriptions pélerinage' },
                    { value: 'm', label: 'Demandes de messes' },
                ]"
            />
        </div>

        <div class="overflow-auto px-10 pb-20">
            <ul class="mt-10" v-if="['t', 'c'].includes(showTitles)">
                <li
                    class="bg-[#cecece] rounded-t-lg py-3 text-sm font-medium text-start px-5"
                >
                    Participations collectes de fonds
                </li>
                <li
                    class="bg-[#cecece] py-3 flex justify-evenly items-center text-sm font-medium text-start"
                >
                    <span class="flex-[3] pl-3">Collecte de fonds </span>
                    <span class="flex-[3] pl-3"> Montant </span>
                    <span class="flex-[2] pl-3"> Date</span>
                </li>
                <li
                    v-if="collectes.length > 0"
                    v-for="(collecte, index) in collectes"
                    class="py-2.5 flex justify- items-center text-sm font-medium hover:bg-[#b7b7b7] text-start"
                    :class="index % 2 == 0 ? 'bg-white' : 'bg-[#cecece]'"
                >
                    <span class="flex-[3] pl-3">
                        {{ collecte.type }}
                    </span>
                    <span class="flex-[3] pl-3 text-green-600">
                        {{ addDots(collecte.montant) }} cfa
                    </span>
                    <span class="flex-[2] pl-3">
                        {{
                            new Date(collecte.date).toLocaleDateString(
                                "fr-FR",
                                {
                                    year: "numeric",
                                    month: "long",
                                    day: "numeric",
                                }
                            )
                        }}
                    </span>
                </li>
                <li
                    v-else
                    class="bg-[#b7b7b7] rounded-b-lg py-3 flex justify-evenly items-center text-sm font-medium text-start"
                >
                    Aucune paticipation de collecte pour le moment
                </li>
            </ul>
            <div class="p-0.5 w-full bg-gray-300 my-10" v-if="showTitles=='t'"></div>
            <ul v-if="['t', 'm'].includes(showTitles)" class="mt-10">
                <li
                    class="bg-[#cecece] rounded-t-lg py-3 text-sm font-medium text-start px-5"
                >
                    Demandes de messe
                </li>
                <li
                    class="bg-[#cecece] py-3 flex justify-evenly items-center text-sm font-medium text-start"
                >
                    <span class="flex-[3] pl-3">Type </span>
                    <span class="flex-[3] pl-3"> Montant </span>
                    <span class="flex-[2] pl-3"> Date</span>
                </li>
                <li
                    v-if="demandes.length > 0"
                    v-for="(demande, index) in demandes"
                    class="py-2.5 flex justify- items-center text-sm font-medium hover:bg-[#b7b7b7] text-start"
                    :class="index % 2 == 0 ? 'bg-white' : 'bg-[#cecece]'"
                >
                    <span class="flex-[3] pl-3">
                        {{ demande.type }}
                    </span>
                    <span class="flex-[3] pl-3 text-green-600">
                        {{ addDots(demande.montant) }} cfa
                    </span>
                    <span class="flex-[2] pl-3">
                        {{
                            new Date(demande.date).toLocaleDateString("fr-FR", {
                                year: "numeric",
                                month: "long",
                                day: "numeric",
                            })
                        }}
                    </span>
                </li>
                <li
                    v-else
                    class="bg-[#b7b7b7] rounded-b-lg py-3 flex justify-evenly items-center text-sm font-medium text-start"
                >
                    Aucune demande de messes pour le moment
                </li>
            </ul>
            <div class="p-0.5 w-full bg-gray-300 my-10" v-if="showTitles=='t'"></div>
            <ul v-if="['t', 'i'].includes(showTitles)" class="mt-10">
                <li
                    class="bg-[#cecece] rounded-t-lg py-3 text-sm font-medium text-start px-5"
                >
                    Inscription au pélerinage
                </li>
                <li
                    class="bg-[#cecece] py-3 flex justify-evenly items-center text-sm font-medium text-start"
                >
                    <span class="flex-[3] pl-3">Année </span>
                    <span class="flex-[3] pl-3"> Montant </span>
                    <span class="flex-[2] pl-3"> Date</span>
                </li>
                <li
                    v-if="pelerinages.length > 0"
                    v-for="(pelerinage, index) in pelerinages"
                    class="py-2.5 flex justify- items-center text-sm font-medium hover:bg-[#b7b7b7] text-start"
                    :class="index % 2 == 0 ? 'bg-white' : 'bg-[#cecece]'"
                >
                    <span class="flex-[3] pl-3">
                        {{ pelerinage.type }}
                    </span>
                    <span class="flex-[3] pl-3 text-green-600">
                        {{ addDots(pelerinage.montant) }} cfa
                    </span>
                    <span class="flex-[2] pl-3">
                        {{
                            new Date(pelerinage.date).toLocaleDateString(
                                "fr-FR",
                                {
                                    year: "numeric",
                                    month: "long",
                                    day: "numeric",
                                }
                            )
                        }}
                    </span>
                </li>
                <li
                    v-else
                    class="bg-[#b7b7b7] rounded-b-lg py-3 flex justify-evenly items-center text-sm font-medium text-start"
                >
                    Aucune inscription au pelerinage le moment
                </li>
            </ul>
            <div class="p-0.5 w-full bg-gray-300 my-10" v-if="showTitles=='t'"></div>

            <ul v-if="['t', 'd'].includes(showTitles)" class="mt-10">
                <li
                    class="bg-[#cecece] rounded-t-lg py-3 text-sm font-medium text-start px-5"
                >
                    Dons
                </li>
                <li
                    class="bg-[#cecece] py-3 flex justify-evenly items-center text-sm font-medium text-start"
                >
                    <span class="flex-[3] pl-3">Type </span>
                    <span class="flex-[3] pl-3"> Montant </span>
                    <span class="flex-[2] pl-3"> Date</span>
                </li>
                <li
                    v-if="dons.length > 0"
                    v-for="(don, index) in dons"
                    class="py-2.5 flex justify- items-center text-sm font-medium hover:bg-[#b7b7b7] text-start"
                    :class="index % 2 == 0 ? 'bg-white' : 'bg-[#cecece]'"
                >
                    <span class="flex-[3] pl-3 capitalize">
                        {{ don.type }}
                    </span>
                    <span class="flex-[3] pl-3 text-green-600">
                        {{ addDots(don.montant) }} cfa
                    </span>
                    <span class="flex-[2] pl-3">
                        {{
                            new Date(don.date).toLocaleDateString("fr-FR", {
                                year: "numeric",
                                month: "long",
                                day: "numeric",
                            })
                        }}
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
</template>
