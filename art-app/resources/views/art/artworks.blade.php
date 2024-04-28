@extends('layout')

@section('title', 'search art')

@section('styles')
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        #search-form, #results {
            max-width: 800px;
            margin: 20px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        #results {
            text-align: center;
            /* line-height: 0.7; */
        }

        #results img {
            width: 300px;
        }

        h1, h2 {
            margin-top: 0;
        }

        #search-form h1, #results h2 {
            text-align: center;
        }

        form {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="text"] {
            margin: 15px;
            padding: 10px;
            width: 200px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-right: 10px;
        }

        button {
            margin: 25px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 20px;
        }
        /* Pagination container */
        .pagination {
            width: 800px;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
            display: inline-block;
            margin: 20px 0;
        }

        /* Pagination links */
        .pagination a {
        color: black;
        float: left;
        padding: 8px 16px;
        text-decoration: none;
        transition: background-color .3s;
        }

        /* Active page link */
        .pagination a.active {
        background-color: #4CAF50;
        color: white;
        }

        /* Hover effect on links */
        .pagination a:hover:not(.active) {
        background-color: #ddd;
        }

        /* Disabled/hidden arrows */
        .pagination a.disabled {
        pointer-events: none;
        cursor: default;
        color: #aaa;
        }


        img {
            max-width: 100%;
            height: auto;
            display: block;
            margin-top: 10px;
        }

        .image-container {
            /* border: 1px solid black; */
            width: 300px;
            margin-left: auto;
            margin-right: auto;
        }
        #no-result {
            /* border: 1px solid black; */

            text-align: center;
            width: 200px;
            margin-left: auto;
            margin-right: auto;
        }
        #random-artwork {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;

            width: 800px;
            height: auto;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 40px;
        }
        #random-image {
            width: 400px;
        }
        #random-info {
            width: 380px;
            float: left;
            margin-left: 20px;
        }
        .clearfloat {
            clear: both;
        }
        .random-content {
            display: flex;
            align-items: center;
        }
    </style>
@endsection

@section('main')
    <div id="search-form">
        <h1>Artwork Search</h1>

        <form action="{{ route('artworks.index') }}" method="GET">
            <label for="search">Enter your search query:</label><br>
            <input type="text" id="search" name="user-query" placeholder="Monet" value="{{ old('user-query') }}" required><br>

            <button type="submit">Search</button>

            @error('user-query')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </form>
    </div>

    @if (empty($query))
        <!-- random artwork -->
        <div id="random-artwork">
            <h4>Artwork of the Day (randomized)</h4>
            <div class="random-content">
                <div id="random-image">
                    <img src="https://www.artic.edu/iiif/2/{{ $randomArtwork->data->image_id }}/full/843,/0/default.jpg" alt="{{ $randomArtwork->data->title }}">
                </div>
                
                <div id="random-info">
                    <p>{{ $randomArtwork->data->title }}</p>
                    <p>{{ $randomArtwork->data->artist_title }}</p>
                    <p>{{ $randomArtwork->data->date_end  }}</p>
                    <p>{{ $randomArtwork->data->classification_title  }}</p>
                    <p>{{ $randomArtwork->data->place_of_origin  }}</p>
                </div>
            </div>
        </div>


    @else
        @if ($numOfResults->pagination->total == 0)
            <div id="no-result">
                <p>No results found for {{ $query }}.</p>
            </div>

        @else
            <div class="pagination">
                <p>
                    Available results for "{{ $query }}": {{ $numOfResults->pagination->total }}
                </p>
            </div>

            <div id="results">
                <ul>
                    <!-- id,title,image_id,artist_title,date_end,classification_title,place_of_origin -->
                    @foreach ($results->data as $result)
                    <li>
                        <div class="info">
                            <p>{{ $result->title }} by <em>{{ $result->artist_title }}</em> ({{ $result->date_end }})</p>
                        </div>
                        
                        <div class="image-container">
                            <img src="https://www.artic.edu/iiif/2/{{ $result->image_id }}/full/843,/0/default.jpg" alt="{{ $result->title }}">
                        </img> 

                        <form action="{{ route('artwork.display', ['id' => $result->id]) }}" method="GET">
                            @csrf
                            <!-- hide user-query here -->
                            <input type="hidden" name="user-query" value="{{ $query }}">
                            <button type="submit">>></button>
                        </form>

                        <hr>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="pagination">
                <!-- Previous page link -->
                <a href="{{ $currentPage > 1 ? route('your.route.name', ['page' => $currentPage - 1]) : '#' }}" class="{{ $currentPage == 1 ? 'disabled' : '' }}">&laquo;</a>

                <!-- Loop through each page -->
                @for ($page = 1; $page <= $totalPages; $page++)
                    <a href="{{ route('your.route.name', ['page' => $page]) }}" @if ($page == $currentPage) class="active" @endif>{{ $page }}</a>
                @endfor

                <!-- Next page link -->
                <a href="{{ $currentPage < $totalPages ? route('your.route.name', ['page' => $currentPage + 1]) : '#' }}" class="{{ $currentPage == $totalPages ? 'disabled' : '' }}">&raquo;</a>


                <!-- <a href="#" class="disabled">&laquo;</a>
                <a href="#" class="active">1</a>
                <a href="#">2</a>
                <a href="#">3</a>
                <a href="#">4</a>
                <a href="#">5</a>
                <a href="#">&raquo;</a> -->
            </div>
        @endif
    @endif
@endsection