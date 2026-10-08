// Menutup pesan notifikasi saat tombol silang ditekan.
document.addEventListener('click', (event) => {
    const closer = event.target.closest('[data-dismiss]');
    if (closer) {
        closer.closest('[data-alert]')?.remove();
    }

    const toggle = event.target.closest('[data-toggle]');
    if (toggle) {
        document.querySelector(toggle.dataset.toggle)?.classList.toggle('hidden');
    }

});

// Konfirmasi sebelum menghapus data.
document.addEventListener('submit', (event) => {
    const message = event.target.dataset.confirm;
    if (message && !window.confirm(message)) {
        event.preventDefault();
    }
});

/**
 * Polling status pesanan.
 *
 * Catatan jujur: ini BUKAN websocket / push notification. Halaman menanyakan
 * status ke server setiap beberapa detik, lalu me-reload saat statusnya berubah.
 */
const tracker = document.querySelector('[data-order-status-url]');
if (tracker) {
    const url = tracker.dataset.orderStatusUrl;
    let current = tracker.dataset.orderStatus;

    setInterval(async () => {
        try {
            const response = await fetch(url, { headers: { Accept: 'application/json' } });
            if (!response.ok) return;
            const data = await response.json();
            if (data.status !== current) {
                current = data.status;
                window.location.reload();
            }
        } catch (error) {
            // Diamkan saja: koneksi putus sesaat tidak perlu mengganggu siswa.
        }
    }, 7000);
}
