export const downloadCsv = (data, filename) => {
    const csvRows = [];

    data.forEach((row) => {
        const csvRow = Object.values(row).join(",");
        csvRows.push(csvRow);
    });

    const csvString = csvRows.join("\n");

    const blob = new Blob([csvString], { type: "text/csv" });

    const url = URL.createObjectURL(blob);

    const a = document.createElement("a");
    a.href = url;
    a.download = filename || "data.csv"; // Default filename if not provided
    a.style.display = "none";

    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);

    URL.revokeObjectURL(url);
};
