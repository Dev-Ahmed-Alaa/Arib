<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Employee') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <form method="post" action="{{ route('employees.store') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="first_name" :value="__('First Name')" />
                                <input id="first_name" class="block mt-1 w-full" type="text" name="first_name" required autofocus />
                                @error('first_name')
                                <div class="text-red-500 mt-2 text-sm">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div>
                                <x-input-label for="last_name" :value="__('Last Name')" />
                                <input id="last_name" class="block mt-1 w-full" type="text" name="last_name" required autofocus />
                                @error('last_name')
                                <div class="text-red-500 mt-2 text-sm">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <input id="email" class="block mt-1 w-full" type="email" name="email" required autofocus />
                                @error('email')
                                <div class="text-red-500 mt-2 text-sm">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div>
                                <x-input-label for="phone" :value="__('Phone')" />
                                <input id="phone" class="block mt-1 w-full" type="tel" name="phone" required autofocus />
                                @error('phone')
                                <div class="text-red-500 mt-2 text-sm">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div>
                                <x-input-label for="password" :value="__('Password')" />
                                <input id="password" class="block mt-1 w-full" type="password" name="password" required autofocus />
                                @error('password')
                                <div class="text-red-500 mt-2 text-sm">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                <input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autofocus />
                                @error('password_confirmation')
                                <div class="text-red-500 mt-2 text-sm">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div>
                                <x-input-label for="salary" :value="__('Salary')" />
                                <input id="salary" class="block mt-1 w-full" type="text" name="salary" required autofocus />
                                @error('salary')
                                <div class="text-red-500 mt-2 text-sm">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div>
                                <x-input-label for="departments" :value="__('Departments')" />
                                <select id="departments" name="department_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                <div class="text-red-500 mt-2 text-sm">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div>
                                <x-input-label for="managers" :value="__('Managers')" />
                                <select id="managers" name="manager_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                    @foreach($departments as $department)
                                        @if($department->manager)
                                        <option value="{{ $department->manager->id }}">{{ $department->manager->full_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('manager_id')
                                <div class="text-red-500 mt-2 text-sm">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div>
                                <x-input-label for="profile_image" :value="__('Profile Image')" />
                                <input id="profile_image" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="file" name="image" />
                                @error('profile_image')
                                <div class="text-red-500 mt-2 text-sm">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button class="ml-3" type="submit">
                                {{ __('Create Employee') }}
                            </button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</x-app-layout>
