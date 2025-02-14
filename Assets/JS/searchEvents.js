document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM loaded');

    const searchEvents = document.getElementById('searchEvents');
    const eventContainer = document.getElementById('eventContainer');
    let oldHtml = eventContainer.innerHTML;

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

        eventContainer.innerHTML = `
            <div class="col-span-full min-h-[500px] flex flex-col items-center justify-center p-16 bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-lg border border-white/20">
                <div class="relative">
                    <div class="w-16 h-16 rounded-full border-4 border-purple-100 border-t-purple-600 animate-spin"></div>
                    <div class="absolute inset-0 rounded-full border-4 border-purple-50"></div>
                </div>
                <span class="mt-6 text-lg text-gray-600 font-medium">Searching events...</span>
            </div>
        `;

        try {
            const data = await fetchData(value);
            const events = data.events || [];

            if (events.length === 0) {
                eventContainer.innerHTML = `
                    <div class="col-span-full min-h-[500px] flex flex-col items-center justify-center p-16 bg-white rounded-[2rem] shadow-sm border border-gray-100">
                        <span class="text-6xl text-gray-300 mb-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 2h12a2 2 0 012 2v16a2 2 0 01-2 2H6a2 2 0 01-2-2V4a2 2 0 012-2z M8 7h8 M8 11h8 M8 15h4"/>
                            </svg>
                        </span>
                        <p class="text-2xl text-gray-600 text-center font-medium mb-3">No events found</p>
                        <p class="text-gray-500 text-lg text-center">Try different search terms</p>
                    </div>
                `;
                return;
            }

            eventContainer.innerHTML = events.map(event => {
                const tagNames = (event.tags || '').replace(/[{}]/g, "").split(",");
                const tagHtml = tagNames
                    .filter(tag => tag)
                    .map(tag => `
                        <span class="px-4 py-2 text-sm font-medium bg-purple-50 text-purple-700 rounded-xl border border-purple-100 hover:bg-purple-100 transition-colors">
                            ${tag}
                        </span>
                    `)
                    .join("");

                return `
                    <article class="group bg-white rounded-[2rem] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100/50">
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img
                                class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                                src="${event.promotional_image || '/Assets/default_event.webp'}"
                                alt="${event.title}"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-gray-900/40 to-transparent"></div>

                            <div class="absolute top-6 left-6 px-4 py-2 rounded-full text-sm font-semibold bg-purple-500 text-white shadow-lg backdrop-blur-md bg-opacity-90">
                                <span class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                                    ${event.category_name}
                                </span>
                            </div>

                            <div class="absolute top-6 right-6 px-4 py-2 bg-white/95 backdrop-blur-md text-gray-900 text-sm font-bold rounded-full shadow-lg">
                                ${event.price > 0 ? `${parseFloat(event.price).toFixed(2)} USD` : 'Free'}
                            </div>
                        </div>

                        <div class="p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4 line-clamp-2 group-hover:text-purple-600 transition-colors leading-tight">
                                ${event.title}
                            </h2>

                            <p class="text-gray-600 mb-8 line-clamp-2 text-base leading-relaxed">
                                ${event.description}
                            </p>

                            <div class="space-y-5 mb-8">
                                <div class="flex items-center text-gray-600 group/item hover:text-gray-900 transition-colors">
                                    <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center mr-4 group-hover/item:bg-purple-100 transition-colors">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <span class="text-base truncate">${event.location}</span>
                                </div>

                                <div class="flex items-center text-gray-600 group/item hover:text-gray-900 transition-colors">
                                    <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center mr-4 group-hover/item:bg-orange-100 transition-colors">
                                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <span class="text-base">
                                        ${new Date(event.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })} - 
                                        ${new Date(event.end_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                                    </span>
                                </div>

                                <div class="flex items-center text-gray-600 group/item hover:text-gray-900 transition-colors">
                                    <div class="w-10 h-10 rounded-full bg-pink-50 flex items-center justify-center mr-4 group-hover/item:bg-pink-100 transition-colors">
                                        <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <span class="text-base">${event.max_capacity} spots available</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2 mb-8">
                                ${tagHtml}
                            </div>

                            <a
                                href="/event/${event.id}"
                                class="relative block w-full px-6 py-4 text-center text-white bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl transition-all duration-300 hover:from-purple-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 shadow-lg hover:shadow-xl transform hover:scale-[1.02] active:scale-[0.98] overflow-hidden group"
                            >
                                <span class="relative z-10 font-medium text-lg">View Details</span>
                                <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </a>
                        </div>
                    </article>
                `;
            }).join("");
        } catch (error) {
            console.error('Error fetching events:', error);
            eventContainer.innerHTML = `
                <div class="col-span-full min-h-[500px] flex flex-col items-center justify-center p-16 bg-white rounded-[2rem] shadow-sm border border-gray-100">
                    <span class="text-6xl text-red-300 mb-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </span>
                    <p class="text-2xl text-gray-600 text-center font-medium mb-3">Oops! Something went wrong</p>
                    <p class="text-gray-500 text-lg text-center">Please try again later</p>
                </div>
            `;
        }
    }, 500));
});