<form method="POST">
    @csrf
    <input name="name" placeholder="Name">
    <input name="email" placeholder="Email">
    <textarea name="message" placeholder="Your message"></textarea>
    <button type="submit">Send</button>
</form>
