<form method="POST">
    @csrf
    <label>Rate us: <input type="number" name="rating" min="1" max="5"></label>
    <textarea name="feedback" placeholder="Your feedback"></textarea>
    <button type="submit">Submit</button>
</form>
