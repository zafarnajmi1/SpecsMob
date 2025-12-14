@props([
    'title',
    'value_column_label',
    'items' => [],
    'header_color' => '#a4c08d',
    'even_row_color' => '#e8f5e9',
])

<div class="w-full bg-white mt-6">

    <h4 class="border-l-[11px] border-[#9e9e9e] text-[#555] uppercase font-bold text-[12px] px-4 py-3">
        {{ $title }}
    </h4>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-[12px] text-[#555]">

            <thead class="text-white" style="background-color: {{ $header_color }}">
                <tr>
                    <th class="w-[30px] py-2 pl-3">#</th>
                    <th class="py-2">Device</th>
                    <th class="py-2 pr-3 text-right">{{ $value_column_label }}</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse ($items as $index => $item)

                    @php
                        $rowStyle = $loop->even ? "background-color: {$even_row_color}" : '';
                    @endphp

                    <tr style="{{ $rowStyle }}">

                        <td class="py-1 pl-3 font-semibold">{{ $index + 1 }}.</td>

                        <td class="py-1">
                            <a href="{{ $item['url'] }}" class="hover:text-[#d50000]">
                                {{ $item['name'] }}
                            </a>
                        </td>

                        <td class="py-1 pr-3 text-right font-semibold">
                            {{ $item['value'] }}
                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="3" class="py-3 text-center text-gray-500">No data available.</td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>
