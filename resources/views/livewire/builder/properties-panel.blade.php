<div class="bg-white rounded-xl shadow">

    <div class="border-b p-4">

        <h2 class="font-bold">

            Properties

        </h2>

    </div>

    <div class="p-5">

        @if($section)

            <div class="space-y-4">

                <div>

                    <label class="block text-sm font-medium mb-1">

                        Section Title

                    </label>

                    <input
                        type="text"
                        class="w-full border rounded-lg p-2"
                        value="{{ $section->title }}">

                </div>

                <div>

                    <label class="block text-sm font-medium mb-1">

                        Template

                    </label>

                    <input
                        type="text"
                        class="w-full border rounded-lg p-2"
                        value="{{ $section->template }}">

                </div>

                <div>

                    <label class="block text-sm font-medium mb-1">

                        Enabled

                    </label>

                    <div class="text-green-600 font-semibold">

                        {{ $section->enabled ? 'Yes' : 'No' }}

                    </div>

                </div>

                <button
                    class="w-full bg-amber-500 hover:bg-amber-600 text-white rounded-lg py-2">

                    Save Changes

                </button>

            </div>

        @else

            <div class="text-gray-400">

                Select a section to edit.

            </div>

        @endif

    </div>

</div>