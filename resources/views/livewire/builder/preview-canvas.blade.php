<div class="bg-white rounded-xl shadow">

    <div class="flex justify-between items-center p-4 border-b">

        <h2 class="font-bold">

            Live Preview

        </h2>

        <span class="text-sm text-gray-500">

            {{ ucfirst($device) }}

        </span>

    </div>

    <div class="bg-gray-100 min-h-[700px] p-6">

        @if($section)

            <div class="bg-white rounded-xl shadow p-8">

                <h1 class="text-3xl font-bold">

                    {{ $section->title }}

                </h1>

                <p class="text-gray-500 mt-3">

                    {{ ucfirst($section->template) }} Template

                </p>

                <div class="mt-8 border rounded-lg p-6 bg-gray-50">

                    This is where the

                    <strong>

                        {{ $section->title }}

                    </strong>

                    section will render.

                </div>

            </div>

        @else

            <div class="text-center py-24 text-gray-400">

                Select a section

            </div>

        @endif

    </div>

</div>