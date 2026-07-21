<div>
    <h1 class="text-2xl font-bold mb-4">Manage Courses</h1>

    <!-- Add/Edit Form -->
    <form wire:submit.prevent="{{ $courseId ? 'updateCourse' : 'addCourse' }}" class="mb-4">
        <input type="text" wire:model="name" placeholder="Course Name" class="border p-2 rounded w-full mb-2">
        @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
            {{ $courseId ? 'Update' : 'Add' }} Course
        </button>
    </form>

    <!-- Course List -->
    <table class="w-full border-collapse border border-gray-200">
        <thead>
            <tr>
                <th class="border border-gray-300 p-2">#</th>
                <th class="border border-gray-300 p-2">Name</th>
                <th class="border border-gray-300 p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($courses as $course)
                <tr>
                    <td class="border border-gray-300 p-2">{{ $course->id }}</td>
                    <td class="border border-gray-300 p-2">{{ $course->name }}</td>
                    <td class="border border-gray-300 p-2">
                        <button wire:click="editCourse({{ $course->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</button>
                        <button wire:click="deleteCourse({{ $course->id }})" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
