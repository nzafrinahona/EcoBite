<h2>Edit Food</h2>

<form action="/foods/{{ $food->id }}" method="POST">
    @csrf
    @method('PUT')

    <input type="text" name="title" value="{{ $food->title }}" required><br>

    <textarea name="description">{{ $food->description }}</textarea><br>

    <input type="number" name="price" value="{{ $food->price }}" required><br>

    <input type="number" name="quantity" value="{{ $food->quantity }}" required><br>

    <input type="datetime-local" name="expiry_time" value="{{ $food->expiry_time }}" required><br>

    <input type="text" name="cafeteria_name" value="{{ $food->cafeteria_name }}" required><br>

    <button type="submit">Update</button>
</form>