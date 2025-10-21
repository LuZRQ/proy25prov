document.addEventListener("DOMContentLoaded", () => {
    const ticketWidth = document.getElementById('ticketWidth');
    const ticket = document.getElementById('ticket');
    const ticketWidthValue = document.getElementById('ticketWidthValue');
    const btnPDF = document.getElementById('btnPDF');
    const btnPrint = document.getElementById('btnPrint');

    // Ajustar ancho del ticket
    ticketWidth.addEventListener('input', (e) => {
        const value = e.target.value;
        ticket.style.maxWidth = value + 'cm';
        ticketWidthValue.textContent = value;
    });

    // Descargar PDF con tamaño en cm
    btnPDF.addEventListener('click', () => {
        const width = parseFloat(ticketWidth.value);
        const height = ticket.offsetHeight / 37.8; // px -> cm
        html2pdf().set({
            margin: 0,
            filename: 'Recibo_Venta_{{ $venta->idVenta }}.pdf',
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'cm', format: [height, width] }
        }).from(ticket).save();
    });

    // Imprimir solo ticket
    btnPrint.addEventListener('click', () => {
        const ticketHTML = ticket.outerHTML;
        const printWindow = window.open('', '', 'width=400,height=600');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Imprimir Ticket</title>
                    <style>
                        body { font-family: 'Roboto Mono', monospace; padding: 10px; }
                        @page { margin: 0; }
                        body * { visibility: hidden; }
                        #printTicket { visibility: visible; margin: auto; }
                    </style>
                </head>
                <body>
                    <div id="printTicket" style="width:${ticketWidth.value}cm;">
                        ${ticketHTML}
                    </div>
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    });
});