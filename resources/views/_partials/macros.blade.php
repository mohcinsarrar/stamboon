@php
    $path = resource_path('views/website/website.json');

    // Get the file contents
    $json = File::get($path);

    // Decode the JSON data
    $data = json_decode($json, true);

    $logo = $data['colors']['logo']

@endphp
<img src="{{asset('storage/'.$logo)}}" class="img-fluid mx-auto" style="object-fit:contain;width: 234px;height: 84px;">

