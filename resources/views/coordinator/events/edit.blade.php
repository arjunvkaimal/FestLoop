<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">
                    {{ __('Edit Event: ') }} {{ $event->title }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Modify scheduling, capacity, rules, or publication status.</p>
            </div>
            <a href="{{ route('coordinator.events.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline font-semibold">
                &larr; Back to Events
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm rounded-2xl p-6 sm:p-8">
                
                <form method="POST" action="{{ route('coordinator.events.update', $event) }}" enctype="multipart/form-data" class="space-y-6">
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
                        <x-input-label for="description" :value="__('Description (Markdown Supported)')" />
                        <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm" rows="5" required>{{ old('description', $event->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <!-- Category & Capacity -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="category" :value="__('Category')" />
                            <select id="category" name="category" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm" required>
                                <option value="cultural" @selected(old('category', $event->category) == 'cultural')>Cultural</option>
                                <option value="technical" @selected(old('category', $event->category) == 'technical')>Technical</option>
                                <option value="sports" @selected(old('category', $event->category) == 'sports')>Sports</option>
                                <option value="workshop" @selected(old('category', $event->category) == 'workshop')>Workshop</option>
                                <option value="seminar" @selected(old('category', $event->category) == 'seminar')>Seminar</option>
                                <option value="other" @selected(old('category', $event->category) == 'other')>Other</option>
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
                        <x-input-label for="venue" :value="__('Campus Venue')" />
                        <x-text-input id="venue" class="block mt-1 w-full" type="text" name="venue" :value="old('venue', $event->venue)" required />
                        <x-input-error :messages="$errors->get('venue')" class="mt-2" />
                    </div>

                    <!-- Dates & Times -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <x-input-label for="start_time" :value="__('Start Time')" />
                            <x-text-input id="start_time" class="block mt-1 w-full" type="datetime-local" name="start_time" :value="old('start_time', $event->start_time ? $event->start_time->format('Y-m-d\TH:i') : '')" required />
                            <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="end_time" :value="__('End Time')" />
                            <x-text-input id="end_time" class="block mt-1 w-full" type="datetime-local" name="end_time" :value="old('end_time', $event->end_time ? $event->end_time->format('Y-m-d\TH:i') : '')" required />
                            <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="registration_deadline" :value="__('Registration Deadline')" />
                            <x-text-input id="registration_deadline" class="block mt-1 w-full" type="datetime-local" name="registration_deadline" :value="old('registration_deadline', $event->registration_deadline ? $event->registration_deadline->format('Y-m-d\TH:i') : '')" required />
                            <x-input-error :messages="$errors->get('registration_deadline')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Banner Image -->
                    <div>
                        <x-input-label for="banner_image" :value="__('Banner Image (Optional)')" />
                        @if($event->banner_image)
                            <div class="mt-2 mb-4 p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600 inline-block">
                                <img src="{{ asset('storage/' . $event->banner_image) }}" alt="Current Banner" class="h-32 object-cover rounded">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5">Current image. Upload a new file below to replace.</p>
                            </div>
                        @endif
                        <input id="banner_image" type="file" name="banner_image" class="block mt-1 w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-950 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900 transition" accept="image/*" />
                        <x-input-error :messages="$errors->get('banner_image')" class="mt-2" />
                    </div>

                    <!-- Status -->
                    <div>
                        <x-input-label for="status" :value="__('Status')" />
                        <select id="status" name="status" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm" required>
                            <option value="draft" @selected(old('status', $event->status) == 'draft')>Draft (Hidden from catalog)</option>
                            <option value="published" @selected(old('status', $event->status) == 'published')>Published (Open for viewing & registration)</option>
                            <option value="cancelled" @selected(old('status', $event->status) == 'cancelled')>Cancelled (Marked as cancelled)</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold shadow-sm transition">
                            {{ __('Update Event') }}
                        </button>
                        <a href="{{ route('coordinator.events.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
