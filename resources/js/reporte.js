// resources/js/reporte.js
import Chart from 'chart.js/auto';

function ready(fn) {
  if (document.readyState !== 'loading') fn();
  else document.addEventListener('DOMContentLoaded', fn);
}

ready(() => {
  const dataRoot = document.getElementById('data-reportes');
  if (!dataRoot) return;

  // ====== Datos desde Blade ======
  let top5 = [];
  let ventasSemana = [];

  try {
    top5 = JSON.parse(dataRoot.dataset.top5 || '[]');
    ventasSemana = JSON.parse(dataRoot.dataset.ventasSemana || '[]');
  } catch (e) {
    console.error('Error parseando datasets de reportes:', e);
    return;
  }

  // ====== Utilidades ======
  const fmtMonto = new Intl.NumberFormat('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

  // ====== Chart: Ventas últimos 7 días (Barras) ======
  const ventasCanvas = document.getElementById('chartVentasSemana');
  if (ventasCanvas && ventasSemana.length) {
    const labels = ventasSemana.map(v => v.fecha);
    const data = ventasSemana.map(v => Number(v.total) || 0);
    const BAR_COLORS = ['#6F4E37', '#A0522D', '#D2B48C', '#C19A6B', '#8B4513'];

    new Chart(ventasCanvas, {
      type: 'bar',
      data: {
        labels,


        datasets: [{
          label: 'Ventas del día (Bs.)',
          data,
          backgroundColor: BAR_COLORS.slice(0, data.length), // colores por barra
          borderRadius: 5, // esquinas redondeadas
        }]

      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: true },
          tooltip: {
            callbacks: {
              label: (ctx) => ` ${fmtMonto.format(ctx.raw)} Bs.`
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: (val) => fmtMonto.format(val)
            }
          }
        }
      }
    });
  }

  // ====== Chart: Top 5 Productos (Doughnut) ======
  // ====== Chart: Top 5 Productos (Doughnut) ======
  const top5Canvas = document.getElementById('chartTop5');
  if (top5Canvas && top5.length) {
    const labels = top5.map(p => p.nombre ?? 'Producto');
    const data = top5.map(p => Number(p.cantidad) || 0);

    const COLORS = ['#6F4E37', '#A0522D', '#D2B48C', '#C19A6B', '#8B4513'];

    new Chart(top5Canvas, {
      type: 'doughnut',
      data: {
        labels,
        datasets: [{
          label: 'Unidades',
          data,
          backgroundColor: COLORS.slice(0, data.length)
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: 'bottom' },
          tooltip: {
            callbacks: {
              label: (ctx) => {
                const lbl = ctx.label ?? '';
                const val = ctx.raw ?? 0;
                return ` ${lbl}: ${val} u.`;
              }
            }
          }
        },
        cutout: '55%'
      }
    });
  }

});
