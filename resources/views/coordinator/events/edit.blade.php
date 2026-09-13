<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Event: ') }} {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('coordinator.events.update', $event) }}" enctype="multipart/form-data" class="space-y-6 max-w-4xl">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div>
                            <x-input-label for="title" :value="__('Event Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $event->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" required>{{ old('description', $event->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Category & Capacity -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="category" :value="__('Category')" />
                                <select id="category" name="category" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select Category</option>
                                    <option value="Workshop" @selected(old('category', $event->category) == 'Workshop')>Workshop</option>
                                    <option value="Competition" @selected(old('category', $event->category) == 'Competition')>Competition</option>
                                    <option value="Seminar" @selected(old('category', $event->category) == 'Seminar')>Seminar</option>
                                    <option value="Cultural" @selected(old('category', $event->category) == 'Cultural')>Cultural</option>
                                    <option value="Sports" @selected(old('category', $event->category) == 'Sports')>Sports</option>
                                    <option value="Other" @selected(old('category', $event->category) == 'Other')>Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('category')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="capacity_limit" :value="__('Capacity Limit')" />
                                <x-text-input id="capacity_limit" class="block mt-1 w-full" type="number" name="capacity_limit" :value="old('capacity_limit', $event->capacity_limit)" min="1" required />
                                <x-input-error :messages="$errors->get('capacity_limit')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Venue -->
                        <div>
                            <x-input-label for="venue" :value="__('Venue')" />
                            <x-text-input id="venue" class="block mt-1 w-full" type="text" name="venue" :value="old('venue', $event->venue)" required />
                            <x-input-error :messages="$errors->get('venue')" class="mt-2" />
                        </div>

                        <!-- Dates & Times -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="start_time" :value="__('Start Time')" />
                                <x-text-input id="start_time" class="block mt-1 w-full" type="datetime-local" name="start_time" :value="old('start_time', $event->start_time->format('Y-m-d\TH:i'))" required />
                                <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="end_time" :value="__('End Time')" />
                                <x-text-input id="end_time" class="block mt-1 w-full" type="datetime-local" name="end_time" :value="old('end_time', $event->end_time->format('Y-m-d\TH:i'))" required />
                                <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="registration_deadline" :value="__('Registration Deadline')" />
                                <x-text-input id="registration_deadline" class="block mt-1 w-full" type="datetime-local" name="registration_deadline" :value="old('registration_deadline', $event->registration_deadline->format('Y-m-d\TH:i'))" required />
                                <x-input-error :messages="$errors->get('registration_deadline')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Banner Image -->
                        <div>
                            <x-input-label for="banner_image" :value="__('Banner Image (Optional)')" />
                            @if($event->banner_image)
                                <div class="mt-2 mb-4">
                                    <img src="{{ Storage::url($event->banner_image) }}" alt="Current Banner" class="h-32 object-cover rounded">
                                    <p class="text-sm text-gray-500 mt-1">Current image. Upload a new one to replace.</p>
                                </div>
                            @endif
                            <input id="banner_image" type="file" name="banner_image" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*" />
                            <x-input-error :messages="$errors->get('banner_image')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div>
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="draft" @selected(old('status', $event->status) == 'draft')>Draft</option>
                                <option value="published" @selected(old('status', $event->status) == 'published')>Published</option>
                                <option value="cancelled" @selected(old('status', $event->status) == 'cancelled')>Cancelled</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4 pt-4">
                            <x-primary-button>{{ __('Update Event') }}</x-primary-button>
                            <a href="{{ route('coordinator.events.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
