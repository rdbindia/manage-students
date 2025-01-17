<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Student List</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($students as $student)
            <div class="bg-white shadow-md rounded-lg overflow-hidden">

                <img src="{{ $student->getMedia('default')->first()?->original_url ?: asset('storage/profile_photos/default.jpg') }}" alt="Profile Picture" class="w-48 h-48 rounded-full object-cover mx-auto">

                <div class="p-4">
                    <h2 class="text-xl font-semibold mb-2">{{ $student->name }}</h2>
                    <p class="text-gray-600 mb-4">{{ $student->bio }}</p>
                    <p class="text-gray-800 font-medium">{{ $student->email }}</p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-6">
        {{ $students->links() }}
    </div>
</div>

</body>
</html>
