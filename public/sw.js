const CACHE_NAME = 'lovex-v1';
const urlsToCache = [
    '/loveapp/public/',
    '/loveapp/public/index.php',
    '/loveapp/public/css/style.css',
    '/loveapp/public/js/app.js',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css',
    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js'
];

// Cài đặt - cache các file tĩnh
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            console.log('Đã cache các file tĩnh');
            return cache.addAll(urlsToCache);
        })
    );
    self.skipWaiting();
});

// Kích hoạt - xóa cache cũ
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    self.clients.claim();
});


self.addEventListener('fetch', event => {
    // ✅ Bỏ qua request không phải GET
    if (event.request.method !== 'GET') return;
    
    // ✅ Bỏ qua request từ chrome-extension, moz-extension
    const url = event.request.url;
    if (url.startsWith('chrome-extension://') || 
        url.startsWith('moz-extension://') ||
        url.startsWith('safari-extension://')) {
        return;
    }
    
    // ✅ Bỏ qua request đến PHP (dynamic)
    if (url.includes('.php')) {
        event.respondWith(
            fetch(event.request).catch(() => caches.match(event.request))
        );
        return;
    }
    
    // Cache first cho file tĩnh
    event.respondWith(
        caches.match(event.request).then(response => {
            return response || fetch(event.request).then(fetchRes => {
                // Chỉ cache response hợp lệ
                if (!fetchRes || fetchRes.status !== 200 || fetchRes.type === 'opaque') {
                    return fetchRes;
                }
                const resClone = fetchRes.clone();
                caches.open(CACHE_NAME).then(cache => cache.put(event.request, resClone));
                return fetchRes;
            });
        })
    );
});