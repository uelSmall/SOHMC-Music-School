@props(["author" => app_name(), "author_url" => app_url()])

<div class="pt-6">
    <div class="flex items-center justify-center text-center">
        <div class="w-1/2 text-sm text-gray-500">
            &copy; {{ date("Y") }}
            <a href="{{ $author_url }}" rel="cc:attributionURL dct:creator">{{ $author }}</a>
            All Right Reserved.
        </div>
    </div>
</div>
