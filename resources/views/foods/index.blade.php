<h2>Food Listings</h2>

<a href="/foods/create">Add New Food</a>

@foreach($foods as $food)
    <div style="border:1px solid black; margin:10px; padding:10px;">
        <h3>{{ $food->title }}</h3>
        <p>{{ $food->description }}</p>
        <p>Price: {{ $food->price }}</p>

        <a href="/foods/{{ $food->id }}/edit">Edit</a>

        <form action="/foods/{{ $food->id }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
    </div>
@endforeach