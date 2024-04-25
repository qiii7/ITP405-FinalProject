<h1>
    <!-- count the result -->
</h1>

<ul>
    @foreach ($results->data as $result)
    <li>
        {{ $result->title }}
        
        <img src="https://www.artic.edu/iiif/2/{{ $result->image_id }}/full/843,/0/default.jpg" alt="{{ $result->title }}">
    </li>
    @endforeach
</ul>