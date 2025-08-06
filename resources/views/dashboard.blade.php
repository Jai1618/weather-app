<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Weather Widget will go here --}}
                    <div id="weather-widget">Loading weather...</div>
                    <hr class="my-4">

                    {{-- Notes Section will go here --}}
                    <div id="notes-section">Loading notes...</div>

                    {{-- Super-Admin Only Section --}}
                    @if(Auth::user()->hasRole('super-admin'))
                        <hr class="my-4">
                        <div id="user-management-section">Loading users...</div>
                        <div id="live-activity-section">Loading live activity...</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script>
            // Store the Sanctum token from login
            // For a real app, this should be handled securely on login.
            // For now, we'll hardcode it after logging in via Postman.
            // A better way is to have a JS-based login page that stores the token in localStorage.
            const apiToken = localStorage.getItem('api_token');
            
            const apiClient = axios.create({
                baseURL: '/api',
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });

            // Function to fetch Weather
            function fetchWeather() {
                // Use a free weather API, e.g., OpenWeatherMap
                // For now, we'll just put placeholder text
                document.getElementById('weather-widget').innerHTML = `<strong>Weather:</strong> Sunny, 25°C in Mumbai`;
            }

            // Function to fetch Notes
            async function fetchNotes() {
                try {
                    const response = await apiClient.get('/notes');
                    const notes = response.data.data;
                    let notesHtml = '<h3>My Notes</h3>';
                    if(notes.length === 0) {
                        notesHtml += '<p>No notes found. Create one!</p>';
                    } else {
                        notesHtml += '<ul>';
                        notes.forEach(note => {
                            notesHtml += `<li><strong>${note.title}</strong>: ${note.content}</li>`;
                        });
                        notesHtml += '</ul>';
                    }
                    document.getElementById('notes-section').innerHTML = notesHtml;
                } catch (error) {
                    console.error('Error fetching notes:', error);
                    document.getElementById('notes-section').innerHTML = '<p class="text-red-500">Could not load notes.</p>';
                }
            }

            // On page load
            document.addEventListener('DOMContentLoaded', function() {
                if (!apiToken) {
                    alert('You are not logged in. Please log in via API and set the token.');
                    // A real app would redirect to a JS login page
                    return;
                }
                
                fetchWeather();
                fetchNotes();

                // Fetch super-admin data if applicable
                if (document.getElementById('user-management-section')) {
                     // fetchUsers(); // We will write this function
                }
            });
        </script>
        <script>
            const currentUserId = {{ Auth::id() }}; // Get logged in user's ID from Blade

            window.Echo.private(`notifications.${currentUserId}`)
                .listen('RealTimeNotification', (e) => {
                    console.log(e); // Check console to see if event is received

                    // Play a sound
                    new Audio('/sounds/notification.mp3').play(); // We will add this sound file

                    // Show a toaster notification (using a simple custom function)
                    showToast(e.message, e.type);

                    // You can also update a notification dropdown here
                });

            function showToast(message, type = 'info') {
                const toastContainer = document.getElementById('toast-container');
                if (!toastContainer) {
                    const container = document.createElement('div');
                    container.id = 'toast-container';
                    container.style.position = 'fixed';
                    container.style.top = '20px';
                    container.style.right = '20px';
                    container.style.zIndex = '1000';
                    document.body.appendChild(container);
                }

                const toast = document.createElement('div');
                toast.className = `p-4 mb-2 text-white rounded-lg shadow-lg ${type === 'success' ? 'bg-green-500' : 'bg-blue-500'}`;
                toast.innerText = message;

                document.getElementById('toast-container').appendChild(toast);

                setTimeout(() => {
                    toast.remove();
                }, 5000);
            }
        </script>
    @endpush
</x-app-layout>