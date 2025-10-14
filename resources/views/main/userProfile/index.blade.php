@extends('main.layouts.app')

@section('content')
    <div class="py-12 mt-20 ">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Edit Profile (Full Width) -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-lg rounded-2xl">
                <div class="max-w-3xl mx-auto">
                    @include('main.userProfile.edit')
                </div>
            </div>

            <!-- Password & Delete (2 Columns) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-lg rounded-2xl">
                    <div class="max-w-xl mx-auto">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-lg rounded-2xl">
                    <div class="max-w-xl mx-auto">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
