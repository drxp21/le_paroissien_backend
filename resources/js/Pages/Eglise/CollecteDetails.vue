<script setup>
import { Head } from "@inertiajs/vue3";
import { onMounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Chart, registerables } from "chart.js";
import { ref } from "vue";

let montantCollecte = ref(0);
const props = defineProps({
    collecte: "",
    participations: "",
});
onMounted(() => {
    Chart.register(...registerables);
    let myChart = new Chart("myChart", {
        type: "doughnut",
        data: {
            labels: ["label", "label2"],
            datasets: [
                {
                    type: "doughnut",
                    backgroundColor: ["blue", "red"],
                    data: [5, 3],
                },
            ],
        },
        options: {
            rotation: -90,
            circumference: 180,
            responsive: false,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
                title: {
                    display: false,
                },
            },
        },
    });
    // montantCollecte.value=
    props.participations.map((s) => (montantCollecte.value += parseInt(s.montant)));
});
</script>
<template>
    <AppLayout>
        <div class="px-20 grid grid-cols-2 gap-5">
            <span class="text-xl font-semibold col-span-2">
                {{ collecte.titre }}</span
            >
            <div class="grid grid-cols-2">
                <div>
                    <canvas id="myChart"></canvas>
                </div>
                <div class="flex flex-col">
                    <span> Date de démarrage: {{ collecte.date_debut }} </span>
                    <span> Date de clôture: {{ collecte.date_cloture }} </span>
                    <span> Montant objectif: {{ collecte.objectif }} cfa</span>
                    <span> Montant minimum: {{ collecte.minimum }} cfa</span>
                    <span>Montant collecté: {{ montantCollecte }} cfa</span>
                </div>
            </div>
            <div>
                {{ participations }}
            </div>
        </div>
    </AppLayout>
</template>
