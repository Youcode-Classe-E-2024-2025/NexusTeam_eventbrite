document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM loaded');

    const searchEvents = document.getElementById('searchEvents');
    const eventContainer = document.getElementById('eventContainer');
    let oldHtml = eventContainer.innerHTML; // Store after DOM is loaded

    function debounce(func, delay) {
        let timer;
        return function (...args) {
            clearTimeout(timer);
            timer = setTimeout(() => func.apply(this, args), delay);
        };
    }

    const fetchData = async (value) => {
        const response = await fetch('http://localhost/api/event/search', {
            body: JSON.stringify({ search: value }),
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        });

        return response.json();
    };

    searchEvents.addEventListener('input', debounce(async (e) => {
        const value = e.target.value.trim();

        if (!value) {
            eventContainer.innerHTML = oldHtml;
            return;
        }

        const data = await fetchData(value);
        const events = data.events || [];

        eventContainer.innerHTML = events.map(event => {
            const tagNames = (event.tags || '').replace(/[{}]/g, "").split(",");
            const tagHtml = tagNames
                .filter(tag => tag)
                .map(tag => `<span class="px-3 py-1 text-sm font-medium bg-orange-100 text-orange-800 rounded-lg">${tag}</span>`)
                .join("");

            return `
                <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-xl hover:scale-[1.02] hover:border-orange-200">
                    <!-- Image Container -->
                    <div class="relative h-56 overflow-hidden">
                        <img
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                            src="${event.promotional_image || '/Assets/default_event.webp'}"
                            alt="${event.title}"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>

                        <!-- Category Badge -->
                        <div class="absolute top-4 left-4 flex items-center gap-2 text-xs font-medium text-white bg-orange-500 px-3 py-1.5 rounded-full shadow-lg">
                            <span class="w-2 h-2 rounded-full bg-white"></span>
                            ${event.category_name}
                        </div>

                        <!-- Price Badge -->
                        <div class="absolute top-4 right-4 px-3 py-1.5 bg-white/90 backdrop-blur-sm text-gray-900 text-sm font-semibold rounded-full shadow-lg">
                            ${event.price > 0 ? `${parseInt(event.price).toFixed(2)} USD` : 'Free'}
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-orange-600 transition-colors">
                            ${event.title}
                        </h2>

                        <p class="text-gray-600 mb-6 line-clamp-2">
                            ${event.description}
                        </p>

                        <!-- Event Details -->
                        <div class="space-y-4 mb-6">
                            <!-- Location -->
                            <div class="flex items-center text-gray-600 group/item hover:text-gray-900">
                                <svg class="w-5 h-5 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="text-sm truncate">${event.location}</span>
                            </div>

                            <!-- Date -->
                            <div class="flex items-center text-gray-600 group/item hover:text-gray-900">
                                <svg class="w-5 h-5 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-sm">
                                    ${new Date(event.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })} - 
                                    ${new Date(event.end_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                                </span>
                            </div>

                            <!-- Capacity -->
                            <div class="flex items-center text-gray-600 group/item hover:text-gray-900">
                                <svg class="w-5 h-5 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span class="text-sm">${event.max_capacity} spots</span>
                            </div>

                            <!-- Tags Section -->
                            <div class="flex flex-wrap gap-2">
                                ${tagHtml}
                            </div>
                        </div>

                        <!-- Action Button -->
                        <a
                            href="/event/${event.id}"
                            class="relative block w-full px-4 py-3 text-center text-white bg-orange-600 rounded-xl transition-all duration-300 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 overflow-hidden group-hover:shadow-lg"
                        >
                            <span class="relative z-10 font-medium">View Details</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-orange-600 to-orange-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </a>
                    </div>
                </div>
            `;
        }).join(""); // Join all event cards into a single update
    }, 500));
});