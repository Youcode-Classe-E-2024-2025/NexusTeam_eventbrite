document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM loaded');
});


const searchEvents = document.getElementById('searchEvents');
const eventContainer = document.getElementById('eventContainer');


function debounce(func, delay) {
    let timer;
    return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(() => func.apply(this, args), delay);
    };
}

const fetchData = async (value) => {
    const response = await fetch('http://localhost/api/event/search', {
        body: JSON.stringify({search: value}),
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    });

    const data = await response.json();

    return data;
}

const oldHtml = eventContainer.innerHTML;

searchEvents.addEventListener('input', debounce(async (e) => {
    const value = e.target.value;

    const data = await fetchData(value);
    const events = data.events;
    console.log(data)

    if (value === '') {
        eventContainer.innerHTML = oldHtml;
        return;
    }

    eventContainer.innerHTML = '';
    events.forEach(event => {
        eventContainer.innerHTML += `
                                    <div class="group relative bg-gray-800 rounded-2xl border border-gray-700/50 shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl hover:scale-[1.02] hover:border-indigo-500/50">
                    <!-- Image Container -->
                    <div class="relative h-56 overflow-hidden">
                        <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                             src="${event.promotional_image}"
                             alt="{{ event.getTitle() }}">

                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/50 via-gray-900/30 to-transparent"></div>

                        <!-- Category Badge -->
                        <div class="absolute top-4 left-4 flex items-center gap-2 text-xs font-medium text-blue-600 bg-blue-500/10 border border-black/20 backdrop-blur-sm px-3 py-1.5 rounded-full">
                            <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                             ${event.category_name}
                        </div>

                        <!-- Price Badge -->
                        <div class="absolute top-4 right-4 px-3 py-1.5 bg-emerald-500/90 backdrop-blur-sm text-white text-sm font-semibold rounded-full shadow-lg">
                           ${event.price}
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-100 mb-3 line-clamp-2 group-hover:text-indigo-400 transition-colors">
                            ${event.title}
                        </h2>

                        <p class="text-gray-400 mb-6 line-clamp-2">
                            ${event.description}
                        </p>

                        <!-- Event Details -->
                        <div class="space-y-4 mb-6">
                            <!-- Location -->
                            <div class="flex items-center text-gray-400 group/item hover:text-gray-300">
                                <svg class="w-5 h-5 mr-3 text-gray-500 group-hover/item:text-indigo-400" fill="none"
                                     stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="text-sm truncate">${event.location}</span>
                            </div>

                            <!-- Date -->
                            <div class="flex items-center text-gray-400 group/item hover:text-gray-300">
                                <svg class="w-5 h-5 mr-3 text-gray-500 group-hover/item:text-indigo-400" fill="none"
                                     stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-sm">
                                    ${event.start_date , '-', event.end_date }
                                </span>
                            </div>

                            <!-- Capacity -->
                            <div class="flex items-center text-gray-400 group/item hover:text-gray-300">
                                <svg class="w-5 h-5 mr-3 text-gray-500 group-hover/item:text-indigo-400" fill="none"
                                     stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span class="text-sm">${event.max_capacity} spots</span>
                            </div>

<!--                            &lt;!&ndash; Tags Section &ndash;&gt;-->
<!--                            <div class="flex flex-wrap gap-2">-->
<!--                                {% if tags[event.getId()] is defined %}-->
<!--                                    {% for tag in tags[event.getId()] %}-->
<!--                                        <span class="px-3 py-1 text-sm font-semibold bg-indigo-600/50 text-white rounded-lg">-->
<!--                                            {{ tag.getName() }}-->
<!--                                        </span>-->
<!--                                    {% endfor %}-->
<!--                                {% endif %}-->
<!--                            </div>-->
                        </div>

                        <!-- Action Button -->
                        <a
                                href="/event/${event.id}"
                                class="relative block w-full px-4 py-3 text-center text-white bg-indigo-600/90 rounded-xl transition-all duration-300 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800 overflow-hidden group-hover:shadow-lg"
                        >
                            <span class="relative z-10 font-medium">View Details</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </a>
                    </div>
                </div>
                                    `
    })
}, 500));