<button
    {{ $attributes->merge(["type" => "submit", "class" => "inline-flex items-center justify-center px-4 py-2 bg-[#A6128D] cursor-pointer border border-transparent rounded-md font-semibold text-white text-center tracking-widest hover:bg-[#8C0375] active:bg-[#6B0254] focus:outline-hidden focus:border-[#8C0375] focus:ring-3 ring-[#D991CD] disabled:opacity-25 transition ease-in-out duration-150"]) }}
>
    {{ $slot }}
</button>
