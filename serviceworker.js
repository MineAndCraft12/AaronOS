var CACHE_NAME = 'aos-servicecache-' + location.host + '-v1';
var urlsToCache = [
  'servicecache.txt'
];

self.addEventListener('install', function(event){
  // Perform install steps
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function(cache){
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('fetch', function(event){
  if(event.request.url.indexOf('ms_shadows/s') > -1){
    event.respondWith(
      caches.open(CACHE_NAME).then((cache) => {
        return caches.match(event.request).then(
          (response) => {
            // Cache hit - return response
            if (response) {
              return response;
            }
            return fetch(event.request).then((netResponse) => {
              cache.put(event.request, netResponse.clone());
              return netResponse;
            });
          }
        );
      })
    );
  }
  
});