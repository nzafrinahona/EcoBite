<!DOCTYPE html>
<html>
<head>
    <title>Add Food</title>
</head>
<body>

<h2>Add Food Listing</h2>

<form action="{{ route('foods.store') }}" method="POST">
    @csrf

    <input type="text" name="title" placeholder="Food Title"><br><br>

    <textarea name="description" placeholder="Description"></textarea><br><br>

    <input type="number" name="price" placeholder="Price"><br><br>

    <input type="number" name="quantity" placeholder="Quantity"><br><br>

    <input type="datetime-local" name="expiry_time"><br><br>

    <input type="text" name="cafeteria_name" placeholder="Cafeteria"><br><br>

    <button type="submit">Add Food</button>

</form>

</body>
</html>