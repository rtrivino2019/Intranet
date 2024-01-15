{{-- <x-app-layout>

    <div class="mt-4 flex justify-center">
        <a href="/admin" class="btn">
            Manager Portal
        </a>

        <a href="/static" class="ml-4 btn">
            Settings
        </a>

        <a href="/employee" class="ml-4 btn">
            Employee Portal
        </a>

        <a href="/owner" class="ml-4 btn">
            Consultant Portal
        </a>

        <a href="/#" class="ml-4 btn">
            Chat Coming Soon
        </a>

    </div>

    <style>
        .btn {
            background-color: #38a169;
            color: #fff; /* Text color */
            font-weight: bold;
            padding: 0.5rem 1rem; /* Adjust as needed */
            border-radius: 0.25rem; /* Adjust for rounded corners */
            text-decoration: none; /* Remove underline for links */
            transition: background-color 0.3s ease; /* Smooth transition on hover */
        }

        .btn:hover {
            background-color: #2f855a; /* Darker color on hover */
        }
    </style>


</x-app-layout> --}}

<x-app-layout>

    <div class="mt-4 flex flex-col items-center">
        <a href="/admin" class="btn mb-4">
            Manager Portal
        </a>

        <a href="/static" class="btn mb-4">
            Settings
        </a>

        <a href="/employee" class="btn mb-4">
            Employee Portal
        </a>

        <a href="/owner" class="btn mb-4">
            Consultant Portal
        </a>

        <a href="/#" class="btn mb-4">
            Chat Coming Soon
        </a>
    </div>

    <style>
        .btn {
            background-color: #38a169;
            color: #fff;
            font-weight: bold;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            text-decoration: none;
            transition: background-color 0.3s ease;
            width: 200px;
            text-align: center;
        }

        .btn:hover {
            background-color: #2f855a;
        }
    </style>

</x-app-layout>
