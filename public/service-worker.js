const CACHE_NAME = "nominas-v1";

// Archivos base (NO metas rutas dinámicas de Laravel aquí)
const STATIC_ASSETS = [
    "/",
    "/offline.html"
];

// INSTALAR
self.addEventListener("install", event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(STATIC_ASSETS))
    );
});

// ACTIVAR
self.addEventListener("activate", event => {
    event.waitUntil(
        caches.keys().then(keys => {
            return Promise.all(
                keys.map(key => {
                    if (key !== CACHE_NAME) {
                        return caches.delete(key);
                    }
                })
            );
        })
    );
});

// FETCH (estrategia inteligente para Laravel + Inertia)
self.addEventListener("fetch", event => {
    event.respondWith(
        fetch(event.request)
            .then(response => {
                return response;
            })
            .catch(() => {
                return caches.match(event.request)
                    .then(res => res || caches.match("/offline.html"));
            })
    );
});